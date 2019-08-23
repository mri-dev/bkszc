<div class="sidebar-holder">
  <div class="sidebar-block naptar-block">
    <?php $this->render('templates/programnaptar') ?>
  </div>
  <div class="sidebar-block partner-list">
    <h3>Támogatóink - Partnereink</h3>
    <div class="holder">
      <div class="logos">
        <?php for ($i=0; $i < 4 ; $i++) { ?>
        <div class="logo">
          <div class="wrapper autocorrett-height-by-width" data-image-ratio="1:1">
            <a href="#"><img src="http://cp.bkszc.ideasandbox.eu//src/uploads/boritokepek/home-borito.jpg" alt=""></a>
          </div>
        </div>
        <?php } ?>
      </div>
      <div class="partner-links">
        <?php for ($i=0; $i < 10 ; $i++) { ?>
        <div class="link">
          <a href="#">> Támogató partner link</a>
        </div>
        <?php } ?>
      </div>
    </div>
  </div>
</div>
