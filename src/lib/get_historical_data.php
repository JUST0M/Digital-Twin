<?php
// Get historical data
$sql = "SELECT date FROM user_factor_values WHERE user_id=$userId GROUP BY date ORDER BY date DESC";
$result = $conn->query($sql);
$dates = array();
while($row = $result->fetch_row()) {
  array_push($dates, $row[0]);
}

$mostRecent = $dates[0];
// if a date to use has been POSTED then use that otherwise just use most recent date
$dateToUse = isset($_POST["date"]) ? $_POST["date"] : $mostRecent;
$dateOptions = "";
foreach($dates as $date){
  // make sure the date we are currently using is selected
  if ($date == $dateToUse) $dateOptions .= "<option value=\"$date\" selected>$date</option>";
  // other options are not selected
  else $dateOptions .= "<option value=\"$date\">$date</option>";
}



$sql = "SELECT factor_id, value FROM user_factor_values WHERE user_id=$userId AND date=\"$dateToUse\"";
$result = $conn->query($sql);
$factorValues = array();
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $factorValues[$row["factor_id"]] = $row["value"];
    //echo $row["factor_id"];
  }
}
else {
  echo "no data for this user and date...";
}


// probably need to change $allScoresInfo so it uses factor_id rather than just the name
// TODO: write this function (could probably go in separate included file)
function calcHistoricalTertile($factorValues, $allScoresInfo, $scoreId){
  $calcScore = function($scoreId) use ($factorValues, $allScoresInfo, &$factorIsGood, &$scoreIsGood){
    $score = 0;
    // for each factor that contributes towards this score
    // check if the factor meets the database-specified requirements
    foreach($allScoresInfo[$scoreId]["factors"] as $factorId => $factorInfo){
      $score += $factorIsGood($factorId, $factorInfo) ? 1 : 0;
    }
    // for each risk score that this risk score depends on
    // check if the risk score meets the database-specified requirements
    foreach($allScoresInfo[$scoreId]["scores"] as $scoreId => $scoreInfo){
      $score += $scoreIsGood($scoreId, $scoreInfo) ? 1 : 0;
    }

    return($score);
  };

  $factorIsGood = function($factorId, $factorInfo) use ($factorValues){
    // if the historical data doesn't contain info for a factor, assume it is healthy for now
    if (!in_array($factorId, array_keys($factorValues))) return(true);
    $factorValue = $factorValues[$factorId];
    // factorData.withinRange determines whether the factor should lie within or outside
    // the range specified by min and max
    if ($factorInfo["withinRange"]){
      return($factorValue >= $factorInfo["min"] && $factorValue <= $factorInfo["max"]);
    }
    else{
      return($factorValue <= $factorInfo["min"] || $factorValue <= $factorInfo["max"]);
    }
  };

  $scoreIsGood = function($scoreId, $scoreInfo) use ($factorValues, $allScoresInfo, $calcScore){
    $scoreValue = $calcScore($scoreId);
    if ($scoreInfo["withinRange"]){
      return($scoreValue >= $scoreInfo["min"] && $scoreValue <= $scoreInfo["max"]);
    }
    else{
      return($scoreValue <= $scoreInfo["min"] || $scoreValue >= $scoreInfo["max"]);
    }
  };

  $scoreValue = $calcScore($scoreId);
  if ($scoreValue <= $allScoresInfo[$scoreId]["tertileLow"]) $tertile = 0;
  else if ($scoreValue <= $allScoresInfo[$scoreId]["tertileHigh"]) $tertile = 1;
  else $tertile = 2;
  // reverse tertiles if high score is bad
  if (!$allScoresInfo[$scoreId]["highScoreGood"]) $tertile = 2 - $tertile;
  return $tertile;

}

$brainScoreId = 1;
$colours = ["red", "orange", "green"];
$historicalTertileBrain = calcHistoricalTertile($factorValues, $allScoresInfo, $brainScoreId);
$brainColourLeft = $colours[$historicalTertileBrain];

// just set all to brain colour for now
$bodyColourLeft = $brainColourLeft;
$heartColourLeft = $brainColourLeft;

$colourJs = "var bodyLeftColour = \"$bodyColourLeft\"; var brainLeftColour = \"$brainColourLeft\"; var heartLeftColour = \"$heartColourLeft\";"
?>
