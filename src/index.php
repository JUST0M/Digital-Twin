<!DOCTYPE html>
<?php

$red = "#f00000";
$yellow = "#f0c800";
$green = "#00c800";
$colours = array($red, $yellow, $green);

$sliderIds = array();
$checkboxIds = array();

// return data input html (either a slider or a checkbox)
function dataInputHtml($factorData) {
  global $colours; global $sliderIds; global $checkboxIds;
  $min = $factorData["min"]; $max = $factorData["max"];
  $dType = $factorData["type"];
  $question = $factorData["question"]; $default = $factorData["default"];
  $factorName = $factorData["factor"];
  // if boolean datatype then just add a checkbox
  if ($dType == 0) {
    array_push($checkboxIds, "\"$factorName\"");
    $checkboxHtml = "<tr>
                       <td><input type=\"checkbox\" id=\"checkbox-$factorName\" unchecked> $question<br/>
                       </td>
                     </tr>";
    return($checkboxHtml);
  }

  // for int and float add a slider
  else{
    array_push($sliderIds, "\"$factorName\"");
    $range = $max - $min;
    $gradientHtml = "90deg";
    foreach ($factorData["colRanges"] as $colRange) {
      // if healthiness is null then this factor doesn't have any data in the factor_ranges table
      // in this case we will just have an uncoloured slider
      if($colRange["healthiness"] != null){
        $col = $colours[$colRange["healthiness"]];
        $lo = (($colRange["min"] - $min) / $range) * 100;
        $hi = (($colRange["max"] - $min) / $range) * 100;
        $gradientHtml .= ", $col $lo% $hi%";
      }
    }

    // dType of 1 means int, 2 means float
    $step = $dType == 1 ? 1 : 0.1;

    $sliderHtml = "<tr>
                     <td>$question: <input type=\"number\" min=\"$min\" max=\"$max\" value=\"$default\" id=\"box-$factorName\"><br/>
                       <input type=\"range\" min=\"$min\" max=\"$max\" step=\"$step\" value=\"$default\" class=\"slider\" id=\"slider-$factorName\" style=\"background:linear-gradient($gradientHtml)\">
                     </td>
                   </tr>";
    return($sliderHtml);
  }
}



// $sliderHtml = slider("Vigorous Physical Activity (minutes per week)", 0, 300, 75, array(array("healthiness" => 0, "min" => 0, "max" => 0), array("healthiness" => 1, "min" => 90, "max" => 135), array("healthiness" => 2, "min" => 150, "max" => 300)), 1);

$servername = "localhost";
$username = "master";
$password = "D1g1talTw1n";
$dbname = "digital-twin";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT f.factor_id as id, f.factor as factor, f.question as q, f.min as min, f.max as max, f.def as def, f.data_type as t, r.healthiness as healthiness, r.min as rmin, r.max as rmax FROM factors f LEFT JOIN factor_ranges r ON f.factor_id = r.factor_id";
$result = $conn->query($sql);

$sliderHtml = "";

if ($result->num_rows > 0) {
  $factorInfo = array();
  while($row = $result->fetch_assoc()) {
    //have already put info in for this id so just add a colour range
    if(in_array($row["id"], array_keys($factorInfo))){
      array_push($factorInfo[$row["id"]]["colRanges"], array("healthiness" => $row["healthiness"], "min" => $row["rmin"], "max" => $row["rmax"]));
    }
    else{
      $factorInfo[$row["id"]] = array("factor" => $row["factor"],
                                      "question" => $row["q"],
                                      "min" => $row["min"],
                                      "max" => $row["max"],
                                      "default" => $row["def"],
                                      "type" => $row["t"],
                                      "colRanges" => array(array("healthiness" => $row["healthiness"], "min" => $row["rmin"], "max" => $row["rmax"])));

    }
    //echo "id: " . $row["factor_id"]. " - factor: " . $row["factor"]. " - question: " . $row["question"]. "<br>";
  }
  $n = 0;
  foreach($factorInfo as $key => $fInfo){
    $toPrint = count($factorInfo);
    $sliderHtml .= dataInputHtml($fInfo);
    $n++;
  }
}
else {
  echo "0 results";
}

// create array of the ids to be referenced by the javascript
$jsSliderIds = "[" . join(", ", $sliderIds) . "]";
$jsCheckboxIds = "[" . join(", ", $checkboxIds) . "]";

