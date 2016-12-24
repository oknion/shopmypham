function initBase(){
    if(_section == 'storeview'){
       $('storeview_extensions').update($('ọbject_store').innerHTML)
    }
}
Event.observe(window, 'load', function() {
   initBase();
});
