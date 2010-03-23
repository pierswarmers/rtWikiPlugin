<?php
include_partial('use');
include_partial('tools');
?>

<h1><?php echo __('Wiki Index') ?></h1>

<?php if(count($gn_wiki_pages) > 0): ?>
<ul>
  <?php foreach ($gn_wiki_pages as $gn_wiki_page): ?>
  <li<?php echo !is_null($gn_wiki_page->getDeletedAt())  ? ' class="deleted"' : '' ?>>
    <?php $date = is_null($gn_wiki_page->getDeletedAt()) ? $gn_wiki_page->getUpdatedAt() : $gn_wiki_page->getDeletedAt(); ?>
    <?php echo link_to($gn_wiki_page->getTitle(), 'gn_wiki_page_show',$gn_wiki_page) ?>
    <small>
      <strong><?php echo !is_null($gn_wiki_page->getDeletedAt())  ? __('Deleted') : __('Updated') ?>:</strong>
      <?php echo format_date($date, 'U', $sf_user->getCulture()) ?>
    </small>
  </li>
  <?php endforeach; ?>
</ul>
<?php else: ?>
<p class="notice"><?php echo __('No pages available yet, please visit again later.') ?></p>
<?php endif; ?>