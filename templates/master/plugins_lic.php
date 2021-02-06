<?php
	if((isset($_POST['ip']) && $_POST['ip'] != "" && isset($_POST['pl']) && $_POST['pl'] != "") || (isset($_GET['ip']) && $_GET['ip'] != "" && isset($_GET['pl']) && $_GET['pl'] != ""))
	{
		//Jeśli połączenie nie odbywa się przez serwer CS, skrypt się nie wykona
		if(strpos($_SERVER['HTTP_USER_AGENT'], "Valve/Steam") === false)
		{
			die('[1] This script can not be used like that');
		}



		//Przyjmujemy argumenty z URL, albo w postaci _POST albo w _GET, zależnie co serwer przesłał
		$serverIP = isset($_POST['ip']) ? $_POST['ip'] : $_GET['ip'];
		$pluginID = isset($_POST['pl']) ? $_POST['pl'] : $_GET['pl'];



		//Sprawdzamy czy przesłane IP ma "kształt" IP poprzez REGEX**
		if(!preg_match('/^(\d+\.\d+\.\d+\.\d+):(\d+)$/', $serverIP))
		{
			die('[2] This script received bad "ip" data');
		}



		//Sprawdzamy ID pluginu czy jest samą liczbą poprzez REGEX**
		if(preg_match("/^\d+$/", $pluginID))
		{
			die('[3] This script received bad "pl" data');
		}

    $dane_serwera_ip = explode(":", $serverIP);
    $serwer = one("SELECT `serwer_id` FROM `acp_serwery` WHERE `ip` = '$dane_serwera_ip[0]' AND `port` = '$dane_serwera_ip[1]' LIMIT 1");

		//I pytamy bazę danych, czy w tabeli z licencjami, znajduje się IP naszego serwera
    if(empty($serwer)) {
      die('[5] Invalid query');
    }

    // szukam has dla pluginu
    $plugin_hash =  one("SELECT `lic_hash` FROM `acp_pluginy` WHERE `lic_name` = '$pluginID' LIMIT 1");
    if(empty($plugin_hash)) {
      die('[6] brak kodu (kodu hash)');
    }
    else {
      echo $plugin_hash;
    }

		die();
	}
  else {
    echo 'brak danych..';
  }

	die();
?>
