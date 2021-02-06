<?
class UstawieniaMgr{

  //
  // Ustawienia CRON: Option
  //
  public function cron_option_show($wartosc, $name){
    $mozliwosci = array(
      "0" => "Wyłączony",
      "60" => "co 60 sekund",
      "1800" => "co 30 minut",
      "3600" => "co 1 godzinę",
      "7200" => "co 2 godziny",
      "14400" => "co 4 godziny",
      "43200" => "co 12 godzin",
      "86400" => "raz 1 dobę"
    );

    $tekst = '<select class="form-control" name="'.$name.'">';
    $tekst .= '<option value="'.$wartosc.'">'.$mozliwosci[$wartosc].'</option>';
    foreach ($mozliwosci as $key => $value) {
      if($wartosc != $key){
        $tekst .= '<option value="'.$key.'">'.$value.'</option>';
      }
    }
    $tekst .= '</select>';

    return $tekst;
  }

  //
  // Funkcje Ustawień
  //
  public function zmien_wartosc($wartosc, $conf_name) {
    $wartosc = real_string($wartosc);
    $conf_name = real_string($conf_name);

    query("UPDATE `acp_system` SET `conf_value` = '$wartosc' WHERE `conf_name` = '$conf_name'; ");
  }
  public function zmien_wartosc_cron_sty($wartosc, $conf_name) {
    $wartosc = $wartosc;
    $conf_name = real_string($conf_name);

    query("UPDATE `acp_system` SET `conf_value` = '$wartosc' WHERE `conf_name` = '$conf_name'; ");
  }
  public function zmien_moduly($post_moduly) {
    $array = array();
    foreach ($post_moduly as $post_moduly=>$value) {
      array_push($array, $value);
    }
    return json_encode($array);
  }
  public function zmien_dostep($post_dostep) {
    $object = new stdClass();
    foreach ($post_dostep as $result) {
      $result = explode("-", $result);
      $object->{$result[0]} = $result[1];

    }
    return json_encode($object);
  }

  public function odnajdz_usera($nazwa) {
    $nazwa = real_string($nazwa);

    $id = one("SELECT `user` FROM `acp_users` WHERE `login` LIKE '$nazwa'; ");
    return $id;
  }
  public function odnajdz_grupe($nazwa) {
    $nazwa = real_string($nazwa);

    $id = one("SELECT `id` FROM `acp_users_grupy` WHERE `nazwa` LIKE '$nazwa'; ");
    return $id;
  }

  public function wpisy_kategorie_dodaj($post, $user) {
    $from = post_to_stdclass();

    if(empty($from->wpisy_kategorie_nazwa)) {
      $_SESSION['msg'] = komunikaty("Proszę o uzupełnienie nazwy kategorii", 3);
      return;
    }

    query("INSERT INTO `acp_wpisy_kategorie` (`id`, `nazwa`) VALUES (NULL, '$from->wpisy_kategorie_nazwa');");

    $_SESSION['msg'] = komunikaty("Dodano nową katerogiię wpisów  $from->wpisy_kategorie_nazwa ", 1);
    admin_log($user, "Dodano nową katerogiię wpisów  $from->wpisy_kategorie_nazwa ");
  }

  public function wpisy_kategorie_zapisz($post, $user) {
    $from = post_to_stdclass();

    if(empty($from->wpisy_kategorie_nazwa)) {
      $_SESSION['msg'] = komunikaty("Proszę o uzupełnienie nazwy kategorii", 3);
      return;
    }

    query("UPDATE `acp_wpisy_kategorie` SET `nazwa` = '$from->wpisy_kategorie_nazwa' WHERE `id` = $from->wpisy_kategorie_id;");
    $_SESSION['msg'] = komunikaty("Zaktualizowanoa nazwę kategorii wpisów $from->wpisy_kategorie_nazwa ($from->wpisy_kategorie_id)", 1);
    admin_log($user, "Zaktualizowanoa nazwę kategorii wpisów $from->wpisy_kategorie_nazwa ($from->wpisy_kategorie_id)");
  }

  public function wpisy_kategorie_usun($post, $user) {
    $from = post_to_stdclass();

    query("DELETE FROM `acp_wpisy_kategorie` WHERE `id` = $from->wpisy_kategorie_id ");
    $_SESSION['msg'] = komunikaty("Usunięto kategorię wpisu $from->wpisy_kategorie_nazwa ($from->wpisy_kategorie_id)", 1);
    admin_log($user, "Usunięto kategorię wpisu $from->wpisy_kategorie_nazwa ($from->wpisy_kategorie_id)");
  }


