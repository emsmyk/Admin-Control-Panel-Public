<?php
class RoundsoundMgr
{
  public function DanePubliczne($id){
    if(is_null($id) || empty($id)){
      $dane->aktualny_rs = one("SELECT `conf_value` FROM `rs_ustawienia` WHERE `conf_name` = 'rs_roundsound';");
      $dane->rs = row("SELECT * FROM `rs_roundsound` WHERE `id` = $dane->aktualny_rs LIMIT 1");
    }
    else {
      $dane->rs = row("SELECT * FROM `rs_roundsound` WHERE `id` = $id LIMIT 1");
    }
    $dane->lista_piosenek = json_decode($dane->rs->lista_piosenek);

    return $dane;
  }
  public function DanePubliczneAktualny(){
    $dane = one("SELECT `conf_value` FROM `rs_ustawienia` WHERE `conf_name` = 'rs_roundsound';");
    $dane = one("SELECT `nazwa` FROM `rs_roundsound` WHERE `id` = $dane LIMIT 1");
    return $dane;
  }
  public function DanePubliczneAktualnyID(){
    $dane = one("SELECT `conf_value` FROM `rs_ustawienia` WHERE `conf_name` = 'rs_roundsound';");
    return $dane;
  }
  public function DanePubliczneKolejny(){
    $dane = one("SELECT `conf_value` FROM `rs_ustawienia` WHERE `conf_name` = 'rs_roundsound_c';");
    $dane = one("SELECT `nazwa` FROM `rs_roundsound` WHERE `id` = $dane LIMIT 1");
    return $dane;
  }
  public function DanePubliczneKolejnyID(){
    $dane = one("SELECT `conf_value` FROM `rs_ustawienia` WHERE `conf_name` = 'rs_roundsound_c';");
    return $dane;
  }
  public function propozycja($rs_id){
    $from = post_to_stdclass();
    $pattern = '~[a-z]+://\S+~';
    if(!preg_match_all($pattern, $from->link, $out)){
      $_SESSION['msg'] = komunikaty("Pole link musi zawierać link do serwisu muzycznego", 3);
      return;
    }
    if(!strpos($from->start, ':')){
      $_SESSION['msg'] = komunikaty("Początek utworu powinen być w formacie: (Minuty):(Sekundy)", 3);
      return;
    }
    if(!strpos($from->end, ':')){
      $_SESSION['msg'] = komunikaty("Koniec utworu powinen być w formacie: (Minuty):(Sekundy)", 3);
      return;
    }

    if(empty($from->nazwa)){
      $_SESSION['msg'] = komunikaty("Pusta nazwa. Aby dodać nową musisz wpisać coś..", 3);
      return;
    }
    if(empty($from->start) || empty($from->end)){
      $_SESSION['msg'] = komunikaty("Początek i koniec utworu nie może być puste..", 3);
      return;
    }
    if(empty($from->link)){
      $_SESSION['msg'] = komunikaty("Podaj link do utworu na YT, proszę..", 3);
      return;
    }

    query("INSERT INTO `rs_utwory` (`nazwa`, `wykonawca`, `album`, `start`, `end`, `link_yt`, `roundsound_propozycja`) VALUES ('$from->nazwa', '$from->wykonawca', '$from->album', '$from->start', '$from->end', '$from->link', '$rs_id'); ");
    $last = one("SELECT LAST_INSERT_ID()");
    $_SESSION['msg'] = komunikaty("Twoja propozycja została dodana do systemu, gdy zostanie zaakceptowana przez administratora pojawi się na liście", 1);
    admin_log('-1', "Zaproponowano nowy utwór: $from->nazwa (ID: $last)", "?x=roundsound&xx=piosenki_edit&id=$last");

    // powiadomienie
    $user_list = array();
    $grupy_q = all("SELECT `id` FROM `acp_users_grupy` WHERE `dostep` LIKE '%\"RsPiosenkaAkcept\":\"1\"%' ");
    foreach ($grupy_q as $grupy) {
      $uzytkownicy_q = all("SELECT `user` FROM `acp_users` WHERE `grupa` = '$grupy->id' ");
      foreach ($uzytkownicy_q as $uzytkownicy) {
        array_push($user_list, $uzytkownicy->user);
      }
    }
    powiadomienie($user_list, "?x=roundsound&xx=piosenki_edit&id=$last", "Roundsound | Dodano propozycję nowej piosenki $from->nazwa, która oczekuje na akceptację", "fa fa-music");

  }
  public function vote($rs_id, $id){
    $rs_id = (int)$rs_id;
    $id = (int)$id;
    $gosc->przegladarka = $_SERVER['HTTP_USER_AGENT'];
    $gosc->ip = $_SERVER['REMOTE_ADDR'];
    $gosc->vote_rs = one("SELECT `roundsound` FROM `rs_vote` WHERE `ip` LIKE '%$gosc->ip%' AND `przegladarka` LIKE '%$gosc->przegladarka%' ORDER BY `data` DESC LIMIT 1");
    $gosc->vote_time = one("SELECT `data` FROM `rs_vote` WHERE `ip` LIKE '%$gosc->ip%' AND `przegladarka` LIKE '%$gosc->przegladarka%' ORDER BY `data` DESC LIMIT 1");
    $gosc->utwor = one("SELECT `utwor` FROM `rs_vote` WHERE `ip` LIKE '%$gosc->ip%' AND `przegladarka` LIKE '%$gosc->przegladarka%' ORDER BY `data` DESC LIMIT 1");
    $rs->rs_vote = one("SELECT `conf_value` FROM `rs_ustawienia` WHERE `conf_name` = 'rs_vote' LIMIT 1"); /* 1 - jednba piosenka 0 - wiele piosenek*/
    $rs->rs_time = one("SELECT `conf_value` FROM `rs_ustawienia` WHERE `conf_name` = 'rs_vote_time' LIMIT 1");

    // jeżeli to google bot to nie dodawanie glosu
    if(isset($gosc->przegladarka) && preg_match('/bot|crawl|slurp|spider|mediapartners/i', $gosc->przegladarka)){
        return;
    }

    if($rs->rs_vote == '1'):
      if(strtotime($gosc->vote_time)< (time() - $rs->rs_time*60) || empty($gosc->vote_time)){
        /* START - Od tego momentu kopia wykonania oddania glosu*/
        query("UPDATE `rs_utwory`  SET `vote` = `vote` + 1 WHERE `id` = '$id' LIMIT 1");
        query("INSERT INTO `rs_vote` (`roundsound`, `utwor`, `ip`, `przegladarka`) VALUES ('$rs_id', '$id', '$gosc->ip', '$gosc->przegladarka')");
        $_SESSION['msg'] = komunikaty("Oddałeś poprawnie głos, Twoja opinia została zapisana w systemie", 1);
        return;
        /* KONIEC*/
      }
      $_SESSION['msg'] = komunikaty("Już głosowałeś, spróbuj kolejnego dnia ponownie", 4);
      return;
    elseif($rs->rs_vote == '0'):
      $gosc->utwor = one("SELECT `utwor` FROM `rs_vote` WHERE `ip` LIKE '%$gosc->ip%' AND `przegladarka` LIKE '%$gosc->przegladarka%' AND `utwor` = '$id' ORDER BY `data` DESC LIMIT 1");
      if($id == $gosc->utwor && strtotime($gosc->vote_time)< (time() - $rs->rs_time*60)){
        /* START - Od tego momentu kopia wykonania oddania glosu*/
        query("UPDATE `rs_utwory`  SET `vote` = `vote` + 1 WHERE `id` = '$id' LIMIT 1");
        query("INSERT INTO `rs_vote` (`roundsound`, `utwor`, `ip`, `przegladarka`) VALUES ('$rs_id', '$id', '$gosc->ip', '$gosc->przegladarka')");
        $_SESSION['msg'] = komunikaty("Oddałeś poprawnie głos, Twoja opinia została zapisana w systemie", 1);
        return;
        /* KONIEC*/
      }
      elseif($id != $gosc->utwor){
        /* START - Od tego momentu kopia wykonania oddania glosu*/
        query("UPDATE `rs_utwory`  SET `vote` = `vote` + 1 WHERE `id` = '$id' LIMIT 1");
        query("INSERT INTO `rs_vote` (`roundsound`, `utwor`, `ip`, `przegladarka`) VALUES ('$rs_id', '$id', '$gosc->ip', '$gosc->przegladarka')");
        $_SESSION['msg'] = komunikaty("Oddałeś poprawnie głos, Twoja opinia została zapisana w systemie", 1);
        return;
        /* KONIEC*/
      }
      $_SESSION['msg'] = komunikaty("Już głosowałeś, spróbuj kolejnego dnia ponownie", 4);
      return;
    endif;
  }

