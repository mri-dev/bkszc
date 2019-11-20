<div style="float: right;">
	<a href="/atiranyitas/create" class="btn btn-primary"> <i class="fa fa-plus"></i> új átirányítás</a>
</div>
<h1>Átirányítások <span><strong><?=$this->redirectors[info][total_num]?> db átirányítás.</strong> nem létező, korábbi domain alcímek átirányítása új címre</span></h1>
<?=$this->msg?>
<table class="table termeklista table-bordered">
	<thead>
    	<tr>
			<th title="Felhasználó ID" width="40">#</th>
			<th width="100">Site</th>
			<th>Indító URL</th>
			<th>Átirányítási cél URL</th>
			<th width="40"><i class="fa fa-gears"></i></th>
        </tr>
	</thead>
    <tbody>    	
    	<? if(count($this->redirectors[data]) > 0): foreach($this->redirectors[data] as $d):  ?>
    	<tr>
	    	<td align="center"><?=$d[ID]?></td>
	    	<td align="center"><?=$d[site]?></td>
	    	<td align="left"><em style="color: #aaa;"><?=($d['site']=='shop') ? HOMEDOMAIN : $this->settings['blog_url'].'/' ?></em><strong><?=$d[watch]?></strong>  <a href="<?=($d['site']=='shop') ? HOMEDOMAIN : $this->settings['blog_url'].'/' ?><?=$d[watch]?>" target="_blank">Link <i class="fa fa-external-link"></i></a></td>
	    	<td align="left"><? if(strpos($d[redirect_to],'http://') !== 0 && strpos($d[redirect_to],'https://') !== 0): ?><em style="color: #aaa;"><?=($d['site']=='shop') ? HOMEDOMAIN : $this->settings['blog_url'].'/' ?></em><? endif; ?><strong><?=$d[redirect_to]?></strong></td>
            <td align="center">
                <div class="dropdown">               
                    <i class="fa fa-gear dropdown-toggle" title="Beállítások" id="dm<?=$d['ID']?>" data-toggle="dropdown"></i>
                      <ul class="dropdown-menu" role="menu" aria-labelledby="dm<?=$d['ID']?>">  
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="/atiranyitas/edit/<?=$d['ID']?>">Szerkesztés <i class="fa fa-pencil"></i></a></li>
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="/atiranyitas/del/<?=$d['ID']?>">Törlés <i class="fa fa-trash"></i></a></li>
                      </ul>
                </div>
            </td>
        </tr>
        <? endforeach; else: ?>
        <tr>
	    	<td colspan="15" align="center">
            	<div style="padding:25px;">Nincs találat!</div>
            </td>
        </tr>
        <? endif; ?>
    </tbody>
</table>
