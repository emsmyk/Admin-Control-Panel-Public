<?
class WpisyMgr{
	public function nowy_wpis($post, $user){
		$from = post_to_stdclass();

		$system_tytul_min = one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'wpisy_nowy_dlugosc_tytulu_min'");
		$system_tytul_max = one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'wpisy_nowy_dlugosc_tytulu_max'");
		$system_text = one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'wpisy_nowy_dlugosc_tekstu'");

		if (empty($from->nowy_tytul) || str_replace(array("\r\n", "\n", "\r", " "), "", $from->nowy_tekst) == "") {
			$_SESSION['msg'] = komunikaty("Brak tytułu lub tekstu. Po prostu puste pola.", 4);
			return;
		}
		if (strlen($from->nowy_tytul) < $system_tytul_min || strlen($from->nowy_tytul) > $system_tytul_max ) {
			$_SESSION['msg'] = komunikaty("Tytuł powinnen być dłuższy niż $system_tytul_min znaków oraz krótszy niż $system_tytul_max ", 3);
			return;
		}
		if (strlen($from->nowy_tekst) < $system_text) {
			$_SESSION['msg'] = komunikaty("Wpis powienien, posiadać więcej niż $system_text znaków", 1);
			return;
		}

		query("INSERT INTO `acp_wpisy` (`u_id` , `tytul` , `text` , `kategoria`) VALUES ($user, '$from->nowy_tytul', '$from->nowy_tekst', $from->nowy_kategoria);");
		$last_insert = one("SELECT LAST_INSERT_ID()");

		admin_log($user, "Dodano nowy wpis $from->nowy_tytul (ID: $last_insert)", "?x=wpisy&xx=wpis&wpisid=$last_insert");

		//powiadomienie
		$user_list = array();
		$uzytkownicy_q = all("SELECT `user` FROM `acp_users`");
		foreach ($uzytkownicy_q as $uzytkownicy) {
			if($user != $uzytkownicy->user) {
				array_push($user_list, $uzytkownicy->user);
			}
		}
		powiadomienie($user_list, "?x=wpisy&xx=wpis&wpis=".clean($from->nowy_tytul)."&wpisid=$last_insert", "Wpisy | $from->nowy_tytul", "fa fa-comment fa-fw");

		$_SESSION['msg'] = komunikaty("Poprawnie dodano nowy wpis", 1);
	}
	public function komentarz($post, $user){
		$from = post_to_stdclass();

		$system_komentarz_min = one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'wpisy_komentarz_dlugosc_min'");

		if(empty($from->komentarz_tekst)) {
			$_SESSION['msg'] = komunikaty("Brak tekstu w komentarzu...", 4);
			return;
		}
		if (strlen($from->komentarz_tekst) < $system_komentarz_min) {
			$_SESSION['msg'] = komunikaty("Komentarz musi zawierać więcej niż $system_komentarz_min znaków", 4);
			return;
		}

		$dane = row("SELECT `tytul`, `u_id` FROM `acp_wpisy` WHERE `id`= $from->komentarz_id LIMIT 1;");
		$dane_komentujacy = row("SELECT `login`, `steam_login` FROM `acp_users` WHERE `user` = $user LIMIT 1;");

		query("INSERT INTO `acp_wpisy_komentarze` (`wpis_id` , `user_id` , `text`) VALUES ($from->komentarz_id, $user, '$from->komentarz_tekst');");
		admin_log($user, "Użytkownik $dane_komentujacy->steam_login ($dane_komentujacy->login) dodał komentarz we wpisie $dane->tytul (ID: $from->komentarz_id)", "?x=wpisy&xx=wpis&wpisid=$from->komentarz_id");

		//powiadomienie
		$user_list = array();
		($user == $dane->u_id) ? '' : array_push($user_list, $dane->u_id);
		$uzytkownicy_q = all("SELECT `user_id` FROM `acp_wpisy_komentarze`");
		foreach ($uzytkownicy_q as $uzytkownicy) {
			if($user != $uzytkownicy->user_id && $dane->u_id != $uzytkownicy->user_id) {
				array_push($user_list, $uzytkownicy->user_id);
			}
		}
		powiadomienie($user_list, "?x=wpisy&xx=wpis&wpis=".clean($dane->tytul)."&wpisid=$from->komentarz_id", "Wpisy | $dane_komentujacy->steam_login ($dane_komentujacy->login) napisał komentarz w $dane->tytul", "fa fa-comment-o fa-fw");

		$_SESSION['msg'] = komunikaty("Dodano nowy komentarz do wpisu $dane->tytul", 1);
	}

