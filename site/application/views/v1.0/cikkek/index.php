<div class="articles-header">
	<h1><?=$this->head_img_title?></h1> 
	<?php if ($this->cat_parent_row): ?>
	<div class="navi">
		<ul class="cat-nav">
			<li><a href="/"><i class="fa fa-home"></i></a></li>
			<?php if (!$is_tematic): ?>
			<li><a href="/cikkek">Cikkek</a></li>
			<?php endif; ?>
			<?php foreach ( (array)$this->cat_parent_row as $cat ):?>
			<li><a href="<?=($is_tematic)?'/':'/cikkek/kategoriak/'?><?=$cat['slug']?>"><?=$cat['neve']?></a></li>
			<?php endforeach; ?>
		</ul>
	</div>
	<?php endif; ?>
</div>
<div class="news-page<?=($this->is_archiv)?' archive-list':''?>">
		<? if( $this->news ):
			$arg = $this->news->getFullData();
			$arg['date_format'] = $this->settings['date_format'];
			$arg['categories'] = $this->news->getCategories();
			$arg['newscats'] = $this->newscats;
			$arg['is_tematic'] = (in_array($arg['categories']['list'][0]['slug'], $this->news->tematic_cikk_slugs)) ? true : false;
		?>
		<? echo $this->template->get( 'hir-olvas',  $arg ); ?>
		<? else: ?>
		<div class="news-list">
			<?php if (isset($_GET['src']) && !empty($_GET['src'])): ?>
				<div class="search-for">
				 <i class="fa fa-search"></i> Keresés, mint: <?php foreach (explode(" ", $_GET['src']) as $src): ?><span><?=$src?></span><?php endforeach; ?>
				</div>
			<?php endif; ?>
			<div class="news-block">
				<div class="holder">
					<?php if ( $this->list->tree_items > 0 ): ?>
						<div class="news-group articles<?=($this->current_page <= 1)?' pageone':''?>">
						<?php
						$step = 0;
						while ( $this->list->walk() ) {
							$step++;
							$arg = $this->list->the_news(); 
							$arg['categories'] = $this->list->getCategories();
							$arg['date_format'] = $this->settings['date_format'];
							$arg['newscats'] = $this->newscats;
				      $read_prefix = (isset($_GET['cat']) && $_GET['cat'] != '') ? $_GET['cat'] : 'olvas';
							$arg['url'] = $this->list->getUrl($read_prefix, true);

							if ($this->current_page <= 1 && $step == 1) {
								echo $this->template->get( 'hir-newest', $arg );
							} else {
								echo $this->template->get( 'hir', $arg );
							}
						}
						?>
						</div>
					<?php else: ?>
						<div class="no-news">
							<h3>Nincsenek cikkek.</h3>
							A keresési feltételek alapján nem találtunk bejegyzéseket.
						</div>
					<?php endif; ?>
				</div>
				<?=($this->list->tree_items > 0)?$this->navigator:''?>

				<?php if ( $_GET['cat'] == 'nemzeti-gundel-alapitvany' ): ?>
					<a name="tamogatas"></a>
					<? $this->render('templates/tamogatas'); ?>
				<?php endif; ?>

			</div>
		</div>
		<? endif; ?>

</div>
