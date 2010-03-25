<?php include_partial('use') ?>
<?php include_partial('tools', array('gn_wiki_page' => $gn_wiki_page)) ?>
<div class="gn-wiki-page">
<?php include_partial('wiki_page', array('gn_wiki_page' => $gn_wiki_page, 'sf_cache_key' => $gn_wiki_page->getId())) ?>
</div>