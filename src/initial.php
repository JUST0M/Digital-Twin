<?php
$servername = "localhost";
$username = "master";
$password = "D1g1talTw1n";
$dbname = "digital-twin";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
$sql = "SELECT factor_id as id, factor, data_type as type, question FROM factors";
$result = $conn->query($sql);

$formText = "";

if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    //boolean inputs
    if ($row['type'] == 0){
      $inputText = "<input type=\"checkbox\" name=\"{$row['factor']}\"><br><br>";
    }
    // might want to enforce min and max for these inputs
    // integer inputs
    elseif ($row['type'] == 1){
      $inputText = "<input type=\"number\" name=\"{$row['factor']}\"><br><br>";
    }
    // float inputs
    else{
      $inputText = "<input type=\"number\" step=\"0.1\" name=\"{$row['factor']}\"><br><br>";
    }
    $formText .= "{$row['question']}:<br>$inputText";
  }
}
?>

<!DOCTYPE html>
<form action="/index.php" method="post">
  <?php echo $formText; ?>
  <input type="submit" value="Submit">
</form>
