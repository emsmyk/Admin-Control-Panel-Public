<?
$changelog = getClass('Changelog');
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
$id = (int)$_GET['id'];
$co = real_string($_GET['co']);
$edycja_id = (int) $_GET['edycja'];

if($_GET['changelog'] == 'changelog'){
  if(isset($_POST['changelog_add'])) {
    $changelog->changelog_add('dodaj_admina', $_POST['changelog_add'], $_POST['serwer'], $player->user);
  }
  if(isset($_POST['changelog_awans_deg_rez'])) {
    $changelog->changelog_add('awans_deg_rez', $_POST['changelog_awans_deg_rez'], $_POST['serwer'], $player->user);
  }
  if(isset($_POST['changelog_wlasny'])) {
    $changelog->changelog_add('wlasny', $_POST['changelog_wlasny'], $_POST['serwer'], $player->user);
  }
  header("Location: ?x=$x");
}

if(!empty( $co) && !empty($id) &&  $co == "usun"){
  $changelog->changelog_usun($id, $player->user, $dostep->ChangelogUsun);
	header("Location: ?x=$x");
}
if(isset($_POST['edycja_from'])) {
  $changelog->changelog_edytuj($_POST['edycja_from'], $player->user, $dostep->ChangelogEdytuj);
	header("Location: ?x=$x");
}

// show(json_encode((object) ['1' => 'NICK ADMINA', 'STEAM' => "STEAM_123", '2' => "Admin", '3' => "Opiekun-Nick" ]), false);

