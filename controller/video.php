<div id="video" class="flex-vertical panel">

   <iframe id="video-preview" src="udp://192.168.0.114:4138" width="275px" height="150px"></iframe>

   <label>Camera address</label>
   <input id="video-source-input" type="text" onchange="onVideoSourceUpdate()">

   <div class="flex-horizontal">
      <label>Show on control panel</label>
      <input id="show-video-input" type="checkbox">
   </div>

</div>
