<?php
// Forces a jump to the main page if there's user data in the database
include "lib/conn.php";
if (!isset($_POST["UserId"])){
  header('Location: registration/login.php');
}
$UserId = $_POST["UserId"];
// Set up a simple send UserId form to be sent if there's user data in the database
$mainPage = "compare.php";
echo '<form id="toMainPage" action="' . $mainPage . '" method="post">
            <input type="hidden" name="UserId" value="' . $UserId . '">
        </form>';
// Check if the user has any data
$sql = "SELECT user_id, factor_id FROM user_factor_values where user_id = \"" . $UserId . "\"";
$result = $conn->query($sql);
// Check if result is empty. If it's not empty, redirect to index.php/compare.php...
if ($result->num_rows != 0){
  echo "<script> document.getElementById('toMainPage').submit() </script>";
}
// Check if someone submitted the form... If this form is submitted, then store the data unless the field was empty; in which case, set it to the default value.
if (isset($_POST["initial-form-submit"])){
  $sql = "SELECT factor_id, factor, def, data_type as type FROM factors";
  $result = $conn->query($sql);
  $timestamp = date("Y-m-d");
  while($row = $result->fetch_assoc()) {
    $factorId = $row['factor_id'];
    $factor = $row["factor"];
    $type = $row["type"];
    $factorValue = 0;
    // Checkbox - boolean inputs
    if ($row['type'] == 0){
      // Post only sends a value for a checkbox if it's been checked. Otherwise, there's no value
      if (isset($_POST[$factor])) $factorValue = 1;
      else $factorValue = 0;
    }
    else{ // Should be either integer inputs or float inputs
      $factorValue = ($_POST[$factor] != "" ? $_POST[$factor] : $row['def']);
    }
    $insertValueSql = "INSERT INTO user_factor_values
                       (user_id, factor_id, date, value)
                       VALUES
                       ('{$UserId}', '{$factorId}', '{$timestamp}', '{$factorValue}')";
    $conn->query($insertValueSql);
  }
  echo "<script> document.getElementById('toMainPage').submit() </script>";
}
// Otherwise, create a form with these details and save the info...
$sql = "SELECT factor_id as id, factor, data_type as type, question FROM factors";
$result = $conn->query($sql);
$conn->close();
$formText = "";
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    //boolean inputs
    if ($row['type'] == 0){
      $inputText = "<input type=\"checkbox\" name=\"{$row['factor']}\" value=\"checked\"><br><br>";
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
<form action="initial.php" method="post">
  <?php echo '<input type="hidden" name="UserId" value="' . $UserId . '">' ?>
  <input type = "hidden" name = "initial-form-submit" value = "yes">
  <?php echo $formText; ?>
  <input type="submit" value="Submit">
</form>
