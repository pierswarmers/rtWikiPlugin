<h1<?php echo  !is_null($gn_wiki_page->getDeletedAt())? ' class="deleted"' : ''?>><?php echo $gn_wiki_page->getTitle() ?></h1>

<div class="gn-page-content clearfix">
<?php echo markdown_to_html($gn_wiki_page->getContent(), $gn_wiki_page); ?>
</div>