<?php include_partial('use'); include_partial('tools'); ?>
<div class="gn-wiki-page-index">
  <?php if(sfConfig::get('app_gn_wiki_index_title', true)): ?>
  <h1><?php echo __(sfConfig::get('app_gn_wiki_index_title', 'Blog Index')) ?></h1>
  <?php endif; ?>
  <?php if(count($gn_wiki_pages) > 0): ?>
  <ul class="gn-wiki-page-index-list">
    <?php foreach ($gn_wiki_pages as $gn_wiki_page): ?>
    <li>
      <?php echo link_to($gn_wiki_page->getTitle(), 'gn_wiki_page_show',$gn_wiki_page) ?>
      <small>
        <strong><?php echo __('Updated') ?>:</strong>
        <?php echo time_ago_in_words_abbr($gn_wiki_page->updated_at, $sf_user->getCulture()) ?>
      </small>
    </li>
    <?php endforeach; ?>
  </ul>
  <?php else: ?>
  <p class="notice"><?php echo __('No pages available yet, please visit again later.') ?></p>
  <?php endif; ?>
</div>