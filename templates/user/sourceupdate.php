<?
$func = getClass('SourceUpdate');

function getDirContents($dir, &$results = array()) {
    $files = scandir($dir);

    foreach ($files as $key => $value) {
        $path = $dir . DIRECTORY_SEPARATOR . $value;
        if (!is_dir($path)) {
            $results[] = $path;
        } else if ($value != "." && $value != "..") {
            getDirContents($path, $results);
            $results[] = $path;
        }
    }
    return $results;
}
$serwer_array = array(0 => 'Wszystkie');
$serwer_array_q = all("SELECT `serwer_id`, `mod`, `nazwa` FROM `acp_serwery`");
foreach($serwer_array_q as $serwer_array_dane){
  $serwer_array[$serwer_array_dane->serwer_id]="$serwer_array_dane->nazwa";
}
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
if(isset($_POST['aktualizuj'])){
  $func->aktualizuj($player->user, $dostep->SourceUpdate);
  header("Location: ?x=$x");
}
?>
	<div class="row">
		<div class="col-lg-9">
			<div class="box box">
				<div class="box-header">
				  <h3 class="box-title">Aktualizator Sourcemod</h3>
				</div>
				<div class="box-body">
					<form method="post">
            <p><div class='form-group input-group'><span class='input-group-addon'>Serwer</span>
              <select class="form-control" name="serwer_id">
                <?
                foreach ($serwer_array as $key => $value):
                  if($acp_r_d->serwer_id != $key)
                  echo '<option value="'.$key.'">'.$value.'</option>';
                endforeach;
                ?>
              </select>
            </div></p>
            <p>
              <div class='form-group input-group'>
                <span class='input-group-addon'>Sourcemod Wersja</span>
                <select class="form-control" name="sourcemod">
                  <option value="0">Nie</option>
                  <?
                  $source_wersje = all("SELECT * FROM `acp_sourceupdate` ORDER BY `id` DESC");
                  foreach ($source_wersje as $key => $value):
                    echo '<option value="'.$value->code.'">'.$value->sm.'</option>';
                  endforeach;
                  ?>
                </select>
              </div>
            </p>
            <p>
              <div class='form-group input-group'>
                <span class='input-group-addon'>Metamod Wersja</span>
                <select class="form-control" name="metamod">
                  <option value="0">Nie</option>
                  <?
                  foreach ($source_wersje as $key => $value):
                    echo '<option value="'.$value->code.'">'.$value->mm.'</option>';
                  endforeach;
                  ?>
                </select>
              </div>
            </p>
            <p> Sourcemod - Wybór katalogów do aktualizacji:</p>
              <div class="form-group input-group">
                <div class="checkbox">
                  <label>
                    <input type="checkbox" name="sm_bin" checked>
                    addons/sourcemod/bin
                  </label>
                </div>
                <div class="checkbox">
                  <label>
                    <input type="checkbox" name="sm_configs">
                    addons/sourcemod/configs
                  </label>
                </div>
                <div class="checkbox">
                  <label>
                    <input type="checkbox" name="sm_extensions" checked>
                    addons/sourcemod/extensions
                  </label>
                </div>
                <div class="checkbox">
                  <label>
                    <input type="checkbox" name="sm_gamedata" checked>
                    addons/sourcemod/gamedata
                  </label>
                </div>
                <div class="checkbox">
                  <label>
                    <input type="checkbox" name="sm_plugins">
                    addons/sourcemod/plugins
                  </label>
                </div>
                <div class="checkbox">
                  <label>
                    <input type="checkbox" name="sm_scripting">
                    addons/sourcemod/scripting
                  </label>
                </div>
                <div class="checkbox">
                  <label>
                    <input type="checkbox" name="sm_translations">
                    addons/sourcemod/translations
                  </label>
                </div>
                <div class="checkbox">
                  <label>
                    <input type="checkbox" name="sm_cfg">
                    addons/cfg/sourcemod
                  </label>
                </div>
              </div>
            <p>
              <button type="input" name="aktualizuj" class="btn btn-primary">Aktualizuj</button>
            </p>
          </form>
				</div>
			</div>
		</div>
		<div class="col-lg-3">
			<div class="box box">
				<div class="box-header">
				  <h3 class="box-title">Dostępne Systemy Source</h3>
				</div>
				<div class="box-body">
          <?
          $systemy_source = all("SELECT * FROM `acp_sourceupdate` ORDER BY `id` DESC LIMIT 5");
          foreach ($systemy_source as $key => $value) {
          ?>
          <div class="list-group">
            <a class="list-group-item list-group-item-action active">
              Data: <?= czas_relatywny($value->data) ?>
            </a>
            <a class="list-group-item list-group-item-action"><?= $value->sm ?></a>
            <a class="list-group-item list-group-item-action"><?= $value->mm ?></a>
          </div>
          <? } ?>
				</div>
			</div>
		</div>
		<div class="col-lg-3">
			<div class="box box">
				<div class="box-header">
				  <h3 class="box-title">Systemy na serwerach</h3>
				</div>
				<div class="box-body">
          <?
          $serwery = all("SELECT `serwer_id` , `mod`, `nazwa` FROM `acp_serwery`");
          foreach ($serwery as $key => $value) {
            $source = row("SELECT * FROM `acp_serwery_update` WHERE `serwer_id` = $value->serwer_id LIMIT 1");
            $value->source = (empty($source->source)) ? '<i>Brak danych</i>' : $source->source;
            $value->meta = (empty($source->meta)) ? '<i>Brak danych</i>' : $source->meta;
          ?>
          <div class="list-group">
            <a class="list-group-item list-group-item-primary">
              Serwer: <?= $value->nazwa ?> [<?= $value->mod ?>]
            </a>
            <a class="list-group-item list-group-item-action"><?= $value->source ?></a>
            <a class="list-group-item list-group-item-action"><?= $value->meta ?></a>
          </div>
          <? } ?>
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
</body>
</html>
