<?
$raport->get->old_data->gt_rank = (empty($raport->get->old_data->gt_rank)) ? 0 : $raport->get->old_data->gt_rank;
$raport->get->old_data->gt_low = (empty($raport->get->old_data->gt_low)) ? 0 : $raport->get->old_data->gt_low;
$raport->get->old_data->gt_hight = (empty($raport->get->old_data->gt_hight)) ? 0 : $raport->get->old_data->gt_hight;
$raport->get->old_data->finanse_koszt = (empty($raport->get->old_data->finanse_koszt)) ? 0 : $raport->get->old_data->finanse_koszt;
?>

<form method='post'>
  <div class="col-lg-4">
    <h4>Sourcebans:</h4>
    <p>
      <div class='form-group input-group'>
        <span class='input-group-addon'>Ilość Banów</span>
        <input class="form-control" type="number" name="sb_ban" value="<?= $raport->get->sb->liczba_ban ?>">
      </div>
    </p>
    <p>
      <div class='form-group input-group'>
        <span class='input-group-addon'>Ilość Mutów</span>
        <input class="form-control" type="number" name="sb_mute" value="<?= $raport->get->sb->liczba_mute ?>">
      </div>
    </p>
    <p>
      <div class='form-group input-group'>
        <span class='input-group-addon'>Ilość Gagów</span>
        <input class="form-control" type="number" name="sb_gag" value="<?= $raport->get->sb->liczba_gag ?>">
      </div>
    </p>
    <p>
      <div class='form-group input-group'>
        <span class='input-group-addon'>Ilość Unbanów</span>
        <input class="form-control" type="number" name="sb_unban" value="<?= $raport->get->sb->liczba_unban ?>">
      </div>
    </p>
    <p>
      <div class='form-group input-group'>
        <span class='input-group-addon'>Ilość UnMute</span>
        <input class="form-control" type="number" name="sb_unmute" value="<?= $raport->get->sb->liczba_unmute ?>">
      </div>
    </p>
    <p>
      <div class='form-group input-group'>
        <span class='input-group-addon'>Ilość UngGag</span>
        <input class="form-control" type="number" name="sb_ungag" value="<?= $raport->get->sb->liczba_ungag ?>">
      </div>
    </p>
  </div>
  <div class="col-lg-4">
    <h4>Statystyki (HLS/GT):</h4>
    <p>
      <div class='form-group input-group'>
        <span class='input-group-addon'>HlStats [Ilość Graczy]</span>
        <input class="form-control" type="number" name="hls_graczy" value="<?= $raport->get->hls_graczy ?>">
      </div>
    </p>
    <p>
      <div class='form-group input-group'>
        <span class='input-group-addon'>GameTracker [Rank]</span>
        <input class="form-control" type="number" name="gt_rank" placeholder="<?= $raport->get->old_data->gt_rank ?>">
      </div>
    </p>
    <p>
      <div class='form-group input-group'>
        <span class='input-group-addon'>GameTracker [Rank Lowest (Ubiegły miesiąc)]</span>
        <input class="form-control" type="number" name="gt_low" placeholder="<?= $raport->get->old_data->gt_low ?>">
      </div>
    </p>
    <p>
      <div class='form-group input-group'>
        <span class='input-group-addon'>GameTracker [Rank Higest (Ubiegły miesiąc)]</span>
        <input class="form-control" type="number" name="gt_hight" placeholder="<?= $raport->get->old_data->gt_hight ?>">
      </div>
    </p>
  </div>
  <div class="col-lg-4">
    <h4>Finanse:</h4>
    <p>
      <div class='form-group input-group'>
        <span class='input-group-addon'>Koszt Serwera</span>
        <input class="form-control" type="number" name="finanse_koszt" placeholder="<?= $raport->get->old_data->finanse_koszt ?>">
      </div>
    </p>
    <p>
      <div class='form-group input-group'>
        <span class='input-group-addon'>Usługi [Ilość sprzedanych]</span>
        <input class="form-control" type="number" name="sklep_uslugi" value="">
      </div>
    </p>
    <p>
      <div class='form-group input-group'>
        <span class='input-group-addon'>Usługi [Cena sprzedanych]</span>
        <input class="form-control" type="number" name="sklep_uslugi_koszt" value="">
      </div>
    </p>
    <p>
      <div class='form-group input-group'>
        <span class='input-group-addon'>Admini [Liczba]</span>
        <input class="form-control" type="number" name="admin_liczba" value="<?= $lista_adminow_dane->adminow ?>">
      </div>
    </p>
    <p>
      <div class='form-group input-group'>
        <span class='input-group-addon'>Admin Miesiąca</span>
        <select class="form-control" name="admin_miesiaca">
          <option value="0">Brak</option>
          <? foreach ($raport_array as $key): ?>
          <option value="<?= $key->authid ?>"><?= $key->user ?> (<?= $key->steam_nick ?>)</option>
          <? endforeach; ?>
        </select>
      </div>
    </p>
  </div>
  <div class="col-lg-12">
    <table width="100%" class="table table-striped table-bordered table-hover" id="tab_lista_adminow_raport">
      <thead>
        <tr>
          <th>Admin<br><small><i>Sourcebans</i></small></th>
          <th>Admin<br><small><i>Steam</i></small></th>
          <th>Posty<br><small><i>Forum</i></small></th>
          <th>Warny<br><small><i>Forum</i></small></th>
          <th>Czas Połaczenia<br><small><i>Serwer (sekundy)</i></small></th>
          <th>Składka<br><small><i>Finanse</i></small></th>
          <th>Kwota<br><small><i>Finanse</i></small></th>
          <th>Metoda<br><small><i>Finanse</i></small></th>
          <th>Opinia Opiekuna<br><small><i>O wybranych Adminach</i></small></th>
        </tr>
      </thead>
      <tbody>
        <?
        foreach ($raport_array as $raport_admin):
          $raport_admin->czas_polaczenia = $api->api_11_2020('hls', $config['site'], $acp_system['api_hlx_host'], $acp_system['api_hlx_db'], $acp_system['api_hlx_user'], $acp_system['api_hlx_pass'], "&xx=czas_polaczenia&y=$raport_admin->authid&srv=$srv_dane->prefix_hls");

          // dane z okresu poprzedniego
          $raport_admin_old = row("SELECT `forum_posty`, `forum_warny`, `opinia` FROM `raport_opiekun` WHERE `steamid` = '$raport_admin->authid' AND `serwer` = '$serwer_id' AND `miesiac` = '".$raport->data['ubiegly_miesiac']."'  LIMIT 1");
          $raport_admin->forum_posty = (empty($raport_admin_old->forum_posty)) ? 0 : $raport_admin_old->forum_posty;
          $raport_admin->forum_warny = (empty($raport_admin_old->forum_warny)) ? 0 : $raport_admin_old->forum_warny;
          $raport_admin->opinia = (empty($raport_admin_old->opinia)) ? 0 : $raport_admin_old->opinia;
        ?>
          <tr class="odd gradeX">
            <input type="hidden" name="id[]" value="<?= $raport_admin->aid ?>"/>
            <input type="hidden" name="steamid[]" value="<?= $raport_admin->authid ?>"/>
            <input type="hidden" name="czas_gry[]" value="<?= $raport_admin->czas_polaczenia ?>"/>
            <input type="hidden" name="srv_group[]" value="<?= $raport_admin->srv_group ?>"/>
            <td><input class="form-control" name="nick_sb[]" type="text" value="<?= $raport_admin->user ?>" readonly="readonly"></td>
            <td><input class="form-control" name="nick_steam[]" type="text" value="<?= $raport_admin->steam_nick ?>" readonly="readonly"></td>
            <td><input class="form-control" name="forum_posty[]" type="number" placeholder="<?= $raport_admin->forum_posty ?>"></td>
            <td><input class="form-control" name="forum_warny[]" type="number" placeholder="<?= $raport_admin->forum_warny ?>"></td>
            <td><input class="form-control" type="text" value="<?= sek_na_tekst((int)$raport_admin->czas_polaczenia) ?>" readonly="readonly"></td>

            <td><select class="form-control" name="skladka[]">
              <option value="1">Tak</option>
              <option value="0">Nie</option>
            </select></td>
            <td>
              <div class="input-group">
                <input class="form-control" name="skladka_kwota[]" type="number" value="5">
                <span class="input-group-addon">zł</span>
              </div>
            </td>
            <td><select class="form-control" name="skladka_metoda[]">
              <option value="SMS">SMS</option>
              <option value="Przelew">Przelew</option>
              <option value="PSC">PSC</option>
            </select></td>
            <td style="width:10px; white-space:nowrap;"><input class="form-control" name="opinia[]" type="text" placeholder="<?= $raport_admin->opinia ?>"></td>
          </tr>
      <? endforeach; ?>
      </tbody>
    </table>
  </div>
