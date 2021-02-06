<?php
class LogiMgr
{
  public function zmien_ss_logi_zdalne($zmienna){
    if($zmienna == 1) {
      $_SESSION['ss_acp_logi'] = 0;
    }
    else {
      $_SESSION['ss_acp_logi'] = 1;
    }
  }
  public function oddaj_zmienna_ss_logi_zdalne($dane){
    $dane = (int)$dane;
    if(empty($dane) || $dane = '') {
      return 0;
    }
    else {
      return 1;
    }
  }

}
?>
