<?php use_helper('I18N', 'rtAdmin') ?>

<h1><?php echo __('Listing Wiki Pages') ?></h1>

<?php slot('rt-tools') ?>
<?php include_partial('rtAdmin/standard_modal_tools', array('object' => new rtWikiPage))?>
<?php end_slot(); ?>

<?php include_partial('rtAdmin/flashes') ?>

<script type="text/javascript">
  $(function() {
    enablePublishToggle('<?php echo url_for('rtWikiPageAdmin/toggle') ?>');
  });
</script>

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
    <?php foreach ($pager->getResults() as $rt_wiki_page): ?>
    <tr>
      <td><a href="<?php echo url_for('rtWikiPageAdmin/edit?id='.$rt_wiki_page->getId()) ?>"><?php echo $rt_wiki_page->getTitle() ?></a></td>
      <td class="rt-admin-publish-toggle">
        <?php echo rt_nice_boolean($rt_wiki_page->getPublished()) ?>
        <div style="display:none;"><?php echo $rt_wiki_page->getId() ?></div>
      </td>
      <td><?php echo link_to_if($rt_wiki_page->version > 1, $rt_wiki_page->version, 'rtWikiPageAdmin/versions?id='.$rt_wiki_page->getId()) ?></td>
      <td><?php echo $rt_wiki_page->getCreatedAt() ?></td>
      <td>
        <ul class="rt-admin-tools">
          <li><?php echo rt_button_show(url_for('rtWikiPageAdmin/show?id='.$rt_wiki_page->getId())) ?></li>
          <li><?php echo rt_button_edit(url_for('rtWikiPageAdmin/edit?id='.$rt_wiki_page->getId())) ?></li>
          <li><?php echo rt_button_delete(url_for('rtWikiPageAdmin/delete?id='.$rt_wiki_page->getId())) ?></li>
        </ul>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php include_partial('rtAdmin/pagination', array('pager' => $pager)); ?>