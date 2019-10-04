<div class="group-gallery result-list">
  <?php if (empty($this->list)): ?>
    <div class="no-search-result">
      Nincs találat a keresési kifejezésre!
    </div>
  <?php else: ?>
    <?php foreach ( (array)$this->list as $d ) {
      $step++;
      ?>
      <article class="">
        <div class="wrapper">
          <?php
          $image = ($d['belyeg_kep'] != '') ? UPLOADS.$d['belyeg_kep'] : false;
          $url = '/galeria/folder/'.$d['slug'];
          ?>
          <?php if ($image): ?>
          <div class="image">
            <a target="_blank" href="<?=$url?>"><img src="<?=$image?>" alt="<?php echo $d['title']; ?>"></a>
          </div>
          <?php endif; ?>
          <div class="datas">
            <div class="title"><a target="_blank" href="<?=$url?>"> <i class="fa fa-camera-retro"></i> <?php echo $d['title']; ?></a> &mdash; <?php echo count($d['images']); ?> db kép</div>
            <div class="in-cats">
              <?php foreach ( (array)$d['in_cats'] as $cat ): ?>
              <a class="cat" target="_blank" href="<?=$cat['slug']?>"><span class="cat-label" style="background:<?=$cat['bgcolor']?>;"><?=$cat['neve']?></span> </a>
              <?php endforeach; ?>
              <div class="clr"></div>
            </div>
            <div class="date">
              <?php echo date('Y.m.d.', strtotime($d['uploaded'])); ?>
            </div>
            <div class="desc">
              <?php echo $d['description']; ?>
            </div>
          </div>

        </div>
      </article>
      <?php } ?>
      <?php echo $this->navigator; ?>
  <?php endif; ?>
</div>
