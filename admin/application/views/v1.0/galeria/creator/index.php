<div style="float:right;">
	<a href="/cikkek/kategoriak" class="btn btn-default"><i class="fa fa-bars"></i> kategóriák</a>
	<a href="/galéria/" class="btn btn-default"><i class="fa fa-th"></i> galériák</a>
</div>
<h1>Galéria</h1>
<?=$this->msg?>
<? if($this->gets[2] == 'torles'): ?>
<form action="" method="post">
<input type="hidden" name="delId" value="<?=$this->gets[2]?>" />
<div class="row np">
	<div class="col-md-12">
    	<div class="con con-del">
            <h2>Galéria törlése</h2>
            Biztos, hogy törli a kiválasztott galériát?
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
                  <div class="col-md-4">
                      <label for="eleres">Elérési kulcs: <?=\PortalManager\Formater::tooltip('Hagyja üresen, hogy a rendszer automatikusan generáljon elérési kulcsot. <br><br>Kérjük ne használjon ékezeteket, speciális karaktereket és üres szóközöket.<br> Példa a helyes használathoz: ez_az_elso_bejegyzesem');?></label>
                      <div class="input-group">
                        <span class="input-group-addon">
                          <i class="fa fa-home" title="<?=HOMEDOMAIN?>galeria/folder/"></i>
                          </span>
                        <input type="text" class="form-control" placeholder="valami_szoveg" name="eleres" id="eleres" value="<?=($this->news ? $this->news['slug'] : '')?>">
                      </div>
                  </div>
                  <div class="col-md-1">
                      <label for="sorrend">Sorrend:</label>
                      <input type="number" class="form-control" value="<?=($this->news)?$this->news['sorrend']:'100'?>" id="sorrend" name="sorrend" />
                  </div>
                  <div class="col-md-1">
                      <label for="lathato">Látható:</label>
                      <input type="checkbox" class="form-control" <?=($this->news && $this->news['lathato'] == '1' ? 'checked="checked"' : '')?> id="lathato" name="lathato" />
                  </div>
                </div>
                <br>
                <div class="row">
                   <div class="col-md-2">
                      <label for="belyegkep">Bélyegkép <?=\PortalManager\Formater::tooltip('Ajánlott kép paraméterek:<br>Dimenzió: 1400 x * pixel <br>Fájlméret: max. 1 MB <br><br>A túl nagy fájlméretű képek lassítják a betöltés idejét és a facebook sem tudja időben letölteni, így megosztáskor kép nélkül jelenhet meg a megosztott bejegyzés az idővonalon.');?></label>
                      <div style="display:block;">
                          <input type="text" id="belyegkep" name="belyegkep" value="<?=($this->news) ? $this->news['belyeg_kep'] : ''?>" style="display:none;">
                          <a title="Kép kiválasztása" href="<?=FILE_BROWSER_IMAGE?>&field_id=belyegkep" data-fancybox-type="iframe" class="btn btn-sm btn-default iframe-btn" type="button"><i class="fa fa-search"></i></a>
                          <span id="url_belyegkep" class="img-selected-thumbnail"><a href="<?=($this->news) ? $this->news['belyeg_kep'] : ''?>" class="zoom"><img src="<?=($this->news) ? $this->news['belyeg_kep'] : ''?>" title="Kiválasztott menükép" alt=""></a></span>
                          <i class="fa fa-times" title="Kép eltávolítása" id="remove_belyegkep" style="color:red; <?=($this->news && $this->news['belyeg_kep'] ? '' :'display:none;')?>"></i>
                      </div>
                  </div>
									<?php if ($this->gets[2] == 'szerkeszt'): ?>
									<div class="col-md-5">
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
              </div>
              <br />
              <div class="row">
                <div class="col-md-12">
                    <label for="szoveg">Galéria leírás</label>
                    <div style="background:#fff;"><textarea name="description" id="description" class="form-control"><?=($this->news ? $this->news['description'] : '')?></textarea></div>
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
