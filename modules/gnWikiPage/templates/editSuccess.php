<?php include_partial('use') ?>

<ul class="gn-tools">
  <li><?php echo link_to('&larr;'.__(' Back'), 'gn_wiki_page_show', $form->getObject()) ?></li>
</ul>

<?php include_partial('form', array('form' => $form)) ?>
