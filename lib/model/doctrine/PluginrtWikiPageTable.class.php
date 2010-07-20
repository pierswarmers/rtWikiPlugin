<?php
/**
 */
class PluginrtWikiPageTable extends rtPageTable
{
  /**
   * Enhanced the default method to include published and site query.
   * @param Doctrine_Query $query 
   */
  public function findOneByIsRoot(Doctrine_Query $query = null)
  {
    $query = $this->addSiteQuery($query);
    $query = $this->addPublishedQuery($query);
    return $query->fetchOne();
  }
}