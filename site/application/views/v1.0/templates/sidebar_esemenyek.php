<div class="sidebar-holder">
  <div class="sidebar-block naptar-slide-block">
    <?php $this->render('templates/programnaptarslide') ?>
  </div>
  <div class="sidebar-block naptar-block">
    <?php $this->render('templates/programnaptar') ?>
  </div>
  <?php if ($this->list && $this->list->has_news()): ?>
  <div class="sidebar-block">
    <article class="newest">
      <h2><div class="cit"><i class="fa fa-clock-o style-blue"></i> Közelgő események</div></h2>
      <div class="esemenyek">
        <div class="wrapper onepline">
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
        </div>
      </div>
    </article>
  </div>
  <?php endif; ?>
</div>
