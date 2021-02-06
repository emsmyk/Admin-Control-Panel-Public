<?
$srv = getClass('Serwer');
$api = getClass('Api');
$changelog = getClass('Changelog');
$wgrywarka = getClass('Wgrywarka');
$sourcebans = getClass('Sourcebans');

$serwer_id = $_GET['serwer_id'];
$srv->sprawdznie_dostepu($serwer_id, $player->user);

$srv_dane = row("SELECT *, (SELECT `login` FROM `acp_users` WHERE `user` = `ser_a_copiekun`) AS `nick_copiekun` FROM `acp_serwery` WHERE `serwer_id` = $serwer_id; ");
$obrazek_mapy = $srv->obrazek_mapy($srv_dane->mapa);
$srv_dane->procent_zapelnienia = round($srv_dane->graczy*100/$srv_dane->max_graczy);
$srv_dane->procent_pustych_slotow = 100-$srv_dane->procent_zapelnienia;

$require_once = 'templates/user/serwery_det/';
?>
<style>
.example-modal .modal { position: relative; top: auto; bottom: auto; right: auto; left: auto; display: block; z-index: 1; }
.example-modal .modal { background: transparent !important; }
</style>
<div class="content-wrapper">
<section class="content">
  <div class="row">
  	<section class="col-lg-12">
  		<p><? if(!empty($_SESSION['msg'])){ echo $_SESSION['msg']; unset($_SESSION['msg']); } ?></p>
      <?= $srv->kontola_systemu($serwer_id); ?>
  	</section >
  </div>
