<div class="tamogatas-form-holder">
  <div class="header">
    <h2>Alapítványi támogatás küldése</h2>
  </div>
  <div class="cwrapper">
    <form id="tamogatasform" action="" method="post">
    <div class="group">
      <h3>1. Támogatás kiválasztása</h3>
      <div class="form-fields radio-set">
        <div class="adomany-tipus-egyszeri">
          <div class="fwrap">
            <input id="ad_type_egyszeri" checked="checked" type="radio" name="adomany_tipus" value="Egyszeri"><label for="ad_type_egyszeri">Egyszeri</label>
          </div>
        </div>
        <div class="adomany-tipus-egyszeri">
          <div class="fwrap">
            <input id="ad_type_rendszeres" type="radio" name="adomany_tipus" value="Rendszeres"><label for="ad_type_rendszeres">Rendszeres</label>
          </div>
        </div>
      </div>
      <div class="paying-cashes">
        <?php foreach (array(500, 1000, 2000, 5000, 10000, 20000) as $cash): ?>
        <div class="cash">
          <input type="radio" id="cbpay_<?=$cash?>" onclick="selectPayingValue(<?=$cash?>);" name="cbpay" value="<?=$cash?>"> <label for="cbpay_<?=$cash?>"><?=number_format($cash, 0, '',' ')?> Ft</label>
        </div>
        <?php endforeach; ?>
        <div class="cash">
          <input type="radio" id="cbpay_other" onclick="selectPayingValue(-1);" name="cbpay" value="-1"> <label for="cbpay_other">Más összeg
            <input type="number" id="othercash" name="othercash" value="" style="display:none;">
          </label>
        </div>
      </div>
    </div>
    <div class="group">
      <h3>2. A támogató adatai</h3>
      <div class="form-fields radio-set">
        <div class="adomanyozo-tipus-egyszeri">
          <div class="fwrap">
            <input id="adm_type_magan" checked="checked" type="radio" name="adomanyozo_forma" value="Magánszemély"><label for="adm_type_magan">Magánszemély</label>
          </div>
        </div>
        <div class="adomanyozo-tipus-egyszeri">
          <div class="fwrap">
            <input id="adm_type_ceg" type="radio" name="adomanyozo_forma" value="Cég/Szervezet"><label for="adm_type_ceg">Cég / Szervezet</label>
          </div>
        </div>
      </div>
      <div class="form-fields">
        <div class="name">
          <div class="fwrap">
            <label for="fc_name">Teljes név / Cégnév *</label>
            <input id="fc_name" type="text" name="name" value="">
          </div>
        </div>
        <div class="email">
          <div class="fwrap">
            <label for="fc_email">E-mail cím *</label>
            <input id="fc_email" type="text" name="email" value="">
          </div>
        </div>
        <div class="telefon">
          <div class="fwrap">
            <label for="fc_telefon">Telefonszám *</label>
            <input id="fc_telefon" type="text" name="telefon" value="">
          </div>
        </div>
      </div>
      <div class="form-fields address-line">
        <div class="cim_megye">
          <div class="fwrap">
            <label for="fc_cim_megye">Megye</label>
            <select id="fc_cim_megye" class="" name="cim_megye">
              <option value="">-- megye kiválasztása --</option>
              <option value="Bács-Kiskun">Bács-Kiskun megye</option>
              <option value="Baranya">Baranya megye</option>
              <option value="Békés">Békés megye</option>
              <option value="Borsod-Abaúj-Zemplén">Borsod-Abaúj-Zemplén megye</option>
              <option value="Budapest">Budapest</option>
              <option value="Csongrád">Csongrád megye</option>
              <option value="Fejér">Fejér megye</option>
              <option value="Győr-Moson-Sopron">Győr-Moson-Sopron megye</option>
              <option value="Hajdú-Bihar">Hajdú-Bihar megye</option>
              <option value="Heves">Heves megye</option>
              <option value="Jász-Nagykun-Szolnok">Jász-Nagykun-Szolnok megye</option>
              <option value="Komárom-Esztergom">Komárom-Esztergom megye</option>
              <option value="Nógrád">Nógrád megye</option>
              <option value="Pest">Pest megye</option>
              <option value="Somogy">Somogy megye</option>
              <option value="Szabolcs-Szatmár-Bereg">Szabolcs-Szatmár-Bereg megye</option>
              <option value="Tolna">Tolna megye</option>
              <option value="Vas">Vas megye</option>
              <option value="Veszprém">Veszprém megye</option>
              <option value="Zala">Zala megye</option>
            </select>
          </div>
        </div>
        <div class="cim_irsz">
          <div class="fwrap">
            <label for="fc_cim_irsz">Irányítószám</label>
            <input id="fc_cim_irsz" type="number" max="9999" min="1000" maxlength="4" name="cim_irsz" value="">
          </div>
        </div>
        <div class="cim_varos">
          <div class="fwrap">
            <label for="fc_cim_varos">Város</label>
            <input id="fc_cim_varos" type="text" name="cim_varos" value="">
          </div>
        </div>
        <div class="cim_cim">
          <div class="fwrap">
            <label for="fc_cim_uhsz">Cím</label>
            <input id="fc_cim_uhsz" type="text" name="cim_uhsz" value="">
          </div>
        </div>
      </div>
      <div class="form-fields">
        <div class="igazolas">
          <div class="fwrap">
            <label for="fc_igazolas">Igazolás a támogatásról *</label>
            <select id="fc_igazolas" class="" name="igazolas">
              <option value="Nem kérek igazolást" selected="selected">Nem kérek igazolást</option>
              <option value="E-mail címemre kérek elekronikus igazolást">E-mail címemre kérek elekronikus igazolást</option>
              <option value="Postai úton kérek igazolást">Postai úton kérek igazolást</option>
            </select>
          </div>
        </div>
      </div>
    </div>
    </form>
    <div class="group">
      <h3>3. ÁSZF elfogadása és feliratkozás</h3>
      <div class="checkboxes">
        <div class="aszf">
          <input type="checkbox" id="check_aszf" name="check_aszf" value="1"> <label for="check_aszf">* Elolvastam és elfogadom a <a target="_blank" href="/aszf">Általános Szerződési Feltételeket</a>, különösen az <a target="_blank" href="/aszf">Adattovábbítási Nyilatkozatot</a>!</label>
        </div>
        <?php if (false): ?>
          <div class="aszf">
            <input type="checkbox" id="check_hirlevel" name="check_hirlevel" value="1"> <label for="check_hirlevel">Feliratkozom a hírlevélre!</label>
          </div>
        <?php endif; ?>
      </div>
    </div>
    <div class="group">
      <h3>4. Fizetési mód kiválasztása</h3>
      <div class="paying-cashes">
        <div class="">
          <input type="radio" id="cbpaymode_otpsimple" onclick="selectPayingMode('OTPSimple');" name="cbpaymode" value="OTPSimple"> <label for="cbpaymode_otpsimple">OTP Simple</label>
        </div>
        <?php if (false): ?>
          <div class="">
            <input type="radio" id="cbpaymode_atutalas" onclick="selectPayingMode('Atutalas');" name="cbpaymode" value="Atutalas"> <label for="cbpaymode_atutalas">Átutalás</label>
          </div>
        <?php endif; ?>
      </div>
      <div id="payingmsg" class="payingbackmsg"></div>
      <div class="paying" style="display:none;" id="payingbuttons_OTPSimple">
        <div class="text">
          <div class="t">
            A gomb megnyomásával elindítja az OTP Simplepay fizetési folyamatot. A rendszer átirányítja a szolgáltató fizető oldalára.
            <br>
            <a href="https://simplepay.hu/index.php/vasarlo-aff" target="_blank">Az OTP Simplepay felhasználói ÁSZF ></a>
            <br>
            <a href="http://simplepartner.hu/PaymentService/Fizetesi_tajekoztato.pdf" target="_blank"> <img height="30" src="<?=SOURCE?>simplesdk/logos/simplepay_bankcard_logos_left_482x40.png" title=" SimplePay - Online bankkártyás fizetés" alt=" SimplePay vásárlói tájékoztató"></a>
          </div>
        </div>
        <div class="buttons">
          <div id="payingforms_OTPSimple"></div>
        </div>
      </div>
      <div class="paying" style="display:none;" id="payingbuttons_Atutalas">
        <div class="text">
          <div class="t" style="border-left: 5px solid #009b7b; padding: 0 10px;">
            <strong>Köszönjük érdeklődését! Az adományát a következő számlaszámra tudja átutalással kifizetni:</strong> <br><br>
            <table>
              <tr>
                <td>Kedvezményezett</td>
                <td><strong>Nemzeti Gundel Alapítvány</strong></td>
              </tr>
              <tr>
                <td>Számlaszám</td>
                <td><strong>11786001-20036335</strong></td>
              </tr>
              <tr>
                <td>Bank</td>
                <td><strong>OTP Bank Nyrt.</strong></td>
              </tr>
              <tr>
                <td>SWIFT</td>
                <td><strong>OTPVHUHB</strong></td>
              </tr>
            </table>
            <h4>Átutalás egyéb adatai:</h4>
            <div id="payingforms_Atutalas"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript">
    function selectPayingValue( value ) {
      if (value == -1) {
        jQuery("#othercash").show().focus();
      } else {
        jQuery("#othercash").val('').hide();
      }
    }
    function selectPayingMode( mode ) {
      jQuery('#payingbuttons_'+mode).hide(0);
      jQuery("#payingmsg").html('');
      if (mode != '') {
        jQuery.post(
          "/ajax/post/", {
            type: 'tamogatas_form',
            mode: mode,
            form: jQuery("#tamogatasform").serialize(),
            check_aszf: jQuery(".checkboxes input[name='check_aszf']").is(':checked'),
            check_hirlevel: jQuery(".checkboxes input[name='check_aszf']").is(':checked')
          }, function( data ) {
            console.log(data);
            if (data.error == 0) {
              if (data.button) {
                jQuery("#payingforms_"+mode).html(data.button);
                jQuery('#payingbuttons_'+mode).slideDown(400);
              }
            } else {
              jQuery('input[name="cbpaymode"]').prop('checked', false);
              selectPayingMode('');
              jQuery("#payingmsg").html(data.msg);
            }
          }, "json");
      }
    }
  </script>
</div>
