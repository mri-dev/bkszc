<div style="float:right;">
	<a href="/dolgozok/" class="btn btn-default"><i class="fa fa-th"></i> dolgozók listája</a>
</div>
<h1>Dolgozók</h1>
<?=$this->msg?>
<? if($this->gets[2] != 'torles'): ?>
<form action="/dolgozok/creator/<?=$this->gets[2]?>/<?=$this->gets[3]?>" method="post" enctype="multipart/form-data">
  <div class="row-neg">
    <div class="row">
    	<div class="col-md-12">
        	<div class="con <?=($this->gets[2] == 'szerkeszt')?'con-edit':''?>">
            <h2><? if($this->gets[2] == 'szerkeszt'): ?>Cikk szerkesztése<? else: ?>Új cikk hozzáadása<? endif; ?></h2>
            <div class="row-neg">
              <div class="row">
                  <div class="col-md-8">
                    <label for="nev">Dolgozó neve *</label>
                    <input type="text"class="form-control" name="nev" id="nev" value="<?=($this->item ? $this->item->getName() : '')?>">
                  </div>
                  <div class="col-md-3">
                    <label for="osztaly">Osztályfőnök:</label>
                    <input type="text" class="form-control" value="<?=($this->item)?$this->item->getOsztaly():''?>" id="osztaly" name="osztaly" />
                  </div>
                  <div class="col-md-1">
                    <label for="lathato">Látható:</label>
                    <input type="checkbox" class="form-control" <?=($this->item && $this->item->getVisibility() ? 'checked="checked"' : '')?> id="lathato" name="lathato" />
                  </div>
                </div>
                <br />
                <div class="row">  
                  <div class="col-md-3">
                    <label for="telefon">Kapcsolat telefonszám:</label>
                    <input type="text" class="form-control" value="<?=($this->item)?$this->item->getValue('telefon'):''?>" id="telefon" name="telefon" />
                  </div>                  
                  <div class="col-md-3">
                    <label for="email">Kapcsolat e-mail cím:</label>
                    <input type="text" class="form-control" value="<?=($this->item)?$this->item->getValue('email'):''?>" id="email" name="email" />
                  </div>
                  <div class="col-md-2">
                    <label for="belyegkep">Bélyegkép <?=\PortalManager\Formater::tooltip('Ajánlott kép paraméterek:<br>Dimenzió: 1400 x * pixel <br>Fájlméret: max. 1 MB <br><br>A túl nagy fájlméretű képek lassítják a betöltés idejét és a facebook sem tudja időben letölteni, így megosztáskor kép nélkül jelenhet meg a megosztott bejegyzés az idővonalon.');?></label>
                    <div style="display:block;">
                        <a title="Kép kiválasztása" href="<?=FILE_BROWSER_IMAGE?>&field_id=belyegkep" data-fancybox-type="iframe" class="btn btn-sm btn-default iframe-btn" type="button"><i class="fa fa-search"></i></a>
                        <input type="hidden" id="belyegkep" name="profilkep" value="<?=($this->item) ? $this->item->getImage() : ''?>">
                        <span id="url_belyegkep" class="img-selected-thumbnail"><a title="<?=($this->item && $this->item->getImage())?UPLOADS.$this->item->getImage():''?>" href="<?=($this->item) ? ADMROOT.UPLOADS.$this->item->getImage() : ''?>" class="zoom"><img src="<?=($this->item) ? ADMROOT.UPLOADS.$this->item->getImage() : ''?>" title="Kiválasztott menükép" alt=""></a></span>
                        <i class="fa fa-times" title="Kép eltávolítása" id="remove_belyegkep" style="color:red; <?=($this->item && $this->item->getImage() ? '' :'display:none;')?>"></i>
                    </div>
                  </div> 
                </div>
                <br>
                <div class="row">
                  <div class="col-md-12">
                      <label for="tantargyak">Beosztás, tantárgyak, preferencia</label>
                      <div style="background:#fff;"><textarea name="tantargyak" id="tantargyak" class="form-control"><?=($this->item ? $this->item->getPreferencia() : '')?></textarea></div>
                    </div>
                </div>
                <br />
              <div class="row floating-buttons">
                <div class="col-md-12 right">
                  <? if($this->gets[2] == 'szerkeszt'): ?>
                    <input type="hidden" name="id" value="<?=$this->gets[2]?>" />
                    <a href="<?=($this->backurl)?$this->backurl:'/'.$this->gets[0]?>"><button type="button" class="btn btn-danger btn-3x"><i class="fa fa-arrow-circle-left"></i> bezár</button></a>
                    <button name="save" class="btn btn-success">Változások mentése <i class="fa fa-check-square"></i></button>
									<? else: ?><a href="<?=($this->backurl)?$this->backurl:'/'.$this->gets[0]?>"><button type="button" class="btn btn-danger btn-3x"><i class="fa fa-arrow-circle-left"></i> mégse</button></a>
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
      $('#remove_belyegkep').click( function (){
          $('#url_belyegkep').find('img').attr('src','').hide();
          $('#belyegkep').val('');
          $(this).hide();
      });
    })

    function responsive_filemanager_callback(field_id)
    {
      var imgurl = $('#'+field_id).val();
      $('#'+field_id).val(imgurl.replace('/src/uploads/', ''));
      $('#url_'+field_id).find('img').attr('src',imgurl).show();
      $('#remove_'+field_id).show();
    }
</script>
