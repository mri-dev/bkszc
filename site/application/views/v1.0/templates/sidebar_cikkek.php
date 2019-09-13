<div class="sidebar-holder">
  <?php if ( $this->aktual_news ): ?>
  <div class="sidebar-block newest-articles dot-navs dot-nav-arrows">
    <h3>Legutóbbi bejegyzések</h3>
    <div class="holder">
      <div class="article-group">
        <?php $i = 0; foreach ((array)$this->aktual_news as $new): $i++; ?>
        <article>
          <a href="<?php echo $new->getURL(); ?>"><?php echo $new->getTitle(); ?></a>
        </article>
        <?php if ( $i % 5 === 0 && $i < 25): ?>
        </div><div class="article-group">
        <?php endif; ?>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
  <script type="text/javascript">
    $(function(){
      $('.newest-articles > .holder').slick({
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        dots: true,
        arrow: true,
        autoplay: false,
        speed: 400
      });
    })
  </script>
  <div class="divider"></div>
  <?php endif; ?>
  <div class="sidebar-block">
    <?php if ($this->currentcat): ?>
    <h3><strong><?=$this->currentcat['name']?></strong> alkategóriák</h3>
    <?php else: ?>
    <h3>Kategóriák</h3>
    <?php endif; ?>
    <div class="list">
      <div class="cat <?=($_GET['cat'] == '')?'active':''?>">
        <a href="<?=$this->cikkroot?>"><span class="dot" style="color:black;"></span> Összes bejegyzés</a>
      </div>
      <?php if ($this->parent_cat): ?>
        <div class="backtoparent cat">
          <a href="/cikkek/kategoriak/<?=$this->parent_cat['slug']?>">< vissza: <strong><?=$this->parent_cat['name']?></strong>  </a>
        </div>
      <?php endif; ?>

      <?php foreach ( (array)$this->newscats as $nc ): if($this->is_archiv && in_array($nc['slug'], (array)$this->history->tematic_cikk_slugs)) continue; ?>
      <div class="cat deep<?=$nc['deep']?> <?=($_GET['cat'] == ($nc['slug']))?'active':''?>">
        <a href="<?=(in_array($nc['slug'], (array)$this->history->tematic_cikk_slugs))?'/':$this->cikkroot?>kategoriak/<?=($nc['slug'])?><?=(isset($_GET['src']))?'?src='.$_GET['src']:''?>"> > <?=$nc['neve']?> <span class="cnt">(<?=$nc['postc']?>)</span></a>
      </div>
      <?php endforeach; ?>
      <?php if (empty($this->newscats)): ?>
        <div class="no-cats cat">
          Nincsenek további kategóriák.
        </div>
      <?php endif; ?>
    </div>
  </div>
  <div class="divider"></div>
  <div class="sidebar-block">
    <h3>Archívum</h3>
    <?php if (true): ?>
    <div class="list">
      <?php foreach ((array)$this->archive_dates as $nc): ?>
      <div class="cat <?=($_GET['date'] == ($nc['date']))?'active':''?>">
        <a href="<?=$this->cikkroot.'date/'.$nc['date'].'/1'?>">> <?=$nc['datef']?> (<?=$nc['posts']?>)</a>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</div>
