<?
class ZadaniaMgr{
	public function nowe_zadanie($post, $user, $dostep){
		if(uprawnienia($dostep, $user) == 0){
      return;
    }
		$from = post_to_stdclass();

		if($from->platforma == 0 || $from->typ == 0) {
			$_SESSION['msg'] = komunikaty("Proszę wybrać Platformę lub typ zadania. Te pola nie mogą być puste.", 2);
			return;
		}
		else if(empty($from->temat) || empty($from->opis)) {
			$_SESSION['msg'] = komunikaty("Temat oraz opis jest polem obowiązkowym, proszę o uzupełnienie..", 2);
			return;
		}
		else {
			query("INSERT INTO `acp_zadania` (`platforma`, `typ`, `serwer_id`, `temat`, `opis`, `zlecajacy_id`) VALUES ('$from->platforma', '$from->typ', '$from->serwer', '$from->temat', '$from->opis', '$user'); ");
			$last_insert = one("SELECT LAST_INSERT_ID()");
			admin_log($user, "Dodano nowe zadanie $from->temat (ID: $last_insert)", "?x=zadania&xx=zadanie&id=$last_insert");
			$_SESSION['msg'] = komunikaty("Dodano nowe zadanie $from->temat typ: $from->typ", 1);

			// powiadomienie
			// osob posiadających dostęp do akceptacja lub odrzucenia zadania
			$user_list = array();

			$grupy_q = all("SELECT `id` FROM `acp_users_grupy` WHERE `dostep` LIKE '%\"ZadanieAkcOdrz\":\"1\"%' ");
			foreach ($grupy_q as $grupy) {
				$uzytkownicy_q = all("SELECT `user` FROM `acp_users` WHERE `grupa` = '$grupy->id' ");
				foreach ($uzytkownicy_q as $uzytkownicy) {
					array_push($user_list, $uzytkownicy->user);
				}
			}
			powiadomienie($user_list, "?x=zadania&xx=zadanie&id=$last_insert", "Zadania | Dodano nowe zadanie $from->temat które oczekuje na akceptację", "fa fa-flag-o");
		}
	}
	public function edytuj_zadanie($post, $user, $dostep){
		if(uprawnienia($dostep, $user) == 0){
      return;
    }
		$from = post_to_stdclass();

		if($from->platforma == 0 || $from->typ == 0) {
			$_SESSION['msg'] = komunikaty("Proszę wybrać Platformę, serwer lub typ zadania. Te pola nie mogą być puste.", 2);
			return;
		}
		else if(empty($from->temat) || empty($from->opis)) {
			$_SESSION['msg'] = komunikaty("Temat oraz opis jest polem obowiązkowym, proszę o uzupełnienie..", 2);
			return;
		}
		else {
			query("UPDATE `acp_zadania` SET `platforma` = '$from->platforma', `serwer_id` = '$from->serwer', `typ` = '$from->typ', `temat` = '$from->temat', `opis` = '$from->opis' WHERE `id` = $from->id;");
			admin_log($user, "Zedytowano zadanie $from->temat (ID: $from->id)", "?x=zadania&xx=zadanie&id=$from->id");
			$_SESSION['msg'] = komunikaty("Zedytowano zadanie $from->temat (ID: $from->id)", 1);
		}
	}
	public function usun_zadanie($post, $user, $dostep){
		if(uprawnienia($dostep, $user) == 0){
      return;
    }
		$id = (int)$_GET['usun'];
		$dane = row("SELECT `temat` FROM `acp_zadania` WHERE `id` = $id; ");

		query("DELETE FROM `acp_zadania` WHERE `id` = $id;");
		admin_log($user, "Usunięto zadanie $dane->temat (ID: $id)", "?x=zadania&xx=lista");
		$_SESSION['msg'] = komunikaty("Usunięto zadanie $dane->temat (ID: $id)", 1);
	}
	public function akceptuj_zadanie($post, $user, $dostep){
		if(uprawnienia($dostep, $user) == 0){
      return;
    }
		$id = (int)$_GET['akceptuj'];
		$dane = row("SELECT `temat`, `zlecajacy_id` FROM `acp_zadania` WHERE `id` = $id; ");

		query("UPDATE `acp_zadania` SET `status` = '1', `akceptujacy_id` = '$user', `a_data` = NOW() WHERE `id` = $id;");
		query("INSERT INTO `acp_zadania_users` (`id_zadania`, `u_id`) VALUES ('$id', '$user'); ");
		admin_log($user, "Zakceptowano zadanie $dane->temat (ID: $id)", "?x=zadania&xx=zadanie&id=$id");
		$_SESSION['msg'] = komunikaty("Zakceptowano zadanie $dane->temat (ID: $id)", 1);

		// powiadomienie
		// osob posiadających dostęp do realizacji oraz osoby dodającej zadanie
		$user_list = array();

		($user == $dane->zlecajacy_id) ? '' : array_push($user_list, $dane->zlecajacy_id);

		$grupy_q = all("SELECT `id` FROM `acp_users_grupy` WHERE `dostep` LIKE '%\"ZadaniePrzyjmnij\":\"1\"%' ");
		foreach ($grupy_q as $grupy) {
			$uzytkownicy_q = all("SELECT `user` FROM `acp_users` WHERE `grupa` = '$grupy->id' ");
			foreach ($uzytkownicy_q as $uzytkownicy) {
				if($dane->zlecajacy_id != $uzytkownicy->user) {
					array_push($user_list, $uzytkownicy->user);
				}
			}
		}
		powiadomienie($user_list, "?x=zadania&xx=zadanie&id=$id", "Zadania | $dane->temat zostało zakceptowane, czeka na realizację..", "fa fa-flag-o");
	}
	public function odrzuc_zadanie($post, $user, $dostep){
		if(uprawnienia($dostep, $user) == 0){
      return;
    }
		$id = (int)$_GET['odrzuc'];
		$dane = row("SELECT `temat`, `zlecajacy_id` FROM `acp_zadania` WHERE `id` = $id; ");

		query("UPDATE `acp_zadania` SET `status` = '-1', `akceptujacy_id` = '$user', `a_data` = NOW() WHERE `id` = $id;");
		admin_log($user, "Odrzucono zadanie $dane->temat (ID: $id)", "?x=zadania&xx=zadanie&id=$id");
		$_SESSION['msg'] = komunikaty("Odrzucono zadanie $dane->temat (ID: $id)", 1);

		// powiadomienie
		// osob dodającej zadanie
		$user_list = array();
		array_push($user_list, $dane->zlecajacy_id);
		powiadomienie($user_list, "?x=zadania&xx=zadanie&id=$id", "Zadania | $dane->temat zostało odrzucone", "fa fa-flag-o");
	}
	public function przyjmnij_zadanie($post, $user, $dostep){
		if(uprawnienia($dostep, $user) == 0){
      return;
    }
		$id = (int)$_GET['przyjmnij'];
		$dane = row("SELECT `temat`, `zlecajacy_id`, `akceptujacy_id` FROM `acp_zadania` WHERE `id` = $id; ");
		$dane_user = row("SELECT `login`, `steam_login` FROM `acp_users` WHERE `user` = $user");

		query("UPDATE `acp_zadania` SET `status` = '2', `technik_id` = '$user', `t_data` = NOW() WHERE `id` = $id;");
		$czy_user_bierze_udzial = one("SELECT `u_id` FROM `acp_zadania_users` WHERE `id_zadania` = $id AND `u_id` = $user");
		if(empty($czy_user_bierze_udzial)) {
			query("INSERT INTO `acp_zadania_users` (`id_zadania`, `u_id`) VALUES ('$id', '$user'); ");
		}
		admin_log($user, "Przyjęto zadanie $dane->temat (ID: $id) do realizacji przez $dane_user->steam_login ( $dane_user->login)", "?x=zadania&xx=zadanie&id=$id");
		$_SESSION['msg'] = komunikaty("Przyjęto zadanie $dane->temat (ID: $id) do realizacji", 1);

		// powiadomienie
		// dodającego oraz akceptującego o przyjęciu zadania do realizacji
		$user_list = array();

		($user == $dane->zlecajacy_id) ? '' : array_push($user_list, $dane->zlecajacy_id);
		($user == $dane->akceptujacy_id) ? '' : array_push($user_list, $dane->akceptujacy_id);

		powiadomienie($user_list, "?x=zadania&xx=zadanie&id=$id", "Zadania | $dane->temat zostało przyjęte do realizacji przez $dane_user->steam_login ( $dane_user->login)", "fa fa-flag-o");
	}
	public function zakoncz_zadanie($post, $user, $dostep){
		if(uprawnienia($dostep, $user) == 0){
      return;
    }
		$id = (int)$_GET['zakoncz'];
		$dane = row("SELECT `temat`, `zlecajacy_id`, `akceptujacy_id` FROM `acp_zadania` WHERE `id` = $id; ");

		query("UPDATE `acp_zadania` SET `status` = '3', `procent_wykonania` = '100', `kolor_wykonania` = 'green',  `time_end` = NOW() WHERE `id` = $id;");
		admin_log($user, "Zakończono zadanie $dane->temat (ID: $id)", "?x=zadania&xx=zadanie&id=$id");
		$_SESSION['msg'] = komunikaty("Zakończono zadanie $dane->temat (ID: $id)", 1);

		// powiadomienie
		// dodającego oraz akceptującego oraz userow biorących udział
		$user_list = array();

		($user == $dane->zlecajacy_id) ? '' : array_push($user_list, $dane->zlecajacy_id);
		($user == $dane->akceptujacy_id) ? '' : array_push($user_list, $dane->akceptujacy_id);


		$zadanie_user_q = all("SELECT `u_id` FROM `acp_zadania_users` WHERE `id_zadania` = $id");
		foreach ($zadanie_user_q as $zadanie_user) {
			if($zadanie_user->u_id != $dane->zlecajacy_id && $zadanie_user->u_id != $dane->akceptujacy_id){
				array_push($user_list, $zadanie_user->u_id);
			}
		}

		powiadomienie($user_list, "?x=zadania&xx=zadanie&id=$id", "Zadania | $dane->temat zostało zakończone", "fa fa-flag-o");
	}
	public function anuluj_zadanie($post, $user, $dostep){
		if(uprawnienia($dostep, $user) == 0){
      return;
    }
		$id = (int)$_GET['anuluj'];
		$dane = row("SELECT `temat`, `zlecajacy_id`, `akceptujacy_id`, `technik_id`  FROM `acp_zadania` WHERE `id` = $id; ");

		query("UPDATE `acp_zadania` SET `status` = '-2', `time_end` = NOW() WHERE `id` = $id;");
		admin_log($user, "Anulowano zadanie $dane->temat (ID: $id)", "?x=zadania&xx=zadanie&id=$id");
		$_SESSION['msg'] = komunikaty("Anulowano zadanie $dane->temat (ID: $id)", 1);

		// powiadomienie
		// dodającego oraz akceptującego, realizujacego oraz userow biorących udział
		$user_list = array();

		($user == $dane->zlecajacy_id) ? '' : array_push($user_list, $dane->zlecajacy_id);
		($user == $dane->akceptujacy_id) ? '' : array_push($user_list, $dane->akceptujacy_id);
		($user == $dane->technik_id) ? '' : array_push($user_list, $dane->technik_id);

		$zadanie_user_q = all("SELECT `u_id` FROM `acp_zadania_users` WHERE `id_zadania` = $id");
		foreach ($zadanie_user_q as $zadanie_user) {
			if($zadanie_user->u_id != $dane->zlecajacy_id && $zadanie_user->u_id != $dane->akceptujacy_id && $zadanie_user->u_id != $dane->technik_id){
				array_push($user_list, $zadanie_user->u_id);
			}
		}

		powiadomienie($user_list, "?x=zadania&xx=zadanie&id=$id", "Zadania | $dane->temat zostało anulowane", "fa fa-flag-o");
	}

