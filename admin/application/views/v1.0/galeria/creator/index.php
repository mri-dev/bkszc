<div style="float:right;">
	<a href="/cikkek/kategoriak" class="btn btn-default"><i class="fa fa-bars"></i> kategóriák</a>
	<a href="/galeria/" class="btn btn-default"><i class="fa fa-th"></i> galériák</a>
</div>
<h1>Galéria</h1>
<?=$this->msg?>
<? if($this->gets[2] == 'torles'): ?>
<form action="" method="post">
<input type="hidden" name="delId" value="<?=$this->gets[3]?>" />
<div class="row np">
	<div class="col-md-12">
    	<div class="con con-del">
            <h2>Galéria törlése</h2>
            Biztos, hogy törli a(z) <strong><?=$this->news['title']?></strong> galériát? A művelet nem visszavonható és véglegesen törli a képeket és adatokat.
            <div class="row np">
                <div class="col-md-12 right">
                    <a href="/<?=$this->gets[0]?>/" class="btn btn-danger"><i class="fa fa-times"></i> NEM</a>
                    <button class="btn btn-success">IGEN <i class="fa fa-check"></i> </button>
                </div>
            </div>
        </div>
    </div>
</div>
</form>
<? endif; ?>
<? if($this->gets[2] != 'torles'): ?>
<?php
$catids = array();
if ($this->news && $this->news['in_cats']) {
	foreach ((array)$this->news['in_cats'] as $ct) {
		$catids[] = (int)$ct['id'];
	}
}
$scats['ids'] = $catids;
?>
<form action="" method="post" enctype="multipart/form-data">
  <div class="row-neg">
    <div class="row">
      <div class="col-md-3">
        <div class="con cat-tree-list">
          <h2>Kategóriába csatolás</h2>
          <?php if ( $this->categories ): ?>
            <?php while( $this->categories->walk() ):
            $cat = $this->categories->the_cat(); ?>
            <div class="deep<?php echo $cat['deep']; ?>">
              <input type="checkbox" class="cont-binder" data-cont-value="<?=$cat['slug']?>" id="cats<?=$cat['ID']?>" name="cats[]" value="<?=$cat['ID']?>" <?=(($this->news && in_array($cat['ID'], $scats['ids'])) || in_array($cat['ID'], $_POST['cats']) )?'checked="checked"':''?>> <label for="cats<?=$cat['ID']?>"><?=$cat['neve']?></label>
            </div>
            <?php endwhile; ?>
          <?php endif; ?>
        </div>
      </div>
    	<div class="col-md-9">
        	<div class="con <?=($this->gets[2] == 'szerkeszt')?'con-edit':''?>">
            <h2><? if($this->gets[2] == 'szerkeszt'): ?>Galéria szerkesztése<? else: ?>Új galéria hozzáadása<? endif; ?></h2>
            <div class="row-neg">
              <div class="row">
                  <div class="col-md-6">
                    <label for="cim">Cím*</label>
                      <input type="text"class="form-control" name="cim" id="cim" value="<?=($this->news ? $this->news['title'] : '')?>">
                  </div>
                  <div class="col-md-5">
                      <label for="eleres">Elérési kulcs: <?=\PortalManager\Formater::tooltip('Hagyja üresen, hogy a rendszer automatikusan generáljon elérési kulcsot. <br><br>Kérjük ne használjon ékezeteket, speciális karaktereket és üres szóközöket.<br> Példa a helyes használathoz: ez_az_elso_bejegyzesem');?></label>
                      <div class="input-group">
                        <span class="input-group-addon">
                          <i class="fa fa-home" title="<?=HOMEDOMAIN?>galeria/folder/"></i>
                          </span>
                        <input type="text" class="form-control" placeholder="valami_szoveg" name="eleres" id="eleres" value="<?=($this->news ? $this->news['slug'] : '')?>">
                      </div>
                  </div>
                  <div class="col-md-1">
                      <label for="lathato">Látható:</label>
                      <input type="checkbox" class="form-control" <?=($this->news && $this->news['lathato'] == '1' ? 'checked="checked"' : '')?> id="lathato" name="lathato" />
                  </div>
                </div>
                <br>
                <div class="row">
                  <div class="col-md-2">
                      <label for="sorrend">Sorrend:</label>
                      <input type="number" class="form-control" value="<?=($this->news)?$this->news['sorrend']:'100'?>" id="sorrend" name="sorrend" />
                  </div>
									<?php if ($this->gets[2] == 'szerkeszt'): ?>
									<div class="col-md-4">
										<label for="">Elsődleges kategória</label>
										<select class="form-control" name="default_cat">
											<option value="" selected="selected">- Nincs kiválasztva -</option>
											<?php if ( $this->categories ):  ?>
						            <?php while( $this->categories->walk() ):
						            $cat = $this->categories->the_cat();  if(!in_array($cat['ID'], $catids)) continue; ?>
												<option value="<?=$cat['ID']?>" <?=($this->news && $this->news['default_cat'] == $cat['ID'])?'selected="selected"':''?>><?=$cat['neve']?></option>
						            <?php endwhile; ?>
						          <?php endif; ?>
										</select>
									</div>
									<?php endif; ?>
									<div class="col-md-6">
										<label for="datepub">Esemény dátuma</label>
										<div class="row" style="margin-left: -15px; margin-right: -15px;">
											<div class="col-md-3">
												<input type="number" class="form-control" placeholder="Év" name="datepub_year" value="<?=($this->news)?$this->news['datepub_year']:date('Y')?>">
											</div>
											<div class="col-md-3">
												<input type="number" class="form-control" placeholder="Hó" min="1" max="12" name="datepub_month" value="<?=($this->news)?$this->news['datepub_month']:date('m')?>">
											</div>
											<div class="col-md-3">
												<input type="number" class="form-control" placeholder="Nap" min="1" max="31" name="datepub_day" value="<?=($this->news)?$this->news['datepub_day']:date('d')?>">
											</div>
											<div class="col-md-3">
												<input type="time" class="form-control" placeholder="Idő"  name="datepub_time" value="<?=($this->news)?$this->news['datepub_time']:'00:00'?>">
											</div>
										</div>
									</div>
              </div>
              <br />
              <div class="row">
                <div class="col-md-12">
                    <label for="szoveg">Galéria leírás</label>
                    <div style="background:#fff;"><textarea name="description" id="description" class="form-control"><?=($this->news ? $this->news['description'] : '')?></textarea></div>
                  </div>
              </div>
              <br />
              <div class="row">
                <div class="col-md-12">
                    <label for="ujkepek">Új képek feltöltése a galériába</label>
                    <input id="ujkepek" type="file" name="images[]" value="" multiple="multiple">
                  </div>
              </div>
              <br />
              <div class="row">
                <div class="col-md-12">
                  <label for="">Galéria képei</label>
									<?php if ($this->news['images']): ?>
										<div class="image-set sortable">
											<?php $ix = 0; foreach ((array)$this->news['images'] as $img): $ix++; ?>
												<div class="image">
													<div class="wrapper">
														<div class="img">
															<input type="hidden" name="images[<?=$ix?>][0]" value="">
															<input type="hidden" name="images[<?=$ix?>][1]" value="">
															<input type="hidden" name="images[<?=$ix?>][2]" value="<?=$img[2]?>">
															<img src="/src/uploads/<?=$img[2]?>" alt="">
														</div>
														<div class="hndler">
															<div class="">
																<input type="checkbox" id="im<?=md5($img[2])?>" name="image_delete[<?=$ix?>]" value="<?=$img[2]?>"> <label for="im<?=md5($img[2])?>">törlés</label>
															</div>
															<div class="">
																<input type="radio"  id="imr<?=md5($img[2])?>" <?=($img[2] == $this->news['belyeg_kep'])?'checked="checked"':''?> name="image_belyegkep" value="<?=$img[2]?>"> <label for="imr<?=md5($img[2])?>">bélyegkép</label>
															</div>
														</div>
													</div>
												</div>
											<?php endforeach; ?>
										</div>
									<?php else: ?>
										<div class="no-data">
											Nincsenek feltöltve képek ebbe a galériába!
										</div>
									<?php endif; ?>
                </div>
              </div>
              <br />
              <div class="row floating-buttons">
                <div class="col-md-12 right">
                  <? if($this->gets[2] == 'szerkeszt'): ?>
                    <input type="hidden" name="id" value="<?=$this->gets[2]?>" />
                    <a href="/<?=$this->gets[0]?>"><button type="button" class="btn btn-danger btn-3x"><i class="fa fa-arrow-circle-left"></i> bezár</button></a>
                    <button name="save" class="btn btn-success">Változások mentése <i class="fa fa-check-square"></i></button>
                    <? else: ?>
                    <button name="add" class="btn btn-primary">Hozzáadás <i class="fa fa-check-square"></i></button>
                  <? endif; ?>
                </div>
              </div>
            </div>
            </div>
        </div>
    </div>
  </div>
