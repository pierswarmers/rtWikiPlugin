<?php slot('gn-side') ?>
<p>
  <?php echo link_to(__('Create new page'), 'gnWikiPage/new', array('class' => 'button alternate')) ?>
  <?php if(isset($gn_wiki_page)): ?>
  <?php echo link_to(__('Edit this page'), 'gnWikiPage/edit?id='.$gn_wiki_page->getId(), array('class' => 'button positive')) ?>
  <?php endif; ?>
</p>
<?php include_partial('gnSearch/form', array('form' => new gnSearchForm())) ?>
<?php end_slot(); ?>
