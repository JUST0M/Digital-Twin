function updateSlider(id) {
  return function() {
  	document.getElementById("box-".concat(id)).value = this.value
  	// Health Indicators might change canvas
  	resetTwinImages()
  	redrawTwin()
  };
}
function updateTextBox(id) {
  return function() {
  	document.getElementById("slider-".concat(id)).value = this.value
  	// Health Indicators might change canvas
  	resetTwinImages()
  	redrawTwin()
  };
}
function updateCheckBox(){
	return function(){
		resetTwinImages()
		redrawTwin()
	}
}

window.onload = function(){
	sliderIds.forEach(function(id) {
		document.getElementById("slider-".concat(id)).oninput = updateSlider(id)
		document.getElementById("box-".concat(id)).oninput = updateTextBox(id)
	});

  checkboxIds.forEach(function(id) {
	  document.getElementById("checkbox-".concat(id)).oninput = updateCheckBox()
  });
}
