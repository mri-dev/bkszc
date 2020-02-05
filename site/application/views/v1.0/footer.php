		<?php if ($this->gets[0] == 'cikkek'): ?>
			<? $this->render('templates/sidebar_cikkek'); ?>
		<?php elseif($this->gets[0] == 'galeria'): ?>
			<? $this->render('templates/sidebar_gallery'); ?>
		<?php elseif($this->gets[0] == 'esemenyek'): ?>
			<? $this->render('templates/sidebar_esemenyek'); ?>
		<?php else: ?>
			<? //$this->render('templates/sidebar'); ?>
		<?php endif; ?>
	  <?php if (!$this->homepage): ?>
	  </div> <!-- .pw -->
	  <?php endif; ?>
	</div> <!-- .content-wrapper -->

	<footer>
		<div class="info">
			<div class="top">
				<div class="pw">
					<div class="flex">
						<div class="logo">
							<a href="<?=$this->settings['page_url']?>"><img src="<?=IMG?>gk_logo.svg" style="height:80px;" alt="<?=$this->settings['page_title']?>"></a>
							<div class="simple-pay-otp-badge">
								<a href="http://simplepartner.hu/PaymentService/Fizetesi_tajekoztato.pdf" target="_blank" rel="noopener noreferrer"> <img title=" SimplePay - Online bankkártyás fizetés" src="<?=SOURCE?>simplesdk/logos/simplepay_200x50.png" alt="SimplePay vásárlói tájékoztató" height="30"></a>
							</div>
						</div>
						<div class="links">
							<ul>
								<? foreach ( $this->menu_footer->tree as $menu ): ?>
									<li>
										<? if($menu['link']): ?><a href="<?=($menu['link']?:'')?>"><? endif; ?>
											<span class="item <?=$menu['css_class']?>" style="<?=$menu['css_styles']?>">
												<? if($menu['kep']): ?><img src="<?=\PortalManager\Formater::sourceImg($menu['kep'])?>"><? endif; ?>
												<?=$menu['nev']?></span>
										<? if($menu['link']): ?></a><? endif; ?>
										<? if($menu['child']): ?>
											<? foreach ( $menu['child'] as $child ) { ?>
												<div class="item <?=$child['css_class']?>">
													<?
													// Inclue
													if(strpos( $child['nev'], '=' ) === 0 ): ?>
														<? echo $this->templates->get( str_replace('=','',$child['nev']), array( 'view' => $this ) ); ?>
													<? else: ?>
													<? if($child['link']): ?><a href="<?=$child['link']?>"><? endif; ?>
													<? if($child['kep']): ?><img src="<?=\PortalManager\Formater::sourceImg($child['kep'])?>"><? endif; ?>
													<span style="<?=$child['css_styles']?>"><?=$child['nev']?></span>
													<? if($child['link']): ?></a><? endif; ?>
													<? endif; ?>
												</div>
											<? } ?>
										<? endif; ?>
									</li>
								<? endforeach; ?>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="bottom">
				<div class="pw">
					Minden jog fenntartva &nbsp; 2016 &copy; <span class="author"><?=$this->settings['page_author']?></span> &mdash; <?=$this->settings['page_description']?>
				</div>
			</div>
		</div>
	</footer>
</body>
</html>
