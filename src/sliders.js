console.log("o".concat(1));
//var str = "o".concat(2);
function updateSlider(i) {
  return function() {document.getElementById("o".concat(i)).innerHTML = this.value};
}
const numSliders = 10;
window.onload = function(){
  for (i = 0; i < numSliders; i++){
    document.getElementById("s".concat(i)).oninput = updateSlider(i)
  }
}


//document.getElementById("o1").innerHTML = "hi";
