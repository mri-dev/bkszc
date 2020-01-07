<?php
if ( isset($this->settings['countdown_event']) && $this->settings['countdown_event'] == '1')
{
  $today = new DateTime();
  $event = new DateTime($this->settings['countdown_event_date']);
  $interval = $today->diff($event);
  $dt = array();

  $nap = $interval->days;
  $ora = $interval->h;
  $perc = $interval->i;
  $masodperc = $interval->s;

  if ($event < $today) {
    $nap = 0;
    $ora = 0;
    $perc = 0;
    $masodperc = 0;
  }

  $dt['nap'][0] =  (strlen($nap) > 1) ? substr($nap,0,1) : 0;
  $dt['nap'][1] =  (strlen($nap) > 1) ? substr($nap,1) : substr($nap,0,1);
  $dt['ora'][0] =  (strlen($ora) > 1) ? substr($ora,0,1) : 0;
  $dt['ora'][1] =  (strlen($ora) > 1) ? substr($ora,1) : substr($ora,0,1);
  $dt['perc'][0] =  (strlen($perc) > 1) ? substr($perc,0,1) : 0;
  $dt['perc'][1] =  (strlen($perc) > 1) ? substr($perc,1) : substr($perc,0,1);
  $dt['masodperc'][0] =  (strlen($masodperc) > 1) ? substr($masodperc,0,1) : 0;
  $dt['masodperc'][1] =  (strlen($masodperc) > 1) ? substr($masodperc,1) : substr($masodperc,0,1);

  //print_r($dt);
?>
<div class="event-countdown" style="background-image: url('<?=ADMROOT.$this->settings['countdown_background']?>');">
  <div class="wrapper">
    <div class="pw">
      <script type="text/javascript">
        $(function(){
          // Let's go !
          Countdown.init();
        });
      </script>
      <div class="in-content">
        <div class="text text-before"><?php echo $this->settings['countdown_text_before']; ?></div>
        <div class="timeback">
          <div id="timetogo" class="countdown">
            <div class="bloc-time days" data-init-value="<?=$nap?>">
              <span class="count-title">Nap</span>
              <div class="figure dayd days-1">
                <span class="top"><?=$dt['nap'][0]?></span>
                <span class="top-back">
                  <span><?=$dt['nap'][0]?></span>
                </span>
                <span class="bottom"><?=$dt['nap'][0]?></span>
                <span class="bottom-back">
                  <span><?=$dt['nap'][0]?></span>
                </span>
              </div>

              <div class="figure days days-2">
                <span class="top"><?=$dt['nap'][1]?></span>
                <span class="top-back">
                  <span><?=$dt['nap'][1]?></span>
                </span>
                <span class="bottom"><?=$dt['nap'][1]?></span>
                <span class="bottom-back">
                  <span><?=$dt['nap'][1]?></span>
                </span>
              </div>
            </div>

            <div class="bloc-time hours" data-init-value="<?=$ora?>">
              <span class="count-title">Óra</span>

              <div class="figure hours hours-1">
                <span class="top"><?=$dt['ora'][0]?></span>
                <span class="top-back">
                  <span><?=$dt['ora'][0]?></span>
                </span>
                <span class="bottom"><?=$dt['ora'][0]?></span>
                <span class="bottom-back">
                  <span><?=$dt['ora'][0]?></span>
                </span>
              </div>

              <div class="figure hours hours-2">
                <span class="top"><?=$dt['ora'][1]?></span>
                <span class="top-back">
                  <span><?=$dt['ora'][1]?></span>
                </span>
                <span class="bottom"><?=$dt['ora'][1]?></span>
                <span class="bottom-back">
                  <span><?=$dt['ora'][1]?></span>
                </span>
              </div>
            </div>

            <div class="bloc-time min" data-init-value="<?=$perc?>">
              <span class="count-title">Perc</span>

              <div class="figure min min-1">
                <span class="top"><?=$dt['perc'][0]?></span>
                <span class="top-back">
                  <span><?=$dt['perc'][0]?></span>
                </span>
                <span class="bottom"><?=$dt['perc'][0]?></span>
                <span class="bottom-back">
                  <span><?=$dt['perc'][0]?></span>
                </span>
              </div>

              <div class="figure min min-2">
               <span class="top"><?=$dt['perc'][1]?></span>
                <span class="top-back">
                  <span><?=$dt['perc'][1]?></span>
                </span>
                <span class="bottom"><?=$dt['perc'][1]?></span>
                <span class="bottom-back">
                  <span><?=$dt['perc'][1]?></span>
                </span>
              </div>
            </div>

            <div class="bloc-time sec" data-init-value="<?=$masodperc?>">
              <span class="count-title">Másodperc</span>
                <div class="figure sec sec-1">
                <span class="top"><?=$dt['masodperc'][0]?></span>
                <span class="top-back">
                  <span><?=$dt['masodperc'][0]?></span>
                </span>
                <span class="bottom"><?=$dt['masodperc'][0]?></span>
                <span class="bottom-back">
                  <span><?=$dt['masodperc'][0]?></span>
                </span>
              </div>
              <div class="figure sec sec-2">
                <span class="top"><?=$dt['masodperc'][1]?></span>
                <span class="top-back">
                  <span><?=$dt['masodperc'][1]?></span>
                </span>
                <span class="bottom"><?=$dt['masodperc'][1]?></span>
                <span class="bottom-back">
                  <span><?=$dt['masodperc'][1]?></span>
                </span>
              </div>
            </div>
          </div>
        </div>
        <div class="text text-after"><?php echo $this->settings['countdown_text_after']; ?></div>
      </div>
    </div>
  </div>
</div>
<?php } ?>
