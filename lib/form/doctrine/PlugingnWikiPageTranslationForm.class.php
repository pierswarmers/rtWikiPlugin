<?php

/**
 * PlugingnWikiPageTranslation form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfDoctrineFormPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class PlugingnWikiPageTranslationForm extends BasegnWikiPageTranslationForm
{
  public function setup()
  {
    parent::setup();
    unset($this['slug'], $this['version'], $this['created_at'], $this['updated_at']);
    $this->setWidget('title',      new sfWidgetFormInputText(array(), array('class' => 'title')));
    $this->setValidator('title',   new sfValidatorString(array('max_length' => 255, 'required' => true), array('required' => 'please enter a descriptive title.')));
    $this->setValidator('content', new sfValidatorString(array('required' => true), array('required' => 'please enter some content.')));
  }
}
