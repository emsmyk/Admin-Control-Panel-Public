<?
$page = 'default';

switch ($xx) {
  case 'wpisy':
    $page = 'templates/user/wpisy_func/wpisy.php';
    break;
  case 'wpis':
    $page = 'templates/user/wpisy_func/wpis.php';
    break;
  case 'category':
    $page = 'templates/user/wpisy_func/category.php';
    break;
  default:
    $page = 'templates/user/wpisy_func/wpisy.php';
    break;
}

if($page != 'default') {
  require_once($page);
}
else {
  header("Location: ?x=default");
}
?>
