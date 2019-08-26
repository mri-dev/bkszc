<?php if ($this->news): ?>
<div class="news-group">
<?php $i = -1; foreach ( (array)$this->news as $new ) { $i++; ?>
  <article class="news">
    <div class="wrapper">
      <div class="title">
        <a href="<?=$new->getUrl()?>"><strong><?=$new->getTitle()?></strong></a>
      </div>
      <div class="desc"><?=$new->getDescription()?></div>
      <div class="navlinks">
        <a href="<?=$new->getUrl()?>">Bővebben ></a>
      </div>
    </div>
  </article>
  <?php if ( $i % 2 !== 0 ): ?>
  </div><div class="news-group">
  <?php endif; ?>
<?php } ?>
</div>
<?php endif; ?>
