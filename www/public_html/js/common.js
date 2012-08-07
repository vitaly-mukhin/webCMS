function appendScripts(data) {
    if ('head' in data) {
        // insert JS files
        if ('script' in data.head) {
            $(data.head.script).each(function(index, url){
                // this is do zhopy, oskilky v cyomu selectory znajdetsya lyshe te, wo bulo vstavleno pry formuvanny pochatkovoi HTML
                var $element = $('script[src="'+url+'"]');
                if(!$element.length) {
                    $.getScript(url);
                }
            });
        }
                
        // insert CSS files
        if ('css'  in data.head) {
            $(data.head.css).each(function(index, url){
                var $element = $('link[href="'+url+'"]');
                if(!$element.length) {
                    $('body').append('<link rel="stylesheet" href="'+url+'" type="text/css" />');
                }
            });
        }
    }
}