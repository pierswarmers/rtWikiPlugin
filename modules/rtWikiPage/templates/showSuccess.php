<?php

/** @var rtWikiPage $rt_wiki_page */

use_helper('I18N','Date');

slot('rt-title', $rt_wiki_page->getTitle());

?>

<?php include_partial('site_page', array('rt_site_page' => $rt_wiki_page, 'sf_cache_key' => $rt_wiki_page->getId())) ?>
