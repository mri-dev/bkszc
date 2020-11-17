<?php  
  $dolgozok = $data->getTree([]);

  if( $dolgozok && $dolgozok->has_items() ):
?>
<div class="dolgozo-lista">
  <div class="wrapper">
  <?php while( $dolgozok->walk() ): $item = $dolgozok->the_item(); ?>
  <div class="ember">
    <div class="wrapper">
      <div class="image">
        kép
      </div>
      <div class="profil">
        <div class="name"><?php echo $item['nev']; ?></div> 
        <div class="whois"><?php echo $item['tantargyak']; ?></div>       
      </div>
      <div class="osztaly">
        <?php echo $item['osztaly']; ?>
      </div>
      <div class="contacts">
        <div class="phone"><?php echo $item['telefon']; ?></div>
        <div class="email"><?php echo $item['email']; ?></div>
      </div>
    </div>
  </div>
  <?php endwhile; ?>
  </div>
</div>
<?php endif; ?>