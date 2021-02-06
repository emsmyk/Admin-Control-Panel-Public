<?php
//polaczenie z baza danych
$config['host'] = 'localhost';
$config['user'] = 'USER';
$config['pass'] = 'HASLO';
$config['dbname'] = 'NAZWA_BAZYDANYCH';

//adres strony http://adres-strony.pl
$config['site'] = 'https://acp.sloneczny-dust.pl';

//polaczenie z baza danych
$connect = @mysql_connect($config['host'], $config['user'], $config['pass']) or die('blad polaczenia z baza');
mysql_select_db($config['dbname'],$connect) or die('socket error - brak bazy danych');
mysql_query("SET NAMES 'utf8'");


//ustawienia systemu acp
$acp_system_1 = array();
$acp_system_2 = array();

$acp_ustawienia_q = all("SELECT * FROM `acp_system`");
foreach($acp_ustawienia_q as $acp_ustawienia){
	array_push($acp_system_1, "$acp_ustawienia->conf_name");
	array_push($acp_system_2, "$acp_ustawienia->conf_value");
}

$acp_system = array_combine($acp_system_1,$acp_system_2);

//strefa czasowa
date_default_timezone_set($acp_system['acp_timezone']);
?>
