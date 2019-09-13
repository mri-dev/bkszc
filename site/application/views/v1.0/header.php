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
<? if(!empty($this->settings[google_analitics])): ?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', ' <?=$this->settings[google_analitics]?>', 'auto');
  ga('send', 'pageview');
</script>
<? endif; ?>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/hu_HU/sdk.js#xfbml=1&version=v2.3";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<header>
  <div class="top">
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
          <img class="bkszc" title="Budapesti Komplex Szakképzési Centrum" src="<?=IMG?>bkszc_logo.svg" style="height:30px;" alt="bkszc">
        </div>
        <div class="actions">
          <div class="kreta">
            <a href="https://bvszc-gundel.e-kreta.hu/" target="_blank" title="Kréta E-napló">
              <div class="ico">
                <img src="<?=IMG?>icons/ico-kreta.svg" alt="Kréta E-napló">
              </div>
            </a>
          </div>
          <div class="office">
            <a href="/kapcsolat" title="Belépés az Office levelezőrendszerbe">
              <div class="ico">
                <img src="<?=IMG?>icons/ico-office.svg" alt="Office">
              </div>
            </a>
          </div>
          <div class="facebook">
            <a href="/kapcsolat" title="Facebook oldalunk">
              <div class="ico">
                <i class="fa fa-facebook"></i>
              </div>
            </a>
          </div>
          <div class="position">
            <a href="/kapcsolat" title="Kapcsolat">
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
  							<?=$menu['nev']?> <? if($menu['child']): ?><i class="fa fa-angle-down"></i><? endif; ?></a>
    						<? if($menu['child']): ?>
    						<div class="sub nav-sub-view">
    								<div class="inside">
                      <ul>
                      <? foreach($menu['child'] as $child): ?>
                      <li class="<?=$child['css_class']?>">
                        <? if($child['link']): ?><a href="<?=$child['link']?>"><? endif; ?>
                        <span style="<?=$child['css_styles']?>"><?=$child['nev']?></span>
                        <? if($child['link']): ?></a><? endif; ?>
                      </li>
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
  </div>
  <?php if ( !$this->hideheadimg ): ?>
  <?php
    $imgheader = get_headers($this->head_img);
    $valid_imghead = (strpos($imgheader[0], '200 OK') !== false) ? true : false;
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
  <div class="pw">