</form>
<? endif; ?>
<script>
    $(function(){
			bindContentHandler();

      $('#menu_type').change(function(){
          var stype = $(this).val();
          $('.type-row').hide();
          $('.type_'+stype).show();
          $('.submit-row').show();
      });

			$('.sortable').sortable({});

      $('#remove_url_img').click( function (){
          $('#url_img').find('img').attr('src','').hide();
          $('#belyegkep').val('');
          $(this).hide();
      });

      $('#remove_option_logo').click( function (){
          $('#url_option_logo').find('img').attr('src','').hide();
          $('#option_logo').val('');
          $(this).hide();
      });

      $('#remove_option_firstimage').click( function (){
          $('#url_option_firstimage').find('img').attr('src','').hide();
          $('#option_firstimage').val('');
          $(this).hide();
      });

			$('.cont-binder').click(function(){
				bindContentHandler();
			});

				$('.link-set').sortable();
    })

		function addmoredownload() {
			var ix = $('.link-set .link').length;
			var e = '<div class="link">'+
				'<div class="row-neg">'+
					'<div class="row">'+
						'<div class="col-md-5"><i class="fa fa-arrows-v"></i>'+
							'<input type="text" placeholder="Letöltés elnevezése" name="downloads[name][]" value="" class="form-control">'+
						'</div>'+
						'<div class="col-md-7">'+
							'<input type="file" name="downloads[file][]" value="" class="form-control">'+
						'</div>'+
					'</div>'+
				'</div>'+
			'</div>';

			$('.link-set').append( e );
		}

		function bindContentHandler() {
			var selected = [];
			jQuery.each($('input[type=checkbox].cont-binder:checked'), function(i,v) {
				var val = $(v).data('cont-value');
				selected.push(val);
			});

			jQuery.each($('.cont-option'), function(i,e){
				$(e).removeClass('active');
				var keys = $(e).data('cont-option').split(",");
				jQuery.each(keys, function(ii,ee){
					var p = selected.indexOf(ee);
					if ( p !== -1 ) {
						$(e).addClass('active');
					}
				});
			});

		}

    function responsive_filemanager_callback(field_id){
        var imgurl = $('#'+field_id).val();
        $('#url_'+field_id).find('img').attr('src',imgurl).show();
        $('#remove_'+field_id).show();
    }
</script>
