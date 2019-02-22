<div id="hardwareConfig" class="flex-vertical panel">

   <label>Motor 1</label>
   <div id="motor1-limits-input" style="width:300px; height:20px;"></div>
   <div class="flex-horizontal"><label>Reverse</label><input id="motor1-reverse_input" type="checkbox"></div>
   
   <label>Motor 2</label>
   <div id="motor2-limits-input" style="width:300px; height:20px;"></div>
   <div class="flex-horizontal"><label>Reverse</label><input id="motor2-reverse_input" type="checkbox"></div>
   
   <label>Motor Pair</label>
   <div class="flex-horizontal"><label>Reverse</label><input id="motor-pair-reverse_input" type="checkbox"></div>
   
   <label>Servo 1</label>
   <div id="servo1-limits-input" style="width:300px; height:20px;"></div>
   <div class="flex-horizontal"><label>Reverse</label><input id="servo1-reverse_input" type="checkbox"></div>
   
   <label>Servo 2</label>
   <div id="servo2-limits-input" style="width:300px; height:20px;"></div>
   <div class="flex-horizontal"><label>Reverse</label><input id="servo2-reverse_input" type="checkbox"></div>
   
   <label>Distance Sensor</label>
   <div class="flex-horizontal"><label>Enable</label><input id="distance-sensor-enable-input" type="checkbox" onclick="onDistanceSensorEnabled(this)"></div>

</div>

<script>
noUiSlider.create(document.getElementById("motor1-limits-input"), {
   start: [0, 180],
   connect: true,
   range: {
      'min': 0,
      'max': 100
   }
});

noUiSlider.create(document.getElementById("motor2-limits-input"), {
   start: [0, 180],
   connect: true,
   range: {
      'min': 0,
      'max': 100
   }
});

noUiSlider.create(document.getElementById("servo1-limits-input"), {
   start: [0, 180],
   connect: true,
   range: {
      'min': 0,
      'max': 180
   }
});

noUiSlider.create(document.getElementById("servo2-limits-input"), {
   start: [0, 180],
   connect: true,
   range: {
      'min': 0,
      'max': 180
   }
});
</script>