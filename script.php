<?php


defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Installer\InstallerScript;

class PlgUserChangeTemplateInstallerScript extends InstallerScript
{
 public function install($parent)
 {
  
   
  $db  = Factory::getDbo();
  $query = $db->getQuery(true);
  $query->update('#__extensions');
  $query->set($db->quoteName('enabled') . ' = 1');
  $query->where($db->quoteName('element') . ' = ' . $db->quote('changetemplate'));
  $query->where($db->quoteName('type') . ' = ' . $db->quote('plugin'));
  $db->setQuery($query);
  $db->execute(); 
  
 }
 
}
