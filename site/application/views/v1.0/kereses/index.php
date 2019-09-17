<div class="search page-width">
  <div class="header">
    <div class="titleblock">
      <h1>Archívum - Kereső</h1>
    </div>
    <div class="clr"></div>
    <form class="" action="/search" method="get">
      <div class="search-form">
        <div class="wrapper">
          <div class="text">
            <div class="w">
              <label for="src_text">Keresési kifejezés</label>
              <input id="src_text" type="text" name="src" class="form-control srcfilter" value="<?=$_GET['src']?>">
            </div>
          </div>
          <div class="cats">
            <div class="w">
              <label for="src_cat">Kategóriákban<? if($_GET['cats'][0] != ''): ?> <span class="ftext">(<?=count($_GET['cats'])?> kiválasztva)</span><? endif;?></label>
              <select id="src_cat" class="form-control" name="cats[]" multiple="multiple">
                <option value="" <?=($_GET['cats'][0] == '')?'selected="selected"':''?>>Az összes kategóriában</option>
                <option value="" disabled="disabled"></option>
                <?php if ( $this->categories ): ?>
                  <?php while( $this->categories->walk() ):
                  $cat = $this->categories->the_cat(); ?>
                  <option class="deep<?=$cat['deep']?>" <?=(in_array($cat['ID'], (array)$_GET['cats']))?'selected="selected"':''?> value="<?=$cat['ID']?>"><?=$cat['neve']?></option>
                  <?php endwhile; ?>
                <?php endif; ?>
              </select>
            </div>
          </div>
          <div class="format">
            <div class="w">
              <label for="src_group">Témakör / Csoport</label>
              <select id="src_group" class="form-control" name="group">
                <option value="article">Cikkek / Bejegyzések</option>
              </select>
            </div>
          </div>
          <div class="sub">
            <div class="w">
              <button type="submit" class="btn btn-primary">Keresés <i class="fa fa-search"></i></button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
  <div class="results">
    <h2>Keresés eredménye a következőre: <strong>„<?=$_GET['src']?>”</strong>  </h2>
  </div>
</div>
