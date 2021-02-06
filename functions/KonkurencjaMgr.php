<?
class KonkurencjaMgr {
  public function rss($kanal_rss, $nazwa_pliku, $czas_cache = '-60 minutes', $ilość_rss = 10){
    $file = "rss/".$nazwa_pliku.".htm";
    if(is_file($file) && (date('d-m-Y H:i:s', filemtime($file))) >=  date('d-m-Y H:i:s', strtotime($czas_cache))){
      return;
    }
    else {
      $doc = new DOMDocument();
      $doc->load($kanal_rss);
      $i=0;

      if(is_file($file)){
        $wyczysc = file_put_contents($file, '');
      }
      $fp = fopen($file, "w+");
      $array = array();
      foreach ($doc->getElementsByTagName('item') as $node) {
        if($i>=$ilość_rss){
          break;
        }
        $i++;
        array_push($array,
          array (
            'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
            'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
            'description' => $node->getElementsByTagName('description')->item(0)->nodeValue,
            'pubDate' => $node->getElementsByTagName('pubDate')->item(0)->nodeValue,
            'pubDate_srt' => strtotime($node->getElementsByTagName('pubDate')->item(0)->nodeValue),
            'code' => $nazwa_pliku,
            )
        );
      }
      $array = json_encode($array);
      fwrite($fp, $array);
      fclose($fp);
      return;
    }
  }

  public function edytuj($user, $dostep){
    if(uprawnienia($dostep, $user) == 0){
      return;
    }

    $from = post_to_stdclass();
    $from->dane_id = row("SELECT * FROM `acp_konkurencja` WHERE `id` = '$from->id' LIMIT 1");
    $from->nazwa_istnieje = one("SELECT COUNT(`id`) FROM `acp_konkurencja` WHERE `nazwa` = '$from->nazwa'");

    if(empty($from->nazwa)){
      $_SESSION['msg'] = komunikaty("Nazwa strony nie może być pusta", 4);
      return;
    }

    query("UPDATE `acp_konkurencja` SET `nazwa` = '$from->nazwa', `color` = '$from->kolor', `url` = '$from->url', `ilosc` = '$from->ilosc', `dane_time` = '$from->aktualizacja'  WHERE `id` = '$from->id' LIMIT 1");
    $_SESSION['msg'] = komunikaty("Zedytowano stronę $from->nazwa (ID: $from->id)", 1);
    admin_log($user, "Zedytowano stronę $from->nazwa (ID: $from->id)", "?x=konkurencja");
  }

  public function dodaj($user, $dostep){
    if(uprawnienia($dostep, $user) == 0){
      return;
    }

    $from = post_to_stdclass();
    $from->nazwa_istnieje = one("SELECT COUNT(`id`) FROM `acp_konkurencja` WHERE `nazwa` = '$from->nazwa'");
    $from->code = clean($from->nazwa);

    if(empty($from->nazwa)){
      $_SESSION['msg'] = komunikaty("Nazwa strony nie może być pusta", 4);
      return;
    }
    if($from->nazwa_istnieje != 0){
      $_SESSION['msg'] = komunikaty("Strona o tej nazwie istenieje", 4);
      return;
    }

    query("INSERT INTO `acp_konkurencja` (`id`, `nazwa`, `color`, `code`, `url`, `ilosc`, `dane_time`) VALUES (NULL, '$from->nazwa', '$from->kolor', '$from->code', '$from->url', '$from->ilosc', '$from->aktualizacja')");
    $_SESSION['msg'] = komunikaty("Dodano nową stronę $from->nazwa (ID: $from->id) code: $from->code", 1);
    admin_log($user, "Dodano nową stronę $from->nazwa (ID: $from->id) code: $from->code", "?x=konkurencja");
  }

  public function usun($id, $user, $dostep){
    if(uprawnienia($dostep, $user) == 0){
      return;
    }
    $id = (int)$id;
    $strona = row("SELECT `nazwa`, `code` FROM `acp_konkurencja` WHERE `id` = $id LIMIT 1");

    query("DELETE FROM `acp_konkurencja` WHERE `id` = $id");
    $_SESSION['msg'] = komunikaty("Strona  $strona->nazwa [code: $strona->code] (ID: $id) została usunięta", 1);
    admin_log($user, "Strona  $strona->nazwa [code: $strona->code] (ID: $id) została usunięta", "?x=konkurencja");
  }

  public function usun_cache($user, $dostep){
    if(uprawnienia($dostep, $user) == 0){
      return;
    }
    $files = glob('rss/*'); // get all file names
      foreach($files as $file){ // iterate files
        if(is_file($file))
          unlink($file); // delete file
      }
  }
}
?>
