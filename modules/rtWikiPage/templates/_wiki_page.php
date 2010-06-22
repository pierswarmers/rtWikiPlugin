<h1><?php echo $rt_wiki_page->getTitle() ?></h1>
<?php echo markdown_to_html($rt_wiki_page->getContent(), $rt_wiki_page); ?>