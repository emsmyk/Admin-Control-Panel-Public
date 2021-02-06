<?php
class WgrywarkaMgr{
  public function plugin($serwer, $user, $dostep){
    if(uprawnienia($dostep, $user) == 0){
      return;
    }
    $from = post_to_stdclass();

    //Dane pluginu
    $plugin = row("SELECT `nazwa` FROM `acp_pluginy` WHERE `id` = $from->id");
    //Dane pliki pluginu
    $pliki = all("SELECT `ftp_directory`, `ftp_source_file_name`, `ftp_dest_file_name` FROM `acp_pluginy_pliki` WHERE `plugin_id` = $from->id AND `ftp_source_file_name` != '' AND `ftp_directory` != '' AND `ftp_dest_file_name` != '' AND `starsza_wersja` IS NULL AND `kod_zrodlowy` IS NULL; ");
    $pliki = json_encode($pliki);

    $count = count($from->serwery);
    for ($i = 0; $i < $count; $i++):
      query("INSERT INTO `acp_wgrywarka` (`serwer_id`, `u_id`, `nazwa`, `kat`, `file`) VALUES ('$from->serwery[$i]', '$user', '$plugin->nazwa', 'Pluginy', '$pliki');");
      $mod_serwera = one("SELECT `mod` FROM `acp_serwery` WHERE `serwer_id` = $from->serwery[$i]");
      $id_serwerow = $id_serwerow." ".$from->serwery[$i];
      $mod_serwerow = $mod_serwerow." ".$mod_serwera;
    endfor;
    admin_log($user, "Plugin $plugin->nazwa został dodany do zadań Wgrywarki dla serwera(ów) $mod_serwerow (ID: $id_serwerow)");
    $_SESSION['msg'] = komunikaty("Plugin $plugin->nazwa został dodany do zadań Wgrywarki dla serwera(ów) $mod_serwerow (ID: $id_serwerow)", 1);
  }

  public function mapa_file($user, $dostep){
    if(uprawnienia($dostep, $user) == 0){
      return;
    }
    $from = post_to_stdclass();

    $file_name = $_FILES['plik']['name'];
    $file_size = $_FILES['plik']['size'];
    $file_tmp = $_FILES['plik']['tmp_name'];
    $file_type = $_FILES['plik']['type'];
    $file_ext = strtolower(end(explode('.',$file_name)));
    $max_rozmiar = 157286400;

    if(!is_uploaded_file($file_tmp)){
      $_SESSION['msg'] =komunikaty("Błąd przy przesyłaniu danych!", 3);
      return;
    }
    if ($file_size > $max_rozmiar) {
      $_SESSION['msg'] =komunikaty("Błąd! Plik jest za duży!", 3);
      return;
    }

    if($file_ext == 'bsp'){
      $path = "www/upload/maps";
      if(!file_exists("$path")) {
        mkdir("www/upload/maps", 0777, true);
      }
      // generowanie nazwy pliku w katalogu
      $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
      $nazwa_pliku = substr(str_shuffle($chars),0,25);

      $katalog_nazwa_pliku ="www/upload/maps/".$nazwa_pliku.".".$file_ext."";
      move_uploaded_file($file_tmp,$katalog_nazwa_pliku);

      $ftp_directory = one("SELECT `katalog` FROM `acp_serwery_cronjobs` WHERE `serwer` = $from->serwer_id LIMIT 1");
      $ftp_directory .= '/maps';

      $pliki = '[{"ftp_directory":"'.$ftp_directory.'","ftp_source_file_name":"'.$katalog_nazwa_pliku.'","ftp_dest_file_name":"'.$file_name.'"}]';
      query("INSERT INTO `acp_wgrywarka` (`serwer_id`, `u_id`, `nazwa`, `kat`, `file`) VALUES ('$from->serwer_id', '$user', '$file_name', 'Mapy', '$pliki');");

      $serwer_mod = one("SELECT `mod` FROM `acp_serwery` WHERE `serwer_id` = $from->serwer_id LIMIT 1");
      admin_log($user, "Mapa $file_name została dodana do zadań Wgrywarki dla serwera $serwer_mod (ID: $from->serwer_id)");
      $_SESSION['msg'] = komunikaty("Mapa $file_name została dodana do zadań Wgrywarki dla serwera $serwer_mod (ID: $from->serwer_id)", 1);
    }
    else {
      $_SESSION['msg'] = komunikaty("Plik w złym formacje, skompresuj go do gz..", 3);
    }

  }
}
?>
