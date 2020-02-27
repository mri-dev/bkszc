<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/html4"
      xmlns:og="http://ogp.me/ns#"
      xmlns:fb="http://www.facebook.com/2008/fbml" lang="hu-HU" ng-app="Gundel">
<head>
    <title><?=$this->title?></title>
    <?=$this->addMeta('robots','index,folow')?>
    <?=$this->SEOSERVICE?>
    <?php if ( $this->settings['FB_APP_ID'] != '' ): ?>
    <meta property="fb:app_id" content="<?=$this->settings['FB_APP_ID']?>" />
    <?php endif; ?>
    <? $this->render('meta'); ?>
</head>
<body class="<?=$this->bodyclass?>">
<? if(!empty($this->settings['google_analitics'])): ?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', ' <?=$this->settings['google_analitics']?>', 'auto');
  ga('send', 'pageview');
</script>
<? endif; ?>
<div id="fb-root"></div>
  <script>(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "https://connect.facebook.net/hu_HU/sdk.js#xfbml=1&version=v3.0";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));</script>
<div class="mobile-menu show-on-mobile">
  <div class="header">
    <div class="title">
      <img src="<?=IMG?>GK_logo_pikto.svg" alt="Gundel Iskola">
      <?=__('Gundel Károly<br>Szakképző Iskola')?>
      <div class="sub"><a href="tel:<?=$this->settings['page_author_phone']?>"><?=$this->settings['page_author_phone']?></a> &bull; <a href="mailto:<?=$this->settings['primary_email']?>"><?=$this->settings['primary_email']?></a></div>
      <div class="clr"></div>
    </div>
    <div class="close" mb-event="true" data-mb='{ "event": "toggleOnClick", "target" : ".mobile-menu", "menu": true }'><img src="<?=IMG?>close-x.svg" alt="Menü bezárása"></div>
  </div>
  <div class="menu-wrapper">
    <ul>
      <? foreach ( $this->menu_header->tree as $menu ): ?>
      <li class="<?=$menu['css_class']?><?=($menu['child'])?' has-child':''?>">
        <a href="<?=($menu['link']?:'')?>">
          <? if($menu['kep']): ?><img src="<?=\PortalManager\Formater::sourceImg($child['kep'])?>"><? endif; ?>
          <?=$menu['nev']?><? if($menu['child']&& false): ?><i class="fa fa-angle-down"></i><? endif; ?></a>
          <? if($menu['child']): ?>
          <div class="sub nav-sub-view">
              <div class="inside">
                <ul>
                <? foreach($menu['child'] as $child):  ?>
                <?php if($child['tipus'] == 'kategoria_alkategoria_lista'): ?>
                  <?php echo $child['kategoria_alkategoria_lista_li']; ?>
                <?php else: ?>
                <li class="<?=$child['css_class']?>">
                  <? if($child['link']): ?><a href="<?=$child['link']?>"><? endif; ?>
                  <span style="<?=$child['css_styles']?>"><?=$child['nev']?></span>
                  <? if($child['link']): ?></a><? endif; ?>
                </li>
                <?php endif; ?>
                <? endforeach; ?>
                </ul>
              </div>
          </div>
          <? endif; ?>
      </li>
      <? endforeach; ?>
    </ul>
  </div>
