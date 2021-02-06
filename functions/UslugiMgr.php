<?
class UslugiMgr{
	public function edytuj_conf($wartosc, $conf_name, $user, $dostep) {
		if(uprawnienia($dostep, $user) == 0){
      return;
    }

		$wartosc = real_string($wartosc);
		$conf_name = real_string($conf_name);

		query("UPDATE `acp_system` SET `conf_value` = '$wartosc' WHERE `conf_name` = '$conf_name'; ");
	}
	public function dodaj_usluge($user, $dostep){
		if(uprawnienia($dostep, $user) == 0){
      return;
    }

		$from = post_to_stdclass();

		if(empty($from->n_nazwa) || empty($from->n_flagi)){
			$_SESSION['msg'] = komunikaty("Wszystkie pola są obowiązkowe, uzupełnij je..", 3);
			return;
		}

		insert("acp_uslugi_rodzaje", array('`nazwa`' => "$from->n_nazwa", '`flags`' => "$from->n_flagi"));
		$_SESSION['msg'] = komunikaty("Dodano nową usługę $from->n_nazwa", 1);
		admin_log($user, "Dodano nową usługę $from->n_nazwa", "?x=uslugi");
	}
	public function zapisz_zmiany($user, $dostep){
		if(uprawnienia($dostep, $user) == 0){
      return;
    }
		$from = post_to_stdclass();

		if(empty($from->nazwa) || empty($from->flagi)){
			$_SESSION['msg'] = komunikaty("Wszystkie pola są obowiązkowe, uzupełnij je..", 3);
			return;
		}

		query("UPDATE `acp_uslugi_rodzaje` SET `nazwa` = '$from->nazwa', `flags` = '$from->flagi' WHERE `id` = $from->id;");
		$_SESSION['msg'] = komunikaty("Zaktualizowano usługę $from->nazwa (ID: $from->id)", 1);
		admin_log($user, "Zaktualizowano usługę $from->nazwa (ID: $from->id)", "?x=uslugi");
	}
	public function usun_usluge($user, $dostep){
		if(uprawnienia($dostep, $user) == 0){
      return;
    }

		$from = post_to_stdclass();

		query("DELETE FROM `acp_uslugi_rodzaje` WHERE `acp_uslugi_rodzaje`.`id` = $from->id");
		$_SESSION['msg'] = komunikaty("Usunięto usługę $from->nazwa (ID: $from->id)", 1);
		admin_log($user, "Usunięto usługę $from->nazwa (ID: $from->id)", "?x=uslugi");
	}
	public function edytuj_dane_publiczne($user, $dostep){
		if(uprawnienia($dostep, $user) == 0){
			return;
		}

		$from = post_to_stdclass();

		query("UPDATE `acp_uslugi_rodzaje` SET `publiczna` = '$from->publiczna', `img` = '$from->img', `opis` = '$from->opis' WHERE `id` = $from->id;");
		$_SESSION['msg'] = komunikaty("Zaktualizowano dane publiczne dla usługi $from->nazwa (ID: $from->id)", 1);
		admin_log($user, "Zaktualizowano usługę $from->nazwa (ID: $from->id)", "?x=uslugi");
	}
	public function ustawienia_OnOff($serwer, $usluga, $OnOff, $user, $dostep) {
		if(uprawnienia($dostep, $user) == 0){
			return;
		}

		$usluga = row("SELECT * FROM `acp_uslugi_rodzaje` WHERE `id` = $usluga LIMIT 1");
		$lista = json_decode($usluga->serwery);

		if($OnOff == 'on'){
			$lista[] = (int)$serwer;
		}
		else if($OnOff = 'off'){
			$kasujemy = [$serwer];
			$lista = array_diff($lista, $kasujemy);
		}
		$lista = json_encode(array_values($lista));
		query("UPDATE `acp_uslugi_rodzaje` SET `serwery` = '$lista' WHERE `id` = $usluga->id LIMIT 1 ");
		$_SESSION['msg'] = komunikaty("Uruchomiono usługę $usluga->nazwa (ID: $usluga->id) dla serwera ID: $serwer", 1);
		// admin_log($user, "Zaktualizowano ustawienie serwera $dane->mod (ID: $id)", "?x=roundsound&xx=ustawienia");
	}

