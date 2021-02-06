<?php
class DanePubliczneMgr{
  public function copy_pase($tekst, $ile){
    $kom = "/* $tekst  */>
    ";
    $retru1n = '';
    for ($i = 1; $i <= $ile; $i++) {
        $retru1n .= $kom;
    }
    return $retru1n;
  }
  public function social(){
    $tekst = '<div class="row">
      <div class="col-lg-12">
        <div class="text-center">';
        $media_array_icon = array('media_fb' => 'facebook', 'media_insta' => 'instagram', 'media_steam' => 'steam', 'media_yt' => 'youtube');
        $media_array_bt = array('media_fb' => 'facebook', 'media_insta' => 'instagram', 'media_steam' => 'github', 'media_yt' => 'google');
        $media = all("SELECT * FROM `acp_system` WHERE `conf_name` LIKE '%media%'");
        foreach ($media as $value):
          if(!empty($value->conf_value)):
            $tekst .= '<a href="'.$value->conf_value.'" target="_blank" class="btn btn-'.$media_array_bt[$value->conf_name].'"><i class="fa fa-'.$media_array_icon[$value->conf_name].'"></i></a>';
          endif;
        endforeach;
    $tekst .= '</div>
      </div>
    </div>';
    return $tekst;
  }
  public function stopka($nazwa, $wersja){
    $tekst = '<div class="row" style="margin-top: 10px;">
      <div class="col-lg-12 text-center text-lg-left">
        <p style="color: #fff;"><b>'.$nazwa.' | ACP</b>  </br>Version '.$wersja.'</p>
      </div>
    </div>';
    return $tekst;
  }
  public function menu($x, $www, $nazwa){
    $system->on = one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'danepub_menu_on' LIMIT 1");
    if($system->on == 0){
      return;
    }

    $system->menu = json_decode(one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'danepub_menu_list' LIMIT 1"));
    $tekst = '<nav class="navbar navbar-inverse">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="'.$www.'">'.$nazwa.' | ACP</a>
        </div>
        <ul class="nav navbar-nav">';

          foreach ($system->menu as $key => $value) {
            $value->link_act = explode("?x=", $value->link);
            $active = ($x == $value->link_act[1]) ? 'class="active"' : '';
            $tekst .= '<li '.$active.'><a href="'.$value->link.'" '.$value->blank.'>'.$value->page.'</a></li>';
          }
        $tekst .= '</ul>
      </div>
    </nav>';
    return $tekst;
  }
  public function serwer_list($x, $serwer_on=1){
    if($serwer_on == 1){}
    $dane = all("SELECT `serwer_id`, `status`, `prefix_sb`, `istotnosc`, `game`, `ip`, `port`, `nazwa`, `mod`, `graczy`, `max_graczy`, `boty`, `mapa` FROM `acp_serwery` WHERE `serwer_on` = 1 AND `test_serwer` = '0' ORDER BY `istotnosc` ASC");

    return $dane;
  }
  public function serwer_czy_istnieje($id){
    $czy_istnieje_serwer = one("SELECT `serwer_id` FROM `acp_serwery` WHERE `serwer_id` = $id LIMIT 1");
    if(!$czy_istnieje_serwer){
      $_SESSION['msg'] = komunikaty("Taki serwer nie istenieje..", 3);
      header("Location: ?x=$_GET[x]");
    }
  }
  public function serwer_banner($id){
    $obrazek = "./www/server_banner/$id.png";
    $obrazek = (file_exists($obrazek)) ? $obrazek : './www/server_banner/0.png';
    return $obrazek;
  }

  public function admin_list($id){
    $dane = one("SELECT `dane` FROM `acp_cache_api` WHERE `get` = 'serwer_id".$id."_admin' LIMIT 1;");
    $dane = json_decode($dane);
    return $dane;
  }

  public function changelog_list($id){
    $dane = all("SELECT *, `user` AS `user_id`, (SELECT `login` FROM `acp_users` WHERE `user` = `user_id` LIMIT 1) AS `user_name` FROM `acp_log_serwery` WHERE `serwer_id` = $id;");
    return $dane;
  }

  public function hlstats_top_list($id){
    $dane = all("SELECT `id`, `serwer_id` AS `srv_id`, `data`, DATE_ADD(`data`, INTERVAL -1 DAY) AS `new_data`, (SELECT `nazwa` FROM `acp_serwery` WHERE `serwer_id` = `srv_id` LIMIT 1) AS `nazwa` FROM `acp_serwery_hlstats_top` WHERE `serwer_id` = $id;");
    return $dane;
  }
  public function hlstats_top_details($id){
    $dane = one("SELECT `dane` FROM `acp_serwery_hlstats_top` WHERE `id` = $id;");
    $dane = json_decode(stripslashes($dane));
    return $dane;
  }
  public function hlstats_top_details_dane($id){
    $dane = row("SELECT `id`, `serwer_id` AS `srv_id`, (SELECT `nazwa` FROM `acp_serwery` WHERE `serwer_id` = `srv_id` LIMIT 1) AS `nazwa`, (SELECT `mod` FROM `acp_serwery` WHERE `serwer_id` = `srv_id` LIMIT 1) AS `mod`, DATE_ADD(`data`, INTERVAL -1 DAY) AS `data` FROM `acp_serwery_hlstats_top` WHERE `id` = $id LIMIT 1;");
    return $dane;
  }

  public function serwer_details($id){
    $dane = row("SELECT `game`, `ip`, `port`, `mod`, `status`, `nazwa`, `graczy`, `max_graczy`, `boty`, `tags`, `mapa`, `ser_a_jr`, `ser_a_opiekun`, `ser_a_copiekun` FROM `acp_serwery` WHERE `serwer_id` = $id;");

    if(!empty($dane->ser_a_jr)){
      $dane->junioradmin = row("SELECT `login`, `steam_login`, `steam_avatar`, `steam` FROM `acp_users` WHERE `user` = $dane->ser_a_jr LIMIT 1");
    }
    else {
      $dane->junioradmin = '<i>Brak danych</i>';
    }
    if(!empty($dane->ser_a_opiekun)){
      $dane->opiekun = row("SELECT `login`, `steam_login`, `steam_avatar`, `steam` FROM `acp_users` WHERE `user` = $dane->ser_a_opiekun LIMIT 1");
    }
    else {
      $dane->opiekun = '<i>Brak danych</i>';
    }
    if(!empty($dane->ser_a_copiekun)){
      $dane->zastepca = row("SELECT `login`, `steam_login`, `steam_avatar`, `steam` FROM `acp_users` WHERE `user` = $dane->ser_a_copiekun LIMIT 1");
    }
    else {
      $dane->zastepca = '<i>Brak danych</i>';
    }
    $dane->regulamin = row("SELECT * FROM `acp_serwery_regulamin` WHERE `serwer_id` = $id");
    $dane->mapa_grupa = one("SELECT `id` FROM `acp_serwery_mapy` WHERE `serwer_id` = $id LIMIT 1");
    $dane->mapa_id = one("SELECT `id` FROM `acp_serwery_mapy_det` WHERE `mapy_id` = $dane->mapa_grupa AND `nazwa` = '$dane->mapa' LIMIT 1");
    $dane->mapa_img = one("SELECT `imgur_url` FROM `acp_serwery_mapy_img` WHERE `id_mapy` = $dane->mapa_id");

    $dane->graczy_live = one("SELECT `dane` FROM `acp_cache_api` WHERE `get` = 'serwer_id$id' LIMIT 1; ");
    $dane->graczy_live = json_decode($dane->graczy_live);

    $dane->hlstats = row("SELECT `data`, `hls_graczy`, `hls_nowych_graczy`, `hls_zabojstw`, `hls_nowych_zabojstw`, `hls_hs`, `hls_nowych_hs` FROM `acp_serwery_hlstats` WHERE `serwer_id` = $id LIMIT 1");
    $dane->changelog = all("SELECT * FROM `acp_log_serwery` WHERE `serwer_id` = $id LIMIT 5");
    $dane->logs = all("SELECT `graczy`, `boty`, `sloty`, `data` FROM `acp_serwery_logs_hour` WHERE `serwer_id` = $id LIMIT 24");
    return $dane;
  }

}
?>
