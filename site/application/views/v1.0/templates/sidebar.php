<div class="sidebar-holder">
  <div class="sidebar-block naptar-slide-block">
    <?php $this->render('templates/programnaptarslide') ?>
  </div>
  <div class="sidebar-block naptar-block">
    <?php $this->render('templates/programnaptar') ?>
  </div>
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
