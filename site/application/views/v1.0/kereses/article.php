<div class="group-article result-list">
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
          <?php $image = $this->list->getImage(true); ?>
          <?php if ($image): ?>
          <div class="image">
            <a target="_blank" href="<?=$arg['url']?>"><img src="<?=$image?>" alt="<?php echo $this->list->getTitle(); ?>"></a>
          </div>
          <?php endif; ?>
          <div class="datas">
            <div class="title"><a target="_blank" href="<?=$arg['url']?>"><?php echo $this->list->getTitle(); ?></a></div>
            <div class="in-cats">
              <?php foreach ( (array)$categories['list'] as $cat ): ?>
              <a class="cat" target="_blank" href="<?=($cat['is_tematic'])?'/':'/cikkek/kategoriak/'?><?=$cat['slug']?>"><?=$cat['label']?></a>
              <?php endforeach; ?>
              <div class="clr"></div>
            </div>
            <div class="date">
              <?php echo $this->list->getIdopont('Y.m.d.'); ?>
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
