<?
if(isset($_POST['admin_list_add_admin_from'])){
  $more = $sourcebans->dodaj_admina($serwer_id, $player->user, $dostep->serwery_det_SB_adm_dodaj);
  $komunikat = $api->api_11_2020('sb', $config['site'], $acp_system['api_sb_host'], $acp_system['api_sb_db'], $acp_system['api_sb_user'], $acp_system['api_sb_pass'], $more, "IN");
  $_SESSION['msg'] = komunikaty($komunikat->sukcess, 1);
  admin_log($user, $komunikat->sukcess." do serwera ".$srv_dane->mod." (ID: $serwer_id)");
  header("Location: ?x=$x&serwer_id=$serwer_id");
}
if(isset($_POST['admin_list_edytuj'])){
  $more = $sourcebans->edytuj_admina($serwer_id, $player->user, $dostep->serwery_det_SB_adm_edytuj);
  $komunikat = $api->api_11_2020('sb', $config['site'], $acp_system['api_sb_host'], $acp_system['api_sb_db'], $acp_system['api_sb_user'], $acp_system['api_sb_pass'], $more, "IN");
  $_SESSION['msg'] = komunikaty($komunikat->sukcess, 1);
  admin_log($user, $komunikat->sukcess." z serwera ".$srv_dane->mod." (ID: $serwer_id)");
  header("Location: ?x=$x&serwer_id=$serwer_id");
}
if(isset($_POST['admin_list_usun'])){
  $more = $sourcebans->usun_admina($serwer_id, $player->user, $dostep->serwery_det_SB_adm_usun);
  $komunikat = $api->api_11_2020('sb', $config['site'], $acp_system['api_sb_host'], $acp_system['api_sb_db'], $acp_system['api_sb_user'], $acp_system['api_sb_pass'], $more, "IN");
  $_SESSION['msg'] = komunikaty($komunikat->sukcess, 1);
  admin_log($user, $komunikat->sukcess." z serwera ".$srv_dane->mod." (ID: $serwer_id)");
  header("Location: ?x=$x&serwer_id=$serwer_id");
}
if(isset($_POST['admin_list_degradacja'])){
  $more = $sourcebans->degradacja_admina($serwer_id, $player->user, $dostep->serwery_det_SB_adm_degra_rezy);
  $komunikat = $api->api_11_2020('sb', $config['site'], $acp_system['api_sb_host'], $acp_system['api_sb_db'], $acp_system['api_sb_user'], $acp_system['api_sb_pass'], $more, "IN");
  $_SESSION['msg'] = komunikaty($komunikat->sukcess, 1);
  admin_log($user, $komunikat->sukcess." z serwera ".$srv_dane->mod." (ID: $serwer_id)");
  header("Location: ?x=$x&serwer_id=$serwer_id");
}
if(isset($_POST['admin_list_rezygnacja'])){
  $more = $sourcebans->rezygnacja_admina($serwer_id, $player->user, $dostep->serwery_det_SB_adm_degra_rezy);
  $komunikat = $api->api_11_2020('sb', $config['site'], $acp_system['api_sb_host'], $acp_system['api_sb_db'], $acp_system['api_sb_user'], $acp_system['api_sb_pass'], $more, "IN");
  $_SESSION['msg'] = komunikaty($komunikat->sukcess, 1);
  admin_log($user, $komunikat->sukcess." z serwera ".$srv_dane->mod." (ID: $serwer_id)");
  header("Location: ?x=$x&serwer_id=$serwer_id");
}


if(isset($_POST['ust_podstawowe_edit'])){
  $srv->ust_podstawowe_edit($_POST['ust_podstawowe_edit'], $serwer_id, $player->user, $dostep->ustawienia_podstawowe);
  header("Location: ?x=$x&serwer_id=$serwer_id");
}
$get_regulamin = (isset($_GET['regulamin'])) ? $_GET['regulamin'] : '';
if($get_regulamin == 'regulamin'){
  $srv->regulamin_edytuj($_POST['regulamin'], $serwer_id, $player->user);
  header("Location: ?x=$x&serwer_id=$serwer_id");
}

if(isset($_POST['admin_list_ustawienia_edit'])){
  $srv->list_adminow_ustawienia_edit($_POST['admin_list_ustawienia_edit'], $serwer_id, $player->user, $dostep->ustawienia_podstawowe);
  header("Location: ?x=$x&serwer_id=$serwer_id");
}
if(isset($_POST['admin_list_raport'])){
  $srv->raport_opiekuna($serwer_id, $player->user, $dostep->serwery_det_RaportOpiekuna);
  header("Location: ?x=$x&serwer_id=$serwer_id");
}

if(isset($_POST['wgraj_mape'])){
  $wgrywarka->mapa_file($player->user, $dostep->serwery_det_WgrajMape);
  header("Location: ?x=$x&serwer_id=$serwer_id");
}

//
// Wykresy
//
$get_wkresy = (isset($_GET['wykresy'])) ? $_GET['wykresy'] : '';
if(empty($_SESSION["wyk-graczy-zakres-$serwer_id"])) { $_SESSION["wyk-graczy-zakres-$serwer_id"] = 'hour'; }
if(empty($_SESSION["srv_det_graczy_$serwer_id"])) { $_SESSION["srv_det_graczy_$serwer_id"] = 30; }
if(empty($_SESSION["srv_det_gosetti_pozycja_$serwer_id"])) { $_SESSION["srv_det_gosetti_pozycja_$serwer_id"] = 10; }
if(empty($_SESSION["srv_det_gosetti_tura_$serwer_id"])) { $_SESSION["srv_det_gosetti_tura_$serwer_id"] = 10; }
if(empty($_SESSION["srv_det_gosetti_tura_$serwer_id"])) { $_SESSION["srv_det_gosetti_tura_$serwer_id"] = 10; }
if($get_wkresy == 'wykresy'){
  $_SESSION["wyk-graczy-zakres-$serwer_id"] = $_POST["wyk-graczy-zakres"];
  $_SESSION["srv_det_graczy_$serwer_id"] = $_POST["wyk-graczy-ilosc"];
  $_SESSION["srv_det_gosetti_pozycja_$serwer_id"] = $_POST["wyk-gosetti-pozycja-ilosc"];
  $_SESSION["srv_det_gosetti_tura_$serwer_id"] = $_POST["wyk-gosetti-punkty-ilosc"];

  header("Location: ?x=$x&serwer_id=$serwer_id");
}

//
// Changelog
//
$get_changelog = (isset($_GET['changelog'])) ? $_GET['changelog'] : '';
if($get_changelog == 'changelog'){
  if(isset($_POST['changelog_add'])) {
    $changelog->changelog_add('dodaj_admina', $_POST['changelog_add'], $serwer_id, $player->user);
  }
  if(isset($_POST['changelog_awans_deg_rez'])) {
    $changelog->changelog_add('awans_deg_rez', $_POST['changelog_awans_deg_rez'], $serwer_id, $player->user);
  }
  if(isset($_POST['changelog_wlasny'])) {
    $changelog->changelog_add('wlasny', $_POST['changelog_wlasny'], $serwer_id, $player->user);
  }
  header("Location: ?x=$x&serwer_id=$serwer_id");
}

//
// Prace Zdalne
//
if(!empty($_GET['prace_zdalne'])){
  if($_GET['prace_zdalne'] == 'skasuj_bledy'){
    $srv->prace_zdalne_oznacz_jako_przeczytane($serwer_id, $player->user, $dostep->PraceCykliczneOdczytane);
    header("Location: ?x=$x&serwer_id=$serwer_id");
  }
}
?>
