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
if(isset($_POST['dodaj'])) {
  $func->acp_moduly_dodaj($_POST, $player->user);
  header("Location: ?x=$x");
}
if(isset($_POST['edycja_from'])) {
  $func->acp_moduly_edytuj_modul($_POST, $player->user);
  header("Location: ?x=$x&id=".(int)$_GET["id"]."&co=edytuj");
}
if(isset($_POST['edycja_from_uprawnienia_add'])){
  $func->edycja_from_uprawnienia_add($_POST, $player->user);
  header("Location: ?x=$x&id=".(int)$_GET["id"]."&co=edytuj");
}
if(isset($_POST['edycja_from_uprawnienia_zapisz'])){
  $func->edycja_from_uprawnienia_zapisz($_POST, $player->user);
  header("Location: ?x=$x&id=".(int)$_GET["id"]."&co=edytuj");
}
if(isset($_POST['edycja_from_uprawnienia_usun'])){
  $func->edycja_from_uprawnienia_usun($_POST, $player->user);
  header("Location: ?x=$x&id=".(int)$_GET["id"]."&co=edytuj");
}

if(isset($_POST['edycja_from_menu_add'])){
  $func->edycja_from_menu_add($_POST, $player->user);
  header("Location: ?x=$x&id=".(int)$_GET["id"]."&co=edytuj");
}
if(isset($_POST['edycja_from_menu_zapisz'])){
  $func->edycja_from_menu_zapisz($_POST, $player->user);
  header("Location: ?x=$x&id=".(int)$_GET["id"]."&co=edytuj");
}
if(isset($_POST['edycja_from_menu_usun'])){
  $func->edycja_from_menu_usun($_POST, $player->user);
  header("Location: ?x=$x&id=".(int)$_GET["id"]."&co=edytuj");
}


switch ($_GET['co']) {
  case 'usun':
    $func->acp_moduly_usun($_GET['id'], $player->user);
    header("Location: ?x=$x");
    break;
}
?>

