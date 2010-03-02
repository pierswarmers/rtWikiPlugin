<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<?php use_helper('I18N', 'GnForm') ?>

<form action="<?php echo url_for('gnWikiPage/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>

  <?php if (!$form->getObject()->isNew()): ?>
  <input type="hidden" name="sf_method" value="put" />
  <?php endif; ?>

  <?php echo $form->renderGlobalErrors() ?>
  <?php echo render_form_row($form[$sf_user->getCulture()]['title'], array('wide' => true)) ?>
  <?php echo render_form_row($form['published']) ?>
  <?php echo render_form_row($form[$sf_user->getCulture()]['content'], array('wide' => true)) ?>

  <?php echo $form->renderHiddenFields() ?>
  
  <?php if (!$form->getObject()->isNew()): ?>
    <?php if(is_null($form->getObject()->getDeletedAt())): ?>
      <?php echo link_to('Delete this page', 'gnWikiPage/delete?id='.$form->getObject()->getId(), array('method' => 'delete', 'confirm' => 'Are you sure?', 'class' => 'button warning medium')) ?>
    <?php else: ?>
      <?php echo link_to('Undelete this page', 'gnWikiPage/undelete?id='.$form->getObject()->getId(), array('method' => 'get', 'class' => 'button warning medium')) ?>
    <?php endif; ?>
  <?php endif; ?>
  <button type="submit" class="button positive medium right"><?php echo __('Save your changes') ?></button>

</form>
