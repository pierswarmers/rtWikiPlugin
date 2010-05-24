<?php

/**
 * gnWikiPageAdmin actions.
 *
 * @package    symfony
 * @subpackage gnWikiPageAdmin
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class gnWikiPageAdminActions extends sfActions
{
  private $_gn_wiki_page;

  public function getGnWikiPage(sfWebRequest $request)
  {
    $this->forward404Unless($gn_wiki_page = Doctrine::getTable('gnWikiPage')->find(array($request->getParameter('id'))), sprintf('Object gn_wiki_page does not exist (%s).', $request->getParameter('id')));
    return $gn_wiki_page;
  }

  public function preExecute()
  {
    gnTemplateToolkit::setTemplateForMode('backend');
  }

  public function executeIndex(sfWebRequest $request)
  {
    $query = Doctrine::getTable('gnWikiPage')->addSiteQuery();
    $query->orderBy('page.id DESC');

    $this->gn_wiki_pages = $query->execute();
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new gnWikiPageForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new gnWikiPageForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $gn_wiki_page = $this->getGnWikiPage($request);
    $this->form = new gnWikiPageForm($gn_wiki_page);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $gn_wiki_page = $this->getGnWikiPage($request);
    $this->form = new gnWikiPageForm($gn_wiki_page);

    $this->processForm($request, $this->form);
    $this->clearCache($request);
    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $gn_wiki_page = $this->getGnWikiPage($request);
    $gn_wiki_page->delete();
    $this->clearCache($request);

    $this->redirect('gnWikiPageAdmin/index');
  }

  public function executeUndelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $gn_wiki_page = $this->getGnWikiPage($request);
    $gn_wiki_page->undelete();
    $this->clearCache($request);

    $this->redirect('gnWikiPageAdmin/index');
  }

  public function executeVersions(sfWebRequest $request)
  {
    $this->gn_wiki_page = $this->getGnWikiPage($request);
    $this->gn_wiki_page_versions = Doctrine::getTable('gnWikiPageVersion')->findById($this->gn_wiki_page->getId());
  }

  public function executeCompare(sfWebRequest $request)
  {
    $this->gn_wiki_page = $this->getGnWikiPage($request);
    $this->current_version = $this->gn_wiki_page->version;

    if(!$request->hasParameter('version1') || !$request->hasParameter('version2'))
    {
      $this->getUser()->setFlash('error', 'Please select two versions to compare.', false);
      $this->redirect('gnWikiPage/versions?id='.$this->gn_wiki_page->getId());
    }

    $this->version_1 = $request->getParameter('version1');
    $this->version_2 = $request->getParameter('version2');
    $this->versions = array();

    $this->versions[1] = array(
      'title' => $this->gn_wiki_page->revert($this->version_1)->title,
      'content' => $this->gn_wiki_page->revert($this->version_1)->content,
      'description' => $this->gn_wiki_page->revert($this->version_1)->description,
      'updated_at' => $this->gn_wiki_page->revert($this->version_1)->updated_at
    );
    $this->versions[2] = array(
      'title' => $this->gn_wiki_page->revert($this->version_2)->title,
      'content' => $this->gn_wiki_page->revert($this->version_2)->content,
      'description' => $this->gn_wiki_page->revert($this->version_1)->description,
      'updated_at' => $this->gn_wiki_page->revert($this->version_1)->updated_at
    );
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $gn_wiki_page = $form->save();

      if($gn_wiki_page->getIsRoot())
      {
        // Run a clean on other wiki pages marked as root. Only one root page allowed.
        $gn_wiki_pages = Doctrine::getTable('gnWikiPage')->findByIsRoot(1);

        if($gn_wiki_pages and count($gn_wiki_pages > 1))
        {
          foreach($gn_wiki_pages as $page)
          {
            if($page->getId() != $gn_wiki_page->getId())
            {
              $page->setIsRoot(0);
              $page->save();
            }
          }
        }
      }
      $this->redirect('gnWikiPageAdmin/index');
    }
  }

  public function executeRevert(sfWebRequest $request)
  {
    $this->gn_wiki_page = $this->getGnWikiPage($request);
    $this->gn_wiki_page->revert($request->getParameter('revert_to'));
    $this->gn_wiki_page->save();
    $this->getUser()->setFlash('notice', 'Reverted to version ' . $request->getParameter('revert_to'), false);
    $this->clearCache($request);
    $this->redirect('gnWikiPageAdmin/edit?id='.$this->gn_wiki_page->getId());
  }

  private function clearCache($request)
  {
    $cache = $this->getContext()->getViewCacheManager();
    $gn_wiki_page = $this->getGnWikiPage($request);
    if ($cache)
    {
      $cache->remove('gnWikiPage/index?sf_format=*');
      $cache->remove(sprintf('gnWikiPage/show?id=%s&slug=%s', $gn_wiki_page->getId(), $gn_wiki_page->getSlug()));
      $cache->remove('@sf_cache_partial?module=gnWikiPage&action=_wiki_page&sf_cache_key='.$gn_wiki_page->getId());
    }
  }
}
