<div class="news-group">
<?php for ($i=0; $i < 4 ; $i++) { ?>
  <article class="news">
    <div class="wrapper">
      <div class="title">
        <a href="#"><strong>Ferenczy Noémi Kollégium</strong></a>
      </div>
      <div class="desc">
        Általános tájékoztató az Erasmus +
programról. Korábbi évek szakmai
gyakorlatairól információk itt és a Blogon!
      </div>
      <div class="navlinks">
        <a href="#">Bővebben ></a>
      </div>
    </div>
  </article>
  <?php if ( $i % 2 !== 0 ): ?>
  </div><div class="news-group">
  <?php endif; ?>
<?php } ?>
</div>