  //
  // Funckje Modulow
  //

  public function edycja_from_uprawnienia_usun($post, $dodajacy) {
    $from = post_to_stdclass();

    query("DELETE FROM `acp_moduly_akcje` WHERE `id` = $from->e_n_id AND `modul_id` = $from->e_n_idmodulu; ");
    admin_log($dodajacy, "Usunięto uprawnienie $from->e_n_akcja (ID: $from->e_n_id) z modułu $from->e_n_nazamodulu (ID: $from->e_n_idmodulu)");
    $_SESSION['msg'] = komunikaty("Usunięto uprawnienie $from->e_n_akcja (ID: $from->e_n_id) z modułu $from->e_n_nazamodulu (ID: $from->e_n_idmodulu)", 1);
  }
  public function edycja_from_uprawnienia_zapisz($post, $dodajacy) {
    $from = post_to_stdclass();

    if(empty($from->e_n_akcja) || empty($from->e_n_akcja_wys)) {
      $_SESSION['msg'] = komunikaty("Pola Akcja(PHP) oraz Akcja Nazwa nie mogą byc puste", 3);
    }
    else {
      query("UPDATE `acp_moduly_akcje` SET `akcja` = '$from->e_n_akcja', `akcja_wys` = '$from->e_n_akcja_wys', `opis` = '$from->e_n_opis' WHERE `id` = $from->e_n_id AND `modul_id` = $from->e_n_idmodulu; ");
      admin_log($dodajacy, "Zedytowano uprawnienie $from->e_n_akcja (ID: $from->e_n_id) dla modułu $from->e_n_nazamodulu (ID: $from->e_n_idmodulu)");
      $_SESSION['msg'] = komunikaty("Zedytowano uprawnienie $from->e_n_akcja (ID: $from->e_n_id) dla modułu $from->e_n_nazamodulu (ID: $from->e_n_idmodulu)", 1);
    }
  }
  public function edycja_from_uprawnienia_add($post, $dodajacy) {
    $from = post_to_stdclass();

    if(empty($from->e_new_akcja) || empty($from->e_new_akcja_wys)) {
      $_SESSION['msg'] = komunikaty("Proszę o uzupełnienie pól Akcja(PHP) oraz Akcja Nazwa", 3);
    }
    else {
      query("INSERT INTO `acp_moduly_akcje` ( `modul_id`, `akcja`, `akcja_wys`, `opis` ) VALUES( $from->e_new_idmodulu, '$from->e_new_akcja', '$from->e_new_akcja_wys', '$from->e_new_opis') ");
      admin_log($dodajacy, "Dodano nowe uprawnienie $from->e_new_akcja_wys ($from->e_new_akcja) dla modułu $from->e_new_nazamodulu  (ID:$from->e_new_idmodulu) ");
      $_SESSION['msg'] = komunikaty("Dodano nowe uprawnienie $from->e_new_akcja_wys ($from->e_new_akcja) dla modułu $from->e_new_nazamodulu  (ID:$from->e_new_idmodulu) ", 1);
    }
  }

