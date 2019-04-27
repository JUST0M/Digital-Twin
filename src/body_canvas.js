var canvas = document.getElementById('humanCanvas');
canvas.width = window.innerWidth / 2;
canvas.height = window.innerHeight * 0.98;

var c = canvas.getContext('2d')

// Leo: Code for original stickman Figure
/*
//head
c.beginPath();
var headX = canvas.width / 2
var headY = canvas.height / 4
var headRadius = canvas.height / 10
c.arc(headX, headY, headRadius, 0, Math.PI * 2)
//outline is blue
c.strokeStyle = 'blue'
// fill is black
c.fillStyle = 'black'
c.fill()
c.stroke()

//draw body
var shoulder = headY + 1.5 * headRadius
var butt = headY + 3.6 * headRadius
var hand = headY + 2.5 * headRadius
var foot = headY + 4.8 * headRadius
var armFootXOff = headRadius * 1.3

c.beginPath()
c.moveTo(headX, headY + headRadius)
//body
c.lineTo(headX, butt);
//right leg
c.lineTo(headX + armFootXOff, foot);
//left leg
c.moveTo(headX, butt)
c.lineTo(headX - armFootXOff, foot)
//right arm
c.moveTo(headX + armFootXOff, hand)
c.lineTo(headX, shoulder);
//left arm
c.lineTo(headX - armFootXOff, hand);

c.stroke()
*/
var mouse = {
  x: undefined,
  y: undefined
}
/*
function isOnHead() {
  xDiff = mouse.x - headX
  yDiff = mouse.y - headY
  return(Math.sqrt(xDiff * xDiff + yDiff * yDiff) <= headRadius)
}
*/

// Leo: Human Body Image Loading
var img = new Image()
img.src = "../images/Human-Body.jpg";
img.onload = function () {
    c.drawImage(img, 0, 0, canvas.width, canvas.height);
}

// Check if mouse is hovering over parts of the body
function isOnBrain() { // Leo: TODO 
  return false 
}
function isOnHeart() { // Leo: TODO
  return false
}
function isOnLungs() { // Leo: TODO
  return false
}

// // listen for resizing of window event. Scale the image when needed
window.addEventListener('resize', function(event) {
    c.drawImage(img, 0, 0, canvas.width, canvas.height)
})

// listen for click event and check if click is on head
// if so, run some function
window.addEventListener('click', function(event) {
  console.log('clicked mouse')
  mouse.x = event.x
  mouse.y = event.y
  console.log(mouse)

  // Run actions according to the click
  if (isOnBrain()) brainClicked()
  if (isOnHeart()) heartClicked()
  if (isOnLungs()) lungsClicked()

})

// actions to take if parts of body are clicked
function brainClicked(){
  console.log("Brain Clicked")
  window.location = "brain/brain.html"
}
function heartClicked(){
  console.log("Heart Clicked")
  window.location = "heart/heart.html"
}
function lungsClicked(){
  console.log("Lungs Clicked")
  window.location = "lungs/lungs.html"
}


/* The following lines *should* encode the 
// Cardiovascular Health Indicators
var bmi = parseInt(document.getElementById(s0).value, 10)
var vpa = parseInt(document.getElementById(s1).value, 10)
var pvo2 = 
var alc = parseInt(document.getElementById(s2).value, 10)
var smo = parseInt(document.getElementById(s3).value, 10) // "have you been a smoker in the past six months?"
var asbp = parseInt(document.getElementById(s4).value, 10)
var adbp = parseInt(document.getElementById(s5).value, 10)
var pedbp = parseInt(document.getElementById(s6).value, 10)
var cho = parseInt(document.getElementById(s7).value, 10)
var glu = parseInt(document.getElementById(s8).value, 10)


// Array containing 1s and 0s detailing whether each of the study's thresholds are met
var healthPoints = []

if (bmi < 25) {healthPoints(0).push(1)} else {healthPoints(0).push(0)}
if (vpa >= 75) {healthPoints(1).push(1)} else {healthPoints(1).push(0)}
if (alc < 8) {healthPoints(2).push(1)} else {healthPoints(2).push(0)}
if (smo = 0) {healthPoints(3).push(1)} else {healthPoints(3).push(0)}
if (asdp < 130; adbp < 80) {healthPoints(4).push(1)} else {healthPoints(4).push(0)}
if (pedbp < 90) {healthPoints(5).push(1)} else {healthPoints(5).push(0)}
if (cho < 200) {healthPoints(6).push(1)} else {healthPoints(6).push(0)}
if (glu < 100) {healthPoints(7).push(1)} else {healthPoints(7).push(0)}

// Patient's score

var healthIndex = 0

for (i = 0; i < healthPoints.length; i ++) { healthIndex += healthPoints(i) }

*/