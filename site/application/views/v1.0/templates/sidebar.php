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
