function submitSignup(){
	if (!$('#pass').val() || !$('#username').val()){
		event.preventDefault();
		alert('Fields may not be empty');
	}
	else if ($('#pass').val() != $('#re_pass').val()){
		event.preventDefault();
		alert('Passwords must be matching');
	}
	else if (!($('#agree-term').is(':checked'))){
		event.preventDefault();
		alert('Please read the Terms of Service and check the box');
	}
}