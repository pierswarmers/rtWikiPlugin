<?php

/**
 * rtWikiPageAdmin actions.
 *
 * @package    symfony
 * @subpackage rtWikiPageAdmin
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
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

  public function executeIndex(sfWebRequest $request)
  {
    $query = Doctrine::getTable('rtWikiPage')->addSiteQuery();
    $query->orderBy('page.id DESC');

    $this->rt_wiki_pages = $query->execute();
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

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $rt_wiki_page = $form->save();
      $this->clearCache($rt_wiki_page);
      
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
        $this->redirect('rt_wiki_page_show',$rt_wiki_page);
      }
      $this->redirect('rtWikiPageAdmin/index');
    }
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

  private function clearCache($rt_wiki_page = null)
  {
    $cache = $this->getContext()->getViewCacheManager();

    if ($cache)
    {
      $cache->remove('rtWikiPage/index'); // index page
      if($rt_wiki_page)
      {
        $cache->remove(sprintf('rtWikiPage/show?id=%s&slug=%s', $rt_wiki_page->getId(), $rt_wiki_page->getSlug())); // show page
        $cache->remove('@sf_cache_partial?module=rtWikiPage&action=_blog_page&sf_cache_key='.$rt_wiki_page->getId()); // show page partial.
      }
    }
  }
}