	public function komentarz($post, $user, $dostep){
		if(uprawnienia($dostep, $user) == 0){
      return;
    }
		$from = post_to_stdclass();

		if(empty($from->komentarz_tekst)){
			return;
		}

		$dane = row("SELECT `temat`, `zlecajacy_id`, `akceptujacy_id`, `technik_id` FROM `acp_zadania` WHERE `id` = $from->id; ");

		query("INSERT INTO `acp_zadania_com` (`id_z`, `u_id`, `text`) VALUES ('$from->id', '$user', '$from->komentarz_tekst');");
		admin_log($user, "Dodano komentarz do zadania $dane->temat (ID: $from->id)", "?x=zadania&xx=zadanie&id=$from->id");
		$_SESSION['msg'] = komunikaty("Dodano komentarz do zadania $dane->temat (ID: $from->id)", 1);

		// powiadomienie
		// dodającego oraz akceptującego, realizujacego oraz userow biorących udział
		$user_list = array();

		($user == $dane->zlecajacy_id) ? '' : array_push($user_list, $dane->zlecajacy_id);
		($user == $dane->akceptujacy_id) ? '' : array_push($user_list, $dane->akceptujacy_id);
		($user == $dane->technik_id) ? '' : array_push($user_list, $dane->technik_id);

		$zadanie_user_q = all("SELECT `u_id` FROM `acp_zadania_users` WHERE `id_zadania` = $id");
		foreach ($zadanie_user_q as $zadanie_user) {
			if($zadanie_user->u_id != $dane->zlecajacy_id && $zadanie_user->u_id != $dane->akceptujacy_id && $zadanie_user->u_id != $dane->technik_id){
				array_push($user_list, $zadanie_user->u_id);
			}
		}
		powiadomienie($user_list, "?x=zadania&xx=zadanie&id=$from->id", "Zadania | $dane->temat dodano komentarz..", "fa fa-flag-o");
	}
	public function todo_dodaj($post, $user, $dostep){
		if(uprawnienia($dostep, $user) == 0){
      return;
    }
		$from = post_to_stdclass();

		$dane = row("SELECT `temat` FROM `acp_zadania` WHERE `id` = $from->id; ");

		query("INSERT INTO `acp_zadania_todo` (`zadanie_id`, `tekst`, `data`, `pozostalo`, `zrealizowano`, `zrealizowano_data`) VALUES ('$from->id', '$from->todo_tekst', NOW(), '$from->todo_czasrealizacji', '0', NULL);");
		admin_log($user, "Dodano pozycję To Do do zadania $dane->temat (ID: $from->id)", "?x=zadania&xx=zadanie&id=$from->id");
		$_SESSION['msg'] = komunikaty("Dodano pozycję To Do do zadania $dane->temat (ID: $from->id)", 1);
	}
	public function todo_status($post, $user, $dostep){
		if(uprawnienia($dostep, $user) == 0){
      return;
    }
		$todo = (int)$_GET['todo_status'];
		$todo_dane = row("SELECT `zrealizowano` FROM `acp_zadania_todo` WHERE `id` = $todo; ");
		$zadanie = (int)$_GET['id'];
		$zadanie_dane = row("SELECT `id`, `temat` FROM `acp_zadania` WHERE `id` = $zadanie; ");

		$realizuj = (1 == $dane_todo->zrealizowano) ? 0 : 1;
		$realizuj_tekst = (1 == $dane_todo->zrealizowano) ? 'niezrealizowane' : 'zrealizowane';

		query("UPDATE `acp_zadania_todo` SET `zrealizowano` = '$realizuj', `zrealizowano_data` = NOW() WHERE `id` = $todo;");
		admin_log($user, "Zmieniono status pozycji To Do (ID: $todo) na $realizuj_tekst w zadaniu $zadanie_dane->temat (ID: $zadanie_dane->id)", "?x=zadania&xx=zadanie&id=$zadanie_dane->id");
		$_SESSION['msg'] = komunikaty("Zmieniono status pozycji To Do (ID: $todo) na $realizuj_tekst w zadaniu $zadanie_dane->temat (ID: $zadanie_dane->id)", 1);
	}
	public function todo_usun($post, $user, $dostep){
		if(uprawnienia($dostep, $user) == 0){
      return;
    }
		$id = (int)$_GET['todo_usun'];
		$id_zadanie = (int)$_GET['id'];
		$dane = row("SELECT `temat` FROM `acp_zadania` WHERE `id` = $id_zadanie; ");

		query("DELETE FROM `acp_zadania_todo` WHERE `id` = $id;");
		admin_log($user, "Usunięto pozycję To Do (ID: $id) w zadaniu $dane->temat (ID: $id_zadanie)", "?x=zadania&xx=zadanie&id=$id_zadanie", "?x=zadania&xx=zadanie&id=$id_zadania");
		$_SESSION['msg'] = komunikaty("Usunięto pozycję To Do (ID: $id) w zadaniu $dane->temat (ID: $id_zadanie)", 1);
	}
	public function zapros($post, $user, $dostep){
		if(uprawnienia($dostep, $user) == 0){
      return;
    }
		$from = post_to_stdclass();
		$form->user_dane = row("SELECT `user` AS `id`, `login`, `steam_login` FROM `acp_users` WHERE `login` LIKE '%$from->zapros_text%' LIMIT 1; ");
		$form->user_dane_ist = row("SELECT * FROM `acp_zadania_users` WHERE `u_id` = ".$form->user_dane->id." AND `id_zadania` = $from->id LIMIT 1");

		if(empty($form->user_dane)){
			$_SESSION['msg'] = komunikaty("Nie odnaleziono użytkownika..", 3);
			return;
		}
		if($form->user_dane_ist->u_id == $form->user_dane->id){
			return;
		}

		insert("acp_zadania_users", array('`id_zadania`' => $from->id, '`u_id`' => $form->user_dane->id));
		admin_log($user, "Dodano użytkownika ".$form->user_dane->steam_login." (".$form->user_dane->login." ID: ".$form->user_dane->id.") do zadania ID: $from->id", "?x=zadania&xx=zadanie&id=$from->id");
		$_SESSION['msg'] = komunikaty("Dodano użytkownika ".$form->user_dane->steam_login." (".$form->user_dane->login." ID: ".$form->user_dane->id.") do zadania ID: $from->id", 1);

		// powiadomienie
		$user_list = array();
		array_push($user_list, $form->user_dane->id);
		powiadomienie($user_list, "?x=zadania&xx=zadanie&id=$id_zadanie", "Zadania | Zostałeś dodany do zadania $temat. Weź w nim czynny udział.", "fa fa-flag-o");
	}
	public function public_link($id, $user, $dostep){
		if(uprawnienia($dostep, $user) == 0){
      return;
    }
		$id = (int)$id;
		$zadanie_dane = row("SELECT `id`, `temat` FROM `acp_zadania` WHERE `id` = $id; ");

		$random = generujLosowyCiag(50);
		query("UPDATE `acp_zadania` SET `public_code` = '$random' WHERE `id` = $id;");
		admin_log($user, "Wygenerowano publiczny link dla zadania $zadanie_dane->temat (ID: $id)", "?x=zadania&xx=zadanie&id=$id");
		$_SESSION['msg'] = komunikaty("Wygenerowano publiczny link dla zadania $zadanie_dane->temat (ID: $id)", 1);
	}
	public function procent_wykonania($id, $status){
		if($status == 3) {
			return 100;
		}
		else {
			$ile_zadań = one("SELECT COUNT(`id`) FROM `acp_zadania_todo` WHERE `zadanie_id` = $id");
			$ile_zrealizowano = one("SELECT COUNT(`id`) FROM `acp_zadania_todo` WHERE `zadanie_id` = $id AND `zrealizowano` = 1");

			$ile = round($ile_zrealizowano*100/$ile_zadań);
			query("UPDATE `acp_zadania` SET `procent_wykonania` = '$ile' WHERE `id` = $id;");

			return $ile;
		}
	}
	public function kolor_procent_wykonania($id, $prc){
		if($prc < 30){
			$kolor = 'red';
		}
		else if($prc >= 30 && $prc < 50){
			$kolor = 'aqua';
		}
		else if($prc >= 50 && $prc < 70){
			$kolor = 'yellow';
		}
		else if($prc >= 70){
			$kolor = 'green';
		}

		query("UPDATE `acp_zadania` SET `kolor_wykonania` = '$kolor' WHERE `id` = $id;");
		return $kolor;
	}

}
?>