<? require_once($require_once.'Form.php'); ?>

  <div class="row">
    <?
    if(uprawnienia($dostep->serwery_det_logi, $player->user) && !empty($_GET['logi'])):
      require_once($require_once.'Logi.php');
    endif;
    ?>
    <div class="col-md-8">
      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs pull-right ui-sortable-handle">
          <li><a href="#dane_changelog" data-toggle="tab">Changelog</a></li>
          <li class="active"><a href="#dane_podstawowe" data-toggle="tab">Dane</a></li>
          <li class="pull-left header"><i class="fa fa-list-alt"></i> <?=($srv_dane->nazwa) ?: 'brak danych'; ?></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="dane_podstawowe">
            <div class="box-body table-responsive no-padding">
              <div class="col-lg-5">
                <p><img src="<?= $obrazek_mapy ?>" class="img-thumbnail"></p>
              </div>
              <div class="col-lg-7">
                <p>Nazwa: <?= $srv_dane->nazwa ?></p>
                <p>Mod: <?= $srv_dane->mod ?></p>
                <p>Mapa: <?= $srv_dane->mapa ?></p>
                <p>Graczy: <?= $srv_dane->graczy ?> / <?= $srv_dane->max_graczy ?> [<?= $srv_dane->boty ?>]</p>
                <hr>
                <p>IP: <a href="steam://connect/<?= $srv_dane->ip ?>:<?= $srv_dane->port ?>/"><?= $srv_dane->ip ?>:<?= $srv_dane->port ?></a></p>
              </div>
              <div class="col-lg-12">
                <div class="progress text-center">
                  <div class="progress-bar progress-bar-green" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="<?=$srv_dane->procent_zapelnienia ?>" style="width: <?=$srv_dane->procent_zapelnienia ?>%;">
                  <span><?=$srv_dane->procent_zapelnienia ?>%</span>
                  </div>
                  <span><?=$srv_dane->procent_pustych_slotow ?>%</span>
                </div>
              </div>
              <div class="col-lg-12">
                <button type="button" class="btn btn-app bg-maroon" data-toggle="modal" data-target="#regulamin"><i class="fa fa-file-text-o"></i>Regulamin</a></button>
                <button type="button" class="btn btn-app bg-default" data-toggle="modal" data-target="#changelog"><i class="fa fa-map-o"></i>Changelog</a></button>
                <a href="?x=sourceupdate" class="btn btn-app bg-default"><i class="fa fa-sitemap"></i>SourceUpdate</a>
                <a href="?x=console&serwer=<?= $serwer_id ?>" class="btn btn-app bg-default"><i class="fa fa-terminal"></i>Konsola</a>
                <a href="?x=serwer_live_say&serwer=<?= $serwer_id ?>" class="btn btn-app bg-default"><i class="fa fa-text-height"></i>Chat Say</a>
                <button type="button" class="btn btn-app bg-default" data-toggle="modal" data-target="#wgraj_mape"><i class="fa fa-map"></i>Mapa</a></button>
                <button type="button" class="btn btn-app bg-default" data-toggle="modal" data-target="#logi"><i class="fa fa-file-text"></i>Logi</a></button>
              </div>
            </div>
          </div>
          <div class="tab-pane" id="dane_changelog">
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tr>
                  <th>Data</th>
                  <th>Tekst</th>
                  <th>Dodał</th>
                </tr>
                <?
                $changelog_last_Q = all("SELECT *, `user` AS `user_id`, (SELECT `login` FROM `acp_users` WHERE `user` = `user_id`) AS `nick` FROM `acp_log_serwery` WHERE `serwer_id` = $serwer_id ORDER BY `data` DESC LIMIT 5");
                if(empty($changelog_last_Q)):?>
                  <tr> <td colspan="3">Brak danych do zaprezentowania..</id></tr>
                <? else:
                  foreach ($changelog_last_Q as $changelog_last):
                  ?>
                  <tr>
                    <td><?= $changelog_last->data ?></td>
                    <td><?= $changelog_last->tekst ?></td>
                    <td><?= $changelog_last->nick ?></td>
                  </tr>
                <? endforeach;
                endif;?>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="box">
        <div class="box-header with-border">
          <i class="fa fa-gear fa-fw"></i>
          <h3 class="box-title">Ustawienia Podstawowe</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          <form name='ust_podstawowe_edit' method='post' action='<?= "?x=$x&serwer_id=$serwer_id" ?>'>
            <p><div class='form-group input-group'><span class='input-group-addon'>Mod</span><input class='form-control' type='text' name='mod' value='<?= $srv_dane->mod ?>'/></div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>Czas Reklam</span><input class='form-control' type='number' name='czas_reklam' value='<?= $srv_dane->czas_reklam ?>'/></div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>FastDL</span><input class='form-control' type='text' name='fastdl' value='<?= $srv_dane->fastdl ?>'/></div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>GOTV</span><input class='form-control' type='text' name='link_gotv' value='<?= $srv_dane->link_gotv ?>'/></div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>Chef Admin</span><input class='form-control' type='text' name='ser_a_copiekun' value='<?= $srv_dane->nick_copiekun ?>'/></div></p>
            <p><input name='ust_podstawowe_edit' class='btn btn-xs btn-primary btn-block' type='submit' value='Edytuj'/></p>
          </form>
        </div>
      </div>
    </div>
  </div>

  <? require_once($require_once.'row/RowListaAdminow.php'); ?>
  <? require_once($require_once.'row/RowPraceCykliczne.php'); ?>
  <? require_once($require_once.'row/RowWykresy.php'); ?>

  <div class="row">
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-red">
        <div class="inner">
          <h3>
            <?
              $ilosc_zadan = one("SELECT COUNT(`id`) FROM `acp_zadania` WHERE `serwer_id` = $serwer_id");
              $ilosc_zadan_zrealizowanych = one("SELECT COUNT(`id`) FROM `acp_zadania` WHERE `status` = 3 AND `serwer_id` = $serwer_id");
              echo $ilosc_zadan;
              echo " / ";
              echo $ilosc_zadan_zrealizowanych;
            ?>
          </h3>
          <p>Zgłoszonych/Zrealizowanych Zadań</p>
        </div>
        <div class="icon">
          <i class="ion ion-pie-graph"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <? require_once($require_once.'modal/ModalChangelog.php'); ?>
    <? require_once($require_once.'modal/ModalRegulamin.php'); ?>
    <? require_once($require_once.'modal/ModalWykresy.php'); ?>
    <? require_once($require_once.'modal/ModalListaAdminow.php'); ?>
    <? require_once($require_once.'modal/RaportOpiekuna.php'); ?>
    <? require_once($require_once.'modal/ModalLogi.php'); ?>
    <div class="modal fade" id="wgraj_mape">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Wgraj Mapę</h4>
          </div>
          <div class="modal-body">
            <form action="<?= "?x=$x&serwer_id=$serwer_id"; ?>" method="POST" ENCTYPE="multipart/form-data" name="wgraj_mape">
              <input type='hidden' name='serwer_id' value='<?= $serwer_id ?>'>

              <div class="form-group">
                <label for="exampleInputFile">Plik</label>
                <input type="file" id="exampleInputFile" name="plik">
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Zamknij</button>
              <button type="input" name="wgraj_mape" class="btn btn-primary">Wyślij plik</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

</section>
</div>

<? require_once("./templates/user/stopka.php");  ?>
<div class="control-sidebar-bg"></div>
</div>

<!-- jQuery 3 -->
<script src="./www/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="./www/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- ChartJS -->
<script src="./www/bower_components/chart.js/Chart.js"></script>
<!-- DataTables -->
<script src="./www/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="./www/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="./www/bower_components/datatables.net-bs/js/dataTables.responsive.js"></script>
<!-- Morris.js charts -->
<script src="./www/bower_components/raphael/raphael.min.js"></script>
<script src="./www/bower_components/morris.js/morris.min.js"></script>
<!-- SlimScroll -->
<script src="./www/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="./www/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="./www/dist/js/adminlte.min.js"></script>
<!-- page script -->
<?= js_table_defaults(); ?>
<?= js_table_one('#tab_lista_adminow', 'asc', 0, $tab_lista_adminow_ilosc); ?>
<?= js_table_one('#tab_lista_adminow_raport', 'asc', 0, 100); ?>

<? require_once($require_once.'js/JsWykresy.php'); ?>

<script>
  $(document).ready(function () {
    $('.serwery_konfiguracja').slimScroll({});
  });
  $(document).ready(function () {
    $('.serwery_logi').slimScroll({});
  });
  $(document).ready(function () {
    $('.prace_zdalne').slimScroll({height: 'auto' });
  });
</script>
</body>
</html>
