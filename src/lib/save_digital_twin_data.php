<?php
// Check if someone submitted the form... If this form is submitted, then store the data unless the field was empty; in which case, set it to the default value.
if (isset($_POST["digitwin-form-submit"])){
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
                       ('{$userId}', '{$factorId}', '{$timestamp}', '{$factorValue}')";
    $conn->query($insertValueSql);
  }
}
?>
