// given the name of score, returns the value of that score
// uses database results to determine how the score is calculated
function calcScore(scoreName){
  var score = 0;
  // for each factor that contributes towards this score
  // check if the factor meets the database-specified requirements
  riskScoreInfo[scoreName].factors.forEach(function(factorInfo){
    score += factorIsGood(factorInfo) ? 1 : 0;
  });
  // for each risk score that this risk score depends on
  // check if the risk score meets the database-specified requirements
  riskScoreInfo[scoreName].scores.forEach(function(scoreInfo){
    score += scoreIsGood(scoreInfo) ? 1 : 0;
  });

  return(score);
}

// given information about a risk factor, calculates whether it meets required threshold(s)
function factorIsGood(factorInfo){
  var factorValue;
  // boolean
  if (factorInfo.type == 0) {
    factorValue = document.getElementById("checkbox-".concat(factorInfo.name)).checked ? 1 : 0;
  }
  else {
    factorValue = document.getElementById("slider-".concat(factorInfo.name)).value;
  }

  // factorData.withinRange determines whether the factor should lie within or outside
  // the range specified by min and max
  if (factorInfo.withinRange){
    return(factorValue >= factorInfo.min && factorValue <= factorInfo.max)
  }
  else{
    return(factorValue <= factorInfo.min || factorValue >= factorInfo.max)
  }
}

// given information about a risk score, calculates whether it meets required threshold(s)
function scoreIsGood(scoreInfo){
  var scoreValue = calcScore(scoreInfo.name);
  if (scoreInfo.withinRange){
    return(scoreValue >= scoreInfo.min && scoreValue <= scoreInfo.max)
  }
  else{
    return(scoreValue <= scoreInfo.min || scoreValue >= scoreInfo.max)
  }
}

// calculates the percentage difference between a factor's value and what is
// considered healthy (given by a range, min to max)
// withinRange indicates whether 'healthy' is considered inside or outside the given range
function percentDiffToHealthy(factorValue, min, max, withinRange){
  if (withinRange){
    if (factorValue >= min && factorValue <= max) {return(0)}
    else if(factorValue < min) {return((min - factorValue) / min)}
    else {return((factorValue - max) / max)}
  }

  else{
    if (factorValue <= min || factorValue >= max) {return(0)}
    else if(factorValue - min < max - factorValue) {return((factorValue - min) / min)}
    else {return((max - factorValue) / max)}
  }
}

// given the name of a score and a value, use database values to return tertile
function calcTertile(scoreName, scoreValue){
  var tertile
  if (scoreValue <= riskScoreInfo[scoreName].tertileLow){tertile = 0}
  else if (scoreValue <= riskScoreInfo[scoreName].tertileHigh){tertile = 1}
  else {tertile = 2}
  // reverse tertiles if high score is bad
  if (!riskScoreInfo[scoreName].highScoreGood) {tertile = 2 - tertile}
  return tertile;
}

const colours = ["red", "orange", "green"];

// Colour indicator for the body parts
function getBodyColour(){
  return getBrainColour() // Tentatively, only brain has a score indicator
}
function getHeartColour(){
  return getBrainColour() // Tentatively, only brain has a score indicator
}
function getBrainColour(){
  var brainScore = calcScore("brain_score");
  var tertile = calcTertile("brain_score", brainScore);
  return colours[tertile];
}

// Text options for the body parts
function getBodyText(){
  return getBrainText() // Tentatively, only brain has a score indicator
}
function getHeartText(){
  return getBrainText() // Tentatively, only brain has a score indicator
}
function getBrainText(){
  var healthIndex = calcScore("brain_score");

  // Split into tertiles by the JAMA paper
  if (healthIndex < 6){ // Lower tertile - 0 to 5
    return "Low health text"
  }
  else if (healthIndex < 7){ // Middle tertile - 6
    return "Mid health text"
  }
  else{ // Upper tertile - 7 to 8
    return "High health text"
  }
}


// TODO: currently won't work for the blood pressure ones since these use the dependent risk score thing...
// solution to this will take a bit of effort
function updateImprovementFactor(scoreName, elemId){
  console.log("doing the ting");
  var worstDiff = 0;
  var worstFactor = "none";
  var url = "";
  for (var i = 0; i < riskScoreInfo[scoreName].factors.length; i++) {
    var factorInfo = riskScoreInfo[scoreName].factors[i];
    // just give bad checkbox's a score of 0.5 for now...
    // percentDiffToHealthy would give a score of 1 and this seems harsh
    var score;
    if (factorInfo.type == 0) {
      score = (document.getElementById("checkbox-".concat(factorInfo.name)).checked ? 1 : 0) == factorInfo.min ? 0 : 0.5;
    }
    else{
      var factorVal = document.getElementById("slider-".concat(factorInfo.name)).value;
      score = percentDiffToHealthy(factorVal, factorInfo.min, factorInfo.max, factorInfo.withinRange);
    }
    if (score > worstDiff) {
      worstDiff = score; worstFactor = factorInfo.name;
      if (factorInfo.infoLink != null){url = factorInfo.infoLink;}
    }
  }


  document.getElementById(elemId).innerHTML = worstFactor;
  document.getElementById(elemId).href = url;
}