  public function nowa_lista($user, $dostep){
    if(uprawnienia($dostep, $user) == 0){
      return;
    }
    $from = post_to_stdclass();
    $from->user = $user;

    if(empty($from->nazwa)){
      $_SESSION['msg'] = komunikaty("Pusta nazwa. Aby dodać nową musisz wpisać coś..", 3);
      return;
    }

    query("INSERT INTO `rs_roundsound` (`nazwa`, `u_id`) VALUES ('$from->nazwa', $from->user); ");
    $last = one("SELECT LAST_INSERT_ID()");
    $_SESSION['msg'] = komunikaty("Dodano nową listę utworów Nazwa: $from->nazwa (ID: $last)", 1);
    admin_log($user, "Dodano nową listę utworów Nazwa: $from->nazwa (ID: $last)", "?x=roundsound&xx=lista_edit&id=$last");
  }
  public function edytuj_lista($user, $dostep){
    if(uprawnienia($dostep, $user) == 0){
      return;
    }
    $from = post_to_stdclass();

    query("UPDATE `rs_roundsound` SET `nazwa` = '$from->nazwa' WHERE `id` = $from->id;");
    $_SESSION['msg'] = komunikaty("Zaktualizowano Listę Nazwa: $from->nazwa (ID: $from->id)", 1);
    admin_log($user, "Zaktualizowano Listę Nazwa: $from->nazwa (ID: $from->id)", "?x=roundsound&xx=lista_edit&id=$from->id");
  }
  public function usun_lista($id, $user, $dostep){
    if(uprawnienia($dostep, $user) == 0){
      return;
    }

    $from->id = (int)$id;
    $from->rs = row("SELECT * FROM `rs_roundsound` WHERE `id` = $from->id LIMIT 1");

    query("DELETE FROM `rs_roundsound` WHERE `id` = $from->id LIMIT 1");
    $_SESSION['msg'] = komunikaty("Usunięto listę ".$from->rs[nazwa]." (ID: $from->id)", 1);
    admin_log($user, "Usunięto listę ".$from->rs[nazwa]." (ID: $from->id)", "?x=roundsound&xx=lista");
  }

