<?
require_once('API.php');

switch ($x) {
  case 'sb':
    switch ($xx) {
      case 'admins':
        $dane = all("SELECT `aid`, `user`, `authid`, `immunity`, `srv_group`, `srv_flags` FROM `".$srv."_admins` WHERE `authid` != '' AND `aid` != '' AND `user` != 'CONSOLE' ORDER BY FIELD(`srv_group`, 'Opiekun', 'Starszy Admin', 'Admin', 'Legenda', '')");
        break;
      case 'admins_list':
        $dane = all("SELECT `aid`, `user`, `authid`, `srv_group` FROM `".$srv."_admins` WHERE `authid` != '' AND `aid` != '' AND `user` != 'CONSOLE' AND `srv_group` NOT IN ('', 'Legenda', 'VIP', 'Weteran') ORDER BY FIELD(`srv_group`, 'Opiekun', 'Starszy Admin', 'Admin')");
        break;
      case 'groups':
        $dane = all("SELECT * FROM `".$srv."_srvgroups` ORDER BY `immunity` ASC");
        break;
      case 'serwers':
        $dane = all("SELECT * FROM `".$srv."_servers`");
        break;
      case 'last_admin':
        $dane = one("SELECT `auto_increment` FROM INFORMATION_SCHEMA.TABLES WHERE table_name = '".$srv."_admins'");
        break;
      case 'raport_opiekuna':
        $dane->liczba_ban = one("SELECT COUNT(`bid`) FROM `".$srv."_bans`");
        $dane->liczba_mute = one("SELECT COUNT(`bid`) FROM `".$srv."_comms` WHERE `type` = 1");
        $dane->liczba_gag = one("SELECT COUNT(`bid`) FROM `".$srv."_comms` WHERE `type` = 2");
        $dane->liczba_unban = one("SELECT COUNT(`bid`) FROM `".$srv."_bans` WHERE `RemovedBy` IS NOT NULL AND `RemovedOn` IS NOT NULL");
        $dane->liczba_unmute = one("SELECT COUNT(`bid`) FROM `".$srv."_comms` WHERE `type` = 2 AND `RemovedBy` IS NOT NULL AND `RemovedOn` IS NOT NULL");
        $dane->liczba_ungag = one("SELECT COUNT(`bid`) FROM `".$srv."_comms` WHERE `type` = 2 AND `RemovedBy` IS NOT NULL AND `RemovedOn` IS NOT NULL");
        break;

      default:
        $dane->blad = 'sb: Brak Wybranej Wartości GET';
        break;
    }
    break;
  case 'hls':
    switch ($xx) {
      case 'top50':
        $dane = all("
        SELECT
          SQL_CALC_FOUND_ROWS
          hlstats_Players.playerId,
          hlstats_Players.connection_time,
                    unhex(replace(hex(hlstats_Players.lastName), 'E280AE', '')) as lastName,
          hlstats_Players.flag,
          hlstats_Players.country,
          hlstats_Players.skill,
          hlstats_Players.kills,
          hlstats_Players.deaths,
          hlstats_Players.last_skill_change,
          ROUND(hlstats_Players.kills/(IF(hlstats_Players.deaths=0, 1, hlstats_Players.deaths)), 2) AS kpd,
          hlstats_Players.headshots,
          ROUND(hlstats_Players.headshots/(IF(hlstats_Players.kills=0, 1, hlstats_Players.kills)), 2) AS hpk,
          IFNULL(ROUND((hlstats_Players.hits / hlstats_Players.shots * 100), 1), 0) AS acc,
          activity,
          (SELECT uniqueId FROM hlstats_PlayerUniqueIds WHERE playerId = hlstats_Players.playerId LIMIT 1) AS steam
        FROM
          hlstats_Players
        WHERE
          hlstats_Players.game = '$srv'
          AND hlstats_Players.hideranking = 0
        ORDER BY skill DESC
        LIMIT 50
        ");
        break;
      case 'czas_polaczenia':
        $steam = substr($y, 8);
        $player_id = one("SELECT `playerId` FROM `hlstats_PlayerUniqueIds` WHERE `game` = '$srv' AND `uniqueId` = '$steam' LIMIT 1");
        $dane = one("SELECT `connection_time` FROM `hlstats_Players` WHERE `playerId` = $player_id");
        break;
      case 'say':
        $dane->serwer = row("SELECT hlstats_Servers.serverId, hlstats_Servers.name FROM hlstats_Servers WHERE hlstats_Servers.game='$srv' LIMIT 1");
        $dane->say = all("SELECT *, `playerId` AS `id_playera`, (SELECT `lastName` FROM `hlstats_Players` WHERE `playerId` = `id_playera` LIMIT 1) AS `name`, (SELECT `uniqueId` FROM `hlstats_PlayerUniqueIds` WHERE `playerId` = `id_playera` LIMIT 1) AS `steam` FROM `hlstats_Events_Chat` WHERE `serverId` = ".$dane->serwer->serverId." ORDER BY hlstats_Events_Chat.id DESC LIMIT 100");
        break;
      case 'ilosc_graczy':
        $game = (int)$_GET['game'];
        if(empty($game)) { $game = $srv; }
        $dane = one("SELECT `players` FROM `hlstats_Servers` WHERE `game` = $game ");
        break;
      default:
        $dane->blad = 'hls: Brak Wybranej Wartości GET';
        break;
    }
    break;

  default:
    $dane->blad = 'Brak Wybranego Serwisu';
    break;
}


if(!is_null($cache_live)){
  api_update_file($cache_filename, $dane);
}
else {
  echo json_encode($dane);
}
?>