  public function edycja_from_menu_add($post, $dodajacy) {
    $from = post_to_stdclass();

    if(empty($from->e_new_nazwa)) {
      $_SESSION['msg'] = komunikaty("Nazwa jest niezbędna", 3);
    }
    else {
      query("INSERT INTO `acp_moduly_menu` ( `modul_id`, `ikona`, `nazwa`, `link` ) VALUES ( $from->e_new_idmodulu, '$from->e_new_ikona', '$from->e_new_nazwa', '$from->e_new_link') ");
      admin_log($dodajacy, "Dodano pozycję $from->e_new_nazwa dla rozwijanego menu dla modulu $from->e_new_nazamodulu  (ID:$from->e_new_idmodulu) ");
      $_SESSION['msg'] = komunikaty("Dodano pozycję $from->e_new_nazwa rozwijanego menu dla modulu $from->e_new_nazamodulu  (ID:$from->e_new_idmodulu) ", 1);
    }
  }
  public function edycja_from_menu_zapisz($post, $dodajacy){
    $from = post_to_stdclass();

    if(empty($from->e_n_nazwa)) {
      $_SESSION['msg'] = komunikaty("Pole nazwa nie może być puste", 3);
    }
    else {
      query("UPDATE `acp_moduly_menu` SET `ikona` = '$from->e_n_ikona', `nazwa` = '$from->e_n_nazwa', `link` = '$from->e_n_link' WHERE `id` = $from->e_n_id AND `modul_id` = $from->e_n_idmodulu; ");
      admin_log($dodajacy, "Zedytowano pozycję $from->e_n_nazwa (ID: $from->e_n_id) rozwijanego menu dla modułu $from->e_n_nazamodulu (ID: $from->e_n_idmodulu)");
      $_SESSION['msg'] = komunikaty("Zedytowano pozycję $from->e_n_nazwa (ID: $from->e_n_id) rozwijanego menu dla modułu $from->e_n_nazamodulu (ID: $from->e_n_idmodulu)", 1);
    }
  }
  public function edycja_from_menu_usun($post, $dodajacy){
    $from = post_to_stdclass();

    query("DELETE FROM `acp_moduly_menu` WHERE `id` = $from->e_n_id AND `modul_id` = $from->e_n_idmodulu; ");
    admin_log($dodajacy, "Usunięto pozycję $from->e_n_nazwa (ID: $from->e_n_id) rozwijanego menu dla modułu $from->e_n_nazamodulu (ID: $from->e_n_idmodulu)");
    $_SESSION['msg'] = komunikaty("Usunięto pozycję $from->e_n_nazwa (ID: $from->e_n_id) rozwijanego menu dla modułu $from->e_n_nazamodulu (ID: $from->e_n_idmodulu)");
  }

  public function acp_moduly_dodaj($post, $dodajacy) {
    $from = post_to_stdclass();

    if(empty($from->n_nazwa) || empty($from->n_nazwa_wys)) {
      $_SESSION['msg'] = komunikaty("Nazwa modulu (PHP) oraz Nazwa Wyświetlana są polami niezbędnymi do poprawnego dodania modulu, uzupełnij je!", 3);
    }
    else {
      query("INSERT INTO `acp_moduly` ( `nazwa`, `nazwa_wys`, `opis` ) VALUES( '$from->n_nazwa', '$from->n_nazwa_wys', '$from->n_opis') ");
      admin_log($dodajacy, "Dodano nowy moduł: $from->n_nazwa_wys ($from->n_nazwa)");
      $_SESSION['msg'] = komunikaty("Dodano nowy moduł: $from->n_nazwa_wys ($from->n_nazwa)", 1);
    }
  }
  public function acp_moduly_usun($id, $dodajacy) {
    $id = (int)$id;

    query("DELETE FROM `acp_moduly` WHERE `id` = $id; ");
    query("DELETE FROM `acp_moduly_akcje` WHERE `modul_id` = $id; ");
    admin_log($dodajacy, "Usunięto moduł ID: $id");
    $_SESSION['msg'] = komunikaty("Usunięto moduł ID: $id", 1);
  }
  public function acp_moduly_edytuj_modul($post, $dodajacy) {
    $from = post_to_stdclass();

    if(empty($from->e_nazwa) || empty($from->e_nazwa_wys)) {
      $_SESSION['msg'] = komunikaty("Pola Nazwa (PHP) oraz Nazwa Wyświetlana nie może być pusta", 3);
    }
    else {
      query("UPDATE `acp_moduly` SET `nazwa` = '$from->e_nazwa', `nazwa_wys` = '$from->e_nazwa_wys', `ikona` = '$from->e_ikona', `menu` = $from->e_menu, `menu_kategoria`= '$from->e_menu_kategoria', `opis` = '$from->e_opis' WHERE `id` = $from->e_id; ");
      admin_log($dodajacy, "Zedytowano moduł: $from->e_nazwa ID: $from->e_id");
      $_SESSION['msg'] = komunikaty("Zedytowano moduł $from->e_nazwa", 1);
    }
  }

  //
  // Funkcje Grupa
  //

