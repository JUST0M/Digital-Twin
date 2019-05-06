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

    $sliderHtml = "<tr>
                     <td>$question: <input type=\"number\" min=\"$min\" max=\"$max\" value=\"$default\" id=\"box-$factorName\"><br/>
                       <input type=\"range\" min=\"$min\" max=\"$max\" value=\"$default\" class=\"slider\" id=\"slider-$factorName\" style=\"background:linear-gradient($gradientHtml)\">
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
  // output data of each row
  $factorInfo = array();
  while($row = $result->fetch_assoc()) {
    //echo $row["id"];
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
} else {
  echo "0 results";
}

// create array of the ids to be referenced by the javascript
$jsSliderIds = "[" . join(", ", $sliderIds) . "]";
$jsCheckboxIds = "[" . join(", ", $checkboxIds) . "]";

$conn->close();
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
