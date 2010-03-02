<?php include_partial('use') ?>

<?php include_partial('tools', array('gn_wiki_page' => $gn_wiki_page)) ?>

<h1>Comparing version <?php echo $version_1 ?> to <?php echo $version_2 ?></h1>

<table>
  <thead>
    <tr>
      <th>&nbsp;</th>
      <th>Version <?php echo $version_1 ?><?php echo $current_version == $version_1 ? ' (Current)' : '' ?></th>
      <th>Version <?php echo $version_2 ?><?php echo $current_version == $version_2 ? ' (Current)' : '' ?></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th>Title</th>
      <td><?php echo $versions[1]['title'] ?></td>
      <td><?php echo $versions[2]['title'] ?></td>
    </tr>
    <tr>
      <th>Content</th>
      <td><?php echo gnMarkdownToolkit::toHTML($versions[1]['content']) ?></td>
      <td><?php echo gnMarkdownToolkit::toHTML($versions[2]['content']) ?></td>
    </tr>
      <tr>
        <td>&nbsp;</td>
        <td><?php echo button_to('Revert to Version '. $version_1, 'gnWikiPage/Revert?id='.$gn_wiki_page->getId().'&revert_to='.$version_1)?></td>
        <td><?php echo button_to('Revert to Version '. $version_2, 'gnWikiPage/Revert?id='.$gn_wiki_page->getId().'&revert_to='.$version_2)?></td>
      </tr>
  </tbody>
</table>

<hr />

<?php echo link_to('Versions', 'gnWikiPage/versions?id='.$gn_wiki_page->getId()) ?>
&nbsp;
<a href="<?php echo url_for('gnWikiPage/edit?id='.$gn_wiki_page->getId()) ?>">Edit</a>
&nbsp;
<a href="<?php echo url_for('gnWikiPage/index') ?>">List</a>
