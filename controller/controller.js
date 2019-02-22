var myRobox;

var ipAddress;

var isConnecting = false;

var loopTimer;

var Panel = {
   FIRST : 0,
   CONTROL : 0,
   HARDWARE_CONFIG : 1,
   WIFI_CONFIG : 2,
   SERVER_CONFIG: 3,
   ROBOX_CONFIG : 4,
   LOGGER : 5,
   VIDEO : 6,
   CODE : 7,
   LAST : 8
};

var currentPanel = Panel.CONTROL;

function setStatus(text, led)
{
   var element = document.getElementById("connect-button");
   element.innerHTML = text;

   element = document.getElementById("status-led");
   element.className = "";
   element.classList.add(led);
}

function setPanel(panel)
{
   hidePanel(currentPanel);
   currentPanel = panel;
   showPanel(currentPanel);
}

function showPanel(panel, isVisible)
{
   var element = getPanel(panel);
   
   if (element)
   {
      element.style.display = "flex";
   }
}

function hidePanel(panel)
{
   var element = getPanel(panel);
   
   if (element)
   {
      element.style.display = "none";
   }
}

function getPanel(panel)
{
   var elementNames = [
      "control",
      "hardwareConfig",
      "wifiConfig",
      "serverConfig",
      "roboxConfig",
      "logger",
      "video",
      "code"
   ];
   
   var element = document.getElementById(elementNames[panel]);

   return (element);
}

function setup(initIpAddress)
{
   myRobox = new Robox();
   
   myRobox.handleMessage = function(message)
   {
      if (message.messageId == "pong")
      {
         document.getElementById("robox-name-input").value = message.deviceId;
      }
      else if ((message.messageId == "sensorReading") &&
               (message.source == "distanceSensor"))
      {
         document.getElementById("distance-sensor-display").value = message.value;
      }
      else if (message.messageId == "logMessage")
      {
         var element = document.getElementById("logs-display");
         element.value += message.logLevel + ": " + message.message + "\n";
      }
      else if (message.messageId == "wifiConfig")
      {
         if (message.ssid)
         {
            var element = document.getElementById("wifi-ssid-input");
            element.value = message.ssid;
         }
        
         if (message.password)
         {
            var element = document.getElementById("wifi-password-input");
            element.value = message.password;
         }
      }
      else if (message.messageId == "serverConfig")
      {
         if (message.host)
         {
            var element = document.getElementById("server-host-input");
            element.value = message.host;
         }
        
         if (message.port)
         {
            var element = document.getElementById("server-port-input");
            element.value = message.port;
         }
        
         if (message.userId)
         {
            var element = document.getElementById("server-user-id-input");
            element.value = message.userId;
         }
        
         if (message.password)
         {
            var element = document.getElementById("server-password-input");
            element.value = message.password;
         }
        
         if (message.topic)
         {
            var element = document.getElementById("server-topic-input");
            element.value = message.topic;
         }
      }
      else
      {
         console.log(message.messageId);
      }
   }

   myRobox.onConnected = function(message)
   {
      console.log("Connected");
      setStatus("Disconnect", "led-green");
      isConnecting = false;
      
      myRobox.ping();
      myRobox.getWifiConfig();
      myRobox.getServerConfig();
      
      myRobox.subscribe("distanceSensor");
      myRobox.components["distanceSensor"].setUnits("CENTIMETERS");
      
      if (document.getElementById("setup-code-enabled-input").checked)
      {
         evaluateCode(document.getElementById("setup-code-input").value);
      }

      if (document.getElementById("loop-code-enabled-input").checked)
      {
         var millis = (document.getElementById("loop-code-period-input").value * 1000);
         
         if (millis > 0)
         {
            startLoopTimer(millis);
         }
      }

      
   }

   myRobox.onDisconnected = function(message)
   {
      console.log("Disconnected");
      setStatus("Connect", "led-red");
      isConnecting = false;
   }

   myRobox.onError = function(message)
   {
      console.log("Error");
      setStatus("Connect", "led-red");
      isConnecting = false;
      
      alert("Failed to connect.");
   }
   
   for (var i = Panel.FIRST; i < Panel.LAST; i++)
   {
      hidePanel(i);
   }
   
   showPanel(currentPanel);
   
   if (initIpAddress != "")
   {
      ipAddress = initIpAddress;
      myRobox.connect(ipAddress, 1975);
   }
   else
   {
      alert("No IP address specified.");
   }
}

