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


?>
<?php
defined('_JEXEC') or die;
use Joomla\CMS\User\User;
use Joomla\CMS\User\UserHelper;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;

class PlgUserChangetemplate extends CMSPlugin
{		
	protected $autoloadLanguage = true;
		protected $app;
		protected $db;
		protected $mtitems;


    public function onAfterInitialise()
	{
		
	
		$this->loadLanguage();
	}
	

	public function onUserAfterLogin($options=[])
	{
		
		// 2 or more users login all their ids in the list check if file exists
						$ids = $this->params->get("ids");
						$ug = $this->params->get("users-groups");


		if($this->app->isClient('site') && $ids!==NULL && !empty($ids) ):
	
		$app = $this->app;
			
       if(!File::exists(JPATH_ROOT . '/plugins/user/changetemplate/template.txt'))
		$this->saveFormerTemplate();
		
				$uorgids = explode(',', $ids);
				$userflag = 0;
				$groupflag = 0;
		if($ug==0)
		{
			
			if($uorgids!==NULL && !empty($uorgids))
            foreach($uorgids as $userid):
			    if($userid == $options['user']->id)
				{
					$userflag = 1;
					break;
				}
			endforeach;
			
			if($userflag==1)
			{
				//check if it is not already updated. by #__menu table to #__session table 
				$this->updateTemplateStyle();
			}
			
			return true;
			
		}
		else
			if($ug==1)
		    {
				if($uorgids!==NULL && !empty($uorgids))
					foreach($uorgids as $groupid):
		         	  foreach($options['user']->groups as $group):
			    if($groupid == $group)
				{
					$groupflag = 1;
					break;
				}
			    endforeach;
			endforeach;
			
			if($groupflag==1)
			{
				//check if it is not already updated. by #__menu table to #__session table 
				 $this->updateTemplateStyle();
			}
			
		        return true;
		    }
		     else 
		       return false;
		   
		   
		
		endif;
		return true;
		
	}
	public function updateTemplateStyle()
	{
		//if called from buttons, check not to be empty in onclick function event or give alert, then this function would not be called
		// if fields are empty.
		$this->mtitems = [];
			$mt = $this->params->get("menuitems-templates");
				if(!empty($mt)):
				$mtitems = explode('|', $mt);
				$med = [];
				foreach($mtitems as $mtitem)
				{
					if($mtitem!==NULL && !empty($mtitem))
						$med[] = $mtitem;
				}
				$this->mtitems = $med;
				
		
		
		$db = $this->db;
		foreach($this->mtitems as $mtitem)
		{
			
			$mtitlestyle = explode('@', $mtitem);
			
				
					    $query = $db->getQuery(true);

                       $fields = array(
                       $db->quoteName('template_style_id') . ' = ' . $db->quote($mtitlestyle[1]),                      
                          );


                  $conditions = array($db->quoteName('title') . ' = ' . $db->quote($mtitlestyle[0]), );
               
                    $query->update($db->quoteName('#__menu'))->set($fields)->where($conditions);

                   $db->setQuery($query);

                    $db->execute();
				
				 			 
			
		}
		endif;
		
	}
	
	 public function returnTemplate()
  {
	  
	  if(File::exists(JPATH_ROOT . '/plugins/user/changetemplate/template.txt'))
	  {
		//here fields have no need to be checked  
		  $stuff = file_get_contents(JPATH_ROOT . '/plugins/user/changetemplate/template.txt');
		  
		  if(!empty($stuff))
		  {
			  
		    $mtitems = [];
				$mtitems = explode('|', $stuff);
				$med = [];
				foreach($mtitems as $mtitem)
				{
					if($mtitem!==NULL && !empty($mtitem))
						$med[] = $mtitem;
				}
				$mtitems = $med;
				
			$db = $this->db;
		foreach($mtitems as $mtitem)
		{
			$mtitlestyle = explode('@', $mtitem);
			
			
					 $query = $db->getQuery(true);

                       $fields = array(
                       $db->quoteName('template_style_id') . ' = ' . $db->quote($mtitlestyle[1]),                      
                          );


                   $conditions = array($db->quoteName('title') . ' = ' . $db->quote($mtitlestyle[0]) );

                    $query->update($db->quoteName('#__menu'))->set($fields)->where($conditions);

                   $db->setQuery($query);

                    $db->execute();
				 
				 				 
			
		     }  //end foreach loop
				
			
            File::delete(JPATH_ROOT . '/plugins/user/changetemplate/template.txt');			
		  } 
	  }
	  
	  
  }
	
	public function onAjaxChangetemplate($event)
  {
	  
	  if($_GET['flag'])
	  {
		  $this->returnTemplate();
	  }
	  else
	  {

	
		if(!File::exists(JPATH_ROOT . '/plugins/user/changetemplate/template.txt'))
             	$this->saveFormerTemplate();
			
	  $this->updateTemplateStyle();
	  }

   return true;
  }
  
