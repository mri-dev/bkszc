<form class="" action="" method="post">
	<div style="float:right;">
		<a href="/dolgozok/creator?b=1" class="btn btn-primary"><i class="fa fa-plus"></i> új dolgozó</a>
	</div>
</form>
<h1>Dolgozók listája <?php if ($_COOKIE[filtered] == '1'): ?><span style="color: red;">Szűrt lista</span><? endif; ?></h1>
<? if( true ): ?>
<?=$this->navigator?>
<br>
<?php echo $this->msg; ?>
<div class="row">
	<div class="col-md-12">
    	<div class="con con-row-list">
          <div class="row row-header">
          		<div class="col-md-7">Név / Szakterület, preferencia</div>
							<div class="col-md-1 center">Osztályfőnök</div>
							<div class="col-md-2 left">Telefon / E-mail cím</div>
              <div class="col-md-1 center">Látható</div>
              <div class="col-md-1" align="right"></div>
         	</div>
					<div class="row row-filter <? if($_COOKIE['filtered'] == '1'): ?>filtered<? endif;?>">
						<form class="" action="" method="post">
          		<div class="col-md-7">
              	<input type="text" class="form-control" name="nev" value="<?=$_COOKIE['filter_nev']?>" placeholder="Keresés...">
              </div>
							<div class="col-md-1 center"><input type="text" class="form-control" name="osztaly" value="<?=$_COOKIE['filter_osztaly']?>" placeholder=""></div>
							<div class="col-md-2 center"></div>
              <div class="col-md-1 center">
								<select class="form-control"  name="lathato">
				        	<option value="" selected="selected"># Mind</option>
		            	<option value="1" <?=(1 == $_COOKIE['filter_lathato'])?'selected="selected"':''?>>Látható</option>
		            	<option value="0" <?=(0 == $_COOKIE['filter_lathato'] && !is_null($_COOKIE['filter_lathato']))?'selected="selected"':''?>>Rejtve</option>
		            </select>
							</div>
              <div class="col-md-1 right">
								<?php if ($_COOKIE[filtered] == '1'): ?>
								<a href="/cikkek/clearfilters" class="btn btn-danger" title="Szűrőfeltételek törlése"><i class="fa fa-times"></i></a>
								<?php endif; ?>
              	<button name="filterList" class="btn btn-default"><i class="fa fa-search"></i></button>
              </div>
						</form>
         	</div>
          <?
            if( $this->list->has_items() ):
            while( $this->list->walk() ): $item = $this->list->the_item();
            ?>
            <div class="row deep<?=$item['deep']?> markarow  <?=($this->item && $this->gets[1] == 'szerkeszt' && $this->item->getId() == $item['ID'] ? 'on-edit' : '')?> <?=($this->item && $this->gets[1] == 'torles' && $this->item->getId() == $item['ID'] ? 'on-del' : '')?>">
            	  <div class="col-md-7">
                  <div class="img-thb">
										<?php if ( $item['profilkep'] != '' ): ?>
											<a href="<?=ADMROOT.UPLOADS.$item['profilkep']?>" class="zoom"><img src="<?=ADMROOT.UPLOADS.$item['profilkep']?>" alt=""></a>
										<?php else: ?>
											<a href="<?=ADMROOT.'src/images/no-image.jpg'?>" class="zoom"><img src="<?=ADMROOT.'src/images/no-image.jpg'?>" alt="Nincs kép"></a>
										<?php endif; ?>
                  </div>
                	<strong><?=$item['nev']?></strong>
                  <div class="subline"><?=$item['tantargyak']?></div>
                </div>               
                <div class="col-md-1 center"><?=$item['osztaly']?></div>
                <div class="col-md-2 left">
                  <?php if(!empty($item['telefon'])): ?><div title="Telefonszám"><?=$item['telefon']?></div><?php endif; ?>
                  <?php if(!empty($item['email'])): ?><div title="E-mail cím"><?=$item['email']?></div><?php endif; ?>
                </div> 
                <div class="col-md-1 center">
                	<? if($item[lathato] == '1'): ?><i style="color:green;" class="fa fa-check"></i><? else: ?><i style="color:red;" class="fa fa-times"></i><? endif; ?>
                </div>
                <div class="col-md-1 actions" align="right">
                    <a href="/<?=$this->gets[0]?>/creator/szerkeszt/<?=$item['ID']?>?b=1" title="Szerkesztés"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;
                    <a href="javascript:void(0)" onclick="deleteArticle(<?=$item['ID']?>, '<?=$item['nev']?>');" title="Törlés"><i class="fa fa-times"></i></a>
                </div>
           	</div>
            <? endwhile; else:?>
            	<div class="noItem">
                	Nincs megjeleníthető dolgozó!
                </div>
            <? endif; ?>
        </div>
    </div>
</div>
<?=$this->navigator?>
<script>
  function deleteArticle( id, title ) {
    var c = confirm('Biztos, hogy végleg törli a(z) "'+title+'" dolgozót?');
    if (c) {
      $.post("/ajax/post", {
        type : 'deleteDolgozo',
        id: id
      }, function(d){
        console.log(d);
        if (!d.error) {
          window.location.assign('?msgkey=msg&msg=Dolgozó véglegesen törölve: <strong>' + title+'</strong>');
        }
      }, "json");
    }
  }

  function responsive_filemanager_callback(field_id){
    var imgurl = $('#'+field_id).val();
    $('#url_img').find('img').attr('src',imgurl).show();
    $('#remove_url_img').show();
  }
</script>
<? endif; ?>
