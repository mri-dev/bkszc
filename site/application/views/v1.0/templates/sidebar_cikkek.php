<div class="sidebar-holder">
  <div class="sidebar-block">
    <h3>Legutóbbi bejegyzések</h3>
  </div>
  <div class="divider"></div>
  <div class="sidebar-block">
    <?php if ($this->currentcat): ?>
    <h3><strong><?=$this->currentcat['name']?></strong> alkategóriák</h3>
    <?php else: ?>
    <h3>Kategóriák</h3>
    <?php endif; ?>
    <div class="list">
      <div class="cat <?=($_GET['cat'] == '')?'active':''?>">
        <a href="<?=$this->cikkroot?>"><span class="dot" style="color:black;"></span> Összes bejegyzés</a>
      </div>
      <?php if ($this->parent_cat): ?>
        <div class="backtoparent cat">
          <a href="/cikkek/kategoriak/<?=$this->parent_cat['slug']?>">< vissza: <strong><?=$this->parent_cat['name']?></strong>  </a>
        </div>
      <?php endif; ?>

      <?php foreach ( (array)$this->newscats as $nc ): if($this->is_archiv && in_array($nc['slug'], (array)$this->history->tematic_cikk_slugs)) continue; ?>
      <div class="cat deep<?=$nc['deep']?> <?=($_GET['cat'] == ($nc['slug']))?'active':''?>">
        <a href="<?=(in_array($nc['slug'], (array)$this->history->tematic_cikk_slugs))?'/':$this->cikkroot?>kategoriak/<?=($nc['slug'])?><?=(isset($_GET['src']))?'?src='.$_GET['src']:''?>"> > <?=$nc['neve']?> <span class="cnt">(<?=$nc['postc']?>)</span></a>
      </div>
      <?php endforeach; ?>
      <?php if (empty($this->newscats)): ?>
        <div class="no-cats cat">
          Nincsenek további kategóriák.
        </div>
      <?php endif; ?>
    </div>
  </div>
  <div class="divider"></div>
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
