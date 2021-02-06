<div class="col-md-12">
  <div class="box">
    <div class="box-header with-border">
      <i class="fa fa-gear fa-file-text"></i>
      <h3 class="box-title">Logi Serwera</h3>
      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
      </div>
    </div>
    <div class="box-body serwery_logi">
      <?
       $logi = $_GET['logi'];
       $srv_dane->ftp_haslo = encrypt_decrypt('decrypt', $srv_dane->ftp_haslo);
       $ftp_path = 'ftp://'.$srv_dane->ftp_user.':'.$srv_dane->ftp_haslo.'@'.$srv_dane->ftp_host.'/CS_GO/csgo/addons/sourcemod/logs/'.$logi.'';
       $curl = curl_init();
       curl_setopt($curl, CURLOPT_URL, $ftp_path);
       curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($curl, CURLOPT_HEADER, false);
       $data = curl_exec($curl);

       $data = ($data === FALSE) ? 'Plik nie istnieje (i/lub) pojawił się błąd skryptu..': str_replace(array("\r\n", "\n", "\r"), "<br>", strip_tags($data));
      ?>

      <?= $data ?>
    </div>
  </div>
</div>