  public function dodaj_piosenke($user, $dostep){
    if(uprawnienia($dostep, $user) == 0){
      return;
    }
    $from = post_to_stdclass();

    $from->rs = row("SELECT * FROM `rs_roundsound` WHERE `id` = $from->id LIMIT 1");
    $from->dane_piosenka = row("SELECT * FROM `rs_utwory` WHERE `id` = $from->piosenka LIMIT 1");
    $from->lista = json_decode($from->rs->lista_piosenek);

    if(in_array($from->piosenka, $from->lista)){
      $_SESSION['msg'] = komunikaty("Ta piosenka jest dodana do tej listy..", 3);
      return;
    }
    $from->lista[] = (int)$from->piosenka;
    $from->lista = json_encode($from->lista);

    query("UPDATE `rs_roundsound` SET `lista_piosenek` = '$from->lista' WHERE `id` = $from->id LIMIT 1; ");
    $_SESSION['msg'] = komunikaty("Dodano utwór ". $from->dane_piosenka->nazwa." (ID: $from->piosenka)  do listy utworów ".$from->rs->nazwa." (ID: $from->id)", 1);
    admin_log($user, "Dodano utwór ". $from->dane_piosenka->nazwa." (ID: $from->piosenka)  do listy utworów ".$from->rs->nazwa." (ID: $from->id)", "?x=roundsound&xx=lista_edit&id=$from->id");
  }
  public function dodaj_do_listy($id, $id_roundsound, $user, $dostep){
    if(uprawnienia($dostep, $user) == 0){
      return;
    }

    $from->id_piosenki = (int)$id;
    $from->id_roundsound = (int)$id_roundsound;
    $from->rs = row("SELECT * FROM `rs_roundsound` WHERE `id` = $from->id_roundsound LIMIT 1");
    $from->piosenka = row("SELECT * FROM `rs_utwory` WHERE `id` = $from->id_piosenki LIMIT 1");
    $from->lista = json_decode($from->rs->lista_piosenek);

    if(in_array($from->id_piosenki, $from->lista)){
      $_SESSION['msg'] = komunikaty("Ta piosenka jest dodana do tej listy..", 3);
      return;
    }
    $from->lista[] = (int)$from->id_piosenki;
    $from->lista = json_encode($from->lista);

    query("UPDATE `rs_roundsound` SET `lista_piosenek` = '$from->lista' WHERE `id` = $from->id_roundsound LIMIT 1; ");
    query("UPDATE `rs_utwory` SET `roundsound_propozycja_dodane` = '1' WHERE `id` = $from->id_piosenki LIMIT 1");
    $_SESSION['msg'] = komunikaty("Dodano utwór ". $from->piosenka->nazwa." (ID: $from->id_piosenki)  do listy utworów ".$from->rs->nazwa." (ID: $from->id_roundsound)", 1);
    admin_log($user, "Dodano utwór ". $from->piosenka->nazwa." (ID: $from->id_piosenki)  do listy utworów ".$from->rs->nazwa." (ID: $from->id_roundsound)", "?x=roundsound&xx=lista_edit&id=$from->id_roundsound");
  }
  public function usun_piosenke_z_listy($id, $piosenka_id, $user, $dostep){
    if(uprawnienia($dostep, $user) == 0){
      return;
    }

    $from->id = (int)$id;
    $from->piosenka_id = (int)$piosenka_id;
    $from->rs = row("SELECT * FROM `rs_roundsound` WHERE `id` = $from->id LIMIT 1");
    $from->piosenka = row("SELECT * FROM `rs_utwory` WHERE `id` = $from->piosenka_id LIMIT 1");

    $from->lista = json_decode($from->rs->lista_piosenek);
    $kasujemy = [(int)$from->piosenka_id];
    $from->lista = array_diff($from->lista, $kasujemy);
    $from->lista = json_encode(array_values($from->lista));

    query("UPDATE `rs_roundsound` SET `lista_piosenek` = '$from->lista' WHERE `id` = $from->id LIMIT 1; ");
    $_SESSION['msg'] = komunikaty("Skasowano utwór ". $from->piosenka->nazwa." (ID: $from->piosenka_id)  z listy utworów ".$from->rs->nazwa." (ID: $from->id)", 1);
    admin_log($user, "Skasowano utwór ". $from->piosenka->nazwa." (ID: $from->piosenka_id)  z listy utworów ".$from->rs->nazwa." (ID: $from->id)", "?x=roundsound&xx=lista_edit&id=$from->id");
  }
  public function ustaw_status($id, $jaki, $user, $dostep){
    if(uprawnienia($dostep, $user) == 0){
      return;
    }
    $from = new stdClass();
    $from->id = (int)$id;
    $from->jaki = $jaki;
    $from->rs = row("SELECT * FROM `rs_roundsound` WHERE `id` = $from->id LIMIT 1");
    switch ($from->jaki) {
      case 'aktualna':
        query("UPDATE `rs_ustawienia` SET `conf_value` = '$from->id' WHERE `conf_name` = 'rs_roundsound' LIMIT 1");
        query("UPDATE `rs_ustawienia` SET `conf_value` = '' WHERE `conf_name` = 'rs_roundsound_c' LIMIT 1");
        $_SESSION['msg'] = komunikaty("Ustawiono listę utworów: ".$from->rs->nazwa." (ID: $from->id) jako Aktualnie Graną", 1);
        admin_log($user, "Ustawiono listę utworów: ".$from->rs->nazwa." (ID: $from->id) jako Aktualnie Graną", "?x=roundsound&xx=lista_edit&id=$from->id");
        break;
      case 'w_przygotowaniu':
        query("UPDATE `rs_ustawienia` SET `conf_value` = '$from->id' WHERE `conf_name` = 'rs_roundsound_c' LIMIT 1");
        $_SESSION['msg'] = komunikaty("Ustawiono listę utworów: ".$from->rs->nazwa." (ID: $from->id) jako W przygotowaniu", 1);
        admin_log($user, "Ustawiono listę utworów: ".$from->rs->nazwa." (ID: $from->id) jako W przygotowaniu", "?x=roundsound&xx=lista_edit&id=$from->id");
        break;
    }
  }
  public function nowa_piosenka($user, $dostep){
    if(uprawnienia($dostep, $user) == 0){
      return;
    }

    $from = post_to_stdclass();
    $from->user = $user;

    if(empty($from->nazwa)){
      $_SESSION['msg'] = komunikaty("Pusta nazwa. Aby dodać nową musisz wpisać coś..", 3);
      return;
    }
    if(empty($from->start) || empty($from->end)){
      $_SESSION['msg'] = komunikaty("Początek i koniec utworu nie może być puste..", 3);
      return;
    }
    if(empty($from->link)){
      $_SESSION['msg'] = komunikaty("Podaj link do utworu na YT, proszę..", 3);
      return;
    }

    query("INSERT INTO `rs_utwory` (`nazwa`, `wykonawca`, `album`, `start`, `end`, `link_yt`) VALUES ('$from->nazwa', '$from->wykonawca', '$from->album', '$from->start', '$from->end', '$from->link'); ");
    $last = one("SELECT LAST_INSERT_ID()");
    $_SESSION['msg'] = komunikaty("Dodano nowy utwór Nazwa: $from->nazwa (ID: $last)", 1);
    admin_log($user, "Dodano nowy utwór Nazwa: $from->nazwa (ID: $last)", "?x=roundsound&xx=piosenki_edit&id=$last");

    // powiadomienie
    $user_list = array();
    $grupy_q = all("SELECT `id` FROM `acp_users_grupy` WHERE `dostep` LIKE '%\"RsPiosenkaAkcept\":\"1\"%' ");
    foreach ($grupy_q as $grupy) {
      $uzytkownicy_q = all("SELECT `user` FROM `acp_users` WHERE `grupa` = '$grupy->id' ");
      foreach ($uzytkownicy_q as $uzytkownicy) {
        array_push($user_list, $uzytkownicy->user);
      }
    }
    powiadomienie($user_list, "?x=roundsound&xx=piosenki_edit&id=$last", "Roundsound | Dodano nową piosenkę $from->nazwa, która oczekuje na akceptację", "fa fa-music");

  }
  public function edytuj_piosenke($user, $dostep){
    if(uprawnienia($dostep, $user) == 0){
      return;
    }

    $from = post_to_stdclass();
    $from->user = $user;

    if(empty($from->nazwa)){
      $_SESSION['msg'] = komunikaty("Pusta nazwa. Aby dodać nową musisz wpisać coś..", 3);
      return;
    }
    if(empty($from->start) || empty($from->end)){
      $_SESSION['msg'] = komunikaty("Początek i koniec utworu nie może być puste..", 3);
      return;
    }
    if(empty($from->link)){
      $_SESSION['msg'] = komunikaty("Podaj link do utworu na YT, proszę..", 3);
      return;
    }

    query("UPDATE `rs_utwory` SET `nazwa` = '$from->nazwa', `wykonawca` = '$from->wykonawca', `album` = '$from->album', `start` = '$from->start', `end` = '$from->end', `link_yt` = '$from->link' WHERE `id` = $from->id;");
    $_SESSION['msg'] = komunikaty("Zaktualizowano utwór Nazwa: $from->nazwa (ID: $from->id)", 1);
    admin_log($user, "Zaktualizowano utwór Nazwa: $from->nazwa (ID: $from->id)", "?x=roundsound&xx=piosenki_edit&id=$from->id");
  }
  public function usun_piosenke($id, $user, $dostep){
    if(uprawnienia($dostep, $user) == 0){
      return;
    }

    $from->id = (int)$id;
    $from->piosenka = row("SELECT * FROM `rs_utwory` WHERE `id` = $from->id LIMIT 1");

    query("DELETE FROM `rs_utwory` WHERE `id` = $from->id LIMIT 1");
    unlink("www/mp3/".$from->piosenka->mp3_code.".mp3");
    $_SESSION['msg'] = komunikaty("Usunięto piosenke ".$from->piosenka[nazwa]." (ID: $from->id)", 1);
    admin_log($user, "Usunięto piosenke ".$from->piosenka[nazwa]." (ID: $from->id)", "?x=roundsound&xx=piosenki");
  }
  public function akceptuj_piosenke($id, $user, $dostep){
    if(uprawnienia($dostep, $user) == 0){
      return;
    }
    $from = new \stdClass;
    $from->id = (int)$id;
    $from->piosenka = row("SELECT * FROM `rs_utwory` WHERE `id` = $from->id LIMIT 1");

    query("UPDATE `rs_utwory` SET `akcept` = '$user', `data_akcept` = NOW() WHERE `id` = $from->id;");
    $_SESSION['msg'] = komunikaty("Zakceptowano piosenkę ".$from->piosenka->nazwa." (ID: $from->id) ", 1);
    admin_log($user, "Zakceptowano piosenkę ".$from->piosenka->nazwa." (ID: $from->id)", "?x=roundsound&xx=piosenki_edit&id=$from->id"); 
  }

