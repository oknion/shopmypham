jQuery(document).ready(function($){
	function cleanHref(){
		var pdpath = arguments[0];
		var preg = /\/[^\/]{0,}$/ig;
		if( typeof pdpath == 'undefined') pdpath = 'null';
		if( pdpath[pdpath.length-1]=="/" ){
			pdpath = pdpath.substring(0,pdpath.length-1);
			return (pdpath.match(preg)+"/");
		}
		return pdpath.match(preg);
	}

	function quickView(){
		var qvpath = 'quickview/index/view';
		if( VT_QV.SETTING.BASE_URL.indexOf('index.php') == -1 ) qvpath = 'index.php/quickview/index/view';
		var baseUrl = VT_QV.SETTING.BASE_URL + qvpath;
		$.each($(arguments[0].wrapQuickView), function() {
			// Append quick view
			if( $(this).find("a.vt_quickview_handler").length <= 0 ){
				if( $(this).find("a").length > 0 ){
					link = $(this).find("a");
					var href = cleanHref(link.attr('href'))[0];
					href = (href[0] == "\/") ? href.substring(1, href.length) : href;
					href = baseUrl+"/path/" + href.replace(/^\s+|\s+$/g,""); //console.log(href);
					// product type
					href = href.replace('?options=cart', "");
					//href = href+'?is_quickview=1';
					$(this).append("<a class=\"vt_quickview_handler\" data-original-title=\""+VT_QV.SETTING.TEXT+"\" data-placement=\"left\" data-toggle=\"tooltip\" href=\""+href+"\"><span>"+VT_QV.SETTING.TEXT+"</span></a>");
				}
			}
		});
		// Insert popup for quick view
		$('.vt_quickview_handler').each(function(){
			$(this).fancybox({
				type		: 'ajax',
				maxWidth	: VT_QV.SETTING.POP_WIDTH,
				maxHeight	: VT_QV.SETTING.POP_HEIGHT,
				fitToView	: false,
				autoSize	: true,
				autoResize	: true,
				autoCenter	: true,
				closeClick	: false,
				scrollOutside: false,
				openEffect	: 'elastic',
				closeEffect	: 'elastic',
				scrollOutside	: false,
		        ajax            : {
		                cache   : false,
		        },
				afterShow 	: function() {
					$(".vt_product_qv_img").each(function(){
						$(this).data('owlCarousel').reinit();
					});
				}
//				afterClose	: function() {
//					$('.fancybox-overlay + .zoomContainer').remove();
//				}
			});
		});
//		$('.vt_quickview_handler')
	}
	quickView({wrapQuickView : VT_QV.SETTING.SELECTOR});
	setInterval(function(){ quickView({wrapQuickView : VT_QV.SETTING.SELECTOR}) },1000);
});