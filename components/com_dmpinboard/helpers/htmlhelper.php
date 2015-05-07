<?php

	/**
	* @copyright		(C)2013 DM Digital S.r.l
	* @author 			DM Digital <support@dmjoomlaextensions.com>
	* @link				http://www.dmjoomlaextensions.com
	* @license 			GNU/LGPL http://www.gnu.org/copyleft/gpl.html
	**/
	
	defined('_JEXEC') or die('Restricted access');

class PBHHtml {
	
	public static function headerStuff($arg) {
		
		$document = JFactory::getDocument();
		$document->addStyleSheet('components/'.JText::_('COM_DMPINBOARD_DIRECTORY').'/css/jquery.ui.css');
		$document->addStyleSheet('components/'.JText::_('COM_DMPINBOARD_DIRECTORY').'/css/dmpinboard.css');
		if ($arg['animatelist'] == '') {
			$document->addStyleSheet('components/'.JText::_('COM_DMPINBOARD_DIRECTORY').'/css/items_anim.css');
		}
		if ($arg['load_jquery'] == 'yes') {
			$document->addScript('http://code.jquery.com/jquery-1.9.0.js');
		}
		if ($arg['load_jqueryui'] == 'yes') {
			$document->addScript('components/'.JText::_('COM_DMPINBOARD_DIRECTORY').'/js/jquery.ui.js');
		}
		$document->addScript('components/'.JText::_('COM_DMPINBOARD_DIRECTORY').'/js/jquery.masonry.min.js');
		$document->addScript('components/'.JText::_('COM_DMPINBOARD_DIRECTORY').'/js/modernizr-transitions.js');
		$document->addScript('components/'.JText::_('COM_DMPINBOARD_DIRECTORY').'/js/jquery.imagesloaded.js');
		$document->addScript('components/'.JText::_('COM_DMPINBOARD_DIRECTORY').'/js/dmjsutils.js');
		$document->addScript('components/'.JText::_('COM_DMPINBOARD_DIRECTORY').'/js/dmpinboard.js');
	}
	
	public static function getViewParams($source) {
		
		//---
		$mainframe = JFactory::getApplication();
	    $option = JRequest::getCmd('option');
	    $params = $mainframe->getParams( $option );
		//---
		$arg = array();
		$arg['source'] 				= $source;
		$arg['categories'] 			= $params->get('categories','');
		$arg['items_number']		= $params->get('items_number',8);
		$arg['introlength'] 		= $params->get('introlength',100);
		$arg['ordering'] 			= $params->get('ordering','date_desc');
		$arg['show_image'] 			= $params->get('show_image','fromtext');
		$arg['show_title'] 			= $params->get('show_title','yes');
		$arg['show_intro'] 			= $params->get('show_intro','no');
		$arg['load_jquery'] 		= $params->get('load_jquery','yes');
		$arg['load_jqueryui'] 		= $params->get('load_jqueryui','yes');
		$arg['preset_style']		= $params->get('preset_style','style_default');
		$arg['show_morebutton'] 	= $params->get('show_morebutton','yes');
		$arg['infinitescroll']		= $params->get('infinitescroll','disabled');
		$arg['animatelist']			= $params->get('animatelist','yes');
		$arg['previewpopup']		= $params->get('previewpopup','disabled');
		$arg['show_popup_title']	= $params->get('show_popup_title','yes');
		$arg['show_popup_images']	= $params->get('show_popup_images','fromtext');
		$arg['show_popup_intro']	= $params->get('show_popup_intro','withoutimg');
		$arg['show_popup_articlelink']	= $params->get('show_popup_articlelink','no');
		$arg['previewlength'] 		= $params->get('previewlength','');
		$arg['socialstuff'] 		= $params->get('socialstuff','yes');
		$arg['zoo_element_teaserimage'] = $params->get('zoo_element_teaserimage','cdce6654-4e01-4a7f-9ed6-0407709d904c');
		$arg['zoo_element_image'] 	= $params->get('zoo_element_image','c26feca6-b2d4-47eb-a74d-b067aaae5b90');
		$arg['zoo_element_text'] 	= $params->get('zoo_element_text','2e3c9e69-1f9e-4647-8d13-4e88094d2790');
		$arg['zoo_item_type']		= $params->get('zoo_item_type','article');
		$arg['loaded_items']		= JRequest::getVar('loaItems','');
		if (!empty($arg['loaded_items']) && count($arg['loaded_items'])>0) {
			$arg['items_offset'] = count($arg['loaded_items']);
		} else {
			$arg['items_offset'] = 0;
		}
		//---
		return $arg;
	}
	
