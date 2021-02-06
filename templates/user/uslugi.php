<?
$page = 'default';
switch ($xx) {
  case 'moje_uslugi':
    $page = 'templates/user/uslugi/moje_uslugi.php';
    break;;
  case 'ustawienia':
    if(uprawnienia($dostep->UslugiUstawienia, $player->user) != 1){
      header("Location: ?x=default");
      break;
    }
    $page = 'templates/user/uslugi/ustawienia.php';
    break;
  case 'dodaj_usluge':
    if(uprawnienia($dostep->UslugiDodaj, $player->user) != 1){
      header("Location: ?x=default");
      break;
    }
    $page = 'templates/user/uslugi/dodaj_usluge.php';
    break;
  case 'uslugi':
    // if($dostep->UslugiDodaj != 1){
    //   header("Location: ?x=default");
    //   break;
    // }
    $page = 'templates/user/uslugi/uslugi.php';
    break;
}

if($page != 'default') {
  require_once($page);
}
else {
  header("Location: ?x=default");
}
?>