function connect()
{
   if (isConnecting == false)
   {
      if (myRobox.isConnected() == false)
      {
         setStatus("...", "led-yellow");
         myRobox.connect(ipAddress, 1975);
         isConnecting = true;
      }
      else
      {
         setStatus("Connect", "led-red");
         myRobox.disconnect();
      }
   }
}

function setStatus(text, led)
{
   var element = document.getElementById("connect-button");
   element.innerHTML = text;
   
   element = document.getElementById("status-led");
   element.className = "";
   element.classList.add(led);
}

function onDpadButtonUpdate(button, isDown)
{
   switch (button.id)
   {
      case "dpad-button-up":
      {
         if (isDown)
         {
            myRobox.components["motorPair"].drive(100, 0);
         }
         else
         {
            myRobox.components["motorPair"].stop();
         }
         break;
      }
      
      case "dpad-button-down":
      {
         if (isDown)
         {
            myRobox.components["motorPair"].drive(-100, 0);
         }
         else
         {
            myRobox.components["motorPair"].stop();
         }
         break;
      }
      
      case "dpad-button-left":
      {
         if (isDown)
         {
            myRobox.components["motorPair"].rotate(-100);
         }
         else
         {
            myRobox.components["motorPair"].stop();
         }
         break;
      }
      
      case "dpad-button-right":
      {
         if (isDown)
         {
            myRobox.components["motorPair"].rotate(100);
         }
         else
         {
            myRobox.components["motorPair"].stop();
         }
         break;
      }
      
      default:
      {
         break;
      }
   }
}
   
function onServoToggleButtonUpdate(button, isDown)
{
   switch (button.id)
   {
      case "servo1-toggle-button":
      {
         if (isDown)
         {
            myRobox.components["servo1"].rotate(180);
         }
         else
         {
            myRobox.components["servo1"].rotate(0);
         }
         break;
      }
      
      case "servo2-toggle-button":
      {
         if (isDown)
         {
            myRobox.components["servo2"].rotate(180);
         }
         else
         {
            myRobox.components["servo2"].rotate(0);
         }
         break;
      }
      
      default:
      {
         break;
      }  
   }
}

function onLogLevelUpdate(input)
{
   var selected = input.options[input.selectedIndex].value;
   
   if (selected == "NONE")
   {
      myRobox.remoteLogging(false);
   }
   else
   {
      myRobox.remoteLogging(true);
      myRobox.setLogLevel(selected);
   }
}

function onDistanceSensorEnabled(input)
{
   var isEnabled = input.checked;
   
   if (isEnabled)
   {
      myRobox.components['distanceSensor'].poll(2000);
   }
   else
   {
      myRobox.components['distanceSensor'].poll(0);
   }
}

function evaluateCode(code)
{
   eval(code);
}

function startLoopTimer(millis)
{
   clearTimeout(loopTimer);
   
   loopTimer = setInterval(function(){
      evaluateCode(document.getElementById("loop-code-input").value);},
      millis);
}

function stopLoopTimer()
{
   clearTimeout(loopTimer);
}

function onCodeEnabled(input)
{
   if ((input.id == "loop-code-enabled-input") ||
       (input.id == "loop-code-period-input"))
   {
      var isEnabled = document.getElementById("loop-code-enabled-input").checked;
      var millis = (document.getElementById("loop-code-period-input").value * 1000);
      
      if (isEnabled)
      {
         startLoopTimer(millis);
      }
      else
      {
         stopLoopTimer();
      }
   }
}

function updateWifiConfig()
{
   var ssid = document.getElementById("wifi-ssid-input").value;
   var password = document.getElementById("wifi-password-input").value;
   
   myRobox.setWifiConfig(ssid, password);
}

function updateServerConfig()
{
   var host = document.getElementById("server-host-input").value;
   var port = document.getElementById("server-port-input").value;
   var userId = document.getElementById("server-user-id-input").value;
   var password = document.getElementById("server-password-input").value;
   var topic = document.getElementById("server-topic-input").value;
   
   myRobox.setServerConfig(host, port, userId, password, topic);
}