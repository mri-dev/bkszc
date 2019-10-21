<div class="sidebar-holder">
  <div class="sidebar-block">
    <?php if ((!isset($_GET['root']) && $this->cat != '') || ($this->gallery && $this->cat != '') ): ?>
      <h3><strong><?=$this->folders[$this->cat]['neve']?></strong> galériák:</h3>
      <div class="list">

        <div class="cat">
          <a href="<?=$this->cikkroot?>">< Összes galéria</a>
        </div>

        <?php if ($this->parent_cat): ?>
          <div class="backtoparent cat">
            <a href="/cikkek/kategoriak/<?=$this->parent_cat['slug']?>">< vissza: <strong><?=$this->parent_cat['name']?></strong>  </a>
          </div>
        <?php endif; ?>
        <?php foreach ( (array)$this->folders[$this->cat]['items'] as $nc ):?>
        <div class="cat <?=($nc['slug'] == $this->gallery['slug'])?'active':''?>">
          <a href="/galeria/folder/<?=$nc['slug']?>"> <i class="fa fa-folder"></i> <?=$nc['title']?> <span class="cnt">(<?=count($nc['images'])?> kép)</span></a>
        </div>
        <?php endforeach; ?>

      </div>
      <div class="divider"></div>
    <?php endif; ?>

  </div>
  <?php if ( $this->newgalleries ): ?>
  <div class="sidebar-block partner-list new-gallery-list">
    <h3>Friss galériák</h3>
    <div class="holder">
      <div class="partner-links">
        <?php foreach ((array)$this->newgalleries as $slug => $gal ) { ?>
          <div class="link">
            <a href="<?=$gal['url']?>"><i class="fa fa-picture-o"></i>  &nbsp; <?=($gal['default_cat']['neve'])?'<span class="cat">'.$gal['default_cat']['neve'].' / </span>':''?><strong><?=$gal['title']?></strong> (<?=count($gal['images'])?>)</a>
          </div>
        <?php } ?>
      </div>
    </div>
  </div>
  <div class="divider"></div>
  <?php endif; ?>

  <div class="sidebar-block partner-list">
    <h3>Támogatóink - Partnereink</h3>
    <div class="holder">
      <?php if (true): ?>
      <div class="logos">
        <?php for ($i=1; $i <=4 ; $i++) { $plogo = $this->settings['tamogato_logo_t'.$i]; if($plogo == '') continue; ?>
        <div class="logo">
          <div class="wrapper autocorrett-height-by-width" data-image-ratio="1:1">
            <?php if ($this->settings['tamogato_logo_turl'.$i] != ''): ?>
              <a href="<?=$this->settings['tamogato_logo_turl'.$i]?>" target="_blank"><img src="<?=ADMROOT.$plogo?>" alt="Partner logo"></a>
            <?php else: ?>
              <img src="<?=ADMROOT.$plogo?>" alt="Partner logo">
            <?php endif; ?>
          </div>
        </div>
        <?php } ?>
      </div>
      <?php endif; ?>
      <?php if ( $this->partnereink_news->tree_items > 0 ): ?>
      <div class="partner-links">
        <?php while ( $this->partnereink_news->walk() ) { $this->partnereink_news->the_news(); ?>
        <div class="link">
          <a href="<?=$this->partnereink_news->getUrl()?>">> &nbsp; <?=$this->partnereink_news->getTitle()?></a>
        </div>
        <?php } ?>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>
