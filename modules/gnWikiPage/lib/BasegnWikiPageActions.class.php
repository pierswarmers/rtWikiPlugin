<?php

/*
 * This file is part of the gumnut package.
 * (c) 2009-2010 Piers Warmers <piers@wranglers.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * BasegnWikiPageActions
 *
 * @package    gnWikiPlugin
 * @subpackage modules
 * @author     Piers Warmers <piers@wranglers.com.au>
 */
class BasegnWikiPageActions extends sfActions
{
  /**
   * Executes an application defined process prior to execution of this sfAction object.
   *
   * By default, this method is empty.
   */
  public function preExecute()
  {
    sfConfig::set('app_gn_node_title', 'Wiki');
  }

  /**
   *
   * @param sfWebRequest $request
   * @property Test $_page
   */
  public function executeIndex(sfWebRequest $request)
  {
    $this->gn_wiki_pages = Doctrine::getTable('gnWikiPage')->findAll();
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->gn_wiki_page = $this->getRoute()->getObject();
    $this->forward404Unless($this->gn_wiki_page);
    $this->updateResponse($this->gn_wiki_page);
    $this->clearCache();
  }

  private function updateResponse(gnWikiPage $page)
  {
    gnResponseToolkit::setCommonMetasFromPage($page, $this->getUser(), $this->getResponse());
  }

  public function executeRevert(sfWebRequest $request)
  {
    $this->gn_wiki_page = $this->getRoute()->getObject();
    $this->gn_wiki_page->Translation[$this->getUser()->getCulture()]->revert($request->getParameter('revert_to'));
    $this->gn_wiki_page->save();
    $this->getUser()->setFlash('notice', 'Reverted to version ' . $request->getParameter('revert_to'), false);
    $this->clearCache();
    $this->redirect('gn_wiki_page_show',$this->gn_wiki_page);
  }

  public function executeVersions(sfWebRequest $request)
  {
    $this->gn_wiki_page = $this->getRoute()->getObject();
  }

  public function executeCompare(sfWebRequest $request)
  {
    $this->gn_wiki_page = $this->getRoute()->getObject();
    $this->current_version = $this->gn_wiki_page->Translation[$this->getUser()->getCulture()]->version;

    if(!$request->hasParameter('version1') || !$request->hasParameter('version2'))
    {
      $this->getUser()->setFlash('error', 'Please select two versions to compare.', false);
      $this->redirect('gnWikiPage/versions?id='.$this->gn_wiki_page->getId());
    }

    $this->version_1 = $request->getParameter('version1');
    $this->version_2 = $request->getParameter('version2');
    $t = $this->gn_wiki_page->Translation[$this->getUser()->getCulture()];
    $this->versions = array();

    $this->versions[1] = array(
      'title' => $t->revert($this->version_1)->title,
      'content' => $t->revert($this->version_1)->content
    );
    $this->versions[2] = array(
      'title' => $t->revert($this->version_2)->title,
      'content' => $t->revert($this->version_2)->content
    );
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new gnWikiPageForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    //$request->checkCSRFProtection();
    $this->form = new gnWikiPageForm();
    $this->processForm($request, $this->form);
    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $gn_wiki_page = $this->getRoute()->getObject();
    $this->form = new gnWikiPageForm($gn_wiki_page);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    //$request->checkCSRFProtection();
    $this->form = new gnWikiPageForm($this->getRoute()->getObject());
    $this->processForm($request, $this->form);
    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    //$request->checkCSRFProtection();
    $this->clearCache();
    $this->getRoute()->getObject()->delete();
    $this->redirect('gnWikiPage/index');
  }

  public function executeUndelete(sfWebRequest $request)
  {
    //$request->checkCSRFProtection();
    $this->clearCache();
    $this->getRoute()->getObject()->undelete();
    $this->redirect('gn_wiki_page_show', $this->getRoute()->getObject());
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $was_created = $form->getObject()->isNew();
      $form->save();
      $this->clearCache();
      if($was_created)
      {
        $this->getUser()->setFlash('success', 'Page created successfully.');
        $this->redirect('gn_wiki_page_edit', $form->getObject());
      }
      $this->redirect('gn_wiki_page_show', $form->getObject());
    }
    $this->getUser()->setFlash('error', 'Some errors were found, see below for details.');
  }

  private function clearCache()
  {
    $cache = $this->getContext()->getViewCacheManager();

    if ($cache)
    {
      $cache->remove('gnWikiPage/index?sf_format=*');
      $cache->remove(sprintf('gnWikiPage/show?id=%s&slug=%s', $this->getRoute()->getObject()->getId(), $this->getRoute()->getObject()->getSlug()));
    }
  }
}