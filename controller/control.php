<div id="control" class="flex-vertical">

   <input id="servo1-input" type="range" min="0" max="180" value="0">
   
   <input id="servo2-input" type="range" min="0" max="180" value="0">
   
   <div class="flex-horizontal">
      <button class="button flex-horizontal" style="width: 50px; height: 50px;" onpointerdown="" onpointerup="">A</button>
      <button class="button flex-horizontal" style="width: 50px; height: 50px;" onpointerdown="" onpointerup="">B</button>
   </div>
   
	<div class="flex-vertical bordered" style="width: 150px; height: 150px; justify-content:center; align-items:center;">
	   <div class="flex-horizontal">
	      <div style="width: 50px; height: 50px;"></div>
	      <div><button class="button" style="width: 50px; height: 50px;" onpointerdown="if (myRobox.isConnected()) {myRobox.components['motorPair'].drive(100, 20);}" onpointerup="if (myRobox.isConnected()) {myRobox.components['motorPair'].drive(0, 0);}">&#8593;</button></div>
	      <div style="width: 50px; height: 50px;"></div>
	   </div>
	   <div class="flex-horizontal">
	      <div><button class="button" style="width: 50px; height: 50px;" onpointerdown="if (myRobox.isConnected()) {myRobox.components['motorPair'].rotate(100);}" onpointerup="if (myRobox.isConnected()) {myRobox.components['motorPair'].drive(0, 0);}">&#8592;</button></div>
	      <div style="width: 50px; height: 50px;"></div>
	      <div><button class="button" style="width: 50px; height: 50px;" onpointerdown="if (myRobox.isConnected()) {myRobox.components['motorPair'].rotate(-100);}" onpointerup="if (myRobox.isConnected()) {myRobox.components['motorPair'].drive(0, 0);}">&#8594;</button></div>
	   </div>
	   <div class="flex-horizontal">
	      <div style="width: 50px; height: 50px;"></div>
	      <div><button class="button" style="width: 50px; height: 50px;" onpointerdown="if (myRobox.isConnected()) {myRobox.components['motorPair'].drive(-100, 25);}" onpointerup="if (myRobox.isConnected()) {myRobox.components['motorPair'].drive(0, 0);}">&#8595;</button></div>
	      <div style="width: 50px; height: 50px;"></div>
	   </div>
	</div>
	
</div>