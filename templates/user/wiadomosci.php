<?
$page = 'default';

switch ($xx) {
  case 'skrzynka':
    $page = 'templates/user/wiadomosci/skrzynka.php';
    break;
  case 'wiadomosc':
    $page = 'templates/user/wiadomosci/wiadomosc.php';
    break;
  case 'czytaj':
    $page = 'templates/user/wiadomosci/czytaj.php';
    break;
}

if($page != 'default') {
  require_once($page);
}
else {
  header("Location: ?x=default");
}
?>
