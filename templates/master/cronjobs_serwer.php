<?
$cron = getClass("Cronjobs");
$file = getClass("File");
$wgrywarka = getClass('Wgrywarka');
$roundsound = getClass('Roundsound');

$FTPSrv = new stdClass();

function DataWykonania($conf_name, $special_table='acp_system'){
  query("UPDATE `$special_table` SET `conf_value` = '".date("Y-m-d H:i:s")."' WHERE `$special_table`.`conf_name` = '$conf_name';");
}
function CzyMinalCzas($cron, $time){
  if($time == 0) { return false; }
  if(strtotime($cron) < (time() - $time)){ return true; } else { return false; }
}

$only_test_serwer = 0;
if($only_test_serwer == 1) {
  echo '<div class="box-body">
          <blockquote>
            <p>UWAGA! Jest włączony testowy serwer</p>
            <small>Pamietaj o zmianie o weryfikacji wgrywanych plików</cite></small>
          </blockquote>
        </div>';
  $serwery_q = all("SELECT `serwer_id`, `test_serwer`, `nazwa`, `serwer_on`, `ftp_user`, `ftp_haslo`, `ftp_host`, `rangi`, `mapy`, `mapy_plugin`, `help_menu`, `bazy`, `reklamy`, `hextags`, `uslugi`, `katalog` FROM `acp_serwery` LEFT JOIN (`acp_serwery_cronjobs`) ON `acp_serwery`.`serwer_id` = `acp_serwery_cronjobs`.`serwer` WHERE `ip` != '' AND `port` != '' AND `test_serwer` = 1");
}
else {
  $serwery_q = all("SELECT `serwer_id`, `test_serwer`, `nazwa`, `serwer_on`, `ftp_user`, `ftp_haslo`, `ftp_host`, `rangi`, `mapy`, `mapy_plugin`, `help_menu`, `bazy`, `reklamy`, `hextags`, `uslugi`, `katalog` FROM `acp_serwery` LEFT JOIN (`acp_serwery_cronjobs`) ON `acp_serwery`.`serwer_id` = `acp_serwery_cronjobs`.`serwer` WHERE `ip` != '' AND `port` != '' AND `serwer_on` = 1 AND `cronjobs` = 1 ");
}

