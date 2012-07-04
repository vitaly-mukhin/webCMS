
$(document).ready(function(){	
	$('a.auth-register').click(function(){
		console.log(this);
		$.get(this.baseURI + 'json/' + 'block' + this.pathname, {}, function(data){
			$('#body-content').html(data);
		}, 'html');
		return false;
	});
});