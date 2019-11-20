<div style="float: right;">
	<a href="/atiranyitas" class="btn btn-default"><i class="fa fa-arrow-left"></i> vissza</a>
</div>
<h1>Új átirányítás <small>Átirányítások</small></h1>
<?=$this->msg?>
<div class="con">
	<form method="post" action="">
		<input type="hidden" name="site" value="web">
		<div class="row">
			<div class="col-sm-6">
				<label for="watch">Indító URL<sup>1</sup></label>
				<input type="text" class="form-control" name="watch" id="watch" value="<?=$_POST[watch]?>">
				pl.: regi/url
			</div>
			<div class="col-sm-4">
				<label for="redirect_to">Átirányítási cél URL<sup>2</sup></label>
				<input type="text" class="form-control" name="redirect_to" id="redirect_to" value="<?=$_POST[redirect_to]?>">
				pl.: uj/url
			</div>
			<div class="col-sm-2 right">
				<br>
				<button class="btn btn-warning" name="createRedirect" value="1">Létrehozás <i class="fa fa-arrow-circle-right"></i></button>
			</div>
		</div>
		<div class="divider" style="margin: 8px 0;"></div>
		<em>
			<div><sup>1</sup> A domain címét ne írja be csak az utána lévő elérhetőséget. Pl.: aloldal, kapcsolat, stb.</div>
			<div><sup>2</sup> Megadhat helyi URL-t és külső hivatkozást is. Külső hivatkozás esetén használjon http:// vagy https:// protokollal kezdődő URL-eket. Pl.: http://www.google.com/, http://www.tuzvedelmicentrum.hu</div>
		</em>
	</form>
</div>
