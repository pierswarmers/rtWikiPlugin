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
   * Executes the index page.
   * @param sfWebRequest $request
   * @property Test $_page
   */
  public function executeIndex(sfWebRequest $request)
  {
    gnTemplateToolkit::setFrontendTemplateDir();

    $this->gn_wiki_page = Doctrine::getTable('gnWikiPage')->findOneByIsRoot(1);

    if($this->gn_wiki_page)
    {
      $this->setTemplate('show');
    }
    else
    {
      $this->gn_wiki_pages = Doctrine::getTable('gnWikiPage')->findAllPublished();
    }
  }
  
  public function executeShow(sfWebRequest $request)
  {
    gnTemplateToolkit::setFrontendTemplateDir();
    $this->gn_wiki_page = $this->getRoute()->getObject();
    $this->forward404Unless($this->gn_wiki_page);

    if(!$this->gn_wiki_page->isPublished() && !$this->isAdmin())
    {
      $this->forward('gnGuardAuth','secure');
    }

    if($this->gn_wiki_page->getIsRoot())
    {
      $this->redirect('@gn_wiki_page_index');
    }

    $this->updateResponse($this->gn_wiki_page);
  }

  private function updateResponse(gnWikiPage $page)
  {
    gnResponseToolkit::setCommonMetasFromPage($page, $this->getUser(), $this->getResponse());
  }

  private function isAdmin()
  {
    return $this->getUser()->hasCredential(sfConfig::get('app_gn_wiki_admin_credential', 'admin_wiki'));
  }
}