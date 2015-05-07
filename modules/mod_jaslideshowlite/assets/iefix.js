;(function(){
	if(document.getElementById('jaieanim')){ //already exist this code, no need to load css again
		return;
	}
	
	var assets = (typeof siteurl != 'undefined' ? siteurl : (typeof JADef != 'undefined' ? JADef.siteurl : '')) + 'modules/mod_jaslideshowlite/assets/',
		head = document.head || document.getElementsByTagName('head')[0],
		iefix = document.createElement ('link'),
		ieanim = null;
		
	try{
		//IE error when load more than 32 styleSheets
		ieanim = document.createStyleSheet();
	} catch(e){	
	}
	
	if(ieanim){
		// add css	
		ieanim.cssText = ''+
			'.ja-ss-item { behavior:url(' + assets + 'animate.htc) }\n' +
			'.ja-ss-item.curr { behavior:url(' + assets + 'animate.htc?curr) }\n' +
			'.ja-ss-item.prev { behavior:url(' + assets + 'animate.htc?prev) }\n' +
			'.ja-ss-item.next { behavior:url(' + assets + 'animate.htc?next) }\n';

		iefix.id = 'jaieanim';
		iefix.type = 'text/css';
		iefix.rel = 'stylesheet';
		iefix.href = assets + 'iefix.css';
		head.appendChild(iefix);
	}
})();