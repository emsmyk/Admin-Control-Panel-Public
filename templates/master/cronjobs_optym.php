<?
$cron = getClass("Cronjobs");
$api = getClass('Api');

// kasowanie starych logow z detali serwerow
echo $cron->optym_kasowanie_log_serwerow($acp_system['cron_optym_log_serwerow_limit'], $acp_system['cron_optym_log_serwerow_day']);

// kasowanie wygasłych usług, rang, raklam
echo $cron->optym_usuwanie_starych_uslug($acp_system['cron_optym_stare_uslugi_limit'], $acp_system['cron_optym_stare_uslugi_hour']);
echo $cron->optym_usuwanie_starych_reklam($acp_system['cron_optym_stare_reklamy_limit'], $acp_system['cron_optym_stare_reklamy_hour']);
echo $cron->optym_usuwanie_starych_rang($acp_system['cron_optym_stare_rangi_limit'], $acp_system['cron_optym_stare_rangi_hour']);
echo $cron->optym_usuwanie_starych_wiadomosci($acp_system['cron_optym_stare_wiadomosc_limit'], $acp_system['cron_optym_stare_wiadomosci_day']);

//
// Powiadomienia
//

// usuwanie oznaczenia nieprzeczytane
echo $cron->optym_powiadomienia_odczytane($acp_system['cron_optym_powiadomienia_odczytane']);
// usuwanie powiadomien starych
echo $cron->optym_powiadomienia_usun($acp_system['cron_optym_powiadomienia_usun']);

//
// Cronjobs optymalizator
//

// kasowanie i sprzatanie po logach i informacji o dacie skasowania...
echo $cron->optym_sprzatanie_po_logach_optymalizatora($acp_system['cron_optym_po_logach_optym_day']);

//
// Kasowanie danych nieistniejących serwerów
//
echo $cron->optm_kasuj_logi_nie_istniejacych_serwerow("acp_serwery_bledy");
echo $cron->optm_kasuj_logi_nie_istniejacych_serwerow("acp_serwery_logs_day");
echo $cron->optm_kasuj_logi_nie_istniejacych_serwerow("acp_serwery_logs_hour");
echo $cron->optm_kasuj_logi_nie_istniejacych_serwerow("acp_serwery_logs_month");
echo $cron->optm_kasuj_logi_nie_istniejacych_serwerow("acp_serwery_logs");
echo $cron->optm_kasuj_logi_nie_istniejacych_serwerow("acp_serwery_hlstats");
echo $cron->optm_kasuj_logi_nie_istniejacych_serwerow("acp_serwery_hlstats_top");
echo $cron->optm_kasuj_logi_nie_istniejacych_serwerow("acp_serwery_gosetti");

echo $cron->optm_kasuj_logi_nie_istniejacych_serwerow_cache(30, 1);

//
// Optymalizacja baz danych hlstats oraz sourcebans
//
if(strtotime($acp_system['hlx_optymalize_last'])< (time() - $acp_system['hlx_optymalize_time']) && $acp_system['hlx_optymalize_time'] != '0'){
  $optymalizacja_hlx = $api->api_11_2020('hls', $config['site'], $acp_system['api_hlx_host'], $acp_system['api_hlx_db'], $acp_system['api_hlx_user'], $acp_system['api_hlx_pass'], "&xx=optymalize_all_tables", "IN");
  $cron->CRON_data_wykonania('hlx_optymalize_last');
}
if(strtotime($acp_system['sb_optymalize_last'])< (time() - $acp_system['sb_optymalize_time']) && $acp_system['sb_optymalize_time'] != '0'){
  $optymalizacja_sb = $api->api_11_2020('sb', $config['site'], $acp_system['api_sb_host'], $acp_system['api_sb_db'], $acp_system['api_sb_user'], $acp_system['api_sb_pass'], "&xx=optymalize_all_tables", "IN");
  $cron->CRON_data_wykonania('sb_optymalize_last');
}

?>
