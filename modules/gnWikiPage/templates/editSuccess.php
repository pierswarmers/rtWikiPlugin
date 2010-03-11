<?php include_partial('use') ?>

<h1><?php echo __('Editing Wiki Page') ?></h1>

<ul class="gn-tools">
  <li><?php echo link_to('&larr;'.__(' Back'), 'gn_wiki_page_show', $form->getObject()) ?></li>
</ul>

<?php include_partial('form', array('form' => $form)) ?>
