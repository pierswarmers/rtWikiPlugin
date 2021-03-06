<?php

/*
 * This file is part of the rtWikiPagePlugin package.
 *
 * (c) 2006-2011 digital Wranglers <steercms@wranglers.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * rtWikiPageAdminActions
 *
 * @package    rtWikiPagePlugin
 * @subpackage modules
 * @author     Piers Warmers <piers@wranglers.com.au>
 * @author     Konny Zurcher <konny@wranglers.com.au>
 */
class rtWikiPageAdminActions extends sfActions
{
  private $_rt_wiki_page;

  public function getrtWikiPage(sfWebRequest $request)
  {
    $this->forward404Unless($rt_wiki_page = Doctrine::getTable('rtWikiPage')->find(array($request->getParameter('id'))), sprintf('Object rt_wiki_page does not exist (%s).', $request->getParameter('id')));
    return $rt_wiki_page;
  }

  public function preExecute()
  {
    rtTemplateToolkit::setTemplateForMode('backend');
  }

  public function executeShow(sfWebRequest $request)
  {
    rtSiteToolkit::siteRedirect($this->getrtWikiPage($request));
  }

  public function executeIndex(sfWebRequest $request)
  {
    $query = Doctrine::getTable('rtWikiPage')->getQuery();
    $query->orderBy('page.created_at DESC');

    $this->pager = new sfDoctrinePager(
      'rtWikiPage',
      $this->getCountPerPage($request)
    );

    $this->pager->setQuery($query);
    $this->pager->setPage($request->getParameter('page', 1));
    $this->pager->init();

    $this->stats = $this->stats();
  }

  private function stats()
  {
    // Dates
    $date_now = date("Y-m-d H:i:s");

    // SQL queries
    $con = Doctrine_Manager::getInstance()->getCurrentConnection();

    $result_wiki_pages_total               = $con->fetchAssoc("select count(id) as count from rt_wiki_page");
    $result_wiki_pages_total_published     = $con->fetchAssoc("select count(id) as count from rt_wiki_page where published = 1 and (published_from <= '".$date_now."' OR published_from IS NULL) and (published_to > '".$date_now."' OR published_to IS NULL)");

    // Create array
    $stats = array();
    $stats['total']            = $result_wiki_pages_total[0] != '' ? $result_wiki_pages_total[0] : 0;
    $stats['total_published']  = $result_wiki_pages_total_published[0] != '' ? $result_wiki_pages_total_published[0] : 0;

    return $stats;
  }