</div>
<header>
  <div class="top hide-on-mobile">
    <div class="pw">
      <div class="flex">
        <div class="contact">
          <div class="flex">
            <div class="phone">
              <div class="block-holder">
                <i class="fa fa-phone"></i>
                <div class="data">
                  <div class="h">Központi telefon:</div>
                  <div class="v"><a href="tel:<?=$this->settings['page_author_phone']?>"><?=$this->settings['page_author_phone']?></a></div>
                </div>
              </div>
            </div>
            <div class="email">
              <div class="block-holder">
                <i class="fa fa-envelope-o"></i>
                <div class="data">
                  <div class="h">E-mail:</div>
                  <div class="v"><a href="mailto:<?=$this->settings['primary_email']?>"><?=$this->settings['primary_email']?></a></div>
                </div>
              </div>
            </div>
            <div class="address">
              <div class="block-holder">
                <i class="fa fa-map-marker"></i>
                <div class="data">
                  <div class="h">Cím:</div>
                  <div class="v"><?=$this->settings['page_author_address']?></div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="ugyintezes">
          <div class="block-holder">
            <i class="fa fa-clock-o"></i>
            <div class="data">
              <div class="h">Ügyintézés</div>
              <div class="v"><?=$this->settings['mobile_number_elerhetoseg']?></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="main">
    <div class="pw">
      <div class="mobil-nav show-on-mobile" mb-event="true" data-mb='{ "event": "toggleOnClick", "target" : ".mobile-menu", "menu": true }'>
        <i class="fa fa-bars"></i>
      </div>
      <div class="flex">
        <div class="logo">
          <a href="<?=$this->settings['page_url']?>"><img src="<?=IMG?>gk_logo.svg" style="height:80px;" alt="<?=$this->settings['page_title']?>"></a>
          <div class="bkszc">
            <a href="https://bkszc.hu" target="_blank"><img title="Budapesti Komplex Szakképzési Centrum" src="<?=IMG?>bkszc_logo.svg" style="height:38px;" alt="bkszc"></a>
          </div>

        </div>
        <div class="actions">
          <div class="kreta">
            <a href="https://bkszc-gundel.e-kreta.hu" target="_blank" title="Kréta E-napló" class="autocorrett-height-by-width" data-image-ratio="1:1" data-image-under="480">
              <div class="ico">
                <img src="<?=IMG?>icons/ico-kreta.svg" alt="Kréta E-napló">
              </div>
            </a>
          </div>
          <div class="office">
            <a href="http://outlook.com/gundeliskola.hu" target="_blank" title="Belépés az Office levelezőrendszerbe" class="autocorrett-height-by-width" data-image-ratio="1:1" data-image-under="480">
              <div class="ico">
                <img src="<?=IMG?>icons/ico-office.svg" alt="Office">
              </div>
            </a>
          </div>
          <div class="facebook">
            <a href="<?=$this->settings['social_facebook_link']?>" target="_blank" title="Facebook oldalunk" class="autocorrett-height-by-width" data-image-ratio="1:1" data-image-under="480">
              <div class="ico">
                <i class="fa fa-facebook"></i>
              </div>
            </a>
          </div>
          <div class="instagram">
            <a href="<?=$this->settings['social_instagram_link']?>" target="_blank" title="Instagram oldalunk" class="autocorrett-height-by-width" data-image-ratio="1:1" data-image-under="480">
              <div class="ico">
                <i class="fa fa-instagram"></i>
              </div>
            </a>
          </div>
          <div class="position">
            <a href="/kapcsolat" title="Kapcsolat" class="autocorrett-height-by-width" data-image-ratio="1:1" data-image-under="480">
              <div class="ico">
                <i class="fa fa-map-marker"></i>
              </div>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="bottom">
    <div class="pw">
      <div class="flex">
        <div class="nav">
  				<ul>
  					<? foreach ( $this->menu_header->tree as $menu ): ?>
  					<li>
  						<a href="<?=($menu['link']?:'')?>">
  							<? if($menu['kep']): ?><img src="<?=\PortalManager\Formater::sourceImg($child['kep'])?>"><? endif; ?>
  							<?=$menu['nev']?><? if($menu['child']&& false): ?><i class="fa fa-angle-down"></i><? endif; ?></a>
    						<? if($menu['child']): ?>
    						<div class="sub nav-sub-view">
    								<div class="inside">
                      <ul>
                      <? foreach($menu['child'] as $child):  ?>
                      <?php if($child['tipus'] == 'kategoria_alkategoria_lista'): ?>
                        <?php echo $child['kategoria_alkategoria_lista_li']; ?>
                      <?php else: ?>
                      <li class="<?=$child['css_class']?>">
                        <? if($child['link']): ?><a href="<?=$child['link']?>"><? endif; ?>
                        <span style="<?=$child['css_styles']?>"><?=$child['nev']?></span>
                        <? if($child['link']): ?></a><? endif; ?>
                      </li>
                      <?php endif; ?>
                      <? endforeach; ?>
                      </ul>
    								</div>
    						</div>
    						<? endif; ?>
  					</li>
  					<? endforeach; ?>
  				</ul>
  			</div>
      </div>
    </div>
    <div class="searcher-block">
      <div class="pw">
        <div class="searcherwrap">
          <form class="" action="/search" method="get">
            <div class="search-inp-holder">
              <input type="text" name="src" value="<?=$_GET['src']?>" placeholder="Keresés...">
            </div>
            <div class="search-sender">
              <button type="submit">Keresés</button>
            </div>
            <div class="toggler" mb-event="true" data-mb='{ "event": "toggleOnClick", "target" : ".searcherwrap", "searcher": true}'>
              <i class="fa fa-search"></i>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <?php if ( !$this->hideheadimg ): ?>
  <?php
    $imgheader = ($this->head_img) ? get_headers($this->head_img) : false;
    $valid_imghead = ($imgheader) ? ((strpos($imgheader[0], '200 OK') !== false) ? true : false) : false;
  ?>
  <div class="header-img<?=(!$valid_imghead)?' noimage':''?><?=($this->head_img_title != '')?' has-text':''?>" style="<?=($valid_imghead)?'background-image: url(\''.$this->head_img.'\');':''?>">
    <div class="pw">
      <div class="htitle">
        <?=$this->head_img_title?>
      </div>
    </div>
  </div>
  <?php endif; ?>
</header>
<div class="page-wrapper">
  <?php if (!$this->homepage): ?>
  <div class="pw">
  <?php endif; ?>
