<?php

/*
 * This file is part of the steercms package.
 * (c) digital Wranglers <steercms@wranglers.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * rtWikiPageCacheToolkit provides cache cleaning logic.
 *
 * @package    reditype
 * @subpackage toolkit
 * @author     Piers Warmers <piers@wranglers.com.au>
 */
class rtWikiPageCacheToolkit
{
  public static function clearCache(rtWikiPage $rt_wiki_page = null)
  {
    $cache = sfContext::getInstance()->getViewCacheManager();

    if ($cache)
    {
      rtGlobalCacheToolkit::clearCache();
      
      $cache->remove('rtWikiPage/index'); // index page
      if($rt_wiki_page)
      {
        $cache->remove(sprintf('rtWikiPage/show?id=%s&slug=%s', $rt_wiki_page->getId(), $rt_wiki_page->getSlug())); // show page
        $cache->remove('@sf_cache_partial?module=rtWikiPage&action=_blog_page&sf_cache_key='.$rt_wiki_page->getId()); // show page partial.
      }
    }
  }
}