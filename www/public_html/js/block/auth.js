
$(document).ready(function(){	
	$('a.auth-register').click(function(){
		$.get(this.baseURI + 'json/' + 'block' + this.pathname, {}, function(data){
			$('#body-content').html($('<div/>').html(data.content).text());
		}, 'json');
		return false;
	});
});