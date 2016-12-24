jQuery(document).ready(function($) {
	$('#vt_sidenav li.level0 > a').addClass ('subhead');
	$('#vt_sidenav li.level0 > a').after ('<a href="#" title="" class="toggle">&nbsp;</a>');
	// second simple accordion with special markup
	$('#vt_sidenav').accordion({
		/*active: '.active',
		header: '.toggle',
		navigation: true,
		event: 'click',
		fillSpace: false,
		autoheight: false,
		alwaysOpen: false, 
		animated: 'easeslide'*/

		selectedClass: "selected",
		alwaysOpen: true,
		animated: 'slide',
		event: "click",
		header: "a",
		autoheight: true,
		running: 0,
		navigationFilter: function() {
			return this.href.toLowerCase() == location.href.toLowerCase();
		}
	});	
});