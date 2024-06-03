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

?>
<return-template-field label-text="Return Template" button-text="Return Template" token="<?= Session::getFormToken(); ?>">
  <button id="b2">Return Template</button>
</return-template-field>

<script type="text/javascript">
document.querySelectorAll('.hide-aware-inline-help.d-none').forEach(el => el.classList.remove('hide-aware-inline-help', 'd-none'));
//document.getElementById('toolbar-inlinehelp').remove();

customElements.define('return-template-field', class extends HTMLElement {
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

    this.button = null;
    this.systemPaths = Joomla.getOptions('system.paths');
    this.onClick = this.onClick.bind(this);
  }
  connectedCallback() {
  
    this.button = this.querySelector('button');
    this.button.addEventListener('click', this.onClick);
  }

  onClick() {
	
    fetch(new URL(`${this.systemPaths.baseFull}index.php?option=com_ajax&type=plugin&plugin=changetemplate&group=user&method=changetemplate&flag=1&format=json&${this.token}=1`), {method: 'POST'})
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

