<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<?php use_stylesheet('/gnCorePlugin/vendor/jquery/css/tools/jquery.tools.css'); ?>
<?php use_javascript('/gnCorePlugin/js/jquery-1.4.2.min.js') ?>
<?php use_javascript('/gnCorePlugin/vendor/jquery/js/jquery.tools.min.js', 'last'); ?>
<?php use_helper('I18N', 'GnForm') ?>

<form id="gnWikiPageForm" action="<?php echo url_for('gnWikiPage/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
  <fieldset>
    <legend><?php echo __('Main Content') ?></legend>
    <?php if (!$form->getObject()->isNew()): ?>
    <input type="hidden" name="sf_method" value="put" />
    <?php endif; ?>

    <?php echo $form->renderGlobalErrors() ?>
    <?php echo render_form_row($form[$sf_user->getCulture()]['title'], array('wide' => true)) ?>
    <?php echo render_form_row($form['published']) ?>
    <?php echo render_form_row($form[$sf_user->getCulture()]['content'], array('wide' => true)) ?>
    <a href="#" rel="#gnLinkPanel">Insert Link</a>
  </fieldset>

  <fieldset class="gn-form-collapse">
    <legend onclick="$('#gnPanelTaggingSEO').slideToggle();"><?php echo __('Tagging and SEO') ?></legend>
    <div id="gnPanelTaggingSEO" style="display:none;">
    <?php echo render_form_row($form[$sf_user->getCulture()]['description'], array('wide' => true)) ?>
    <?php echo render_form_row($form['searchable']) ?>
    <?php echo render_form_row($form[$sf_user->getCulture()]['tags']) ?>
    </div>
  </fieldset>

  <fieldset class="gn-form-collapse">
    <legend onclick="$('#gnPanelOrganisation').slideToggle();"><?php echo __('Organisation') ?></legend>
    <div id="gnPanelOrganisation" style="display:none;">
      <?php echo render_form_row($form['is_root']) ?>
      <?php echo render_form_row($form['slug']) ?>
    </div>
  </fieldset>

  <?php echo $form->renderHiddenFields() ?>

  <div class="gn-form-row clearfix">
    <button type="submit" class="button positive"><?php echo __('Save your changes') ?></button>
  </div>

</form>
<?php slot('gn-side') ?>
<p><button type="submit" class="button positive" onclick="$('#gnWikiPageForm').submit()"><?php echo __('Save your changes') ?></button>
<?php if (!$form->getObject()->isNew()): ?>
  <br/>
  <?php echo __('Or') ?>,
  <?php if(is_null($form->getObject()->getDeletedAt())): ?>
    <?php echo link_to('delete this page', 'gnWikiPage/delete?id='.$form->getObject()->getId(), array('method' => 'delete', 'confirm' => 'Are you sure?')) ?>
  <?php else: ?>
    <?php echo link_to('undelete this page', 'gnWikiPage/undelete?id='.$form->getObject()->getId(), array('method' => 'get')) ?>
  <?php endif; ?>
<?php endif; ?>
</p>
<?php include_component('gnAsset', 'form', array('object' => $form->getObject())) ?>
<?php end_slot(); ?>


<?php include_partial('gnSearch/ajaxForm', array('form' => new gnSearchForm()))?>
