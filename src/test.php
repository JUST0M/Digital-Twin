<!DOCTYPE html>
<?php

$red = "#f00000";
$yellow = "#f0c800";
$green = "#00c800";
$colours = array($red, $yellow, $green);


// return data input html (either a slider or a checkbox)
function dataInputHtml($factorData, $n) {
  global $colours;
  $min = $factorData["min"]; $max = $factorData["max"];
  $dType = $factorData["type"];
  $question = $factorData["question"]; $default = $factorData["default"];
  $factorName = $factorData["factor"];
  // if boolean datatype then just add a checkbox
  if ($dType == 0) {
    $checkboxHtml = "<tr>
                       <td><input type=\"checkbox\" id=\"$factorName\" unchecked> $question<br/>
                       </td>
                     </tr>";
    return($checkboxHtml);
  }
  // for int and float add a slider
  else{
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
                     <td>$question: <input type=\"number\" min=\"$min\" max=\"$max\" value=\"$default\" id=\"b$n\"><br/>
                       <input type=\"range\" min=\"$min\" max=\"$max\" value=\"$default\" class=\"slider\" id=\"s$n\" style=\"background:linear-gradient($gradientHtml)\">
                     </td>
                   </tr>";
    return($sliderHtml);
  }
}



$sliderHtml = slider("Vigorous Physical Activity (minutes per week)", 0, 300, 75, array(array("healthiness" => 0, "min" => 0, "max" => 0), array("healthiness" => 1, "min" => 90, "max" => 135), array("healthiness" => 2, "min" => 150, "max" => 300)), 1);

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

$sql = "SELECT f.factor_id as id, f.factor as factor, f.question as q, f.min as min, f.max as max, f.def as def, f.type as t, r.healthiness as healthiness, r.min as rmin, r.max as rmax, r.factor_id as id2 FROM factors f LEFT JOIN factor_ranges r ON f.factor_id = r.factor_id";
$result = $conn->query($sql);

$sliderHtml = "";

if ($result->num_rows > 0) {
  // output data of each row
  $factorInfo = array();
  while($row = $result->fetch_assoc()) {
    //echo $row["id"];
    //have already put info in for this id so just add a colour range
    if(in_array($row["id"], array_keys($factorInfo))){
      echo "<script> console.log(\"adding more\") </script>";
      array_push($factorInfo[$row["id"]]["colRanges"], array("healthiness" => $row["healthiness"], "min" => $row["rmin"], "max" => $row["rmax"]));
    }
    else{
      echo "<script> console.log(\"new slider\") </script>";
      $factorInfo[$row["id"]] = array("factor" =? $row["factor"],
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
    echo "<script>console.log(\"$toPrint\");</script>";
    $sliderHtml .= dataInputHtml($fInfo, $n);
    $n++;
  }
} else {
  echo "0 results";
}

$conn->close();
?>


<html lang="en"; style="height:100%">
<head>
  <meta charset="UTF-8">
  <title>Digital Twin</title>

  <link rel="stylesheet" type="text/css" href="main.css">
  <link rel="stylesheet" type="text/css" href="modalStyle.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
  <script src="sliders.js"></script>
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
          <tr>
            <td>BMI: <input type="number" min="15" max="35" value="25" id="b0"><br/>
              <input type="range" min="15" max="35" value="25" class="slider" id="s0" style="background:linear-gradient(90deg, #f00000 0%, #f0c800 10%, #00c800 17.5% 50%, #f0c800 55% 70%, #f00000 80% 100%)">
            </td>
          </tr>
          <?php echo $sliderHtml; ?>
          <tr>
            <td>Alcoholic Units (per Week): <input type="number" min="0" max="30" value="8" id="b2"><br/>
              <input type="range" min="0" max="30" value="8" class="slider" id="s2" style="background:linear-gradient(90deg, #00c800 0% 45%, #f0c800 50% 65%, #f00000 75% 100%)">
            </td>
          </tr>
          <tr>
            <td><input type="checkbox" id="smoking" unchecked> I have been smoking in the past six months<br/>
            </td>
          </tr>
          <tr>
            <td>Ambulatory Systolic Blood Pressure (mm/Hg): <input type="number" min="60" max="180" value="130" id="b3"><br/>
              <input type="range" min="60" max="180" value="130" class="slider" id="s3" style="background:linear-gradient(90deg, #f00000 0% 17%, #f0c800 23%, #00c800 30% 45%, #f0c800 60%, #f00000 70% 100%)">
            </td>
          </tr>
          <tr>
            <td>Ambulatory Diastolic Blood Pressure (mm/Hg): <input type="number" min="50" max="120" value="80" id="b4"><br/>
              <input type="range" min="50" max="120" value="80" class="slider" id="s4" style="background:linear-gradient(90deg, #f00000 0% 10%, #f0c800 15%, #00c800 20% 40%, #f0c800 50%, #f00000 60% 100%)">
            </td>
          </tr>
          <tr>
            <td>Post-Exercise Diastolic Blood Pressure (mm/Hg): <input type="number" min="50" max="120" value="90" id="b5"><br/>
              <input type="range" min="50" max="120" value="90" class="slider" id="s5" style="background:linear-gradient(90deg, #f00000 0% 10%, #f0c800 20%, #00c800 25% 50%, #f0c800 60%, #f00000 70% 100%)">
            </td>
          </tr>
          <tr>
            <td>Cholesterol (mg/dL): <input type="number" min="50" max="350" value="200" id="b6"><br/>
              <input type="range" min="50" max="350" value="200" class="slider" id="s6" style="background:linear-gradient(90deg, #00c800 0% 50%, #f0c800 55%, #f00000 60% 100%)">
            </td>
          </tr>
          <tr>
            <td>Blood Glucose (mg/dL): <input type="number" min="50" max="150" value="100" id="b7"><br/>
              <input type="range" min="50" max="150" value="100" class="slider" id="s7" style="background:linear-gradient(90deg, #f00000 0% 15%, #f0c800 20% 25%, #00c800 30% 43%, #f0c800 57% 65%, #f00000 70% 100%)">
            </td>
          </tr>
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
