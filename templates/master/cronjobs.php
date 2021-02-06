<?
$cron = getClass("Cronjobs");
$api = getClass("Api");
$source = getClass("SourceUpdate");

require __DIR__ . './../../functions/SourceQuery/bootstrap.php';
use xPaw\SourceQuery\SourceQuery;


//
// SOURCEMOD sprawdzenie dostepnych nowych silnikow
//
$aktualizacja_source = $source->sprawdz_dostepne((int)$acp_system['sourceupdate_wymus']);

//
// Serwery Aktualizacja danych
//
$serwery_q = all("SELECT `serwer_id`, `game`, `prefix_sb`, `ip`, `port`, `nazwa`, `status`, `status_data`, `rcon` FROM `acp_serwery` WHERE `ip` != '' AND `port` != ''; ");
foreach($serwery_q as $serwery){
  //sv_tags
  $serwery->rcon_dec = encrypt_decrypt('decrypt', $serwery->rcon);
  if(!empty($serwery->rcon_dec)){
    $list_tags = all("SELECT * FROM `acp_serwery_tagi` WHERE `serwer` IN (0, $serwery->serwer_id) ");
    $sv_tags = 'sm_acvar_console sv_tags "! !,';
    foreach ($list_tags as $key => $value) {
      if($value->staly == 0){
        $value->losowa = rand(0, 1);
        if($value->losowa == 0){
          $sv_tags .= "$value->tekst,";
        }
      }
      else {
        $sv_tags .= "$value->tekst,";
      }
    }
    $sv_tags .= '"';
    $serwery->sv_tags = $sv_tags;
  }

  // daj szanse na aktualizacje danych serwerow ktore nie odpowiadaja
  if($serwery->status == 1 && strtotime($serwery->status_data) < (time() - $acp_system['cron_serwery_time_off'])) {
    query("UPDATE `acp_serwery` SET `status` = '0' WHERE `serwer_id` = $serwery->serwer_id;");
  }

  if(strtotime($acp_system['cron_serwery']) < (time() - $acp_system['time_serwery']) && $serwery->status == 0){
    $Query = new SourceQuery( );

    try
    {
      switch ($serwery->game) {
        case 'CSGO':
          $Query->Connect( $serwery->ip, $serwery->port, 1, SourceQuery::SOURCE );
          if(!empty($serwery->rcon_dec)){
            $Query->SetRconPassword($serwery->rcon_dec);
            $Query->Rcon( $serwery->sv_tags );
          }
          break;
        case 'CS':
          $Query->Connect( $serwery->ip, $serwery->port, 1, SourceQuery::GOLDSOURCE );
          break;
      }

      $serwery->sourcequery = $Query->GetInfo( );

      // insert logs
      query("INSERT INTO `acp_serwery_logs` (`id` , `serwer_id` , `graczy` , `boty` , `sloty` , `data` ) VALUES (NULL, $serwery->serwer_id, '".$serwery->sourcequery['Players']."', '".$serwery->sourcequery['Bots']."', '".$serwery->sourcequery['MaxPlayers']."', NOW() ); ");
      // update serwer
      query("UPDATE `acp_serwery` SET `nazwa` = '".$serwery->sourcequery['HostName']."', `mapa` = '".$serwery->sourcequery['Map']."', `graczy` = '".$serwery->sourcequery['Players']."',  `max_graczy` = '".$serwery->sourcequery['MaxPlayers']."', `boty` = '".$serwery->sourcequery['Bots']."', `tags` = '".$serwery->sourcequery['GameTags']."'  WHERE `serwer_id` = $serwery->serwer_id; ");

      query("UPDATE `acp_system` SET `conf_value` = '".date("Y-m-d H:i:s")."' WHERE `conf_name` = 'cron_serwery';");

      //lista graczy cache
      $serwery->sourcequery_players = $cron->jsonRemoveUnicodeSequences($Query->GetPlayers( ));
      $serwery->ostatnia_aktualizacja = one("SELECT `data` FROM `acp_cache_api` WHERE `get` = 'serwer_id".$serwery->serwer_id."'; ");

      if($serwery->ostatnia_aktualizacja == ""){
        query("INSERT INTO `acp_cache_api` (`get`, `dane`, `data`) VALUES ('serwer_id".$serwery->serwer_id."', '$serwery->sourcequery_players', NOW()); ");
      }
      else if(strtotime($serwery->ostatnia_aktualizacja) < (time() - $acp_system['time_serwery'])) {
        query("UPDATE `acp_cache_api` SET `dane` = '$serwery->sourcequery_players', `data` = NOW() WHERE `get` = 'serwer_id".$serwery->serwer_id."'; ");
      }

      $cron->CRON_data_wykonania("cron_serwery");
      echo "<p>Dane Serwera ID: $serwery->serwer_id - ".$serwery->sourcequery['HostName']." zostały zaktualizowane.</p>";
    }
    catch( Exception $e )
    {
    	echo $e->getMessage( );
      if($e->getMessage( ) == 'Failed to read any data from socket') {
        query("UPDATE `acp_serwery` SET `status` = '1', `status_data` = NOW(),`graczy` = 0, `max_graczy` = 0, `boty` = 0 WHERE `serwer_id` = $serwery->serwer_id;");
      }
    }
    finally
    {
    	$Query->Disconnect( );
    }
  }


  //
  // Lista Adminów NEW
  //
  if(strtotime($acp_system['cron_adminlist'])< (time() - $acp_system['cron_adminlist_time'])){
    $pobierz_liste_z_sb = $api->api_11_2020('sb', $config['site'], $acp_system['api_sb_host'], $acp_system['api_sb_db'], $acp_system['api_sb_user'], $acp_system['api_sb_pass'], "&xx=admins_list&srv=$serwery->prefix_sb");
    $api_sb_admins = $pobierz_liste_z_sb;
    $admin_list = new stdClass();
    $i = 1;
    foreach ($api_sb_admins as $api_sb_admins_s) {
      $api_sb_admins_s->steam = toCommunityID($api_sb_admins_s->authid);
      $dane_steam = $cron->dane_steam_admin($acp_system['acp_steam_api'], $api_sb_admins_s->steam);

      $admin_list->{$i++} = $admin_list_det = new stdClass();

      $admin_list_det->{user} = $api_sb_admins_s->user;
      $admin_list_det->{srv_group} = $api_sb_admins_s->srv_group;
      $admin_list_det->{authid} = $api_sb_admins_s->authid;
      $admin_list_det->{steam} = $api_sb_admins_s->steam;
      $admin_list_det->{steam_nick} = htmlentities($dane_steam[personaname]);
      $admin_list_det->{steam_lastlogoff} = $dane_steam[lastlogoff];
      $admin_list_det->{steam_profileurl} = $dane_steam[profileurl];
      $admin_list_det->{steam_avatar} = $dane_steam[avatar];
      $admin_list_det->{steam_status} = $dane_steam[personastate];
    }
    $admin_list = $cron->jsonRemoveUnicodeSequences($admin_list);
    $czy_istnieje = one("SELECT `get` FROM `acp_cache_api` WHERE `get`='serwer_id".$serwery->serwer_id."_admin'; ");
    if(empty($czy_istnieje)){
      query("INSERT INTO `acp_cache_api` (`get`, `dane`) VALUES ('serwer_id".$serwery->serwer_id."_admin', '".$admin_list."'); ");
    }
    else {
      query("UPDATE `acp_cache_api` SET `dane` = '$admin_list' WHERE `acp_cache_api`.`get` = 'serwer_id".$serwery->serwer_id."_admin'; ");
    }
    $cron->CRON_data_wykonania("cron_adminlist");
  }
}

//
// Aktualizacja danych profili steam
//
$limit_steam = $acp_system['acp_steam_count_limit'];

$steam_update_q = all("SELECT `user`, `login`, `steam`, `steam_update` FROM `acp_users` WHERE `banned` = -1 AND `steam` NOT LIKE '%STEAM%' AND `steam` != '' AND `steam_update` < NOW() - INTERVAL 900 SECOND LIMIT $limit_steam; ");
if(!empty($steam_update_q)){
  foreach($steam_update_q as $su_q){
    $cron->aktualizuj_dane_steam($acp_system['acp_steam_api'], $su_q->steam, $su_q->user);
  }
}
?>