$sql = "SELECT risk_scores.score_id as id, name, high_score_good as highScoreGood, tertile_low as tertileLow, tertile_high as tertileHigh, factor as factorName, data_type as type, risk_factors.min as min, risk_factors.max as max, inside_range as withinRange FROM risk_factors JOIN risk_scores ON risk_scores.score_id = risk_factors.score_id JOIN factors ON risk_factors.factor_id = factors.factor_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  $allScoresInfo = array();
  while($row = $result->fetch_assoc()) {
    //have already put info in for this score so just add a factor
    if(in_array($row["id"], array_keys($allScoresInfo))){
      $allScoresInfo[$row["id"]]["factors"][$row["factorName"]] = array("type" => $row["type"], "min" => $row["min"], "max" => $row["max"], "withinRange" => $row["withinRange"]);
    }
    else{
      $allScoresInfo[$row["id"]] = array( "name" => $row["name"],
                                      "highScoreGood" => $row["highScoreGood"],
                                      "tertileLow" => $row["tertileLow"],
                                      "tertileHigh" => $row["tertileHigh"],
                                      "factors" => array($row["factorName"] => array("type" => $row["type"], "min" => $row["min"], "max" => $row["max"], "withinRange" => $row["withinRange"])),
                                      "scores" => array());
    }
  }
}

$sql = "SELECT rsd.score_id as id, name as score2, rsd.min as min, rsd.max as max, inside_range as withinRange  FROM risk_score_dependencies AS rsd JOIN risk_scores ON risk_scores.score_id = rsd.score_dependency_id;";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    if(in_array($row["id"], array_keys($allScoresInfo))){
      $allScoresInfo[$row["id"]]["scores"][$row["score2"]] = array("min" => $row["min"], "max" => $row["max"], "withinRange" => $row["withinRange"]);
    }
  }
}

$conn->close();
$riskScoreInfosJs = array();
foreach($allScoresInfo as $scoreId => $scoreInfo){
  $scoreName = $scoreInfo["name"];
  $factorsArray = array();
  foreach($scoreInfo["factors"] as $factorName => $factorInfo){
    $type = $factorInfo["type"]; $min = $factorInfo["min"]; $max = $factorInfo["max"];
    $withinRange = $factorInfo["withinRange"];
    array_push($factorsArray, "{name: \"$factorName\", type: $type, min: $min, max: $max, withinRange: $withinRange}");
  }
  $factorsJs = "[" . join(", ", $factorsArray) . "]";

  $scoresArray = array();
  foreach($scoreInfo["scores"] as $scoreName2 => $scoreInfo2){
    $min = $scoreInfo2["min"]; $max = $scoreInfo2["max"];
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
should probably using php to do the calculations rather than weirdl passing
data between php and js like this. But we don't have time right now so we are
going with messy option */
$jsText = "<script>
            var riskScoreInfo = $riskScoreInfo;
          </script>";

?>


<html lang="en"; style="height:100%">
<head>
  <meta charset="UTF-8">
  <title>Digital Twin</title>

  <link rel="stylesheet" type="text/css" href="main.css">
  <link rel="stylesheet" type="text/css" href="modalStyle.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
  <script>
    var sliderIds = <?php echo $jsSliderIds; ?>;
    var checkboxIds = <?php echo $jsCheckboxIds; ?>;
  </script>
  <?php echo $jsText; ?>
  <script src="sliders.js"></script>
  <script src="lib/functions.js"></script>
  <script src="health.js"></script>
</head>
<body>
  <div style = "height: 100%; line-height: 2em">
    <table style = "float: left">
      <tr>
        <th rowspan="9" style="width:50%"><canvas id="humanCanvas"></canvas></th>
      </tr>
    </table>

    <!-- Yoojin: New <div>s added so that we can separate the left hand side and the right hand side, thus being able to fix the canvas and scroll on the right hand side. Checked by adding 10+ sliders. See main.css for table-wrapper, etc.-->

    <div id="table-wrapper">
      <div id="table-scroll">
        <table style = "float: left">
          <!-- (Kareem): the sliders have been updated to contain information on nine different measures that are used in the formulation-->
          <?php echo $sliderHtml; ?>
        </table>
      </div>
    </div>

    <!-- Ben: Pop up boxes, represented by modals -->
    <div id="heartModal" class="modal" style="display: none" >
      <!-- Modal content -->
      <div class="modal-content">
        <div class="modal-header">
          <span class="close">&times;</span>
          <h2>Heart Modal</h2>
        </div>
        <div class="modal-body">
          <p>Heart modal text...</p>
        </div>
      </div>
    </div>

    <div id="brainModal" class="modal" style="display: none" >
      <!-- Modal content -->
      <div class="modal-content">
        <div class="modal-header">
          <span class="close">&times;</span>
          <h2>Brain Modal</h2>
        </div>
        <div class="modal-body">
          <p>Brain modal text...</p>
          <p>The thing you most need to improve on is: </p>
          <p id="improveBrain"></p>
        </div>
      </div>
    </div>

    <div id="bodyModal" class="modal" style="display: none" >
      <!-- Modal content -->
      <div class="modal-content">
        <div class="modal-header">
          <span class="close">&times;</span>
          <h2>Body Modal</h2>
        </div>
        <div class="modal-body">
          <p>Body modal text...</p>
        </div>
      </div>
    </div>
  </div>
  <script src="body_canvas.js"></script>
</body>
</html>
