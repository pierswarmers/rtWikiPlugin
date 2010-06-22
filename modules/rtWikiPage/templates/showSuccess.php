<?php use_helper('I18N', 'Date', 'rtText', 'rtForm', 'rtDate') ?>

<div class="rt-wiki-page rt-show rt-primary-container rt-admin-edit-tools-panel">
  <?php echo link_to(__('Edit'), 'rtWikiPageAdmin/edit?id='.$rt_wiki_page->getId(), array('class' => 'rt-admin-edit-tools-trigger')) ?>
  <h1><?php echo $rt_wiki_page->getTitle() ?></h1>
  <div class="rt-container">
    <?php echo markdown_to_html($rt_wiki_page->getContent(), $rt_wiki_page); ?>
  </div>
</div>