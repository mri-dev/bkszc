<div class="group-events result-list">
  <?php if (!$this->list->has_news()): ?>
    <div class="no-search-result">
      Nincs találat a keresési kifejezésre!
    </div>
  <?php else: ?>
    <?php while ( $this->list->walk() ) {
      $step++;
      $arg = $this->list->the_news();
      $categories = $this->list->getCategories();
      $arg['date_format'] = $this->settings['date_format'];
      $arg['newscats'] = $this->newscats;
      $read_prefix = (isset($_GET['cat']) && $_GET['cat'] != '') ? $_GET['cat'] : 'olvas';
      $arg['url'] = $this->list->getUrl($read_prefix, true);
      ?>
      <article class="">
        <div class="wrapper">
          <?php $image = $this->list->getImage();?>
          <?php if ($image): $image = $this->list->getImage(true); ?>
          <div class="image">
            <a target="_blank" href="<?=$arg['url']?>"><img src="<?=$image?>" alt="<?php echo $this->list->getTitle(); ?>"></a>
          </div>
          <?php endif; ?>
          <div class="datas">
            <?php
            $date_start = $this->list->getIdopont('Y.m.d.');
            $date_end = ($this->list->getEndIdopont()) ? $this->list->getEndIdopont('Y.m.d.') : false;
            $start_end_date_same = ($date_end && $date_end === $date_start) ? true : false;
            ?>
            <div class="title"><a target="_blank" href="<?=$arg['url']?>"><?php echo $this->list->getTitle(); ?></a></div>
            <div class="date">
              <span class="in-date"><span title="Esemény kezdete"><i class="fa fa-clock-o"></i> <?php echo $date_start; $hour = $this->list->getIdopont('H:i'); ?></span> <?php if($hour != '00:00'): ?><span class="hour" title="Esemény kezdete"><?=$hour?></span>
                <?php if($date_end && !$start_end_date_same): ?> <span title="Esemény vége">&mdash; <?=$date_end?></span><? if($this->list->getEndIdopont('H:i') != '00:00'):?><span class="hour_end" title="Esemény vége"><?=$this->list->getEndIdopont('H:i')?></span><? endif;?><? endif;?>
              <?=($date_end && $start_end_date_same)?'<span title="Esemény vége" class="hour_end">'.$this->list->getEndIdopont('H:i').'</span>':''?><? endif; ?></span>
            </div>
            <div class="clr"></div>
            <div class="in-cats">
              <?php foreach ( (array)$categories['list'] as $cat ): ?>
              <a class="cat" target="_blank" href="<?=($cat[is_tematic])?'/':'/cikkek/kategoriak/'?><?=$cat['slug']?>"><?=$cat['label']?></a>
              <?php endforeach; ?>
              <div class="clr"></div>
            </div>
            <div class="desc">
              <?php echo $this->list->getDescription(); ?>
            </div>
          </div>
        </div>
      </article>
      <?php } ?>
      <?php echo $this->navigator; ?>
  <?php endif; ?>
</div>
