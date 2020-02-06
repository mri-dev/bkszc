<? require "head.php"; ?>
<h1>Alapítványi támogatás érkezett!</h1>
<strong>Tájékoztatjuk, hogy a(z) <?=$settings['page_title']?> felületén <u>Online Alapítványi Támogatás</u> érkezett.</strong>

<h3>A Támogató adatai:</h3>
<table class="if">
  <tr>
    <th><strong>Támogatás rendszeressége:</strong></th>
    <td><?=$adomany_tipus?></td>
  </tr>
  <tr>
    <th><strong>Támogatás összege:</strong></th>
    <td><?=\Helper::cashFormat($tamogatas)?> Ft</td>
  </tr>
  <tr>
    <th><strong>Tranzakció saját egyedi azonosító:</strong></th>
    <td><?=$hashkey?></td>
  </tr>
  <tr>
    <th><strong>Jogalany:</strong></th>
    <td><?=$adomanyozo_forma?></td>
  </tr>
  <tr>
    <th><strong>Név / Cégnév:</strong></th>
    <td><?=$name?></td>
  </tr>
  <tr>
    <th><strong>Telefonszáma:</strong></th>
    <td><?=$phone?></td>
  </tr>
  <tr>
    <th><strong>E-mail címe:</strong></th>
    <td><?=$email?></td>
  </tr>

  <tr>
    <th><strong>Megye:</strong></th>
    <td><?=$cim_megye?></td>
  </tr>

  <tr>
    <th><strong>Irányítószám:</strong></th>
    <td><?=$cim_irsz?></td>
  </tr>

  <tr>
    <th><strong>Város:</strong></th>
    <td><?=$cim_varos?></td>
  </tr>

  <tr>
    <th><strong>Cím:</strong></th>
    <td><?=$cim_uhsz?></td>
  </tr>

  <tr>
    <th><strong>Igazolás a támogatásról:</strong></th>
    <td><?=$igazolas?></td>
  </tr>
</table>
<p>A támogatás tranzakció <strong><?=NOW?></strong> időponttal lett elindítva a rendszerben.</p>

<p style="color: green;">Figyelem! Jelen levelet a rendszer a támogatás sikeres kifizetése végeztével küldte.</p>

<? require "footer.php"; ?>
