<?
class GaleriaMapMgr {
  public function lista_map($serwer){
    $no_image_map = one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'galeria_map_noimage' LIMIT 1");

    $galeria_map = row("SELECT `serwer_id` AS `srv_id`, `nazwa`, `mod`, (SELECT `id` FROM `acp_serwery_mapy` WHERE `serwer_id` = `srv_id` LIMIT 1) AS `id_grupy_map` FROM `acp_serwery` WHERE `serwer_on` = 1 AND `serwer_id` = $serwer");

    $lista_map_q = all("SELECT * FROM `acp_serwery_mapy_det` WHERE `mapy_id` = $galeria_map->id_grupy_map");
    foreach ($lista_map_q as $lista_map):
      $galeria_map->mapa_id[] = $lista_map->id;
      $galeria_map->mapa_nazwa[] = $lista_map->nazwa;
    endforeach;

    foreach ($galeria_map->mapa_id as $key => $value) {
      $img = one("SELECT `imgur_url` FROM `acp_serwery_mapy_img` WHERE `id_mapy` = $value LIMIT 1");
      $img = (empty($img)) ? $no_image_map : $img;
      $galeria_map->mapa_img[] = $img;
    }

    return $galeria_map;
  }

  public function obrazek($url, $rozmiar){
    if($url == 'https://acp.sloneczny-dust.pl/www/maps/nomap.jpg'){
      return $url;
    }

    $url = pathinfo($url);

    switch ($rozmiar) {
      case '1':
        $url = $url[dirname].'/'.$url[filename].'s.'.$url[extension];
        break;
      case '2':
        $url = $url[dirname].'/'.$url[filename].'b.'.$url[extension];
        break;
      case '3':
        $url = $url[dirname].'/'.$url[filename].'t.'.$url[extension];
        break;
      case '4':
        $url = $url[dirname].'/'.$url[filename].'m.'.$url[extension];
        break;
      case '5':
        $url = $url[dirname].'/'.$url[filename].'l.'.$url[extension];
        break;
      case '6':
        $url = $url[dirname].'/'.$url[filename].'h.'.$url[extension];
        break;
    }
    return $url;
  }

  public function mapy_mapa_detale_grafiki($post, $file, $admin, $dostep){
    if(uprawnienia($dostep, $admin) == 0){
      return;
    }

    $from = post_to_stdclass();
    $img = $_FILES['img'];

    // Ustawienia edycji grafiki obrazka
    $ust = array();
    $ustawienia = all("SELECT * FROM `acp_system` WHERE `conf_name` LIKE '%GaleriaMap%'");
    foreach ($ustawienia as $key => $value) {
      $ust[$value->conf_name] = $value->conf_value;
    }

    // jeszeli nie jest wyslany
    if($img['name']==''){
      $_SESSION['msg'] = komunikaty("Wybierz obrazek..", 3);
      return;
    }

    // Wgranie go do katalogu, nazwa pliku po edycji
    $target = "www/galeria_map/".$img['name'];
    move_uploaded_file($img['tmp_name'], $target);
    $save = "www/galeria_map/resize_" . $img['name']; //This is the new file you saving
    $water_mark = "www/galeria_map/watermark_" . $img['name']; //This is the new file you saving

    // Zmiana rozmarów obrazu mapy
    if($ust['GaleriaMap_wymiary_on'] == 1){
      list($width, $height) = getimagesize($target);

      $tn = imagecreatetruecolor($ust['GaleriaMap_wymiary_szerokosc'], $ust['GaleriaMap_wymiary_wysokosc']) ;
      $image = imagecreatefromjpeg($target) ;
      imagecopyresampled($tn, $image, 0, 0, 0, 0, $ust['GaleriaMap_wymiary_szerokosc'], $ust['GaleriaMap_wymiary_wysokosc'], $width, $height) ;

      imagejpeg($tn, $save, 100);
      imagedestroy($image);

      // adres pliku do wyslania
      $file_to_upload_imgur = $save;
    }
    // znak wodny
    if($ust['GaleriaMap_znak_on'] == 1){
      $obrazek = (file_exists($save)) ? $save : $target;

      $image = imagecreatefromjpeg($obrazek);
      switch ($ust['GaleriaMap_znak_tekst_kolor']) {
        case 'white':
          $textcolor = imagecolorallocate($image, 255, 255, 255);
          break;
        case 'black':
          $textcolor = imagecolorallocate($image, 0, 0, 0);
          break;
        case 'grey':
          $textcolor = imagecolorallocate($image, 128, 128, 128);
          break;
      }
      $font_file = 'www/galeria_map/Roboto-Bold.ttf';
      $custom_text = "Watermark Text";
      imagettftext($image, $ust['GaleriaMap_znak_tekst_wielkosc'], 0, 0+25, 0+25, $textcolor, $font_file, $ust['GaleriaMap_znak_tekst']);
      imagejpeg($image, $water_mark, 100);
      imagedestroy($image);

      // adres pliku do wyslania
      $file_to_upload_imgur = $water_mark;
    }


    if($img['name']==''){
      $_SESSION['msg'] = komunikaty("Wybierz obrazek..", 3);
      return;
    }

    // Wgrywanie obrazka na imgura
    $filename = $file_to_upload_imgur;
    $client_id=$ust['GaleriaMap_api'];
    $handle = fopen($filename, "r");
    $data = fread($handle, filesize($filename));
    $pvars   = array('image' => base64_encode($data));
    $timeout = 30;
    $curl    = curl_init();
    curl_setopt($curl, CURLOPT_URL, 'https://api.imgur.com/3/image.json');
    curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Client-ID ' . $client_id));
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $pvars);
    $out = curl_exec($curl);
    curl_close ($curl);
    $pms = json_decode($out,true);
    $url=$pms['data']['link'];
    if(empty($url)){
      $_SESSION['msg'] = komunikaty("Błąd, podczas wgrywania pliku", 3);
      return;
    }
    else {
      $czy_istnieje = one("SELECT `id` FROM `acp_serwery_mapy_img` WHERE `id_mapy` = $from->id");
      if(!empty($czy_istnieje)){
        query("UPDATE `acp_serwery_mapy_img` SET `imgur_url` = '$url'  WHERE `id_mapy` = $from->id;");
      }
      else {
        query("INSERT INTO `acp_serwery_mapy_img` (`id_mapy`, `imgur_url`) VALUES ('$from->id', '$url');");
      }
      $_SESSION['msg'] = komunikaty("Zaktualizowano galerię mapy $from->mapa (ID: $from->id)", 1);
      admin_log($admin, "Zaktualizowano galerię mapy $from->mapa (ID: $from->id)", "?x=galeria_map&id=$from->id");
    }

    // Skasowanie plików z acp
    unlink($water_mark);
    unlink($target);
    unlink($save);
  }
}
?>
