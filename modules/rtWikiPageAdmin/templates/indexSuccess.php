<?php use_helper('I18N', 'rtAdmin') ?>

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
    <?php foreach ($rt_wiki_pages as $rt_wiki_page): ?>
    <tr>
      <td><a href="<?php echo url_for('rtWikiPageAdmin/edit?id='.$rt_wiki_page->getId()) ?>"><?php echo $rt_wiki_page->getTitle() ?></a></td>
      <td><?php echo rt_nice_boolean($rt_wiki_page->getPublished()) ?></td>
      <td><?php echo link_to_if($rt_wiki_page->version > 1, $rt_wiki_page->version, 'rtWikiPageAdmin/versions?id='.$rt_wiki_page->getId()) ?></td>
      <td><?php echo $rt_wiki_page->getCreatedAt() ?></td>
      <td>
        <ul class="rt-admin-tools">
          <li><?php echo rt_button_show(url_for('rt_wiki_page_show', $rt_wiki_page)) ?></li>
          <li><?php echo rt_button_edit(url_for('rtWikiPageAdmin/edit?id='.$rt_wiki_page->getId())) ?></li>
          <li><?php echo rt_button_delete(url_for('rtWikiPageAdmin/delete?id='.$rt_wiki_page->getId())) ?></li>
        </ul>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php slot('rt-side') ?>
<p><?php echo button_to(__('Create new wiki page'), 'rtWikiPageAdmin/new', array('class' => 'button positive')) ?></p>
<?php end_slot(); ?>