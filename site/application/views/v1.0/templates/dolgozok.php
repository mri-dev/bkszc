<?php  
  $dolgozok = $data->getTree([]);

  if( $dolgozok && $dolgozok->has_items() ):
?>
<div class="dolgozo-lista">
  <div class="wrapper">
  <div class="ember-header">
    <div class="wrapper">
      <div class="profil"><?=__('Név / Beosztás')?></div>
      <div class="osztaly center"><?=__('Osztálya')?></div>
      <div class="contacts"><?=__('Elérhetőségek')?></div>
    </div>
  </div>
  <?php while( $dolgozok->walk() ): $item = $dolgozok->the_item(); ?>
  <div class="ember">
    <div class="wrapper">
      <?php if ( $item['profilkep'] != '' ): $fileinfo = getimagesize( UPLOADS.$item['profilkep']); $class = ($fileinfo && $fileinfo[0] > $fileinfo[1]) ? 'landscape': 'portrait'; ?>
      <div class="image <?=$class?> autocorrett-height-by-width" data-image-ratio="1:1">
        <a href="<?=UPLOADS.$item['profilkep']?>" class="zoom" data-caption="" title="<?php echo $item['nev']; ?>"><img src="<?=UPLOADS.$item['profilkep']?>" alt="<?php echo $item['nev']; ?>"></a>
      </div>        
      <?php endif; ?>
      <div class="profil">
        <div class="name"><?php echo $item['nev']; ?></div> 
        <div class="whois"><?php echo $item['tantargyak']; ?></div>       
      </div>
      <div class="osztaly center">
        <?php echo $item['osztaly']; ?>
      </div>
      <div class="contacts">
        <?php if(!empty($item['telefon'])): ?><div class="phone"><label for=""><?=__('Telefonszám')?></label><?php echo $item['telefon']; ?></div><?php endif; ?>
        <?php if(!empty($item['email'])): ?><div class="email"><label for=""><?=__('E-mail cím')?></label><?php echo $item['email']; ?></div><?php endif; ?>
      </div>
    </div>
  </div>
  <?php endwhile; ?>
  </div>
</div>
<?php endif; ?>