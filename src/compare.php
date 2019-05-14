<!DOCTYPE html>
<?php
// redirect to login if not logged in
if(!isset($_POST["UserId"])) header('Location: http://digitwin.co.uk/registration/login.php');

include "lib/conn.php";
include "lib/gen_sliders.php";
include "lib/get_score_data.php";
include "lib/save_digital_twin_data.php";
include "lib/get_historical_data.php";

$conn->close();
?>


<html lang="en"; style="height:100%">
<head>
  <meta charset="UTF-8">
  <title>Digital Twin</title>

  <link rel="stylesheet" type="text/css" href="main.css">
  <link rel="stylesheet" type="text/css" href="modalStyle2.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
  <script>
    var sliderIds = <?php echo $jsSliderIds; ?>;
    var checkboxIds = <?php echo $jsCheckboxIds; ?>;
    <?php echo $colourJs ?>
  </script>
  <?php echo $jsText; ?>
  <script src="sliders2.js"></script>
  <script src="lib/functions2.js"></script>
  <script src="health.js"></script>
</head>
<body>
  <div style = "height: 90%; line-height: 2em">
    <table style = "float: left">
      <tr>
        <td><canvas id="humanCanvasLeft"></canvas> </td>

        <td><canvas id="humanCanvasTwin"></canvas> </td>
      </tr>
      <tr align="center">
        <td>
          <form action="compare.php" method="post">
            <input type="hidden" name="UserId" value="<?php echo $userId;?>">
            historical data:
            <select onchange="this.form.submit()" name="date">
              <?php echo $dateOptions ?>
            </select>
          </form>
        </td>
        <td>
        </td>
      </tr>

    </table>

    <!-- Yoojin: New <div>s added so that we can separate the left hand side and the right hand side, thus being able to fix the canvas and scroll on the right hand side. Checked by adding 10+ sliders. See main.css for table-wrapper, etc.-->

    <!--<div id="table-wrapper"> -->
    <div id="table-scroll">
      <font size="2">
        <table style = "width: 100%">
          <form action="compare.php" method="post">
            <!-- (Kareem): the sliders have been updated to contain information on nine different measures that are used in the formulation-->
            <?php echo $sliderHtml; ?>
            <input type="hidden" name="UserId" value="<?php echo $userId;?>">
            <input type="hidden" name="digitwin-form-submit" value="yes">
            <input type="submit" value="Save digital twin data">
          </form>
        </table>
      </font>
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
          <p id="heartModalText">Heart modal text...</p>
        </div>
      </div>
    </div>

    <div id="brainModal" class="modal">
      <!-- Modal content -->
      <div class="modal-content">
        <div class="modal-header">
          <span class="close">&times;</span>
          <h2>Brain Modal</h2>
        </div>
        <div class="modal-body">
          <p id="brainModalText">Brain modal text...</p>
          <p>The thing you most need to improve on is: </p>
          <a target="_blank" rel="noopener noreferrer" id="improveBrain"></a>
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
          <p id="bodyModalText">Body modal text...</p>
        </div>
      </div>
    </div>

    <div id="heartLeftModal" class="modal" style="display: none" >
      <!-- Modal content -->
      <div class="modal-content">
        <div class="modal-header" style="background-color: <?php echo $heartColourLeft; ?>;">
          <span class="close">&times;</span>
          <h2>Left Heart Modal</h2>
        </div>
        <div class="modal-body">
          <p id="leftHeartModalText">Heart left modal text...</p>
        </div>
      </div>
    </div>

    <div id="brainLeftModal" class="modal" style="display: none" >
      <!-- Modal content -->
      <div class="modal-content">
        <div class="modal-header" style="background-color: <?php echo $brainColourLeft; ?>;">
          <span class="close">&times;</span>
          <h2>Left Brain Modal</h2>
        </div>
        <div class="modal-body">
          <p id="leftBrainModalText">Brain left modal text...</p>
          <p>The thing you most need to improve on is: </p>
          <a target="_blank" rel="noopener noreferrer" id="improveBrainLeft"></a>
        </div>
      </div>
    </div>

    <div id="bodyLeftModal" class="modal" style="display: none" >
      <!-- Modal content -->
      <div class="modal-content">
        <div class="modal-header" style="background-color: <?php echo $bodyColourLeft; ?>;">
          <span class="close">&times;</span>
          <h2>Left Body Modal</h2>
        </div>
        <div class="modal-body">
          <p id="leftBodyModalText">Body left modal text...</p>
        </div>
      </div>
    </div>
  </div>
  <script src="body_canvas2.js"></script>

</body>
</html>
