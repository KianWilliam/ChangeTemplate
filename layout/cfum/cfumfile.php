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

//based on the button text value, send a flag via ajax parameter to create or delete a text file
//load from file causes the script loaded in header while the button is still not loaded and the events won't work

?>
<p>Alert! The text file created on clicking this button keeps ONLY the layout data of menuitems before using this plugin. In case you delete the file , you
will lose the info of layout data for menuitems before the start of using this plugin!</p>
<change-template-field label-text="Update Template" button-text="Change Template" token="<?= Session::getFormToken(); ?>">
  <button >Change Template</button>

</change-template-field>
<script type="text/javascript">
document.querySelectorAll('.hide-aware-inline-help.d-none').forEach(el => el.classList.remove('hide-aware-inline-help', 'd-none'));
//document.getElementById('toolbar-inlinehelp').remove(); gives error in console.

customElements.define('change-template-field', class extends HTMLElement {
  get labelText() {
    return this.getAttribute('label-text');
  }
  get buttonText() {
    return this.getAttribute('button-text');
  }
  
  get token() {
    return this.getAttribute('token');
  }
  constructor() {
    super();
//console.log("haaaaaaaaaaaaaaaaaaaamid");
    this.button = null;
    this.systemPaths = Joomla.getOptions('system.paths');
    this.onClick = this.onClick.bind(this);
  }
  connectedCallback() {
  
    this.button = this.querySelector('button');
    this.button.addEventListener('click', this.onClick);
  }

  onClick() {
		console.log("haha"); 
console.log("nemigiram"+`${this.systemPaths.baseFull}`);		
	  
    fetch(new URL(`${this.systemPaths.baseFull}index.php?option=com_ajax&type=plugin&plugin=changetemplate&group=user&method=changetemplate&format=json&${this.token}=1`), {method: 'POST'})
    .then((response)=>{
      if (!response.ok) throw new Error("HTTP status " + response.status);
      return response.json();
    })
    .then(resp => {
      if (resp.success) this.renderMsg({'success': ['Success! 🎉']}, undefined, false, 4000);
      else this.renderMsg({'danger': ["We've failed 🤷‍♂️"]}, undefined, false);
    })
    .catch(err => {
      console.log(err)
      this.renderMsg({'danger': ["We've failed 🤷‍♂️"]}, undefined, false);
     });


		 
  }

  renderMsg(msg, selector, keepOld, timeout) {
    scrollTo({
      top: 0,
      left: 0,
      behavior: 'smooth'
    });
    Joomla.renderMessages(msg, selector, keepOld, timeout);
  }
});

</script>
