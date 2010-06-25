<?php

/*
 * This file is part of the gumnut package.
 * (c) 2009-2010 Piers Warmers <piers@wranglers.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * BasertWikiPageActions
 *
 * @package    rtWikiPlugin
 * @subpackage modules
 * @author     Piers Warmers <piers@wranglers.com.au>
 */
class BasertWikiPageActions extends sfActions
{
  /**
   * Executes an application defined process prior to execution of this sfAction object.
   *
   * By default, this method is empty.
   */
  public function preExecute()
  {
    sfConfig::set('app_rt_node_title', 'Wiki');
  }

  /**
   * Executes the index page.
   * @param sfWebRequest $request
   * @property Test $_page
   */
  public function executeIndex(sfWebRequest $request)
  {
    rtTemplateToolkit::setFrontendTemplateDir();

    $this->rt_wiki_page = Doctrine::getTable('rtWikiPage')->findOneByIsRoot(1);

    if($this->rt_wiki_page)
    {
      $this->setTemplate('show');
    }
    else
    {
      $this->rt_wiki_pages = Doctrine::getTable('rtWikiPage')->findAllPublished();
    }
  }
  
  public function executeShow(sfWebRequest $request)
  {
    rtTemplateToolkit::setFrontendTemplateDir();
    $this->rt_wiki_page = $this->getRoute()->getObject();
    $this->forward404Unless($this->rt_wiki_page);

    if(!$this->rt_wiki_page->isPublished() && !$this->isAdmin())
    {
      $this->forward('rtGuardAuth','secure');
    }

    rtSiteToolkit::checkSiteReference($this->rt_wiki_page);
    
    if($this->rt_wiki_page->getIsRoot())
    {
      $this->redirect('@rt_wiki_page_index');
    }

    $this->updateResponse($this->rt_wiki_page);
  }

  private function updateResponse(rtWikiPage $page)
  {
    rtResponseToolkit::setCommonMetasFromPage($page, $this->getUser(), $this->getResponse());
  }

  private function isAdmin()
  {
    return $this->getUser()->hasCredential(sfConfig::get('app_rt_wiki_admin_credential', 'admin_wiki'));
  }
}