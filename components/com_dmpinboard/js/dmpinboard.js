/**
* @copyright		(C)2013 DM Digital S.r.l
* @author 			DM Digital <support@dmjoomlaextensions.com>
* @link				http://www.dmjoomlaextensions.com
* @license 			GNU/LGPL http://www.gnu.org/copyleft/gpl.html
**/

var jDM = jQuery.noConflict();
var DMPinboard = {
	
	root : '',
	comurl : '',
	source : '',
	itemId : -1,
	container : '',
	loadingCount : 0,
	enableButton : false,
	enablePopup : false,
	presetStyle : 'style_default',
	autoLoadEnable : true,
	contentFinished : false,
	lang : {},
	stack : [],
	laodedItems : [],
	
	reqReply : function(myReply, myTarget, myOption) {
		
		myReply = jDM.parseJSON(myReply);
		allOK = true;
		if (myReply != undefined) {
			if (myReply.content != undefined && myReply.type == 'HTML') {
				if (myOption == undefined || myOption == 'html') {
					myTarget.html(myReply.content);
				} else if (myOption == 'prepend') {
					myTarget.prepend(myReply.content);
				} else if (myOption == 'popup') {
					DMPinboard.popUp(myReply.content);
				} else {
					myTarget.append(myReply.content);
				}
			} else if (myReply.content != undefined && myReply.type == 'JSON') {
				DMPinboard.stack.push(JSON.parse(myReply.content));
			} else if (myReply.content != undefined && myReply.type == 'JS') {
				eval(myReply.content);
			}
		}
		if (myReply.result != 'OK') {
			allOK = false;
		}
		if (myReply != undefined && myReply.message != undefined && jDM.trim(myReply.message) != '') {
			DMPinboard.showError(myReply.message);
		}
		return allOK;
	},
	
	getItems : function() {
		
		DMPinboard.removeButton();
		DMPinboard.loading();
		
		if (DMPinboard.comurl.trim() != '') {
			jDM.post(
				DMPinboard.comurl,
				{
					'source' 	: DMPinboard.source,
					'Itemid' 	: DMPinboard.itemId,
					'task' 		: 'getItems',
					'loaItems'	: DMPinboard.laodedItems,
					'offset' 	: DMPinboard.container.find('a.pbitem_cont').length
				},
				function(data){
					if (DMPinboard.reqReply(data)) {
						var itemlist = DMPinboard.stack.pop();
						if (itemlist.length > 0) {
							for (var i=0,length = itemlist.length;i<length;i++) {
							    var myItem = '<a class="pbitem_cont justadded" '+itemlist[i].link+'>';
							    if (itemlist[i].image.trim() != '' && itemlist[i].image.substring(0,4) == 'http') {
							    	myItem += '<img src="'+itemlist[i].image+'" class="pbitem_image"/>';
							    } else if (itemlist[i].image.trim() != '') {
							    	myItem += '<img src="'+DMPinboard.root+itemlist[i].image+'" class="pbitem_image"/>';
							    }
							    if (itemlist[i].title.trim() != '') {
							    	myItem += '<div class="pbitem_title">'+itemlist[i].title+'</div>';
							    }
							    if (itemlist[i].intro.trim() != '') {
							    	myItem += '<div class="pbitem_intro">'+itemlist[i].intro+'</div>';
							    }
							    myItem += '</a>';
							    jDM(DMPinboard.container).append(myItem);
							    DMPinboard.laodedItems.push(itemlist[i].id);
							}
						}
						DMPinboard.container.imagesLoaded(function(){
		    				DMPinboard.container.masonry('reload');
		    				DMPinboard.autoLoadEnable = true;
		    				DMPinboard.container.find('a.pbitem_cont.justadded').removeClass('justadded');
		    				DMPinboard.loading('stop');
		    				DMPinboard.addButton();
		    				DMPinboard.scrollCheck();
		    			});
					} else {
						DMPinboard.container.masonry('reload');
		    			DMPinboard.autoLoadEnable = true;
		    			DMPinboard.loading('stop');
					}
				});
		}
	},
	
	getPreview : function(articleId) {
		
		if (DMPinboard.comurl.trim() != '') {
			jDM.post(
				DMPinboard.comurl,
				{
					'source' 	: DMPinboard.source,
					'Itemid' 	: DMPinboard.itemId,
					'articleId'	: articleId,
					'task' 		: 'getItemPreview'
				},
				function(data){
					DMPinboard.reqReply(data,'','popup');
				});
		}
	},
	
	popUp : function(htmlCont) {
		
		if (jDM('#pbgrid_notice #pbgrid_popup').length <= 0) {
			jDM('#pbgrid_notice').append('<div id="pbgrid_popup" class="'+DMPinboard.presetStyle+'">'+htmlCont+'</div>');
		} else {
			jDM('#pbgrid_notice #pbgrid_popup').html(htmlCont);
		}
		jDM('#pbgrid_popup').css('opacity',0);
		jDM('#pbgrid_notice #pbgrid_popup').imagesLoaded(function(){
			var popupOptions = {};
			popupOptions.modal = true;
			popupOptions.resizable = false;
			popupOptions.width = jDM('#pbgrid_popup').width();
			popupOptions.dialogClass = 'dmpbPopup '+DMPinboard.presetStyle;
			popupOptions.autoOpen = true;
			popupOptions.maxHeight = (jDM(window).height() - 20);
			popupOptions.create = function(event, ui){jDM("body").css({ height:jDM(window).height(), overflow:'hidden' })};
			popupOptions.beforeClose = function(event, ui){jDM("body").css({ height:'auto', overflow:'inherit' })};
			
			jDM('#pbgrid_popup').dialog(popupOptions);
			jDM('#pbgrid_popup').css('opacity',1);
			jDM('.ui-widget-overlay:visible').click(function(){
			    jDM('#pbgrid_popup').dialog('close');
			    jDM('#pbgrid_popup').remove();
			});
		});
	},
	
	showError : function(message) {
		
		//TODO: fare in modo che aggiunga il messaggio di errore a fine lista
		console.log(message);
	},
	
	loading : function(action, smessage) {
		
		if (action == 'stop') {
			DMPinboard.loadingCount--;
			if (DMPinboard.loadingCount <= 0) {
				jDM('#pbgrid_notice .loading').remove();
			}
		} else {
			DMPinboard.loadingCount++;
			DMPinboard.showLoading(smessage);
		}
	},
	
	showLoading : function(smessage) {
		
		if (smessage == undefined || smessage.trim() == '') {
			smessage = DMPinboard.lang.LOADING;
		}
		if (jDM('#pbgrid_notice .loading').length <= 0) {
			jDM('#pbgrid_notice').append('<div class="loading">'+smessage+'</div>');
		}
	},
	
	addButton : function() {
		
		if (jDM('#pbgrid_notice .more_button').length <= 0 && DMPinboard.enableButton == true) {
			jDM('#pbgrid_notice').append('<button class="more_button">'+DMPinboard.lang.LOADMORE+'</button>');
			jDM('#pbgrid_notice .more_button').click(function(){DMPinboard.getItems();});
		}
	},
	
	removeButton : function() {
		
		if (jDM('#pbgrid_notice .more_button').length > 0) {
			jDM('#pbgrid_notice .more_button').remove();
		}
	},
	
	scrollCheck : function() {
		
		var myOffset = DMPinboard.container.offset(),
			botPos = DMPinboard.container.height() + myOffset.top;
		if (!DMPinboard.contentFinished) {
		    if (
		    	((jDM(window).scrollTop()+jDM(window).height()) >= botPos) ||
		    	(jDM(window).height() > botPos)
		       ) {
		        if (DMPinboard.autoLoadEnable) {
		        	DMPinboard.getItems();
		            DMPinboard.autoLoadEnable = false;
		        }
		    }
		}
	}
	
};