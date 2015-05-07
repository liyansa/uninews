<?php

	/**
	* @copyright		(C)2013 DM Digital S.r.l
	* @author 			DM Digital <support@dmjoomlaextensions.com>
	* @link				http://www.dmjoomlaextensions.com
	* @license 			GNU/LGPL http://www.gnu.org/copyleft/gpl.html
	**/
	
	defined('_JEXEC') or die('Restricted access');
	require_once(JPATH_SITE.DS.'components'.DS.'com_k2'.DS.'helpers'.DS.'route.php');

class PBHSourceK2content {
	
	public $arg;
	
	public function loadFromDB($itemId = -1) {
		
		$myQuery = 'SELECT 
					i.*, 
					c.alias AS catalias 
					FROM #__k2_items as i 
					LEFT JOIN #__k2_categories AS c ON (i.catid = c.id) 
					WHERE 1=1 
					AND i.published = 1';
		if ($itemId < 0 && !empty($this->arg['categories'])) {
			$myQuery .= ' AND i.catid IN (' .implode(',',$this->arg['categories']). ')';
		}
		if ($this->arg['ordering'] == 'random' && !empty($this->arg['loaded_items'])) {
			$myQuery .= ' AND i.id NOT IN ('.implode(',',$this->arg['loaded_items']).')';
		}
		if ($itemId < 0 && !empty($this->arg['ordering'])) {
			switch ($this->arg['ordering']) {
				case 'title_asc': 
					$myQuery .= ' ORDER BY i.title ASC';
					break;
				case 'title_desc': 
					$myQuery .= ' ORDER BY i.title DESC';
					break;
				case 'date_asc': 
					$myQuery .= ' ORDER BY i.publish_up ASC';
					break;
				case 'hits_desc': 
					$myQuery .= ' ORDER BY i.hits DESC';
					break;
				case 'hits_asc': 
					$myQuery .= ' ORDER BY i.hits ASC';
					break;
				case 'article_order': 
					$myQuery .= ' ORDER BY i.ordering ASC';
					break;
				case 'random':
					$myQuery .= ' ORDER BY RAND()';
					break;
				default: 
					$myQuery .= ' ORDER BY i.publish_up DESC';
					break;
			}
		}
		if ($itemId < 0 && !empty($this->arg['items_number'])) {
			$myQuery .= ' LIMIT ' . $this->arg['items_number'];
		}
		if ($itemId < 0 && !empty($this->arg['items_offset']) && $this->arg['items_offset'] > 0 && $this->arg['ordering'] != 'random') {
			$myQuery .= ' OFFSET ' . $this->arg['items_offset'];
		}
		if ($itemId < 0) {
			return DMHData::loadObjectList($myQuery);
		} else {
			$myQuery .= ' AND i.id = ' . $itemId;
			return DMHData::loadObject($myQuery);
		}
	}
	
	public function getItemId($item) {
		
		return $item->id;
	}
	
	public function getItemUrl($item) {
		
		if ($this->arg['previewpopup'] == 'enabled') {
			$myLink = 'href="#" onclick="DMPinboard.getPreview('.$item->id.');return false;"';
		} else {
			$link = JRoute::_(K2HelperRoute::getItemRoute($item->id.':'.urlencode($item->alias), $item->catid.':'.urlencode($item->catalias)));
			$myLink = 'href="'.urldecode($link).'"';
		}
		
		return $myLink;
	}
	
	public function getItemTitle($item) {
		
		if ($this->arg['show_title'] == 'yes') {
			return $item->title;
		} else {
			return '';
		}
	}
	
	public function getItemImage($item) {
		
		if ($this->arg['show_image'] == 'fromtext') {
			$myImage = '';
			$output = preg_match( '/<img[^>]+src=[\'"]([^\'"]+)[\'"][^>]*>/i', $item->introtext, $matches);
			if ($output > 0) {
				$myImage = $matches[1];
			}
			return $myImage;
		} else if($this->arg['show_image'] == 'k2itemimg') {
			if (file_exists(JPATH_SITE.DS.'media'.DS.'k2'.DS.'items'.DS.'src'.DS.md5("Image".$item->id).'.jpg')) {
				return 'media'.DS.'k2'.DS.'items'.DS.'src'.DS.md5("Image".$item->id).'.jpg';
			} else {
				return '';
			}
		} else {
			return '';
		}
	}
	
	public function getItemIntro($item) {
		
		if ($this->arg['show_intro'] == 'yes') {
			$outText = str_replace(array("\t","\n","\r","\r\n"),'',strip_tags($item->introtext));
			if (!empty($this->arg['introlength']) && $this->arg['introlength'] > 0) {
				$outText = PBHHtml::shorter($outText, $this->arg['introlength']);
			}
			return $outText;
		} else {
			return '';
		}
	}
	
	public function getPreviewImage($item) {
		
		if ($this->arg['show_popup_images'] == 'fromtext') {
			$myImage = '';
			$output = preg_match('/<img[^>]+src=[\'"]([^\'"]+)[\'"][^>]*>/i', $item->introtext, $matches);
			if ($output > 0) {
				$myImage = $matches[1];
			}
			return $myImage;
		} else if ($this->arg['show_popup_images'] == 'k2itemimg') {
			if (file_exists(JPATH_SITE.DS.'media'.DS.'k2'.DS.'items'.DS.'src'.DS.md5("Image".$item->id).'.jpg')) {
				return 'media'.DS.'k2'.DS.'items'.DS.'src'.DS.md5("Image".$item->id).'.jpg';
			} else {
				return '';
			}
		} else {
			return '';
		}
	}
	
	public function getPreviewTitle($item) {
		
		if ($this->arg['show_popup_title'] == 'linked') {
			$link = JRoute::_(K2HelperRoute::getItemRoute($item->id.':'.urlencode($item->alias), $item->catid.':'.urlencode($item->catalias)));
			return '<a href="'.urldecode($link).'">'.$item->title.'</a>';
		} else if ($this->arg['show_popup_title'] == 'yes') {
			return $item->title;
		} else {
			return '';
		}
	}
	
	public function getPreviewContent($item) {
		
		if ($this->arg['show_popup_intro'] == 'withoutimg') {
			$outText = preg_replace('/<img[^>]+\>/i', '', $item->introtext);
		} else if($this->arg['show_popup_intro'] == 'yes') {
			$outText = preg_replace_callback('/(src=["\'])([^"\']+)(["\'])/','PBHHtml::checkImgSrc',$item->introtext);
		} else {
			return $outText;
		}
		if (!empty($this->arg['previewlength']) && $this->arg['previewlength'] > 0) {
			$outText = PBHHtml::truncate_teaser($outText,$this->arg['previewlength']);
		}
		return $outText;
	}
	
	public function getPreviewReadmore($item) {
		
		if ($this->arg['show_popup_articlelink'] == 'yes') {
			$link = JRoute::_(K2HelperRoute::getItemRoute($item->id.':'.urlencode($item->alias), $item->catid.':'.urlencode($item->catalias)));
			return '<a href="'.urldecode($link).'">'.JText::_('COM_DMPINBOARD_FRONTEND_READMORE').'</a>';
		} else {
			return '';
		}
	}
	
	public function getShareInfo($item) {
		
		$share = array();
		//---
		$link = JRoute::_(K2HelperRoute::getItemRoute($item->id.':'.urlencode($item->alias), $item->catid.':'.urlencode($item->catalias)));
		$share['url'] = urldecode(JUri::base().$link);
		//---
		$share['title'] = $item->title;
		return $share;
	}
	
}

?>