<? require "head.php"; ?>
<h1>Ingyenes visszahívás kérés érkezett!</h1>
<strong>Tájékoztatjuk, hogy a(z) <?=$settings[page_title]?> rendszerén keresztül <u><?=$targy?></u> kértek.</strong>

<h3>Az igénylő által beírt adatok:</h3>
<table class="if">
  <tr>
    <th><strong>Igénylő neve:</strong></th>
    <td><?=$name?></td>
  </tr>
  <tr>
    <th><strong>Igénylő telefonszáma:</strong></th>
    <td><?=$phone?></td>
  </tr>
  <tr>
    <th><strong>Milyen ügyben érdeklődik:</strong></th>
    <td><?=$subject?></td>
  </tr>
</table>
<p>Az igénylés <strong><?=NOW?></strong> időponttal lett rögzítve a rendszerbe.</p>

<p style="color: red;">
  Az igényre válaszolhat az adminisztrációs felületen keresztül a beérkező üzeneteknél, melyet az Üzenetek menüpont alatt talál meg:
</p>
<p><a href="<?=$settings['admin_url']?>/uzenetek"><?=$settings['admin_url']?>/uzenetek</a></p>

<? require "footer.php"; ?>
