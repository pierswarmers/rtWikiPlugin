<?php use_helper('I18N', 'Date', 'rtText', 'rtForm', 'rtDate') ?>
<div class="rt-wiki-page-show">
  <h1><?php echo $rt_wiki_page->getTitle() ?></h1>
  <div class="rt-page-content clearfix">
  <?php echo markdown_to_html($rt_wiki_page->getContent(), $rt_wiki_page); ?>
  </div>
  <dl class="rt-meta-data">
    <dt><?php echo __('Created') ?>:</dt>
    <dd><?php echo time_ago_in_words_abbr($rt_wiki_page->getCreatedAt(), $sf_user->getCulture()) ?></dd>
    <dt><?php echo __('Updated') ?>:</dt>
    <dd><?php echo time_ago_in_words_abbr($rt_wiki_page->getUpdatedAt(), $sf_user->getCulture()) ?></dd>
    <dt><?php echo __('Version') ?>:</dt>
    <dd><?php echo $rt_wiki_page->version ?></dd>
  </dl>
</div>