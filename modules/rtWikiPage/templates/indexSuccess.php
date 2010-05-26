<?php use_helper('I18N', 'Date', 'rtText', 'rtForm', 'rtDate') ?>
<div class="rt-wiki-page-index">
  <?php if(sfConfig::get('app_rt_wiki_index_title', true)): ?>
  <h1><?php echo __(sfConfig::get('app_rt_wiki_index_title', 'Wiki Index')) ?></h1>
  <?php endif; ?>
  <?php if(count($rt_wiki_pages) > 0): ?>
  <ul class="rt-wiki-page-index-list">
    <?php foreach ($rt_wiki_pages as $rt_wiki_page): ?>
    <li>
      <?php echo link_to($rt_wiki_page->getTitle(), 'rt_wiki_page_show',$rt_wiki_page) ?>
      <small>
        <strong><?php echo __('Updated') ?>:</strong>
        <?php echo time_ago_in_words_abbr($rt_wiki_page->updated_at, $sf_user->getCulture()) ?>
      </small>
    </li>
    <?php endforeach; ?>
  </ul>
  <?php else: ?>
  <p class="notice"><?php echo __('No pages available yet, please visit again later.') ?></p>
  <?php endif; ?>
</div>