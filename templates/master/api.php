<?
switch ($xx) {
  case 'serwery_lista':
    $dane = all("SELECT `istotnosc`, `ip`, `port`, `mod`, `nazwa`, `graczy`, `max_graczy`, `boty`, `mapa` FROM `acp_serwery` WHERE `serwer_on` = 1; ");
    echo json_encode($dane);
    break;

  default:
    // code...
    break;
}

?>
