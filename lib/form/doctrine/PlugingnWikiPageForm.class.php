<?php

/**
 * PlugingnWikiPage form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfDoctrineFormPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class PlugingnWikiPageForm extends BasegnWikiPageForm
{
  public function setup()
  {
    parent::setup();
    unset($this['comment_status']);

    $this->widgetSchema['is_root']->setLabel('Make wiki homepage');
    $this->widgetSchema->setHelp('is_root', 'This will be the first page people see when entering the wiki.');
  }
}
