jQuery(document).ready(function($){ 
	$('.sm_megamenu_menu > li > div').parent().addClass('parent-item');
	
	var full_width = $('body').innerWidth();
	$('.full-content').css({'width':full_width});

	$( window ).resize(function() {
		var full_width = $('body').innerWidth();
		$('.full-content').css({'width':full_width});
	});
	
	$('body').bind('touchstart', function() {});
jQuery(document).ready(function ($) {
    $(window).on('load resize change', function(event){
        Move();
    });

    function Move() {
      //   if($(window).width() < 760) {
        
      //        $('.header-style-1 .header-middle .middle-right-content .searchbox-header , .header-style-1 .header-middle .middle-right-content .minicart-header').prependTo($('.header-style-1 .header-bottom .main-megamenu'));
      // }
      //   else {
      //       $('.header-style-1 .header-bottom .main-megamenu  .searchbox-header,.header-style-1 .header-bottom .main-megamenu  .minicart-header ').prependTo($('.header-style-1 .middle-right-content'));
          
      //   }
       if($(window).width() < 760) {
        
             $('.header-style-2 .header-middle .search-wrapper ').prependTo($('.header-style-2 .header-bottom .main-megamenu'));
      }
        else {
            $('.header-style-2 .header-bottom .main-megamenu  .search-wrapper ').prependTo($('.header-style-2 .right-content-header'));
          
        }
        if($(window).width() < 760) {
        
             $('.header-style-2 .header-middle .minicart-header').prependTo($('.header-style-2 .header-bottom .main-megamenu'));
      }
        else {
            $('.header-style-2 .header-bottom .main-megamenu  .minicart-header ').prependTo($('.header-style-2 .left-content-header'));
          
        }
        // header 4 
        if($(window).width() < 760) {
        
             $('.header-style-4 .header-middle .minicart-header').prependTo($('.header-style-4 .header-bottom .main-megamenu'));
      }
        else {
            $('.header-style-4 .header-bottom .main-megamenu  .minicart-header ').prependTo($('.header-style-4 .middle-right-content'));
          
        }
         if($(window).width() < 992) {
        
             $('.header-style-5 .header-middle .navigation-mobile-container').prependTo($('.header-style-5 .header-bottom .hd2-search-box'));
      }
        else {
            $('.header-style-5 .header-bottom .hd2-search-box  .navigation-mobile-container ').prependTo($('.header-style-5 .header-middle .navigation-container'));
          
        }
        
}
});
});

