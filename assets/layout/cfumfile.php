<?php

/**
 * @package Plugin user - changetemplate for Joomla! 4.x and 5.x
 * @version $Id: user - change tamplate 1.0.0 2024-05-10 23:26:33Z $
 * @author KWProductions Co.
 * @copyright (C) 2020- KWProductions Co.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 
 This file is part of user - changetemplate.
    user - changetemplate is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.
    It is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    You should have received a copy of the GNU General Public License
    along with it.  If not, see <http://www.gnu.org/licenses/>.
 
**/


defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Session\Session;

//add script here to change the button text
//based on the button text value, send a flag via ajax parameter to create or delete a text file
Factory::getDocument()->getWebAssetManager()
  ->registerScript(
    'change-template',
    'media/plg_user_changetemplate/js/update-template.js',
    ['version' => 'auto', 'relative' => true],
    ['nomodule' => true, 'defer' => true],
    ['core']
  );
 /* ->registerAndUseScript(
    'clearcache-esm',
    'media/plg_system_imageconvertor/js/clearcache-esm.js',
    ['version' => 'auto', 'relative' => true],
    ['type' => 'module'],
    ['clearcache-es6', 'core']
  );*/
?>
<change-template label-text="Update Template" button-text="Update Template" token="<?= Session::getFormToken(); ?>">
  <button id="changebutt"></button>
</change-template>
