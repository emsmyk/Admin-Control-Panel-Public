<?
class SourcebansMgr {
  public function dodaj_admina($serwer, $user, $dostep){
    if(uprawnienia($dostep, $user) == 0){
      return;
    }
    $from = post_to_stdclass();
    $from->steam = toSteamID($from->steam );
    $from->ranga_explode = explode(":", $from->ranga);

    $from->changelog = ($from->changelog == 'on') ? '1' : '0';
    $from->serwer_id_acp = (int)$from->serwer_id;
    $from->serwer_dane = row("SELECT `ip`, `port`, `mod`, `prefix_sb`  FROM `acp_serwery` WHERE `serwer_id` = $from->serwer_id_acp LIMIT 1");

    if(empty($from->nick)){
      $_SESSION['msg'] = komunikaty("Pole nick musi zostać wypełnione", 4);
      return;
    }
    if(empty($from->steam)){
      $_SESSION['msg'] = komunikaty("Pole STEAMID musi zostać wypełnione", 4);
      return;
    }
    if($from->changelog == 1){
      admin_log_srv($from->serwer_id_acp, $user, 0, "$from->nick (STEAM: $from->steam) został ".$from->ranga_explode[1]." na serwerze");
    }

    $in = new stdClass();
    $in->dane_nick = $from->nick;
    $in->dane_steam = $from->steam;
    $in->dane_ranga_tekst = $from->ranga_explode[1];
    $in->dane_ranga_id = $from->ranga_explode[0];
    $in->s_ip = $from->serwer_dane->ip;
    $in->s_port = $from->serwer_dane->port;
    $in = json_encode($in);

    $more = "&xx=dodaj_admina&srv=".$from->serwer_dane->prefix_sb."&in=$in";

    return $more;
  }
  public function edytuj_admina($serwer, $user, $dostep){
    if(uprawnienia($dostep, $user) == 0){
      return;
    }
    $from = post_to_stdclass();

    $from->ranga_explode = explode(":", $from->ranga);
    $from->serwer_id_acp = (int)$serwer;
    $from->serwer_dane = row("SELECT `ip`, `port`, `mod`, `prefix_sb`  FROM `acp_serwery` WHERE `serwer_id` = $from->serwer_id_acp LIMIT 1");

    if(empty($from->nick)){
      $_SESSION['msg'] = komunikaty("Pole nick musi zostać wypełnione", 4);
      return;
    }
    if(empty($from->steam)){
      $_SESSION['msg'] = komunikaty("Pole STEAMID musi zostać wypełnione", 4);
      return;
    }

    $in = new stdClass();
    $in->aid = $from->aid;
    $in->dane_nick = $from->nick;
    $in->dane_steam = $from->steam;
    $in->dane_ranga_tekst = $from->ranga_explode[1];
    $in->dane_ranga_id = $from->ranga_explode[0];
    $in->s_ip = $from->serwer_dane->ip;
    $in->s_port = $from->serwer_dane->port;
    $in = json_encode($in);

    $more = "&xx=edytuj_admina&srv=".$from->serwer_dane->prefix_sb."&in=$in";

    return $more;
  }
  public function usun_admina($serwer, $user, $dostep){
    if(uprawnienia($dostep, $user) == 0){
      return;
    }
    $from = post_to_stdclass();

    $from->serwer_id_acp = (int)$serwer;
    $from->serwer_dane = row("SELECT `ip`, `port`, `mod`, `prefix_sb`  FROM `acp_serwery` WHERE `serwer_id` = $from->serwer_id_acp LIMIT 1");

    $in = new stdClass();
    $in->aid = $from->aid;
    $in = json_encode($in);

    $more = "&xx=usun_admina&srv=".$from->serwer_dane->prefix_sb."&in=$in";

    return $more;
  }
  public function degradacja_admina($serwer, $user, $dostep){
    if(uprawnienia($dostep, $user) == 0){
      return;
    }
    $from = post_to_stdclass();
    $from->serwer_id_acp = (int)$serwer;
    $from->serwer_dane = row("SELECT `ip`, `port`, `mod`, `prefix_sb`  FROM `acp_serwery` WHERE `serwer_id` = $from->serwer_id_acp LIMIT 1");

    $in = new stdClass();
    $in->aid = $from->aid;
    $in = json_encode($in);

    $more = "&xx=degradacja_admina&srv=".$from->serwer_dane->prefix_sb."&in=$in";

    return $more;
  }
  public function rezygnacja_admina($serwer, $user, $dostep){
    if(uprawnienia($dostep, $user) == 0){
      return;
    }
    $from = post_to_stdclass();
    $from->serwer_id_acp = (int)$serwer;
    $from->serwer_dane = row("SELECT `ip`, `port`, `mod`, `prefix_sb`  FROM `acp_serwery` WHERE `serwer_id` = $from->serwer_id_acp LIMIT 1");

    $in = new stdClass();
    $in->aid = $from->aid;
    $in = json_encode($in);

    $more = "&xx=rezygnacja_admina&srv=".$from->serwer_dane->prefix_sb."&in=$in";

    return $more;
  }
}
?>
