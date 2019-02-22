<?php 
function getParam($paramName)
{
   $value = "";

   if (isset($_POST[$paramName]))
   {
      $value= $_POST[$paramName];
   }
   else if (isset($_GET[$paramName]))
   {
      $value= $_GET[$paramName];
   }

   return ($value);
}
?>

<!DOCTYPE html>
<html>

<head>

   <meta name="viewport" content="width=device-width, initial-scale=1">
   
   <title>Robox Controller</title>
   
   <script src="../robox.js/communication/message.js"></script>
   <script src="../robox.js/communication/jsonProtocol.js"></script>
   <script src="../robox.js/communication/webSocketAdapter.js"></script>
   <script src="../robox.js/component/sensor.js"></script>
   <script src="../robox.js/component/led.js"></script>
   <script src="../robox.js/component/motor.js"></script>
   <script src="../robox.js/component/motorPair.js"></script>
   <script src="../robox.js/component/servo.js"></script>
   <script src="../robox.js/robox.js"></script>
   
   <script src="../thirdparty/nouislider/nouislider.js"></script>
   
   <script src="controller.js"></script>
   
   <link rel="stylesheet" type="text/css" href="../common/flex.css"/>
   <link rel="stylesheet" type="text/css" href="../thirdparty/nouislider/nouislider.css"/>
   
   <!--  TODO: Move to CSS file -->
   <style>
      .led-red {
         margin: 20px 20px 20px 20px;
         width: 12px;
         height: 12px;
         background-color: #e53302;
         border-radius: 50%;
         box-shadow: #000 0 -1px 7px 1px, inset #600 0 -1px 9px, #F00 0 2px 12px;
      }

      .led-yellow {
         margin: 20px 20px 20px 20px;
         width: 12px;
         height: 12px;
         background-color: #ffe900;
         border-radius: 50%;
         box-shadow: #000 0 -1px 7px 1px, inset #660 0 -1px 9px, #DD0 0 2px 12px;
      }

      .led-green {
         margin: 20px 20px 20px 20px;
         width: 12px;
         height: 12px;
         background-color: #05e502;
         border-radius: 50%;
         box-shadow: #000 0 -1px 7px 1px, inset #460 0 -1px 9px, #7D0 0 2px 12px;
      }

      .button {
        padding: 0px;
        font-size: 24px;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
        outline: none;
        color: white;
        background-color: red;
        border: none;
        border-radius: 15px;
        box-shadow: 0 9px #999;
        text-align: center;
        vertical-align: middle;

        -webkit-user-select: none;  /* Chrome all / Safari all */
        -moz-user-select: none;     /* Firefox all */
        -ms-user-select: none;      /* IE 10+ */
        user-select: none;          /* Likely future */
      }

      .button:active {
        background-color: #red;
        box-shadow: 0 5px #666;
        transform: translateY(4px);
      }
      
      .dpad {
         width: 150px; 
         height: 150px; 
         justify-content:center; 
         align-items:center;
      }
      
      .dpad-button, .dpad-spacer, .server-toggle-button {
         width: 50px;
         height: 50px;
      }

      body {
         background-image: linear-gradient(45deg, #ff9a9e 0%, #fad0c4 99%, #fad0c4 100%);
         display: flex;
         justify-content:center; align-items:center;
      }

      div {
         display: flex;
      }

      .bordered {
         border-radius: 25px;
         border: 5px solid #73AD21;
         border-color: white;
         margin: 5px 5px 5px 5px;
      }
      
      #controller-div {
         width: 300px; 
         height: 600px;
      }   
      
      .panel {
         display: none;
      }
   </style>

</head>

<body onload="setup('<?php echo getParam("ipAddress");?>')">

   <button class="button" style="width: 250px; height: 50px;" onclick="var nextPanel = currentPanel - 1; if (nextPanel < Panel.FIRST) {nextPanel = (Panel.LAST - 1);} setPanel(nextPanel);">Prev Panel</button>

   <div id="controller-div" class="flex-vertical bordered">
   
      <div class="flex-horizontal">
         <button id="connect-button" class="button" style="width: 150px; height: 50;" onclick="connect()">Connect</button>
         <div id="status-led" class="led-red"></div>
      </div>
      
      <?php include 'control.php';?>
      
      <?php include 'hardwareConfig.php';?>
      
      <?php include 'wifiConfig.php';?>
      
      <?php include 'serverConfig.php';?>
      
      <?php include 'roboxConfig.php';?>
      
      <?php include 'logger.php';?>
      
      <?php include 'video.php';?>
      
      <?php include 'code.php';?>
      
   </div>
   
   <button class="button" style="width: 250px; height: 50px;" onclick="var nextPanel = currentPanel + 1; if (nextPanel == Panel.LAST) {nextPanel = Panel.FIRST;} setPanel(nextPanel);">Next Panel</button>

</body>
      