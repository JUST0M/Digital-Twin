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
