function updateSlider(i) {
  return function() {
  	document.getElementById("b".concat(i)).value = this.value
  	// Health Indicators might change canvas
  	resetImages()
  	redraw()
  };
}
function updateTextBox(i) {
  return function() {
  	document.getElementById("s".concat(i)).value = this.value
  	// Health Indicators might change canvas
  	resetImages()
  	redraw()
  };
}
function updateCheckBox(){
	return function(){
		resetImages()
		redraw()
	}
}

window.onload = function(){
	const numSliders = 8;
	for (i = 0; i < numSliders; i++){
		document.getElementById("s".concat(i)).oninput = updateSlider(i)
		document.getElementById("b".concat(i)).oninput = updateTextBox(i)
	}

	document.getElementById("smoking").oninput = updateCheckBox()
}
