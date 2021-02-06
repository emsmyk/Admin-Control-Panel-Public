<?
class SerwerMgr{
	public function prace_zdalne_oznacz_jako_przeczytane($serwer, $user, $dostep){
		if(uprawnienia($dostep, $user) == 0){
			return;
		}

		$serwer = (int)$serwer;
		$serwer_det = row("SELECT `mod` FROM `acp_serwery` WHERE `serwer_id` = $serwer");

		query("UPDATE `acp_serwery_bledy` SET `status` = '0' WHERE `serwer_id` = $serwer;");
		admin_log($user, "Oznaczono wszystkie logi z prac cyklicznych jako odczytane dla serwera $serwer_det->mod (ID: $serwer)", "?x=serwery_det&serwer_id=$serwer");
		$_SESSION['msg'] = komunikaty("Oznaczono wszystkie logi z prac cyklicznych jako odczytane dla serwera $serwer_det->mod (ID: $serwer)", 1);
	}
	public function kontola_systemu($serwer_id){
		$dane = row("SELECT `serwer_id`, `mod`, `status`, `status_data`,`rcon`, `ftp_user`, `ftp_haslo`,`ftp_host` FROM `acp_serwery` WHERE `serwer_id` = $serwer_id LIMIT 1; ");
		$tekst = '';
		if($dane->status == 1){
			$tekst .= komunikaty_rozbudowany('fa fa-info', 'Serwer OFF!', 'Serwer nie odpowiada! Sprawdź jego status..', 4);
		}
		if(!$dane->rcon) {
			$tekst .= komunikaty_rozbudowany('fa fa-info', 'Kontrola Systemu - RCON', 'Serwer '.$dane->mod.' (ID:'.$dane->serwer_id.') nie posiada podanego hasła RCON, aby je uzupełnić przejdź do <a href="?x=serwery_ust&edycja='.$serwer_id.'">Serwery Ustawienia</a>', 3);
		}
		if(!$dane->ftp_user || !$dane->ftp_haslo || !$dane->ftp_host){
			$tekst .= komunikaty_rozbudowany('fa fa-info', 'Kontrola Systemu - FTP', 'Serwer '.$dane->mod.' (ID:'.$dane->serwer_id.') nie posiada skonfigurowanego połączenia FTP, aby je uzupełnić przejdź do <a href="?x=serwery_ust&edycja='.$serwer_id.'">Serwery Ustawienia</a>', 4);
		}

		return $tekst;
	}
	public function sprawdznie_dostepu($serwer_id, $player){
		$serwer = one("SELECT `serwer_id` FROM `acp_serwery` WHERE `serwer_id` = $serwer_id LIMIT 1");
		$dostep = one("SELECT `serwer_id` FROM `acp_serwery` WHERE `serwer_id` = $serwer_id AND `ser_a_jr` = $player OR  `ser_a_opiekun` = $player OR  `ser_a_copiekun` = $player LIMIT 1");
		if(empty($serwer) || $serwer == ''){
			header('Location: ?x=serwery');
		}
		$role = one("SELECT `role` FROM `acp_users` WHERE `user` = $player");
		switch($role){
			case 0:
				if(empty($dostep) || $dostep == ''){
					header('Location: ?x=serwery');
				}
			break;
			case 1:
			break;
		}
	}
	public function obrazek_mapy($mapa){
		$mapa_id = one("SELECT `id` FROM `acp_serwery_mapy_det` WHERE `nazwa` = '$mapa' LIMIT 1");
		$obrazek = one("SELECT `imgur_url` FROM `acp_serwery_mapy_img` WHERE `id_mapy` = $mapa_id LIMIT 1");
		$obrazek = ($obrazek) ?: '#';

		if($obrazek == '#'){
			$mapa = 'https://acp.sloneczny-dust.pl/www/maps/nomap.jpg';
			return $mapa;
		}
		$src_headers = @get_headers($obrazek);
		if($src_headers[0] == 'HTTP/1.1 404 Not Found') {
			$mapa = 'https://acp.sloneczny-dust.pl/www/maps/nomap.jpg';
		}
		else {
			$mapa = $obrazek;
		}
		return $mapa;
	}
	public function ust_podstawowe_edit($post, $serwer_id, $user, $dostep){
		if(uprawnienia($dostep, $user) == 0){
      return;
    }
		$from = post_to_stdclass();
		$from->ser_a_copiekun = one("SELECT `user` FROM `acp_users` WHERE `login` LIKE '$from->ser_a_copiekun' LIMIT 1");

		query("UPDATE `acp_serwery` SET `mod` = '$from->mod', `czas_reklam` = '$from->czas_reklam', `liczba_map` = '$from->liczba_map', `fastdl` = '$from->fastdl', `link_gotv` = '$from->link_gotv', `ser_a_copiekun` = '$from->ser_a_copiekun' WHERE `serwer_id` = $serwer_id ");
		admin_log($user, "Zaktualizowano ustawienia serwera $from->mod (ID: $serwer_id)", "?x=serwery_det&serwer_id=$serwer_id");
		$_SESSION['msg'] = komunikaty("Zaktualizowano ustawienia serwera $from->mod (ID: $serwer_id)", 1);
	}
	public function wykres_pobierz_dane($i, $co, $jakie=NULL, $serwer_id, $ile=5){
		switch ($i) {
			case 'wykres_gosetti':
				switch ($co) {
					case 'data':
						$query = all("SELECT `data` FROM `acp_serwery_gosetti` WHERE `serwer_id` = $serwer_id ORDER BY `data` DESC LIMIT $ile");
						foreach ($query as $dane) {
							$dane_zwort = $dane_zwort . '"'. $dane->data.'",';
						}
						return $dane_zwort;
						break;
					case 'rank_all':
						$query = all("SELECT `gosetti_rank_all` FROM `acp_serwery_gosetti` WHERE `serwer_id` = $serwer_id ORDER BY `data` DESC LIMIT $ile");
						foreach ($query as $dane) {
							$dane_zwort = $dane_zwort . '"'. $dane->gosetti_rank_all.'",';
						}
						return $dane_zwort;
						break;
					case 'rank_tura':
						$query = all("SELECT `gosetti_rank_tura` FROM `acp_serwery_gosetti` WHERE `serwer_id` = $serwer_id ORDER BY `data` DESC LIMIT $ile");
						foreach ($query as $dane) {
							$dane_zwort = $dane_zwort . '"'. $dane->gosetti_rank_tura.'",';
						}
						return $dane_zwort;
						break;
					case 'punkty_klikniecia':
						$query = all("SELECT `gosetti_p_klik_tura` FROM `acp_serwery_gosetti` WHERE `serwer_id` = $serwer_id ORDER BY `data` DESC LIMIT $ile");
						foreach ($query as $dane) {
							$dane_zwort = $dane_zwort . '"'. $dane->gosetti_p_klik_tura.'",';
						}
						return $dane_zwort;
						break;
					case 'punkty_skiny':
						$query = all("SELECT `gosetti_p_skiny_tura` FROM `acp_serwery_gosetti` WHERE `serwer_id` = $serwer_id ORDER BY `data` DESC LIMIT $ile");
						foreach ($query as $dane) {
							$dane_zwort = $dane_zwort . '"'. $dane->gosetti_p_skiny_tura.'",';
						}
						return $dane_zwort;
						break;
					case 'punkty_pln':
						$query = all("SELECT `gosetti_p_pln_tura` FROM `acp_serwery_gosetti` WHERE `serwer_id` = $serwer_id ORDER BY `data` DESC LIMIT $ile");
						foreach ($query as $dane) {
							$dane_zwort = $dane_zwort . '"'. $dane->gosetti_p_pln_tura.'",';
						}
						return $dane_zwort;
						break;
					case 'punkty_www':
						$query = all("SELECT `gosetti_p_www_tura` FROM `acp_serwery_gosetti` WHERE `serwer_id` = $serwer_id ORDER BY `data` DESC LIMIT $ile");
						foreach ($query as $dane) {
							$dane_zwort = $dane_zwort . '"'. $dane->gosetti_p_www_tura.'",';
						}
						return $dane_zwort;
						break;
				}
				break;

			case 'wykres_graczy_morris':
				$query = all("SELECT `graczy`, `sloty`, `data` FROM `acp_serwery_logs_$jakie` WHERE `serwer_id` = $serwer_id ORDER BY `data` DESC LIMIT $ile");
				foreach ($query as $dane) {
					switch ($jakie) {
						case 'hour':
							$dane->data = substr($dane->data, 0, -3);
							break;
						case 'day':
							$dane->data = substr($dane->data, 0, -8);
							break;
						case 'month':
							$dane->data = substr($dane->data, 0, -9);
							break;
					}
					$dane->wolne_sloty = round($dane->sloty - $dane->graczy);
					$dane_zwort = $dane_zwort . "{y: '$dane->data', item1: $dane->graczy, item2: $dane->wolne_sloty},";
				}
				return $dane_zwort;
				break;

			case 'wykres_hlstats':
				$query = all("SELECT `hls_graczy`, `hls_nowych_graczy`, `hls_zabojstw`, `hls_nowych_zabojstw`, `hls_hs`, `hls_nowych_hs`, `data` FROM `acp_serwery_hlstats` WHERE `serwer_id` = $serwer_id ORDER BY `data` DESC LIMIT 20");
				foreach ($query as $dane) {
					$dane_zwort = $dane_zwort . "{y: '$dane->data', item1: $dane->hls_graczy, item2: $dane->hls_nowych_graczy, item3: $dane->hls_zabojstw, item4: $dane->hls_nowych_zabojstw, item5: $dane->hls_hs, item6: $dane->hls_nowych_hs },";
				}
				return $dane_zwort;
				break;

			case 'gosetti':
				switch ($co) {
					case 'rank':
						$query = all("SELECT `gosetti_rank_all`, `gosetti_rank_tura`, `data` FROM `acp_serwery_gosetti` WHERE `serwer_id` = $serwer_id ORDER BY `data` DESC LIMIT $ile");
						foreach ($query as $dane) {
							$dane_zwort = $dane_zwort . "{y: '$dane->data', item1: $dane->gosetti_rank_all, item2: $dane->gosetti_rank_tura},";
						}
						return $dane_zwort;
						break;
					case 'punkty':
						$query = all("SELECT `gosetti_p_klik_tura`, `gosetti_p_skiny_tura`, `gosetti_p_pln_tura`, `gosetti_p_www_tura`, `data` FROM `acp_serwery_gosetti` WHERE `serwer_id` = $serwer_id ORDER BY `data` DESC LIMIT $ile");
						foreach ($query as $dane) {
							$dane_zwort = $dane_zwort . "{y: '$dane->data', item1: $dane->gosetti_p_klik_tura, item2: $dane->gosetti_p_skiny_tura, item3: $dane->gosetti_p_pln_tura, item4: $dane->gosetti_p_www_tura},";
						}
						return $dane_zwort;
						break;

				}
				break;
		}

		return $tekst;
	}
	public function dane_cronjobs($data, $czas){
		if($czas == 0){
			$tekst = 'Aktualizacja wyłączona';
		}
		else {
			$teraz = time();
			$data_srt = strtotime($data);
			$za_ile_kolejna = $data_srt + $czas - $teraz;

			$tekst = "Kolejna za ".sek_na_tekst($za_ile_kolejna)." [Ostatnia aktualizacja: $data]";
		}
		return $tekst;
	}
	public function regulamin_edytuj($post, $serwer, $wykonujacy){
		$from = post_to_stdclass();
		$from->tekst = htmlspecialchars($from->tekst);

		$czy_jest = one("SELECT `id` FROM `acp_serwery_regulamin` WHERE `id` = $from->id");
		if(empty($czy_jest)){
			query("INSERT INTO `acp_serwery_regulamin` (`serwer_id`, `tekst`, `link`) VALUES ( $serwer, '$from->tekst', '$from->link')");
			admin_log($admin, "Dodano nowy regulamin dla serwera ID: $serwer", "?x=serwery_det&serwer_id=$serwer");
			$_SESSION['msg'] = komunikaty("Dodano nowy regulamin dla serwera ID: $serwer", 1);
			return;
		}

		query("UPDATE `acp_serwery_regulamin` SET `tekst` = '$from->tekst', `link` = '$from->link' WHERE `id` = $from->id AND `serwer_id` = $serwer");
		admin_log($admin, "Zaktualizowano regulamin (ID: $from->id) serwera ID: $serwer", "?x=serwery_det&serwer_id=$serwer");
		$_SESSION['msg'] = komunikaty("Zaktualizowano regulamin (ID: $from->id) serwera ID: $serwer", 1);
	}
	public function admin_awans($serwer, $admin, $jr, $opiekun, $copiekun, $wykonujacy){
	  $admin_ranga_aktualna = one("SELECT `srv_group` FROM `s".$serwer."_admins` WHERE `aid` = $admin LIMIT 1");
	  if($admin_ranga_aktualna == 'Opiekun Ser'){
	    $_SESSION['msg'] = komunikaty("Admin posiada najwyższą rangę jako możesz nadać z Detali Serwerow.", 2);
	    return;
	  }

	  $admin_ranga_aktualna_im = one("SELECT `immunity` FROM `s".$serwer."_srvgroups` WHERE `name` = '$admin_ranga_aktualna' LIMIT 1");
	  $admin_ranga_aktualna_id = one("SELECT `id` FROM `s".$serwer."_srvgroups` WHERE `name` = '$admin_ranga_aktualna' LIMIT 1");
	  $admin_awans_ranga = one("SELECT `name` FROM `s".$serwer."_srvgroups` WHERE `immunity` > $admin_ranga_aktualna_im ORDER BY `immunity` ASC LIMIT 1");
	  $admin_awans_ranga_id = one("SELECT `id` FROM `s".$serwer."_srvgroups` WHERE `name` = '$admin_awans_ranga' LIMIT 1");

	  query("UPDATE `s".$serwer."_admins` SET `srv_group` =  '$admin_awans_ranga' WHERE `aid` = $admin LIMIT 1;");
	  query("UPDATE `s".$serwer."_admins_servers_groups` SET `group_id` = $admin_awans_ranga_id WHERE `admin_id` = $admin LIMIT 1;");

	  $admin_name = one("SELECT `user` FROM `s".$serwer."_admins` WHERE `aid` = $admin LIMIT 1");
	  $admin_steam = one("SELECT `authid` FROM `s".$serwer."_admins` WHERE `aid` = $admin LIMIT 1");
		$serwer_id = one("SELECT `serwer_id` FROM `acp_serwery` WHERE `istotnosc` = $serwer LIMIT 1");
		admin_log_srv($serwer_id, $wykonujacy, 0, "Awansowano Admina $admin_name (STEAM: $admin_steam) z rangi $admin_ranga_aktualna na $admin_awans_ranga");

	  $_SESSION['msg'] = komunikaty("Przyznano Awans $admin_name. (ID: $admin)", 1);
	}
	public function admin_degradacja($serwer, $admin, $jr, $opiekun, $copiekun, $wykonujacy){
		$admin_ranga_aktualna = one("SELECT `srv_group` FROM `s".$serwer."_admins` WHERE `aid` = $admin LIMIT 1");

		if($admin_ranga_aktualna == 'Brak Uprawnien'){
			$_SESSION['msg'] = komunikaty("Admin nie posiada już rangi. Jest widoczny tylko i wyłącznie z powodu weryfikacji listy banow.", 2);
			return;
		}

		$admin_ranga_aktualna_im = one("SELECT `immunity` FROM `s".$serwer."_srvgroups` WHERE `name` = '$admin_ranga_aktualna' LIMIT 1");
		$admin_ranga_aktualna_id = one("SELECT `id` FROM `s".$serwer."_srvgroups` WHERE `name` = '$admin_ranga_aktualna' LIMIT 1");

		$admin_deg_ranga = one("SELECT `name` FROM `s".$serwer."_srvgroups` WHERE `immunity` < $admin_ranga_aktualna_im ORDER BY `immunity` DESC LIMIT 1");
		$admin_deg_ranga_id = one("SELECT `id` FROM `s".$serwer."_srvgroups` WHERE `name` = '$admin_deg_ranga' LIMIT 1");

		query("UPDATE `s".$serwer."_admins` SET `srv_group` =  '$admin_deg_ranga' WHERE `aid` = $admin;");
		query("UPDATE `s".$serwer."_admins_servers_groups` SET `group_id` = $admin_deg_ranga_id WHERE `admin_id` = $admin LIMIT 1;");

		$admin_name = one("SELECT `user` FROM `s".$serwer."_admins` WHERE `aid` = $admin LIMIT 1");
		$admin_steam = one("SELECT `authid` FROM `s".$serwer."_admins` WHERE `aid` = $admin LIMIT 1");
		$serwer_id = one("SELECT `serwer_id` FROM `acp_serwery` WHERE `istotnosc` = $serwer LIMIT 1");
		admin_log_srv($serwer_id, $wykonujacy, 0, "Degradowno Admina $admin_name (STEAM: $admin_steam) z rangi $admin_ranga_aktualna na $admin_deg_ranga");

		$_SESSION['msg'] = komunikaty("Zdegradowano Admina $admin_name (ID: $admin).", 1);
	}
	public function pliki_open_file($ftp_path){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $ftp_path);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HEADER, false);
		$data = curl_exec($curl);
		if($data === FALSE) {
			return;
		}
		curl_close($curl);

		$data = str_replace("\n", '<br>', $data);
		return $data;
	}

	public function plik_czytaj($path, $dostep, $admin){
		if(uprawnienia($dostep, $admin) == 0){
      return;
    }

		if(!file_exists($path)) {
			return 'Plik nie istenieje..';
		}

		$myfile = fopen($path, "r");
		$tekst = fread($myfile,filesize($path));
		fclose($myfile);

			if($tekst == '') {
				return 'Wystąpił problem, nie można wyświetlić pliku..';
			}

		$tekst = str_replace("\n", '<br>', $tekst);
		return $tekst;
	}

	public function admin_skladka($serwer, $admin, $jr, $nadajacy){
	  $admin_steam_id = one("SELECT `authid` FROM `s".$serwer."_admins` WHERE `aid` = $admin LIMIT 1");

	  $steam1 = toSteamID($admin_steam_id);
	  $steam2 = toCommunityID($admin_steam_id);

	  $end = date("Y-m-02 H:i:s", strtotime("+1 month"));

	  query("INSERT INTO `acp_uslugi_platnosci` (`ip`, `przegladarka`, `portfel_wartosc`) VALUES ( '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['HTTP_USER_AGENT']."', '0' ); ");
	  $last_id = mysql_insert_id();
	  query("INSERT INTO `acp_uslugi_zakupione` (`platnosc`, `platnosc_id`, `wartosc`, `steam`, `steam_id`, `serwer`, `data` ) VALUES ('admin', $last_id, '0', '$steam2', '$steam1', $serwer, NOW() ); ");
	  query("INSERT INTO `acp_uslugi` (`u_id`, `serwer_id`, `steam`, `steam_id`, `end`, `rodzaj`) VALUES ($nadajacy, $serwer, '$steam2', '$steam1', '$end', '1' ); ");

		$_SESSION['msg'] = komunikaty("Nadano składkę adminowi (ID: $admin).", 1);
	}

	public function get_table_serwer_file($i, $serwer_id, $dostep, $dostep_all){
		if($dostep == 1 || $dostep_all == 1) {
			$tekst = '';
			switch ($i) {
				case 'rangi':
					$query = all("SELECT * FROM `acp_serwery_rangi` WHERE `serwer_id` IN (0, $serwer_id) AND `czasowa` != 1 ORDER BY `istotnosc` DESC");
					foreach ($query as $rec) {
						$rec->komentarz = (empty($rec->komentarz)) ? 'Brak' : $rec->komentarz;

						$tekst .= '<tr>';
						$tekst .= "<td>$rec->flags</td>";
						$tekst .= "<td>$rec->tag_tabela</td>";
						$tekst .= "<td>$rec->tag_say</td>";
						$tekst .= "<td>$rec->komentarz</td>";
						$tekst .= "<td><a href='?x=serwery_konfiguracja&xx=rangi&edycja=$rec->id'><i class='fa fa-search'></i></a></td>";
						$tekst .= '</tr>';
					}
					break;
				case 'hextags':
					$query = all("SELECT * FROM `acp_serwery_hextags` WHERE `serwer_id` IN (0, $serwer_id) AND `czasowa` != 1 ORDER BY `istotnosc` DESC");
					foreach ($query as $rec) {
						$rec->komentarz = (empty($rec->komentarz)) ? 'Brak' : $rec->komentarz;

						$tekst .= '<tr>';
						$tekst .= "<td>$rec->hextags</td>";
						$tekst .= "<td>$rec->ScoreTag</td>";
						$tekst .= "<td>$rec->ChatTag</td>";
						$tekst .= "<td>$rec->komentarz</td>";
						$tekst .= "<td><a href='?x=serwery_konfiguracja&xx=hextags&edycja=$rec->id'><i class='fa fa-search'></i></a></td>";
						$tekst .= '</tr>';
					}
					break;
				case 'reklamy':
					$gdzie_array = array('S' => 'Tekst w Say', 'C' => 'Tekst w Csay', 'M' => 'Menu');
					$query = all("SELECT * FROM `acp_serwery_reklamy` WHERE `serwer_id` IN (0, $serwer_id)");
					foreach ($query as $rec) {
						$rec->komentarz = (empty($rec->komentarz)) ? 'Brak' : $rec->komentarz;
						$rec->gdzie = $gdzie_array[$rec->gdzie];

						$tekst .= '<tr>';
						$tekst .= "<td>$rec->gdzie</td>";
						$tekst .= "<td>$rec->tekst</td>";
						$tekst .= "<td><a href='?x=serwery_konfiguracja&xx=reklamy&edycja=$rec->id'><i class='fa fa-search'></i></a></td>";
						$tekst .= '</tr>';
					}
					break;
				case 'bazydanych':
					$query = all("SELECT * FROM `acp_serwery_baza` WHERE `serwer_id` IN (0, $serwer_id)");
					foreach ($query as $rec) {
						$rec->komentarz = (empty($rec->komentarz)) ? 'Brak' : $rec->komentarz;

						$tekst .= '<tr>';
						$tekst .= "<td>$rec->nazwa</td>";
						$tekst .= "<td>$rec->d_driver</td>";
						$tekst .= "<td>$rec->d_baze</td>";
						$tekst .= "<td><a href='?x=serwery_konfiguracja&xx=baza&edycja=$rec->id'><i class='fa fa-search'></i></a></td>";
						$tekst .= '</tr>';
					}
					break;
				case 'mapy':
					$query = all("SELECT * FROM `acp_serwery_mapy` WHERE `serwer_id` IN (0, $serwer_id)");
					foreach ($query as $rec) {
						$tekst .= '<tr>';
						$tekst .= "<td>$rec->nazwa</td>";
						$tekst .= "<td>$rec->display_template</td>";
						$tekst .= "<td><a href='?x=serwery_konfiguracja&xx=mapy&edycja=$rec->id'><i class='fa fa-search'></i></a></td>";
						$tekst .= '</tr>';
							$tekst .= '<table class="table table-hover">';
							$tekst .= '<tr>';
							$tekst .= '<th>Mapa</th>';
							$tekst .= '<th>Nazwa</th>';
							$tekst .= '<th>Max/Min Graczy</th>';
							$tekst .= '<th></th>';
							$tekst .= '</tr>';
							$query2 = all("SELECT * FROM `acp_serwery_mapy_det` WHERE `mapy_id` = $rec->id");
								foreach ($query2 as $rec2) {
									$rec2->max_players = (empty($rec2->max_players)) ? '-' : $rec2->max_players ;
									$rec2->min_players = (empty($rec2->min_players)) ? '-' : $rec2->max_players ;
									$tekst .= "<td>$rec2->nazwa</td>";
									$tekst .= "<td>$rec2->display</td>";
									$tekst .= "<td>$rec2->max_players/$rec2->min_players</td>";
									$tekst .= "<td><a href='?x=serwery_konfiguracja&xx=mapy&edycja_mapy=$rec2->id'><i class='fa fa-search'></i></a></td>";
									$tekst .= '</tr>';
								}
							$tekst .= '</table>';
					}
					break;

				default:
					// code...
					break;
			}
		}
		else {
			$tekst = "Nie posiadasz dostępu aby wyświetlić detale $i";
		}
		return $tekst;
	}

	public function list_adminow_ust($id){
		$id = (int)$id;
		if($id == 0):
			return '<option value="0">Ukryty</option><option value="1">Widoczny</option>';
		elseif($id == 1):
			return '<option value="1">Widoczny</option><option value="0">Ukryty</option>';
		endif;
	}
	public function list_adminow_ustawienia_edit($post, $serwer, $user, $dostep){
		if(uprawnienia($dostep, $user) == 0){
      return;
    }
		$from = post_to_stdclass();

		if(empty($from->ilosc_adminow)){
			$_POST['ilosc_adminow'] = 5;
		}
		
		$czy_istnieje = one("SELECT `id` FROM `acp_serwery_listaadminow` WHERE `serwer` = $serwer LIMIT 1");

		if(empty($czy_istnieje)):
			query("INSERT INTO `acp_serwery_listaadminow` (`serwer`, `dane`, `ilosc_adminow`) VALUES ('$serwer', '{\"pokaz_legende\":$from->pok_ukr_legenda, \"pokaz_weteran\":pok_ukr_weteran, \"pokaz_bez_uprawnien\":$from->pok_ukr_bezuprawnien, \"pokaz_opiekuna\":$from->pok_ukr_opiekun,\"pokaz_zastepce\":$from->pok_ukr_zastepca}', '$from->ilosc_adminow'); ");
		else:
			query("UPDATE `acp_serwery_listaadminow` SET `dane` = '{\"pokaz_legende\":$from->pok_ukr_legenda, \"pokaz_bez_uprawnien\":$from->pok_ukr_bezuprawnien, \"pokaz_opiekuna\":$from->pok_ukr_opiekun,\"pokaz_zastepce\":$from->pok_ukr_zastepca}', `ilosc_adminow` = '$from->ilosc_adminow' WHERE `serwer` = $serwer;");
		endif;
	}
	public function raport_opiekuna($serwer_id, $user, $dostep){
		if(uprawnienia($dostep, $user) == 0){
			return;
		}
		$serwer->id = (int)$serwer_id;
		$serwer->dane = row("SELECT `nazwa`, `mod` FROM `acp_serwery` WHERE `serwer_id` = $serwer_id LIMIT 1");
		$serwer->nazwa = $serwer->dane->nazwa;
		$serwer->mod = $serwer->dane->mod;
		$serwer->opiekun = one("SELECT `ser_a_opiekun` FROM `acp_serwery` WHERE `serwer_id` = '$serwer->id' LIMIT 1");
		$serwer->chefadmin = one("SELECT `ser_a_copiekun` FROM `acp_serwery` WHERE `serwer_id` = '$serwer->id' LIMIT 1");

		// $data->miesiac = date("m");
		// $data->rok = date("Y");

		$data->data = array(
			'ubiegly_miesiac' => date('m', strtotime("-1 month")),
			'ubiegly_rok' => date('Y', strtotime("-1 month"))
	  );

		foreach ($_POST['id'] as $key => $value) {
			$rpt->id = $_POST['id']["$key"];
			$rpt->steamid = $_POST['steamid']["$key"];
			$rpt->czas_gry = $_POST['czas_gry']["$key"];
			$rpt->nick_sb = $_POST['nick_sb']["$key"];
			$rpt->nick_steam = $_POST['nick_steam']["$key"];
			$rpt->forum_posty = $_POST['forum_posty']["$key"];
			$rpt->forum_warny = $_POST['forum_warny']["$key"];
			$rpt->skladka = $_POST['skladka']["$key"];
			$rpt->skladka_kwota = $_POST['skladka_kwota']["$key"];
			$rpt->skladka_metoda = $_POST['skladka_metoda']["$key"];
			$rpt->opinia = $_POST['opinia']["$key"];
			$rpt->srv_group = $_POST['srv_group']["$key"];
		 	insert("raport_opiekun", array('`serwer`' => "$serwer->id", '`opiekun`' => "$serwer->opiekun", '`chefadmin`' => "$serwer->chefadmin", '`steamid`' => "$rpt->steamid", '`admin_nick`' => "$rpt->nick_sb", '`admin_steam`' => "$rpt->nick_steam", '`grupa`' => "$rpt->srv_group", '`forum_posty`' => "$rpt->forum_posty", '`forum_warny`' => "$rpt->forum_warny", '`serwer_czaspolaczenia`' => "$rpt->czas_gry", '`skladka`' => "$rpt->skladka", '`skladka_kwota`' => "$rpt->skladka_kwota", '`skladka_metoda`' => "$rpt->skladka_metoda", '`opinia`' => "$rpt->opinia", '`data_raportu`' => "NOW",'`miesiac`' => "".$data->data['ubiegly_miesiac']."", '`rok`' => "".$data->data['ubiegly_rok'].""));
		}

		$raport->sb_ban = (int)$_POST['sb_ban'];
		$raport->sb_mute = (int)$_POST['sb_mute'];
		$raport->sb_gag = (int)$_POST['sb_gag'];
		$raport->sb_unban = (int)$_POST['sb_unban'];
		$raport->sb_unmute = (int)$_POST['sb_unmute'];
		$raport->sb_ungag = (int)$_POST['sb_ungag'];
		$raport->hls_graczy = (int)$_POST['hls_graczy'];
		$raport->gt_rank = (int)$_POST['gt_rank'];
		$raport->gt_low = (int)$_POST['gt_low'];
		$raport->gt_hight = (int)$_POST['gt_hight'];
		$raport->finanse_koszt = (int)$_POST['finanse_koszt'];
		$raport->sklep_uslugi = (int)$_POST['sklep_uslugi'];
		$raport->sklep_uslugi_koszt = (int)$_POST['sklep_uslugi_koszt'];
		$raport->admin_liczba = (int)$_POST['admin_liczba'];
		$raport->admin_miesiaca = $_POST['admin_miesiaca'];

		insert("raport_serwer", array('`serwer_id`' => "$serwer->id", '`mod`' => "$serwer->mod", '`nazwa`' => "$serwer->nazwa", '`hls_graczy`' => "$raport->hls_graczy", '`finanse_koszt`' => "$raport->finanse_koszt", '`sklep_uslugi`' => "$raport->sklep_uslugi", '`sklep_uslugi_koszt`' => "$raport->sklep_uslugi_koszt", '`admini_liczba`' => "$raport->admin_liczba", '`admin_miesiaca`' => "$raport->admin_miesiaca", '`gt_rank`' => "$raport->gt_rank", '`gt_low`' => "$raport->gt_low", '`gt_hight`' => "$raport->gt_hight", '`sb_ban`' => "$raport->sb_ban", '`sb_mute`' => "$raport->sb_mute", '`sb_gag`' => "$raport->sb_gag", '`sb_unban`' => "$raport->sb_unban", '`sb_unmute`' => "$raport->sb_unmute", '`sb_ungag`' => "$raport->sb_ungag", '`miesiac`' => "".$data->data['ubiegly_miesiac']."", '`rok`' => "".$data->data['ubiegly_rok'].""));

		$_SESSION['msg'] = komunikaty("Raport Opiekuna za ".$data->data['ubiegly_miesiac']."/".$data->data['ubiegly_rok']." z serwera ".$serwer->dane->nazwa." [".$serwer->dane->mod."] (ID: $serwer->id) złożony.", 1);
	 	admin_log($user, "Raport Opiekuna za ".$data->data['ubiegly_miesiac']."/".$data->data['ubiegly_rok']." z serwera ".$serwer->dane->nazwa." [".$serwer->dane->mod."] (ID: $serwer->id) złożony.", "?x=raporty&xx=raport_miesieczny");

		$czy_nagroda = one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'AdmRaport_AdmM_Nagroda' LIMIT 1");
		if($czy_nagroda == 1){
			$nagroda->steam = $raport->admin_miesiaca;
			$nagroda->steam_comunity = toCommunityID($nagroda->steam);
			$nagroda->usluga_id = one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'AdmRaport_AdmM_Nagroda_flagi' LIMIT 1");
			$nagroda->czas = one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'AdmRaport_AdmM_Nagroda_czas' LIMIT 1");
			$now = new DateTime();
			$nagroda->koniec = $now->modify("+$nagroda->czas day")->format('Y-m-d H:i:s');
			if(!empty($nagroda->steam) || $nagroda->steam != '') {
				insert("acp_uslugi", array('`user`' => 0,'`serwer`' => "$serwer->id", '`steam`' => "$nagroda->steam_comunity", "`steam_id`" => "$nagroda->steam", '`koniec`' => "$nagroda->koniec", '`rodzaj`' => "$nagroda->usluga_id"));
			}
		}
		$czy_rang = one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'AdmRaport_AdmM_tag' LIMIT 1");
		if($czy_nagroda == 1){
			$nagroda->steam = $raport->admin_miesiaca;
			$nagroda->tag_tabela = one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'AdmRaport_AdmM_tag_tabela' LIMIT 1");
			$nagroda->tag_say = one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'AdmRaport_AdmM_tag_say' LIMIT 1");
			$nagroda->color_tag = one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'AdmRaport_AdmM_color_tag' LIMIT 1");
			$nagroda->color_nick = one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'AdmRaport_AdmM_color_nick' LIMIT 1");
			$nagroda->color_tekst = one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'AdmRaport_AdmM_color_tekst' LIMIT 1");
			$nagroda->ranga_czas = one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'AdmRaport_AdmM_ranga_czas' LIMIT 1");
			$nagroda->czas = one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'AdmRaport_AdmM_Nagroda_czas' LIMIT 1");
			$now = new DateTime();
			$nagroda->koniec = $now->modify("+$nagroda->czas day")->format('Y-m-d H:i:s');
			if(!empty($nagroda->steam) || $nagroda->steam != '') {
				query("INSERT INTO `acp_serwery_hextags` (`serwer_id`, `hextags`, `ScoreTag`, `TagColor`, `ChatTag`, `ChatColor`, `NameColor`, `Force`, `istotnosc`, `czasowa`, `czasowa_end`, `komentarz`) VALUES ($serwer->id, '$nagroda->steam', '$nagroda->tag_tabela', '$nagroda->color_tag', '$nagroda->tag_say', '$nagroda->color_tag', '$nagroda->color_nick', 0, 20, '1', '$nagroda->koniec', 'Najleszy admin'); ");
			}
		}
	}
}
?>
