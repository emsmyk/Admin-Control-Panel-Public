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
    <?= komunikaty("UWAGA! Ten moduł nie aktualizuje w tym momencie żadnego pliku na serwerze. Będzie aktywny po wprowadzeniu poprawek jak i danych.<br>Celem jest aby aktulizował plik csgo-servers.cfg w katalogu cfg, oraz zastąpił cvary znajdujące się w sourcemod.cfg", 4);?>
		<p><? if(!empty($_SESSION['msg'])){ echo $_SESSION['msg']; unset($_SESSION['msg']); } ?></p>
	</section >
  </div>
<?
$id = (int)$_GET['id'];
$co = real_string($_GET['co']);
$edycja_id = (int) $_GET['edycja'];

if(!empty( $co) && !empty($id) &&  $co == "usun"){
  $func->cvary_usun($id, $player->user, $dostep->SerwerCvaryUsun);
	header("Location: ?x=$x&xx=$xx");
}
if(isset($_POST['edycja_from'])) {
  $func->cvary_edytuj($_POST['edycja_from'], $player->user, $dostep->SerwerCvaryEdytuj);
	header("Location: ?x=$x&xx=$xx");
}
if(isset($_POST['nowy_rekord'])) {
  $func->cvary_dodaj($_POST['nowy_rekord'], $player->user, $dostep->SerwerCvaryDodaj);
  header("Location: ?x=$x&xx=$xx");
}
if($_GET['wymus_aktualizacje'] == 1){
  $func->wymus_aktualizacje($xx, $player->user, $dostep->SerwerWymusAktualizacje);
  header("Location: ?x=$x&xx=$xx");
}
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
          <? $acp_r_d = row("SELECT * FROM `acp_serwery_cvary` WHERE `id` = $edycja_id LIMIT 1;"); ?>
          <input type='hidden' name='id' value='<?= $acp_r_d->id ?>'>
          <p><div class='form-group input-group'><span class='input-group-addon'>Serwer ID</span><input class='form-control' type='text' name='serwerid' value='<?= $acp_r_d->serwer_id ?>'/></div></p>
					<p><div class='form-group input-group'><span class='input-group-addon'>Cvar</span><input class='form-control' type='text' name='cvar' value='<?= $acp_r_d->cvar ?>'/></div></p>
					<p><div class='form-group input-group'><span class='input-group-addon'>Komentarz</span><input class='form-control' type='text' name='komentarz' value='<?= $acp_r_d->komentarz ?>'/></div></p>
          <p><input name='edycja_from' class='btn btn-primary btn-lg btn-block' type='submit' value='Edytuj'/></p>
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
				  <h3 class="box-title">Cvary</h3>
				  <div class="pull-right box-tools">
				  </div>
				</div>
				<div class="box-body">
          <table data-page-length='10' id="example" class="table table-bordered table-striped" width="100%">
						<thead>
							<tr>
                <th>ID</th>
								<th>Serwer</th>
								<th>Cvar</th>
								<th>Komentarz</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
						<?
            $acp_wyswietl_all_q = all("SELECT *, `serwer_id` AS `serwer_id_table`, (SELECT `mod` FROM `acp_serwery` WHERE `serwer_id` = `serwer_id_table`) AS serwer_nazwa FROM `acp_serwery_cvary`");
  					foreach($acp_wyswietl_all_q as $acp_r){
              $acp_r->serwer_nazwa = ($acp_r->serwer_id_table==0) ? 'Wszystkie' : $acp_r->serwer_nazwa ;
              $acp_r->serwer_nazwa = (empty($acp_r->serwer_nazwa)) ? '<i>Serwer nie istnieje</i>' : $acp_r->serwer_nazwa;
						?>
            <tr class="odd gradeX">
              <td><?= $acp_r->id ?></td>
							<td><?= $acp_r->serwer_nazwa ?></td>
							<td><?= $acp_r->cvar ?></td>
							<td><?= $acp_r->komentarz ?></td>
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

      <div class="box box">
        <div class="box-header">
          <h3 class="box-title">Lista dostępnych zmiennych</h3>
          <div class="pull-right box-tools">
          </div>
        </div>
        <div class="box-body">
          <p>Aktualna listę dostępnych komend, zmiennych znajdziesz na forum <a href='https://forums.alliedmods.net/'>alliedmods.net</a> lub pod tym <a href='https://forums.alliedmods.net/showthread.php?t=186668'>linkiem</a></p>
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
                    <option>Wszystkie</option>
                    <?
                    $acp_wybor_serwera_q = all("SELECT `serwer_id`, `mod`, `nazwa` FROM `acp_serwery`");
                    foreach($acp_wybor_serwera_q as $acp_wybor_serwera){
                    ?>
                      <option value="<?= $acp_wybor_serwera->serwer_id ?>"><?= $acp_wybor_serwera->nazwa ?> ( <?= $acp_wybor_serwera->mod ?> )</option>
                    <? } ?>
                  </select>
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Miejsce wyświetlenia</span>
                  <select class="form-control" name="n_gdzie">
                    <option>S</option>
					          <option>C</option>
                  </select>
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Tekst Reklamy</span>
                  <input class="form-control" name="n_tekst">
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
