//Ben: Fetch appropriate elements for picture popups
var heartModal = document.getElementById('heartModal');
var heartSpan = document.getElementsByClassName("close")[0];
var heartHeader = document.getElementsByClassName("modal-header")[0];
var heartText = document.getElementById("heartModalText");


var brainModal = document.getElementById('brainModal');
var brainSpan = document.getElementsByClassName("close")[1];
var brainHeader = document.getElementsByClassName("modal-header")[1];
var brainText = document.getElementById("brainModalText");

var bodyModal = document.getElementById('bodyModal');
var bodySpan = document.getElementsByClassName("close")[2];
var bodyHeader = document.getElementsByClassName("modal-header")[2];
var bodyText = document.getElementById("bodyModalText");

var canvas = document.getElementById('humanCanvasTwin');
/* canvasLeft refers to the left canvas where we will display historical health */
var canvasLeft = document.getElementById('humanCanvasLeft');

function resizeCanvas(){
  canvas.width = window.innerWidth / 3; canvasLeft.width = window.innerWidth / 3;
  canvas.height = window.innerHeight * 0.95; canvasLeft.height = window.innerHeight * 0.95;
}

resizeCanvas()
var c = canvas.getContext('2d'); var cLeft = canvasLeft.getContext('2d');

// Loads body images
var bodyImg = new Image()
bodyImg.src = "../images/Human-Body-3.png"

// Loads organ images
var organDir = "../images/organs"
// brain
var brainImg = new Image()
brainImg.src = organDir + "/brain.png";
// heart
var heartImg = new Image()
heartImg.src = organDir + "/heart.png";

var body, brain, heart;
function resetTwinImages(){
  body = new imageRect(canvas, bodyImg, 0, 0, canvas.width*2/3, canvas.height, getBodyColour())
  brain = new imageRect(canvas, brainImg, canvas.width*4/5, 0, canvas.width/5, canvas.height/6, getBrainColour())
  heart = new imageRect(canvas, heartImg, canvas.width*4/5, canvas.height/6, canvas.width/5, canvas.height/6, getHeartColour())
}

var bodyLeft, brainLeft, heartLeft;
function resetLeftImages(){
  /*
  bodyLeft = new imageRect(bodyImg, 0, 0, canvasLeft.width*2/3, canvasLeft.height, getLeftBodyColour())
  brainLeft = new imageRect(brainImg, canvasLeft.width*4/5, 0, canvasLeft.width/5, canvasLeft.height/6, getLeftBrainColour())
  heartLeft = new imageRect(heartImg, canvasLeft.width*4/5, canvasLeft.height/6, canvasLeft.width/5, canvasLeft.height/6, getLeftHeartColour())
  */
  bodyLeft = new imageRect(canvasLeft, bodyImg, 0, 0, canvasLeft.width*2/3, canvasLeft.height, getBodyColour())
  brainLeft = new imageRect(canvasLeft, brainImg, canvasLeft.width*4/5, 0, canvasLeft.width/5, canvasLeft.height/6, getBrainColour())
  heartLeft = new imageRect(canvasLeft, heartImg, canvasLeft.width*4/5, canvasLeft.height/6, canvasLeft.width/5, canvasLeft.height/6, getHeartColour())
}

resetTwinImages();
resetLeftImages();


// Draw images
function drawArrowsToBody(){
  drawArrow(c, canvas.width*4/5, canvas.height/10, canvas.width/3, canvas.height/10) // Brain
  drawArrow(c, canvas.width*4/5, canvas.height/4, canvas.width/3, canvas.height/4) // Heart
}

// As above for left body
function drawArrowsToBodyLeft(){
  drawArrow(cLeft, canvasLeft.width*4/5, canvasLeft.height/10, canvasLeft.width/3, canvasLeft.height/10) // Brain
  drawArrow(cLeft, canvasLeft.width*4/5, canvasLeft.height/4, canvasLeft.width/3, canvasLeft.height/4) // Heart
}

// listen for resizing of window event. Scale the image when needed
function redrawTwin(){
  body.redraw()
  brain.redraw()
  heart.redraw()
  drawArrowsToBody()
}

// As above for left body
function redrawLeft(){
  console.log("redrawing left body")
  bodyLeft.redraw()
  brainLeft.redraw()
  heartLeft.redraw()
  drawArrowsToBodyLeft()
}

// Organs
bodyImg.onload  = function () {body.redraw(); bodyLeft.redraw(); drawArrowsToBody(); drawArrowsToBodyLeft()}
brainImg.onload = function () {brain.redraw(); brainLeft.redraw()}
heartImg.onload = function () {heart.redraw(); heartLeft.redraw()}


