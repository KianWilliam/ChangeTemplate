<?php

defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Form\FormField;
use Joomla\CMS\HTML\HTMLHelper; 
use Joomla\CMS\Factory;



HTMLHelper::_('jquery.framework');

class JFormFieldMtextarea extends FormField{

	protected $type = 'mtextarea';
	protected $rows = 10;
	protected $columns = 100;

	
	public function getInput()
	{
		$html="";
		$db = Factory::getDbo();
		$query = $db->getQuery(true);
			
				 $query->select('*')->from($db->quoteName('#__template_styles'));;
				 $db->setQuery($query);
                 $templates = $db->loadObjectList();
				
				 foreach($templates as $template):
				    $html .= "Title:".$template->title."-Template Style id: ".$template->id."<br />";
				 endforeach;

		$html .= '<textarea  name="' . $this->name . '" id="' . $this->id . '"   class="form-control validate-victory"   aria-describedby="'.$this->id.'-desc">'.$this->value.'</textarea>';
		$html.="<div>Copy and paste the structure below:(You may use templatestyleid(s) from the list above)</div>";
		$html.="<div>|menutitle@templatestyleid@|menutitle@templatestyleid@|</div>";
		return $html;
	}
/*
To reveal the default label
	public function getLabel() {	
		return (string) $this->element['label'];		
		//return $this->label;
	}*/
   
}
?>
<script type="text/javascript">
jQuery(document).ready(function(){
document.formvalidator.setHandler('victory', function(value){
		
		regex=/^(\|(\@?([a-zA-Z0-9\-\s_])+\@([0-9\s])+\@\|)+)+$/;
	return regex.test(value);
	
})

});
</script>