	public function close_open($id, $user, $dostep){
		if(uprawnienia($dostep, $user) == 0){
      return;
    }
		$id = (int)$id;
		$dane = row("SELECT `closed`, `tytul` FROM `acp_wpisy` WHERE `id` = $id LIMIT 1");
		if($dane->closed == 1){
			query("UPDATE `acp_wpisy` SET `closed` = '0', `closed_data` = NULL WHERE `id` = $id;");
			admin_log($user, "Wpis $dane->tytul (ID: $id) zostal otwarty", "?x=wpisy&xx=wpis&wpisid=$id");
			$_SESSION['msg'] = komunikaty("Wpis $dane->tytul (ID: $id) został otwarty", 1);
		}
		else {
			query("UPDATE `acp_wpisy` SET `closed` = '1', `closed_data` = NOW() WHERE `id` = $id;");
			admin_log($user, "Wpis $dane->tytul (ID: $id) zostal zamknięty", "?x=wpisy&xx=wpis&wpisid=$id");
			$_SESSION['msg'] = komunikaty("Wpis $dane->tytul (ID: $id) został zamknięty", 1);
		}
	}
	public function usun($id, $user, $dostep){
		if(uprawnienia($dostep, $user) == 0){
      return;
    }
		$id = (int)$id;
		$dane = row("SELECT `tytul` FROM `acp_wpisy` WHERE `id` = $id LIMIT 1;");
		admin_log($user, "Wpis $dane->tytul (ID: $id) zostal usunięty", "?x=wpisy");
		//del powiadomienia
		query("DELETE FROM `acp_users_notification` WHERE `text` LIKE '%$dane->tytul%';");
		//del komentarze
		query("DELETE FROM `acp_wpisy_komentarze` WHERE `f_id` = $id;");
		//del wpis
		query("DELETE FROM `acp_wpisy` WHERE `id` = $id;");
		$_SESSION['msg'] = komunikaty("Wpis $dane->tytul (ID: $id) został usunięty", 1);
	}
	public function ogloszenie($id, $user, $dostep){
		if(uprawnienia($dostep, $user) == 0){
      return;
    }
		$id = (int)$id;
		$dane = row("SELECT `tytul` FROM `acp_wpisy` WHERE `id` = $id LIMIT 1;");

		query("UPDATE `acp_wpisy` SET `ogloszenie` = '1' WHERE `id` = $id;");
		admin_log($user, "Wpis $dane->tytul (ID: $id) zostal oznaczony jako ogłoszenie", "?x=wpisy&xx=wpis&wpisid=$id");
		$_SESSION['msg'] = komunikaty("Wpis $dane->tytul (ID: $id) został oznaczony jako ogłoszenie", 1);
	}
	public function zmiana_kategori($post, $user, $dostep){
		if(uprawnienia($dostep, $user) == 0){
      return;
    }
		$from = post_to_stdclass();

		$dane = row("SELECT `tytul` FROM `acp_wpisy` WHERE `id` = $from->id LIMIT 1;");
		$dane_kat = row("SELECT `nazwa` FROM `acp_wpisy_kategorie` WHERE `id` = $from->id LIMIT 1;");

		query("UPDATE `acp_wpisy` SET `kategoria` = '$from->kategoria' WHERE `id` = $from->id;");
		admin_log($user, "Zmieniono kategorię dla wpisu $dane->tytul (ID: $from->id) na $dane_kat->nazwa (ID: $from->kategoria)", "?x=wpisy&xx=wpis&wpisid=$from->id");
		$_SESSION['msg'] = komunikaty("Zmieniono kategorię dla wpisu $dane->tytul (ID: $from->id) na $dane_kat->nazwa (ID: $from->kategoria)", 1);
	}
	public function edytuj_wpis($post, $user, $dostep){
		if(uprawnienia($dostep, $user) == 0){
      return;
    }
		$from = post_to_stdclass();

		$dane = row("SELECT `tytul` FROM `acp_wpisy` WHERE `id` = $from->id LIMIT 1;");

		query("UPDATE `acp_wpisy` SET `tytul` = '$from->tytul', `text` = '$from->tekst' WHERE `id` = $from->id;");
		admin_log($user, "Zedytowano wpis $dane->tytul (ID: $from->id)", "?x=wpisy&xx=wpis&wpisid=$from->id", "?x=wpisy&xx=wpis&wpisid=$from->id");
		$_SESSION['msg'] = komunikaty("Zedytowano wpis $dane->tytul (ID: $from->id)", 1);
	}

	public function dashbord_czy_puste_av($pole){
		if($pole ==""){
			return "./www/img/av_default.jpg";
		}
		else {
			return $pole;
		}
	}
	public function dashbord_czy_puste_login($steam_login, $login){
		if($steam_login ==""){
			return $login;
		}
		else {
			return $steam_login;
		}
	}
}
?>
