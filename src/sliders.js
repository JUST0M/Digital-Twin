//console.log("b".concat(1));
//var str = "o".concat(2);
function updateSlider(i) {
  return function() {document.getElementById("b".concat(i)).value = this.value};
}
function updateBox(i) {
  return function() {document.getElementById("s".concat(i)).value = this.value};
}

const numSliders = 8;
window.onload = function(){
  for (i = 0; i < numSliders; i++){
    document.getElementById("s".concat(i)).oninput = updateSlider(i)
    document.getElementById("b".concat(i)).oninput = updateBox(i)
  }
}


//document.getElementById("o1").innerHTML = "hi";
