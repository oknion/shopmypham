if (typeof $vt_jq != 'function'){
	if (typeof jQuery == 'undefined'){
		var msg = 'Please include jquery first. jQuery 1.5.0 is recommended!';
		if (console.log){
			console.log(msg);
		} else {
			alert(msg);
		}
	} else {
		$vt_jq = jQuery.noConflict();
	}
}