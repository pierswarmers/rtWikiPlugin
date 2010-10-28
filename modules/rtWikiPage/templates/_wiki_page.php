<?php use_helper('rtText') ?>
<?php slot('rt-title') ?>
<?php echo $rt_wiki_page->getTitle() ?>
<?php end_slot(); ?>

<?php echo markdown_to_html($rt_wiki_page->getContent(), $rt_wiki_page); ?>