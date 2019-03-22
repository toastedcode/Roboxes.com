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

var Modal = {
   FIRST : 0,
   SELECT_CONNECTION_MODE : 0,
   LOST_CONNECTION : 1,
   ENTER_IP_ADDRESS : 2,
   ENTER_SERVER_CONFIG : 3,
   LAST : 4
};

var ConnectionMode = {
   FIRST : 0,
   AP : 0,
   LAN : 1,
   INTERNET : 2,
   LAST : 2
}

var currentPanel = Panel.CONTROL;

function setStatus(text, led)
{
   var element = document.getElementById("connect-button");
   element.innerHTML = text;

   element = document.getElementById("status-led");
   element.className = "";
   element.classList.add(led);
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

function getModal(modal)
{
   var elementNames = [
      "select-connection-mode-dialog",
      "lost-connection-dialog",
      "enter-ip-address-dialog",
      "enter-server-config-dialog",
   ];
   
   var element = document.getElementById(elementNames[modal]);

   return (element);
}

function showModal(modal)
{
   var element = getModal(modal);
   
   if (element)
   {
      element.style.display = "flex";
   }   
}

function hideModal(modal)
{
   if (modal != null)
   {
      var element = getModal(modal);
      
      if (element)
      {
         element.style.display = "none";
      }
   }
   else
   {
      for (var i = Modal.FIRST; i < Modal.LAST; i++)
      {
         hideModal(i);
      }
   }
}

function setup()
{
   myRobox = new Robox();
   
   myRobox.handleMessage = function(message)
   {
      if (message.messageId == "pong")
      {
         document.getElementById("robox-name-input").value = message.deviceName;
         document.getElementById("robox-api-key-input").value = message.apiKey;
         document.getElementById("robox-mode-input").value = message.mode;
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

   showModal(Modal.SELECT_CONNECTION_MODE);
}

function onConnectionStateToggled()
{
   if ((myRobox.isConnected() == true) ||
       (isConnecting == true))
   {
      disconnect();
   }
   else
   {
      showModal(Modal.SELECT_CONNECTION_MODE);
   }
}

function connect(mode)
{
   if ((myRobox.isConnected() == false) ||
       (isConnecting == false))
   {
      switch (mode)
      {
         case ConnectionMode.AP:
         {
            myRobox.connect("192.168.4.1", 1975);
            setStatus("...", "led-yellow");
            break;
         }
         
         case ConnectionMode.LAN:
         {
            if (ipAddress != "")
            {
               myRobox.connect(ipAddress, 1975);
               
               setStatus("...", "led-yellow");
               isConnecting = true;
            }
            else
            {
               showModal(Modal.ENTER_IP_ADDRESS);
            }
            break;
         }
         
         case ConnectionMode.INTERNET:
         {
            var host = document.getElementById("server-host-input").value;
            var port = document.getElementById("server-port-input").value;
            var topic = document.getElementById("server-topic-input").value;
            
            myRobox.mqttConnect(host, port, topic);
            
            setStatus("...", "led-yellow");
            isConnecting = true;
            break;
         }
      }
   }
}

function disconnect()
{
   if ((myRobox.isConnected() == true) ||
       (isConnecting == true))
   {
      setStatus("Connect", "led-red");
      myRobox.disconnect();
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

function constrain(value, limitMin, limitMax)
{
   return (Math.min(limitMax, Math.max(limitMin, value)));
}

function map(value, fromLow, fromHigh, toLow, toHigh)
{
   return (toLow + (toHigh - toLow) * ((value - fromLow) / (fromHigh - fromLow)));
}

function transformAngle(componentId, angle)
{
   var limitMin = 0;
   var limitMax = 180;
   var reversed = false;
   
   if (componentId == "servo1")
   {
      var values = document.getElementById("servo1-limits-input").noUiSlider.get();
      
      limitMin = Math.round(values[0]);
      limitMax = Math.round(values[1]);
      
      reversed = document.getElementById("servo1-reverse_input").checked;
   }
   else if (componentId == "servo2")
   {
      var values = document.getElementById("servo2-limits-input").noUiSlider.get();
      
      limitMin = Math.round(values[0]);
      limitMax = Math.round(values[1]);
      
      reversed = document.getElementById("servo2-reverse_input").checked;
   }
   
   if (reversed)
   {
      limitMin = [limitMax, limitMax = limitMin][0];  // one line swap
   }
   
   var transformedAngle = map(angle, 0, 180, limitMin, limitMax);
   
   return (transformedAngle);
}

function transformSpeed(componentId, speed)
{
   var limitMin = 0;
   var limitMax = 100;
   var reversed = false;
   
   if (componentId == "motor1")
   {
      var values = document.getElementById("motor1-limits-input").noUiSlider.get();
      
      limitMin = Math.round(values[0]);
      limitMax = Math.round(values[1]);
      
      reversed = document.getElementById("motor1-reverse_input").checked;
   }
   else if (componentId == "motor2")
   {
      var values = document.getElementById("motor2-limits-input").noUiSlider.get();
      
      limitMin = Math.round(values[0]);
      limitMax = Math.round(values[1]);
      
      reversed = document.getElementById("motor2-reverse_input").checked;
   }
   
   if (reversed)
   {
      limitMin = [limitMax, limitMax = limitMin][0];  // one line swap
   }
   
   var transformedSpeed = map(speed, 0, 100, limitMin, limitMax);
   
   return (transformedSpeed);
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
            myRobox.components["servo1"].rotate(transformAngle("servo1", 180));
         }
         else
         {
            myRobox.components["servo1"].rotate(transformAngle("servo1", 0));
         }
         break;
      }
      
      case "servo2-toggle-button":
      {
         if (isDown)
         {
            myRobox.components["servo2"].rotate(transformAngle("servo2", 180));
         }
         else
         {
            myRobox.components["servo2"].rotate(transformAngle("servo2", 0));
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

function onVideoSourceUpdate()
{
   document.getElementById("video-preview").src = document.getElementById("video-source-input").value;
   document.getElementById("video-display").src = document.getElementById("video-source-input").value;
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

function updateRoboxConfig()
{
   var deviceName = document.getElementById("robox-name-input").value;
   var apiKey = document.getElementById("robox-api-key-input").value;
   var mode = document.getElementById("robox-mode-input").value;
   
   myRobox.setProperty("deviceName", deviceName);
   myRobox.setProperty("apiKey", apiKey);
   myRobox.setProperty("mode", mode);
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

function onConnectionModeSelected(connectionMode)
{
   connect(connectionMode);
}

function onIpAddressUpdated()
{
   ipAddress = document.getElementById("ip-address-input").value;
   
   if (ipAddress != "")
   {
      connect(ConnectionMode.LAN);
   }
}