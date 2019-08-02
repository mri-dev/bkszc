<div class="content-holder">
  <div class="content-wrapper">
    <div class="article-block actual-news-block">
      <div class="holder">
      <?php for ($i=0; $i < 1 ; $i++) { ?>
      <article class="actual autocorrett-height-by-width" data-image-ratio="16:8">
        <div class="wrapper">
          <div class="content">
            <div class="text">
              <div class="title">
                EBÉDBEFIZETÉS 2019 JÚNIUSRA
              </div>
              <div class="desc">
                FIGYELEM! A befizetés
                ÁTUTALÁSSAL is
                történhet.<br><br>
                Kérjük ne felejtse el az
                adatkezelési
                nyilatkozatot sem!<br><br>
                A menza- és az extra
                menü étlapja a lap
                alján tölthető le!
              </div>
            </div>
            <div class="morebtn">
              <a href="#">Bővebben</a>
            </div>
          </div>
          <div class="image">
            <img src="http://cp.bkszc.web-pro.hu//src/uploads/boritokepek/home-borito.jpg" alt="">
          </div>
        </div>
      </article>
      <?php } ?>
      </div>
    </div>
    <div class="article-block news-block">
      <div class="head">
        <h3><img src="<?=IMG?>icons/ico-time-circle.svg" alt="Legfrissebb hírek" class="ico">Legfrissebb hírek</h3>
      </div>
      <div class="holder">
        <? $this->render('templates/newsblock'); ?>
      </div>
    </div>
    <div class="article-block galery-block">
      <div class="head">
        <h3><img src="<?=IMG?>icons/ico-galery-img-circle.svg" alt="Galéria" class="ico">Galéria</h3>
      </div>
      <div class="holder">
        <? $this->render('templates/galeryblock'); ?>
      </div>
    </div>
  </div>
</div>
