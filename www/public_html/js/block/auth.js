
$(document).ready(function(){	
	$('a.auth-register').click(function(){
		$.get(this.baseURI + 'block' + this.pathname, {}, function(data){
			$('#body-content').html(data);
		}, 'html');
//		document.location.hash = this.pathname.substr(1);
		return false;
	});
});