  public function wgraj_mp3($user, $dostep){
    if(uprawnienia($dostep, $user) == 0){
      return;
    }
    $from = post_to_stdclass();
    $from->file = $_FILES['nazwa_pliku'];
    $from->piosenka = row("SELECT * FROM `rs_utwory` WHERE `id` = $from->id LIMIT 1");
    $from->mp3_code = generujLosowyCiag(10, false);

    // sprwadzenie czy losowy ciag jest unikalny
    if(!empty(one("SELECT * FROM `rs_utwory` WHERE `mp3_code` LIKE '$from->mp3_code'"))){
      $from->mp3_code = generujLosowyCiag(10, false);
    }

    if($from->file[size] > 2097152){
      $_SESSION['msg'] = komunikaty("Plik jest za duży, makysmalna wielkość to 2 MB", 3);
      return;
    }
    if($from->file[type] != 'audio/mpeg'){
      $_SESSION['msg'] = komunikaty("Plik musi być w formacie MP3", 3);
      return;
    }

    move_uploaded_file($from->file[tmp_name], "www/mp3/".$from->mp3_code.".mp3");

    query("UPDATE `rs_utwory` SET `mp3` = '1', `mp3_code` = '$from->mp3_code' WHERE `id` = $from->id LIMIT 1;");
    $_SESSION['msg'] = komunikaty("Wgrano ".$from->file[name]." ", 1);
    admin_log($user, "Wgrano ".$from->file[name]." ", "?x=roundsound&xx=piosenki_edit&id=$from->id");

  }