<?
if($_GET['co'] == 'edytuj' && !empty($_GET['id'])) {
  $edycja_id = $_GET["id"];
  $edycja_mod = row("SELECT * FROM `acp_moduly` WHERE `id` = $edycja_id LIMIT 1;");
?>
<div class="row">
  <div class="col-xs-12">
    <div class="box box">
      <div class="box-header">
        <h3 class="box-title">Modułu ID: <? echo $_GET['id'] ?></h3>
        <div class="pull-right box-tools">
        </div>
      </div>
      <div class="box-body">
        <form name='edycja_from' method='post' action='<? echo "?x=$x&id=$edycja_id&co=edytuj"; ?>'>
          <input type='hidden' name='e_id' value='<? echo $edycja_mod->id ?>'>
          <p><div class='form-group input-group'><span class='input-group-addon'>Nazwa (PHP)</span><input class='form-control' type='text' name='e_nazwa' value='<? echo $edycja_mod->nazwa ?>'/></div></p>
          <p><div class='form-group input-group'><span class='input-group-addon'>Nazwa Wyświetlana</span><input class='form-control' type='text' name='e_nazwa_wys' value='<? echo $edycja_mod->nazwa_wys ?>'/></div></p>
          <p><div class='form-group input-group'><span class='input-group-addon'>Ikona</span><input class='form-control' type='text' name='e_ikona' value='<? echo $edycja_mod->ikona ?>'/></div></p>
          <p><div class='form-group input-group'><span class='input-group-addon'>Menu</span>
            <select class="form-control" name="e_menu">
              <?
              if($edycja_mod->menu == 0) {
                echo '
                  <option value="0">NIE (Brak pozycji w menu)</option>
                  <option value="1">Tak (Pojedyńczy link)</option>
                  <option value="2">Tak (Rozwiana lista)</option>
                  ';
              }
              else if($edycja_mod->menu == 1) {
                echo '
                  <option value="1">Tak (Pojedyńczy link)</option>
                  <option value="0">NIE (Brak pozycji w menu)</option>
                  <option value="2">Tak (Rozwiana lista)</option>
                  ';
              }
              else if($edycja_mod->menu == 2) {
                echo '
                  <option value="2">Tak (Rozwiana lista)</option>
                  <option value="0">NIE (Brak pozycji w menu)</option>
                  <option value="1">Tak (Pojedyńczy link)</option>
                  ';
              }
              ?>
            </select>
          </div></p>
          <p><div class='form-group input-group'><span class='input-group-addon'>Kategoria Menu</span>
            <select class="form-control" name="e_menu_kategoria">
              <?
              if($edycja_mod->menu_kategoria == '0') {
                echo '
                  <option value="0">Podstawowe</option>
                  <option value="1">Serwery Gier</option>
                  <option value="2">Administracja</option>
                  ';
              }
              else if($edycja_mod->menu_kategoria == '1'){
                echo '
                  <option value="1">Serwery Gier</option>
                  <option value="0">Podstawowe</option>
                  <option value="2">Administracja</option>
                  ';
              }
              else if($edycja_mod->menu_kategoria == '2') {
                echo '
                  <option value="2">Administracja</option>
                  <option value="0">Podstawowe</option>
                  <option value="1">Serwery Gier</option>
                  ';
              }
              ?>
            </select>
          </div></p>
          <p><div class='form-group input-group'><span class='input-group-addon'>Opis</span><input class='form-control' type='text' name='e_opis' value='<? echo $edycja_mod->opis ?>'/></div></p>
          <p><input name='edycja_from' class='btn btn-primary btn-sm btn-block' type='submit' value='Edytuj'/></p>
        </form>

        <hr>
          <h4>Menu:</h4>
          <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
              <tr>
                <th width="5%">ID</th>
                <th>Ikona</th>
                <th>Nazwa</th>
                <th>Link</th>
                <th></th>
              </tr>
              <?
              $mod_menu_q = all("SELECT * FROM  `acp_moduly_menu` WHERE `modul_id` = $edycja_id; ");
              foreach ($mod_menu_q as $mod_menu) { ?>
              <tr>
              <form name='edycja_from_menu' method='post' action='<? echo "?x=$x&id=$edycja_id&co=edytuj"; ?>'>
                <input type="hidden" name="e_n_id" value="<? echo $mod_menu->id ?>">
                <input type="hidden" name="e_n_idmodulu" value="<? echo $_GET["id"] ?>">
                <input type="hidden" name="e_n_nazamodulu" value="<? echo $edycja_mod->nazwa_wys ?>">
                <td><input type="text" class="form-control" type="text" value="<? echo $mod_menu->id ?>" disabled></td>
                <td><input type="text" class="form-control" type="text" name="e_n_ikona" value="<? echo $mod_menu->ikona ?>" ></td>
                <td><input type="text" class="form-control" type="text" name="e_n_nazwa" value="<? echo $mod_menu->nazwa ?>" ></td>
                <td><input type="text" class="form-control" type="text" name="e_n_link" value="<? echo $mod_menu->link ?>" ></td>
                <td>
                  <input name='edycja_from_menu_zapisz' type="submit" class="btn btn-default" value='Zapisz'>
                  <input name='edycja_from_menu_usun' type="submit" class="btn btn-danger" value='Usuń'>
                </td>
              </form>
              </tr>
              <? } ?>
              <tr>
              <form name='edycja_from_menu_add' method='post' action='<? echo "?x=$x&id=$edycja_id&co=edytuj"; ?>'>
                <td><input type="text" class="form-control" value="-" disabled></td>
                <input type="hidden" name="e_new_idmodulu" value="<? echo $_GET["id"] ?>">
                <input type="hidden" name="e_new_nazamodulu" value="<? echo $edycja_mod->nazwa_wys ?>">
                <td><input type="text" type="text" name="e_new_ikona" class="form-control"></td>
                <td><input type="text" type="text" name="e_new_nazwa" class="form-control"></td>
                <td><input type="text" type="text" name="e_new_link" class="form-control"></td>
                <td>
                  <input name='edycja_from_menu_add' type="submit" class="btn btn-default" value='Dodaj'>
                </td>
              </form>
              </tr>
            </table>
          </div>
          <hr>
        <hr>
          <h4>Uprawnienia:</h4>
          <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
              <tr>
                <th width="5%">ID</th>
                <th>Akcja (PHP)</th>
                <th>Akcja Nazwa</th>
                <th>Opis</th>
                <th></th>
              </tr>
              <?
              $akcje_q = all("SELECT * FROM  `acp_moduly_akcje` WHERE `modul_id` = $edycja_id; ");
              foreach ($akcje_q as $akcje) {
                if(empty($akcje->opis)) { $akcje->opis = 'brak opisu'; } ?>
              <tr>
              <form name='edycja_from_uprawnienia' method='post' action='<? echo "?x=$x&id=$edycja_id&co=edytuj"; ?>'>
                <input type="hidden" name="e_n_id" value="<? echo $akcje->id ?>">
                <input type="hidden" name="e_n_idmodulu" value="<? echo $_GET["id"] ?>">
                <input type="hidden" name="e_n_nazamodulu" value="<? echo $edycja_mod->nazwa_wys ?>">
                <td><input type="text" class="form-control" type="text" value="<? echo $akcje->id ?>" disabled></td>
                <td><input type="text" class="form-control" type="text" name="e_n_akcja" value="<? echo $akcje->akcja ?>" ></td>
                <td><input type="text" class="form-control" type="text" name="e_n_akcja_wys" value="<? echo $akcje->akcja_wys ?>" ></td>
                <td><input type="text" class="form-control" type="text" name="e_n_opis" value="<? echo $akcje->opis ?>" ></td>
                <td>
                  <input name='edycja_from_uprawnienia_zapisz' type="submit" class="btn btn-default" value='Zapisz'>
                  <input name='edycja_from_uprawnienia_usun' type="submit" class="btn btn-danger" value='Usuń'>
                </td>
              </form>
              </tr>
              <? } ?>
              <tr>
              <form name='edycja_from_uprawnienia_add' method='post' action='<? echo "?x=$x&id=$edycja_id&co=edytuj"; ?>'>
                <td><input type="text" class="form-control" value="-" disabled></td>
                <input type="hidden" name="e_new_idmodulu" value="<? echo $_GET["id"] ?>">
                <input type="hidden" name="e_new_nazamodulu" value="<? echo $edycja_mod->nazwa_wys ?>">
                <td><input type="text" type="text" name="e_new_akcja" class="form-control"></td>
                <td><input type="text" type="text" name="e_new_akcja_wys" class="form-control"></td>
                <td><input type="text" type="text" name="e_new_opis" class="form-control"></td>
                <td>
                  <input name='edycja_from_uprawnienia_add' type="submit" class="btn btn-default" value='Dodaj'>
                </td>
              </form>
              </tr>
            </table>
          </div>
          <hr>
      </div>
    </div>
  </div>
</div>

<?
}
?>

	<div class="row">
		<div class="col-xs-12">
			<div class="box box">
				<div class="box-header">
				  <h3 class="box-title">Moduły</h3>
				  <div class="pull-right box-tools">
				  </div>
				</div>
				<div class="box-body">
          <table data-page-length='10' id="example" class="table table-bordered table-striped" width="100%">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nazwa</th>
                <th>Nazwa Wyświetlana</th>
                <th>Opis</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?
              $moduly_q = all("SELECT * FROM  `acp_moduly`  ORDER BY `id` +0 ASC;");
              foreach($moduly_q as $moduly){
              ?>
              <tr class="odd gradeX">
                <td><? echo $moduly->id; ?></td>
                <td><? echo $moduly->nazwa; ?></td>
                <td><? echo $moduly->nazwa_wys; ?></td>
                <td><? echo $moduly->opis; ?></td>
                <td>
                  <a href="<? echo "?x=$x&id=$moduly->id&co=edytuj" ?>"><button type="button" class="btn btn-primary"><i class="fa fa-edit"></i></button></a>
                  <a href="<? echo "?x=$x&id=$moduly->id&co=usun" ?>"><button type="button" class="btn btn-danger"><i class="fa fa-times"></i></button></a>
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
    <!-- okno wyskakujace -->
    <div class="modal fade" id="dodaj">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Dodaj Moduł</h4>
          </div>
          <div class="modal-body">
            <form name='dodaj' method='post' action='?x=<? echo $_GET['x'] ?>'>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Nazwa (PHP)</span>
                  <input class="form-control" name="n_nazwa">
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Nazwa Wyświetlana</span>
                  <input class="form-control" name="n_nazwa_wys">
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Opis</span>
                  <input class="form-control" name="n_opis">
                </div>
              </p>
              <p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Zamknij</button>
              <button type="input" name="dodaj" class="btn btn-primary">Zapisz</button>
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
<?= js_table_one('#example2'); ?>
</body>
</html>
