<?php

	/**
	* @copyright		(C)2013 DM Digital S.r.l
	* @author 			DM Digital <support@dmjoomlaextensions.com>
	* @link				http://www.dmjoomlaextensions.com
	* @license 			GNU/LGPL http://www.gnu.org/copyleft/gpl.html
	**/
	
	defined('_JEXEC') or die('Restricted access');
	require_once(JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php');

class PBHSourceZoocontent {
	
	public $arg;
	
	public function loadFromDB($itemId = -1) {
		
		$myQuery = 'SELECT 
					i.*, 
					c.category_id AS catid 
					FROM #__zoo_item AS i 
					LEFT JOIN #__zoo_category_item AS c ON (c.item_id = i.id) 
					WHERE 1=1 
					AND i.state = 1 
					AND i.type = "'.$this->arg['zoo_item_type'].'" 
					AND c.category_id NOT IN (0) ';
		if ($itemId < 0 && !empty($this->arg['categories'])) {
			$myQuery .= ' AND c.category_id IN (' .implode(',',$this->arg['categories']). ')';
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
				case 'priority': 
					$myQuery .= ' ORDER BY i.priority ASC';
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
			$link = JRoute::_('index.php?option=com_zoo&task=item&item_id='.$item->id);
			$myLink = 'href="'.urldecode($link).'"';
		}
		
		return $myLink;
	}
	
	public function getItemTitle($item) {
		
		if ($this->arg['show_title'] == 'yes') {
			return $item->name;
		} else {
			return '';
		}
	}
	
	public function getItemImage($item) {
		
		$elements = json_decode($item->elements);
		$elementName = $this->arg['zoo_element_text'];
		if ($this->arg['show_image'] == 'fromtext' && !empty($elements->$elementName->{0})) {
			$myImage = '';
			$output = preg_match( '/<img[^>]+src=[\'"]([^\'"]+)[\'"][^>]*>/i', $elements->$elementName->{0}->value, $matches);
			if ($output > 0) {
				$myImage = $matches[1];
			}
			return $myImage;
		} else if($this->arg['show_image'] == 'teaserimg') {
			$elementName = $this->arg['zoo_element_teaserimage'];
			return $elements->$elementName->file;
		} else if ($this->arg['show_image'] == 'itemimg') {
			$elementName = $this->arg['zoo_element_image'];
			return $elements->$elementName->file;
		} else {
			return '';
		}
	}
	
	public function getItemIntro($item) {
		
		$elements = json_decode($item->elements);
		$elementName = $this->arg['zoo_element_text'];
		if ($this->arg['show_intro'] == 'yes' && !empty($elements->$elementName->{0})) {
			$outText = str_replace(array("\t","\n","\r","\r\n"),'',strip_tags($elements->$elementName->{0}->value));
			if (!empty($this->arg['introlength']) && $this->arg['introlength'] > 0) {
				$outText = PBHHtml::shorter($outText, $this->arg['introlength']);
			}
			return $outText;
		} else {
			return '';
		}
	}
	
	public function getPreviewImage($item) {
		
		$elements = json_decode($item->elements);
		$elementName = $this->arg['zoo_element_text'];
		if ($this->arg['show_popup_images'] == 'fromtext' && !empty($elements->$elementName->{0})) {
			$myImage = '';
			$output = preg_match('/<img[^>]+src=[\'"]([^\'"]+)[\'"][^>]*>/i', $elements->$elementName->{0}->value, $matches);
			if ($output > 0) {
				$myImage = $matches[1];
			}
			return $myImage;
		} else if ($this->arg['show_popup_images'] == 'itemimg') {
			$elementName = $this->arg['zoo_element_teaserimage'];
			return $elements->$elementName->file;
		} else {
			return '';
		}
	}
	
	public function getPreviewTitle($item) {
		
		if ($this->arg['show_popup_title'] == 'linked') {
			$link = JRoute::_('index.php?option=com_zoo&task=item&item_id='.$item->id);
			return '<a href="'.urldecode($link).'">'.$item->name.'</a>';
		} else if ($this->arg['show_popup_title'] == 'yes') {
			return $item->name;
		} else {
			return '';
		}
	}
	
	public function getPreviewContent($item) {
		
		$elements = json_decode($item->elements);
		$elementName = $this->arg['zoo_element_text'];
		if ($this->arg['show_popup_intro'] == 'withoutimg' && !empty($elements->$elementName->{0}->value)) {
			$outText = preg_replace('/<img[^>]+\>/i', '', $elements->$elementName->{0}->value);
		} else if($this->arg['show_popup_intro'] == 'yes') {
			$outText = preg_replace_callback('/(src=["\'])([^"\']+)(["\'])/','PBHHtml::checkImgSrc',$elements->$elementName->{0}->value);
		} else {
			$outText = '';
		}
		if (!empty($this->arg['previewlength']) && $this->arg['previewlength'] > 0) {
			$outText = PBHHtml::truncate_teaser($outText,$this->arg['previewlength']);
		}
		return $outText;
	}
	
	public function getPreviewReadmore($item) {
		
		if ($this->arg['show_popup_articlelink'] == 'yes') {
			$link = JRoute::_('index.php?option=com_zoo&task=item&item_id='.$item->id);
			return '<a href="'.urldecode($link).'">'.JText::_('COM_DMPINBOARD_FRONTEND_READMORE').'</a>';
		} else {
			return '';
		}
	}
	
	public function getShareInfo($item) {
		
		$share = array();
		//---
		$link = JRoute::_('index.php?option=com_zoo&task=item&item_id='.$item->id);
		$share['url'] = urldecode(JUri::base().$link);
		//---
		$share['title'] = $item->name;
		return $share;
	}
	
}

?>