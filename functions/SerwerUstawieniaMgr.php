<?
class SerwerUstawieniaMgr {
  public function edytuj($user, $dostep){
    if(uprawnienia($dostep, $user) == 0){
      return;
    }
    $from = post_to_stdclass();
    $from->e_rcon = (empty($from->e_rcon)) ? $dane->rcon : encrypt_decrypt('encrypt', $from->e_rcon);
    $from->e_ftpp = (empty($from->e_ftpp)) ? $dane->ftp_haslo : encrypt_decrypt('encrypt', $from->e_ftpp);

    $dane = row("SELECT `ftp_haslo`, `rcon`, `nazwa` FROM `acp_serwery` WHERE `serwer_id` = $from->id");

		query("UPDATE `acp_serwery` SET `test_serwer` = '$from->e_test_serwer', `ip` = '$from->e_ip', `port` = '$from->e_port', `prefix_sb` = '$from->e_prefix_sb', `prefix_hls` = '$from->e_prefix_hls', `serwer_on` = $from->e_wlaczony, `cronjobs` = $from->e_cronjobs, `istotnosc` = '$from->e_istonosc', `mod` = '$from->e_mod', `rcon` = '$from->e_rcon', `czas_reklam` = '$from->e_czasreklam', `liczba_map` = '$from->e_liczbamap', `ip_bot_hlstats` = '$from->e_botip', `link_gotv` = '$from->e_gotvlink', `ftp_user` = '$from->e_ftpu', `ftp_haslo` = '$from->e_ftpp', `ftp_host` = '$from->e_ftph', `ser_a_jr` = '$from->e_junioradmin', `ser_a_opiekun` = '$from->e_opiekun', `ser_a_copiekun` = '$from->e_copiekun' WHERE `serwer_id` = $from->id ");
		admin_log($user, "Zaktualizowano ustawienia serwera $dane->nazwa MOD: $from->e_mod (ID: $from->id)", "?x=serwery_ust&edycja=$from->id");
		$_SESSION['msg'] = komunikaty("Zaktualizowano ustawienia serwera $dane->nazwa MOD: $from->e_mod (ID: $from->id)", 1);
  }
  public function serwerbanner($user, $dostep){
    if(uprawnienia($dostep, $user) == 0){
      return;
    }
    $from = post_to_stdclass();

    $file_name = $_FILES['nazwa_pliku']['name'];
    $file_size = $_FILES['nazwa_pliku']['size'];
    $file_tmp = $_FILES['nazwa_pliku']['tmp_name'];
    $file_type = $_FILES['nazwa_pliku']['type'];

    $dane = row("SELECT `serwer_id`, `ftp_haslo`, `rcon`, `nazwa` FROM `acp_serwery` WHERE `serwer_id` = $from->id");

    if($file_size > 2097152){
      $_SESSION['msg'] = komunikaty("Obrazek jest za duży, makysmalna wielkość to 2 MB", 3);
      return;
    }
    if(file_exists("www/server_banner/".$dane->serwer_id.".png")) {
      unlink("www/server_banner/".$dane->serwer_id.".png");
    }

    move_uploaded_file($file_tmp,"www/server_banner/".$dane->serwer_id.".png");
    $_SESSION['msg'] = komunikaty("Obrazek został zaktualizowany dla serwera $dane->nazwa ($from->id).", 1);
    admin_log($user, "Zaktualizowano obrazek serwera $dane->nazwa ($from->id)", "?x=serwery_ust&edytuj=$from->id");
  }
  public function pracezdalne($user, $dostep){
    if(uprawnienia($dostep, $user) == 0){
      return;
    }
    $from = post_to_stdclass();
    $from->mapy_plugin = (empty($from->mapy_plugin)) ? 'NULL' : $from->mapy_plugin;

		$dane = row("SELECT `nazwa`, `mod` FROM `acp_serwery` WHERE `serwer_id` = $from->id");
		$id_cron = one("SELECT `id` FROM `acp_serwery_cronjobs` WHERE `serwer` = $from->id; ");

    if(empty($id_cron)){
			query("INSERT INTO `acp_serwery_cronjobs` (`id`, `serwer`, `typ_polaczenia`, `katalog`, `reklamy`, `bazy`, `cvary`, `mapy`, `mapy_plugin`, `hextags`, `help_menu`, `uslugi`) VALUES (NULL, '$from->id', '$from->typ_polaczenia', '$from->katalog', '$from->reklamy', '$from->mapy', '$from->mapy_plugin', '$from->bazy', '$from->cvary', '$from->hextags', '$from->helpmenu', '$from->uslugi');");
			admin_log($user, "Utworzono zdalne prace wykonywane na dla serwera $dane->nazwa ($from->id)", "?x=serwery_ust&cron=$from->id");
			$_SESSION['msg'] = komunikaty("Utworzono zdalne prace wykonywane na dla serwera $dane->nazwa ($from->id)", 1);
		}
		else {
			query("UPDATE `acp_serwery_cronjobs` SET `typ_polaczenia` = '$from->typ_polaczenia', `katalog` = '$from->katalog', `reklamy` = '$from->reklamy', `bazy` = '$from->bazy', `cvary` = '$from->cvary', `mapy` = '$from->mapy', `mapy_plugin` = '$from->mapy_plugin', `hextags` = '$from->hextags', `help_menu` = $from->helpmenu, `uslugi` = $from->uslugi WHERE `serwer` = $from->id;");
			admin_log($user, "Zaktualizowano zdalne prace wykonywane na dla serwera $dane->nazwa ($from->id)", "?x=serwery_ust&cron=$from->id");
			$_SESSION['msg'] = komunikaty("Zaktualizowano zdalne prace wykonywane na dla serwera $dane->nazwa ($from->id)", 1);
		}
  }
  public function usun($id, $user, $dostep){
    if(uprawnienia($dostep, $user) == 0){
      return;
    }
		query("DELETE FROM `acp_serwery` WHERE `serwer_id` = $id LIMIT 1;");
		query("DELETE FROM `acp_serwery_logs` WHERE `serwer_id` = $id;");
		query("DELETE FROM `acp_serwery_logs_day` WHERE `serwer_id` = $id;");
		query("DELETE FROM `acp_serwery_logs_hour` WHERE `serwer_id` = $id;");
		query("DELETE FROM `acp_serwery_logs_month` WHERE `serwer_id` = $id;");

		$_SESSION['msg'] = komunikaty("Usunięto serwer ID: $id", 1);
		admin_log($user, "Usunięto serwer ID: $id", "?x=serwery_ust");

  }
  public function dodaj($user, $dostep){
    if(uprawnienia($dostep, $user) == 0){
      return;
    }
    $from = post_to_stdclass();
		$from->test_serwer = ($_POST['test_serwer'] == 'on') ? 1 : 0;

		if(empty($from->new_ip) || empty($from->new_port)){
			 $_SESSION['msg'] = komunikaty("Aby dodać serwer niezbędne jest podanie poprawngo ip oraz portu serwera!", 3);
			 return;
		}

    query("INSERT INTO `acp_serwery` (`game`, `mod`, `test_serwer`, `ip`, `port`) VALUES ('$from->new_gra', '$from->new_mod', $from->test_serwer, '$from->new_ip', '$from->new_port'); ");
    admin_log($user, "Dodano nowy serwer $from->new_ip:$from->new_port", "?x=serwery_ust");
		$_SESSION['msg'] = komunikaty("Dodano nowy serwer $from->new_ip:$from->new_port", 1);
  }
}
?>
