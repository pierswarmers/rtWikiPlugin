<?php

/** @var rtWikiPage $rt_wiki_page */

use_helper('I18N', 'Date', 'rtText')

?>

<div class="rt-section rt-wiki-page">

  <div class="rt-section-tools-header rt-admin-tools">
    <?php echo link_to(__('Edit Page'), 'rtWikiPageAdmin/edit?id='.$rt_wiki_page->getId(), array('class' => 'rt-admin-edit-tools-trigger')) ?>
  </div>

  <?php if(sfConfig::get('app_rt_templates_headers_embedded', true)): ?>
  <div class="rt-section-header">
    <h1><?php echo $rt_wiki_page->getTitle() ?></h1>
  </div>
  <?php endif; ?>

  <div class="rt-section-content">
    <?php echo markdown_to_html($rt_wiki_page->getContent(), $rt_wiki_page); ?>
  </div>

</div>