	public function admin_dodaj_usluge($user, $dostep){
		if(uprawnienia($dostep, $user) == 0){
			return;
		}
		$from = post_to_stdclass();
		$from->steam_comunity = toCommunityID($from->steam);

		if(empty($from->dni) || empty($from->steam)){
			$_SESSION['msg'] = komunikaty("Wszystkie pola muszą zostać uzupełnione", 3);
			return;
		}
		$now = new DateTime();
		$from->koniec = $now->modify("+$from->dni day")->format('Y-m-d H:i:s');
		$from->serwery_dostepne = json_decode(one("SELECT `serwery` FROM `acp_uslugi_rodzaje` WHERE `id` = $from->rodzaj_uslugi LIMIT 1"));

		if(!in_array($from->serwer, $from->serwery_dostepne)){
			$_SESSION['msg'] = komunikaty("Wybrana Usługa nie jest włączona na wybranym serwerze. Nie możemy jej dodać.", 3);
			return;
		}
		insert("acp_uslugi", array('`user`' => "$user",'`serwer`' => "$from->serwer", '`steam`' => "$from->steam_comunity", "`steam_id`" => "$from->steam", '`koniec`' => "$from->koniec", '`rodzaj`' => "$from->rodzaj"));
		$_SESSION['msg'] = komunikaty("Dodano usługę $from->steam_comunity (STEAMID: $from->steam)", 1);
		admin_log($user, "Dodano usługę $from->steam_comunity (STEAMID: $from->steam)", "?x=uslugi&xx=dodaj_usluge");
	}
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

	public function uslugi_edytuj($user, $dostep){
		if(uprawnienia($dostep, $user) == 0){
			return;
		}
		$from = post_to_stdclass();
		$from->steam_comunity = toCommunityID($from->steam);
		if(empty($from->koniec) || empty($from->steam)){
			$_SESSION['msg'] = komunikaty("Wszystkie pola muszą zostać uzupełnione", 3);
			return;
		}

		$from->serwery_dostepne = json_decode(one("SELECT `serwery` FROM `acp_uslugi_rodzaje` WHERE `id` = $from->rodzaj LIMIT 1"));

		if(!in_array($from->serwer, $from->serwery_dostepne)){
			$_SESSION['msg'] = komunikaty("Wybrana Usługa nie jest włączona na wybranym serwerze. Nie możemy jej dodać.", 3);
			return;
		}
		query("UPDATE `acp_uslugi` SET `serwer` = '$from->serwer', `steam_id`= '$from->steam', `steam` = '$from->steam_comunity', `koniec` = '$from->koniec', `rodzaj` ='$from->rodzaj' WHERE `id` = $from->id;");
		$_SESSION['msg'] = komunikaty("Zedytowano usługę ID: $from->id - $from->steam_comunity (STEAMID: $from->steam)", 1);
		admin_log($user, "Zedytowano usługę ID: $from->id - $from->steam_comunity (STEAMID: $from->steam)", "?x=uslugi&xx=uslugi");

	}
	public function uslugi_usun($id, $user, $dostep){
		if(uprawnienia($dostep, $user) == 0){
			return;
		}
		$id = (int)$id;
		$usluga = row("SELECT `steam`, `steam_id`, `serwer`, `rodzaj` FROM `acp_uslugi` WHERE `id` = $id LIMIT 1");
 		query("DELETE FROM `acp_uslugi` WHERE `acp_uslugi`.`id` = $id LIMIT 1");
		admin_log($user, "Skasowano usługę $usluga->steam (STEAMID: $from->steam_id) Serwer ID: $usluga->serwer Rodzaj ID: $usluga->rodzaj", "?x=uslugi&xx=uslugi");
	}
}
?>
