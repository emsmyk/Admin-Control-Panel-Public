<?
tytul_strony("RoundSound: Głosy");
$func = getClass('Roundsound');
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
<?
?>

	<div class="row">
		<div class="col-xs-12">
			<div class="box box">
				<div class="box-header">
				  <h3 class="box-title">Głosy</h3>
				  <div class="pull-right box-tools">
				  </div>
				</div>
				<div class="box-body">
					<table width="100%" class="table table-striped table-bordered table-hover" id="example">
            <thead>
							<tr>
                <th>ID</th>
  							<th>RoundSound</th>
  							<th>Utówr</th>
  							<th>IP (Przegladarka)</th>
  							<th>Data</th>
  							<th></th>
							</tr>
						</thead>
						<tbody>
            <?
            $lista = all("SELECT *, (SELECT `nazwa` FROM `rs_roundsound` WHERE `id` = `roundsound` LIMIT 1) AS `roundsound_nazwa`, (SELECT `nazwa` FROM `rs_utwory` WHERE `id` = `utwor` LIMIT 1) AS `utwor_nazwa`  FROM `rs_vote` ORDER BY `data` DESC");
            foreach ($lista as $row) {
            ?>
              <tr class="odd gradeX">
                <td><?= $row->id ?></td>
                <td><a href="?x=roundsound&xx=lista_edit&id=<?= $row->roundsound ?>"><?= $row->roundsound_nazwa ?></a></td>
                <td><a href="?x=roundsound&xx=piosenki_edit&id=<?= $row->utwor ?>"><?= $row->utwor_nazwa ?></a></td>
                <td><?= $row->ip ?> (<?= $row->przegladarka ?>)</td>
                <td><?= $row->data ?></td>
                <td>
                </td>
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
<?= js_table_one('#example', `desc`); ?>
</body>
</html>
