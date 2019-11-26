<div class="pw">
  <div class="content-holder">
    <div class="content-wrapper">
      <?php if ($this->aktual_news): ?>
      <div class="article-block actual-news-block">
        <div class="holder">
        <?php foreach ((array)$this->aktual_news as $new ) { ?>
        <article class="actual autocorrett-height-by-width" data-image-ratio="16:8">
          <div class="wrapper">
            <div class="content">
              <div class="text">
                <div class="title"><?=$new->getTitle()?></div>
                <div class="subtitle">
                  <span title="Bejegyzés közzétéve" class="date"><i class="fa fa-clock-o"></i> <?=substr(\PortalManager\Formater::dateFormat($new->getIdopont(), $this->settings['date_format']),0,-6)?></span>
                </div>
                <div class="desc"><?=$new->getDescription()?></div>
              </div>
              <div class="morebtn">
                <a href="<?=$new->getUrl()?>">Bővebben</a>
              </div>
            </div>
            <?php $image = $new->getImage(true); ?>
            <div class="image" style="background-image:url('<?=$image?>');">
            </div>
          </div>
        </article>
        <?php } ?>
        </div>
        <script type="text/javascript">
          $(function(){
            $('.actual-news-block > .holder').slick({
              infinite: true,
              slidesToShow: 1,
              slidesToScroll: 1,
              dots: true,
              arrow: true,
              autoplay: true,
              delay: 5000,
              speed: 1000
            });
          })
        </script>
      </div>
      <?php endif; ?>

      <div class="article-block news-block slide-view">
        <div class="head">
          <h3><img src="<?=IMG?>icons/ico-time-circle.svg" alt="Legfrissebb hírek" class="ico">Legfrissebb hírek</h3>
          <div class="links"><a href="/cikkek/kategoriak/aktualis_hirek">Összes hír</a></div>
        </div>
        <div class="holder">
          <? $this->render('templates/newsblock'); ?>
        </div>
        <script type="text/javascript">
          $(function(){
            $('.news-block > .holder').slick({
              infinite: true,
              slidesToShow: 2,
              slidesToScroll: 1,
              dots: false,
              arrow: true
            });
          })
        </script>
      </div>
    </div>
  </div>
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
</div>

<? $this->render('templates/eventcountdown'); ?>

<div class="pw">
  <div class="content-holder">
    <div class="content-wrapper">
      <div class="article-block galery-block">
        <div class="head">
          <h3><img src="<?=IMG?>icons/ico-galery-img-circle.svg" alt="Galéria" class="ico">Galéria</h3>
          <div class="links"><a href="/galeria">Összes galéria</a></div>
        </div>
        <div class="holder">
          <? $this->render('templates/galeryblock'); ?>
        </div>
        <script type="text/javascript">
          $(function(){
            $('.galery-block > .holder').slick({
              infinite: true,
              slidesToShow: 3,
              slidesToScroll: 1,
              dots: false,
              arrow: true
            });
          })
        </script>
      </div>
    </div>
  </div>
  <div class="sidebar-holder nomargintop">
    <div class="sidebar-block alapitvany-list">
      <div class="header">
        <h3>Alapítványi hírek</h3>
      </div>
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

<div class="pw">
  <? $this->render('templates/nezzen_el_erre_is'); ?>
</div>
