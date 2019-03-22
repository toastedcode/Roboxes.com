<div id="select-connection-mode-dialog" class="flex-vertical modal">
   <div class="flex-vertical modal-content">
      <span class="close" onclick="hideModal();">&times;</span>
      <button class="button" onclick="hideModal(); onConnectionModeSelected(ConnectionMode.LAN);">Connect over local network</button>
      <button class="button" onclick="hideModal(); onConnectionModeSelected(ConnectionMode.INTERNET)">Connect over Internet</button>
   </div>
</div>

<div id="lost-connection-dialog" class="flex-vertical modal">
   <div class="flex-vertical modal-content">
      <span class="close" onclick="hideModal();">&times;</span>
      <span>Lost connection to Robox!</span>
   </div>
</div>

<div id="enter-ip-address-dialog" class="flex-vertical modal">
   <div class="flex-vertical modal-content">
      <span class="close" onclick="hideModal();">&times;</span>
      <span>Enter the IP address of your Robox</span>
      <input id="ip-address-input" type="text">
      <button class="button" onclick="hideModal(); onIpAddressUpdated();">Ok</button>
   </div>
</div>