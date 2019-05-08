function submitSignup(){
	if ($('#pass').val() != $('#re_pass').val()){
		alert('Passwords must be matching');
		event.preventDefault();
	}
	else if (!($('#agree-term').is(':checked'))){
		alert('Please read the Terms of Service and check the box');
		event.preventDefault();
	}
}