<?
$page = 'default';

switch ($xx) {
  case 'lista':
    $page = 'templates/user/roundsound_func/lista.php';
    break;
  case 'lista_edit':
    $page = 'templates/user/roundsound_func/lista_edit.php';
    break;
  case 'piosenki':
    $page = 'templates/user/roundsound_func/piosenki.php';
    break;
  case 'piosenki_edit':
    $page = 'templates/user/roundsound_func/piosenki_edit.php';
    break;
  case 'vote':
    $page = 'templates/user/roundsound_func/vote.php';
    break;
  case 'ustawienia':
    $page = 'templates/user/roundsound_func/ustawienia.php';
    break;
}

if($page != 'default') {
  require_once($page);
}
else {
  header("Location: ?x=default");
}
?>
