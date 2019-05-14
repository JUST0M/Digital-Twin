<?php
$red = "#f00000";
$yellow = "#f0c800";
$green = "#00c800";
$sliderColours = array($red, $yellow, $green);

$sliderIds = array();
$checkboxIds = array();

// return data input html (either a slider or a checkbox)
function dataInputHtml($factorData) {
  global $sliderColours; global $sliderIds; global $checkboxIds;
  $min = $factorData["min"]; $max = $factorData["max"];
  $dType = $factorData["type"];
  $question = $factorData["question"]; $default = $factorData["default"];
  $factorName = $factorData["factor"];
  // if boolean datatype then just add a checkbox
  if ($dType == 0) {
    $default = $factorData["default"] == 1 ? "checked" : "";
    array_push($checkboxIds, "\"$factorName\"");
    $checkboxHtml = "<tr>
                       <td><input type=\"checkbox\" name=\"$factorName\" id=\"checkbox-$factorName\" $default> $question<br/>
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
        $col = $sliderColours[$colRange["healthiness"]];
        $lo = (($colRange["min"] - $min) / $range) * 100;
        $hi = (($colRange["max"] - $min) / $range) * 100;
        $gradientHtml .= ", $col $lo% $hi%";
      }
    }

    // dType of 1 means int, 2 means float
    $step = $dType == 1 ? 1 : 0.1;

    $sliderHtml = "<tr>
                     <td>$question: <input type=\"number\" min=\"$min\" max=\"$max\" step=\"$step\" value=\"$default\" name=\"$factorName\" id=\"box-$factorName\"><br/>
                       <input type=\"range\" min=\"$min\" max=\"$max\" step=\"$step\" value=\"$default\" class=\"slider\" id=\"slider-$factorName\" style=\"background:linear-gradient($gradientHtml)\">
                     </td>
                   </tr>";
    return($sliderHtml);
  }
}


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
                                      //"default" => $row["def"],
                                      //"default" => (isset($_POST[$row["factor"]]) && $_POST[$row["factor"]] != null) ? $_POST[$row["factor"]] : $row["def"],
                                      // set to same as left-hand body to start with
                                      "default" => (isset($factorValues[$row["id"]]) && $factorValues[$row["id"]] != null) ? $factorValues[$row["id"]] : $row["def"],
                                      "type" => $row["t"],
                                      "colRanges" => array(array("healthiness" => $row["healthiness"], "min" => $row["rmin"], "max" => $row["rmax"])));

    }
    //echo "id: " . $row["factor_id"]. " - factor: " . $row["factor"]. " - question: " . $row["question"]. "<br>";
  }
  foreach($factorInfo as $key => $fInfo){$sliderHtml .= dataInputHtml($fInfo);}
}
else {
  echo "0 results";
}

// create array of the ids to be referenced by the javascript
$jsSliderIds = "[" . join(", ", $sliderIds) . "]";
$jsCheckboxIds = "[" . join(", ", $checkboxIds) . "]";
?>
