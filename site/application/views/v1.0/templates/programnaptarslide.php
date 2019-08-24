<?php if ($this->futureprograms && count($this->futureprograms) > 0): ?>
<div class="wrapper">
  <?php foreach ((array)$this->futureprograms as $d): $start_date = rtrim(str_replace('.','-', $d['date']['start']), '-'); ?>
  <div class="event">
    <div class="wrapper">
      <div class="date">
        <div class="year"><?=date('Y.', strtotime($start_date))?></div>
        <div class="month"><?=ucfirst(utf8_encode(strftime('%B', strtotime($start_date))))?></div>
        <div class="day"><?=date('d.', strtotime($start_date))?></div>
      </div>
      <div class="evdata">
        <div class="title"><a href="<?=$d['url']?>"><?=$d['title']?></a></div>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>
<script type="text/javascript">
  $(function(){
    $('.naptar-slide-block > .wrapper').slick({
      infinite: true,
      slidesToShow: 1,
      slidesToScroll: 1,
      autoplay: true,
      speed: 400,
      delay: 5000
    });
  })
</script>
<?php endif; ?>
