<?php
class PluginyMgr{
  public function usun_plugin($id, $user, $dostep){
    if(uprawnienia($dostep, $user) == 0){
      return;
    }
    $id = (int)$id;

    $dane = row("SELECT `nazwa` FROM `acp_pluginy` WHERE `id` = $id LIMIT 1");

    $usun_pliki_q = all("SELECT * FROM `acp_pluginy_pliki` WHERE `plugin_id` = $id");
    foreach ($usun_pliki_q as $usun_pliki):
      unlink($usun_pliki->ftp_source_file_name);
      query("DELETE FROM `acp_pluginy_pliki` WHERE `id` = $usun_pliki->id");
    endforeach;
    rmdir("www/server_plugins/$id");

    query("DELETE FROM `acp_pluginy` WHERE `id` = $id");
    admin_log($user, "Usunięto plugin $dane->nazwa (ID: $id)", "?x=pluginy");
    $_SESSION['msg'] = komunikaty("Usunięto plugin $dane->nazwa (ID: $id)", 1);
  }
  public function nowy_plugin($post, $user, $dostep){
    if(uprawnienia($dostep, $user) == 0){
      return;
    }
    $from = post_to_stdclass();

    if(empty($from->nazwa)) {
      $_SESSION['msg'] = komunikaty("Nazwa Pluginu nie może być pusta..", 2);
      return;
    }

    query("INSERT INTO `acp_pluginy` (`nazwa`, `opis`, `cvary`, `notatki`, `u_id`) VALUES ('$from->nazwa', '$from->opis', '$from->cvary', '$from->notatki', $user)");
    $last_insert = one("SELECT LAST_INSERT_ID()");
    admin_log($user, "Dodano nowy plugin $from->nazwa (ID: $last_insert)", "?x=pluginy");
    $_SESSION['msg'] = komunikaty("Dodano nowy plugin $from->nazwa (ID: $last_insert)", 1);
  }
  public function edytuj_plugin($post, $user, $dostep){
    if(uprawnienia($dostep, $user) == 0){
      return;
    }
    $from = post_to_stdclass();

    if(empty($from->nazwa)){
			$_SESSION['msg'] = komunikaty("Nazwa Pluginu nie może być pusta..", 2);
			return;
		}

    query("UPDATE `acp_pluginy` SET `nazwa` = '$from->nazwa', `opis` = '$from->opis', `cvary` = '$from->cvary', `notatki` = '$from->notatki', `lic_name` = '$from->lic_name', `lic_hash` = '$from->lic_hash' WHERE `id` = $from->id");
    admin_log($user, "Zedytowano plugin $from->nazwa (ID: $from->id)", "?x=pluginy&id=$from->id");
    $_SESSION['msg'] = komunikaty("Zedytowano plugin $from->nazwa (ID: $from->id)", 1);
  }

