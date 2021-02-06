<?
function post_to_stdclass(){
  $form = new stdClass();
  foreach ($_POST as $key => $value) {
    $form->$key = $value;
  }
  return $form;
}

function reload($where){ header("Location: ?x=$where"); }

function czas_relatywny( $data_wejsciowa ) {
  $roznica_czasu = time() - strtotime( $data_wejsciowa );
  if( $roznica_czasu < 0 ) { die(); }
  $okres = array('sekund', 'minut', 'godzin', null, 'dni');
  $dlugosc = array(60,60,24,3,31);

  for($j = 0; $roznica_czasu >= $dlugosc[$j]; $j++) { if( !isset($dlugosc[$j]) ) { break; } if( $j<3) { $roznica_czasu /= $dlugosc[$j]; } }
  $roznica_czasu = round(abs($roznica_czasu));

  switch( $j ) {
          case 0: case 1: case 2:
                  switch( $roznica_czasu ) {
                          case 1: $okres[$j] .= 'a'; break;
                          case 2:case 3:case 4:case 22:case 23:case 24:case 32:case 33:case 34:case 42:case 43:case 44:case 52:case 53:case 54: $okres[$j] .= 'y'; break;
                  } break;
          case 3:
                  switch( $roznica_czasu ) {
                          case 1: $okres[$j] = 'wczoraj'; break;
                          case 2: $okres[$j] = 'przedwczoraj'; break;
                          case 3: $j=4; break;
                  } break;
  }
  if( $j==0 or $j==1 or $j==2 or $j == 4 ) { return $roznica_czasu.' '.$okres[$j].' temu, '.date("H:i",strtotime( $data_wejsciowa )); }
  elseif( $j == 3 ) { return $okres[$j].', '.date("H:i",strtotime( $data_wejsciowa )); }
  elseif( $j == 5 ) { return date("d-m-Y H:i",strtotime( $data_wejsciowa )); }
}
function sek_na_tekst($sekundy){
  $czas = round($sekundy);
  return sprintf('%02d godz. %02d min. %02d sek.', ($czas/3600),($czas/60%60), $czas%60);
}

function limit_text($text, $length){
    if( strlen( $text) < $length) { return $text; }
    list( $wrapped) = explode("\n", wordwrap( $text, $length));
    $remainder = substr( $text, strlen( $wrapped));
    $wrapped .= ' ... ';
    preg_match_all( '#<span class="highlight">[^<]+</span>#i', $remainder, $matches);
    $wrapped .= implode( ', ', $matches[0]);
    return $wrapped;
}

