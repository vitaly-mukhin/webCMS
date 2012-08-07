
$(document).ready(function(){	
    $('a.auth-register, a.auth-reset').click(function(){
        $.get(this.baseURI + 'json/' + 'block' + this.pathname, {}, function(data){
            $('#body-content').html($('<div/>').html(data.content).text());
            appendScripts(data);
        }, 'json');
        return false;
    });
});