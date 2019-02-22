var myRobox;

var ipAddress;

var isConnecting = false;

var Panel = {
   FIRST : 0,
   CONTROL : 0,
   HARDWARE_CONFIG : 1,
   WIFI_CONFIG : 2,
   ROBOX_CONFIG : 3,
   LOGGER : 4,
   VIDEO : 5,
   LAST : 6
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
      "roboxConfig",
      "logger",
      "video"
   ];
   
   var element = document.getElementById(elementNames[panel]);

   return (element);
}

function setup(initIpAddress)
{
   myRobox = new Robox();
   
   myRobox.handleMessage = function(message)
   {
      if ((message.messageId == "sensorReading") &&
            (message.source == "distanceSensor"))
     {
        document.getElementById("distance-sensor-display").value = message.value;
     }
     else if (message.messageId == "logMessage")
     {
        var element = document.getElementById("logs-display");
        element.value += message.logLevel + ": " + message.message + "\n";
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
      
      myRobox.subscribe("distanceSensor");
      myRobox.components["distanceSensor"].setUnits("CENTIMETERS");
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