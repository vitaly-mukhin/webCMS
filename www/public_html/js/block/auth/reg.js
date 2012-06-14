(function(){
	var STATUS = {
		OK: 'success',
		FAILED: 'error'
	};
	
	// Check for login field
	$('#form-auth-reg input[name=login]').change(function(){
		var s = true;
		s = s && (this.value != '');
		s = s && !(this.value.match(/[^$A-Za-z0-9_\-.]/i));
		setStatus($(this), (s ? STATUS.OK : STATUS.FAILED));
	});
	
	$('#form-auth-reg input[name=password], #form-auth-reg input[name=password_repeat]').change(function(){
		var s = true;
		s = s && this.value != '';
		s = s && this.value.length >= 6;
		setStatus($(this), (s ? STATUS.OK : STATUS.FAILED));
	});

	// Submitting the reg form
	$('#form-auth-reg').submit(function(){
			
		var $login = $('input[name=login]', this);
		$login.trigger('change');

		// check Password and Password_Repeat
		var $pwd = $('input[name=password]', this);
		$pwd.trigger('change');

		var $pwd_r = $('input[name=password_repeat]', this);
		$pwd_r.trigger('change');
			
		if ($pwd.val() != $pwd_r.val()) {
			setStatus($pwd_r, STATUS.FAILED);
		}
			
		var result = $('.control-group.' + STATUS.FAILED, this).get().length == 0;
		
		if (result) {
			$.post(this.baseURI + 'block' + this.action.substr(this.baseURI.length-1), $(this).serialize(), function(data){
				console.log(data);
			});
		}
		
		return false;
	});

	function setStatus($el, status) {
		var $parent = $el.parents('.control-group');
		for (var i in STATUS) {
			$parent.removeClass(STATUS[i]);
		}
		$parent.addClass(status);
	}
})();