  public function usun_grupe($id, $co, $dodajacy) {
    $id = (int) $id;

    if($id == 0) {
      $_SESSION['msg'] = komunikaty("Nie można usunąc podstawowej grupy", 4);
    }
    else {
      switch ($co) {
        case 'usun_grupa':
          $nazwa = one("SELECT `nazwa` FROM `acp_users_grupy` WHERE `id` = $id LIMIT 1; ");
          if(empty($nazwa)){
              $_SESSION['msg'] = komunikaty("Ta grupa nie istnieje...", 3);
              return;
          }

          query("UPDATE `acp_users` SET `grupa` = '0' WHERE `grupa` =  $id;");
          query("DELETE FROM `acp_users_grupy` WHERE `id` = $id;");

          admin_log($dodajacy, "Grupa $nazwa (ID: $id) została usunięta");
          $_SESSION['msg'] = komunikaty("Grupa $nazwa (ID: $id) została usunięta", 1);
          break;
        case 'usun_dep':
          $nazwa = one("SELECT `nazwa` FROM `acp_users_departament` WHERE `id` = $id LIMIT 1; ");
          if(empty($nazwa)){
              $_SESSION['msg'] = komunikaty("Ta grupa nie istnieje...", 3);
              return;
          }

          query("UPDATE `acp_users_grupy` SET `departament` = '0' WHERE `departament` =  $id;");
          query("DELETE FROM `acp_users_departament` WHERE `id` = $id;");

          admin_log($dodajacy, "Departament ID: $id został usunięty");
          $_SESSION['msg'] = komunikaty("Departament ID: $id został usunięty", 1);
          break;
      }
    }
  }
  public function dodaj_grupe($post, $dodajacy) {
    $from = post_to_stdclass();

    if(empty($from->new_nazwa)) {
      $_SESSION['msg'] = komunikaty("Brak nazwy, bez tej informacji nie możesz dodać nowej grupy", 3);
    }
    else {
      query("INSERT INTO `acp_users_grupy` (`nazwa`, `kolor`) VALUES ('".$from->new_nazwa."', '".$from->new_kolor."'); ");
      admin_log($dodajacy, "Utworzono nową grupę $from->new_nazwa ");
      $_SESSION['msg'] = komunikaty("Utworzono grupę $from->new_nazwa", 1);
    }
  }
  public function dodaj_departament($post, $dodajacy) {
    $from = post_to_stdclass();

    if(empty($from->new_nazwa)) {
      $_SESSION['msg'] = komunikaty("Brak nazwy, bez tej informacji nie możesz dodać nowej departamentu", 3);
    }
    else {
      query("INSERT INTO `acp_users_departament` (`nazwa`) VALUES ('".$from->new_nazwa."'); ");
      admin_log($dodajacy, "Utworzono nowy departament $from->new_nazwa");
      $_SESSION['msg'] = komunikaty("Utworzono departament $from->new_nazwa", 1);
    }
  }

  //
  // Funckja Lista Użytkowników
  //
  public function edytuj_usera($post, $dodajacy) {
    $from = post_to_stdclass();

    $from->new->login = $from->e_login;
    $from->new->steam = $from->e_steam;
    $from->new->mail = $from->e_mail;
    $from->new->grupa = $from->e_grupa;
    $from->new->grupa_nazwa = one("SELECT `nazwa` FROM `acp_users_grupy` WHERE `id` = ".$from->new->grupa." LIMIT 1");
    $from->old = row("SELECT `login`, `steam`, `email`, `grupa` FROM `acp_users` WHERE `user` = '$from->id' LIMIT 1");
    $from->old_grupa = row("SELECT `id`, `nazwa` FROM `acp_users_grupy` WHERE `id` = ".$from->old->grupa." LIMIT 1");

    if(empty($from->new->login)){
      $_SESSION['msg'] = komunikaty("Login nie może być pusty", 3);
      return;
    }
    if(empty($from->new->steam)) {
      $_SESSION['msg'] = komunikaty("Pole STEAM 64 nie może być puste", 3);
      return;
    }

    query("UPDATE `acp_users` SET `login` = '".$from->new->login."', `steam` = '".$from->new->steam."', `email` = '".$from->new->mail."', `grupa` = '".$from->new->grupa."' WHERE `user` = $from->id LIMIT 1;");

    $from->log = "Zaktualizowano konto użytkownika ID: $from->id Zmieniono:";
    if($from->old->login != $from->new->login){
      $from->log .= " Login: ".$from->old->login." -> ".$from->new->login;
    }
    if($from->old->steam != $from->new->steam){
      $from->log .= " Steam: ".$from->old->steam." -> ".$from->new->steam;
    }
    if($from->old->email != $from->new->mail){
      $from->log .= " Mail: ".$from->old->email." -> ".$from->new->mail;
    }
    if($from->old_grupa->id != $from->new->grupa){
      $from->log .= " Grupę: ".$from->old_grupa->nazwa."(ID: ".$from->old_grupa->id.") -> ".$from->new->grupa_nazwa." (ID: ".$from->new->grupa.")";
    }
    admin_log($dodajacy, $from->log, "?x=account&id=$id");

    return;
  }
  public function password_usera($id, $dodajacy) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $pass = substr(str_shuffle($chars),0, 8);

