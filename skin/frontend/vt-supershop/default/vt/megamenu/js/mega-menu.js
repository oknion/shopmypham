jQuery(function($){
	var wrap = $('#vt_menu');
	
	var $t = $('#vt_menu > div').hasClass('container');
	if($t != true){
		var container = $('#vt_menu');
	}else{
		var container = $('#vt_menu .container');
	}
	$(window).resize(function(){
		setTimeout(function(){
    		$('.vt-megamenu-wrap').find('li').each(function(){
				var menucontent = $(this).find(".mega-content-wrap:first");
			 	var li = $(this);

				if( (container.outerWidth() + container.offset().left) < (li.offset().left + menucontent.outerWidth()) ){
					menucontent.css({"left": (container.outerWidth() - menucontent.outerWidth() )+"px"});
				}
			});
  		}, 200);
	});
	$('.vt-megamenu-wrap').find('li').each(function(){
		var menucontent 		= $(this).find(".mega-content-wrap:first");
		var mshow_timer = 0;
		var mhide_timer = 0;
	 	var li = $(this);
	 	var islevel0 = (li.hasClass('level0'))?true:false;
		var havechild = (li.hasClass('parent'))?true:false;

		if( (container.outerWidth() + container.offset().left) < (li.offset().left + menucontent.outerWidth()) ){
			menucontent.css({"left": (container.outerWidth() - menucontent.outerWidth() )+"px"});
		}

	 	if(islevel0 && $('.vt-megamenu-wrap.using-effect').length){
	 		if(menucontent){
		 		menucontent.hide();
		 	}
			li.mouseenter(function(el){
				clearTimeout(mhide_timer);
				clearTimeout(mshow_timer);
				$(this).addClass('hover');
				if(havechild){
					$('.vt-megamenu-wrap').find('li').find(".mega-content-wrap:first").hide();
					$('.vt-megamenu-wrap').find('li').find(".mega-content-wrap:first").css({"display":"none"});
					mshow_timer = setTimeout(function(){ //Emulate HoverIntent
						menucontent.stop().show(500, function(){
							menucontent.css({"display":"block"});
						});
					}, 100);
				}
			}).mouseleave(function(el){ //return;
				clearTimeout(mshow_timer);
				clearTimeout(mhide_timer);
				//$('.vt-megamenu-wrap.using-effect').find('li').find(".mega-content-wrap:first").hide();
				if(havechild){
					mhide_timer = setTimeout(function(){ //Emulate HoverIntent
						menucontent.stop().hide(500, function(){
							menucontent.css({"display":"none", "width":"", "height":""});
						});
					}, 100);
				}
				$(this).removeClass('hover');
		    });
		}
	});
});