  public function plugin_wgraj_plik($file, $user, $dostep){
    if(uprawnienia($dostep, $user) == 0){
      return;
    }
    $from = post_to_stdclass();

    $file_name = $_FILES['plik']['name'];
    $file_size = $_FILES['plik']['size'];
    $file_tmp = $_FILES['plik']['tmp_name'];
    $file_type = $_FILES['plik']['type'];
    $file_ext = strtolower(end(explode('.',$file_name)));

    $from->nazwa_pluginu = row("SELECT `id`, `nazwa` FROM `acp_pluginy` WHERE `id` = $from->id LIMIT 1");
    $file_name = (empty($from->nazwa)) ? $file_name : $from->nazwa;

    if(empty($from->gdzie)){
      $_SESSION['msg'] =komunikaty("Należy uzupełnić pole Gdzie Wgrać..", 3);
      return;
    }

    $max_rozmiar = 1362150;
    $extensions= array("smx, sp, cfg, txt");

    if(!is_uploaded_file($file_tmp)){
      $_SESSION['msg'] =komunikaty("Błąd przy przesyłaniu danych!", 3);
      return;
    }
    if ($file_size > $max_rozmiar) {
      $_SESSION['msg'] =komunikaty("Błąd! Plik jest za duży!", 3);
      return;
    }
    // tworzenie sprawdzeni czy jest katalog dla Pluginu
    $path = "www/server_plugins/$from->id";
    if(!file_exists("$path")) {
      mkdir("www/server_plugins/$from->id", 0777, true);
    }
    // generowanie nazwy pliku w katalogu
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $nazwa_pliku = substr(str_shuffle($chars),0,25);
    // dodowanie pliku do folderu z losowym ciągiem znaków
    $katalog_nazwa_pliku ="www/server_plugins/".$from->id."/".$nazwa_pliku.".".$file_ext."";
    move_uploaded_file($file_tmp,$katalog_nazwa_pliku);
    query("INSERT INTO `acp_pluginy_pliki` (`plugin_id`, `ftp_directory`, `ftp_source_file_name`, `ftp_dest_file_name`) VALUES ('$from->id', '$from->gdzie', '$katalog_nazwa_pliku', '$file_name');");

    admin_log($user, "Wgrano nowy plik $file_name dla pluginu $nazwa_pluginu->nazwa (ID: $from->id)", "?x=pluginy&id=$from->id");
    $_SESSION['msg'] = komunikaty("Wgrano nowy plik $file_name dla pluginu $nazwa_pluginu->nazwa (ID: $from->id)", 1);
  }
  public function plugin_usun_plik($id, $user, $dostep){
    if(uprawnienia($dostep, $user) == 0){
      return;
    }
    $id = (int)$id;

    $dane = row("SELECT `plugin_id`, `ftp_dest_file_name`, `ftp_source_file_name` FROM `acp_pluginy_pliki` WHERE `id` = $id LIMIT 1");
    $nazwa_pluginu = row("SELECT `id`, `nazwa` FROM `acp_pluginy` WHERE `id` = $dane->plugin_id LIMIT 1");
    unlink($dane->ftp_source_file_name);
    query("DELETE FROM `acp_pluginy_pliki` WHERE `id` = $id");

    admin_log($user, "Usunięto plik $dane->ftp_dest_file_name (ID: $id) z pluginu $nazwa_pluginu->nazwa (ID: $nazwa_pluginu->id)", "?x=pluginy&id=$nazwa_pluginu->id");
    $_SESSION['msg'] = komunikaty("Usunięto plik $dane->ftp_dest_file_name (ID: $id) z pluginu $nazwa_pluginu->nazwa (ID: $nazwa_pluginu->id)", 1);
  }
  public function plugin_edytuj_plik($post, $user, $dostep){
    if(uprawnienia($dostep, $user) == 0){
      return;
    }
    $from = post_to_stdclass();
    $from->kod_zrodlowy = ($from->kod_zrodlowy == 'on') ? "'1'" : 'NULL';
    $from->starsza_wersja = ($from->starsza_wersja == 'on') ? "'1'" : 'NULL';

    $dane = row("SELECT `plugin_id` FROM `acp_pluginy_pliki` WHERE `id` = $from->id LIMIT 1");
    $from->nazwa_pluginu = row("SELECT `id`, `nazwa` FROM `acp_pluginy` WHERE `id` = $dane->plugin_id LIMIT 1");
    if(empty($from->nazwa)) {
			$_SESSION['msg'] = komunikaty("Nazwa Pluginu nie może być pusta..", 2);
			return;
		}

    query("UPDATE `acp_pluginy_pliki` SET `ftp_directory` = '$from->gdzie_wgrac', `ftp_dest_file_name` = '$from->nazwa', `starsza_wersja` = $from->starsza_wersja, `kod_zrodlowy` = $from->kod_zrodlowy WHERE `id` = $from->id");
    admin_log($user, "Zedytowano plik $from->nazwa (ID: $from->id) z pluginu $nazwa_pluginu->nazwa (ID: $nazwa_pluginu->id)", "?x=pluginy&id=$nazwa_pluginu->id");
    $_SESSION['msg'] = komunikaty("Zedytowano plik $from->nazwa (ID: $from->id) z pluginu $nazwa_pluginu->nazwa (ID: $nazwa_pluginu->id)", 1);
  }
}
?>