// Check if mouse is hovering over parts of the body
function isOnBody(x, y) {
  return body.isPointInside(x, y)
}
function isOnBrain(x, y) {
  return brain.isPointInside(x, y)
}
function isOnHeart(x, y) {
  return heart.isPointInside(x, y)
}
function isOnBodyLeft(x, y) {
  return bodyLeft.isPointInside(x, y)
}
function isOnBrainLeft(x, y) {
  return brainLeft.isPointInside(x, y)
}
function isOnHeartLeft(x, y) {
  return heartLeft.isPointInside(x, y)
}

var modals = [bodyModal, brainModal, heartModal, bodyLeftModal, brainLeftModal, heartLeftModal];

// hide all modals
function hideAllModals(){
  modals.forEach(function(modal){modal.style.display = "none";});
}


// actions to take if parts of twin body are clicked
function brainClicked(){
  brainText.innerHTML = getBrainText()
  brainHeader.style.backgroundColor = getBrainColour()//background-color?
  hideAllModals();
  brainModal.style.display = "block"; //Display brain modal
  updateImprovementFactor("brain_score", "improveBrain")
}
function heartClicked(){
  heartText.innerHTML = getHeartText()
  heartHeader.style.backgroundColor = getHeartColour()//background-color?
  hideAllModals();
  heartModal.style.display = "block"; //Display heart modal
}
function bodyClicked(){
  bodyText.innerHTML = getBodyText()
  bodyHeader.style.backgroundColor = getBodyColour()//background-color?
  hideAllModals();
  bodyModal.style.display = "block"; //Display body modal
}

// actions to take if parts of left body are clicked
// for the twin functions above, we have to calculate text/colour based on sliders
// for the left body, these things will be determined by the php
function leftBrainClicked(){
  hideAllModals();
  brainLeftModal.style.display = "block"; //Display brain modal
}
function leftHeartClicked(){
  hideAllModals();
  heartLeftModal.style.display = "block"; //Display heart modal
}
function leftBodyClicked(){
  hideAllModals();
  bodyLeftModal.style.display = "block"; //Display body modal
}


// Ben: When the user clicks on *span (x), close the modal
modals.forEach(function(modal){
  modal.onclick = function() {
    modal.style.display = "none";
  }
})
/*
heartSpan.onclick = function() {
  heartModal.style.display = "none";
}

brainSpan.onclick = function() {
  brainModal.style.display = "none";
}

bodySpan.onclick = function() {
  bodyModal.style.display = "none";
}
*/


var mouse = {
  x: undefined,
  y: undefined
}

function handleWindowResize(event){
  c.clearRect(0, 0, canvas.width, canvas.height); cLeft.clearRect(0, 0, canvasLeft.width, canvasLeft.height);
  resizeCanvas()
  resetTwinImages(); resetLeftImages();
  redrawTwin(); redrawLeft();
}

function handleMouseMove(event){
  mouse.x = event.x
  mouse.y = event.y
  // Run actions according to mouse position
  // Seems like a lot of repeated code but that's a job for later...
  if (isOnBody(mouse.x, mouse.y)) body.highlight()
  else body.redraw()
  if (isOnBrain(mouse.x, mouse.y)) brain.highlight()
  else brain.redraw()
  if (isOnHeart(mouse.x, mouse.y)) heart.highlight()
  else heart.redraw()
  if (isOnBodyLeft(mouse.x, mouse.y)) bodyLeft.highlight()
  else bodyLeft.redraw()
  if (isOnBrainLeft(mouse.x, mouse.y)) brainLeft.highlight()
  else brainLeft.redraw()
  if (isOnHeartLeft(mouse.x, mouse.y)) heartLeft.highlight()
  else heartLeft.redraw()

  //redrawing things will cover up arrows so make sure these are on top
  drawArrowsToBody()
  drawArrowsToBodyLeft()
}

function handleMouseClick(event){
  console.log('clicked mouse')
  mouse.x = event.x
  mouse.y = event.y
  console.log(mouse)

  // Run actions according to the click
  if (isOnBody(mouse.x, mouse.y)) bodyClicked()
  if (isOnBrain(mouse.x, mouse.y)) brainClicked()
  if (isOnHeart(mouse.x, mouse.y)) heartClicked()
  if (isOnBodyLeft(mouse.x, mouse.y)) leftBodyClicked()
  if (isOnBrainLeft(mouse.x, mouse.y)) leftBrainClicked()
  if (isOnHeartLeft(mouse.x, mouse.y)) leftHeartClicked()
}


// Listen for events
window.addEventListener('resize', handleWindowResize)
window.addEventListener('mousemove', handleMouseMove)
window.addEventListener('click', handleMouseClick)

//var cardiovascularHealth = getCardiovascularHealth()

//console.log(cardiovascularHealth)
// brain colour
