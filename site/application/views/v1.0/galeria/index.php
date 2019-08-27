<div class="news-page galleries">
  <?php if (isset($_GET['root'])): ?>
    <div class="folder-list">
      <?php foreach ((array)$this->folders as $folder): ?>
      <div class="folder">
        <div class="wrapper">
          <div class="title">
            <h3><a href="/galeria/folders/kategoriak/<?=$folder['slug']?>"><?=$folder['neve']?></a></h3>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
  <?php
  // kategória galéria listázás
  if (isset($_GET['cat'])): ?>

  <?php endif; ?>
  <?php
  // galéria megjelenítés
  if (isset($_GET['folder'])): ?>

  <?php endif; ?>
  <pre><?php //print_r($this->folders); ?></pre>
</div>
