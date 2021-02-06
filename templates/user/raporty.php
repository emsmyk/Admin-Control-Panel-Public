<?
$page = 'default';

switch ($xx) {
  case 'raport_miesieczny':
    $page = 'templates/user/raporty/raport_miesieczny.php';
    break;
  case 'raport_serwer':
    $page = 'templates/user/raporty/raport_serwer.php';
    break;
}

if($page != 'default') {
  require_once($page);
}
else {
  header("Location: ?x=default");
}
?>