  private function getCountPerPage(sfWebRequest $request)
  {
    $count = sfConfig::get('app_rt_admin_pagination_limit', 50);
    if($request->hasParameter('show_more'))
    {
      $count = sfConfig::get('app_rt_admin_pagination_per_page_multiple', 2) * $count;
    }

    return $count;
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new rtWikiPageForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));
    $this->form = new rtWikiPageForm();
    $this->processForm($request, $this->form);
    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $rt_wiki_page = $this->getrtWikiPage($request);
    $this->form = new rtWikiPageForm($rt_wiki_page);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $rt_wiki_page = $this->getrtWikiPage($request);
    $this->form = new rtWikiPageForm($rt_wiki_page);
    $this->processForm($request, $this->form);
    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $rt_wiki_page = $this->getrtWikiPage($request);
    $rt_wiki_page->delete();
    $this->clearCache($rt_wiki_page);
    $this->getDispatcher($request)->notify(new sfEvent($this, 'doctrine.admin.delete_object', array('object' => $rt_wiki_page)));
    $this->redirect('rtWikiPageAdmin/index');
  }

  public function executeVersions(sfWebRequest $request)
  {
    $this->rt_wiki_page = $this->getrtWikiPage($request);
    $this->rt_wiki_page_versions = Doctrine::getTable('rtWikiPageVersion')->findById($this->rt_wiki_page->getId());
  }

  public function executeCompare(sfWebRequest $request)
  {
    $this->rt_wiki_page = $this->getrtWikiPage($request);
    $this->current_version = $this->rt_wiki_page->version;

    if(!$request->hasParameter('version1') || !$request->hasParameter('version2'))
    {
      $this->getUser()->setFlash('error', 'Please select two versions to compare.', false);
      $this->redirect('rtWikiPageAdmin/versions?id='.$this->rt_wiki_page->getId());
    }

    $this->version_1 = $request->getParameter('version1');
    $this->version_2 = $request->getParameter('version2');
    $this->versions = array();

    $this->versions[1] = array(
      'title' => $this->rt_wiki_page->revert($this->version_1)->title,
      'content' => $this->rt_wiki_page->revert($this->version_1)->content,
      'description' => $this->rt_wiki_page->revert($this->version_1)->description,
      'updated_at' => $this->rt_wiki_page->revert($this->version_1)->updated_at
    );
    $this->versions[2] = array(
      'title' => $this->rt_wiki_page->revert($this->version_2)->title,
      'content' => $this->rt_wiki_page->revert($this->version_2)->content,
      'description' => $this->rt_wiki_page->revert($this->version_1)->description,
      'updated_at' => $this->rt_wiki_page->revert($this->version_1)->updated_at
    );
  }

  public function executeRevert(sfWebRequest $request)
  {
    $this->rt_wiki_page = $this->getrtWikiPage($request);
    $this->rt_wiki_page->revert($request->getParameter('revert_to'));
    $this->rt_wiki_page->save();
    $this->getUser()->setFlash('notice', 'Reverted to version ' . $request->getParameter('revert_to'), false);
    $this->clearCache($this->rt_wiki_page);
    $this->redirect('rtWikiPageAdmin/edit?id='.$this->rt_wiki_page->getId());
  }

  public function executeToggle(sfWebRequest $request)
  {
    $rt_wiki_page = Doctrine_Core::getTable('rtWikiPage')->find(array($request->getParameter('id')));
    if(!$rt_wiki_page)
    {
      $this->status = 'error';
      return sfView::SUCCESS;
    }

    $rt_wiki_page->setPublished(!$rt_wiki_page->getPublished());
    $this->status = $rt_wiki_page->getPublished() ? 'activated' : 'deactivated';
    $rt_wiki_page->save();
    $this->clearCache($rt_wiki_page);
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $rt_wiki_page = $form->save();
      $this->clearCache($rt_wiki_page);

      $this->getDispatcher($request)->notify(new sfEvent($this, 'doctrine.admin.save_object', array('object' => $rt_wiki_page)));

      if($rt_wiki_page->getIsRoot())
      {
        // Run a clean on other wiki pages marked as root. Only one root page allowed.
        $rt_wiki_pages = Doctrine::getTable('rtWikiPage')->findByIsRoot(1);

        if($rt_wiki_pages)
        {
          foreach($rt_wiki_pages as $page)
          {
            if($page->getId() != $rt_wiki_page->getId())
            {
              $page->setIsRoot(0);
              $page->save();
            }
          }
        }
      }
      
      $action = $request->getParameter('rt_post_save_action', 'index');

      if($action == 'edit')
      {
        $this->redirect('rtWikiPageAdmin/edit?id='.$rt_wiki_page->getId());
      }elseif($action == 'show')
      {
        rtSiteToolkit::siteRedirect($rt_wiki_page);
      }
      $this->redirect('rtWikiPageAdmin/index');
    }
    $this->getUser()->setFlash('default_error', true, false);
  }

  /**
   * Clean the cache relating to rtWikiPage
   *
   * @param rtWikiPage $rt_wiki_page
   */
  private function clearCache(rtWikiPage $rt_wiki_page = null)
  {
    rtWikiPageCacheToolkit::clearCache($rt_wiki_page);
  }

  /**
   * @return sfEventDispatcher
   */
  protected function getDispatcher(sfWebRequest $request)
  {
    return ProjectConfiguration::getActive()->getEventDispatcher(array('request' => $request));
  }
}
