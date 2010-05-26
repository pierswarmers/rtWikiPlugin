<h1><?php echo $rt_wiki_page->getTitle() ?></h1>

<div class="rt-page-content clearfix">
<?php echo markdown_to_html($rt_wiki_page->getContent(), $rt_wiki_page); ?>
</div>