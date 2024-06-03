<?php

defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Form\FormField;
use Joomla\CMS\HTML\HTMLHelper; 
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Factory;


HTMLHelper::_('jquery.framework');

class JFormFieldCfumfile extends FormField{

	protected $type = 'cfumfile';  
    protected $layout = 'cfumfile';

  protected function getInput(): string
  {
    return rtrim(LayoutHelper::render($this->layout, [], JPATH_PLUGINS . '/user/changetemplate/layout/cfum'), PHP_EOL);

	
  }
}


