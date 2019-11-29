<form class="" action="" method="post">
	<div style="float:right;">
		<a href="/cikkek/kategoriak" class="btn btn-default"><i class="fa fa-bars"></i> cikk kategóriák</a>
		<a href="/cikkek/creator?b=1" class="btn btn-primary"><i class="fa fa-plus"></i> új cikk</a>
	</div>
</form>
<h1><?=(isset($_COOKIE['showarchive']))?'Archivált cikkek':'Cikkek'?> <?php if ($_COOKIE[filtered] == '1'): ?><span style="color: red;">Szűrt lista</span><? endif; ?></h1>
<? if( true ): ?>
<?=$this->navigator?>
<br>
<?php echo $this->msg; ?>
<form id="filters" action="" method="post">
	<input type="hidden" name="filters" value="1">
	<div class="filter-inputs">
		<div class="orders">
			<div class="input-group">
				<div class="input-group-addon">Rendezés</div>
				<select class="form-control" name="order" onchange="$('#filters').submit();">
					<option value="" <?=(!isset($_COOKIE['filter_order']))?'selected="selected"':''?>>Legújabbak elől</option>
					<option value="cim-ASC" <?=($_COOKIE['filter_order'] == 'cim-ASC')?'selected="selected"':''?>>Cím szerint - A->Z</option>
					<option value="cim-DESC" <?=($_COOKIE['filter_order'] == 'cim-DESC')?'selected="selected"':''?>>Cím szerint - Z->A</option>
					<option value="letrehozva-DESC" <?=($_COOKIE['filter_order'] == 'letrehozva-DESC')?'selected="selected"':''?>>Publikálás szerint - Újak elől</option>
					<option value="letrehozva-ASC" <?=($_COOKIE['filter_order'] == 'letrehozva-ASC')?'selected="selected"':''?>>Publikálás szerint -Régiek elől</option>
					<option value="idopont-DESC" <?=($_COOKIE['filter_order'] == 'idopont-DESC')?'selected="selected"':''?>>Utoljára módosítva - Legutóbb</option>
					<option value="idopont-ASC" <?=($_COOKIE['filter_order'] == 'idopont-ASC')?'selected="selected"':''?>>Utoljára módosítva - Legrégebben</option>
					<option value="lathato-DESC" <?=($_COOKIE['filter_order'] == 'lathato-DESC')?'selected="selected"':''?>>Láthatóság szerint - Láthatóak elől</option>
					<option value="lathato-ASC" <?=($_COOKIE['filter_order'] == 'lathato-ASC')?'selected="selected"':''?>>Láthatóság szerint - Rejtettek elől</option>
				</select>
			</div>
		</div>
	</div>
