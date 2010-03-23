<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of gnWikiPluginConfigurationclass
 *
 * @author pierswarmers
 */
class gnWikiPluginConfiguration extends sfPluginConfiguration
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
      'gn_wiki_page_index',
      new sfRoute('/wiki',array('module' => 'gnWikiPage', 'action' => 'index'))
    );

    $routing->prependRoute(
      'gn_wiki_page_index_admin',
      new sfRoute('/wiki_admin',array('module' => 'gnWikiPage', 'action' => 'index_admin'))
    );

    $routing->prependRoute(
      'gn_wiki_page_show',
      new sfDoctrineRoute(
        '/wiki/:slug/:id',
          array('module' => 'gnWikiPage', 'action' => 'show'),
          array('id' => '\d+', 'sf_method' => array('get')),
          array('model' => 'gnWikiPage', 'type' => 'object')
      )
    );

    $routing->prependRoute('gn_page', new sfDoctrineRouteCollection(array(
      'name'                => 'gn_wiki_page',
      'model'               => 'gnWikiPage',
      'module'              => 'gnWikiPage',
      'prefix_path'         => 'wiki',
      'with_wildcard_routes' => true,
      'collection_actions'  => array('filter' => 'post', 'batch' => 'post', 'page' => 'get'),
      'requirements'        => array(),
    )));

    $routing->prependRoute(
            'gn_wiki_page_undelete',
            new sfDoctrineRoute(
            '/wiki/undelete/:id',
            array('module' => 'gnWikiPage', 'action' => 'undelete'),
            array('id' => '\d+', 'sf_method' => array('get')),
            array('model' => 'gnWikiPage', 'type' => 'object')
            )
    );
  }
}
?>
