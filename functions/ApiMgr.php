<?php
class ApiMgr{
  /*
    Funkcja do pobierania danych
      @system: sb, hls, inne wpisane w API
      @page: adres strony ACP
      @host: Host connect db
      @db: Baza connect db
      @user: User connect db
      @pass: Hasło connect db
      @more: dane dodatkowe w adresie www api
      @get_in: podział na dwa pliki API - GET (Pobieranie danych), IN (Dodawanie danych)
      @live: czas w sekundach jak długo utrzymywać plik json
  */
  public function api_11_2020($system, $page, $host, $db, $user, $pass, $more='', $get_in='GET', $live=null){
    switch ($get_in) {
      case 'GET':
        $get_in = "API_GET.php";
        break;
      case 'IN':
        $get_in = "API_IN.php";
        break;
    }

    $ftp_path = "$page/api/$get_in?x=$system&h=$host&u=$user&p=$pass&db=$db$more";
    if(!is_null($live)){
      $ftp_path .= "&lv=$live";
    }

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $ftp_path);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, false);
    $data = curl_exec($curl);
    if($data === FALSE) {
      return 'Błąd Połączenia API';
    }
    curl_close($curl);

    return json_decode($data);
  }

  /*
    Funkcja do pobierania danych
      @system: sb, hls
      @page: adres strony ACP
      @host: Host connect db
      @db: Baza connect db
      @user: User connect db
      @pass: Hasło connect db
      @more: dane dodatkowe w adresie www api
  */
  public function API($system, $page, $host, $db, $user, $pass, $more){
    switch ($system) {
      case 'sb':
        $system = "api_sourcebans.php";
        break;
      case 'hls':
        $system = "api_hlstats.php";
        break;
    }
    $ftp_path = "$page/api/$system?h=$host&u=$user&p=$pass&db=$db$more";
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $ftp_path);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, false);
    $data = curl_exec($curl);
    if($data === FALSE) {
      return 'Błąd Połączenia API';
    }
    curl_close($curl);

    $data = json_decode($data);
    return $data;
  }
}
?>