</form>
<div class="row">
	<div class="col-md-12">
    	<div class="con con-row-list">
          <div class="row row-header">
          		<div class="col-md-5">
              	Cím
              </div>
							<div class="col-md-2 center">
            		Kategóriák
              </div>
              <div class="col-md-1 center">
            		Utoljára frissítve
              </div>
              <div class="col-md-1 center">
                Publikálva
              </div>
              <div class="col-md-1 center">
              	Sorrend
              </div>
              <div class="col-md-1 center">
              	Látható
              </div>
              <div class="col-md-1" align="right"></div>
         	</div>
					<div class="row row-filter <? if($_COOKIE['filtered'] == '1'): ?>filtered<? endif;?>">
						<form class="" action="" method="post">
          		<div class="col-md-5">
              	<input type="text" class="form-control" name="nev" value="<?=$_COOKIE['filter_nev']?>" placeholder="Keresés...">
              </div>
							<div class="col-md-2 center">
								<select class="form-control" name="kategoria">
				        	<option value="" selected="selected"># Mind</option>
		            	<?	while( $this->categories->walk() ): $cat = $this->categories->the_cat(); ?>
		                <option value="<?=$cat['ID']?>" <?=($cat['ID'] == $_COOKIE['filter_kategoria'])?'selected':''?>><?=str_repeat('&mdash;',$cat['deep']).' '.$cat['neve']?></option>
									<? endwhile; ?>
		            </select>
              </div>
              <div class="col-md-3 center"></div>
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
            if( $this->news_list->has_news() ):
            while( $this->news_list->walk() ):
              $news = $this->news_list->the_news();
							$cats = $this->news_list->getCategories();
							$url = $this->news_list->getUrl( false, false );
            ?>
            <div class="row deep<?=$news['deep']?> markarow  <?=($this->news && $this->gets[1] == 'szerkeszt' && $this->news->getId() == $news['ID'] ? 'on-edit' : '')?> <?=($this->news && $this->gets[1] == 'torles' && $this->news->getId() == $news['ID'] ? 'on-del' : '')?>">
            	<div class="col-md-5">
                  <div class="img-thb">
										<?php if ( $news['belyeg_kep'] != '' ): ?>
											<a href="<?=ADMROOT.UPLOADS.$news['belyeg_kep']?>" class="zoom"><img src="<?=ADMROOT.UPLOADS.$news['belyeg_kep']?>" alt=""></a>
										<?php else: ?>
											<a href="<?=ADMROOT.'src/images/no-image.jpg'?>" class="zoom"><img src="<?=ADMROOT.'src/images/no-image.jpg'?>" alt="Nincs kép"></a>
										<?php endif; ?>
                  </div>
                	<strong><?=$news[cim]?></strong>
                  <div class="subline"><a target="_blank" class="url" href="<?=HOMEDOMAIN?><?=$url?>" class="news-url"><i title="<?=HOMEDOMAIN?>" class="fa fa-home"></i> <?=$url?></a></div>
                </div>
                <div class="col-md-2 center">
									<?php if (count($cats['ids']) != 0): $icat = ''; ?>
										<?php foreach ($cats['list'] as $cat): ?>
												<?php $icat .= '<span>'.$cat['neve'].'</span>, '; ?>
										<?php endforeach; $icat = rtrim($icat,', ');  ?>
										<?php echo $icat; ?>
									<?php else: ?>
										N/A
									<?php endif; ?>
                </div>
                <div class="col-md-1 center times">
                	<?=\PortalManager\Formater::dateFormat($news['idopont'], $this->settings['date_format'])?>
                </div>
                <div class="col-md-1 center times">
                    <?=\PortalManager\Formater::dateFormat($news['letrehozva'], $this->settings['date_format'])?>
                </div>
                <div class="col-md-1 center">
                	<?php echo $news[sorrend]; ?>
                </div>
                 <div class="col-md-1 center">
                	<? if($news[lathato] == '1'): ?><i style="color:green;" class="fa fa-check"></i><? else: ?><i style="color:red;" class="fa fa-times"></i><? endif; ?>
                </div>
                <div class="col-md-1 actions" align="right">
                    <a href="/<?=$this->gets[0]?>/creator/szerkeszt/<?=$news[ID]?>?b=1" title="Szerkesztés"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;
                    <a href="javascript:void(0)" onclick="deleteArticle(<?=$news[ID]?>, '<?=$news[cim]?>');" title="Törlés"><i class="fa fa-times"></i></a>
                </div>
           	</div>
            <? endwhile; else:?>
            	<div class="noItem">
                	Nincs megjeleníthető tartalom!
                </div>
            <? endif; ?>
        </div>
    </div>
</div>
<?=$this->navigator?>
<script>
    $(function(){
        $('#menu_type').change(function(){
            var stype = $(this).val();
            $('.type-row').hide();
            $('.type_'+stype).show();
            $('.submit-row').show();
        });
        $('#remove_url_img').click( function (){
            $('#url_img').find('img').attr('src','').hide();
            $('#uimg').val('');
            $(this).hide();
        });
    })

		function deleteArticle( id, title ) {
			var c = confirm('Biztos, hogy végleg törli a(z) "'+title+'" bejegyzést?');
			if (c) {
				$.post("/ajax/post", {
					type : 'deleteArticle',
					id: id
				}, function(d){
					console.log(d);
					if (!d.error) {
						window.location.assign('?msgkey=msg&msg=Bejegyzés véglegesen törölve: <strong>' + title+'</strong>');
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