  public function zmien_wartosc($wartosc, $conf_name, $user, $dostep) {
    if(uprawnienia($dostep, $user) == 0){
      return;
    }

    $wartosc = real_string($wartosc);
    $conf_name = real_string($conf_name);
    $co_bylo = one("SELECT `conf_value` FROM `rs_ustawienia` WHERE `conf_name` = '$conf_name' LIMIT 1");
    if($wartosc == $co_bylo){
      return;
    }
    query("UPDATE `rs_ustawienia` SET `conf_value` = '$wartosc' WHERE `conf_name` = '$conf_name'; ");
    $_SESSION['msg'] = komunikaty("Zmieniono $co_bylo na $wartosc dla ustawienia $conf_name", 1);
    admin_log($user, "Zmieniono $co_bylo na $wartosc dla ustawienia $conf_name", "?x=roundsound&xx=ustawienia");
  }
  public function ustawienia_OnOff($lista, $id, $OnOff, $user, $dostep) {
    if(uprawnienia($dostep, $user) == 0){
      return;
    }

    $dane = row("SELECT `mod` FROM `acp_serwery` WHERE `serwer_id` = $id LIMIT 1");

    if($OnOff == 'on'){
      $lista[] = (int)$id;
    }
    else if($OnOff = 'off'){
      $kasujemy = [$id];
      $lista = array_diff($lista, $kasujemy);
    }
    $lista = json_encode(array_values($lista));
    query("UPDATE `rs_ustawienia` SET `conf_value` = '$lista' WHERE `conf_name` = 'rs_serwery'; ");

    $_SESSION['msg'] = komunikaty("Zaktualizowano ustawienie serwera $dane->mod (ID: $id)", 1);
    admin_log($user, "Zaktualizowano ustawienie serwera $dane->mod (ID: $id)", "?x=roundsound&xx=ustawienia");
  }

}
?>
