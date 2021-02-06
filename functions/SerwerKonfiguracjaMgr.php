<?
class SerwerKonfiguracjaMgr{

  //
  // Serwery Dane o zdalnych pracach
  //

  public function kolejna_aktualizacja($xx){
    $one = one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` LIKE 'cron_$xx'");
    $ostatnia_aktualizacja = $one;
    $one2 = one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` LIKE 'time_$xx'");
    $one = date("Y-m-d H:i:s", (strtotime(date($one)) + $one2));
    if($one2 == 0) {
      return 'Moduł został wyłączony..';
    }
    if($ostatnia_aktualizacja == '2000-00-00 00:00:00'){
      return 'Wymuszona wcześniejsza aktualizacja plików, odczekaj maksymalnie jedną minutę aby została wykonana..';
    }
    $mozliwosci = array("0" => "Wyłączony", "60" => "60 sekund", "1800" => "30 minut", "3600" => "1 godzinę", "7200" => "2 godziny", "14400" => "4 godziny", "43200" => "12 godzin", "86400" => "1 dobę");
    $one2 = $mozliwosci[$one2];

    $kolejna_aktualizacja = "Będzie za $one2, czyli $one";
    return $kolejna_aktualizacja;
  }
  public function ostatnia_aktualizacja($xx){
    $one = one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` LIKE 'cron_$xx'");
    if($one == '2000-00-00 00:00:00'){
      $tekst = 'Wymuszona wcześniejsza aktualizacja plików';
    }
    else {
      $tekst = czas_relatywny($one);
    }

    $tekst .= "<p><a href='?x=$_GET[x]&xx=$xx&wymus_aktualizacje=1'><button type='button' class='btn btn-block btn-default btn-xs'>Wymuś aktualizację</button></a></p>";
    return $tekst;
  }
  public function serwery_aktualizowane(){
    $xx = $_GET['xx'];

    $ser_list_q = all("SELECT `cronjobs`, `serwer_id`, `nazwa`, `mod`, `rangi`, `mapy`, `bazy`, `reklamy`, `hextags`, `help_menu` FROM `acp_serwery` LEFT JOIN (`acp_serwery_cronjobs`) ON `acp_serwery`.`serwer_id` = `acp_serwery_cronjobs`.`serwer` WHERE `serwer_on` = 1");
    $tekst = '<ul class="list-group">';
    $tekst .= '<h2>Serwery:</h2>';
    foreach ($ser_list_q as $ser_list) {
      $tekst .= "<a href='?x=serwery_det&serwer_id=$ser_list->serwer_id'><li class='list-group-item list-group-item-dark'> <b>[$ser_list->mod]</b> $ser_list->nazwa</li></a>";
      if($ser_list->cronjobs == 0){
        $tekst .= "<p>Aktualizacje na tym serwerze zostały wyłączone całkowicie</p>";
      }
      else {
        $ser_list->rangi = ($ser_list->rangi) ?: 0;
        $ser_list->reklamy = ($ser_list->reklamy) ?: 0;
        $ser_list->mapy = ($ser_list->mapy) ?: 0;
        $ser_list->bazy = ($ser_list->bazy) ?: 0;
        $ser_list->hextags = ($ser_list->hextags) ?: 0;
        $ser_list->help_menu = ($ser_list->help_menu) ?: 0;

        $crony = array();
        $ser_list->rangi = (1 == $ser_list->rangi) ? array_push($crony, 'rangi') : 'Nie';
        $ser_list->reklamy = (1 == $ser_list->reklamy) ? array_push($crony, 'reklamy') : 'Nie';
        $ser_list->mapy = (1 == $ser_list->mapy) ? array_push($crony, 'mapy') : 'Nie';
        $ser_list->bazy = (1 == $ser_list->bazy) ? array_push($crony, 'baza') : 'Nie';
        $ser_list->hextags = (1 == $ser_list->hextags) ? array_push($crony, 'hextags') : 'Nie';
        $ser_list->help_menu = (1 == $ser_list->help_menu) ? array_push($crony, 'help_menu') : 'Nie';

        if(in_array($xx, $crony)){
          $tekst .= "<p>Włączona (ON)</p>";
        }
        else {
          $tekst .= "<p>Wyłączona (OFF)</p>";
        }
      }
    }
    $tekst .='</ul>';

    return $tekst;
  }

  public function wymus_aktualizacje($xx, $admin, $dostep) {
    if(uprawnienia($dostep, $admin) == 0){
      return;
    }
    query("UPDATE `acp_system` SET `conf_value` = '2000-00-00 00:00:00' WHERE `conf_name` LIKE 'cron_$xx' LIMIT 1;");

    $_SESSION['msg'] = komunikaty("Wymuszono wcześniejsza aktualizację. Odczekaj max 1 minutę aby została wykonana.", 1);
    admin_log($admin, "Wymuszono aktualizację modułu Serwery Konfiguracja [$xx]");
  }

  //
  //  Konfiguracja: Rangi
  //

  public function rangi_dodaj($id, $admin, $dostep){
    if(uprawnienia($dostep, $admin) == 0){
      return;
    }
    $from = post_to_stdclass();

    if(empty($from->n_tag_tabela) || empty($from->n_tag_say) || empty($from->n_flags)) {
      $_SESSION['msg'] = komunikaty("Pola takie jak Tag Tabela, Tag Say oraz Flagi nie mogą być puste..", 2);
      return;
    }
    query("INSERT INTO `acp_serwery_rangi` ( `serwer_id`, `flags`, `tag_tabela`, `tag_say`, `tag_say_kolor`, `nick_say_kolor`, `istotnosc` ) VALUES ($from->n_serwer, '$from->n_flags', '$from->n_tag_tabela', '$from->n_tag_say', '$from->n_kolor_tag', '$from->n_kolor_nick', '$from->n_istotnosc');");
    $last_insert = one("SELECT LAST_INSERT_ID()");
    $_SESSION['msg'] = komunikaty("Dodano rangę $from->n_tag_tabela (ID: $last_insert) [Flagi: $from->n_flags]", 1);
    admin_log($admin, "Dodano rangę $from->n_tag_tabela (ID: $last_insert) [Flagi: $from->n_flags]");
  }
  public function rangi_edytuj($id, $admin, $dostep){
    if(uprawnienia($dostep, $admin) == 0){
      return;
    }
    $from = post_to_stdclass();

    query("UPDATE `acp_serwery_rangi` SET `serwer_id` = $from->e_serwerid, `flags` = '$from->e_flags', `tag_tabela` = '$from->e_tagtabela', `tag_say` = '$from->e_tagsay', `tag_say_kolor` = '$from->e_kolorsay', `nick_say_kolor` = '$e_kolornick', `istotnosc` = $from->e_istotnosc, `komentarz` = '$from->e_komentarz' WHERE `id` = $from->id;");
    $_SESSION['msg'] = komunikaty("Zaktualizowano Rangę $from->e_tagtabela (ID: $from->id) [Flagi: $from->e_flags]", 1);
    admin_log($admin, "Zaktualizowano rangę $from->e_tagtabela (ID: $from->id) [Flagi: $from->e_flags]");
  }
  public function rangi_usun($id, $admin, $dostep){
    if(uprawnienia($dostep, $admin) == 0){
      return;
    }
    $row = row("SELECT * FROM `acp_serwery_rangi` WHERE `id` = $id LIMIT 1");
		query("DELETE FROM `acp_serwery_rangi` WHERE `id` = $id LIMIT 1;");
		$_SESSION['msg'] = komunikaty("Usunięto rangę $row->tag_tabela (ID: $id) [Flagi: $row->flags]", 1);
    admin_log($admin, "Usunięto rangę $row->tag_tabela (ID: $id) [Flagi: $row->flags]");
  }

  //
  //  Konfiguracja: HexTags
  //

  public function hextags_dodaj($id, $admin, $dostep){
    if(uprawnienia($dostep, $admin) == 0){
      return;
    }
    $from = post_to_stdclass();

    $najwieksza_wartosc = one("SELECT `istotnosc` FROM `acp_serwery_hextags` ORDER BY `istotnosc` DESC LIMIT 1");
    $najwieksza_wartosc = (int)$najwieksza_wartosc +1;

    if(empty($from->n_tag_tabela) || empty($from->n_tag_say)) {
      $_SESSION['msg'] = komunikaty("Pola takie jak Tag Tabela, Tag Say oraz Flagi nie mogą być puste..", 2);
      return;
    }

    query("INSERT INTO `acp_serwery_hextags` (`serwer_id`, `hextags`, `ScoreTag`, `TagColor`, `ChatTag`, `ChatColor`, `NameColor`, `Force`, `istotnosc`, `komentarz`) VALUES ($from->n_serwer, '$from->n_typ', '$from->n_tag_tabela', '$from->n_kolor_tag_tag', '$from->n_tag_say', '$from->n_kolor_tag', '$from->n_kolor_nick', $from->n_force, $najwieksza_wartosc, '$from->n_komentarz'); ");
    $last_insert = one("SELECT LAST_INSERT_ID()");
    $_SESSION['msg'] = komunikaty("Dodano HexTags $from->n_typ (ID: $last_insert) [Ranga: $from->n_tag_tabela]", 1);
    admin_log($admin, "Dodano HexTags $from->n_typ (ID: $last_insert) [Ranga: $from->n_tag_tabela]", "?x=serwery_konfiguracja&xx=hextags&edycja=$last_insert");
  }
  public function hextags_edytuj($id, $admin, $dostep){
    if(uprawnienia($dostep, $admin) == 0){
      return;
    }
    $from = post_to_stdclass();

    if($from->e_Force >= 2){
      $_SESSION['msg'] = komunikaty("Parametr Force może być tylko 1(TAK) lub 0 (NIE).", 3);
      return;
    }

    query("UPDATE `acp_serwery_hextags` SET `serwer_id` = $from->e_serwerid, `hextags` = '$from->e_hextags', `TagName` = '$from->e_TagName', `ScoreTag` = '$from->e_ScoreTag', `TagColor` = '$from->e_TagColor', `ChatTag` = '$from->e_ChatTag', `ChatColor` = '$from->e_ChatColor', `NameColor` = '$from->e_NameColor', `Force` = '$e_Force', `komentarz` = '$from->e_komentarz' WHERE `id` = $from->id; ");
    $_SESSION['msg'] = komunikaty("Zaktualizowano HexTags $from->e_hextags (ID: $from->id) [Ranga: $e_ScoreTag]", 1);
    admin_log($admin, "Zaktualizowano HexTags $from->e_hextags (ID: $from->id) [Ranga: $e_ScoreTag]", "?x=serwery_konfiguracja&xx=hextags&edycja=$from->id");
  }
  public function hextags_usun($id, $admin, $dostep){
    if(uprawnienia($dostep, $admin) == 0){
      return;
    }
    $row = row("SELECT * FROM `acp_serwery_hextags` WHERE `id` = $id LIMIT 1");
    query("DELETE FROM `acp_serwery_hextags` WHERE `id` = $id LIMIT 1;");
    $_SESSION['msg'] = komunikaty("Usunięto HexTags $row->hextags (ID: $id) [Ranga: $row->ScoreTag]", 1);
    admin_log($admin, "Usunięto HexTags $row->hextags (ID: $id) [Ranga: $row->ScoreTag]", "?x=serwery_konfiguracja&xx=hextags");
  }



  public function kolejnosc($kierunek, $id, $kolumna, $tabela, $admin, $dostep){
    if(uprawnienia($dostep, $admin) == 0){
      return;
    }
    $id = (int)$id;

    switch ($kierunek) {
      case 'down':
        $poz->aktualna = one("SELECT `$kolumna` FROM `$tabela` WHERE `id` = $id LIMIT 1");
        $poz->nowa = (int)$poz->aktualna - 1;
        $poz->aktualnie_zajmuje = one("SELECT `id` FROM `$tabela` WHERE `$kolumna` = $poz->nowa LIMIT 1");
        if($poz->nowa <= 0){
          return;
        }
        query("UPDATE `$tabela` SET `$kolumna` = $poz->nowa WHERE `id` = $id LIMIT 1");
        query("UPDATE `$tabela` SET `$kolumna` = $poz->aktualna WHERE `id` = $poz->aktualnie_zajmuje LIMIT 1");
        break;

      case 'up':
        $poz->aktualna = one("SELECT `$kolumna` FROM `$tabela` WHERE `id` = $id LIMIT 1");
        $poz->nowa = (int)$poz->aktualna + 1;
        $poz->aktualnie_zajmuje = one("SELECT `id` FROM `$tabela` WHERE `$kolumna` = $poz->nowa LIMIT 1");

        $poz->najwieksza = one("SELECT `$kolumna` FROM `$tabela` ORDER BY `$kolumna` DESC LIMIT 1");
        if($poz->nowa > $poz->najwieksza){
          return;
        }
        query("UPDATE `$tabela` SET `$kolumna` = $poz->nowa WHERE `id` = $id LIMIT 1");
        query("UPDATE `$tabela` SET `$kolumna` = $poz->aktualna WHERE `id` = $poz->aktualnie_zajmuje LIMIT 1");
        break;
    }
  }



  //
  // Konfiguracja: Reklamy
  //
  public function reklamy_dodaj($id, $admin, $dostep){
    if(uprawnienia($dostep, $admin) == 0){
      return;
    }
    $from = post_to_stdclass();

    if(empty($from->n_tekst)) {
      $_SESSION['msg'] = komunikaty("Pole Tekst Reklamy nie może być puste..", 2);
      return;
    }
    query("INSERT INTO `acp_serwery_reklamy` ( `serwer_id` , `tekst` , `gdzie` ) VALUES   ( $from->n_serwer, '$from->n_tekst', '$from->n_gdzie' );");
    $last_insert = one("SELECT LAST_INSERT_ID()");
    $_SESSION['msg'] = komunikaty("Dodano reklamę: $from->n_tekst (ID: $last_insert)", 1);
    admin_log($admin, "Dodano reklamę: $from->n_tekst (ID: $last_insert)", "?x=serwery_konfiguracja&xx=reklamy&edycja=$last_insert");
  }
  public function reklamy_edytuj($id, $admin, $dostep){
    if(uprawnienia($dostep, $admin) == 0){
      return;
    }
    $from = post_to_stdclass();

    query("UPDATE `acp_serwery_reklamy` SET `serwer_id` = '$from->e_serwerid', `tekst` = '$from->e_tekst', `gdzie` = '$from->e_gdzie', `czasowa` = '$from->e_gdze_czasowaie', `czasowa_end` = '$from->e_czasowa_end', `zakres` = '$from->e_czasowa_end', `zakres_start` = '$from->e_zakres_start', `zakres_koniec` = '$from->e_zakres_koniec' WHERE `id` = $from->id;");
		$_SESSION['msg'] = komunikaty("Zaktualizowano reklamę: $from->e_tekst (ID: $from->id)", 1);
    admin_log($admin, "Zaktualizowano reklamę: $from->e_tekst (ID: $from->id)", "?x=serwery_konfiguracja&xx=reklamy&edycja=$from->id");
  }
  public function reklamy_usun($id, $admin, $dostep){
    if(uprawnienia($dostep, $admin) == 0){
      return;
    }
    query("DELETE FROM `acp_serwery_reklamy` WHERE `id` = $id LIMIT 1;");
    $_SESSION['msg'] = komunikaty("Usunięto reklamę ID: $id", 1);
    admin_log($admin, "Usunięto reklamę ID: $id", "?x=serwery_konfiguracja&xx=reklamy");
  }

  //
  // Konfiguracja: Mapy
  //
  public function mapy_mapa_dodaj($id, $admin, $dostep){
    if(uprawnienia($dostep, $admin) == 0){
      return;
    }
    $from = post_to_stdclass();

    if(empty($from->mapy_nazwa)) {
      $_SESSION['msg'] = komunikaty("Nazwa mapy nie może być pusta", 2);
      return;
    }
    query("INSERT INTO `acp_serwery_mapy_det` ( `mapy_id` , `nazwa`, `display` ) VALUES ( $from->id, '$from->mapy_nazwa', '$from->mapy_display' );");
    $last_insert = one("SELECT LAST_INSERT_ID()");
    $_SESSION['msg'] = komunikaty("Dodano mapę: $from->mapy_nazwa (ID: $last_insert) do grupy map $from->nazwa_grupy ($from->id)", 1);
    admin_log($admin, "Dodano mapę: $from->mapy_nazwa (ID: $last_insert) do grupy map $from->nazwa_grupy ($from->id)", "?x=serwery_konfiguracja&xx=mapy");
  }
  public function mapy_mapa_zapisz($id, $admin, $dostep){
    if(uprawnienia($dostep, $admin) == 0){
      return;
    }
    $from = post_to_stdclass();

    if(empty($from->mapy_nazwa)) {
      $_SESSION['msg'] = komunikaty("Nazwa mapy nie może być pusta", 2);
      return;
    }
    query("UPDATE `acp_serwery_mapy_det` SET `nazwa` = '$from->mapy_nazwa', `display` = '$from->mapy_display ' WHERE `id` = $from->id;");
    $_SESSION['msg'] = komunikaty("Zedytowano mapę: $from->mapy_nazwa (ID: $from->id)", 1);
    admin_log($admin, "Zedytowano mapę: $from->mapy_nazwa (ID: $from->id)", "?x=serwery_konfiguracja&xx=mapy");
  }
  public function mapy_mapa_usun($id, $admin, $dostep){
    if(uprawnienia($dostep, $admin) == 0){
      return;
    }
    $from = post_to_stdclass();

    query("DELETE FROM `acp_serwery_mapy_det` WHERE `id` = $from->id; ");
    $_SESSION['msg'] = komunikaty("Usunięto mapę: $from->mapy_nazwa (ID: $from->id)", 1);
    admin_log($admin, "Usunięto mapę: $from->mapy_nazwa (ID: $from->id)", "?x=serwery_konfiguracja&xx=mapy");
  }
  public function mapy_mapa_detale($id, $admin, $dostep){
    if(uprawnienia($dostep, $admin) == 0){
      return;
    }
    $from = post_to_stdclass();


    query("UPDATE `acp_serwery_mapy_det` SET `nazwa` = '$from->e_nazwa', `display` = '$from->e_display', `weight` = '$from->e_weight', `next_mapgroup` = '$from->e_next_mapgroup', `min_players` = '$from->e_min_players', `max_players` = '$from->e_max_players', `min_time` = '$from->e_min_time', `max_time` = '$from->e_max_time', `allow_every` = '$from->e_allow_every', `command` = '$from->e_command', `nominate_flags` = '$from->e_nominate_flags', `adminmenu_flags` = '$from->e_adminmenu_flag' WHERE `id` = $from->id; ");
    $_SESSION['msg'] = komunikaty("Zaktualizowano mapę $from->e_nazwa (ID: $from->id)", 1);
    admin_log($admin, "Zaktualizowano mapę $from->e_nazwa (ID: $from->id)", "?x=serwery_konfiguracja&xx=mapy");
  }
  public function mapy_grupa_dodaj($id, $admin, $dostep){
    if(uprawnienia($dostep, $admin) == 0){
      return;
    }
    $from = post_to_stdclass();

    if(empty($from->n_nazwa)) {
      $_SESSION['msg'] = komunikaty("Nazwa grupy map nie może być pusta", 2);
      return;
    }
    query("INSERT INTO `acp_serwery_mapy` ( `serwer_id` , `nazwa` ) VALUES ( $from->n_serwer, '$from->n_nazwa' );");
    $last_insert = one("SELECT LAST_INSERT_ID()");
    $_SESSION['msg'] = komunikaty("Dodano grupę map: $from->n_nazwa (ID: $last_insert)", 1);
    admin_log($admin, "Dodano grupę map: $from->n_nazwa (ID: $last_insert)", "?x=serwery_konfiguracja&xx=mapy");
  }
  public function mapy_grupa_edytuj($id, $admin, $dostep){
    if(uprawnienia($dostep, $admin) == 0){
      return;
    }
    $from = post_to_stdclass();

    query("UPDATE `acp_serwery_mapy` SET `serwer_id` = '$from->e_serwerid', `nazwa` = '$from->e_nazwa', `display_template` = '$from->e_display_template', `maps_invote` = '$from->e_maps_invote', `next_mapgroup` = '$from->e_next_mapgroup', `nominate_flags` = '$from->e_nominate_flags', `adminmenu_flag` = '$from->e_adminmenu_flag', `command` = '$from->e_command', `group_weight` = '$from->e_group_weight', `default_min_players` = '$from->e_default_min_players', `default_max_players` = '$from->e_default_max_players', `default_min_time` = '$from->e_default_min_time', `default_max_time` = '$from->e_default_max_time', `default_allow_every` = '$from->e_default_allow_every' WHERE `id` = $from->id; ");
    $_SESSION['msg'] = komunikaty("Zaktualizowano grupę map $from->e_nazwa (ID: $from->id)", 1);
    admin_log($admin, "Zaktualizowano grupę map $from->e_nazwa (ID: $from->id)", "?x=serwery_konfiguracja&xx=mapy");
  }
  public function mapy_grupa_usun($id, $admin, $dostep){
    if(uprawnienia($dostep, $admin) == 0){
      return;
    }
    $dane = row("SELECT `nazwa` FROM `acp_serwery_mapy` WHERE `id` = $id LIMIT 1");
    query("DELETE FROM `acp_serwery_mapy` WHERE `id` = $id LIMIT 1");
    query("DELETE FROM `acp_serwery_mapy_det` WHERE `mapy_id` = $id");

    $_SESSION['msg'] = komunikaty("Usunięto grupę map $dane->nazwa (ID: $id)", 1);
    admin_log($admin, "Usunięto grupę map $dane->nazwa (ID: $id)", "?x=serwery_konfiguracja&xx=mapy");
  }
  //
  // Konfiguracja: Bazy Danych
  //
  public function bazy_dodaj($id, $admin, $dostep){
    if(uprawnienia($dostep, $admin) == 0){
      return;
    }
    $from = post_to_stdclass();

    if(empty($from->n_nazwa)) {
      $_SESSION['msg'] = komunikaty("Nazwa bazy nie może być pusta..", 2);
      return;
    }

    query("INSERT INTO `acp_serwery_baza`( `serwer_id` , `nazwa` , `d_driver` , `d_host` , `d_baze` , `d_user` , `d_pass` , `d_timeout` , `d_port` , `d_time_port_on` ) VALUES ( $from->n_serwer, '$from->n_nazwa', '$from->n_driver', '$from->n_host', '$from->n_baza', '$n_user', '$from->n_haslo', '$from->n_timeout', '$from->n_port', '$from->n_time_out_on' ) ");
    $last_insert = one("SELECT LAST_INSERT_ID()");
    $_SESSION['msg'] = komunikaty("Dodano bazę danych $from->n_nazwa (ID: $last_insert)", 1);
    admin_log($admin, "Dodano bazę danych $from->n_nazwa (ID: $last_insert)", "?x=serwery_konfiguracja&xx=baza&edycja=$last_insert");
  }
  public function bazy_edytuj($id, $admin, $dostep){
    if(uprawnienia($dostep, $admin) == 0){
      return;
    }
    $from = post_to_stdclass();

    if(empty($from->e_nazwa)) {
      $_SESSION['msg'] = komunikaty("Nazwa bazy nie może być pusta..", 2);
      return;
    }

    query("UPDATE `acp_serwery_baza` SET `serwer_id` = $from->e_serwerid, `nazwa` = '$from->e_nazwa', `d_driver` = '$from->e_driver', `d_host` = '$from->e_host', `d_baze` = '$from->e_baza', `d_user` = '$from->e_user', `d_pass` = '$from->e_haslo', `d_timeout` = '$from->e_timeout', `d_port` = '$from->e_port', `d_time_port_on` = $from->e_time_out_on WHERE `id` = $from->id;");
    $_SESSION['msg'] = komunikaty("Zaktualizowano bazę danych $from->e_nazwa (ID: $from->id)", 1);
    admin_log($admin, "Zaktualizowano bazę danych $from->e_nazwa (ID: $from->id)", "?x=serwery_konfiguracja&xx=baza&edycja=$from->id");
  }
  public function bazy_usun($id, $admin, $dostep){
    if(uprawnienia($dostep, $admin) == 0){
      return;
    }
    $dane = row("SELECT `nazwa` FROM `acp_serwery_baza` WHERE `id` = $id LIMIT 1");
		query("DELETE FROM `acp_serwery_baza` WHERE `id` = $id LIMIT 1;");
		$_SESSION['msg'] = komunikaty("Usunięto bazę danych $dane->nazwa (ID: $id)", 1);
    admin_log($admin, "Usunięto bazę danych $dane->nazwa (ID: $id)", "?x=serwery_konfiguracja&xx=baza");
  }

  //
  // Konfiguracja: Help Menu
  //
  public function help_menu_nowy($admin, $dostep){
    if(uprawnienia($dostep, $admin) == 0){
      return;
    }
    $from = post_to_stdclass();
    $serwer_nazwa = one("SELECT `mod` FROM `acp_serwery` WHERE `serwer_id` = $from->serwer LIMIT 1");

    $czy_serwer_istenieje = one("SELECT COUNT(`id`) FROM `acp_serwery_helpmenu` WHERE `serwer_id` = $from->serwer");
    if($czy_serwer_istenieje != 0){
      $_SESSION['msg'] = komunikaty("Serwer $serwer_nazwa (ID: $from->serwer) posiada już menu.", 3);
      return;
    }

    query("INSERT INTO `acp_serwery_helpmenu` (`serwer_id`, `lista_serwerow`, `lista_adminow`, `opis_vipa`, `lista_komend`, `statystyki`) VALUES ('$from->serwer', '$from->lista_serwerow', '$from->lista_adminow', '$from->opis_vipa', '$from->lista_komend', '$from->statystyki');");
    $last_insert = one("SELECT LAST_INSERT_ID()");
    $_SESSION['msg'] = komunikaty("Dodano nowe Help Menu (ID: $last_insert) dla serwera $serwer_nazwa (ID: $from->serwer)", 1);
    admin_log($admin, "Dodano nowe Help Menu (ID: $last_insert) dla serwera $serwer_nazwa (ID: $from->serwer)", "?x=serwery_konfiguracja&xx=help_menu");
  }
  public function help_menu_edytuj($admin, $dostep){
    if(uprawnienia($dostep, $admin) == 0){
      return;
    }
    $from = post_to_stdclass();
    $serwer_nazwa = one("SELECT `mod` FROM `acp_serwery` WHERE `serwer_id` = $from->serwer LIMIT 1");

    query("UPDATE `acp_serwery_helpmenu` SET `lista_serwerow` = '$from->lista_serwerow', `lista_adminow` = $from->lista_adminow, `opis_vipa` = $from->opis_vipa, `lista_komend` = $from->lista_komend, `statystyki` = $from->statystyki WHERE `id` = $from->id AND `serwer_id` = $from->serwer;");
    $_SESSION['msg'] = komunikaty("Zaktualizowano Help Menu (ID: $from->id) dla serwera $serwer_nazwa (ID: $from->serwer)", 1);
    admin_log($admin, "Zaktualizowano Help Menu (ID: $from->id) dla serwera $serwer_nazwa (ID: $from->serwer)", "?x=serwery_konfiguracja&xx=help_menu");
  }
  public function help_menu_usun($id, $admin, $dostep){
    if(uprawnienia($dostep, $admin) == 0){
      return;
    }

    query("DELETE FROM `acp_serwery_helpmenu` WHERE `id` = $id");
    query("DELETE FROM `acp_serwery_helpmenu_komendy` WHERE `helpmenu_id` = $id");
    query("DELETE FROM `acp_serwery_helpmenu_vip` WHERE `helpmenu_id` = $id");
    $_SESSION['msg'] = komunikaty("Usunięto Help Menu (ID: $id)", 1);
    admin_log($admin, "Usunięto Help Menu (ID: $id)", "?x=serwery_konfiguracja&xx=help_menu");
  }

  public function help_menu_vip_nowy($admin, $dostep){
    if(uprawnienia($dostep, $admin) == 0){
      return;
    }
    $from = post_to_stdclass();

    $najwieksza_wartosc = one("SELECT `kolejnosc` FROM `acp_serwery_helpmenu_vip` WHERE `helpmenu_id` = $from->helpmenu_id ORDER BY `acp_serwery_helpmenu_vip`.`kolejnosc` DESC LIMIT 1");
    $najwieksza_wartosc = (int)$najwieksza_wartosc +1;
    $serwer_nazwa = one("SELECT `mod` FROM `acp_serwery` WHERE `serwer_id` = $serwer LIMIT 1");

    if(empty($from->tekst)){
      $_SESSION['msg'] = komunikaty("Pole tekst nie może być puste, uzupełnij je..", 3);
      return;
    }

    query("INSERT INTO `acp_serwery_helpmenu_vip` (`serwer_id`, `helpmenu_id`, `tekst`, `kolejnosc`) VALUES ('$from->serwer_id', '$from->helpmenu_id', '$from->tekst', '$najwieksza_wartosc');");
    $last_insert = one("SELECT LAST_INSERT_ID()");
    $_SESSION['msg'] = komunikaty("Dodano nową pozycję dla Opisu Vipa - Help Menu (ID: $from->helpmenu_id) dla serwera $serwer_nazwa (ID: $from->serwer_id)", 1);
    admin_log($admin, "Dodano nową pozycję dla Opisu Vipa - Help Menu (ID: $from->helpmenu_id) dla serwera $serwer_nazwa (ID: $from->serwer_id)", "?x=serwery_konfiguracja&xx=help_menu");
  }
  public function help_menu_vip_zapisz($admin, $dostep){
    if(uprawnienia($dostep, $admin) == 0){
      return;
    }
    $from = post_to_stdclass();

    if(empty($from->tekst)){
      $_SESSION['msg'] = komunikaty("Pole tekst nie może być puste, uzupełnij je..", 3);
      return;
    }

    query("UPDATE `acp_serwery_helpmenu_vip` SET `tekst` = '$from->tekst' WHERE `id` = $from->id;");
    $_SESSION['msg'] = komunikaty("Zeedytowano pozycję Help Menu (ID: $from->helpmenu_id) opis vipa ID: $from->id", 1);
    admin_log($admin, "Zeedytowano pozycję Help Menu (ID: $from->helpmenu_id) opis vipa ID: $from->id", "?x=serwery_konfiguracja&xx=help_menu");
  }
  public function help_menu_vip_kolejnosc($kierunek, $admin, $dostep){
    if(uprawnienia($dostep, $admin) == 0){
      return;
    }
    $from = post_to_stdclass();

    switch ($kierunek) {
      case 'up':
        $poz->aktualna = one("SELECT `kolejnosc` FROM `acp_serwery_helpmenu_vip` WHERE `id` = $from->id LIMIT 1");
        $poz->nowa = (int)$poz->aktualna - 1;
        $poz->aktualnie_zajmuje = one("SELECT `id` FROM `acp_serwery_helpmenu_vip` WHERE `kolejnosc` = $poz->nowa LIMIT 1");
        if($poz->nowa <= 0){
          return;
        }
        query("UPDATE `acp_serwery_helpmenu_vip` SET `kolejnosc` = $poz->nowa WHERE `id` = $from->id LIMIT 1");
        query("UPDATE `acp_serwery_helpmenu_vip` SET `kolejnosc` = $poz->aktualna WHERE `id` = $poz->aktualnie_zajmuje LIMIT 1");
        break;

      case 'down':
        $poz->aktualna = one("SELECT `kolejnosc` FROM `acp_serwery_helpmenu_vip` WHERE `id` = $from->id LIMIT 1");
        $poz->nowa = (int)$poz->aktualna + 1;
        $poz->aktualnie_zajmuje = one("SELECT `id` FROM `acp_serwery_helpmenu_vip` WHERE `kolejnosc` = $poz->nowa LIMIT 1");

        $poz->najwieksza = one("SELECT `kolejnosc` FROM `acp_serwery_helpmenu_vip` WHERE `helpmenu_id` = $from->helpmenu_id ORDER BY `kolejnosc` DESC LIMIT 1");
        if($poz->nowa > $poz->najwieksza){
          return;
        }
        query("UPDATE `acp_serwery_helpmenu_vip` SET `kolejnosc` = $poz->nowa WHERE `id` = $from->id LIMIT 1");
        query("UPDATE `acp_serwery_helpmenu_vip` SET `kolejnosc` = $poz->aktualna WHERE `id` = $poz->aktualnie_zajmuje LIMIT 1");
        break;
    }
  }
  public function help_menu_vip_usun($admin, $dostep){
    if(uprawnienia($dostep, $admin) == 0){
      return;
    }
    $from = post_to_stdclass();

    query("DELETE FROM `acp_serwery_helpmenu_vip` WHERE `id` = $from->id LIMIT 1");
    $_SESSION['msg'] = komunikaty("Usunięto pozycję Opis Vipa (ID: $from->id) dla Help Menu (ID: $from->helpmenu_id)", 1);
    admin_log($admin, "Usunięto pozycję Opis Vipa (ID: $from->id) dla Help Menu (ID: $from->helpmenu_id)", "?x=serwery_konfiguracja&xx=help_menu");
  }

  public function help_menu_komenda_nowy($admin, $dostep){
    if(uprawnienia($dostep, $admin) == 0){
      return;
    }
    $from = post_to_stdclass();
    $najwieksza_wartosc = one("SELECT `kolejnosc` FROM `acp_serwery_helpmenu_komendy` WHERE `helpmenu_id` = $from->helpmenu_id ORDER BY `kolejnosc` DESC LIMIT 1");
    $najwieksza_wartosc = (int)$najwieksza_wartosc +1;
    $serwer_nazwa = one("SELECT `mod` FROM `acp_serwery` WHERE `serwer_id` = $serwer LIMIT 1");

    if(empty($from->tekst) || empty($from->komenda)){
      $_SESSION['msg'] = komunikaty("Pole komenda oraz tekst nie może być puste, uzupełnij je..", 3);
      return;
    }

    query("INSERT INTO `acp_serwery_helpmenu_komendy` (`serwer_id`, `helpmenu_id`, `komenda`, `tekst`, `kolejnosc`) VALUES ('$from->serwer_id', '$from->helpmenu_id', '$from->komenda', '$from->tekst', '$najwieksza_wartosc');");
    $last_insert = one("SELECT LAST_INSERT_ID()");
    $_SESSION['msg'] = komunikaty("Dodano nową pozycję dla Komendy - Help Menu (ID: $from->helpmenu_id) dla serwera $serwer_nazwa (ID: $from->serwer_id)", 1);
    admin_log($admin, "Dodano nową pozycję dla Komendy - Help Menu (ID: $from->helpmenu_id) dla serwera $serwer_nazwa (ID: $from->serwer_id)", "?x=serwery_konfiguracja&xx=help_menu");
  }
  public function help_menu_komenda_zapisz($admin, $dostep){
    if(uprawnienia($dostep, $admin) == 0){
      return;
    }
    $from = post_to_stdclass();

    if(empty($from->tekst) || empty($from->komenda)){
      $_SESSION['msg'] = komunikaty("Pole komenda oraz tekst nie może być puste, uzupełnij je..", 3);
      return;
    }

    query("UPDATE `acp_serwery_helpmenu_komendy` SET `komenda` = '$from->komenda', `tekst` = '$from->tekst' WHERE `id` = $from->id;");
    $_SESSION['msg'] = komunikaty("Zeedytowano pozycję Help Menu (ID: $from->helpmenu_id) lista komenda ID: $from->id", 1);
    admin_log($admin, "Zeedytowano pozycję Help Menu (ID: $from->helpmenu_id) lista komenda ID: $from->id", "?x=serwery_konfiguracja&xx=help_menu");
  }
  public function help_menu_komenda_kolejnosc($kierunek, $admin, $dostep){
    if(uprawnienia($dostep, $admin) == 0){
      return;
    }
    $from = post_to_stdclass();

    switch ($kierunek) {
      case 'up':
        $poz->aktualna = one("SELECT `kolejnosc` FROM `acp_serwery_helpmenu_komendy` WHERE `id` = $from->id LIMIT 1");
        $poz->nowa = (int)$poz->aktualna - 1;
        $poz->aktualnie_zajmuje = one("SELECT `id` FROM `acp_serwery_helpmenu_komendy` WHERE `kolejnosc` = $poz->nowa LIMIT 1");
        if($poz->nowa <= 0){
          return;
        }
        query("UPDATE `acp_serwery_helpmenu_komendy` SET `kolejnosc` = $poz->nowa WHERE `id` = $from->id LIMIT 1");
        query("UPDATE `acp_serwery_helpmenu_komendy` SET `kolejnosc` = $poz->aktualna WHERE `id` = $poz->aktualnie_zajmuje LIMIT 1");
        break;

      case 'down':
        $poz->aktualna = one("SELECT `kolejnosc` FROM `acp_serwery_helpmenu_komendy` WHERE `id` = $from->id LIMIT 1");
        $poz->nowa = (int)$poz->aktualna + 1;
        $poz->aktualnie_zajmuje = one("SELECT `id` FROM `acp_serwery_helpmenu_komendy` WHERE `kolejnosc` = $poz->nowa LIMIT 1");

        $poz->najwieksza = one("SELECT `kolejnosc` FROM `acp_serwery_helpmenu_komendy` WHERE `helpmenu_id` = $from->helpmenu_id ORDER BY `kolejnosc` DESC LIMIT 1");
        if($poz->nowa > $poz->najwieksza){
          return;
        }
        query("UPDATE `acp_serwery_helpmenu_komendy` SET `kolejnosc` = $poz->nowa WHERE `id` = $from->id LIMIT 1");
        query("UPDATE `acp_serwery_helpmenu_komendy` SET `kolejnosc` = $poz->aktualna WHERE `id` = $poz->aktualnie_zajmuje LIMIT 1");
        break;
    }
  }
  public function help_menu_komenda_usun($admin, $dostep){
    if(uprawnienia($dostep, $admin) == 0){
      return;
    }
    $from = post_to_stdclass();
    $serwer_nazwa = one("SELECT `mod` FROM `acp_serwery` WHERE `serwer_id` = $serwer LIMIT 1");

    query("DELETE FROM `acp_serwery_helpmenu_komendy` WHERE `id` = $from->id LIMIT 1");
    $_SESSION['msg'] = komunikaty("Usunięto pozycję Lista Komend (ID: $from->id) dla Help Menu (ID: $from->helpmenu_id)", 1);
    admin_log($admin, "Usunięto pozycję Lista Komend (ID: $from->id) dla Help Menu (ID: $from->helpmenu_id)", "?x=serwery_konfiguracja&xx=help_menu");
  }

  //
  // Konfiguracja: Tagi
  //
  public function tagi_dodaj($admin, $dostep){
    if(uprawnienia($dostep, $admin) == 0){
      return;
    }
    $form = post_to_stdclass();
    $form->staly = ($form->staly == 'on') ? '1' : '0';

    insert("acp_serwery_tagi", array('`serwer`' => "$form->serwer", '`tekst`' => "$form->tag", '`staly`' => "$form->staly"));
    $_SESSION['msg'] = komunikaty("Dodano nowy Tag: $form->tag dla serwera ID: $form->serwer", 1);
    admin_log($admin, "Dodano nowy Tag: $form->tag dla serwera ID: $form->serwer", "?x=serwery_konfiguracja&xx=tagi");
  }
  public function tagi_edytuj($admin, $dostep){
    if(uprawnienia($dostep, $admin) == 0){
      return;
    }
    $form = post_to_stdclass();
    $form->staly = ($form->staly == 'on') ? '1' : '0';

    query("UPDATE `acp_serwery_tagi` SET `serwer` = '$form->serwer', `tekst` = '$form->tag', `staly` = $form->staly WHERE `id` = $form->id");
    $_SESSION['msg'] = komunikaty("Zaktualizowano Tag: $form->tag (ID: $form->id) dla serwera ID: $form->serwer", 1);
    admin_log($admin, "Zaktualizowano Tag: $form->tag (ID: $form->id) dla serwera ID: $form->serwer", "?x=serwery_konfiguracja&xx=tagi");
  }
  public function tagi_usun($id, $admin, $dostep){
    if(uprawnienia($dostep, $admin) == 0){
      return;
    }

    $dane = row("SELECT `tekst` FROM `acp_serwery_tagi` WHERE `id` = $id LIMIT 1");
    query("DELETE FROM `acp_serwery_tagi` WHERE `id` = $id LIMIT 1;");
    $_SESSION['msg'] = komunikaty("Usunięto Tag $dane->tekst (ID: $id)", 1);
    admin_log($admin, "Usunięto Tag $dane->tekst (ID: $id)", "?x=serwery_konfiguracja&xx=tagi");
  }
}
?>
