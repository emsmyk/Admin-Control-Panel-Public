<?
$page = 'default';

switch ($xx) {
  case 'lista':
    $page = 'templates/user/zadania_func/zadania_lista.php';
    break;
  case 'moje':
    $page = 'templates/user/zadania_func/zadania_moje.php';
    break;
  case 'zadanie':
    $page = 'templates/user/zadania_func/zadania_zadanie.php';
    break;
  case 'statystyki':
    $page = 'templates/user/zadania_func/zadania_statystyki.php';
    break;
  case 'dodaj':
    $page = 'templates/user/zadania_func/zadania_dodaj.php';
    break;
}

if($page != 'default') {
  require_once($page);
}
else {
  header("Location: ?x=default");
}
?>
