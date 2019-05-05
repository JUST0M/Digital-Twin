function updateSlider(i) {
  return function() {
  	document.getElementById("b".concat(i)).value = this.value
  	// Health Indicators might change
  	resetImages()
  	redraw()
  };
}
function updateBox(i) {
  return function() {
  	document.getElementById("s".concat(i)).value = this.value
  	// Health Indicators might change
  	resetImages()
  	redraw()
  };
}

const numSliders = 8;
window.onload = function(){
  for (i = 0; i < numSliders; i++){
    document.getElementById("s".concat(i)).oninput = updateSlider(i)
    document.getElementById("b".concat(i)).oninput = updateBox(i)
  }
}
