<?
$func = getClass('SerwerKonfiguracja');
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
$id = (isset($_GET['id'])) ? (int)$_GET['id'] : null;
$co = (isset($_GET['co'])) ? $_GET['co'] : null;
$edycja_id = (isset($_GET['edycja'])) ? (int)$_GET['edycja'] : null;
$wymus_aktualizacje = (isset($_GET['wymus_aktualizacje'])) ? (int)$_GET['wymus_aktualizacje'] : null;

if(!empty( $co) && !empty($id) &&  $co == "usun"){
  $func->bazy_usun($id, $player->user, $dostep->SerwerReklamyUsun);
	header("Location: ?x=$x&xx=$xx");
}
if(isset($_POST['edycja_from'])) {
  $func->bazy_edytuj($_POST['edycja_from'], $player->user, $dostep->SerwerReklamyEdytuj);
	header("Location: ?x=$x&xx=$xx");
}
if(isset($_POST['nowy_rekord'])) {
  $func->bazy_dodaj($_POST['nowy_rekord'], $player->user, $dostep->SerwerReklamyDodaj);
  header("Location: ?x=$x&xx=$xx");
}
if($wymus_aktualizacje == 1){
  $func->wymus_aktualizacje($xx, $player->user, $dostep->SerwerWymusAktualizacje);
  header("Location: ?x=$x&xx=$xx");
}

$serwer_array = array(0 => 'Wszystkie');
$serwer_array_q = all("SELECT `serwer_id`, `mod`, `nazwa` FROM `acp_serwery`");
foreach($serwer_array_q as $serwer_array_dane){
  $serwer_array[$serwer_array_dane->serwer_id]="$serwer_array_dane->nazwa";
}
$wl_wyl_array = array(1 => 'Włączony', 0 => 'Wyłączony');
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
        <form name='edycja_from' method='post' action='<?= "?x=$x&xx=$xx&edycja=$edycja_id"; ?>'>
          <? $acp_r_d = row("SELECT * FROM `acp_serwery_baza` WHERE `id` = $edycja_id LIMIT 1;");   ?>
          <input type='hidden' name='id' value='<?= $acp_r_d->id ?>'>
          <p><div class='form-group input-group'><span class='input-group-addon'>Serwer</span>
            <select class="form-control" name="e_serwerid">
              <?
              echo '<option value="'.$acp_r_d->serwer_id.'">'.$serwer_array[$acp_r_d->serwer_id].'</option>';
              foreach ($serwer_array as $key => $value):
                if($acp_r_d->serwer_id != $key)
                echo '<option value="'.$key.'">'.$value.'</option>';
              endforeach;
              ?>
            </select>
          </div></p>
					<p><div class='form-group input-group'><span class='input-group-addon'>Nazwa</span><input class='form-control' type='text' name='e_nazwa' value='<?= $acp_r_d->nazwa ?>'/></div></p>
					<p><div class='form-group input-group'><span class='input-group-addon'>Driver</span><input class='form-control' type='text' name='e_driver' value='<?= $acp_r_d->d_driver ?>'/></div></p>
					<p><div class='form-group input-group'><span class='input-group-addon'>Host</span><input class='form-control' type='text' name='e_host' value='<?= $acp_r_d->d_host ?>'/></div></p>
					<p><div class='form-group input-group'><span class='input-group-addon'>Baza</span><input class='form-control' type='text' name='e_baza' value='<?= $acp_r_d->d_baze ?>'/></div></p>
					<p><div class='form-group input-group'><span class='input-group-addon'>User</span><input class='form-control' type='text' name='e_user' value='<?= $acp_r_d->d_user ?>'/></div></p>
					<p><div class='form-group input-group'><span class='input-group-addon'>Hasło</span><input class='form-control' type='text' name='e_haslo' value='<?= $acp_r_d->d_pass ?>'/></div></p>
					<p><div class='form-group input-group'><span class='input-group-addon'>Port</span><input class='form-control' type='text' name='e_port' value='<?= $acp_r_d->d_port ?>'/></div></p>
					<p><div class='form-group input-group'><span class='input-group-addon'>TimeOut</span><input class='form-control' type='text' name='e_timeout' value='<?= $acp_r_d->d_timeout ?>'/></div></p>
          <p><div class='form-group input-group'><span class='input-group-addon'>Port/TimeOut</span>
            <select class="form-control" name="e_time_out_on">
              <?
              echo '<option value="'.$acp_r_d->d_time_port_on.'">'.$wl_wyl_array[$acp_r_d->d_time_port_on].'</option>';
              foreach ($wl_wyl_array as $key => $value):
                if($acp_r_d->d_time_port_on != $key)
                echo '<option value="'.$key.'">'.$value.'</option>';
              endforeach;
              ?>
            </select>
          </div></p>
					<p><input name='edycja_from' class='btn btn-primary btn btn-block' type='submit' value='Edytuj'/></p>
        </form>
      </div>
    </div>
  </div>
