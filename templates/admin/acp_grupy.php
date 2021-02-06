<?
$func = getClass('Ustawienia');
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
$co = $_GET['co'];
$id = $_GET['id'];


if(isset($_POST['nowa_grupa'])) {
  $func->dodaj_grupe($_POST['nowa_grupa'], $player->user);
  header("Location: ?x=$x");
}
if(isset($_POST['nowa_dep'])) {
  $func->dodaj_departament($_POST['nowa_dep'], $player->user);
  header("Location: ?x=$x");
}

if($co == 'usun_grupa' || $co == 'usun_dep' && $id >= 0) {
  $func->usun_grupe($id, $co, $player->user);
  header("Location: ?x=$x");
}
else if($co == 'edytuj_grupa' && $id >= 0) {
  $edytuj_grupa_q = row("SELECT *, (SELECT `nazwa` FROM `acp_users_departament` WHERE `id` = `departament`) AS nazwa_departamentu FROM `acp_users_grupy` WHERE `id` = $id LIMIT 1;");
  $grupa_dostep = json_decode($edytuj_grupa_q->dostep)[0];
  $grupa_moduly = json_decode($edytuj_grupa_q->moduly);

  if(isset($_POST['edycja_grupy'])) {
    $e_id = (int) $_POST['id'];
    $e_nazwa = real_string($_POST['e_nazwa']);
    $e_kolor = real_string($_POST['e_kolor']);
    $e_departament = real_string($_POST['e_departament']);
    $tablica_post = $func->zmien_moduly($_POST['checkboxvar']);
    $tablica_post_dostep = $func->zmien_dostep($_POST['e_dostep']);

    query("UPDATE `acp_users_grupy` SET `dostep` = '[".$tablica_post_dostep."]', `moduly` = '$tablica_post', `nazwa` = '$e_nazwa', `kolor` = '$e_kolor', `departament` = '$e_departament' WHERE `id` = $e_id;");

    admin_log($player->user, "Grupa $e_nazwa (ID: $e_id) została zedytowana");
    $_SESSION['msg'] = komunikaty("Grupa $e_nazwa (ID: $e_id) została zedytowana", 1);
  	header("Location: ?x=$x&co=$co&id=$id");
  }
?>
<div class="row">
  <div class="col-xs-12">
    <div class="box box">
      <div class="box-header with-border">
        <h3 class="box-title">Edycja Grupy | <? echo $edytuj_grupa_q->nazwa ?></h3>
      </div>
      <div class="box-body">
        <form name='edycja_grupy' method='post' action='?x=acp_grupy&co=edytuj_grupa&id=<? echo $edytuj_grupa_q->id; ?>'>
        <input type='hidden' name='id' value='<? echo $edytuj_grupa_q->id; ?>'>
          <p>
            <div class='form-group input-group'>
              <span class='input-group-addon'>Nazwa</span><input class='form-control' type='text' name='e_nazwa' value='<? echo $edytuj_grupa_q->nazwa; ?>'/></div>
          </p>
          <p>
            <div class='form-group input-group'>
              <span class='input-group-addon'>Kolor</span><input class='form-control' type='text' name='e_kolor' value='<? echo $edytuj_grupa_q->kolor; ?>'/></div>
          </p>
          <div class='form-group input-group'>
            <span class='input-group-addon'>Departament</span>
            <select class="form-control" name="e_departament">
              <option value="<? echo $edytuj_grupa_q->departament ?>"><? echo $edytuj_grupa_q->nazwa_departamentu ?></option>
              <? $departamenty_list_q = all("SELECT `id`, `nazwa` FROM `acp_users_departament` WHERE `id` !=  $edytuj_grupa_q->departament;");
              foreach($departamenty_list_q as $departamenty_list){ ?>
                <option value="<? echo $departamenty_list->id ?>"><? echo $departamenty_list->nazwa ?></option>
              <? } ?>
              <option value="0">brak</option>
            </select>
          </div>
          <p><input name='edycja_grupy' class='btn btn-primary btn-sm btn-block' type='submit' value='Edytuj'/></p>
          </from>
        </div>
    </div>
  </div>

  <div class="col-xs-12">
    <div class="box box">
      <div class="box-header with-border">
        <h3 class="box-title">Dostęp | Moduły & Uprawnienia</h3>
      </div>
      <div class="box-body">
      <form name='edycja_grupy' method='post' action='?x=acp_grupy&co=edytuj_grupa&id=<? echo $edytuj_grupa_q->id; ?>'>
      <input type='hidden' name='id' value='<? echo $edytuj_grupa_q->id; ?>'>

      <?
      $moduly_q = all("SELECT * FROM `acp_moduly`;");
      foreach ($moduly_q as $modul){
        if(in_Array($modul->nazwa, $grupa_moduly)) {
          $checked = 'checked'; $checked_text = 'Odznacz aby zabrać dostęp'; $checked_collapse = ' <small>(Posiada dostęp)</small>';
        }
        else {
          $checked = '';  $checked_text = 'Zaznacz Aby nadać dostęp'; $checked_collapse = '';
        }
        if($modul->opis == '') { $modul->opis = 'brak opisu modulu..'; }
      ?>
        <div class="box-group" id="accordion">
          <div class="panel box">
            <div class="box-header with-border">
              <p class="box-title ">
                <a class="text-black" data-toggle="collapse" data-parent="#accordion" href="#collapse<?= $modul->id ?>">
                  <i class="<?= $modul->ikona ?>"></i> <span><?= $modul->nazwa_wys ?></span> <?= $checked_collapse ?>
                </a>
              </p>
            </div>
            <div id="collapse<?= $modul->id ?>" class="panel-collapse collapse">
              <div class="box-body">
                <div class="list-group">
                  <li class="list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-between">
                     <?=$modul->opis ?>
                    </div>
                    <p class="mb-1">  <input type="checkbox" value="<?=$modul->nazwa ?>" name="checkboxvar[]" <?=$checked ?> /> <?=$checked_text ?></p>
                  </li>
                </div>
                <hr>
                <div class="list-group">
                  <?
                  $modul_akcja_q = all("SELECT * FROM `acp_moduly_akcje` WHERE `modul_id` = $modul->id ; ");
                  foreach ($modul_akcja_q as $modul_akcja){
                  ?>
                  <li class="list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-between">
                      <div class='form-group input-group'><span class='input-group-addon'><?= $modul_akcja->akcja_wys ?></span>
                        <select class='form-control' name='e_dostep[]'>
                          <?
                          if(in_array(1, (array) $grupa_dostep->{$modul_akcja->akcja})) {
                            echo "<option value='$modul_akcja->akcja-1'>Tak</option>";
                            echo "<option value='$modul_akcja->akcja-0'>Nie</option>";
                          }
                          else{
                            echo "<option value='$modul_akcja->akcja-0'>Nie</option>";
                            echo "<option value='$modul_akcja->akcja-1'>Tak</option>";
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                    <p class="mb-1">Opis:</b> <?= $modul_akcja->opis ?></p>
                  </li>
                  <? } ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      <? } ?>


      <p><input name='edycja_grupy' class='btn btn-primary btn-sm btn-block' type='submit' value='Zapisz zmiany'/></p>
      </from>
      </div>
    </div>
  </div>
</div>

    <?}
else if($_GET['co'] == 'edytuj_dep' && $_GET['id'] >= 0) {
  $edytuj_dep_q = row("SELECT *,
    (SELECT `login` FROM `acp_users` WHERE `user` = `odpowiedzialny`) AS odpowiedzialny_nazwa,
    (SELECT `login` FROM `acp_users` WHERE `user` = `zastepca`) AS zastepca_nazwa,
    (SELECT `nazwa` FROM `acp_users_grupy` WHERE `id` = `grupa_1`) AS grupa_1_nazwa,
    (SELECT `nazwa` FROM `acp_users_grupy` WHERE `id` = `grupa_2`) AS grupa_2_nazwa,
    (SELECT `nazwa` FROM `acp_users_grupy` WHERE `id` = `grupa_3`) AS grupa_3_nazwa
    FROM `acp_users_departament`  WHERE `id` = '".$_GET['id']."' LIMIT 1;");

  if(isset($_POST['edycja_dep'])) {
    $e_odpowiedzialny = $func->odnajdz_usera($_POST['e_odpowiedzialny']);
    $e_zastepca = $func->odnajdz_usera($_POST['e_zastepca']);

    $e_gru_1 = $func->odnajdz_grupe($_POST['e_gru_1']);
    $e_gru_2 = $func->odnajdz_grupe($_POST['e_gru_2']);
    $e_gru_3 = $func->odnajdz_grupe($_POST['e_gru_3']);

    $e_id = (int) $_POST['id'];
    $e_nazwa = real_string($_POST['e_nazwa']);

    query("UPDATE `acp_users_departament` SET `nazwa` = '$e_nazwa', `odpowiedzialny` = $e_odpowiedzialny, `zastepca` = $e_zastepca, `grupa_1` = $e_gru_1, `grupa_2` = $e_gru_2, `grupa_3` = $e_gru_3 WHERE `id` = $e_id; ");

    admin_log($player->user, "Departament $e_nazwa (ID: $e_id) został zedytowany");
    $_SESSION['msg'] = komunikaty("Departament $e_nazwa (ID: $e_id) został zedytowany", 1);
    header("Location: ?x=$x&co=$co&id=$id");
  }
?>
    <div class="row">
      <div class="col-xs-12">
        <div class="box box">
          <div class="box-header">
            <h3 class="box-title">Edycja Departamentu - <? echo $edytuj_dep_q->nazwa ?></h3>
          </div>
          <div class="box-body">
            <? echo komunikaty("Uwaga! Używamy pełnych nazw użytkonikow/grupy aby zmienic daną wartośc departamentu.", 2); ?>
            <form name='edycja_depoartamentu' method='post' action='?x=<? echo $x ?>&co=edytuj_dep&id=<? echo $edytuj_dep_q->id; ?>'>
              <input type='hidden' name='id' value='<? echo $edytuj_dep_q->id; ?>'>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Nazwa</span><input class='form-control' type='text' name='e_nazwa' value='<? echo $edytuj_dep_q->nazwa; ?>'/></div>
              </p>
              <hr>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Odpowiedzialny</span><input class='form-control' type='text' name='e_odpowiedzialny' value='<? echo $edytuj_dep_q->odpowiedzialny_nazwa; ?>'/></div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Zastępca</span><input class='form-control' type='text' name='e_zastepca' value='<? echo $edytuj_dep_q->zastepca_nazwa; ?>'/></div>
              </p>
              <hr>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Grupa Nr 1</span><input class='form-control' type='text' name='e_gru_1' value='<? echo $edytuj_dep_q->grupa_1_nazwa; ?>'/></div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Grupa Nr 2</span><input class='form-control' type='text' name='e_gru_2' value='<? echo $edytuj_dep_q->grupa_2_nazwa; ?>'/></div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Grupa Nr 3</span><input class='form-control' type='text' name='e_gru_3' value='<? echo $edytuj_dep_q->grupa_3_nazwa; ?>'/></div>
              </p>
              <p><input name='edycja_dep' class='btn btn-primary btn-sm btn-block' type='submit' value='Edytuj'/></p>
            </from>
          </div>
        </div>
      </div>
    </div>
  <?}
else {
?>
    <div class="row">
      <div class="col-xs-12">
        <div class="box box">
          <div class="box-header">
            <h3 class="box-title">Grupy</h3>
          </div>
          <div class="box-body">
            <table data-page-length='10' id="example" class="table table-bordered table-striped" width="100%">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nazwa</th>
                  <th>Departament</th>
                  <th>Użytkowników</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <?
    						$grupy_q = all("SELECT *, departament AS departament_id,
                (SELECT COUNT(`login`) FROM `acp_users` WHERE `grupa` = `id`) AS liczba_userow,
                (SELECT `nazwa` FROM `acp_users_departament` WHERE `id` = `departament_id` LIMIT 1) AS departament_nazwa
                FROM `acp_users_grupy` ORDER BY `id` +0 ASC;");
    						foreach($grupy_q as $grupy){
                  if($grupy->departament_nazwa == '') { $grupy->departament_nazwa = 'brak'; }
    						?>
                <tr class="odd gradeX">
                  <td><? echo $grupy->id; ?></td>
                  <td><? echo $grupy->nazwa; ?></td>
                  <td><? echo $grupy->departament_nazwa; ?></td>
                  <td><? echo $grupy->liczba_userow; ?></td>
                  <td>
                    <a href="<? echo "?x=$x&co=edytuj_grupa&id=$grupy->id" ?>"><button type="button" class="btn btn-primary"><i class="fa fa-edit"></i></button></a>
                    <a href="<? echo "?x=$x&co=usun_grupa&id=$grupy->id" ?>"><button type="button" class="btn btn-danger"><i class="fa fa-times"></i></button></a>
                  </td>
                </tr>
                <? } ?>
              </tbody>
            </table>
          </div>
          <div class="box-footer clearfix no-border">
            <button type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#dodaj-grupe"><i class="fa fa-plus"></i> Nowa Grupa</button>
          </div>
        </div>
      </div>
      <div class="col-xs-12">
        <div class="box box">
          <div class="box-header">
            <h3 class="box-title">Departamenty</h3>
            <div class="pull-right box-tools"></div>
          </div>
          <div class="box-body">
            <table data-page-length='10' id="example2" class="table table-bordered table-striped" width="100%">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nazwa</th>
                  <th>Odpowiedzialny</th>
                  <th>Zastępca</th>
                  <th>Grupa 1</th>
                  <th>Grupa 2</th>
                  <th>Grupa 3</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <?
    						$departamenty_q = all("SELECT *,
                (SELECT `login` FROM `acp_users` WHERE `user` = `odpowiedzialny`) AS odpowiedzialny_nazwa,
                (SELECT `login` FROM `acp_users` WHERE `user` = `zastepca`) AS zastepca_nazwa,

                (SELECT `nazwa` FROM `acp_users_grupy` WHERE `id` = `grupa_1`) AS grupa_1_nazwa,
                (SELECT `nazwa` FROM `acp_users_grupy` WHERE `id` = `grupa_2`) AS grupa_2_nazwa,
                (SELECT `nazwa` FROM `acp_users_grupy` WHERE `id` = `grupa_3`) AS grupa_3_nazwa
    						FROM `acp_users_departament` ORDER BY `id` +0 ASC;");
    						foreach($departamenty_q as $departamenty){
    						?>
                <tr class="odd gradeX">
                  <td><? echo $departamenty->id; ?></td>
                  <td><? echo $departamenty->nazwa; ?></td>
                  <td><? echo $departamenty->odpowiedzialny_nazwa; ?></td>
                  <td><? echo $departamenty->zastepca_nazwa; ?></td>
                  <td><? echo $departamenty->grupa_1_nazwa; ?></td>
                  <td><? echo $departamenty->grupa_2_nazwa; ?></td>
                  <td><? echo $departamenty->grupa_3_nazwa; ?></td>
                  <td>
                    <a href="<? echo "?x=$x&co=edytuj_dep&id=$departamenty->id" ?>"><button type="button" class="btn btn-primary"><i class="fa fa-edit"></i></button></a>
                    <a href="<? echo "?x=$x&co=usun_dep&id=$departamenty->id" ?>"><button type="button" class="btn btn-danger"><i class="fa fa-times"></i></button></a>
                  </td>
                </tr>
                <? } ?>
              </tbody>
            </table>
          </div>
          <div class="box-footer clearfix no-border">
            <button type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#dodaj-departament"><i class="fa fa-plus"></i> Nowy Departament</button>
          </div>
        </div>
      </div>
    </div>
    <? } ?>

    <div class="row">
      <div class="modal fade" id="dodaj-grupe">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Dodaj Grupę</h4>
            </div>
            <form name='nowa_grupa' method='post' action='?x=<? echo $x ?>'>
              <div class="modal-body">
                <p>
                  <div class='form-group input-group'>
                    <span class='input-group-addon'>Nazwa</span>
                    <input class="form-control" name="new_nazwa">
                  </div>
                </p>
                <p>
                  <div class='form-group input-group'>
                    <span class='input-group-addon'>Kolor</span>
                    <input class="form-control" name="new_kolor">
                  </div>
                </p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Zamknij</button>
                <button type="input" name="nowa_grupa" class="btn btn-primary">Zapisz</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="modal fade" id="dodaj-departament">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Dodaj Departament</h4>
            </div>
            <form name='nowa_dep' method='post' action='?x=<? echo $x ?>'>
              <div class="modal-body">
                <p>
                  <div class='form-group input-group'>
                    <span class='input-group-addon'>Nazwa</span>
                    <input class="form-control" name="new_nazwa">
                  </div>
                </p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Zamknij</button>
                <button type="input" name="nowa_dep" class="btn btn-primary">Zapisz</button>
              </div>
            </form>
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
<?= js_table_one('#example2'); ?>
</body>
</html>
