var myRobox;

var isConnecting = false;

var Panel = {
   FIRST : 0,
   CONTROL : 0,
   HARDWARE_CONFIG : 1,
   WIFI_CONFIG : 2,
   ROBOX_CONFIG : 3,
   LAST : 4 
};

var currentPanel = Panel.CONTROL;

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
   ];
   
   var element = document.getElementById(elementNames[panel]);

   return (element);
}

function setup()
{
   myRobox = new Robox();
   
   myRobox.handleMessage = function(message)
   {
      console.log(message.messageId);
   }

   myRobox.onConnected = function(message)
   {
      console.log("Connected");
      setStatus("Disconnect", "led-green");
      isConnecting = false;
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
      
      alert("Failed to connect to Lover Bot.");
   }
   
   for (var i = Panel.FIRST; i < Panel.LAST; i++)
   {
      hidePanel(i);
   }
   
   showPanel(currentPanel);
}

function connect()
{
   if (isConnecting == false)
   {
      if (myRobox.isConnected() == false)
      {
         setStatus("...", "led-yellow");
         myRobox.connect('192.168.244.1', 1975);
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
