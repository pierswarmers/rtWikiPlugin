<?php include_partial('use'); include_partial('tools'); ?>
<div class="gn-wiki-page-index">
  <?php if(sfConfig::get('app_gn_wiki_index_title', true)): ?>
  <h1><?php echo __(sfConfig::get('app_gn_wiki_index_title', 'Blog Index')) ?></h1>
  <?php endif; ?>
  <?php if(count($gn_wiki_pages) > 0): ?>
  <ul class="gn-wiki-page-index-list">
    <?php foreach ($gn_wiki_pages as $gn_wiki_page): ?>
    <li<?php echo !is_null($gn_wiki_page->getDeletedAt())  ? ' class="deleted"' : '' ?>>
      <?php $date = is_null($gn_wiki_page->getDeletedAt()) ? $gn_wiki_page->getUpdatedAt() : $gn_wiki_page->getDeletedAt(); ?>
      <?php echo link_to($gn_wiki_page->getTitle(), 'gn_wiki_page_show',$gn_wiki_page) ?>
      <small>
        <strong><?php echo !is_null($gn_wiki_page->getDeletedAt())  ? __('Deleted') : __('Updated') ?>:</strong>
        <?php echo time_ago_in_words_abbr($date, $sf_user->getCulture()) ?>
      </small>
    </li>
    <?php endforeach; ?>
  </ul>
  <?php else: ?>
  <p class="notice"><?php echo __('No pages available yet, please visit again later.') ?></p>
  <?php endif; ?>
</div>