<div class="sidebar-holder">
  <div class="sidebar-block">
    <?php if (!isset($_GET['root']) && $this->cat != ''): ?>
      <h3><strong><?=$this->folders[$this->cat]['neve']?></strong> galériák:</h3>
      <div class="list">

        <div class="cat <?=($_GET['cat'] == '')?'active':''?>">
          <a href="<?=$this->cikkroot?>"><span class="dot" style="color:black;"></span> Összes galéria</a>
        </div>

        <?php if ($this->parent_cat): ?>
          <div class="backtoparent cat">
            <a href="/cikkek/kategoriak/<?=$this->parent_cat['slug']?>">< vissza: <strong><?=$this->parent_cat['name']?></strong>  </a>
          </div>
        <?php endif; ?>
        <?php foreach ( (array)$this->folders[$this->cat]['items'] as $nc ):?>
        <div class="cat">
          <a href="/galeria/folder/<?=$nc['slug']?>"> <i class="fa fa-folder"></i> <?=$nc['title']?> <span class="cnt">(<?=count($nc['images'])?> kép)</span></a>
        </div>
        <?php endforeach; ?>

      </div>
      <div class="divider"></div>
    <?php endif; ?>

  </div>
  <div class="sidebar-block">
    <h3>Friss galériák</h3>
  </div>
  <div class="divider"></div>
  <div class="sidebar-block partner-list">
    <h3>Támogatóink - Partnereink</h3>
    <div class="holder">
      <?php if (false): ?>
      <div class="logos">
        <?php for ($i=0; $i < 4 ; $i++) { ?>
        <div class="logo">
          <div class="wrapper autocorrett-height-by-width" data-image-ratio="1:1">
            <a href="#"><img src="http://cp.bkszc.ideasandbox.eu//src/uploads/boritokepek/home-borito.jpg" alt=""></a>
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
