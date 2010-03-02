<?php include_partial('use') ?>
<?php include_partial('tools', array('gn_wiki_page' => $gn_wiki_page)) ?>

<h1<?php echo  !is_null($gn_wiki_page->getDeletedAt())? ' class="deleted"' : ''?>><?php echo $gn_wiki_page->getTitle() ?></h1>

<?php echo markdown_to_html($gn_wiki_page->getContent()) ?>

<dl class="gn-meta-data">
  <dt><?php echo __('Created') ?>:</dt>
  <dd><?php echo $gn_wiki_page->getCreatedAt() ?></dd>
  <dt><?php echo __('Updated') ?>:</dt>
  <dd><?php echo $gn_wiki_page->getUpdatedAt() ?></dd>
  <dt><?php echo __('Version') ?>:</dt>
  <dd><?php echo link_to($gn_wiki_page->getVersion(), 'gnWikiPage/versions?id='.$gn_wiki_page->getId()) ?></dd>
</dl>