function komunikaty($text, $rodzaj) {
  $rodzaj_array = array(1 => 'success', 2 => 'info', 3 => 'warning', 4 => 'danger');
	$rodzaj = (int)$rodzaj;
	$text = real_string($text);
  return "<div class='alert alert-".$rodzaj_array[$rodzaj]." alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>$text</div>";
}
function komunikaty_rozbudowany($icon, $tytul, $tekst, $rodzaj) {
  $rodzaj_array = array(1 => 'success', 2 => 'info', 3 => 'warning', 4 => 'danger');
	$rodzaj = (int)$rodzaj;
	$text = $text;
	return '<div class="alert alert-'.$rodzaj_array[$rodzaj].' alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon '.$icon.'"></i> '.$tytul.'</h4>
                '.$tekst.'
              </div>';
}
function komunikat_and_powiadomienie($id){
  $id = (int)$id;
  if(empty($id)){
    return;
  }
  $powiadomienie_dane = row("SELECT `id`, `icon`, `text`, `data`, `read` FROM `acp_users_notification` WHERE `id` = $id LIMIT 1");
  if($powiadomienie_dane->read == 1) { powiadomienie_odczytaj($powiadomienie_dane->id); }
  return komunikaty_rozbudowany($powiadomienie_dane->icon, "Powiadomienie: $powiadomienie_dane->id zostało oznaczone jako odczytane", "$powiadomienie_dane->text <br> Data: $powiadomienie_dane->data ", 2);
}
function powiadomienie($tbl, $link, $text, $icon) {
  $tbl = array_unique($tbl);
	foreach ($tbl as $i => $value) {
		$user = (int)$tbl[$i];
		mysql_query("INSERT INTO `acp_users_notification` (`u_id`, `link`, `text`, `icon`) VALUES ('$user', '$link', '$text', '$icon'); ");
	}
}
function powiadomienie_odczytaj($id) {
	query("UPDATE `acp_users_notification` SET `read` = '0' WHERE `id` = $id;");
}
function clean($string) {
	$aReplacePL = array('ą' => 'a', 'ę' => 'e', 'ś' => 's', 'ć' => 'c', 'ó' => 'o', 'ń' => 'n', 'ż' => 'z', 'ź' => 'z', 'ł' => 'l', 'Ą' => 'A', 'Ę' => 'E', 'Ś' => 'S', 'Ć' => 'C', 'Ó' => 'O', 'Ń' => 'N', 'Ż' => 'Z', 'Ź' => 'Z', 'Ł' => 'L');
   $string = str_replace(array_keys($aReplacePL), array_values($aReplacePL), $string);
   $string = strtolower($string);
   $string = str_replace(' ', '-', $string);
   $string = preg_replace('/[^0-9a-z\-]+/', '', $string);
   $string = preg_replace('/[\-]+/', '_', $string);
   $string = trim($string, '-');
   return $string;
}
function admin_log($user, $tekst, $link='#') {
	$page = '?'.$_SERVER['QUERY_STRING'];
  $link = real_string($link);

  return insert("acp_log", array('`page`' => "$page", '`user`' => "$user", '`tekst`' => "$tekst", '`link`' => "$link" ));
}
function admin_log_srv($serwer, $user, $object, $tekst) {
	$page = '?'.$_SERVER['QUERY_STRING'];
  $object = json_encode($object);
	query("INSERT INTO `acp_log_serwery` (`id`, `page`, `serwer_id` ,`user`, `tekst`, `data`) VALUES (NULL, '$page', '$serwer', '$user', '$tekst', NOW() ); ");
}
function admin_log_srv_time($serwer, $user, $user2, $tekst, $date) {
	$page = '?'.$_SERVER['QUERY_STRING'];
	query("INSERT INTO `acp_log_serwery` (`id`, `page`, `serwer_id` ,`user`, `user_2`, `tekst`, `data`) VALUES (NULL, '$page', '$serwer', '$user', '$user2', '$tekst','$date' ); ");
}

