var canvas = document.querySelector('canvas');
canvas.width = window.innerWidth / 2;
canvas.height = window.innerHeight;

var c = canvas.getContext('2d')

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

var mouse = {
  x: undefined,
  y: undefined
}

function isOnHead() {
  xDiff = mouse.x - headX
  yDiff = mouse.y - headY
  return(Math.sqrt(xDiff * xDiff + yDiff * yDiff) <= headRadius)
}

// listen for click event and check if click is on head
// if so, run some function
window.addEventListener('click', function(event) {
  console.log('clicked mouse')
  mouse.x = event.x
  mouse.y = event.y
  console.log(mouse)
  if (isOnHead()) headClicked();

})

// action to take if head is clicked
function headClicked() {
  console.log('head clicked')
}
