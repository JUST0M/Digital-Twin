//Ben: Fetch appropriate elements for picture popups
var heartModal = document.getElementById('heartModal');
var heartSpan = document.getElementsByClassName("close")[0];

var brainModal = document.getElementById('brainModal');
var brainSpan = document.getElementsByClassName("close")[1];

var bodyModal = document.getElementById('bodyModal');
var bodySpan = document.getElementsByClassName("close")[2];

var canvas = document.getElementById('humanCanvas');

function resizeCanvas(){
  canvas.width = window.innerWidth / 2;
  canvas.height = window.innerHeight * 0.98;
}

resizeCanvas()
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
/*
function isOnHead() {
  xDiff = mouse.x - headX
  yDiff = mouse.y - headY
  return(Math.sqrt(xDiff * xDiff + yDiff * yDiff) <= headRadius)
}
*/

// Image Rectangles

var imageRect = (function () {

    // constructor
    function imageRect(img, x, y, width, height, fillColour, stroke, strokewidth) {
        this.x = x;
        this.y = y;
        this.img = img;
        this.width = width;
        this.height = height;
        this.fillColour = fillColour || "grey"
        this.stroke = stroke || "skyblue";
        this.strokewidth = strokewidth || 3;
        this.redraw(this.x, this.y);
        return (this);
    }
    imageRect.prototype.redraw = function (x, y) {
        this.x = x || this.x;
        this.y = y || this.y;
        this.draw(this.stroke);
        return (this);
    }
    //
    imageRect.prototype.highlight = function (x, y) {
        this.x = x || this.x;
        this.y = y || this.y;
        this.draw("orange");
        return (this);
    }
    //
    imageRect.prototype.draw = function (stroke) {
        c.save();
        c.beginPath();
        c.strokeStyle = stroke;
        c.lineWidth = this.strokewidth;
        c.rect(this.x, this.y, this.width, this.height);
        c.stroke();
        c.fillStyle = this.fillColour
        c.fillRect(this.x, this.y, this.width, this.height);
        c.drawImage(this.img, this.x, this.y, this.width, this.height)
        c.restore();
    }
    //
    imageRect.prototype.isPointInside = function (x, y) {
        return (x >= this.x && x <= this.x + this.width && y >= this.y && y <= this.y + this.height);
    }

    return imageRect;
})();


// Loading body images
var bodyImg = new Image()
bodyImg.src = "../images/Human-Body-3.png";

// Loading organ images
var organDir = "../images/organs"
// Brain
var brainImg = new Image()
brainImg.src = organDir + "/brain.png";
// Heart
var heartImg = new Image()
heartImg.src = organDir + "/heart.png";

var body = new imageRect(bodyImg, 0, 0, canvas.width/2, canvas.height)
var brain = new imageRect(brainImg, canvas.width*3/4, 0, canvas.width/4, canvas.height/4)
var heart = new imageRect(heartImg, canvas.width*3/4, canvas.height/4, canvas.width/4, canvas.height/4)

function resetImages(){
  body = new imageRect(bodyImg, 0, 0, canvas.width/2, canvas.height)
  brain = new imageRect(brainImg, canvas.width*3/4, 0, canvas.width/4, canvas.height/4)
  heart = new imageRect(heartImg, canvas.width*3/4, canvas.height/4, canvas.width/4, canvas.height/4)
}



function drawArrow(fromx, fromy, tox, toy){
  c.beginPath()
  var headlen = 10;   // length of head in pixels
  var angle = Math.atan2(toy-fromy,tox-fromx);
  c.moveTo(fromx, fromy);
  c.lineTo(tox, toy);
  c.lineTo(tox-headlen*Math.cos(angle-Math.PI/6),toy-headlen*Math.sin(angle-Math.PI/6));
  c.moveTo(tox, toy);
  c.lineTo(tox-headlen*Math.cos(angle+Math.PI/6),toy-headlen*Math.sin(angle+Math.PI/6));
  c.stroke()
}

// Draw images
function drawArrowsToBody(){
  drawArrow(canvas.width*3/4 * 0.98, canvas.height/8, canvas.width/4, canvas.height/8) // Brain
  drawArrow(canvas.width*3/4 * 0.98, canvas.height/3, canvas.width/4, canvas.height/3.5) // Heart
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
  bodyModal.style.display = "none"; //Hide the body modal if it is open
  heartModal.style.display = "none"; //Hide the heart modal if it is open
  brainModal.style.display = "block"; //Display brain modal
}
function heartClicked(){
  bodyModal.style.display = "none"; //Hide the body modal if it is open
  brainModal.style.display = "none"; //Hide the brain modal if it is open
  heartModal.style.display = "block"; //Display heart modal
}
function bodyClicked(){
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


// The following lines *should* encode the
// Cardiovascular Health Indicators
var bmi = parseInt(document.getElementById('s0').value, 10)
var vpa = parseInt(document.getElementById('s1').value, 10)
// var pvo2 =
var alc = parseInt(document.getElementById('s2').value, 10)
var smo = parseInt(document.getElementById('smoking').value, 10) // "have you been a smoker in the past six months?"
var asbp = parseInt(document.getElementById('s3').value, 10)
var adbp = parseInt(document.getElementById('s4').value, 10)
var pedbp = parseInt(document.getElementById('s5').value, 10)
var cho = parseInt(document.getElementById('s6').value, 10)
var glu = parseInt(document.getElementById('s7').value, 10)


// Array containing 1s and 0s detailing whether each of the study's thresholds are met
var healthPoints = []

if (bmi < 25) {healthPoints[0].push(1)} else {healthPoints[0].push(0)}
if (vpa >= 75) {healthPoints[1].push(1)} else {healthPoints[1].push(0)}
if (alc < 8) {healthPoints[2].push(1)} else {healthPoints[2].push(0)}
if (smo = 0) {healthPoints[3].push(1)} else {healthPoints[3].push(0)}
if (asdp < 130 && adbp < 80) {healthPoints[4].push(1)} else {healthPoints([4].push(0)}
if (pedbp < 90) {healthPoints[5].push(1)} else {healthPoints[5].push(0)}
if (cho < 200) {healthPoints[6].push(1)} else {healthPoints[6].push(0)}
if (glu < 100) {healthPoints[7].push(1)} else {healthPoints[7].push(0)}

// Patient's score

var healthIndex = 0

for (i = 0; i < healthPoints.length; i ++) { healthIndex += healthPoints[i] }

// brain colour

