<?php slot('gn-side') ?>
<ul class="gn-tools">
  <li><?php echo link_to(__('Create a new wiki page'), 'gnWikiPage/new', array('class' => 'button positive')) ?></li>
  <?php if(isset($gn_wiki_page)): ?>
  <li>Or, <?php echo link_to(__('edit the current page'), 'gnWikiPage/edit?id='.$gn_wiki_page->getId()) ?>.</li>
  <?php endif; ?>
</ul>
<?php include_partial('gnSearch/form', array('form' => new gnSearchForm())) ?>
<?php end_slot(); ?>