?>
<?
if(!empty($edycja_id)){
?>
<div class="row">
  <div class="col-xs-12">
    <div class="box box">
      <div class="box-header">
        <h3 class="box-title">Edycja</h3>
        <div class="pull-right box-tools">
        </div>
      </div>
      <div class="box-body">
        <form method='post'>
          <? $acp_r_d = row("SELECT * FROM `acp_log_serwery` WHERE `id` = $edycja_id LIMIT 1;");   ?>
          <input type='hidden' name='id' value='<? echo $acp_r_d->id ?>'>
          <p><div class='form-group input-group'><span class='input-group-addon'>Tekst</span><input class='form-control' type='text' name='tekst' value='<? echo $acp_r_d->tekst ?>'/></div></p>
          <p><div class='form-group input-group'><span class='input-group-addon'>Data</span><input class='form-control' type="datetime-local" name='data' value='<? echo date("Y-m-d\TH:i:s", strtotime($acp_r_d->data)) ?>'/></div></p>

					<p><input name='edycja_from' class='btn btn-primary btn-block' type='submit' value='Edytuj'/></p>
        </form>
      </div>
    </div>
  </div>
</div>
<?
}
?>
	<div class="row">
		<div class="col-lg-12">
			<div class="box box">
				<div class="box-header">
				  <h3 class="box-title">Changelog</h3>
				  <div class="pull-right box-tools">
				  </div>
				</div>
				<div class="box-body">
          <table data-page-length='50' id="example" class="table table-bordered table-striped" width="100%">
						<thead>
							<tr>
                <th>ID</th>
                <th>Data</th>
								<th>Serwer</th>
								<th>Tekst</th>
								<th>Wykonał</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
						<?
            $acp_wyswietl_all_q = all("SELECT *, `user` AS `user_id`, (SELECT `login` FROM `acp_users` WHERE `user` = `user_id` LIMIT 1) AS `user_name`, `serwer_id` AS `serwer_id_table`, (SELECT `mod` FROM `acp_serwery` WHERE `serwer_id` = `serwer_id_table`) AS `serwer_nazwa` FROM `acp_log_serwery`; ");
            foreach($acp_wyswietl_all_q as $acp_r){
              if($acp_r->serwer_id_table == 0) $acp_r->serwer_nazwa = "Wszystkie";
						?>
            <tr class="odd gradeX">
              <td><? echo $acp_r->id ?></td>
              <td><? echo $acp_r->data ?></td>
							<td><? echo $acp_r->serwer_nazwa ?></td>
							<td><? echo $acp_r->tekst ?></td>
							<td><? echo $acp_r->user_name ?></td>

              <td>
                <a href="<? echo "?x=$x&xx=$xx&edycja=$acp_r->id" ?>"><button type="button" class="btn btn-primary"><i class="fa fa-edit"></i></button></a>
                <a href="<? echo "?x=$x&xx=$xx&co=usun&id=$acp_r->id" ?>"><button type="button" class="btn btn-danger"><i class="fa fa-times"></i></button></a>
              </td>
						</tr>
						<? } ?>
						</tbody>
					</table>
				</div>
        <div class="box-footer clearfix no-border">
          <button type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#changelog"><i class="fa fa-plus"></i> Dodaj</button>
        </div>
			</div>
		</div>
  </div>


  <div class="row">
    <div class="modal fade" id="changelog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Changelog</h4>
          </div>
          <div class="modal-body">
            <p>Tutaj dodasz wszelkie informacje o działaniach na serwerze, wybierz odpowiednią kategorię tematyczą lub wałasny wpis aby dodać wyjątkowy unikatowy wpis.</p>
            <button type="button" class="btn bg-olive btn-block" data-toggle="modal" data-target="#changelog-add">Dodanie Admina</a></button>
            <button type="button" class="btn bg-purple btn-block" data-toggle="modal" data-target="#changelog-awans-deg-rez">Awans/Degradacja/Rezygnacja Admina</a></button>
            <button type="button" class="btn bg-default btn-block" data-toggle="modal" data-target="#changelog-inne">Własny wpis</a></button>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Zamknij</button>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="changelog-add">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Changelog - Dodanie Admina</h4>
          </div>
          <div class="modal-body">
            <form name='changelog' method='post' action='<? echo "?x=$x&changelog=changelog" ?>'>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Serwer</span>
                  <select class="form-control" name="serwer">
                    <option>Wszystkie</option>
                    <?
                    $acp_wybor_serwera_q = all("SELECT `serwer_id`, `mod`, `nazwa` FROM `acp_serwery`");
                    foreach($acp_wybor_serwera_q as $acp_wybor_serwera){
                    ?>
                      <option value="<? echo $acp_wybor_serwera->serwer_id ?>"><? echo $acp_wybor_serwera->nazwa ?> ( <? echo $acp_wybor_serwera->mod ?> )</option>
                    <? } ?>
                  </select>
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Nick</span>
                  <input class="form-control" name="nick">
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Steam_ID</span>
                  <input class="form-control" name="steam">
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Okres próbny?</span>
                  <select class="form-control" name="proba">
                    <option value="1">Tak</option>
                    <option value="0">Nie</option>
                  </select>
                </div>
              </p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Zamknij</button>
              <button type="input" name="changelog_add" class="btn btn-primary">Dodaj</button>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="changelog-awans-deg-rez">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Changelog - Awans/Degradacja/Rezygnacja</h4>
          </div>
          <div class="modal-body">
            <form name='changelog' method='post' action='<? echo "?x=$x&changelog=changelog" ?>'>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Serwer</span>
                  <select class="form-control" name="serwer">
                    <option>Wszystkie</option>
                    <?
                    $acp_wybor_serwera_q = all("SELECT `serwer_id`, `mod`, `nazwa` FROM `acp_serwery`");
                    foreach($acp_wybor_serwera_q as $acp_wybor_serwera){
                    ?>
                      <option value="<? echo $acp_wybor_serwera->serwer_id ?>"><? echo $acp_wybor_serwera->nazwa ?> ( <? echo $acp_wybor_serwera->mod ?> )</option>
                    <? } ?>
                  </select>
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Wybierz czynność</span>
                  <select class="form-control" name="czynnosc">
                    <option value="0">Brak</option>
                    <option value="1">Awans</option>
                    <option value="2">Degradacja</option>
                    <option value="3">Rezygnacja</option>
                  </select>
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Nick</span>
                  <input class="form-control" name="nick">
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Steam_ID</span>
                  <input class="form-control" name="steam">
                </div>
              </p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Zamknij</button>
              <button type="input" name="changelog_awans_deg_rez" class="btn btn-primary">Dodaj</button>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="changelog-inne">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Changelog</h4>
            <p>Tutaj dodasz wszelkie informacje o działaniach na serwerze</p>
          </div>
          <div class="modal-body">
            <form name='changelog' method='post' action='<? echo "?x=$x&changelog=changelog" ?>'>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Serwer</span>
                  <select class="form-control" name="serwer">
                    <option>Wszystkie</option>
                    <?
                    $acp_wybor_serwera_q = all("SELECT `serwer_id`, `mod`, `nazwa` FROM `acp_serwery`");
                    foreach($acp_wybor_serwera_q as $acp_wybor_serwera){
                    ?>
                      <option value="<? echo $acp_wybor_serwera->serwer_id ?>"><? echo $acp_wybor_serwera->nazwa ?> ( <? echo $acp_wybor_serwera->mod ?> )</option>
                    <? } ?>
                  </select>
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Tekst</span>
                  <input class="form-control" name="tekst">
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Data</span>
                  <input class="form-control" name="data" type="datetime-local">
                </div>
              </p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Zamknij</button>
              <button type="input" name="changelog_wlasny" class="btn btn-primary">Dodaj</button>
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
