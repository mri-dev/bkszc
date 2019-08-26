<div class="news-content">
	<?php if ($optional_logo): ?>
	<div class="logo">
		<img src="<?=ADMROOT?><?=$optional_logo?>" alt="<?=$cim?>">
	</div>
	<?php endif; ?>
	<div class="head">
		<h1><?=$cim?></h1>
		<div class="subline">
			<div class="backurl">
				<a href="/cikkek"><i class="fa fa-th" aria-hidden="true"></i> összes cikk</a>
				<?php if (isset($_GET['cat']) && $_GET['cat'] != '' && $_GET['cat'] != 'olvas'): ?>
					<a href="<?=($is_tematic)?'/':'/cikkek/'?><?=$_GET['cat']?>"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> <?=$newscats[$_GET['cat']]['neve']?></a>
				<?php endif; ?>
			</div>
			<div class="share">
				<div class="fb-like" data-href="<?=DOMAIN?>cikkek/<?=$eleres?>" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div>
			</div>
			<div class="date" title="Bejegyzés közzétéve"><i class="fa fa-clock-o"></i> <?=substr(\PortalManager\Formater::dateFormat($letrehozva, $date_format),0,-6)?></div>
			<div class="nav">
				<ul class="cat-nav">
					<li><a href="/"><i class="fa fa-home"></i></a></li>
					<?php if (!$is_tematic): ?>
					<li><a href="/cikkek">Cikkek</a></li>
					<?php endif; ?>
					<li>
						<?php foreach ( (array)$categories['list'] as $cat ): ?>
						<a class="cat" href="<?=($is_tematic)?'/':'/cikkek/kategoriak/'?><?=$cat['slug']?>"><?=$cat['label']?></a>
						<?php endforeach; ?>
					</li>
				</ul>
			</div>
		</div>
	</div>

	<div class="content">
		<?php if ($belyeg_kep != '' || $bevezeto != ''): ?>
		<div class="abstract">
			<?php if ($belyeg_kep): ?>
				<div class="image">
					<img src="<?php echo UPLOADS.$belyeg_kep; ?>" alt="<?=$cim?>">
				</div>
			<?php endif; ?>
			<div class="text">
				<?php if (!empty($bevezeto)): ?>
				<div class="abstext"><?php echo $bevezeto; ?></div>
				<?php endif; ?>
			</div>
		</div>
		<?php endif; ?>
		<?=\PortalManager\News::textRewrites($szoveg)?>
		<?php $linkek_list = unserialize($linkek); ?>
		<?php if ($linkek && count($linkek_list) > 0): ?>
			<div class="links">
				<ul>
					<?php foreach ((array)$linkek_list as $link ): ?>
						<li class="link">
							<a href="<?=UPLOADS?>files/<?=$link[2]?>"><strong><?=$link[1]?></strong></a> (<?=$link[4]?>)
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>

		<?php if ($forrasinfo): ?>
		<div class="source" title="Forrás">
			<i class="fa fa-link"></i> <?php echo $forrasinfo; ?>
		</div>
		<?php endif; ?>
	</div>
</div>