</div>
<?
}
?>
	<div class="row">
		<div class="col-lg-8">
			<div class="box box">
				<div class="box-header">
				  <h3 class="box-title">Bazy Danych</h3>
				  <div class="pull-right box-tools">
				  </div>
				</div>
				<div class="box-body">
          <table data-page-length='10' id="example" class="table table-bordered table-striped" width="100%">
						<thead>
							<tr>
                <th>ID</th>
								<th>Serwer</th>
								<th>Nazwa</th>
								<th>Driver</th>
								<th>Host</th>
								<th>Baza</th>
								<th>User</th>
								<th>Port / TimeOut</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
						<?
            $acp_wyswietl_all_q = all("SELECT *, `serwer_id` AS `serwer_id_table`, (SELECT `mod` FROM `acp_serwery` WHERE `serwer_id` = `serwer_id_table`) AS serwer_nazwa FROM `acp_serwery_baza`");
  					foreach($acp_wyswietl_all_q as $acp_r){
              $acp_r->serwer_nazwa = ($acp_r->serwer_id_table==0) ? 'Wszystkie' : $acp_r->serwer_nazwa ;
              $acp_r->serwer_nazwa = (empty($acp_r->serwer_nazwa)) ? '<i>Serwer nie istnieje</i>' : $acp_r->serwer_nazwa;
						?>
            <tr class="odd gradeX">
              <td><?= $acp_r->id ?></td>
								<td><?= $acp_r->serwer_nazwa ?></td>
								<td><?= $acp_r->nazwa ?></td>
								<td><?= $acp_r->d_driver ?></td>
								<td><?= $acp_r->d_host ?></td>
								<td><?= $acp_r->d_baze ?></td>
								<td><?= $acp_r->d_user ?></td>
								<td><? if($acp_r->d_time_port_on == 1) {?><?= $acp_r->d_port; ?> / <?= $acp_r->d_timeout; ?> <? } else { echo "Brak"; }?></td>
                <td>
                  <div class="btn-group">
                    <a href="<?= "?x=$x&xx=$xx&edycja=$acp_r->id" ?>" class="btn btn-primary" role="button" aria-pressed="true"><i class="fa fa-edit"></i> Edytuj</a>
                    <a href="<?= "?x=$x&xx=$xx&co=usun&id=$acp_r->id" ?>" class="btn btn-danger" role="button" aria-pressed="true"><i class="fa fa-times"></i> Usuń</a>
                  </div>
                </td>
						</tr>
						<? } ?>
						</tbody>
					</table>
				</div>
        <div class="box-footer clearfix no-border">
          <button type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#dodaj-serwer"><i class="fa fa-plus"></i> Dodaj</button>
        </div>
			</div>
		</div>
    <?
    $ostatnia_aktualizacja = $func->ostatnia_aktualizacja($xx);
    $kolejna_aktualizacja = $func->kolejna_aktualizacja($xx);
    $serwery_aktualizowane = $func->serwery_aktualizowane();
    ?>
    <div class="col-lg-4 col-xs-12">
      <div class="small-box bg-maroon">
        <div class="inner">
          <h2>Ostatnia Aktualizcja</h2>
          <p><?= $ostatnia_aktualizacja; ?> </p>
        </div>
        <div class="icon">
          <i class="fa fa-clock-o"></i>
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-xs-12">
      <div class="small-box bg-olive">
        <div class="inner">
          <h2>Kolejna aktualizacja</h2>
          <p><?= $kolejna_aktualizacja; ?> </p>
        </div>
        <div class="icon">
          <i class="fa fa-bolt"></i>
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-xs-12">
      <div class="small-box bg-blue">
        <div class="inner">
          <?= $serwery_aktualizowane; ?>
        </div>
        <div class="icon">
          <i class="fa fa-server"></i>
        </div>
      </div>
    </div>
  </div>


  <div class="row">
    <!-- okno wyskakujace -->
    <div class="modal fade" id="dodaj-serwer">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Dodaj</h4>
          </div>
          <div class="modal-body">
            <form name='now_rekord' method='post' action='<?= "?x=$x&xx=$xx"; ?>'>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Serwer</span>
                  <select class="form-control" name="n_serwer">
                    <?
                    foreach ($serwer_array as $key => $value):
                      echo '<option value="'.$key.'">'.$value.'</option>';
                    endforeach;
                    ?>
                  </select>
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Nazwa</span>
                  <input class="form-control" name="n_nazwa">
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Driver</span>
                  <input class="form-control" name="n_driver">
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Host</span>
                  <input class="form-control" name="n_host">
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Baza</span>
                  <input class="form-control" name="n_baza">
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>User</span>
                  <input class="form-control" name="n_user">
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Hasło</span>
                  <input class="form-control" name="n_haslo">
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Port</span>
                  <input class="form-control" name="n_port">
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>TimeOut</span>
                  <input class="form-control" name="n_timeout">
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Port/TimeOut</span>
                  <select class="form-control" name="n_time_out_on">
                    <option value="1">ON</option>
                    <option value="0">OFF</option>
                  </select>
                </div>
              </p>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Zamknij</button>
              <button type="input" name="nowy_rekord" class="btn btn-primary">Zapisz</button>

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
