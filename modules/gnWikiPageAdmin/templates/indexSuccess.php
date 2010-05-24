<?php use_helper('I18n', 'gnAdmin') ?>

<h1><?php echo __('Listing Wiki Pages') ?></h1>

<table>
  <thead>
    <tr>
      <th><?php echo __('Title') ?></th>
      <th><?php echo __('Published') ?></th>
      <th><?php echo __('Version') ?></th>
      <th><?php echo __('Created at') ?></th>
      <th>&nbsp;</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($gn_wiki_pages as $gn_wiki_page): ?>
    <tr>
      <td><a href="<?php echo url_for('gnWikiPageAdmin/edit?id='.$gn_wiki_page->getId()) ?>"><?php echo $gn_wiki_page->getTitle() ?></a></td>
      <td><?php echo gn_nice_boolean($gn_wiki_page->getPublished()) ?></td>
      <td><?php echo link_to_if($gn_wiki_page->version > 1, $gn_wiki_page->version, 'gnWikiPageAdmin/versions?id='.$gn_wiki_page->getId()) ?></td>
      <td><?php echo $gn_wiki_page->getCreatedAt() ?></td>
      <td>
        <ul class="gn-admin-tools">
          <li><?php echo gn_button_show(url_for('gn_wiki_page_show', $gn_wiki_page)) ?></li>
          <li><?php echo gn_button_edit(url_for('gnWikiPageAdmin/edit?id='.$gn_wiki_page->getId())) ?></li>
          <li><?php echo gn_button_delete(url_for('gnWikiPageAdmin/delete?id='.$gn_wiki_page->getId())) ?></li>
        </ul>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php slot('gn-side') ?>
<p><?php echo button_to(__('Create new wiki page'), 'gnWikiPageAdmin/new', array('class' => 'button positive')) ?></p>
<?php end_slot(); ?>