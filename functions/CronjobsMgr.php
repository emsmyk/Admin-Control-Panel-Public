<?
class CronjobsMgr {
  //
  // Cronjobs: CRON_STATUS
  //
  public function CRON_DOSTEP($key, $acp){
    $key = real_string($key);
    if($key == $acp){
      return 1;
    }
  }

  public function CRON_data_wykonania($conf_name){
    query("UPDATE `acp_system` SET `conf_value` = '".date("Y-m-d H:i:s")."' WHERE `acp_system`.`conf_name` = '$conf_name';");
  }
  public function CRON_data_wykonania_wymuszenie($conf_name){
    query("UPDATE `acp_system` SET `conf_value` = '0000-00-00 00:00:00' WHERE `acp_system`.`conf_name` = '$conf_name';");
  }

  //
  // Cronjobs: Podstawowy
  //
  public function aktualizuj_dane_steam($steam_api, $steam, $user){
    $ftp_path = 'https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key='.$steam_api.'&steamids='.$steam.'&format=json';

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $ftp_path);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, false);
    $data = curl_exec($curl);
    if($data === FALSE) {
      $_SESSION['msg'] = komunikaty("Plik nie istnieje (i/lub) pojawił się błąd skryptu..", 4);
    }
    curl_close($curl);

    if ($data) {
      $arraySummaries = json_decode($data, true)['response']['players'][0];
      query("UPDATE `acp_users` SET `steam_update` = NOW(), `steam_avatar` = '".$arraySummaries['avatarfull']."', `steam_login` = '".$arraySummaries['personaname']."' WHERE `user` = $user LIMIT 1; ");
    }
  }

  public function dane_steam_admin($steam_api, $steam){
    $ftp_path = 'https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key='.$steam_api.'&steamids='.$steam.'&format=json';

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $ftp_path);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, false);
    $data = curl_exec($curl);
    if($data === FALSE) {
      $_SESSION['msg'] = komunikaty("Plik nie istnieje (i/lub) pojawił się błąd skryptu..", 4);
    }
    curl_close($curl);

    if ($data) {
      $arraySummaries = json_decode($data, true)['response']['players'][0];
      return $arraySummaries;
    }
  }

  public function jsonRemoveUnicodeSequences($struct) {
    return preg_replace("/\\\\u([a-f0-9]{4})/e", "iconv('UCS-4LE','UTF-8',pack('V', hexdec('U$1')))", json_encode($struct));
  }

  //
  // Cronjobs: Statystyki przelicz dane na godzine, dzien, miesiac
  //
  public function stats_przelicz($id, $i){
    $godzine = date('Y-m-d H', strtotime("-1 hour"));
    $wczoraj = date('Y-m-d', strtotime("-1 day"));
    $miesiactemu = date('Y-m', strtotime("-1 month"));

    switch ($i) {
      case 'stats_hour':
        $dane = row("SELECT COUNT(*) AS liczba_danych, SUM(`graczy`) AS suma_graczy, SUM(`boty`) AS suma_boty, SUM(`sloty`) AS suma_sloty  FROM `acp_serwery_logs` WHERE `serwer_id` = $id AND `data` LIKE '%$godzine%'");
        // Przeliczenie do na jeden dzień
        $graczy = round($dane->suma_graczy / $dane->liczba_danych);
        $boty = round($dane->suma_boty / $dane->liczba_danych);
        $sloty = round($dane->suma_sloty / $dane->liczba_danych);

        //insert do bazdy danych
        query("INSERT INTO `acp_serwery_logs_hour` (`serwer_id`, `graczy`, `boty`, `sloty`, `suma_graczy`, `suma_botow`, `suma_sloty`, `data`) VALUES ($id, $graczy, $boty, $sloty, $dane->suma_graczy, $dane->suma_boty, $dane->suma_sloty, NOW() ); ");
        break;

      case 'stats_day':
        $dane = row("SELECT COUNT(*) AS liczba_danych, SUM(`graczy`) AS suma_graczy, SUM(`boty`) AS suma_boty, SUM(`sloty`) AS suma_sloty  FROM `acp_serwery_logs_hour` WHERE `serwer_id` = $id AND `data` LIKE '%$wczoraj%'");
        // Przeliczenie do na jeden dzień
        $graczy = round($dane->suma_graczy / $dane->liczba_danych);
        $boty = round($dane->suma_boty / $dane->liczba_danych);
        $sloty = round($dane->suma_sloty / $dane->liczba_danych);

        //insert do bazdy danych
        query("INSERT INTO `acp_serwery_logs_day` (`serwer_id`, `graczy`, `boty`, `sloty`, `suma_graczy`, `suma_botow`, `suma_sloty`, `data`) VALUES ($id, $graczy, $boty, $sloty, $dane->suma_graczy, $dane->suma_boty, $dane->suma_sloty, NOW() ); ");
        break;

      case 'stats_month':
        $dane = row("SELECT COUNT(*) AS liczba_danych, SUM(`graczy`) AS suma_graczy, SUM(`boty`) AS suma_boty, SUM(`sloty`) AS suma_sloty  FROM `acp_serwery_logs_day` WHERE `serwer_id` = $id AND `data` LIKE '%$miesiactemu%'");
        // Przeliczenie do na jeden dzień
        $graczy = round($dane->suma_graczy / $dane->liczba_danych);
        $boty = round($dane->suma_boty / $dane->liczba_danych);
        $sloty = round($dane->suma_sloty / $dane->liczba_danych);

        //insert do bazdy danych
        query("INSERT INTO `acp_serwery_logs_month` (`serwer_id`, `graczy`, `boty`, `sloty`, `suma_graczy`, `suma_botow`, `suma_sloty`, `data`) VALUES ($id, $graczy, $boty, $sloty, $dane->suma_graczy, $dane->suma_boty, $dane->suma_sloty, NOW() ); ");
        break;
    }
    return "<p>Zaktualizowano $i dla $id</p>";
  }
  public function stats_gosetti($serwer_id){
    $serwer_ip = one("SELECT `ip` FROM `acp_serwery` WHERE `serwer_id` = $serwer_id;");
    $serwer_port = one("SELECT `port` FROM `acp_serwery` WHERE `serwer_id` = $serwer_id;");
    $serwer = ''.$serwer_ip.':'.$serwer_port.'';

    $gosetti = file_get_html("https://gosetti.pl/serwery/$serwer");
    $info[$serwer_id]['gosetti_rank'] = $gosetti->find(".greenbar-top > div > .description, .greenbar-top > div > .value",1)->innertext;
    $info[$serwer_id]['gosetti_rank_tura'] = $gosetti->find(".greenbar-top > div > .description, .greenbar-top > div > .value",3)->innertext;
    $info[$serwer_id]['gosetti_tura_klik'] = $gosetti->find(".greenbar-bottom > div > .text > .row-bigger",0)->innertext;
    $info[$serwer_id]['gosetti_tura_klik'] = str_replace(' ', '', $info[$serwer_id]['gosetti_tura_klik']);
    $info[$serwer_id]['gosetti_tura_skiny'] = $gosetti->find(".greenbar-bottom > div > .text > .row-bigger",1)->innertext;
    $info[$serwer_id]['gosetti_tura_skiny'] = str_replace(' ', '', $info[$serwer_id]['gosetti_tura_skiny']);
    $info[$serwer_id]['gosetti_tura_wpl'] = $gosetti->find(".greenbar-bottom > div > .text > .row-bigger",2)->innertext;
    $info[$serwer_id]['gosetti_tura_wpl'] = str_replace(' ', '', $info[$serwer_id]['gosetti_tura_wpl']);
    $info[$serwer_id]['gosetti_tura_www'] = $gosetti->find(".greenbar-bottom > div > .text > .row-bigger",3)->innertext;
    $info[$serwer_id]['gosetti_tura_www'] = str_replace(' ', '', $info[$serwer_id]['gosetti_tura_www']);

    query("INSERT INTO `acp_serwery_gosetti` ( `serwer_id`, `data`, `gosetti_rank_all`, `gosetti_rank_tura`, `gosetti_p_klik_tura`, `gosetti_p_skiny_tura`, `gosetti_p_pln_tura`, `gosetti_p_www_tura`)
    VALUES ($serwer_id, NOW(), ".$info[$serwer_id]['gosetti_rank'].", ".$info[$serwer_id]['gosetti_rank_tura'].", ".$info[$serwer_id]['gosetti_tura_klik'].", ".$info[$serwer_id]['gosetti_tura_skiny'].", ".$info[$serwer_id]['gosetti_tura_wpl'].", ".$info[$serwer_id]['gosetti_tura_www']." );");

    return "<p>HLStats: Zaktualizowano dane podstawowe serwera ID: $serwer_id (IP: $serwer)</p>";
  }
  public function stats_hlstats($serwer_id){
    $hlstats = one("SELECT `istotnosc` FROM `acp_serwery` WHERE `serwer_id` = $serwer_id;");

    if($hlstats != '0' || $hlstats != '') {
      $hlstats = file_get_html("http://hlstats.sloneczny-dust.pl/hlstats.php?game=$hlstats");
			$info[$serwery->serwer_id]['hls_graczy'] = $hlstats->find(".data-table-head b",0)->innertext;
			$info[$serwery->serwer_id]['hls_graczy'] = str_replace(',', '', $info[$serwery->serwer_id]['hls_graczy']);
      $hls_graczy = $info[$serwery->serwer_id]['hls_graczy'];
			$info[$serwery->serwer_id]['hls_nowychgraczy'] = $hlstats->find(".data-table-head b",1)->innertext;
			$info[$serwery->serwer_id]['hls_nowychgraczy'] = str_replace(',', '', $info[$serwery->serwer_id]['hls_nowychgraczy']);
			$info[$serwery->serwer_id]['hls_nowychgraczy'] = str_replace('+', '', $info[$serwery->serwer_id]['hls_nowychgraczy']);
      $hls_nowychgraczy = $info[$serwery->serwer_id]['hls_nowychgraczy'];
			$info[$serwery->serwer_id]['hls_zab'] = $hlstats->find(".data-table-head b",2)->innertext;
			$info[$serwery->serwer_id]['hls_zab'] = str_replace(',', '', $info[$serwery->serwer_id]['hls_zab']);
      $hls_zab = $info[$serwery->serwer_id]['hls_zab'];
			$info[$serwery->serwer_id]['hls_nowychzab'] = $hlstats->find(".data-table-head b",3)->innertext;
			$info[$serwery->serwer_id]['hls_nowychzab'] = str_replace(',', '', $info[$serwery->serwer_id]['hls_nowychzab']);
			$info[$serwery->serwer_id]['hls_nowychzab'] = str_replace('+', '', $info[$serwery->serwer_id]['hls_nowychzab']);
      $hls_nowychzab = $info[$serwery->serwer_id]['hls_nowychzab'];
			$info[$serwery->serwer_id]['hls_hs'] = $hlstats->find(".data-table-head b",4)->innertext;
			$info[$serwery->serwer_id]['hls_hs'] = str_replace(',', '', $info[$serwery->serwer_id]['hls_hs']);
      $hls_hs = $info[$serwery->serwer_id]['hls_hs'];
			$info[$serwery->serwer_id]['hls_nowychhs'] = $hlstats->find(".data-table-head b",3)->innertext;
			$info[$serwery->serwer_id]['hls_nowychhs'] = str_replace(',', '', $info[$serwery->serwer_id]['hls_nowychhs']);
			$info[$serwery->serwer_id]['hls_nowychhs'] = str_replace('+', '', $info[$serwery->serwer_id]['hls_nowychhs']);
      $hls_nowychhs = $info[$serwery->serwer_id]['hls_nowychhs'];

      query("INSERT INTO `acp_serwery_hlstats` (`serwer_id`, `data`, `hls_graczy`, `hls_nowych_graczy`, `hls_zabojstw`, `hls_nowych_zabojstw`, `hls_hs`, `hls_nowych_hs`) VALUES ($serwer_id, NOW(), $hls_graczy, $hls_nowychgraczy, $hls_zab, $hls_nowychzab, $hls_hs, $hls_nowychhs);");


      return "<p>HLStats pobrano i wgrano dane serwera ID: $serwer_id</p>";
    }
  }
  public function stats_hlstats_top50($ftp_path, $serwer_id){
    $hlx_ust->hlx_top50 = one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'hlx_top50' LIMIT 1");
    $hlx_ust->hlx_top_rangi = one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'hlx_top_rangi' LIMIT 1");
    $hlx_ust->hlx_ilosc = one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'hlx_ilosc' LIMIT 1");
    $hlx_ust->hlx_top50_tag_tabela = one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'hlx_top50_tag_tabela' LIMIT 1");
    $hlx_ust->hlx_top50_tag_say = one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'hlx_top50_tag_say' LIMIT 1");
    $hlx_ust->hlx_top50_color_tag = one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'hlx_top50_color_tag' LIMIT 1");
    $hlx_ust->hlx_top50_color_nick = one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'hlx_top50_color_nick' LIMIT 1");
    $hlx_ust->hlx_top50_color_tekst = one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'hlx_top50_color_tekst' LIMIT 1");

    if($hlx_ust->hlx_top50 != '1'){
      return "<p>HLstatsX Top50: Pobieranie danych zostało wyłączone</p>";
    }

    $srv = one("SELECT `prefix_hls` FROM `acp_serwery` WHERE `serwer_id` = $serwer_id LIMIT 1");
    if(empty($srv)){
      return 'brak prefixu hlstats serwera ';
    }

    $ftp_path = "$ftp_path&srv=$srv";
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $ftp_path);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, false);
    $data = curl_exec($curl);
    if($data === FALSE) {
      return;
    }
    curl_close($curl);

    $data = preg_replace("/\\\\u([a-f0-9]{4})/e", "iconv('UCS-4LE','UTF-8',pack('V', hexdec('U$1')))", $data);
    query("INSERT INTO `acp_serwery_hlstats_top` (`serwer_id`, `data`, `dane`) VALUES ($serwer_id, NOW(), '$data');");

    //
    // nadanie top3 rang
    //
    if($hlx_ust->hlx_top_rangi != '1'){
      return "<p>HLstatsX Top50: Nagrody dla top X zostały wyłączone</p>";
    }

    $data = json_decode($data);
    $i=0;
    $top=1;
    foreach ($data as $dane) {
      if($i < $hlx_ust->hlx_ilosc) {
        query("INSERT INTO `acp_serwery_hextags` (`id`, `serwer_id`, `hextags`, `ScoreTag`, `TagColor`, `ChatTag`, `ChatColor`, `NameColor`, `Force`, `istotnosc`, `czasowa`, `czasowa_end`, `komentarz`) VALUES (NULL, '$serwer_id', 'STEAM_0:$dane->steam', '♜ ".$hlx_ust->hlx_top50_tag_tabela."".$top."', '$hlx_ust->hlx_top50_color_tag', '♜ ".$hlx_ust->hlx_top50_tag_say."".$top."', '$hlx_ust->hlx_top50_color_nick', '$hlx_ust->hlx_top50_color_tekst', '0', '2', '1', NOW()+INTERVAL 1 DAY, 'Hlx Top50 ($dane->lastName)'); ");
        $i +=1;
        $top +=1;
      }
    }
    return "<p>HLstatsX Top50: Przyznano nagrody za top x (SERWER ID: $serwer_id)</p>";
  }
  //
  // Cronjobs: Optymalizator
  //
  public function optym_kasowanie_log_serwerow($limit, $day){
    query("DELETE FROM `acp_serwery_logs` WHERE `data` < NOW() - INTERVAL $day DAY LIMIT $limit;");
    return "<p>Skasowano logi serwerów w starsze niż $day dni. (Ograniczono ilość kasowanych logów do $limit)</p>";
  }
  public function optym_usuwanie_starych_uslug($limit, $day){
    query("DELETE FROM `acp_uslugi` WHERE `koniec` < NOW() - INTERVAL $day HOUR LIMIT $limit;");
    return "<p>Skasowano usługi starsze niż $day godzin. (Ograniczono ilość kasowanych logów do $limit)</p>";
  }
  public function optym_usuwanie_starych_reklam($limit, $day){
    query("DELETE FROM `acp_serwery_reklamy` WHERE `czasowa_end` < NOW() - INTERVAL $day DAY AND `czasowa` = 1");
    return "<p>Skasowano reklamy starsze niż $day dni. (Ograniczono ilość kasowanych reklam do $limit)</p>";
  }
  public function optym_usuwanie_starych_rang($limit, $day){
    query("DELETE FROM `acp_serwery_hextags` WHERE `czasowa_end` < NOW() - INTERVAL $day HOUR AND `czasowa` = 1");
    return "<p>Skasowano rangi starsze niż $day godzin. (Ograniczono ilość kasowanych rang do $limit)</p>";
  }
  public function optym_usuwanie_starych_wiadomosci($limit, $day){
    query("DELETE FROM `acp_messages` WHERE `m_date` < NOW() - INTERVAL $day DAY AND `m_type` = 0 LIMIT $limit");
    return "<p>Skasowano rangi starsze niż $day godzin. (Ograniczono ilość kasowanych rang do $limit)</p>";
  }
  public function optym_powiadomienia_odczytane($day){
    query("UPDATE `acp_users_notification` SET `read` = '0', `read_date` = NOW() WHERE `data` < NOW() - INTERVAL $day DAY AND `read` = 1; ");
    return "<p>Powiadomienia starsze niż $day dni zostały oznaczone jako odczytane</p>";
  }
  public function optym_powiadomienia_usun($day){
    query("DELETE FROM `acp_users_notification` WHERE `read_date` < NOW() - INTERVAL $day DAY AND `read` = 0;");
    return "<p>Powiadomienia odczytane oraz starsze niż $day dni zostały skasowane</p>";
  }
  public function optym_sprzatanie_po_logach_optymalizatora($day){
    query("DELETE FROM `acp_log` WHERE `data` < NOW() - INTERVAL $day DAY AND `page` = '?x=cronjobs_optym';");
    return "<p>Skasowano logi optymalizatora starsze niż $day dni</p>";
  }

  public function optm_kasuj_logi_nie_istniejacych_serwerow($co){
    $kas = all("SELECT `id`, `serwer_id` AS `serwer`,
      (SELECT `mod` FROM `acp_serwery` WHERE `serwer_id` = `serwer` LIMIT 1) AS `mod`,
      (SELECT `ip` FROM `acp_serwery` WHERE `serwer_id` = `serwer` LIMIT 1) AS `ip`
    FROM `$co`");

    $ilosc_skasowanych = 0;

    foreach($kas as $row){
      if(is_null($row->mod) || is_null($row->ip)){
        $ilosc_skasowanych = $ilosc_skasowanych +1;
        query("DELETE FROM `$co` WHERE `id` = $row->id");
      }
      if(!next($kas)){
        query("OPTIMIZE TABLE `$co`");
        return "<p>Skasowano $ilosc_skasowanych logów - $co</p>";
      }
    }
  }
  public function optm_kasuj_logi_nie_istniejacych_serwerow_cache($limit, $day){
    $kas = all("SELECT `get` FROM `acp_cache_api` WHERE `modification_data` < NOW() - INTERVAL $day DAY LIMIT $limit");

    $ilosc_skasowanych = 0;

    foreach($kas as $row){
      $ilosc_skasowanych = $ilosc_skasowanych +1;
      query("DELETE FROM `acp_cache_api` WHERE `get` = '$row->get' LIMIT 1");

      if(!next($kas)){
        query("OPTIMIZE TABLE `acp_cache_api`");
        return "<p>Skasowano $ilosc_skasowanych danych cache</p>";
      }
    }
  }


  //
  // Cronjobs: Edycja zdalna plików serwerowych
  //

  // @type = FTP_BINARY (mp3) / FTP_ASCII (file txt, cfg)
  public function ftp_upload($serwer, $ftp, $files, $scan){
    if(empty($ftp->user) || empty($ftp->haslo)){
      echo "<p>BRAK DANYCH FTP: Login: $ftp->user Hasło: $ftp->haslo</p>";
    }
    // Połączenie ftp do serwera
    $conn_id = ftp_connect($ftp->serwer);
  	if($conn_id == false){
      insert("acp_serwery_bledy", array('`serwer_id`' => "$serwer", '`modul`' => "?x=cronjobs_serwer", '`tekst`' => "[FTP] Błąd połaczenia 1", '`tekst_admin`' => "FTP open connection failed to $ftp->serwer" ));
  		return false;
  	}
    $login_result = ftp_login($conn_id, $ftp->user, $ftp->haslo);
    if((!$conn_id) || (!$login_result)){
      insert("acp_serwery_bledy", array('`serwer_id`' => "$serwer", '`modul`' => "?x=cronjobs_serwer", '`tekst`' => "[FTP] Błąd połaczenia 2", '`tekst_admin`' => "FTP connection has failed! Attempted to connect to $ftp->serwer for user $ftp->user" ));
      return false;
    }

    // Wgrywanie plików
    foreach ($files as $value){
      if(strlen($value->ftp_directory) > 0){
        if(!ftp_chdir($conn_id, $value->ftp_directory)) {
          // probujemy tworzyc katalogi
          $value->tworze_katalogi = explode("/", $value->ftp_directory);
          $value->fullpath = "";

          foreach($value->tworze_katalogi as $part){
            if(empty($part)){
              $value->fullpath .= "/";
              continue;
            }
            $value->fullpath .= $part."/";
            if(@ftp_chdir($conn_id, $value->fullpath)){
               ftp_chdir($conn_id, $value->fullpath);
            }
            else {
              if(@ftp_mkdir($conn_id, $part)){
                      ftp_chdir($conn_id, $part);
              }
              else {
                $return = false;
              }
            }
          }
          
          insert("acp_serwery_bledy", array('`serwer_id`' => "$value->serwer_id", '`modul`' => "$value->modul", '`tekst`' => "[FTP] Wystąpił problem ze zmianą katalogu przy wgrywaniu pliku $value->ftp_dest_file_name", '`tekst_admin`' => "Błąd zmiany katalogu ($value->ftp_directory), pliku $value->ftp_dest_file_name na serwerze ftp $ftp->serwer [Serwer ID: $serwer] " ));
          return false;
        }
      }
      ftp_pasv ($conn_id, true);
      if($value->type_upload == 'FTP_ASCII'){
        $upload = ftp_put($conn_id, $value->ftp_dest_file_name, $value->ftp_source_file_name, FTP_ASCII);
      }
      elseif($value->type_upload == 'FTP_BINARY') {
        $upload = ftp_put($conn_id, $value->ftp_dest_file_name, $value->ftp_source_file_name, FTP_BINARY);
      }

      if (!$upload) {
        insert("acp_serwery_bledy", array('`serwer_id`' => "$value->serwer_id", '`modul`' => "$value->modul", '`tekst`' => "[FTP] Przesłanie pliku $value->ftp_dest_file_name nie powiodło się", '`tekst_admin`' => "$ftp->serwer: Wysłanie pliku $value->ftp_dest_file_name nie zrealizowane poprawnie" ));
        // dodatkowe powiadomienie wgryawrki ze sie nie udało wgrać pliku
        if(isset($value->wgrywarka_file_id)){
          query("UPDATE `acp_wgrywarka` SET `status` = '-1' WHERE `id` = $value->wgrywarka_file_id;");
        }
        return false;
      } else {
        echo "<p>[Serwer ID: $serwer] [Moduł: $value->modul] Wgrano plik " . $value->ftp_dest_file_name . " (Typ wysłania: $value->type_upload)</p>";
      }

      // DODATKOWE POWIADOMIENIE
      // o wgraniu plikow takich jak rangi, reklmay, roundosund
      if(isset($value->info_wykonanie) && empty($value->special_table)){
        DataWykonania($value->info_wykonanie);
      }
      elseif(isset($value->info_wykonanie)){
        DataWykonania($value->info_wykonanie, $value->special_table);
      }
      // o wgraniu prac z wgrywarki
      if(isset($value->wgrywarka_file_id)){
        query("UPDATE `acp_wgrywarka` SET `status` = '1' WHERE `id` = $value->wgrywarka_file_id;");
      }


      // powrót do katalogu pierwszego.
      $aPath = explode('/',ftp_pwd($conn_id));
      $sHomeDir = str_repeat('../', count($aPath) - 1);
      ftp_chdir($conn_id, $sHomeDir);
    }
    // skanowanie folderow
    foreach ($scan as $value) {
      ftp_chdir($conn_id, $value->katalog);
      switch ($value->type) {
          case 'nlist':
            $file_list = ftp_nlist($conn_id, ".");
            break;
          case 'rawlist':
            $file_list = ftp_rawlist($conn_id, ".");
            break;
        }
        $file_list = json_encode($file_list, JSON_PARTIAL_OUTPUT_ON_ERROR);

        if(!empty(one("SELECT `get` FROM `acp_cache_api` WHERE `get`='$value->acp_cache_api'; "))){
          query("DELETE FROM `acp_cache_api` WHERE `acp_cache_api`.`get` = '$value->acp_cache_api'; ");
          query("INSERT INTO `acp_cache_api` (`get`, `dane`) VALUES ('$value->acp_cache_api', '".$file_list."'); ");
        }
        else {
          query("INSERT INTO `acp_cache_api` (`get`, `dane`) VALUES ('$value->acp_cache_api', '".$file_list."'); ");
        }

        // DODATKOWE POWIADOMIENIE
        // o wgraniu plikow takich jak rangi, reklmay, roundosund
        if(isset($value->info_wykonanie) && empty($value->special_table)){
          DataWykonania($value->info_wykonanie);
        }
        elseif(isset($value->info_wykonanie)){
          DataWykonania($value->info_wykonanie, $value->special_table);
        }

      // powrót do katalogu pierwszego.
      $aPath = explode('/',ftp_pwd($conn_id));
      $sHomeDir = str_repeat('../', count($aPath) - 1);
      ftp_chdir($conn_id, $sHomeDir);
    }

    // rozłaczenie z ftp
    ftp_close($conn_id);
  	return true;
  }

  public function delete_old_files($folderName){
    if (file_exists($folderName)) {
      foreach (new DirectoryIterator($folderName) as $fileInfo) {
        if ($fileInfo->isDot()) {
          continue;
        }
        if ($fileInfo->isFile() && time() - $fileInfo->getCTime() >= 1) {
            unlink($fileInfo->getRealPath());
        }
      }
    }
  }
}
?>
