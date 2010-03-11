<?php include_partial('use') ?>

<h1><?php echo __('New Wiki Page') ?></h1>

<ul class="gn-tools">
  <li><?php echo link_to('&larr;'.__(' Back'), 'gnWikiPage/index') ?></li>
</ul>

<?php include_partial('form', array('form' => $form)) ?>