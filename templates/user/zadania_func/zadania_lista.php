<?
tytul_strony("Zadania: Lista Zadań");
$func = getClass('Zadania');
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
  $func->nowe_zadanie($_POST['nowe'], $player->user, $dostep->ZadaniaDodaj);
  header("Location: ?x=$x&xx=$xx");
}
if(isset($_POST['filtruj'])){
  $filtry = new stdClass();
  $filtry->data_start = $_POST['data_start'];
  $filtry->data_end = $_POST['data_end'];
  $filtry->status_min = $_POST['status_min'];
  $filtry->status_max = $_POST['status_max'];
  $filtry->platforma = $_POST['platforma'];
  $filtry->serwer = $_POST['serwer'];

  $filtry_txt = 'WHERE `procent_wykonania` > 0';
  if(!empty($filtry->data_start)) { $filtry_txt .= " AND `data` >= '$filtry->data_start'"; }
  if(!empty($filtry->data_end)) { $filtry_txt .= " AND `data` <= '$filtry->data_end'"; }
  if(!empty($filtry->status_min) && $filtry->status_min != 99) { $filtry_txt .= ' AND `status` < '.$filtry->status_min; }
  if(!empty($filtry->status_max) && $filtry->status_max != 99) { $filtry_txt .= ' AND `status` > '.$filtry->status_max; }
  if($filtry->platforma != 99) { $filtry_txt .= ' AND `platforma` = '. $filtry->platforma; }
  if($filtry->serwer != 99) { $filtry_txt .= ' AND `serwer_id` = '. $filtry->serwer; }
}
$acp_zadania_list = all("SELECT *, `serwer_id` AS `id_serwera`, (SELECT `nazwa` FROM `acp_zadania_platforma` WHERE `id` = `platforma` LIMIT 1) AS platforma, (SELECT `web` FROM `acp_zadania_platforma` WHERE `id` = `platforma` LIMIT 1) AS platforma_web, (SELECT `nazwa` FROM `acp_zadania_typ` WHERE `id` = `typ` LIMIT 1) AS typ, (SELECT `nazwa` FROM `acp_serwery` WHERE `serwer_id` = `id_serwera` LIMIT 1) AS nazwa_serwera, (SELECT `nazwa` FROM `acp_zadania_status` WHERE `id` = `status` LIMIT 1) AS status_nazwa, (SELECT `typ` FROM `acp_zadania_status` WHERE `id` = `status` LIMIT 1) AS kolor FROM `acp_zadania`  $filtry_txt ORDER BY `id` DESC");

$zadania_status_q = all("SELECT `id`, `nazwa` FROM `acp_zadania_status` ORDER BY `id` DESC");
$zadania_status_array = array(99 => 'Wybierz');
foreach ($zadania_status_q as $value) {
  $zadania_status_array[$value->id]="$value->nazwa";
}
$zadania_platforma_q = all("SELECT `id`, `nazwa` FROM `acp_zadania_platforma` ORDER BY `id` DESC");
$zadania_platforma_array = array(99 => 'Wybierz');
foreach ($zadania_platforma_q as $value) {
  $zadania_platforma_array[$value->id]="$value->nazwa";
}

