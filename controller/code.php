<div id="code" class="flex-vertical panel">

   <label>Code</label>
   <textarea id="code-input" rows="5" cols="30"></textarea>
   <div class="flex-horizontal">
      <button onclick="evaluateCode(document.getElementById('code-input').value)">Evaluate</button>
      <button onclick="document.getElementById('code-input').value = ''">Clear</button>
   </div>
   
   <label>setup()</label>
   <div class="flex-horizontal">
      <input id="setup-code-enabled-input" type="checkbox">
	   <textarea id="setup-code-input" rows="5" cols="30"></textarea>
	</div>
   <div class="flex-horizontal">
      <button onclick="evaluateCode(document.getElementById('setup-code-input').value)">Test</button>
      <button onclick="document.getElementById('setup-code-input').value = ''">Clear</button>
   </div>
   
   <div class="flex-horizontal">
      <label>loop()</label>
      <input id="loop-code-period-input" type="number" style="width:30px" value="0" onchange="onCodeEnabled(this)">
      <label>sec</label>
   </div>
   <div class="flex-horizontal">
      <input id="loop-code-enabled-input" type="checkbox" onclick="onCodeEnabled(this)">
	   <textarea id="loop-code-input" rows="5" cols="30"></textarea>
	</div>
   <div class="flex-horizontal">
      <button onclick="evaluateCode(document.getElementById('loop-code-input').value)">Test</button>
      <button onclick="document.getElementById('loop-code-input').value = ''">Clear</button>
   </div>
   
   <label>handleMessage(message)</label>
   <div class="flex-horizontal">
      <input id="handle-message-code-enabled-input" type="checkbox">
	   <textarea id="handle-message-code-input" rows="5" cols="30"></textarea>
	</div>
   <div class="flex-horizontal">
      <button onclick="evaluateCode(document.getElementById('handle-message-code-input').value)">Test</button>
      <button onclick="document.getElementById('handle-message-code-input').value = ''">Clear</button>
   </div>    

</div>