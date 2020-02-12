<?php
$nav = array();

if (isset($_GET['cat'])) {
  $galleries = $this->folders[$this->cat];
  $title = $galleries['neve'];
  $nav[] = array(
    'url' => '/galeria/folders/kategoriak/'.$this->cat,
    'title' => 'Galériák itt: <strong>'.$title.'</strong>'
  );
}
if (isset($_GET['root'])) {
  $title = 'Galéria kategóriák';
}
if (isset($_GET['folder'])) {
  $title = $this->gallery['title'];
  if ($this->gallery['default_cat']) {
    $nav[] = array(
      'url' => $this->gallery['default_cat']['url'],
      'title' => $this->gallery['default_cat']['neve']
    );
  }

  $nav[] = array(
    'url' => '/galeria/folder/'.$this->gallery['slug'],
    'title' => 'Galéria: <strong>'.$title.'</strong>'
  );
}
?>
<div class="articles-header">
  <h1><?=$title?></h1>
  <div class="navi">
    <ul class="cat-nav">
      <li><a href="/"><i class="fa fa-home"></i></a></li>
      <li><a href="/galeria">Galériák</a></li>
      <?php foreach ((array)$nav as $n): ?>
      <li><a href="<?=$n['url']?>"><?=$n['title']?></a></li>
      <?php endforeach; ?>
    </ul>
  </div>
</div>
<div class="news-page galleries">
  <?php if (isset($_GET['root'])): ?>
    <div class="folder-list">
      <?php foreach ((array)$this->folders as $folder): ?>
      <div class="folder">
        <div class="wrapper">
          <div class="title">
            <h3><a href="/galeria/folders/kategoriak/<?=$folder['slug']?>"><?=$folder['neve']?></a></h3>
            <div class="glrycnt">
              <?=count($folder['items'])?> db galéria
            </div>
          </div>
          <div class="list">
            <ul>
              <?php foreach ((array)$folder['items'] as $i): ?>
              <li><a href="/galeria/folder/<?=$i['slug']?>"><?=$i['title']?></a> <span class="datetime">&mdash; <?php echo date('Y/m/d', strtotime($i['idopont'])); ?></span></li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
  <?php
  // kategória galéria listázás
  if (isset($_GET['cat'])): ?>
    <?php $galleries = $this->folders[$this->cat];?>

    <?php if ($galleries['items']): ?>
      <div class="folder-list">
        <?php foreach ((array)$galleries['items'] as $folder): ?>
        <div class="folder">
          <div class="wrapper">
            <div class="title">
              <h3><a href="/galeria/folder/<?=$folder['slug']?>"><?=$folder['title']?></a></h3>
              <div class="glrycnt">
                <?=count($folder['images'])?> db fénykép
              </div>
            </div>
            <?php if ($folder['belyeg_kep'] != ''): ?>
            <div class="belyegkep">
              <a href="/galeria/folder/<?=$folder['slug']?>"><img src="<?=UPLOADS.$folder['belyeg_kep']?>" alt="<?=$folder['title']?>"></a>
            </div>
            <?php endif; ?>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="no-data">
        Nincsenek elérhető galériák.
      </div>
    <?php endif; ?>
  <?php endif; ?>
  <?php
  // galéria megjelenítés
  if (isset($_GET['folder']) && $this->gallery): ?>
  <div class="news-content">
    <div class="uploaddate">
      Esemény ideje: <strong><?=date('Y. m. d.', strtotime($this->gallery['idopont']))?></strong>
    </div>
    <div class="content">
      <?=$this->gallery['description']?>
    </div>
    <?php if ($this->gallery['images']): ?>
      <div class="image-list">
        <?php foreach ((array)$this->gallery['images'] as $img): ?>
        <div class="image">
          <div class="wrapper autocorrett-height-by-width" data-image-ratio="4:3">
            <a href="<?=UPLOADS.$img[2]?>" caption="<?=$this->gallery['neve']?>" rel="gallery" class="zoom"><img src="/render/thumbnail?i=<?=$img[2]?>&w=200" alt="<?=$this->gallery['neve']?>"></a>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
  <pre><?php //print_r($this->gallery); ?></pre>
  <?php endif; ?>
</div>
