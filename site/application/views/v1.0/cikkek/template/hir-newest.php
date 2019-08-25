<article class="news newest <?=($belyeg_kep == '')?'no-img':''?>">
  <h2><div class="cit"><i class="fa fa-clock-o style-red"></i> Legfrissebb</div><div class="fakeline"></div></h2>
  <div class="wrapper">
    <?php if (false): $belyeg_kep = ($belyeg_kep == '') ? IMG.'no-image.png' : \PortalManager\Formater::sourceImg($belyeg_kep); ?>
    <div class="img __autocorrett-height-by-width" data-image-ratio="1:1">
      <a href="<?php echo $url; ?>"><img src="<?=$belyeg_kep?>" alt="<?=$cim?>"></a>
    </div>
    <?php endif; ?>
    <div class="title"><h2><a href="<?php echo $url; ?>"><?=$cim?></a></h2></div>
    <?php if ($categories['list']): ?>
    <div class="in-cats">
      <?php foreach ( (array)$categories['list'] as $cat ): ?>
      <a class="cat" href="<?=($cat[is_tematic])?'/':'/cikkek/'?><?=$cat['slug']?>"><?=$cat['label']?></a>
      <?php endforeach; ?>
      <div class="clr"></div>
    </div>
    <?php endif; ?>
  	<div class="desc"><?=$bevezeto?></div>
  	<div class="navlinks"><a href="<?php echo $url; ?>">Bővebben <i class="fa fa-angle-right"></i></a></div>
  	<div class="clr"></div>
  </div>
  <h2><div class="cit"><i class="fa fa-clock-o style-green"></i> További cikkek</div><div class="fakeline"></div></h2>
</article>
