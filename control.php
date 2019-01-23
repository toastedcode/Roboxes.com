<?php 

require_once 'common/registryEntry.php';

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

$registryEntry = RegistryEntry::load(getParam("chipId"));
?>

<!DOCTYPE html>
<html>

<head>
   <title>robox.js</title>

   <script src="robox.js/thirdparty/mqttws31.js"></script>
   <script src="robox.js/thirdparty/mqtt-client.js"></script>
   <script src="robox.js/communication/message.js"></script>
   <script src="robox.js/communication/jsonProtocol.js"></script>
   <script src="robox.js/communication/mqttAdapter.js"></script>
   <script src="robox.js/communication/webSocketAdapter.js"></script>
   <script src="robox.js/component/led.js"></script>
   <script src="robox.js/component/motor.js"></script>
   <script src="robox.js/component/motorPair.js"></script>
   <script src="robox.js/component/servo.js"></script>
   <script src="robox.js/robox.js"></script>
</head>

<body>

   <script>
      var myRobox = new Robox();

      myRobox.handleMessage = function(message)
      {
         console.log(message.messageId);
      }

      function scout()
      {
         var message = new Message();
         message.messageId = "ping";
         message.destination = "motor1";

         myRobox.sendMessage(message);
      }

      function getRoboxAddress()
      {
         return (document.getElementById("roboxAddress").value);
      }
   </script>

   <h1>Robot Controller: <?php echo $registryEntry->roboxName?></h1>

   <div>Robox address: <input type="text" id="roboxAddress" value="<?php echo $registryEntry->ipAddress?>"></div>
   <div>Camera address: <input type="text" id="camAddress"></div>

   <button onclick="myRobox.connect(getRoboxAddress(), 1975)">Connect</button>
   <button onclick="myRobox.disconnect()">Disconnect</button>
   <!--button onclick="myRobox.ping()">Ping</button-->

   <br><br>

   <button onmousedown="myRobox.components['motor1'].drive(100)" onmouseup="myRobox.components['motor1'].stop()">Motor 1</button>
   <button onmousedown="myRobox.components['motor2'].drive(100)" onmouseup="myRobox.components['motor2'].stop()">Motor 2</button>

   <br><br>

   <button onmousedown="myRobox.components['servo1'].rotate(10)" onmouseup="myRobox.components['servo1'].rotate(170)">Servo 1</button>
   <button onmousedown="myRobox.components['servo2'].rotate(170)" onmouseup="myRobox.components['servo2'].rotate(10)">Servo 2</button>

   <button onmousedown="scout()">Scout</button>

   <br><br>

   <table>
      <tr>
         <td></td>
         <td><button onmousedown="myRobox.components['motorPair'].drive(100, 0)" onmouseup="myRobox.components['motorPair'].stop()">Up</button></td>
         <td></td>
      <tr>
         <td><button onmousedown="myRobox.components['motorPair'].rotate(100)" onmouseup="myRobox.components['motorPair'].stop()">Left</button></td>
         <td></td>
         <td><button onmousedown="myRobox.components['motorPair'].rotate(-100, 0)" onmouseup="myRobox.components['motorPair'].stop()">Right</button></td>
      </tr>
      <tr>
         <td></td>
         <td><button onmousedown="myRobox.components['motorPair'].drive(-100, 0)" onmouseup="myRobox.components['motorPair'].stop()">Down</button></td>
         <td></td>
      </tr>
   </table>

   <!--div>
      <iframe id="video-iframe" src="http://192.168.0.107:8080/browserfs.html" width="640px" height="480px"></iframe>
   </div-->

</body>

</html>