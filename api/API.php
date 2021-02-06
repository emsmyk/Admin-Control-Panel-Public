<?
require_once('../functions/ACP/MYSQLfunctions.php');
require_once('../functions/ACP/API_func_save.php');

//  SB, HLSTATS, INNE
$x = (isset($_GET['x'])) ? $_GET['x'] : null;
// Co dokladnie z tego api
$xx = (isset($_GET['xx'])) ? $_GET['xx'] : null;
// Serwer
$srv = (isset($_GET['srv'])) ? $_GET['srv'] : null;
// steam, nick, coś co rozrozni gracza od gracza
$y = (isset($_GET['y'])) ? $_GET['y'] : null;

// cache plików json
$cache_live = (isset($_GET['lv'])) ? $_GET['lv'] : null;
$cache_filename = $x."_".$xx."_".$srv."_".$y;
$cache_filename = beautify_filename($cache_filename);
if(!is_null($cache_live)){
  api_file_old($cache_filename, $cache_live);
}

// jeśli nie ma cache plikow - wykonujemy polaczenie do bazy w celu pobrania danych
connection($_GET['h'], $_GET['u'], $_GET['p'], $_GET['db']);

$dane = new stdClass();
if(is_null($x) || is_null($xx) || is_null($srv)){
  $dane->blad = 'Brak podstawowych danych. Api nie może zostać wykonane..';
}
?>