foreach($serwery_q as $serwery){

  // zmienne dotyczace wgrywania zdalna_edycja_plikow
  $serwer_id = $serwery->serwer_id;
  // dane ftp
  $FTPSrv->{$serwer_id} = new stdClass();
  $FTPSrv->{$serwer_id}->ftp =  (object) array('serwer' => $serwery->ftp_host, 'user' => $serwery->ftp_user, 'haslo' => encrypt_decrypt('decrypt', $serwery->ftp_haslo), 'serwer_id' => $serwer_id, 'test_serwer' => $serwery->test_serwer);
  // utowrzenie katalogu dla serwera gdy go nie ma
  echo $file->sprawdz_katalog($serwer_id);

  if(CzyMinalCzas($acp_system['cron_uslugi'], $acp_system['time_uslugi']) && $serwery->uslugi == 1){
    echo $file->file_tworzy("admins_simple.ini", $serwer_id, "uslugi");
    $FTPSrv->{$serwer_id}->{'files'}->uslugi = (object) array('serwer_id' => $serwer_id, 'ftp_directory' => $serwery->katalog."/addons/sourcemod/configs", 'ftp_dest_file_name' => "admins_simple.ini", 'ftp_source_file_name' => "www/upload/serwer_$serwer_id/admins_simple.ini", 'type_upload' => 'FTP_ASCII', 'modul' => "?x=uslugi", 'info_wykonanie' => 'cron_uslugi' );
  }
  if(CzyMinalCzas($acp_system['cron_reklamy'], $acp_system['time_reklamy']) && $serwery->reklamy == 1){
    echo $file->file_tworzy("reklama.ini", $serwer_id, "reklamy");
    $FTPSrv->{$serwer_id}->{'files'}->reklamy = (object) array('serwer_id' => $serwer_id, 'ftp_directory' => $serwery->katalog."/addons/sourcemod/configs", 'ftp_dest_file_name' => "acp_reklama.ini", 'ftp_source_file_name' => "www/upload/serwer_$serwer_id/reklama.ini", 'type_upload' => 'FTP_ASCII', 'modul' => "?x=serwery_konfiguracja&xx=reklamy", 'info_wykonanie' => 'cron_reklamy' );
  }
  if(CzyMinalCzas($acp_system['cron_hextags'], $acp_system['time_hextags']) && $serwery->hextags == 1){
    echo $file->file_tworzy("hextags.cfg", $serwer_id, "hextags");
    $FTPSrv->{$serwer_id}->{'files'}->hextags = (object) array('serwer_id' => $serwer_id, 'ftp_directory' => $serwery->katalog."/addons/sourcemod/configs", 'ftp_dest_file_name' => "hextags.cfg", 'ftp_source_file_name' => "www/upload/serwer_$serwer_id/hextags.cfg", 'type_upload' => 'FTP_ASCII', 'modul' => "?x=serwery_konfiguracja&xx=hextags", 'info_wykonanie' => 'cron_hextags' );
  }
  if(CzyMinalCzas($acp_system['cron_baza'], $acp_system['time_baza']) && $serwery->bazy == 1){
    echo $file->file_tworzy("databases.cfg", $serwer_id, "database");
    $FTPSrv->{$serwer_id}->{'files'}->databases = (object) array('serwer_id' => $serwer_id, 'ftp_directory' => $serwery->katalog."/addons/sourcemod/configs", 'ftp_dest_file_name' => "databases.cfg", 'ftp_source_file_name' => "www/upload/serwer_$serwer_id/databases.cfg", 'type_upload' => 'FTP_ASCII', 'modul' => "?x=serwery_konfiguracja&xx=baza", 'info_wykonanie' => 'cron_baza' );
  }
  if(CzyMinalCzas($acp_system['cron_mapy'], $acp_system['time_mapy']) && $serwery->mapy == 1){
    if(is_null($serwery->mapy_plugin) || $serwery->mapy_plugin == 'UMC') {
      echo $file->file_tworzy("umc_mapcycle.txt", $serwer_id, "mapy_umc");
      $FTPSrv->{$serwer_id}->{'files'}->umc_mapcycle = (object) array('serwer_id' => $serwer_id, 'ftp_directory' => $serwery->katalog, 'ftp_dest_file_name' => "umc_mapcycle.txt", 'ftp_source_file_name' => "www/upload/serwer_$serwer_id/umc_mapcycle.txt", 'type_upload' => 'FTP_ASCII', 'modul' => "?x=serwery_konfiguracja&xx=mapy", 'info_wykonanie' => 'cron_mapy' );
    }
    else {
      echo $file->file_tworzy("lista_map.txt", $serwer_id, "mapchooser");
      $FTPSrv->{$serwer_id}->{'files'}->map_list_1 = (object) array('serwer_id' => $serwer_id, 'ftp_directory' => $serwery->katalog, 'ftp_dest_file_name' => "maplist.txt", 'ftp_source_file_name' => "www/upload/serwer_$serwer_id/lista_map.txt", 'type_upload' => 'FTP_ASCII', 'modul' => "?x=serwery_konfiguracja&xx=mapy", 'info_wykonanie' => 'cron_mapy');
      $FTPSrv->{$serwer_id}->{'files'}->map_list_2 = (object) array('serwer_id' => $serwer_id, 'ftp_directory' => $serwery->katalog, 'ftp_dest_file_name' => "mapcycle.txt", 'ftp_source_file_name' => "www/upload/serwer_$serwer_id/lista_map.txt", 'type_upload' => 'FTP_ASCII', 'modul' => "?x=serwery_konfiguracja&xx=mapy");
      $FTPSrv->{$serwer_id}->{'files'}->map_list_3 = (object) array('serwer_id' => $serwer_id, 'ftp_directory' => $serwery->katalog."/addons/sourcemod/configs/mapchooser_extended/maps", 'ftp_dest_file_name' => "csgo.txt", 'ftp_source_file_name' => "www/upload/serwer_$serwer_id/lista_map.txt", 'type_upload' => 'FTP_ASCII', 'modul' => "?x=serwery_konfiguracja&xx=mapy");
      $FTPSrv->{$serwer_id}->{'files'}->map_list_4 = (object) array('serwer_id' => $serwer_id, 'ftp_directory' => $serwery->katalog."/addons/sourcemod/configs/mapchooser_extended/maps", 'ftp_dest_file_name' => "cstrike.txt", 'ftp_source_file_name' => "www/upload/serwer_$serwer_id/lista_map.txt", 'type_upload' => 'FTP_ASCII', 'modul' => "?x=serwery_konfiguracja&xx=mapy");
    }
  }
  if(CzyMinalCzas($acp_system['cron_help_menu'], $acp_system['time_help_menu']) && $serwery->help_menu == 1){
    echo $file->file_tworzy("menu_podstawa.cfg", $serwer_id, "help_menu");
    $FTPSrv->{$serwer_id}->{'files'}->help_menu = (object) array('serwer_id' => $serwer_id, 'ftp_directory' => $serwery->katalog."/addons/sourcemod/configs", 'ftp_dest_file_name' => "acp_main_menu.cfg", 'ftp_source_file_name' => "www/upload/serwer_$serwer_id/menu_podstawa.cfg", 'type_upload' => 'FTP_ASCII', 'modul' => "?x=serwery_konfiguracja&xx=help_menu", 'info_wykonanie' => 'cron_help_menu' );

    $konfiguracja = row("SELECT * FROM `acp_serwery_helpmenu` WHERE `serwer_id` = $serwery->serwer_id;");
    if($konfiguracja->lista_serwerow == 1){
     echo $file->file_tworzy("menu_1_serwery.cfg", $serwer_id, "help_menu_listaserwerow");
     $FTPSrv->{$serwer_id}->{'files'}->help_menu_listaserwerow = (object) array('serwer_id' => $serwer_id, 'ftp_directory' => $serwery->katalog."/addons/sourcemod/configs", 'ftp_dest_file_name' => "acp_servers_menu.cfg", 'ftp_source_file_name' => "www/upload/serwer_$serwer_id/menu_1_serwery.cfg", 'type_upload' => 'FTP_ASCII', 'modul' => "?x=serwery_konfiguracja&xx=help_menu" );

     echo $file->file_tworzy("menu_1_serwery_detale.cfg", $serwer_id, "help_menu_listaserwerow_details");
     $FTPSrv->{$serwer_id}->{'files'}->help_menu_listaserwerow_details = (object) array('serwer_id' => $serwer_id, 'ftp_directory' => $serwery->katalog."/addons/sourcemod/configs", 'ftp_dest_file_name' => "acp_details_menu.cfg", 'ftp_source_file_name' => "www/upload/serwer_$serwer_id/menu_1_serwery_detale.cfg", 'type_upload' => 'FTP_ASCII', 'modul' => "?x=serwery_konfiguracja&xx=help_menu" );
    }
    if($konfiguracja->lista_adminow == 1){
     echo $file->file_tworzy("menu_2_admini.cfg", $serwer_id, "help_menu_listaadminow");
     $FTPSrv->{$serwer_id}->{'files'}->help_menu_listaadminow = (object) array('serwer_id' => $serwer_id, 'ftp_directory' => $serwery->katalog."/addons/sourcemod/configs", 'ftp_dest_file_name' => "acp_admins_menu.cfg", 'ftp_source_file_name' => "www/upload/serwer_$serwer_id/menu_2_admini.cfg", 'type_upload' => 'FTP_ASCII', 'modul' => "?x=serwery_konfiguracja&xx=help_menu" );
    }
    if($konfiguracja->opis_vipa == 1){
     echo $file->file_tworzy("menu_3_vip.cfg", $serwer_id, "help_menu_opisvipa");
     $FTPSrv->{$serwer_id}->{'files'}->help_menu_opisvipa = (object) array('serwer_id' => $serwer_id, 'ftp_directory' => $serwery->katalog."/addons/sourcemod/configs", 'ftp_dest_file_name' => "acp_vip_panel.cfg", 'ftp_source_file_name' => "www/upload/serwer_$serwer_id/menu_3_vip.cfg", 'type_upload' => 'FTP_ASCII', 'modul' => "?x=serwery_konfiguracja&xx=help_menu" );
    }
    if($konfiguracja->lista_komend == 1){
     echo $file->file_tworzy("menu_4_komendy.cfg", $serwer_id, "help_menu_komendy");
     $FTPSrv->{$serwer_id}->{'files'}->help_menu_komendy = (object) array('serwer_id' => $serwer_id, 'ftp_directory' => $serwery->katalog."/addons/sourcemod/configs", 'ftp_dest_file_name' => "acp_command_menu.cfg", 'ftp_source_file_name' => "www/upload/serwer_$serwer_id/menu_4_komendy.cfg", 'type_upload' => 'FTP_ASCII', 'modul' => "?x=serwery_konfiguracja&xx=help_menu" );
    }
    if($konfiguracja->statystyki == 1){
     echo $file->file_tworzy("menu_5_statystyki.cfg", $serwer_id, "help_menu_statystyki");
     $FTPSrv->{$serwer_id}->{'files'}->help_menu_statystyki = (object) array('serwer_id' => $serwer_id, 'ftp_directory' => $serwery->katalog."/addons/sourcemod/configs", 'ftp_dest_file_name' => "acp_stats_menu.cfg", 'ftp_source_file_name' => "www/upload/serwer_$serwer_id/menu_5_statystyki.cfg", 'type_upload' => 'FTP_ASCII', 'modul' => "?x=serwery_konfiguracja&xx=help_menu" );
    }
  }

  // wgrywarka
  $wgrywarka = row("SELECT * FROM `acp_wgrywarka` WHERE `status` = 0; ");
  if(!empty($wgrywarka)) {
    $wgrywarka->file = json_decode($wgrywarka->file);
    foreach ($wgrywarka->file as $value) {
      $wgrywarka_serwer_id = $wgrywarka->serwer_id;
      $wgrywarka_name = "Wgrywarka__SERWER_".$serwer_id."__W_ID_".$wgrywarka->id."__NAME_".$value->ftp_dest_file_name;
      $wgrywarka_katalog = $serwery->katalog.$value->ftp_directory;
      $FTPSrv->{$wgrywarka_serwer_id}->{'files'}->$wgrywarka_name = (object) array('serwer_id' => $serwer_id, 'ftp_directory' => $wgrywarka_katalog, 'ftp_dest_file_name' => "$value->ftp_dest_file_name", 'ftp_source_file_name' => "$value->ftp_source_file_name", 'type_upload' => 'FTP_BINARY', 'modul' => "?x=wgrywarka", 'wgrywarka_file_id' => "$wgrywarka->id");
    }
  }


  if(CzyMinalCzas($acp_system['cron_file_list_pluginy'], $acp_system['cron_file_list_pluginy_time'])){
    $FTPSrv->{$serwer_id}->{'scan'}->plugins = (object) array('serwer_id' => $serwer_id, 'katalog' => $serwery->katalog."/addons/sourcemod/plugins", 'type' => 'nlist', 'acp_cache_api' => 'serwer_id'.$serwer_id.'_pluginy', 'info_wykonanie' => 'cron_file_list_pluginy');
  }
  if(CzyMinalCzas($acp_system['cron_file_list_mapy'], $acp_system['cron_file_list_mapy_time'])){
    $FTPSrv->{$serwer_id}->{'scan'}->maps = (object) array('serwer_id' => $serwer_id, 'katalog' => $serwery->katalog."/maps", 'type' => 'nlist', 'acp_cache_api' => 'serwer_id'.$serwer_id.'_mapy', 'info_wykonanie' => 'cron_file_list_mapy');
  }
  if(CzyMinalCzas($acp_system['cron_file_list_logi'], $acp_system['cron_file_list_logi_time'])){
    $FTPSrv->{$serwer_id}->{'scan'}->logs_sm = (object) array('serwer_id' => $serwer_id, 'katalog' => $serwery->katalog."/addons/sourcemod/logs", 'type' => 'nlist', 'acp_cache_api' => 'serwer_id'.$serwer_id.'_logs_sm', 'info_wykonanie' => 'cron_file_list_logi');
    $FTPSrv->{$serwer_id}->{'scan'}->logs = (object) array('serwer_id' => $serwer_id, 'katalog' => $serwery->katalog."/logs", 'type' => 'nlist', 'acp_cache_api' => 'serwer_id'.$serwer_id.'_logs', 'info_wykonanie' => 'cron_file_list_logi');
  }

  // ROUNDSOUND
  $rs_ograniczenia = new \stdClass();
  $rs_ograniczenia->rs_on = one("SELECT `conf_value` FROM `rs_ustawienia` WHERE `conf_name` = 'rs_on';");
  if($rs_ograniczenia->rs_on == '1'){
    $roundsound_serwery_array = one("SELECT `conf_value` FROM `rs_ustawienia` WHERE `conf_name` = 'rs_serwery';");
    $roundsound_serwery_array = json_decode($roundsound_serwery_array);
    $rs_ograniczenia->rs_cron = one("SELECT `conf_value` FROM `rs_ustawienia` WHERE `conf_name` = 'rs_cron';");
    $rs_ograniczenia->rs_cron_utwory = one("SELECT `conf_value` FROM `rs_ustawienia` WHERE `conf_name` = 'rs_cron_utwory';");
    $rs_ograniczenia->co_ile_cron = 60*60;
    $rs_piosenki = new \stdClass();
    $rs_piosenki->rs_roundsound = one("SELECT `conf_value` FROM `rs_ustawienia` WHERE `conf_name` = 'rs_roundsound' LIMIT 1");
    foreach ($roundsound_serwery_array as $rs_serwer) {
      if(strtotime($rs_ograniczenia->rs_cron)< (time() - $rs_ograniczenia->co_ile_cron) && $serwer_id == $rs_serwer){
        // plik z nazwami utworów
        $files_rs_konfiguracja = "RoundSound__SERWER_".$serwer_id."__RS_ID_".$rs_piosenki->rs_roundsound;
        echo $file->file_tworzy("roundsound.txt", $serwer_id, "roundsound");
        $FTPSrv->{$serwer_id}->{'files'}->$files_rs_konfiguracja = (object) array('serwer_id' => $serwer_id, 'ftp_directory' =>  $serwery->katalog."/addons/sourcemod/configs", 'ftp_dest_file_name' => "abner_res.txt", 'ftp_source_file_name' => "www/upload/serwer_".$serwer_id."/roundsound.txt", 'type_upload' => 'FTP_ASCII', 'modul' => "?x=roundsound", 'info_wykonanie' => "rs_cron", 'special_table' => "rs_ustawienia" );

        // plik konfiguracyjny
        $files_rs_konfiguracja_cfg = "RoundSound__SERWER_".$serwer_id."__RS_cfg_ID_".$rs_piosenki->rs_roundsound;
        echo $file->file_tworzy("roundsound_cfg.txt", $serwer_id, "roundsound_cfg");
        $FTPSrv->{$serwer_id}->{'files'}->$files_rs_konfiguracja_cfg = (object) array('serwer_id' => $serwer_id, 'ftp_directory' =>  $serwery->katalog."/cfg/sourcemod", 'ftp_dest_file_name' => "abner_res.cfg", 'ftp_source_file_name' => "www/upload/serwer_".$serwer_id."/roundsound_cfg.txt", 'type_upload' => 'FTP_ASCII', 'modul' => "?x=roundsound", 'info_wykonanie' => "rs_cron", 'special_table' => "rs_ustawienia" );
      }
      if(strtotime($rs_ograniczenia->rs_cron_utwory)< (time() - $rs_ograniczenia->co_ile_cron) && $serwer_id == $rs_serwer){
        $rs_piosenki->lista = one("SELECT `lista_piosenek` FROM `rs_roundsound` WHERE `id` = $rs_piosenki->rs_roundsound LIMIT 1");
        $rs_piosenki->rs_katalog = one("SELECT `conf_value` FROM `rs_ustawienia` WHERE `conf_name` = 'rs_katalog' LIMIT 1");
        $rs_piosenki->katalog = $serwery->katalog."/sound/".$rs_piosenki->rs_katalog."/".$rs_piosenki->rs_roundsound;
        foreach ($rs_piosenki->lista = json_decode($rs_piosenki->lista) as $value){
          $piosenka_code = one("SELECT `mp3_code` FROM `rs_utwory` WHERE `id` = $value LIMIT 1");

          $files_rs_piosenka = "RoundSound__SERWER_".$serwer_id."__PIOSENKA_ID_".$value;
          $FTPSrv->{$serwer_id}->{'files'}->$files_rs_piosenka = (object) array('serwer_id' => $serwer_id, 'ftp_directory' =>  $rs_piosenki->katalog, 'ftp_dest_file_name' => "$piosenka_code.mp3", 'ftp_source_file_name' => "www/mp3/$piosenka_code.mp3", 'type_upload' => 'FTP_BINARY', 'modul' => "?x=roundsound_utowry", 'info_wykonanie' => "rs_cron_utwory", 'special_table' => "rs_ustawienia" );
        }
      }
    }
  }
}
// Blokowanie cronjoba gdy 5 razy w ciagu 6 h wystapi problem z polaczeniem
foreach($serwery_q as $serwery){
  (int)$liczba_bledow = one("SELECT COUNT(*) FROM `acp_serwery_bledy` WHERE `serwer_id` = $serwery->serwer_id AND `status` = 1 AND `data` > NOW() - INTERVAL 6 HOUR");
  if($liczba_bledow >= 5 && $serwery->test_serwer == 0){
    query("UPDATE `acp_serwery` SET `cronjobs` = '-1' WHERE `serwer_id` = $serwery->serwer_id;");

    // powiadomienie
    $user_list = array();
    $grupy_q = all("SELECT `id` FROM `acp_users_grupy` WHERE `dostep` LIKE '%\"SerwerCron\":\"1\"%' ");
    foreach ($grupy_q as $grupy) {
      $uzytkownicy_q = all("SELECT `user` FROM `acp_users` WHERE `grupa` = '$grupy->id' ");
      foreach ($uzytkownicy_q as $uzytkownicy) {
        array_push($user_list, $uzytkownicy->user);
      }
    }
    powiadomienie($user_list, "?x=serwery_ust&edycja=$serwery->serwer_id", "Cronjobs | Serwer: $serwery->nazwa [$serwery->mod](ID: $serwery->serwer_id) został zablokowany z powodu problemów z połaczeniem. Sprawdź dane FTP a następnie ustaw edycję plików na Tak", "fa fa-server");
  }
}