    $user = one("SELECT `login` FROM `acp_users` WHERE `user` = $id; ");
    if(empty($user)) {
      $_SESSION['msg'] = komunikaty("Nie istnieje taki użytkownik", 3);
      return;
    }
    query("UPDATE `acp_users` SET `pass` = '".md5($pass)."' WHERE `user` = $id LIMIT 1; ");
    admin_log($dodajacy, "Wygenerowno nowe hasło dla użytkonika $user (ID: $id)");
    $_SESSION['msg'] = komunikaty("Wygenerowno nowe hasło dla użytkonika $user (ID: $id) Hasło: $pass", 1);
  }
  public function usun_usera($id, $dodajacy) {
    $user = one("SELECT `login` FROM `acp_users` WHERE `user` = $id; ");
    if(empty($user)) {
      $_SESSION['msg'] = komunikaty("Nie istnieje taki użytkownik", 3);
      return;
    }
    query("DELETE FROM `acp_users` WHERE `user` = $id; ");
    admin_log($dodajacy, "Użytkonik $user (ID: $id) został usunięty");
    $_SESSION['msg'] = komunikaty("Użytkonik $user (ID: $id) został usunięty", 1);

  }
  public function ban_usera($id, $dodajacy) {
    $user = one("SELECT `login` FROM `acp_users` WHERE `user` = $id; ");
    if(empty($user)) {
      $_SESSION['msg'] = komunikaty("Nie istnieje taki użytkownik", 3);
      return;
    }

    $status_bana = one("SELECT `banned` FROM `acp_users` WHERE `user` = $id LIMIT 1;");
    if($status_bana == -1){
      query("UPDATE `acp_users` SET `banned` = '0' WHERE `user` = $id LIMIT 1;");
      admin_log($dodajacy, "Użytkonik $user (ID: $id) został zablokowany");
      $_SESSION['msg'] = komunikaty("Użytkonik $user (ID: $id) został zablokowany", 1);
    }
    else if($status_bana == 0){
      query("UPDATE `acp_users` SET `banned` = '-1' WHERE `user` = $id LIMIT 1;");
      admin_log($dodajacy, "Użytkonik $user (ID: $id) został odblokowany");
      $_SESSION['msg'] = komunikaty("Użytkonik $user (ID: $id) został odblokowany", 1);
    }
  }
  public function danepub_zapisz($menu, $user){
    $from = post_to_stdclass();
    $from->danepub_blank = ($from->danepub_blank == 'on') ? "target='_blank'": '';

    $menu[$from->danepub_id]->page = "$from->danepub_page";
    $menu[$from->danepub_id]->link = $from->danepub_link;
    $menu[$from->danepub_id]->blank = $from->danepub_blank;
    $menu = json_encode($menu);
    query("UPDATE `acp_system` SET `conf_value` = '$menu' WHERE `conf_name` = 'danepub_menu_list' LIMIT 1");
    $_SESSION['msg'] = komunikaty("Zeedytowano pozycję menu publicznego", 1);
  }
  public function danepub_usun($menu, $user){
    $from = post_to_stdclass();

    unset($menu[$from->danepub_id]);
    $menu = json_encode($menu);
    query("UPDATE `acp_system` SET `conf_value` = '$menu' WHERE `conf_name` = 'danepub_menu_list' LIMIT 1");
    $_SESSION['msg'] = komunikaty("Skasowano pozycję menu publicznego", 1);
  }
  public function danepub_dodaj($menu, $user){
    $from = post_to_stdclass();
    $from->danepub_blank = ($from->danepub_blank == 'on') ? "target='_blank'": '';

    $menu2 = new stdClass();
    $menu2->page = $from->danepub_page;
    $menu2->link = $from->danepub_link;
    $menu2->blank = $from->danepub_blank;
    $menu[] = $menu2;
    $menu = json_encode($menu);
    query("UPDATE `acp_system` SET `conf_value` = '$menu' WHERE `conf_name` = 'danepub_menu_list' LIMIT 1");
    $_SESSION['msg'] = komunikaty("Dodano nową pozycję do menu publicznego", 1);
  }
}
?>