	public function saveFormerTemplate()
	{
		$this->mtitems = [];
			$mt = $this->params->get("menuitems-templates");
				if($mt!=NULL && !empty($mt)):
				$mtitems = explode('|', $mt);
				$med = [];
				foreach($mtitems as $mtitem)
				{
					if($mtitem!==NULL && !empty($mtitem))
						$med[] = $mtitem;
				}
				$this->mtitems = $med;
				
		
		
		$stuff = "";
		$db = $this->db;
		$num = count($this->mtitems);
		
		$i=0;
		$flag = 0;
		foreach($this->mtitems as $mtitem)
		{
					$mtitlestyle = explode('@', $mtitem);

	                $query=	$db->getQuery(true);
				 				 
				 $query->select('*')->from($db->quoteName('#__menu'))->where('title='. $db->quote($mtitlestyle[0]));
				 $db->setQuery($query);
                $m = $db->loadObject();
				$stuff .="|".$mtitlestyle[0]."@".$m->template_style_id."@";
				$i++;
				if($i==$num)
				{
							$stuff .="|";
							$flag = 1;

				}
		}
		//write the stuff in a file and use it on logout to update the menu table.
		if($flag==1 && !File::exists(JPATH_ROOT . '/plugins/user/changetemplate/template.txt'))
		{
			/*$myfile = fopen("template.txt", "w")
			fwrite($myfile, $stuff);
			fclose($myfile);*/
			file_put_contents(JPATH_ROOT . '/plugins/user/changetemplate/template.txt', $stuff);
		}
		
		endif;
		
	}
	
	public function onUserLogout($credentials=[], $options=[])
	{
	    
			$ids = $this->params->get("ids");
		if($this->app->isClient('site') && !empty($ids) && $ids!==NULL):
		
		//and also check table session(table session must be checked.) if other users or groups of this sort are still logged in
		//better only to handle from the backsite		
		
		        $ug = $this->params->get("users-groups");
			
				$uorgids = explode(',', $ids);
				$userflag = 0;
				$groupflag = 0;
	
				if($ug==0)
				{
					
					foreach($uorgids as $userid)
					   if($userid==$credentials['id'])
					   {
						   $userflag = 1;
						   break;
					   }
				}
				else
					if($ug==1)
				    {
						$ruser = User::getInstance($credentials['id']);
						
						foreach($uorgids as $groupid)
						  foreach($ruser->groups as $usergroupid)
					       if($groupid==$usergroupid)
					       {
						       $groupflag = 1;
						   break;
					       }
				    }
			
		
		if( $groupflag==1 ||  $userflag==1 ){
		
				$db = $this->db;
				$query=	$db->getQuery(true);				 				 
				$query->select('*')->from($db->quoteName('#__session'));
				$db->setQuery($query);
                $users = $db->loadObjectList();
				$actionflag = 0;
				
				foreach($users as $user):
				
				    if($ug==0)
					{
						foreach($uorgids as $id)
						{
							if($id == $user->userid && $id!= $credentials['id'])
							{
								$actionflag = 1;
							   break;
							}
						}
						
					}
					else
						if($ug==1)
						{
							$groups = [];
							
							
							    $otheruser =  User::getInstance($user->userid);
								
							
								   $groups = $otheruser->groups;	
								
								   foreach($groups as $group)
								   {
									   foreach($uorgids as $gid)
							           {
										     if($gid==$group  && $otheruser->id!=$credentials['id'])
											 {
												 $actionflag = 1;
												 break;
											 }
								       }
								  }
						
								
							
						
						
						}
				
				endforeach;
		
		if($actionflag==0){
				if(File::exists(JPATH_ROOT . '/plugins/user/changetemplate/template.txt'))
				{
					
			        $stuff = file_get_contents(JPATH_ROOT . '/plugins/user/changetemplate/template.txt');
					$theStuff = explode('|', $stuff);
						$med = [];
			     	foreach($theStuff as $ss)
				    {
					   if($ss!==NULL && !empty($ss))
						   $med[] = $ss;
				    }
				      $theStuff = $med;
				
				      foreach($theStuff as $ss)
		              {
					     $mtitlestyle = explode('@', $ss);

	                   $query = $db->getQuery(true);

                         $fields = array($db->quoteName('template_style_id') . ' = ' . $mtitlestyle[1]);


                         $conditions = array($db->quoteName('title') . ' = ' . $db->quote($mtitlestyle[0]) );

                         $query->update($db->quoteName('#__menu'))->set($fields)->where($conditions);

                         $db->setQuery($query);

                         $db->execute(); 
				 				 
				
		             }
					 
					  			


		        }
		    }
		}				
				
		    endif;
		
		
		return true;
		
	}
	
		



}