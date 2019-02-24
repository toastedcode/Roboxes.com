<div id="roboxConfig" class="flex-vertical panel">

<label>Name</label>
<input id="robox-name-input" type="text">

<label>API Key</label>
<input id="robox-api-key-input" type="text">

<label>Mode</label>
<select id="robox-mode-input">
  <option value="AP">AP</option>
  <option value="WIFI">LAN</option>
  <option value="ONLINE">INTERNET</option>
</select>

<button onclick="updateRoboxConfig()">Update</button>
<button onclick="myRobox.reset()">Reset</button>

</div>
