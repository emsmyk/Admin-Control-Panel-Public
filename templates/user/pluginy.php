<?
$func = getClass('Pluginy');
$wgrywarka = getClass('Wgrywarka');
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

if(isset($_POST['nowe'])) {
  $func->nowy_plugin($_POST['nowe'], $player->user, $dostep->PluginyDodaj);
  header("Location: ?x=$x");
}
if(isset($_POST['edytuj'])) {
  $func->edytuj_plugin($_POST['edytuj'], $player->user, $dostep->PluginyEdytuj);
  header("Location: ?x=$x&id=$id");
}
switch ($_GET['co']) {
  case 'usun':
    $func->usun_plugin($_GET['id'], $player->user, $dostep->PluginyUsun);
    header("Location: ?x=$x");
    break;
  case 'usun_plik':
    $func->plugin_usun_plik($_GET['id_pliku'], $player->user, $dostep->PluginyPlikUsun);
    header("Location: ?x=$x&id=$id");
    break;
}
if (isset($_POST['wgraj_plik'])) {
  $func->plugin_wgraj_plik($_FILES['plik'], $player->user, $dostep->PluginyPlikDodaj);
  header("Location: ?x=$x&id=$id");
}
if(isset($_POST['edytuj_plik'])) {
  $func->plugin_edytuj_plik($_POST['edytuj_plik'], $player->user, $dostep->PluginyPlikEdytuj);
  header("Location: ?x=$x&id=$id");
}
if(isset($_POST['wgrywarka'])){
   $wgrywarka->plugin($_POST['serwery'], $player->user, $dostep->PluginyWgrywarka);
   header("Location: ?x=$x&id=$id");
}
?>
  <div class="row">
    <? if(!empty($id)):
      $dane = row("SELECT *,(SELECT `login` FROM `acp_users` WHERE `user` = `u_id` LIMIT 1) AS `login` FROM `acp_pluginy` WHERE `id` = $id");
      tytul_strony("Pluginy: $dane->nazwa");
      $dane->opis = (empty($dane->opis)) ? 'brak': $dane->opis;
      $dane->cvary = (empty($dane->cvary)) ? 'brak': $dane->cvary;
      $dane->notatki = (empty($dane->notatki)) ? 'brak': $dane->notatki;
    ?>
    <div class="col-lg-8">
      <div class="box box">
        <div class="box-header">
          <h3 class="box-title"><i class="fa fa-plug"></i>  Plugin  #<?= $dane->id ?> <br><small><?= $dane->nazwa ?></small></h3>
          <div class="pull-right box-tools">
          </div>
        </div>
        <div class="box-body">
          <div class="col-lg-6">
            <dl>
              <dt>Nazwa Pluginu</dt>
              <dd><?= $dane->nazwa ?></dd>
              <dt>Opis</dt>
              <dd><?= $dane->opis ?></dd>
              <dt>Cvary</dt>
              <dd><var><?= $dane->cvary ?></var></dd>
              <dt>Notatki</dt>
              <dd><?= $dane->notatki ?></dd>
            </dl>
          </div>
          <div class="col-lg-6">
            <dl>
              <dt>Dodający</dt>
              <dd><a href="?x=account&id=<?= $dane->u_id ?>"><?= $dane->login ?></a></dd>
              <dt>Data Dodania</dt>
              <dd><?= $dane->data_dodania ?></dd>
            </dl>
          </div>
        </div>
      </div>
      <div class="box box">
        <div class="box-header">
          <h3 class="box-title"><i class="fa fa-file"></i>  Pliki (<?= one("SELECT COUNT(`id`) FROM `acp_pluginy_pliki` WHERE `plugin_id` = $dane->id; "); ?>)</h3>
        </div>
        <div class="box-body">
          <div class="col-lg-12">
              <table class="table table-hover">
                <tbody>
                <tr>
                  <th>Plik</th>
                  <th>Data</th>
                  <th></th>
                </tr>
              <?
              $pliki_q = all("SELECT * FROM `acp_pluginy_pliki` WHERE `plugin_id` = $dane->id ORDER BY `starsza_wersja`, `kod_zrodlowy` ASC");
              foreach ($pliki_q as $pliki):
              $pliki->ftp_source_file_name_color = file_exists($pliki->ftp_source_file_name);
              $pliki->ftp_source_file_name_color = ($pliki->ftp_source_file_name_color) ? 'success' : 'danger';
              $pliki->ftp_source_file_name = ($pliki->ftp_source_file_name_color == 'danger') ? '#' : $pliki->ftp_source_file_name;
              $pliki->brak_ftp_dorectory = (empty($pliki->ftp_directory)) ? '<span class="label label-danger">Plik ma brak lokalizacji na serwerze, uzupełnij go!</span>' : '';
              $pliki->kod_zrodlowy = (is_null($pliki->kod_zrodlowy)) ? '' : '<span class="label label-success">Kod Żródłowy</span>';
              $pliki->starsza_wersja = (is_null($pliki->starsza_wersja)) ? '' : '<span class="label label-default">Starsza Wersja</span>';
              ?>
                  <tr>
                    <td><?= $pliki->ftp_dest_file_name." ".$pliki->brak_ftp_dorectory ?>
                      <br> <?= $pliki->kod_zrodlowy ?> <?= $pliki->starsza_wersja ?>
                    </td>
                    <td><?= $pliki->data ?></td>
                    <td>
                      <div class="btn-group">
                        <button onclick="window.location.href='<?= "?x=download&xx=".$pliki->ftp_source_file_name ?>'" type="button" class="btn btn-success"><i class="fa fa-cloud-download"></i> Pobierz</button>
                        <button type="button" class="btn btn-default" data-toggle="modal" data-target="#edytuj_pliki_<?= $pliki->id ?>"><i class="fa fa-edit"></i> Edytuj</button>
                        <button onclick="window.location.href='<?= "?x=$x&id=$dane->id&co=usun_plik&id_pliku=$pliki->id"; ?>'" type="button" class="btn btn-danger"><i class="fa fa-close"></i> Usuń</button>
                      </div>
                    </td>
                  </tr>
              <? endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="box box">
        <div class="box-header">
          <h3 class="box-title"><i class="fa fa-gear"></i> Funkcje</h3>
        </div>
        <div class="box-body">
          <button type="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#wgrywarka"><i class="fa fa-cloud-upload"></i> Wgrywarka</button>
          <button type="button" class="btn btn-default btn-block" data-toggle="modal" data-target="#pliki"><i class="fa fa-file"></i> Wgraj Plik</button>
          <button type="button" class="btn btn-default btn-block" data-toggle="modal" data-target="#edytuj"><i class="fa fa-edit"></i> Edytuj Plugin</button>
          <a class="btn btn-danger btn-block" href="<?= "?x=$x&co=usun&id=$dane->id" ?>"><i class="fa fa-close"></i> Usuń Plugin</a>
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="box box">
        <div class="box-header">
          <h3 class="box-title"><i class="fa fa-info"></i> Pugin wgrany na serwery</h3>
        </div>
          <div class="box-body">
            <ul class="list-group">
            <?
            $plugin_serwer = new stdClass();
            $plugin_serwer->serwery_ist = new stdClass();
            $plugin_serwer->lista = array();
            $plugin_serwer->nazwa_pluginu = one("SELECT `ftp_dest_file_name` FROM `acp_pluginy_pliki` WHERE `plugin_id` = $dane->id AND `ftp_source_file_name` LIKE '%smx%' AND `kod_zrodlowy` IS NULL AND `starsza_wersja` IS NULL");
            $plugin_serwer->serwery = all("SELECT `get`, `data` FROM `acp_cache_api` WHERE `dane` LIKE '%$plugin_serwer->nazwa_pluginu%';");
            foreach ($plugin_serwer->serwery as $key => $value):
              $plugin_serwer->serwery_ist->$key->serwer_id = str_replace(array('serwer_id','_pluginy'), "", $value->get);
              $plugin_serwer->serwery_ist->$key->data = str_replace(array('serwer_id','_pluginy'), "", $value->data);
              $plugin_serwer->serwery_ist->$key->dane = row("SELECT `nazwa`, `mod`, `serwer_id` FROM `acp_serwery` WHERE `serwer_id` = ".$plugin_serwer->serwery_ist->$key->serwer_id." LIMIT 1");
              $plugin_serwer->lista[] = $plugin_serwer->serwery_ist->$key->serwer_id;
            endforeach;
            unset($plugin_serwer->serwery);
            ?>

            <? if(empty($plugin_serwer->nazwa_pluginu)): ?>
              <p>Plugin nie posiada plików, system nie jest w stanie stwierdzić czy plugin o tej nazwe istnieje na serwerach..</p>
            <?
              else:
              foreach ($plugin_serwer->serwery_ist as $value):
            ?>
              <p>
                <a href='?x=serwery_det&serwer_id=<?= $value->serwer_id ?>'>
                  <li class='list-group-item list-group-item-dark'>
                    <b>[<?= $value->dane->mod ?>]</b> <?= $value->dane->nazwa ?><br><small>Informacja z <?= $value->data ?></small>
                  </li>
                </a>
              </p>
            <? endforeach;
            endif; ?>
          </div>
        </div>
      </div>
    </div>
  <? else: ?>
  </div>

  <div class="row">
    <div class="col-xs-12">
			<div class="box box">
				<div class="box-header">
				  <h3 class="box-title"><i class="fa fa-plug"></i> Bilioteka Pluginów</h3>
				  <div class="pull-right box-tools">
				  </div>
				</div>
				<div class="box-body">
					<table width="100%" class="table table-striped table-bordered table-hover" id="example">
            <thead>
							<tr>
                <th>ID</th>
  							<th>Nazwa</th>
  							<th>Dodał</th>
  							<th>Data dodania</th>
  							<th></th>
							</tr>
						</thead>
						<tbody>
            <?
            $pluginy_q = all("SELECT *,(SELECT `login` FROM `acp_users` WHERE `user` = `u_id` LIMIT 1) AS login FROM `acp_pluginy` ORDER BY `id` DESC");
            foreach ($pluginy_q as $pluginy) {
            ?>
              <tr class="odd gradeX">
                <td><?= $pluginy->id ?></td>
                <td><?= $pluginy->nazwa ?></td>
                <td><?= $pluginy->login ?></td>
                <td><?= $pluginy->data_dodania ?></td>
                <td>
                  <div class="btn-group">
                    <a href="<?= "?x=$x&id=$pluginy->id" ?>" class="btn btn-primary" role="button" aria-pressed="true"><i class="fa fa-edit"></i> Detale</button></a>
                    <a href="<?= "?x=$x&co=usun&id=$pluginy->id" ?>" class="btn btn-danger" role="button" aria-pressed="true"><i class="fa fa-times"></i> Usuń</button></a>
                  </div>
                </td>
              </tr>
            <? } ?>
            </tbody>
          </table>
				</div>
        <div class="box-footer clearfix no-border">
          <button type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#dodaj"><i class="fa fa-plus"></i> Dodaj Plugin</button>
        </div>
      </div>
		</div>
  </div>
  <? endif; ?>

