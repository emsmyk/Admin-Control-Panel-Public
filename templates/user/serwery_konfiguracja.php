<?
$page = 'default';

switch ($xx) {
  case 'rangi':
    $page = 'templates/user/serwery_func/serwery_rangi.php';
    break;
  case 'reklamy':
    $page = 'templates/user/serwery_func/serwery_reklamy.php';
    break;
  case 'baza':
    $page = 'templates/user/serwery_func/serwery_baza.php';
    break;
  case 'mapy':
    $page = 'templates/user/serwery_func/serwery_mapy.php';
    break;
  case 'hextags':
    $page = 'templates/user/serwery_func/serwery_hextags.php';
    break;
  case 'help_menu':
    $page = 'templates/user/serwery_func/serwery_help_menu.php';
    break;
  case 'tagi':
    $page = 'templates/user/serwery_func/serwery_tagi.php';
    break;
}

if($page != 'default') {
  require_once($page);
}
else {
  header("Location: ?x=default");
}
?>
