<div id="hardwareConfig" class="flex-vertical">

   <label>Motor 1</label>
   <div id="motor1-limits-input" style="width:300px; height:20px;"></div>
   <label>Motor 2</label>
   <div id="motor2-limits-input" style="width:300px; height:20px;"></div>
   <label>Servo 1</label>
   <div id="servo1-limits-input" style="width:300px; height:20px;"></div>
   <label>Servo 2</label>
   <div id="servo2-limits-input" style="width:300px; height:20px;"></div>

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