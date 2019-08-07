<div class="sidebar-holder">
  <div class="sidebar-block">
    <h3>Legutóbbi bejegyzések</h3>
  </div>
  <div class="sidebar-block">
    <h3>Kategóriák</h3>
    <div class="list">
      <div class="cat <?=($_GET['cat'] == '')?'active':''?>">
        <a href="<?=$this->cikkroot?>"><span class="dot" style="color:black;"></span> Összes bejegyzés</a>
      </div>
      <?php foreach ( (array)$this->newscats as $nc ): if($this->is_archiv && in_array($nc['slug'], (array)$this->history->tematic_cikk_slugs)) continue; ?>
      <div class="cat deep<?=$nc['deep']?> <?=($_GET['cat'] == ($nc['slug']))?'active':''?>">
        <a href="<?=(in_array($nc['slug'], (array)$this->history->tematic_cikk_slugs))?'/':$this->cikkroot?><?=($nc['slug'])?><?=(isset($_GET['src']))?'?src='.$_GET['src']:''?>"><span class="dot" style="color:<?=$nc['bgcolor']?>;"></span> <?=$nc['neve']?> <span class="badge"><?=$nc['postc']?></span></a>
      </div>
      <?php if (!empty($nc['children'])): ?>
        <?php foreach ($nc['children'] as $nc): ?>
        <div class="cat deep<?=$nc['deep']?> <?=($_GET['cat'] == ($nc['slug']))?'active':''?>">
          <a href="<?=(in_array($nc['slug'], (array)$this->history->tematic_cikk_slugs))?'/':$this->cikkroot?><?=($nc['slug'])?><?=(isset($_GET['src']))?'?src='.$_GET['src']:''?>"><span class="dot" style="color:<?=$nc['bgcolor']?>;"></span> <?=$nc['neve']?> <span class="badge"><?=$nc['postc']?></span></a>
        </div>
        <?php endforeach; ?>
      <?php endif; ?>
      <?php endforeach; ?>
    </div>
  </div>
  <div class="sidebar-block">
    <h3>Archívum</h3>
    <?php if (true): ?>
    <div class="list">
      <div class="cat <?=($_GET['date'] == '')?'active':''?>">
        <a href="<?=$this->cikkroot?>">Összes</a>
      </div>
      <?php foreach ((array)$this->archive_dates as $nc): ?>
      <div class="cat <?=($_GET['date'] == ($nc['date']))?'active':''?>">
        <a href="<?=$this->cikkroot.'date/'.$nc['date'].'/1'?>"><?=$nc['datef']?> <span class="badge"><?=$nc['posts']?></span></a>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</div>
