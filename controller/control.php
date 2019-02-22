<div id="control" class="flex-vertical panel">

   <iframe id="video-display" src="" width="275px" height="150px"></iframe>
   
   <div class="flex-horizontal"><input id="distance-sensor-display" type="number"><label>cm</label></div>

   <input id="servo1-input" type="range" min="0" max="180" value="0">
   
   <input id="servo2-input" type="range" min="0" max="180" value="0">
   
   <div class="flex-horizontal">
      <button id="servo1-toggle-button" class="button server-toggle-button" onpointerdown="onServoToggleButtonUpdate(this, true)" onpointerup="onServoToggleButtonUpdate(this, false)">A</button>
      <button id="servo2-toggle-button" class="button server-toggle-button" onpointerdown="onServoToggleButtonUpdate(this, true)" onpointerup="onServoToggleButtonUpdate(this, false)">B</button>
   </div>
   
	<div class="flex-vertical bordered dpad">
	   <div class="flex-horizontal">
	      <div class="dpad-spacer"></div>
	      <div><button id="dpad-button-up" class="button dpad-button" onpointerdown="onDpadButtonUpdate(this, true)" onpointerup="onDpadButtonUpdate(this, false)">&#8593;</button></div>
	      <div class="dpad-spacer"></div>
	   </div>
	   <div class="flex-horizontal">
	      <div><button id="dpad-button-left" class="button dpad-button" onpointerdown="onDpadButtonUpdate(this, true)" onpointerup="onDpadButtonUpdate(this, false)">&#8592;</button></div>
	      <div class="dpad-spacer"></div>
	      <div><button id="dpad-button-right" class="button dpad-button" onpointerdown="onDpadButtonUpdate(this, true)" onpointerup="onDpadButtonUpdate(this, false)">&#8594;</button></div>
	   </div>
	   <div class="flex-horizontal">
	      <div class="dpad-spacer"></div>
	      <div><button id="dpad-button-down" class="button dpad-button" onpointerdown="onDpadButtonUpdate(this, true)" onpointerup="onDpadButtonUpdate(this, false)">&#8595;</button></div>
	      <div class="dpad-spacer"></div>
	   </div>
	</div>
	
</div>