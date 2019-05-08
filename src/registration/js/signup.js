function validateSignup(){
	if ($('#pass').val() != $('#re_pass').val()){
		console.log($('#pass').val())
		console.log($('#re_pass').val())
		alert('Passwords must be matching')
		return false
	}
	return true
}