function js_table($name, $name2=0, $name3=0, $sort='asc', $sort2 = 'asc', $sort3 = 'asc'){
  $tabela = '
  <script>
    $(function () {
    $.extend( true, $.fn.dataTable.defaults, { "language": { "processing":     "Przetwarzanie...", "search":         "Szukaj:", "lengthMenu":     "Pokaż _MENU_ pozycji", "info":           "Pozycje od _START_ do _END_ z _TOTAL_ łącznie", "infoEmpty":      "Pozycji 0 z 0 dostępnych", "infoFiltered":   "(filtrowanie spośród _MAX_ dostępnych pozycji)", "infoPostFix":    "", "loadingRecords": "Wczytywanie...", "zeroRecords":    "Nie znaleziono pasujących pozycji", "emptyTable":     "Brak danych", "paginate": { 	"first":      "Pierwsza", 	"previous":   "Poprzednia", 	"next":       "Następna", 	"last":       "Ostatnia" }, "aria": { 	"sortAscending": ": aktywuj, by posortować kolumnę rosnąco", 	"sortDescending": ": aktywuj, by posortować kolumnę malejąco" } } } );';

  $tabela .= '$(\''.$name.'\').DataTable({ responsive: true, "iDisplayLength": 25,  "order": [[ 0, "'.$sort.'" ]] });';
  if($name2 != 0){
    $tabela .= '$(\''.$name2.'\').DataTable({ responsive: true, "iDisplayLength": 25,  "order": [[ 0, "'.$sort2.'" ]] });';
  }
  if($name3 != 0){
    $tabela .= '$(\''.$name3.'\').DataTable({ responsive: true, "iDisplayLength": 25,  "order": [[ 0, "'.$sort3.'" ]] });';
  }
  $tabela .= '}) </script>';

  return $tabela;
}
function js_table_defaults(){
  $tabela = '<script>
    $(function () {
    $.extend( true, $.fn.dataTable.defaults, { "language": { "processing":     "Przetwarzanie...", "search":         "Szukaj:", "lengthMenu":     "Pokaż _MENU_ pozycji", "info":           "Pozycje od _START_ do _END_ z _TOTAL_ łącznie", "infoEmpty":      "Pozycji 0 z 0 dostępnych", "infoFiltered":   "(filtrowanie spośród _MAX_ dostępnych pozycji)", "infoPostFix":    "", "loadingRecords": "Wczytywanie...", "zeroRecords":    "Nie znaleziono pasujących pozycji", "emptyTable":     "Brak danych", "paginate": { 	"first":      "Pierwsza", 	"previous":   "Poprzednia", 	"next":       "Następna", 	"last":       "Ostatnia" }, "aria": { 	"sortAscending": ": aktywuj, by posortować kolumnę rosnąco", 	"sortDescending": ": aktywuj, by posortować kolumnę malejąco" } } } );
  }) </script>';
  return $tabela;
}
function js_table_one($name, $sort='asc', $kolumna=0, $ilosc=10){
  $tabela = '<script> $(function () {';
  $tabela .= '$(\''.$name.'\').DataTable({ responsive: true, "iDisplayLength": '.$ilosc.',  "order": [[ '.$kolumna.', "'.$sort.'" ]] });';
  $tabela .= '}) </script>';
  return $tabela;
}

function uprawnienia($dostep, $user){
  $more_user = row("SELECT `role`, `grupa` FROM `acp_users` WHERE `user`= $user LIMIT 1");

  // gdy nie ma ustawionej żadnej grupy pooglądowej
  if(empty($_SESSION['acp_grupa_sesja']) && empty($_SESSION['acp_grupa_sesja_nazwa'])):
    // gdy jest dostep
    if($dostep == 1):
      return 1;
    // gdy user jest rootem
    elseif($more_user->role == 1):
      return 1;
    // brak dostepu
    else:
      $_SESSION['msg'] = komunikaty("Nie posiadasz dostępu do tej funkcji.", 3);
      return 0;
    endif;
  // gdy mamy nadaną grupę pooglądową
  elseif((int)$_SESSION['acp_grupa_sesja'] != (int)$more_user->grupa):
    if($dostep == 1):
      return 1;
    endif;
  // gdy nic nie mamy
  else:
    return 0;
  endif;

  // $role = one("SELECT `role` FROM `acp_users` WHERE `user`= $user LIMIT 1");
  // if($role == 1 || $dostep == 1) {
  //   return 1;
  // }
  // else {
  //   $_SESSION['msg'] = komunikaty("Nie posiadasz dostępu do tej funkcji.", 3);
  //   return 0;
  // }
}

function encrypt_decrypt($action, $string) {
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $secret_key = 'g3r2\reT\RD28YCw/%3E#4Szd';
    $secret_iv = 'NyGjxx5#mj<+dpF>bHNuUxR<>';
    // hash
    $key = hash('sha256', $secret_key);

    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    if ( $action == 'encrypt' ) {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    } else if( $action == 'decrypt' ) {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
    return $output;
}

function daj_bledy($OnOff, $page, $get){
  if($OnOff === 1){
    if($page === $get){
      ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
    }
  }
}

function tytul_strony($tekst){
  $nazwa = one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'acp_nazwa' LIMIT 1");
  echo '<script>
   document.title = "'.$nazwa.' | '.$tekst.'";
   </script>';
}

function generujLosowyCiag($length = 10, $alfabet=true) {
  if($alfabet==true){
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  }
  else {
    $characters = '0123456789';
  }
  $charactersLength = strlen($characters);
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
  }
  return $randomString;
}
?>
