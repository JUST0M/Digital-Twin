function metThreshold(healthPoints){
  healthPoints.push(1)
}
function missedThreshold(healthPoints){
  healthPoints.push(0)  
}

// Calculates Cardiovascular Health
// Returns a value from 0 - 8
function getCardiovascularHealth(){
  // Array containing 1s and 0s detailing whether each of the study's thresholds are met
  var healthPoints = []

  // The following lines *should* encode the
  // Cardiovascular Health Indicators
  var bmi = parseInt(document.getElementById('s0').value, 10)
  var vpa = parseInt(document.getElementById('s1').value, 10)
  // var pvo2 =
  var alc = parseInt(document.getElementById('s2').value, 10)
  var smo = $('#smoking').is(":checked") // "have you been a smoker in the past six months?"
  var asbp = parseInt(document.getElementById('s3').value, 10)
  var adbp = parseInt(document.getElementById('s4').value, 10)
  var pedbp = parseInt(document.getElementById('s5').value, 10)
  var cho = parseInt(document.getElementById('s6').value, 10)
  var glu = parseInt(document.getElementById('s7').value, 10)

  if (bmi < 25) {metThreshold(healthPoints)} else {missedThreshold(healthPoints)}
  if (vpa >= 75) {metThreshold(healthPoints)} else {missedThreshold(healthPoints)}
  if (alc < 8) {metThreshold(healthPoints)} else {missedThreshold(healthPoints)}
  if (!smo) {metThreshold(healthPoints)} else {missedThreshold(healthPoints)}
  if (asbp < 130 && adbp < 80) {metThreshold(healthPoints)} else {missedThreshold(healthPoints)}
  if (pedbp < 90) {metThreshold(healthPoints)} else {missedThreshold(healthPoints)}
  if (cho < 200) {metThreshold(healthPoints)} else {missedThreshold(healthPoints)}
  if (glu < 100) {metThreshold(healthPoints)} else {missedThreshold(healthPoints)}

  // Patient's score

  var healthIndex = 0

  for (i = 0; i < healthPoints.length; i ++) { 
    healthIndex += healthPoints[i] 
  }
  return healthIndex
}

// Calculates heart for each part of the body 
function getBodyHealth(){
  return getCardiovascularHealth()
}

function getHeartHealth(){
  return getCardiovascularHealth()
}

function getBrainHealth(){
  var healthIndex = getCardiovascularHealth()
  console.log("Brain Health: " + healthIndex)
  return healthIndex
}

// Colour indicator for the body parts
function getBodyColour(){
  return getBrainColour() // Tentatively, only brain has a score indicator
}
function getHeartColour(){
  return getBrainColour() // Tentatively, only brain has a score indicator
}
function getBrainColour(){
  var healthIndex = getBrainHealth()

  // Split into tertiles by the JAMA paper
  if (healthIndex < 6){ // Lower tertile - 0 to 5
    return "red"
  }
  else if (healthIndex < 7){ // Middle tertile - 6
    return "orange"
  }
  else{ // Upper tertile - 7 to 8
    return "green"
  }
}