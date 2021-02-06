<style>
.example-modal .modal { position: relative; top: auto; bottom: auto; right: auto; left: auto; display: block; z-index: 1; }
.example-modal .modal { background: transparent !important; }
</style>
<div class="content-wrapper">
<section class="content">
  <div class="row">
	<section class="col-lg-12">
		<p><? if(!empty($_SESSION['msg'])){ echo $_SESSION['msg']; unset($_SESSION['msg']); } ?></p>
	</section >
  </div>
<?
$id = (isset($_GET['id'])) ? (int)$_GET['id'] : null;

$wgrywarka_wykonanych = one("SELECT COUNT(`id`) FROM `acp_wgrywarka` WHERE `status` = '1' ");

?>
  <div class="row">
    <? if(!empty($id)):
      $dane = row("SELECT *, `serwer_id` AS `id_serwera`,(SELECT `login` FROM `acp_users` WHERE `user` = `u_id` LIMIT 1) AS `login`, (SELECT `nazwa` FROM `acp_serwery` WHERE `serwer_id` = `id_serwera` LIMIT 1) AS `nazwa_serwera` FROM `acp_wgrywarka` WHERE `id` = $id; ");
      $dane->file = json_decode($dane->file);
      switch ($dane->status) {
        case 0:
          $dane->status = 'Nowe';
          $dane->data_upload = '-';
          break;
        case 1:
          $dane->status = 'Wgrane';
          break;
        case -1:
          $dane->status = 'Błąd';
          break;
      }
    ?>
    <div class="col-xs-12">
      <div class="box box">
        <div class="box-header">
          <h3 class="box-title">Detale  #<?= $dane->id ?> <br><small><?= $dane->kat.': '.$dane->nazwa ?></small></h3>
          <div class="pull-right box-tools">
          </div>
        </div>
        <div class="box-body">
          <div class="col-lg-6">
            <dl>
              <dt>Kategoria</dt>
              <dd><?= $dane->kat ?></dd>
              <dt>Nazwa</dt>
              <dd><?= $dane->nazwa ?></dd>
              <dt>Status</dt>
              <dd><?= $dane->status ?></dd>
            </dl>
          </div>
          <div class="col-lg-6">
            <dl>
              <dt>Zlecajacy</dt>
              <dd><a href="?x=account&id=<?= $dane->u_id ?>"><?= $dane->login ?></a></dd>
              <dt>Data Dodania</dt>
              <dd><?= $dane->data ?></dd>
              <dt>Data Wgrania</dt>
              <dd><?= $dane->data_upload ?></dd>
            </dl>
          </div>
          <div class="col-lg-12">
            <h4>Pliki (<?= count($dane->file); ?>):</h4>
              <table class="table table-hover">
                <tbody>
                <tr>
                  <th>Plik</th>
                  <th>Lokalizacja</th>
                  <th>Pobierz</th>
                </tr>
              <?
              foreach ($dane->file as $pliki):
              $pliki->ftp_source_file_name_color = file_exists($pliki->ftp_source_file_name);
              $pliki->ftp_source_file_name_color = ($pliki->ftp_source_file_name_color) ? 'success' : 'danger';
              $pliki->ftp_source_file_name = ($pliki->ftp_source_file_name_color == 'danger') ? '#' : $pliki->ftp_source_file_name;
              $download = (empty($dostep->WgrywarkaDownloadFile)) ? "<span class=\"label label-$pliki->ftp_source_file_name_color\">Download</span>" : "<a href=\"?x=download&xx=$pliki->ftp_source_file_name\"><span class=\"label label-$pliki->ftp_source_file_name_color\">Download</span></a>";
              ?>
                  <tr>
                    <td><?= $pliki->ftp_dest_file_name ?></td>
                    <td><?= $pliki->ftp_directory ?></td>
                    <td><?= $download ?></td>
                  </tr>
              <? endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <? endif; ?>
  </div>

  <div class="row">
    <div class="col-lg-8">
			<div class="box box">
				<div class="box-header">
				  <h3 class="box-title">Wgrywarka</h3>
				</div>
				<div class="box-body">
					<table width="100%" class="table table-striped table-bordered table-hover" id="example">
            <thead>
							<tr>
                <th>ID</th>
  							<th>Kategoria</th>
                <th>Serwer</th>
  							<th>Nazwa</th>
  							<th>Status</th>
  							<th>Data dodania</th>
  							<th>Data Wgrania</th>
  							<th></th>
							</tr>
						</thead>
						<tbody>
            <?
            $wgrywarka_q = all("SELECT *, `serwer_id` AS `id_serwera`,(SELECT `login` FROM `acp_users` WHERE `user` = `u_id` LIMIT 1) AS `login`, (SELECT `mod` FROM `acp_serwery` WHERE `serwer_id` = `id_serwera` LIMIT 1) AS `mod_serwera` FROM `acp_wgrywarka` ORDER BY `id` DESC");
            foreach ($wgrywarka_q as $wgrywarka) {
              $wgrywarka->mod_serwera = (empty($wgrywarka->mod_serwera)) ? '<i>Serwer nie istnieje</i>' : $wgrywarka->mod_serwera;

              switch ($wgrywarka->status) {
                case 0:
                  $wgrywarka->status = 'Nowe';
                  $wgrywarka->data_upload = '-';
                  break;
                case 1:
                  $wgrywarka->status = 'Wgrane';
                  break;
                case -1:
                  $wgrywarka->status = 'Błąd';
                  break;
              }
              $wgrywarka->file = json_decode($wgrywarka->file);
            ?>
              <tr class="odd gradeX">
                <td><?= $wgrywarka->id ?></td>
                <td><?= $wgrywarka->kat ?></td>
                <td><a href="?x=serwery_det&serwer_id=<?= $wgrywarka->serwer_id ?>"><?= $wgrywarka->mod_serwera ?></a></td>
                <td><?= $wgrywarka->nazwa ?></td>
                <td><?= $wgrywarka->status ?></td>
                <td><?= $wgrywarka->data ?></td>
                <td><?= $wgrywarka->data_upload ?></td>
                <td>
                  <a href="<? echo "?x=$x&id=$wgrywarka->id" ?>"><button type="button" class="btn btn-primary"><i class="fa fa-ellipsis-h"></i></button></a>
                </td>
              </tr>
            <? } ?>
            </tbody>
          </table>
				</div>
      </div>
		</div>
    <div class="col-lg-4 col-xs-12">
      <div class="small-box bg-aqua">
        <div class="inner">
          <h3><?= $wgrywarka_wykonanych ?></h3>

          <p>Wykonanych Zleceń</p>
        </div>
        <div class="icon">
          <i class="fa fa-upload"></i>
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-xs-12">
      <div class="small-box bg-green">
        <div class="inner">
          <h3>~</h3>

          <p>Wgranych Plików</p>
        </div>
        <div class="icon">
          <i class="fa fa-thumbs-o-up"></i>
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-xs-12">
      <div class="box box">
        <div class="box-header">
				  <h3 class="box-title">Błędy Serwerów</h3>
				</div>
        <div class="box-body">
          <?
          $bledy_all = all("SELECT `serwer_id` AS `id_srv`, `tekst`, `tekst_admin`, `data`, (SELECT `mod` FROM `acp_serwery` WHERE `serwer_id` = `id_srv`) AS `mod` FROM `acp_serwery_bledy` WHERE `status` = '1' ORDER BY `data` DESC LIMIT 10");
          if(!empty($bledy_all)):
            foreach ($bledy_all as $row):?>
              <p><b>[<?= $row->mod ?>]</b> <?= $row->tekst ?> - <?= czas_relatywny($row->data)?></p>
            <? endforeach; ?>
          <? else: ?>
            <p><i>Nie wystąpiły błędy podczas wykonywania prac.</i></p>
          <? endif;?>
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
<!-- DataTables -->
<script src="./www/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="./www/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="./www/bower_components/datatables.net-bs/js/dataTables.responsive.js"></script>
<!-- SlimScroll -->
<script src="./www/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="./www/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="./www/dist/js/adminlte.min.js"></script>
<!-- page script -->
<?= js_table_defaults(); ?>
<?= js_table_one('#example','desc', 0, 20);  ?>
</body>
</html>
