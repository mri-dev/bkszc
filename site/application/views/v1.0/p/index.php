<? if( $this->page->getId() ): ?>
<? if($this->page->getCoverImg() && false ): ?>
<div class="cover-img" style="background-image: url('<?=\PortalManager\Formater::sourceImg($this->page->getCoverImg())?>');"></div>
<? endif; ?>
<div class="main-content">
  <div class="page page-width <?=($this->page->getCoverImg() && false) ? 'covered-page':'' ?>">
          <div class="responsive-view <?=(!$this->parent->getId())?'full-width':''?>">
          <div class="page-view">
              <?=$this->page_msg?>
              <?php if (false): ?>
                <div class="title"><h1><?=$this->page->getTitle()?></h1></div>
              <?php endif; ?>
              <div class="content">
                  <?=$this->page->textRewrites($this->page->getHtmlContent())?>
              </div>
              <? if( count($this->page->getImageSet()) > 0 ): ?>
              <div class="image-set">
                  <? $s = 0; foreach( $this->page->getImageSet() as $img ): $s++; $ws = 250; ?>
                  <div class="img"><span class="helper"></span><a href="<?=\PortalManager\Formater::productImage($img)?>" rel="page-images" class="zoom"><img src="/render/thumbnail/?i=admin<?=$img?>&w=<?=$ws?>" alt=""></a></div>
                  <? endforeach;?>
              </div>
              <? endif; ?>
          </div>
      </div>
  </div>
</div>

<?php if ( $this->page->getUrl() == 'kapcsolat' && $this->settings['page_author_address'] != ''): ?>
  <div class="address-map-holder">
    <iframe src="//www.google.com/maps/embed/v1/place?language=hu-HU&q=Magyarország, <?=$this->settings['page_author_address']?>&zoom=16&key=<?=APIKEY_GOOGLE_MAP_EMBEDKEY?>" allowfullscreen frameborder="0"></iframe>
  </div>
<?php endif; ?>
<script type="text/javascript">
    $(function(){
        var url_anchor = window.location.hash.substring( 1 );

        if( url_anchor != '' && typeof url_anchor !== 'undefined' ) {
            $('.product-feature-table .feature').addClass( 'bind-overlay' );

            $('.product-feature-table .feature.'+url_anchor).removeClass( 'bind-overlay' );
        }

    })
</script>
<? else: ?>
    <? $this->render( 'PageNotFound'); ?>
<? endif; ?>
