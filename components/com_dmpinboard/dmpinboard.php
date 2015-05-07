<?php
	
	/**
	* @copyright		(C)2013 DM Digital S.r.l
	* @author 			DM Digital <support@dmjoomlaextensions.com>
	* @link				http://www.dmjoomlaextensions.com
	* @license 			GNU/LGPL http://www.gnu.org/copyleft/gpl.html
	**/
	
	defined('_JEXEC') or die('Restricted access');
	if (!defined('DS')&&!defined(DIRECTORY_SEPARATOR)) {
		define('DS',DIRECTORY_SEPARATOR);
	} else if (!defined('DS')) {
		define('DS','/');
	}
	jimport('joomla.application.component.controller');
	
	require_once(JPATH_COMPONENT.DS.'helpers'.DS.'dmcompatibility.php');
	require_once(JPATH_COMPONENT.DS.'helpers'.DS.'dbhelper.php');
	require_once(JPATH_COMPONENT.DS.'helpers'.DS.'articleshelper.php');
	require_once(JPATH_COMPONENT.DS.'helpers'.DS.'htmlhelper.php');
	
	$controller = JRequest::getCmd('controller', 'newsboard');
	$task 		= JRequest::getCmd('task', 'display');
	
	if (file_exists(JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php')) {
	    require_once(JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php');
		$controllerName = 'PBController' . $controller;
		$controller = new $controllerName();
		$controller->execute($task);
		$controller->redirect();
	}
	
?>