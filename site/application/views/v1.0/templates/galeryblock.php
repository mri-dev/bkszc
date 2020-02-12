<?php foreach ((array)$this->newgalleries as $galslug => $gal ) { $date = strtotime($gal['idopont']); ?>
<div class="gal">
  <div class="wrapper">
    <div class="text __autocorrett-height-by-width" data-image-ratio="1:1">
      <div class="wrapper">
        <div class="date">
          <div class="year"><?=date('Y', $date)?></div>
          <div class="month"><?=utf8_encode(strftime('%B', $date))?></div>
          <div class="day"><?=date('d.', $date)?></div>
        </div>
        <div class="title" title="<?=$gal['title']?>"><a href="<?=$gal['url']?>"><?=$gal['title']?></a></div>
        <div class="images"><?=count($gal['images'])?> db kép</div>
      </div>
    </div>
    <div class="image __autocorrett-height-by-width" data-image-ratio="1:1">
      <?php
        $image = SOURCE . 'images/no-image-gallery.jpg';
        if ($gal['belyeg_kep'] != '') {
          $image = UPLOADS . $gal['belyeg_kep'];
        }
      ?>
      <div class="wrapper">
        <a href="<?=$gal['url']?>"><img src="<?=$image?>" alt="<?=$gal['title']?>"></a>
      </div>
    </div>
  </div>
</div>
<?php } ?>
