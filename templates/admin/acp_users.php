<?
$func = getClass('Ustawienia');
$register = getClass('Register');
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
$edycja_id = (isset($_GET['edycja'])) ? (int)$_GET['edycja'] : null;
$password_id = (isset($_GET['password'])) ? (int)$_GET['password'] : null;
$delete_id = (isset($_GET['delete'])) ? (int)$_GET['delete'] : null;
$ban_id = (isset($_GET['ban'])) ? (int)$_GET['ban'] : null;

if(isset($_POST['edycja'])) {
  $func->edytuj_usera($_POST, $player->user);
	header("Location: ?x=$x&edycja=$edycja_id");
}
if(isset($_POST['dodaj'])) {
  $register->dodaj_usera($player->user);
  header("Location: ?x=$x");
}
if(!empty($password_id)) {
  $func->password_usera($password_id, $player->user);
  header("Location: ?x=$x");
}
if(!empty($ban_id)){
  $func->ban_usera($ban_id, $player->user);
  header("Location: ?x=$x");
}
if(!empty($delete_id)){
  $func->usun_usera($delete_id, $player->user);
  header("Location: ?x=$x");
}
?>
<?
if(!empty($edycja_id)){
  $acp_r_d = row("SELECT user, grupa, login,	email, steam, (SELECT `nazwa` FROM `acp_users_grupy` WHERE `id` = `grupa`) AS nazwa_grupy FROM `acp_users` WHERE `user` = $edycja_id; ");
?>
	<div class="row">
		<div class="col-lg-12">
			<div class="box box">
        <div class="box-header">
          <h3 class="box-title">Edycja użytykowanika #<?= $_GET['edycja'], " - ", $acp_r_d->login; ?></h3>
          <div class="pull-right box-tools">
          </div>
        </div>
        <div class="box-body">
          <form name='edycja_from' method='post' action='?x=acp_users&edycja=<?= $acp_r_d->user; ?>'>
            <input type='hidden' name='id' value='<?= $acp_r_d->user; ?>'>
            <p><div class='form-group input-group'><span class='input-group-addon'>Login</span><input class='form-control' type='text' name='e_login' value='<?= $acp_r_d->login; ?>'/></div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>Mail</span><input class='form-control' type='text' name='e_mail' value='<?= $acp_r_d->email; ?>'/></div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>STEAM 64</span><input class='form-control' type='text' name='e_steam' value='<?= $acp_r_d->steam; ?>'/></div></p>
            <div class='form-group input-group'><span class='input-group-addon'>Grupa</span>
              <select class="form-control" name="e_grupa">
               <option value="<?= $acp_r_d->grupa ?>"><?= $acp_r_d->nazwa_grupy ?></option>
    					 <? $grupy_list_q = all("SELECT * FROM `acp_users_grupy` WHERE `id` !=  $acp_r_d->grupa;");
    					 foreach($grupy_list_q as $grupy_list){ ?>
    					 	<option value="<?= $grupy_list->id ?>"><?= $grupy_list->nazwa ?></option>
    					 <? } ?>
  					 </select>
           </div>
           <p><input name='edycja' class='btn btn-primary btn-lg btn-block' type='submit' value='Edytuj'/></p>
          </form>
        </div>
			</div>
		</div>
	</div>
<? } ?>
	<div class="row">
		<div class="col-xs-12">
			<div class="box box">
				<div class="box-header">
				  <h3 class="box-title">Lista Użytkowników</h3>
				  <div class="pull-right box-tools">
				  </div>
				</div>
				<div class="box-body">
					<table data-page-length='50' id="example" class="table table-bordered table-striped" width="100%">
						<thead>
							<tr>
								<th>ID</th>
								<th></th>
								<th>Nick</th>
								<th>Grupa</th>
								<th>Departament</th>
								<th>Data Rejestracji</th>
								<th>Ostatnio Logowany</th>
								<th>STEAM</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
						<?
						$users_lista_q = all("SELECT *,
						(SELECT `nazwa` FROM `acp_users_grupy` WHERE `id` = `grupa` LIMIT 1) AS stanowisko,
						(SELECT `departament` FROM `acp_users_grupy` WHERE `id` = `grupa` LIMIT 1) AS departament_id,
						(SELECT `nazwa` FROM `acp_users_departament` WHERE `id` = `departament_id` LIMIT 1) AS departament
						FROM `acp_users` ORDER BY `user` +0 ASC;");
						foreach($users_lista_q as $users){
							if ($users->banned == 0) { $ban = " <button type='button' class='btn btn-danger btn-xs'>Zablokowany</button>"; } else { $ban = ""; }
						?>
							<tr class="odd gradeX">
								<td><?= $users->user; ?></td>
								<td><img src="<?= $users->steam_avatar ?>" width="25px" height="auto"></img></td>
								<td><a href="?x=account&id=<?= $users->user; ?>"><?= $users->login; ?></a> ( <?= $users->steam_login ?> ) <?= $ban; ?></td>
								<td><?= $users->stanowisko; ?></td>
								<td><?= $users->departament; ?></td>
								<td><?= $users->data_rejestracji; ?></td>
								<td><?= $users->last_login; ?></td>
								<td><a href="https://steamcommunity.com/profiles/<?= $users->steam; ?>" target="_blank"><?= $users->steam; ?></a></td>
                <td>
                  <div class="btn-group">
                    <a href="<?= "?x=$x&password=$users->user" ?>" class="btn btn-default" role="button" aria-pressed="true"><i class="glyphicon glyphicon-lock"></i> Hasło</a>
                    <a href="<?= "?x=$x&ban=$users->user" ?>" class="btn btn-default" role="button" aria-pressed="true"><i class="fa fa-ban"></i> Blokada</a>
                    <a href="<?= "?x=$x&edycja=$users->user" ?>" class="btn btn-primary" role="button" aria-pressed="true"><i class="fa fa-edit"></i> Edytuj</a>
                    <a href="<?= "?x=$x&delete=$users->user" ?>" class="btn btn-danger" role="button" aria-pressed="true"><i class="fa fa-times"></i> Usuń</a>
                  </div>
                </td>
							</tr>
						<? } ?>
						</tbody>
					</table>
				</div>
        <div class="box-footer clearfix no-border">
          <button type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#dodaj"><i class="fa fa-plus"></i> Dodaj Użytkonika</button>
        </div>
			</div>
		</div>
	</div>
  <div class="row">
    <div class="modal fade" id="dodaj">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Dodaj nowego użytkownika</h4>
          </div>
          <div class="modal-body">
            <form name='dodaj' method='post' action='?x=<?= $_GET['x'] ?>'>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Nick</span>
                  <input class="form-control" name="nick" placeholder="Login">
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Hasło</span>
                  <input class="form-control" name="haslo" placeholder="Hasło">
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>STEAM ID</span>
                  <input class="form-control" pattern="^STEAM_[01]:[01]:\d+$" placeholder="STEAM_X:X:XXXXXXXX" name="steam_id">
                </div>
              </p>
              <p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Zamknij</button>
              <button type="input" name="dodaj" class="btn btn-primary">Dodaj</button>
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
