<?
tytul_strony("Usługi: Moje Usługi");
$func = getClass('Uslugi');
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
	</section >
  </div>

	<div class="row">
		<div class="col-xs-12">
			<div class="box box">
				<div class="box-header">
				  <h3 class="box-title">Lista Moich Usług</h3>
				  <div class="pull-right box-tools">
				  </div>
				</div>
				<div class="box-body">
					<table width="100%" class="table table-striped table-bordered table-hover" id="example">
            <thead>
							<tr>
                <th>ID</th>
  							<th>Serwer</th>
  							<th>Steam</th>
  							<th>Koniec</th>
  							<th>Rodzaj</th>
  							<th>Data Dodania</th>
							</tr>
						</thead>
						<tbody>
            <?
            $player->steam_id = toSteamID($player->steam);
            $lista_q = all("SELECT *,
              (SELECT `mod` FROM `acp_serwery` WHERE `serwer_id` = `serwer` LIMIT 1) AS `serwer_mod`,
              (SELECT `nazwa` FROM `acp_uslugi_rodzaje` WHERE `id` = `rodzaj` LIMIT 1) AS `rodzaj_nazwa`
            FROM `acp_uslugi` WHERE `steam` = '$player->steam' OR `steam_id` = '$player->steam_id'; ");
            foreach ($lista_q as $lista) {
            ?>
              <tr class="odd gradeX">
                <td><?= $lista->id ?></td>
                <td><?= $lista->serwer_mod ?></td>
                <td><?= $lista->steam ?><br><small><?= $lista->steam_id ?></small></td>
                <td><?= $lista->koniec ?></td>
                <td><?= $lista->rodzaj_nazwa ?></td>
                <td><?= $lista->data ?></td>
              </tr>
            <? } ?>
            </tbody>
          </table>
				</div>
      </div>
		</div>
		<div class="col-xs-12">
			<div class="box box">
				<div class="box-header">
				  <h3 class="box-title">Lista Dodanych Usług</h3>
				  <div class="pull-right box-tools">
				  </div>
				</div>
				<div class="box-body">
					<table width="100%" class="table table-striped table-bordered table-hover" id="example2">
            <thead>
							<tr>
                <th>ID</th>
  							<th>Serwer</th>
  							<th>Steam</th>
  							<th>Koniec</th>
  							<th>Rodzaj</th>
  							<th>Data Dodania</th>
							</tr>
						</thead>
						<tbody>
            <?
            $player->steam_id = toSteamID($player->steam);
            $lista_q = all("SELECT *,
              (SELECT `mod` FROM `acp_serwery` WHERE `serwer_id` = `serwer` LIMIT 1) AS `serwer_mod`,
              (SELECT `nazwa` FROM `acp_uslugi_rodzaje` WHERE `id` = `rodzaj` LIMIT 1) AS `rodzaj_nazwa`
            FROM `acp_uslugi` WHERE `user` = '$player->user'; ");
            foreach ($lista_q as $lista) {
            ?>
              <tr class="odd gradeX">
                <td><?= $lista->id ?></td>
                <td><?= $lista->serwer_mod ?></td>
                <td><?= $lista->steam ?><br><small><?= $lista->steam_id ?></small></td>
                <td><?= $lista->koniec ?></td>
                <td><?= $lista->rodzaj_nazwa ?></td>
                <td><?= $lista->data ?></td>
              </tr>
            <? } ?>
            </tbody>
          </table>
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
<?= js_table_one('#example', 'asc'); ?>
<?= js_table_one('#example2', 'asc'); ?>
</body>
</html>
