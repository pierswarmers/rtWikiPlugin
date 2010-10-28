<?php use_helper('I18N') ?>

<?php echo link_to(__('Edit'), 'rtWikiPageAdmin/edit?id='.$rt_wiki_page->getId(), array('class' => 'rt-admin-edit-tools-trigger')) ?>
<?php include_partial('wiki_page', array('rt_wiki_page' => $rt_wiki_page, 'sf_cache_key' => $rt_wiki_page->getId())) ?>