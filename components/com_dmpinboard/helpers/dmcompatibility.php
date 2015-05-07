<?php
	
	/**
	* @copyright		(C)2013 DM Digital S.r.l
	* @author 			DM Digital <support@dmjoomlaextensions.com>
	* @link				http://www.dmjoomlaextensions.com
	* @license 			GNU/LGPL http://www.gnu.org/copyleft/gpl.html
	**/
	
	defined('_JEXEC') or die('Restricted access');
	jimport('joomla.application.component.controller');
	jimport('joomla.application.component.view');
	
	
	if (!class_exists('DMController')) {
		
		if (version_compare(JVERSION, '3.0', 'ge')) {
		    
		    class DMController extends JControllerLegacy {
		        
		        public function display($cachable = false, $urlparams = array()) {
		        	parent::display($cachable, $urlparams);
		        }
		    }
		    
		} else if (version_compare(JVERSION, '2.5', 'ge')) {
		    
		    class DMController extends JControllerLegacy {
		    	
		    	public function display($cachable = false, $urlparams = false) {
		        	parent::display($cachable, $urlparams);
		        }
		    }
		    
		} else {
		    
		    class DMController extends JController {
		    	
		    	public function display($cachable = false) {
		        	parent::display($cachable);
		        }
		    }
		    
		}
		
	}
	
	if (!class_exists('DMView')) {
		
		if (version_compare(JVERSION, '3.0', 'ge')) {
		    
		    class DMView extends JViewLegacy {
		    }
		    
		} else if (version_compare(JVERSION, '2.5', 'ge')) {
		    
		    class DMView extends JView {
		    }
		    
		} else {
		    
		    class DMView extends JView {
		    }
		    
		}
		
	}
	
?>