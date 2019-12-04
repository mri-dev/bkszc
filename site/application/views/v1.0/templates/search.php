<form role="search" method="post" class="searchform" action="" onsubmit="searchItem($(this)); return false;">
	<div>
		<input type="text" value="<?=($this->gets[0]=='kereses')?$this->gets[1]:''?>" name="s" id="default_search"><!--
		--><button id="searchsubmit"><i class="fa fa-search"></i></button>
	</div>
</form>