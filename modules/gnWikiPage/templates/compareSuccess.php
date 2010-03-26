<?php include_partial('use') ?>

<?php include_partial('tools', array('gn_wiki_page' => $gn_wiki_page)) ?>

<h1>Comparing version <?php echo $version_1 ?> to <?php echo $version_2 ?></h1>

<ul class="gn-tools">
  <li><?php echo link_to('&larr;'.__('Back'), 'gnWikiPage/versions?id='.$gn_wiki_page->getId()) ?></li>
</ul>

<table class="gn-version-comparison">
  <thead>
    <tr>
      <th>&nbsp;</th>
      <th>Version <?php echo $version_1 ?><?php echo $current_version == $version_1 ? ' (Current)' : '' ?></th>
      <th>Version <?php echo $version_2 ?><?php echo $current_version == $version_2 ? ' (Current)' : '' ?></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><strong>Title</strong></td>
      <td><?php echo $versions[1]['title'] ?></td>
      <td><?php echo $versions[2]['title'] ?></td>
    </tr>
    <tr>
      <td><strong>Content</strong></td>
      <td><pre><?php echo $versions[1]['content'] ?></pre></td>
      <td><pre><?php echo $versions[2]['content'] ?></pre></td>
    </tr>
      <tr>
        <td>&nbsp;</td>
        <td><?php echo button_to('Revert to Version '. $version_1, 'gnWikiPage/Revert?id='.$gn_wiki_page->getId().'&revert_to='.$version_1, array('class' => 'button'))?></td>
        <td><?php echo button_to('Revert to Version '. $version_2, 'gnWikiPage/Revert?id='.$gn_wiki_page->getId().'&revert_to='.$version_2, array('class' => 'button'))?></td>
      </tr>
  </tbody>
</table>
