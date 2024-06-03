<?php

defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Form\FormField;
use Joomla\CMS\HTML\HTMLHelper; 
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Session\Session;



HTMLHelper::_('jquery.framework');

class JFormFieldRmdfile extends FormField{

	protected $type = 'rmdfile';  
    protected $layout = 'rmdfile';

  protected function getInput(): string
  {
    return rtrim(LayoutHelper::render($this->layout, [], JPATH_PLUGINS . '/user/changetemplate/layout/rmd'), PHP_EOL);
  }
}
