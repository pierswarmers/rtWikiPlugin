<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of rtWikiPluginConfigurationclass
 *
 * @author pierswarmers
 */
class rtWikiPluginConfiguration extends sfPluginConfiguration
{
  public function initialize()
  {
    $this->dispatcher->connect('routing.load_configuration', array($this, 'listenToRoutingLoadConfiguration'));
  }

  /**
   * Enable the required routes, carefully checking that no customisation are present.
   * 
   * @param sfEvent $event
   */
  public function listenToRoutingLoadConfiguration(sfEvent $event)
  {
    $routing = $event->getSubject();

    $routing->prependRoute(
      'rt_wiki_page_index',
      new sfRoute('/wiki',array('module' => 'rtWikiPage', 'action' => 'index'))
    );

    $routing->prependRoute(
      'rt_wiki_page_show',
      new sfDoctrineRoute(
        '/wiki/:slug/:id',
          array('module' => 'rtWikiPage', 'action' => 'show'),
          array('id' => '\d+', 'sf_method' => array('get')),
          array('model' => 'rtWikiPage', 'type' => 'object')
      )
    );
  }
}