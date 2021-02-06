<?
require_once('API.php');
// talica do in
$in = (isset($_GET['in'])) ? $_GET['in'] : null;

switch ($x) {
  case 'sb':
    switch ($xx) {
      case 'dodaj_admina':
        $in = json_decode($in);

        $sb = new stdClass();
        $sb->serwer_id = one("SELECT `sid` FROM `".$srv."_servers` WHERE `ip` = '$in->s_ip' AND `port` = '$in->s_port' LIMIT 1");
        if(empty($sb->serwer_id)) { $dane->blad = "sb: Nie znaleziono serwera.."; break;}

        $dane->query = insert($srv."_admins", array('`user`' => "$in->dane_ranga_tekst - $in->dane_nick", '`authid`' => "$in->dane_steam", '`password`' => "", '`gid`' => "-1", '`email`' => "",'`extraflags`' => "0",'`srv_group`' => "$in->dane_ranga_tekst", '`immunity`' => "0"));
        $last_insert = one("SELECT LAST_INSERT_ID()");
        $dane->query = insert($srv."_admins_servers_groups", array('`admin_id`' => "$last_insert", '`group_id`' => $in->dane_ranga_id, '`srv_group_id`' => "-1", '`server_id`' => "$sb->serwer_id"));
        if(is_null($dane->query)){
          $dane->sukcess = "Dodano poprawnie $in->dane_nick (STEAM: $in->dane_steam) na rangę $in->dane_ranga_tekst";
        }
        break;
      case 'edytuj_admina':
        $in = json_decode($in);

        $sb = new stdClass();
        $sb->serwer_id = one("SELECT `sid` FROM `".$srv."_servers` WHERE `ip` = '$in->s_ip' AND `port` = '$in->s_port' LIMIT 1");
        if(empty($sb->serwer_id)) { $dane->blad = "sb: Nie znaleziono serwera.."; break;}

        if($in->dane_ranga_id == 0){
          query("UPDATE `".$srv."_admins` SET `user` = '$in->dane_nick', `authid` = '$in->dane_steam' WHERE `aid` = $in->aid LIMIT 1;");
        }
        else {
          query("UPDATE `".$srv."_admins` SET `user` = '$in->dane_nick', `authid` = '$in->dane_steam', `srv_group` = '$in->dane_ranga_tekst' WHERE `aid` = $in->aid LIMIT 1;");
          query("UPDATE `".$srv."_admins_servers_groups` SET `group_id` = '$in->dane_ranga_id' WHERE `id` = $in->aid;");
        }

        if(is_null($dane->query)){
          $dane->sukcess = "Zedytowano poprawnie $in->dane_nick (STEAM: $in->dane_steam)";
        }
        break;
      case 'usun_admina':
        $in = json_decode($in);
        $admin = row("SELECT `user`, `authid` FROM `".$srv."_admins` WHERE `aid` = $in->aid LIMIT 1");

        query("DELETE FROM `".$srv."_admins` WHERE `aid` = $in->aid LIMIT 1;");
        query("DELETE FROM `".$srv."_admins_servers_groups` WHERE `id` = $in->aid LIMIT 1;");

        if(is_null($dane->query)){
          $dane->sukcess = "Usunięto poprawnie $admin->user (STEAM: $admin->authid)";
        }
        break;
      case 'degradacja_admina':
        $in = json_decode($in);
        $admin = row("SELECT `user`, `authid` FROM `".$srv."_admins` WHERE `aid` = $in->aid LIMIT 1");
        $admin->user_ex = explode($admin->user, ' - ');

        query("UPDATE `".$srv."_admins` SET `user` = 'Degradacja - ".$admin->user_ex['1']."', `authid` = '$in->dane_steam', `srv_group` = '' WHERE `aid` = $in->aid LIMIT 1;");
        query("DELETE FROM `".$srv."_admins_servers_groups` WHERE `id` = $in->aid LIMIT 1;");

        if(is_null($dane->query)){
          $dane->sukcess = "Zdegradowano $admin->user (STEAM: $admin->authid)";
        }
        break;
      case 'rezygnacja_admina':
        $in = json_decode($in);
        $admin = row("SELECT `user`, `authid` FROM `".$srv."_admins` WHERE `aid` = $in->aid LIMIT 1");
        $admin->user_ex = explode($admin->user, ' - ');

        query("UPDATE `".$srv."_admins` SET `user` = 'Rezygnacja - ".$admin->user_ex['1']."', `authid` = '$in->dane_steam', `srv_group` = '' WHERE `aid` = $in->aid LIMIT 1;");
        query("DELETE FROM `".$srv."_admins_servers_groups` WHERE `id` = $in->aid LIMIT 1;");

        if(is_null($dane->query)){
          $dane->sukcess = "Oznaczono Rezygnację $admin->user (STEAM: $admin->authid)";
        }
        break;

      case 'optymalize_all_tables':
        $dane = all("SHOW TABLES");
        foreach ($dane as $db => $tablename) {
          query("OPTIMIZE TABLE '".$tablename."'");
        }
        break;
      default:
        $dane->blad = 'sb: Brak Wybranej Wartości IN';
        break;
    }
    break;
  case 'hls':
    switch ($xx) {
      case 'optymalize_all_tables':
        $dane = all("SHOW TABLES");
        foreach ($dane as $db => $tablename) {
          query("OPTIMIZE TABLE '".$tablename."'");
        }
        break;

      default:
        $dane->blad = 'hls: Brak Wybranej Wartości IN';
        break;
    }
    break;

  default:
    $dane->blad = 'Brak Wybranego Serwisu';
    break;
}

echo json_encode($dane);
?>
