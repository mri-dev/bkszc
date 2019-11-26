<div class="event-countdown">
  <div class="wrapper">
    <div class="pw">
      <script type="text/javascript">
        $(function(){
          var timmer = setInterval(function(){
            countdown.resetLabels();
            countdown.setLabels(
            ' | | | | | | | | | | ',
          	' | | | | | | | | | | ',
          	'<span class="sep">:</span> ',
          	'<span class="sep">:</span> ',
          	'');
            var cts = countdown(new Date(2019, 12, 24), null, countdown.DAYS|countdown.HOURS|countdown.MINUTES|countdown.SECONDS);
            $('#timetogo').html(cts.toHTML());
          }, 1000);
        });
      </script>
      <div class="in-content">
        <div class="text text-before">
          A téli szünetig még
        </div>
        <div class="timeback">
          <div id="timetogo"></div>
        </div>
        <div class="text text-before">
          van még hátra!
        </div>
      </div>
    </div>
  </div>
</div>
