<?php
/**
 * Notification module for Joomla 1.5
 *
 * @author      $Author: shumisha $
 * @copyright   Yannick Gaultier - 2011
 * @package     shotimoo
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version     $Id$
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

if (!function_exists('shotimoo_escape')) {
	function shotimoo_escape($text) {
		return 'decodeURIComponent(\'' . str_replace('+', '%20', urlencode($text)) . '\')';
		/*
		for($i = 0, $l = strlen($text), $new_str=''; $i < $l; $i++) {
			$ord = ord(JString::substr($text, $i, 1));
			$new_str .= ($ord < 16 ? '\\x0' : '\\x') . dechex($ord);
		}
		return $new_str;
		*/
	}
}


$mainframe = &JFactory::getApplication();
if (!$mainframe->isAdmin()) {

  $document = &JFactory::getDocument();
  if ($document->getType() == 'html') {

    // insert custom js
    $js = '';
    for( $n = 1; $n < 4; $n++) {
      $cleared = false;
      // search for a cleared by user cookie
      if($params->get('useclearcookie', false)) {
        $cleared = JRequest::getBool( 'shotimoo_clrd_' . $module->id, false, 'COOKIE' );
      }
      if(!$cleared) {
        $message = $params->get('message'.$n, '');
        $title = $params->get('title'.$n, '');
        $delay = intval($params->get('close_delay'.$n, 0));
        $sticky = empty($delay) ? ', sticky:true' : '';
        $visibleTime = empty( $delay) ? '' : ',visibleTime:' . $delay;
        $message = str_replace( "\n", '<br />', $message);
        $start = $params->get( 'display_from'.$n, '');
        $end = $params->get( 'display_until'.$n, '');
        $now = '';
        $dateFormat = 'Y-m-d H:i:s';
        if(!empty($start)) {
          try {
            $now = new DateTime();
            $now = $now->format($dateFormat);
            $startDate = new DateTime( $start);
            $startDate = $startDate->format($dateFormat);
          } catch (Exception $e) {
            $startDate = $now;
          }
          if($startDate > $now) {
            $message = '';
            $title = '';
          }
        }

        if(!empty( $end)) {
          try {
            if(empty( $now)) {
              $now = new DateTime();
              $now = $now->format($dateFormat);
            }
            $endDate = new DateTime($end);
            $endDate = $endDate->format($dateFormat);
          } catch (Exception $e) {
            $endDate = $now;
          }
          if($endDate < $now) {
            $message = '';
            $title = '';
          }
        }

        if(!empty( $title) || !empty( $message)) {
          $js .= "\n" . 'mod_shotimoo_'. $module->id.'.show( {message:'.shotimoo_escape($message) . ',title:'.shotimoo_escape($title). $sticky . $visibleTime.'});';
        }
      }
    }

    if (!empty($js)) {
    	$document->addScript('media/shotimoo_assets/1.2.1/shotimoo-v1.2.1' . (JDEBUG ? '' : '.min') . '.js');
    	$document->addStyleSheet('media/shotimoo_assets/1.2.1/shotimoo-v1.2.1.css');
      $js = 'window.addEvent(\'domready\', function(){
        var mod_shotimoo_'. $module->id.' = new Shotimoo({
        shId:\''.$module->id.'\',shSetCookie:' . ($params->get('useclearcookie', false) ? 'true' : 'false') .',locationVType:\''.$params->get('vLocation', 'top').'\',locationHType:\''.$params->get('hLocation', 'right').'\'});' . $js . '});';
      $document->addScriptDeclaration( $js);
    }
  }
}

