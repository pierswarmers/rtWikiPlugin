<?php use_helper('I18N', 'Date', 'gnText', 'gnDate') ?>
<div class="gn-wiki-page-show">
  <h1><?php echo $gn_wiki_page->getTitle() ?></h1>
  <div class="gn-page-content clearfix">
  <?php echo markdown_to_html($gn_wiki_page->getContent(), $gn_wiki_page); ?>
  </div>
  <dl class="gn-meta-data">
    <dt><?php echo __('Created') ?>:</dt>
    <dd><?php echo time_ago_in_words_abbr($gn_wiki_page->getCreatedAt(), $sf_user->getCulture()) ?></dd>
    <dt><?php echo __('Updated') ?>:</dt>
    <dd><?php echo time_ago_in_words_abbr($gn_wiki_page->getUpdatedAt(), $sf_user->getCulture()) ?></dd>
    <dt><?php echo __('Version') ?>:</dt>
    <dd><?php echo $gn_wiki_page->version ?></dd>
  </dl>
</div>