<?
$prace_cykliczne = ($srv_dane->cronjobs == 0) ? 'maroon' : 'green';
if($srv_dane->cronjobs == 1):
?>
<div class="row">
  <div class="col-sm-8">
    <div class="box bg-<?= $prace_cykliczne ?>">
      <div class="box-body prace_zdalne">
        <h3>Prace Cykliczne (Prace Zdalne):</h3>
        <?
        $cron_serwer = row("SELECT * FROM `acp_serwery_cronjobs` WHERE `serwer` = $serwer_id");
        $cron_serwer->reklamy = (1 == $cron_serwer->reklamy) ? 'Włączone (<i>'.$srv->dane_cronjobs($acp_system['cron_reklamy'], $acp_system['time_reklamy']).'</i>)' : 'Wyłączone';
        $cron_serwer->mapy = (1 == $cron_serwer->mapy) ? 'Włączone (<i>'.$srv->dane_cronjobs($acp_system['cron_mapy'], $acp_system['time_mapy']).'</i>)' : 'Wyłączone';
        $cron_serwer->bazy = (1 == $cron_serwer->bazy) ? 'Włączone (<i>'.$srv->dane_cronjobs($acp_system['cron_baza'], $acp_system['time_baza']).'</i>)' : 'Wyłączone';
        $cron_serwer->cvary = (1 == $cron_serwer->cvary) ? 'Włączone (<i>'.$srv->dane_cronjobs($acp_system['cron_cvary'], $acp_system['time_cvary']).'</i>)' : 'Wyłączone';
        $cron_serwer->hextags = (1 == $cron_serwer->hextags) ? 'Włączone (<i>'.$srv->dane_cronjobs($acp_system['cron_hextags'], $acp_system['time_hextags']).'</i>)' : 'Wyłączone';
        $cron_serwer->uslugi = (1 == $cron_serwer->uslugi) ? 'Włączone (<i>'.$srv->dane_cronjobs($acp_system['cron_uslugi'], $acp_system['time_uslugi']).'</i>)' : 'Wyłączone';
        $cron_serwer->help_menu = (1 == $cron_serwer->help_menu) ? 'Włączone (<i>'.$srv->dane_cronjobs($acp_system['cron_help_menu'], $acp_system['time_help_menu']).'</i>)' : 'Wyłączone';

        echo "
          <p>Usługi: $cron_serwer->uslugi</p>
          <p>Bazy Danych: $cron_serwer->bazy</p>
          <p>Reklamy: $cron_serwer->reklamy</p>
          <p>Mapy: $cron_serwer->mapy</p>
          <p>HexTags: $cron_serwer->hextags</p>
          <p>Help Menu: $cron_serwer->help_menu</p>
        ";
        ?>
      </div>
      <div class="box-footer text-center">
        <a href="?x=serwery_ust&cron=<?= $serwer_id ?>" class="uppercase">Edytuj Ustawienia</a>
      </div>
    </div>
  </div>
  <div class="col-sm-4">
    <div class="box bg-teal">
      <div class="box-body prace_zdalne">
        <h3>Błędy:</h3>
        <?
        $bledy_all = all("SELECT `serwer_id`, `tekst`, `tekst_admin`, `data` FROM `acp_serwery_bledy` WHERE `serwer_id` = $serwer_id AND `status` = '1' ORDER BY `data` DESC LIMIT 5");
        if(!empty($bledy_all)):
          foreach ($bledy_all as $row):?>
            <p><?= $row->tekst ?> - <?= czas_relatywny($row->data)?></p>
          <? endforeach; ?>
        <? else: ?>
          <p><i>Nie wystąpiły błędy podczas wykonywania prac.</i></p>
        <? endif;?>
      </div>
      <div class="box-footer text-center">
        <a href="<?= "?x=$x&serwer_id=$serwer_id&prace_zdalne=skasuj_bledy" ?>" class="uppercase">Oznacz jako odczytane</a>
      </div>
    </div>
  </div>
</div>
<? elseif($srv_dane->cronjobs == -1): ?>
<div class="row">
  <div class="col-sm-12">
    <div class="box bg-teal">
      <div class="box-body prace_zdalne">
        <h3>Błędy:</h3>
        <?
        $bledy_all = all("SELECT `serwer_id`, `tekst`, `tekst_admin`, `data` FROM `acp_serwery_bledy` WHERE `serwer_id` = $serwer_id AND `status` = '1' ORDER BY `data` DESC LIMIT 5");
        if(!empty($bledy_all)):
          foreach ($bledy_all as $row):?>
            <p><?= $row->tekst_admin ?> - <?= czas_relatywny($row->data)?></p>
          <? endforeach; ?>
        <? else: ?>
          <p><i>Nie wystąpiły błędy podczas wykonywania prac.</i></p>
        <? endif;?>
      </div>
      <div class="box-footer text-center">
        <a href="<?= "?x=$x&edycja=$serwer_id" ?>" class="uppercase">Prace Zdalne zostały Zablokowane! Sprawdź ustawienia i uruchom je ponownie..</a>
      </div>
    </div>
  </div>
</div>
<? else: ?>
<div class="row">
  <div class="col-lg-12">
    <div class="box bg-<?= $prace_cykliczne ?>">
      <div class="box-body prace_zdalne">
        <h3>Prace Cykliczne (Prace Zdalne):</h3>
        <p>Wszystkie prace zostały wyłączone na tym serwerze, aby je włączyć <a href="?x=serwery_ust&edycja=<?= $serwer_id ?>">przejdź</a> do ustawień serwera.. </p>
      </div>
    </div>

  </div>
</div>
<? endif; ?>
