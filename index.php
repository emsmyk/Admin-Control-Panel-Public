<?php
ob_start();
if(!isset($_SESSION)) { session_start(); }

// function
require_once('functions/ACP/MYSQLfunctions.php');
require_once('functions/ACP/ACPfunctions.php');
require_once('functions/ACP/TEXTfunctions.php');
require_once('functions/ACP/STEAMfunctions.php');

require_once('var/config.php');

if(!isset($_SESSION)) { session_start(); }
if(!empty($_SESSION['user'])  && is_numeric($_SESSION['user'])){ $player = getPlayer($_SESSION['user']);}
if(empty($player)){	$player->role = -1; }
if($player->banned == 0 && !empty($_SESSION['user'])) { $_SESSION = array(); session_destroy(); header('Location: ?x=login'); }

// //dostepy do funckji administratorskich w zaleznosci tablicy z bazy
// $dostep = one("SELECT `dostep` FROM `acp_users_grupy` WHERE `id` = $player->grupa");
// $dostep = json_decode($dostep)[0];

//moduly dostepu
$podstawa = array("blad", "login", "register", "forget_password", "logout", "ajax",
"default", "cronjobs", "cronjobs_stats", "cronjobs_optym", "cronjobs_serwer", "api", "plugins_lic",
"pub_sourcebans", "pub_admin_list", "pub_galeria_map", "pub_changelog", "pub_serwery", "pub_hlstats_top", "pub_roundsound", "pub_zadanie", "pub_iframe"
);
$podstawa_clear = array("download");
$gosc = array_merge($podstawa, $podstawa_clear);

if($player->role != -1 ) {
	$podstawa_user = array("wiadomosci", "powiadomienia");
	if((int)$_SESSION['acp_grupa_sesja'] != (int)$player->grupa && $_SESSION['acp_grupa_sesja'] != ''){
		$grupa_dane = row("SELECT `id`, `nazwa`, `moduly`, `dostep` FROM `acp_users_grupy` WHERE `id` = ".$_SESSION['acp_grupa_sesja']." LIMIT 1");
		$moduly_grupa = json_decode($grupa_dane->moduly);
		$dostep = json_decode($grupa_dane->dostep)[0];

		$_SESSION['acp_grupa_sesja_nazwa'] = one("SELECT `nazwa` FROM `acp_users_grupy` WHERE `id` = ".$_SESSION['acp_grupa_sesja']." LIMIT 1");
		$_SESSION['msg'] = komunikaty_rozbudowany("fa fa-warning", "TRYB POGLĄDOWY", "Jesteś aktualnie w trakcie podglądu jao grupa <b>".$grupa_dane->nazwa."</b> (ID: ".$grupa_dane->id."), aby wyjść wyloguj się albo zmień w ustawieniach..", 4);
	}
	else {
		$grupa_dane = row("SELECT `nazwa`, `moduly`, `dostep` FROM `acp_users_grupy` WHERE `id` = ".$player->grupa." LIMIT 1");
		$moduly_grupa = json_decode($grupa_dane->moduly);
		$dostep = json_decode($grupa_dane->dostep)[0];
	}
	$moduly = array_merge($moduly_grupa, $podstawa_user);
}
// wartość get x
$x = (isset($_GET['x'])) ? $_GET['x'] : null;
$xx = (isset($_GET['xx'])) ? $_GET['xx'] : null;

// wyswietl bledy php 0 = OFF
daj_bledy((int)$acp_system['dev_on'], $acp_system['dev_modul'], $x);

//pusty pasek adresu
if(empty($x)){
	if($player->role >= 0) { $x = 'wpisy'; } else { $x = 'default'; }
}

$header = 'templates/master/header.php';
$footer = 'templates/master/footer.php';

if(in_Array($x,$moduly) && ($player->role == 1) ){
	$header = 'templates/user/header.php';
	$menu = 'templates/user/menu.php';
	$footer = 'templates/user/footer.php';
	$page = (file_exists("templates/admin/$x.php")) ? "templates/admin/$x.php" : "templates/user/$x.php";
}
elseif(in_Array($x,$moduly) && ($player->role == 0) ){
	$header = 'templates/user/header.php';
	$menu = 'templates/user/menu.php';
	$footer = 'templates/user/footer.php';
	$page =  'templates/user/'.$x.'.php';
}
elseif(in_Array($x,$gosc)){
	if(in_Array($x,$podstawa_clear)){
		$page =  'templates/master/'.$x.'.php';
		require_once($page);
	}
	else {
		$header = 'templates/master/przybornik/header.php';
		$menu = 'templates/master/przybornik/menu.php';
		$footer = 'templates/master/przybornik/footer.php';
	}
	$page =  'templates/master/'.$x.'.php';
}
else {
	$header = 'templates/master/przybornik/header.php';
	$menu = 'templates/master/przybornik/menu.php';
	$footer = 'templates/master/przybornik/footer.php';
	$page =  'templates/master/blad.php';
}
require_once($header);
require_once($menu);
require_once($page);
require_once($footer);

mysql_close();
ob_end_flush();
?>
