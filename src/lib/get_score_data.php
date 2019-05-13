<?php
$sql = "SELECT risk_scores.score_id as id, name, high_score_good as highScoreGood, tertile_low as tertileLow, tertile_high as tertileHigh, factor as factorName, factors.factor_id as factor_id, data_type as type, info_link, risk_factors.min as min, risk_factors.max as max, inside_range as withinRange FROM risk_factors JOIN risk_scores ON risk_scores.score_id = risk_factors.score_id JOIN factors ON risk_factors.factor_id = factors.factor_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  $allScoresInfo = array();
  while($row = $result->fetch_assoc()) {
    //have already put info in for this score so just add a factor
    if(in_array($row["id"], array_keys($allScoresInfo))){
      $allScoresInfo[$row["id"]]["factors"][$row["factor_id"]] = array("factorName" => $row["factorName"], "type" => $row["type"], "infoLink" => $row["info_link"], "min" => $row["min"], "max" => $row["max"], "withinRange" => $row["withinRange"]);
    }
    else{
      $allScoresInfo[$row["id"]] = array( "name" => $row["name"],
                                      "highScoreGood" => $row["highScoreGood"],
                                      "tertileLow" => $row["tertileLow"],
                                      "tertileHigh" => $row["tertileHigh"],
                                      "factors" => array($row["factor_id"] => array("factorName" => $row["factorName"], "type" => $row["type"], "infoLink" => $row["info_link"], "min" => $row["min"], "max" => $row["max"], "withinRange" => $row["withinRange"])),
                                      "scores" => array());
    }
  }
}

$sql = "SELECT rsd.score_id as id, risk_scores.score_id as id2, name as score2, rsd.min as min, rsd.max as max, inside_range as withinRange  FROM risk_score_dependencies AS rsd JOIN risk_scores ON risk_scores.score_id = rsd.score_dependency_id;";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    if(in_array($row["id"], array_keys($allScoresInfo))){
      $allScoresInfo[$row["id"]]["scores"][$row["id2"]] = array("name" => $row["score2"], "min" => $row["min"], "max" => $row["max"], "withinRange" => $row["withinRange"]);
    }
  }
}

$riskScoreInfosJs = array();
foreach($allScoresInfo as $scoreId => $scoreInfo){
  $scoreName = $scoreInfo["name"];
  $factorsArray = array();
  foreach($scoreInfo["factors"] as $factorId => $factorInfo){
    $type = $factorInfo["type"]; $min = $factorInfo["min"]; $max = $factorInfo["max"];
    $factorName = $factorInfo["factorName"]; $infoLink = $factorInfo["infoLink"];
    $withinRange = $factorInfo["withinRange"];
    array_push($factorsArray, "{name: \"$factorName\", type: $type, min: $min, max: $max, withinRange: $withinRange, infoLink: \"$infoLink\"}");
  }
  $factorsJs = "[" . join(", ", $factorsArray) . "]";

  $scoresArray = array();
  foreach($scoreInfo["scores"] as $scoreId2 => $scoreInfo2){
    $min = $scoreInfo2["min"]; $max = $scoreInfo2["max"];
    $scoreName2 = $scoreInfo2["name"];
    $withinRange = $scoreInfo2["withinRange"];
    array_push($scoresArray, "{name: \"$scoreName2\", min: $min, max: $max, withinRange: $withinRange}");
  }
  $scoresJs = "[" . join(", ", $scoresArray) . "]";

  $tertileLowJs = $scoreInfo["tertileLow"];
  $tertileHighJs = $scoreInfo["tertileHigh"];
  $highScoreGoodJs = $scoreInfo["highScoreGood"];

  $riskScoreInfoJs = "{factors: $factorsJs, scores: $scoresJs, tertileLow: $tertileLowJs, tertileHigh: $tertileHighJs, highScoreGood: $highScoreGoodJs}";
  array_push($riskScoreInfosJs, "$scoreName: $riskScoreInfoJs");
}

$riskScoreInfo = "{" . join(", ", $riskScoreInfosJs) . "}";

/* this seems like a terrible way of doing things...
should probably using php to do the calculations rather than weirdly passing
data between php and js like this. But we don't have time right now so we are
going with messy option */
$jsText = "<script>
            var riskScoreInfo = $riskScoreInfo;
          </script>";
?>
