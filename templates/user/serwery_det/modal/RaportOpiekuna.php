<?
$raport = new stdClass();
$raport->on = (int)$acp_system['AdmRaport_on'];

if(uprawnienia($dostep->serwery_det_RaportOpiekuna, $player->user) == 1):
  $raport = new stdClass();
  $raport->on = (int)$acp_system['AdmRaport_on'];
  $raport->start =(int)$acp_system['AdmRaport_start'];
  $raport->koniec =(int)$acp_system['AdmRaport_stop'];
  $raport->data = array(
    'dzien' => date("j"),
    'miesiac' => date("m"),
    'rok' => date("Y"),
    'ubiegly_miesiac' => date('m', strtotime("-1 month"))
  );
  $raport->przycisk = '';
  $raport->save = row("SELECT * FROM `raport_serwer` WHERE `miesiac` = '".$raport->data['ubiegly_miesiac']."' AND `rok` = '".$raport->data['rok']."' AND `serwer_id` = '$serwer_id' LIMIT 1");
  if(empty($raport->save)){
    $raport->get->hls_graczy = $api->api_11_2020('hls', $config['site'], $acp_system['api_hlx_host'], $acp_system['api_hlx_db'], $acp_system['api_hlx_user'], $acp_system['api_hlx_pass'], "&xx=ilosc_graczy&srv=$srv_dane->prefix_hls", "GET", 60);
    $raport->get->sb = $api->api_11_2020('sb', $config['site'], $acp_system['api_sb_host'], $acp_system['api_sb_db'], $acp_system['api_sb_user'], $acp_system['api_sb_pass'],
    "&xx=raport_opiekuna&srv=$srv_dane->prefix_sb", "GET", 60);
    $raport->get->old_data = row("SELECT `finanse_koszt`, `gt_rank`, `gt_low`, `gt_hight` FROM `raport_serwer` WHERE `serwer_id` = '$serwer_id' AND `miesiac` = '".$raport->data['ubiegly_miesiac']."' LIMIT 1");
  }
?>

<style> .modal-full { min-width: 100%; } .modal-full .modal-content { min-height: 90vh; } </style>

<div class="modal fade" id="admin_list_raport">
  <div class="modal-dialog modal-lg modal-full">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Raport Opiekuna - Lista Adminów</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <!-- Gdy raport jest wyłączony -->
          <? if($raport->on != 1): ?>
            <div class="col-lg-12">
              <p>Raport został wyłączony przez administratora systemu</p>
            </div>
          <!-- Gdy raport złożony -->
          <? elseif(!empty($raport->save)): ?>
            <div class="col-lg-12">
              <p>Raport został złożony za ten miesiąc</p>
            </div>
          <!-- Gdy minie czas na złożenie raportu-->
          <? elseif($raport->data['dzien'] < (int)  $raport->start || $raport->data['dzien'] > (int)$raport->koniec): ?>
            <div class="col-lg-12">
              <p>Czas na złożenie raportu minął.. Okres zbioru danych jest od <?= (int)  $raport->start ?> do <?= (int)$raport->koniec ?> dnia miesiąca..</p>
            </div>
          <!-- Gdy nie ma raportu, to nowy formularz -->
          <? elseif(empty($raport->save)):
              $raport->przycisk = '<button type="input" name="admin_list_raport" class="btn btn-success"><i class="fa fa-send"></i> Złóż Raport</button>';
              require_once('templates/user/serwery_det/modal/RaportOpiekuna_Form.php');
           endif; ?>
        </div>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Zamknij</button>
          <?= $raport->przycisk ?>
        </form>
      </div>
    </div>
  </div>
</div>

<?
endif;
?>