	public static function outputView($arg) {
		
		self::headerStuff($arg);
		$articles = PBHArticles::getArticles($arg);
		$output = self::getViewHtml($articles,$arg);
		echo $output;
	}
	
	public static function getViewHtml($articles, $arg) {
		
		ob_start();
       	require_once(JPATH_COMPONENT.DS.'tmpl'.DS.'default.php');
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}
	
	public static function getPreviewHtml($preview, $arg) {
		
		ob_start();
       	require_once(JPATH_COMPONENT.DS.'tmpl'.DS.'itempreview.php');
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}
	
	public static function reqOtput($result = 'OK', $type = 'HTML', $content = '', $message = '') {
		
		$output = array();
		$output['result'] = $result;
		$output['type'] = $type;
		$output['content'] = $content;
		$output['message'] = $message;
		echo json_encode( $output );
		exit;
	}
	
	public static function checkImgSrc($matchess) {
		
		$parsedUrl = parse_url($matchess[2]);
		if (empty($parsedUrl['scheme'])) {
			$matchess[2] = JUri::base().$matchess[2];
		}
		return $matchess[1].$matchess[2].$matchess[3];
	}
	
	public static function shorter($input, $length) {
		
	    if (strlen($input) <= $length) {
	        return $input;
	    }
	    $last_space = strrpos(substr($input, 0, $length), ' ');
	    if (!$last_space) {
	    	$last_space = $length;
	    }
	    $trimmed_text = substr($input, 0, $last_space);
	    
	    $trimmed_text .= '...';
	    
	    return $trimmed_text;
	}
	
	public static function truncate_teaser($text, $length = 100, $ending = '...', $exact = false, $considerHtml = true) {
		
		if ($considerHtml) {
			if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
				return $text;
			}
			preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
			$total_length = strlen($ending);
			$open_tags = array();
			$truncate = '';
			foreach ($lines as $line_matchings) {
				if (!empty($line_matchings[1])) {
					if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
					} else if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
						$pos = array_search($tag_matchings[1], $open_tags);
						if ($pos !== false) {
						unset($open_tags[$pos]);
						}
					} else if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
						array_unshift($open_tags, strtolower($tag_matchings[1]));
					}
					$truncate .= $line_matchings[1];
				}
				$content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
				if ($total_length+$content_length> $length) {
					$left = $length - $total_length;
					$entities_length = 0;
					if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
						foreach ($entities[0] as $entity) {
							if ($entity[1]+1-$entities_length <= $left) {
								$left--;
								$entities_length += strlen($entity[0]);
							} else {
								break;
							}
						}
					}
					$truncate .= substr($line_matchings[2], 0, $left+$entities_length);
					break;
				} else {
					$truncate .= $line_matchings[2];
					$total_length += $content_length;
				}
				if($total_length>= $length) {
					break;
				}
			}
		} else {
			if (strlen($text) <= $length) {
				return $text;
			} else {
				$truncate = substr($text, 0, $length - strlen($ending));
			}
		}
		if (!$exact) {
			$spacepos = strrpos($truncate, ' ');
			if (isset($spacepos)) {
				$truncate = substr($truncate, 0, $spacepos);
			}
		}
		$truncate .= $ending;
		if($considerHtml) {
			foreach ($open_tags as $tag) {
				$truncate .= '</' . $tag . '>';
			}
		}
		return $truncate;
	}
	
}

?>