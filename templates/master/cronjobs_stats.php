<?
require "functions/pobieranie/simple_html_dom.php";

$cron = getClass("Cronjobs");


$serwery_q = all("SELECT `serwer_id` FROM `acp_serwery` WHERE `serwer_on` = 1;");
// Statystki raz na godzinę
foreach($serwery_q as $serwery){
  if(strtotime($acp_system['acp_cron_stats_date_hour']) < strtotime(date("Y-m-d H:i"))) {
    echo $cron->stats_przelicz($serwery->serwer_id, 'stats_hour');
    query("UPDATE `acp_system` SET `conf_value` = '".date("Y-m-d H:i:s")."' WHERE `conf_name` = 'acp_cron_stats_date_hour';");
  }
}
// Statystyki raz na dzień
foreach($serwery_q as $serwery){
  if(strtotime($acp_system['acp_cron_stats_date_day']) != strtotime(date("Y-m-d"))) {
    echo $cron->stats_przelicz($serwery->serwer_id, 'stats_day');
    query("UPDATE `acp_system` SET `conf_value` = '".date("Y-m-d")."' WHERE `conf_name` = 'acp_cron_stats_date_day';");
  }
}
// Statystyki raz na miesiac
foreach($serwery_q as $serwery){
  if(strtotime($acp_system['acp_cron_stats_date_month']) < strtotime(date("Y-m"))) {
    echo $cron->stats_przelicz($serwery->serwer_id, 'stats_month');
    query("UPDATE `acp_system` SET `conf_value` = '".date("Y-m-d")."' WHERE `conf_name` = 'acp_cron_stats_date_month';");
  }
}

//GoSetti
foreach($serwery_q as $serwery){
  if(strtotime($acp_system['acp_cron_stats_gosetti']) != strtotime(date("Y-m-d"))) {
    echo $cron->stats_gosetti($serwery->serwer_id);
    query("UPDATE `acp_system` SET `conf_value` = '".date("Y-m-d")."' WHERE `conf_name` = 'acp_cron_stats_gosetti';");
  }
}

//hlstats dane graczy i top 50
foreach ($serwery_q as $serwery) {
  if(strtotime($acp_system['acp_cron_stats_hlstats']) != strtotime(date("Y-m-d"))) {
    echo $cron->stats_hlstats($serwery->serwer_id);
    echo $cron->stats_hlstats_top50($config['site']."/api/API_GET.php?x=hls&h=".$acp_system['api_hlx_host']."&u=".$acp_system['api_hlx_user']."&p=".$acp_system['api_hlx_pass']."&db=".$acp_system['api_hlx_db']."&xx=top50", $serwery->serwer_id);

    query("UPDATE `acp_system` SET `conf_value` = '".date("Y-m-d")."' WHERE `conf_name` = 'acp_cron_stats_hlstats';");
  }
}
?>
