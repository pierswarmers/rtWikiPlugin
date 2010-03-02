<ul class="gn-tools">
  <li><?php echo link_to(__('Home'), 'gnWikiPage/index') ?></li>
  <li><?php echo link_to(__('New'), 'gnWikiPage/new') ?></li>
  <?php if(isset($gn_wiki_page)): ?>
  <li><?php echo link_to(__('Edit'), 'gnWikiPage/edit?id='.$gn_wiki_page->getId()) ?></li>
  <?php endif; ?>
</ul>