$serwer_array = array(99 => 'Wybierz', 0 => 'Wszystkie');
$serwer_array_q = all("SELECT `serwer_id`, `mod`, `nazwa` FROM `acp_serwery`");
foreach($serwer_array_q as $serwer_array_dane){
  $serwer_array[$serwer_array_dane->serwer_id]="$serwer_array_dane->nazwa";
}
?>

	<div class="row">
    <div class="col-xs-12">
			<div class="box box">
				<div class="box-header">
				  <h3 class="box-title">Filtruj</h3>
				</div>
				<div class="box-body">
          <form method='post'>
            <div class="col-lg-4">
              <h4>Data:</h4>
              <div class="form-group input-group"><span class="input-group-addon">Początek</span><input class="form-control" type="date" name="data_start"></div>
              <div class="form-group input-group"><span class="input-group-addon">Koniec</span><input class="form-control" type="date" name="data_end"></div>
            </div>
            <div class="col-lg-4">
              <h4>Status:</h4>
              <p><div class='form-group input-group'><span class='input-group-addon'>Wiekszy niż</span>
                <select class="form-control" name="status_min">
                  <? foreach ($zadania_status_array as $key => $value):
                    echo '<option value="'.$key.'">'.$value.'</option>';
                  endforeach; ?>
                </select>
              </div></p>
              <p><div class='form-group input-group'><span class='input-group-addon'>Mniejszy niż</span>
                <select class="form-control" name="status_max">
                  <? foreach ($zadania_status_array as $key => $value):
                    echo '<option value="'.$key.'">'.$value.'</option>';
                  endforeach; ?>
                </select>
              </div></p>
            </div>
            <div class="col-lg-4">
              <h4>Inne:</h4>
              <p><div class='form-group input-group'><span class='input-group-addon'>Platforma</span>
                <select class="form-control" name="platforma">
                  <? foreach ($zadania_platforma_array as $key => $value):
                    echo '<option value="'.$key.'">'.$value.'</option>';
                  endforeach; ?>
                </select>
              </div></p>
              <p><div class='form-group input-group'><span class='input-group-addon'>Serwer</span>
                <select class="form-control" name="serwer">
                  <? foreach ($serwer_array as $key => $value):
                    echo '<option value="'.$key.'">'.$value.'</option>';
                  endforeach; ?>
                </select>
              </div></p>
            </div>
            <div class="col-lg-12">
              <p><input name='filtruj' class='btn btn-primary btn btn-block' type='submit' value='Filtruj'/></p>
            </div>
          </form>
				</div>
      </div>
		</div>
    <div class="col-xs-12">
			<div class="box box">
				<div class="box-header">
				  <h3 class="box-title">Lista Zadań</h3>
				  <div class="pull-right box-tools">
				  </div>
				</div>
				<div class="box-body">
					<table width="100%" class="table table-striped table-bordered table-hover" id="example">
            <thead>
							<tr>
                <th>ID</th>
  							<th>Platforma</th>
  							<th>Typ</th>
  							<th>Temat</th>
  							<th>Status</th>
  							<th>PRC</th>
  							<th>Serwer</th>
  							<th>Data</th>
  							<th></th>
							</tr>
						</thead>
						<tbody>
            <?
            foreach ($acp_zadania_list as $zadania) {
              if($zadania->platforma_web == 1):
                $zadania->nazwa_serwera = '<i>Nie dotyczy</i>';
              elseif(is_null($zadania->serwer_id)):
                $zadania->nazwa_serwera = '<i>Serwer nie istenieje</i>';
              elseif($zadania->serwer_id == 0):
                $zadania->nazwa_serwera = 'Wszystkie';
              endif;
              $zadania->prc_wyk_small = ($zadania->status >= 2) ? '<span class="badge bg-'.$zadania->kolor_wykonania.'">'.$zadania->procent_wykonania.'%</span>' :  '<span class="badge bg-default">0%</span>' ;
            ?>
              <tr class="odd gradeX">
                <td><?= $zadania->id ?></td>
                <td><?= $zadania->platforma ?></td>
                <td><?= $zadania->typ ?></td>
                <td><a href="<?= "?x=$x&xx=zadanie&id=$zadania->id" ?>"><?= $zadania->temat ?></a></td>
                <td><button type='button' class='btn btn-<?= $zadania->kolor ?> btn-xs'><?= $zadania->status_nazwa ?></button></td>
                <td><?= $zadania->prc_wyk_small ?></td>
                <td><?= $zadania->nazwa_serwera ?></td>
                <td><?= $zadania->data ?></td>
                <td>
                  <a href="<?= "?x=$x&xx=zadanie&id=$zadania->id" ?>"><button type="button" class="btn btn-primary"><i class="fa fa-ellipsis-h"></i></button></a>
                </td>
              </tr>
            <? } ?>
            </tbody>
          </table>
				</div>
        <div class="box-footer clearfix no-border">
          <button type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#dodaj"><i class="fa fa-plus"></i> Dodaj zadanie</button>
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
            <h4 class="modal-title">Dodaj nowe zadanie</h4>
          </div>
          <div class="modal-body">
            <form name='nowe' method='post' action='<?= "?x=$x&xx=$xx"; ?>'>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Platforma</span>
                  <select class="form-control" name="platforma">
                      <option value="0">Wybierz</option>
                    <?
                    $platformy_q = all("SELECT * FROM `acp_zadania_platforma`");
                    foreach($platformy_q as $platformy){
                    ?>
                      <option value="<?= $platformy->id ?>"><?= $platformy->nazwa ?></option>
                    <? } ?>
                  </select>
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Serwer</span>
                  <select class="form-control" name="serwer">
                      <option>Wybierz</option>
                      <option value="0">Wszystkie</option>
                    <?
                    $serwery_q = all("SELECT `serwer_id`, `nazwa`, `mod` FROM `acp_serwery`");
                    foreach($serwery_q as $serwery){
                    ?>
                      <option value="<?= $serwery->serwer_id ?>"><?= $serwery->nazwa ?> (<?= $serwery->mod ?>)</option>
                    <? } ?>
                  </select>
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Typ</span>
                  <select class="form-control" name="typ">
                      <option value="0">Wybierz</option>
                    <?
                    $typ_q = all("SELECT * FROM `acp_zadania_typ`");
                    foreach($typ_q as $typ){
                    ?>
                      <option value="<?= $typ->id ?>"><?= $typ->nazwa ?></option>
                    <? } ?>
                  </select>
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Temat</span>
                  <input class="form-control" name="temat" type="text">
                </div>
                <p class="help-block">Krótko czego dotyczy zadanie</p>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Opis</span>
                  <textarea class="form-control" rows="5" name="opis"></textarea>
                </div>
              </p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Zamknij</button>
              <button type="input" name="nowe" class="btn btn-primary">Dodaj Zadanie</button>

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
<?= js_table_one('#example', 'desc'); ?>
</body>
</html>