</section>
</div>

<div class="row">
  <div class="modal fade" id="dodaj">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Dodaj Plugin</h4>
        </div>
        <div class="modal-body">
          <form name='nowe' method='post' action='<?= "?x=$x"; ?>'>
            <p>
              <div class='form-group input-group'>
                <span class='input-group-addon'>Nazwa</span>
                <input class="form-control" name="nazwa" type="text">
              </div>
            </p>
            <p>
              <div class='form-group input-group'>
                <span class='input-group-addon'>Opis</span>
                <textarea class="form-control" rows="3" name="opis"></textarea>
              </div>
            </p>
            <p>
              <div class='form-group input-group'>
                <span class='input-group-addon'>Cvary</span>
                <textarea class="form-control" rows="3" name="cvary"></textarea>
              </div>
            </p>
            <p>
              <div class='form-group input-group'>
                <span class='input-group-addon'>Notatki</span>
                <textarea class="form-control" rows="3" name="notatki"></textarea>
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
  <? if(!empty($id)): ?>
  <div class="modal fade" id="edytuj">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Edytuj Plugin</h4>
        </div>
        <div class="modal-body">
          <form name='edytuj' method='post' action='<?= "?x=$x&id=$dane->id"; ?>'>
            <input type="hidden" name="id" value="<?= $dane->id ?>">
            <p>
              <div class='form-group input-group'>
                <span class='input-group-addon'>Nazwa</span>
                <input class="form-control" name="nazwa" type="text" value="<?= $dane->nazwa ?>">
              </div>
            </p>
            <p>
              <div class='form-group input-group'>
                <span class='input-group-addon'>Opis</span>
                <textarea class="form-control" rows="3" name="opis"><?= $dane->opis ?></textarea>
              </div>
            </p>
            <p>
              <div class='form-group input-group'>
                <span class='input-group-addon'>Cvary</span>
                <textarea class="form-control" rows="3" name="cvary"><?= $dane->cvary ?></textarea>
              </div>
            </p>
            <p>
              <div class='form-group input-group'>
                <span class='input-group-addon'>Notatki</span>
                <textarea class="form-control" rows="3" name="notatki"><?= $dane->notatki ?></textarea>
              </div>
            </p>
            <h4 class="modal-title">Licencja</h4>
            <p>
              <div class='form-group input-group'>
                <span class='input-group-addon'>Nazwa</span>
                <input class="form-control" name="lic_name" type="text" value="<?= $dane->lic_name ?>">
              </div>
            </p>
            <p>
              <div class='form-group input-group'>
                <span class='input-group-addon'>Hash</span>
                <input class="form-control" name="lic_hash" type="text" value="<?= $dane->lic_hash ?>">
              </div>
            </p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Zamknij</button>
            <button type="input" name="edytuj" class="btn btn-primary">Edytuj</button>

          </form>
        </div>
      </div>
    </div>
  </div>
    <?
    $pliki_q = all("SELECT * FROM `acp_pluginy_pliki` WHERE `plugin_id` = $id");
    foreach ($pliki_q as $pliki):
      $pliki->kod_zrodlowy = (is_null($pliki->kod_zrodlowy)) ? '' : 'checked';
      $pliki->starsza_wersja = (is_null($pliki->starsza_wersja)) ? '' : 'checked';
    ?>
    <div class="modal fade" id="edytuj_pliki_<?= $pliki->id ?>">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Edytuj Plik - <?= $pliki->ftp_dest_file_name ?></h4>
          </div>
          <div class="modal-body">
            <form name='edytuj_plik' method='post' action='<?= "?x=$x&id=$dane->id"; ?>'>
              <input type="hidden" name="id" value="<?= $pliki->id ?>">
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Nazwa Pluginu</span>
                  <input class="form-control" name="nazwa" type="text" value="<?= $pliki->ftp_dest_file_name ?>">
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Gdzie Wgrać?</span>
                  <input class="form-control" name="gdzie_wgrac" type="text" value="<?= $pliki->ftp_directory ?>">
                </div>
              </p>
              <p class="help-block">np: <i>/addons/sourcemod/plugins</i></p>
              <p>
                <div class="checkbox">
                  <label>
                    <input type="checkbox" name="kod_zrodlowy" <?= $pliki->kod_zrodlowy ?>> Kod Źródłowy
                  </label>
                </div>
              </p>
              <p>
                <div class="checkbox">
                  <label>
                    <input type="checkbox" name="starsza_wersja" <?= $pliki->starsza_wersja ?>> Starsza Wersja
                  </label>
                </div>
              </p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Zamknij</button>
              <button type="input" name="edytuj_plik" class="btn btn-primary">Edytuj</button>

            </form>
          </div>
        </div>
      </div>
    </div>
    <? endforeach; ?>
  <div class="modal fade" id="pliki">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Wgraj Plik</h4>
        </div>
        <div class="modal-body">
          <form action="<?= "?x=$x&id=$dane->id"; ?>" method="POST" ENCTYPE="multipart/form-data" name="wgraj_plik">
            <input type='hidden' name='id' value='<?= $dane->id ?>'>

            <div class="form-group">
              <label>Nazwa Pliku</label>
              <input type="text" class="form-control" name="nazwa">
              <p class="help-block">Jeśli pozostawisz puste, zostanie użyta nazwa pliku.</p>
            </div>
            <div class="form-group">
              <label>Gdzie Wgrać?</label>
              <input type="text" class="form-control" placeholder="Enter ..." name="gdzie">
              <p class="help-block">np: /addons/sourcemod/plugins</p>
            </div>
            <div class="form-group">
              <label for="exampleInputFile">Plik</label>
              <input type="file" id="exampleInputFile" name="plik">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Zamknij</button>
            <button type="input" name="wgraj_plik" class="btn btn-primary">Wyślij plik</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="wgrywarka">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Wybierz Serwer</h4>
        </div>
        <div class="modal-body">
          <form action="<?= "?x=$x&id=$dane->id"; ?>" method='post' name="wgrywarka">
            <input type='hidden' name='id' value='<?= $dane->id ?>'>
            <div class="form-group">
            <?
            $serwery_Q = all("SELECT `serwer_id`, `mod`, `nazwa` FROM `acp_serwery` WHERE `serwer_on` = 1 AND `cronjobs` = 1 ORDER BY `istotnosc` ASC");
            foreach ($serwery_Q as $serwery):
              $serwery->istnieje = (in_array($serwery->serwer_id, $plugin_serwer->lista)) ? '<small class="label label-warning"><i class="fa fa-clock-o"></i> Plugin Wgrany</small>' : '' ;
            ?>
             <div class="checkbox">
               <label>
                 <input type="checkbox" name="serwery[]" value="<?=$serwery->serwer_id ?>">
                 <b><?=$serwery->mod ?></b> <?= $serwery->nazwa ?> <?= $serwery->istnieje ?>
               </label>
             </div>
           <? endforeach; ?>
           </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Zamknij</button>
            <button type="input" name="wgrywarka" class="btn btn-primary">Wgrywaj!</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <? endif ?>
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
