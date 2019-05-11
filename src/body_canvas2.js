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

var canvas = document.getElementById('humanCanvas');

function resizeCanvas(){
  canvas.width = window.innerWidth / 3;
  canvas.height = window.innerHeight * 0.95;
}

resizeCanvas()
var c = canvas.getContext('2d')

// Loads body images
var bodyImg = new Image()
bodyImg.src = "../images/Human-Body-3.png";

// Loads organ images
var organDir = "../images/organs"
// brain
var brainImg = new Image()
brainImg.src = organDir + "/brain.png";
// heart
var heartImg = new Image()
heartImg.src = organDir + "/heart.png";

/*
var body = new imageRect(bodyImg, 0, 0, canvas.width*2/3, canvas.height, getBodyColour())
var brain = new imageRect(brainImg, canvas.width*2/3, 0, canvas.width/3, canvas.height/3, getBrainColour())
var heart = new imageRect(heartImg, canvas.width*2/3, canvas.height/3, canvas.width/3, canvas.height/3, getHeartColour())
*/
var body, brain, heart;
function resetImages(){
  body = new imageRect(bodyImg, 0, 0, canvas.width*2/3, canvas.height, getBodyColour())
  brain = new imageRect(brainImg, canvas.width*4/5, 0, canvas.width/5, canvas.height/6, getBrainColour())
  heart = new imageRect(heartImg, canvas.width*4/5, canvas.height/6, canvas.width/5, canvas.height/6, getHeartColour())
}

resetImages()

// Draw images
function drawArrowsToBody(){
  drawArrow(canvas.width*4/5, canvas.height/10, canvas.width/3, canvas.height/10) // Brain
  drawArrow(canvas.width*4/5, canvas.height/4, canvas.width/3, canvas.height/4) // Heart
}

// listen for resizing of window event. Scale the image when needed
function redraw(){
  body.redraw()
  brain.redraw()
  heart.redraw()
  drawArrowsToBody()
}

// Organs
bodyImg.onload  = function () {body.redraw(); drawArrowsToBody();}
brainImg.onload = function () {brain.redraw()}
heartImg.onload = function () {heart.redraw()}


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

// actions to take if parts of body are clicked
function brainClicked(){
  brainText.innerHTML = getBrainText()
  brainHeader.style.backgroundColor = getBrainColour()//background-color?
  bodyModal.style.display = "none"; //Hide the body modal if it is open
  heartModal.style.display = "none"; //Hide the heart modal if it is open
  brainModal.style.display = "block"; //Display brain modal
  updateImprovementFactor("brain_score", "improveBrain")
}
function heartClicked(){
  heartText.innerHTML = getHeartText()
  heartHeader.style.backgroundColor = getHeartColour()//background-color?
  bodyModal.style.display = "none"; //Hide the body modal if it is open
  brainModal.style.display = "none"; //Hide the brain modal if it is open
  heartModal.style.display = "block"; //Display heart modal
}
function bodyClicked(){
  bodyText.innerHTML = getBodyText()
  bodyHeader.style.backgroundColor = getBodyColour()//background-color?
  brainModal.style.display = "none"; //Hide the brain modal if it is open
  heartModal.style.display = "none"; //Hide the heart modal if it is open
  bodyModal.style.display = "block"; //Display body modal
}


// Ben: When the user clicks on *span (x), close the modal
heartSpan.onclick = function() {
  heartModal.style.display = "none";
}

brainSpan.onclick = function() {
  brainModal.style.display = "none";
}

bodySpan.onclick = function() {
  bodyModal.style.display = "none";
}


var mouse = {
  x: undefined,
  y: undefined
}

function handleWindowResize(event){
  c.clearRect(0, 0, canvas.width, canvas.height);
  resizeCanvas()
  resetImages()
  redraw()
}

function handleMouseMove(event){
  mouse.x = event.x
  mouse.y = event.y
  // Run actions according to mouse position
  if (isOnBody(mouse.x, mouse.y)) {body.highlight(); drawArrowsToBody()}
  else {body.redraw(); drawArrowsToBody()}
  if (isOnBrain(mouse.x, mouse.y)) brain.highlight()
  else brain.redraw()
  if (isOnHeart(mouse.x, mouse.y)) heart.highlight()
  else heart.redraw()
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
}


// Listen for events
window.addEventListener('resize', handleWindowResize)
window.addEventListener('mousemove', handleMouseMove)
window.addEventListener('click', handleMouseClick)

//var cardiovascularHealth = getCardiovascularHealth()

//console.log(cardiovascularHealth)
// brain colour
