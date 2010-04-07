<?php include_partial('use'); include_partial('tools', array('gn_wiki_page' => $gn_wiki_page)); ?>
<div class="gn-wiki-page-show">
  <?php include_partial('wiki_page', array('gn_wiki_page' => $gn_wiki_page, 'sf_cache_key' => $gn_wiki_page->getId())) ?>
  <dl class="gn-meta-data">
    <dt><?php echo __('Created') ?>:</dt>
    <dd><?php echo time_ago_in_words_abbr($gn_wiki_page->getCreatedAt(), $sf_user->getCulture()) ?></dd>
    <dt><?php echo __('Updated') ?>:</dt>
    <dd><?php echo time_ago_in_words_abbr($gn_wiki_page->getUpdatedAt(), $sf_user->getCulture()) ?></dd>
    <dt><?php echo __('Version') ?>:</dt>
    <dd><?php echo link_to($gn_wiki_page->getVersion(), 'gnWikiPage/versions?id='.$gn_wiki_page->getId()) ?></dd>
  </dl>
</div>