<div id="logger" class="flex-vertical panel">

   <label>Robox Logs</label>
   <textarea id="logs-display" rows="15" cols="30"></textarea>
   
   <label>Log Level</label>
   <select id="log-level-input" onchange="onLogLevelUpdate(this)">
     <option value="NONE" selected>None</option>
     <option value="DEBUG_FINEST">Finest</option>
     <option value="DEBUG">Debug</option>
     <option value="INFO">Info</option>
     <option value="INFO">Warning</option>
     <option value="INFO">Severe</option>
   </select>

</div>
