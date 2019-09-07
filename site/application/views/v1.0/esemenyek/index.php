<div class="news-page">
  <?php if ($_GET['list'] == 1): ?>
    <?php if ($this->newest->has_news()): ?>
      <div class="esemeny">
        <article class="newest">
          <h2><div class="cit"><i class="fa fa-clock-o style-red"></i> Legújabb esemény</div><div class="fakeline"></div></h2>
          <?php while ( $this->newest->walk() ):
            $arg = $this->newest->the_news(); ?>
            <div class="wrapper">
              <?php if ( $this->newest->getImage() ): ?>
              <div class="image">
                <a href="<?php echo $this->newest->getURL(); ?>"><img src="<?=$this->newest->getImage()?>" alt="<?php echo $this->newest->getTitle(); ?>"></a>
              </div>
              <?php endif; ?>
              <div class="data">
                <div class="date"><i class="fa fa-clock-o style-blue"></i> <?php echo $this->newest->getIdopont('Y. m. d.'); ?></div>
                <div class="title"><a href="<?php echo $this->newest->getURL(); ?>"><?php echo $this->newest->getTitle(); ?></a></div>
                <div class="bevezeto"><?php echo $this->newest->getDescription(); ?></div>
                <div class="morebtn"><a href="<?php echo $this->newest->getURL(); ?>">Bővebben ></a></div>
              </div>
            </div>
          <?php endwhile; ?>
        </article>
      </div>
    <?php endif; ?>
    <article class="newest">
      <h2><div class="cit"><i class="fa fa-clock-o style-blue"></i> Közelgő események</div><div class="fakeline"></div></h2>
    </article>
    <div class="esemenyek">
      <div class="wrapper">
        <?php if ($this->list->has_news()): ?>
          <?php while ( $this->list->walk() ):
            $arg = $this->list->the_news(); ?>
            <article class="">
              <div class="wrapper">
                <div class="data">
                  <div class="title"><a href="<?php echo $this->list->getURL(); ?>"><?php echo $this->list->getTitle(); ?></a></div>
                  <div class="bevezeto"><?php echo $this->list->getDescription(); ?></div>
                </div>
                <div class="foot">
                  <div class="date"><i class="fa fa-clock-o style-blue"></i> <?php echo $this->list->getIdopont('Y. m. d.'); ?></div>
                  <div class="morebtn"><a href="<?php echo $this->list->getURL(); ?>">Bővebben ></a></div>
                </div>
              </div>
            </article>
          <?php endwhile; ?>
        <?php else: ?>
          <div class="no-data">
            Nincsenek jelenleg közelgő események!
          </div>
        <?php endif; ?>
      </div>
      <?=($this->list->tree_items > 0)?$this->navigator:''?>
    </div>
  <?php elseif($_GET['list'] == 0 && isset($_GET['cikk'])): ?>
  <div class="esemeny">
    <?php if ($this->news): ?>
      <article class="read">
        <div class="wrapper">
          <div class="title">
            <h1><?php echo $this->news->getTitle(); ?></h1>
            <div class="date"><i class="fa fa-clock-o style-blue"></i> <?php echo $this->news->getIdopont('Y. m. d.'); ?></div>
            <div class="navi">
      				<ul class="cat-nav">
      					<li><a href="/"><i class="fa fa-home"></i></a></li>
                <li><a href="/esemenyek">Események</a></li>
      				</ul>
      			</div>
          </div>
          <?php if ( $this->news->getImage() ): ?>
          <div class="image"><img src="<?=$this->news->getImage()?>" alt="<?php echo $this->news->getTitle(); ?>"></div>
          <?php endif; ?>
          <div class="bevezeto"><?php echo $this->news->getDescription(); ?></div>
          <div class="desc"><?php echo $this->news->getHtmlContent(); ?></div>
        </div>
      </article>
    <?php else: ?>
      <div class="no-data">
        Nem található az esemény!
      </div>
    <?php endif; ?>
  </div>
  <?php endif; ?>
</div>
