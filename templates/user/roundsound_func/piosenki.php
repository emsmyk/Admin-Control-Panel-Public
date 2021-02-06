<?
tytul_strony("RoundSound: Piosenki");
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
if(isset($_POST['nowe'])) {
  $func->nowa_piosenka($player->user, $dostep->RsPiosenkaDodaj);
  header("Location: ?x=$x&xx=$xx");
}
?>

	<div class="row">
		<div class="col-xs-12">
			<div class="box box">
				<div class="box-header">
				  <h3 class="box-title">Piosenki - Lista</h3>
				  <div class="pull-right box-tools">
				  </div>
				</div>
				<div class="box-body">
					<table width="100%" class="table table-striped table-bordered table-hover" id="example">
            <thead>
							<tr>
                <th>ID</th>
  							<th>Nazwa</th>
  							<th>Wykonawca</th>
  							<th>Album</th>
  							<th>RS propozycja</th>
  							<th>Start-Stop</th>
  							<th>Głosów</th>
  							<th>Akceptujacy(Data)</th>
  							<th>Data Dodania</th>
  							<th></th>
							</tr>
						</thead>
						<tbody>
            <?
            $lista = all("SELECT *, (SELECT `nazwa` FROM `rs_roundsound` WHERE `id` = `roundsound_propozycja` ) AS `roundsound_nazwa`, (SELECT `login` FROM `acp_users` WHERE `user` = `akcept` LIMIT 1) AS `login_akceptujacego` FROM `rs_utwory` ORDER BY `id` DESC");
            foreach ($lista as $row) {
              $row->roundsound_nazwa = (empty($row->roundsound_nazwa)) ? "Utwór dodany przez Administratora" : "<a href='?x=roundsound&xx=lista_edit&id=$row->roundsound_propozycja'>$row->roundsound_nazwa</a>";
              $row->akcept = ($row->akcept != 0) ? "<a href='?x=account&id=$row->akcept'>$row->login_akceptujacego</a> ($row->data_akcept)" : "Utwór jeszcze nie został zaakceptowany";
            ?>
              <tr class="odd gradeX">
                <td><?= $row->id ?></td>
                <td><?= $row->nazwa ?></td>
                <td><?= $row->wykonawca ?></td>
                <td><?= $row->album ?></td>
                <td><?= $row->roundsound_nazwa ?></td>
                <td><?= $row->start ?> - <?= $row->end ?></td>
                <td><?= $row->vote ?></td>
                <td><?= $row->akcept ?></td>
                <td><?= $row->data_dodania ?></td>
                <td>
                  <div class="btn-group">
                    <a href="<?= "?x=$x&xx=piosenki_edit&id=$row->id" ?>" class="btn btn-primary" role="button" aria-pressed="true"><i class="fa fa-edit"></i> Detale</a>
                  </div>
                </td>
              </tr>
            <? } ?>
            </tbody>
          </table>
				</div>
        <div class="box-footer clearfix no-border">
          <button type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#dodaj"><i class="fa fa-plus"></i> Dodaj</button>
        </div>
      </div>
		</div>
	</div>


  <div class="row">
    <div class="modal fade" id="dodaj">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Dodaj nowy utówr </h4>
          </div>
          <div class="modal-body">
            <form name='nowe' method='post' action='<?= "?x=$x&xx=$xx"; ?>'>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Nazwa</span>
                  <input class="form-control" name="nazwa" type="text">
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Wykonawca</span>
                  <input class="form-control" name="wykonawca" type="text">
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Album</span>
                  <input class="form-control" name="album" type="text">
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Start</span>
                  <input class="form-control" name="start" type="text">
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Koniec</span>
                  <input class="form-control" name="end" type="text">
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Link YT</span>
                  <input class="form-control" name="link" type="text">
                </div>
              </p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Zamknij</button>
              <button type="input" name="nowe" class="btn btn-primary">Dodaj</button>
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
<?= js_table_one('#example'); ?>
</body>
</html>