// WGRYWANIE PLIKÓW
/*
  $FTPSrv->SERWERID

  files->unicodenamefiles
  array dane:
    serwer_id => SERWERID,
    ftp_directory => katalog na serwerze,
    ftp_dest_file_name => nazwa pliku na serwerze,
    ftp_source_file_name > lokalizacja pliku w acp
    type_upload => typ wgrywania (PLIK txt FTP_ASCII, MP3 FTP_BINARY),
    modul => nazwa modułu z jakiego wykonywany jest wgranie pliku,
    info_wykonanie => nazwa confa w tabeli `special_table` której ma być odnotowana data wgrywania
    special_table => nazwa tabeli w której ma być odnotowana data wgrania default `acp_system`
    wgrywarka_file_id => id pracy wgrywarki w której ma odnotować o wgraniu pliku
*/
/*
  scan->unicodename
  array dane:
    serwer_id => SERWERID,
    katalog => lokalizacja skanowania plikow,
    type => jak pobrać dane nlist, rawlist
    acp_cache_api => nazwa wiesza danych,
    info_wykonanie => nazwa confa w tabeli `special_table` której ma być odnotowana data wgrywania
    special_table => nazwa tabeli w której ma być odnotowana data wgrania default `acp_system`
*/
foreach ($FTPSrv as $value) {
  if(!empty($value->files) || !empty($value->scan)){
    $cron->ftp_upload($value->ftp->serwer_id, $value->ftp, $value->files, $value->scan);
  }
}

// kasowanie plików
foreach($serwery_q as $serwery){
  $cron->delete_old_files("www/upload/serwer_$serwery->serwer_id");
}
?>
