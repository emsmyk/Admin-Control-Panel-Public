<div class="content-wrapper">
 <section class="content">
	<div class="row">
		<div class="col-lg-12">
			<p> <? if(!empty($_SESSION['msg'])){ echo $_SESSION['msg']; unset($_SESSION['msg']); } ?> </p>
		</div>
	</div>
<?
if(!empty($_GET['co']) && !empty($_GET['id'])) {
	if($_GET['co'] == "usun") {
		query("DELETE FROM `acp_users_notification` WHERE `id` = '".$_GET['id']."' AND `u_id`= '".$player->user."';");
		$_SESSION['msg'] = komunikaty("Powiadomienie zostało usunięte", 1);
	}
	else if($_GET['co'] == "read") {
		query("UPDATE `acp_users_notification` SET `read` = '0' WHERE `id` = '".$_GET['id']."' AND `u_id`= '".$player->user."';");
		$_SESSION['msg'] = komunikaty("Powiadomienie zostało oznaczone jako przeczytane", 1);
	}
	header('Location: ?x=powiadomienia');
}
if(!empty($_GET['co'])) {
	if($_GET['co'] == "odczytane_all") {
		query("UPDATE `acp_users_notification` SET `read` = '0' WHERE `u_id`= '".$player->user."';");
		$_SESSION['msg'] = komunikaty("Wszystkie powiadomienia zostały odczytane", 1);
	}
	else if($_GET['co'] == "usun_all") {
		query("DELETE FROM `acp_users_notification` WHERE `u_id`= '".$player->user."';");
		$_SESSION['msg'] = komunikaty("Usunięto wszystkie powiadomienia", 1);
	}
	header('Location: ?x=powiadomienia');
}
?>
  <div class="row">
	<div class="col-lg-12">
	<div class="box">
	 <div class="box-header with-border"><h3 class="box-title">Powiadomienia</h3></div>
	 <div class="box-body">
	  <table id="example" class="table table-bordered table-striped" width="100%">
		<thead>
			<tr>
				<th width="1%"></th>
				<th></th>
				<th></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		<?
		$powiadomienia_lista = all("SELECT * FROM `acp_users_notification` WHERE `u_id` = '".$player->user."' ORDER BY `data` DESC;");
		foreach($powiadomienia_lista as $powiadomienia){
      $powiadomienia->icon_kolor = ($powiadomienia->read==1) ? 'text-aqua' : '';
		?>
			<tr class="odd gradeX">
				<td><i class="<?= $powiadomienia->icon.' '.$powiadomienia->icon_kolor ?>"></i></td>
				<td>
				<?= $new; ?>
				<a href="<?= $powiadomienia->link ?>&powiadomienie_id=<?= $powiadomienia->id ?>"><?= $powiadomienia->text; ?></a></td>
				<td><?= czas_relatywny($powiadomienia->data); ?></td>
				<td>
					<div class="btn-group">
					  <button type="button" class="btn btn-default" onclick="window.location.href='<?= $powiadomienia->link ?>&powiadomienie_id=<?= $powiadomienia->id ?>'">Otwórz</button>
					  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
						<span class="caret"></span>
						<span class="sr-only">Toggle Dropdown</span>
					  </button>
					  <ul class="dropdown-menu" role="menu">
						<li><a target="_blank" href="<?= $powiadomienia->link ?>&powiadomienie_id=<?= $powiadomienia->id ?>">Nowa Karta</a></li>
						<li class="divider"></li>
						<li><a href="?x=powiadomienia&co=read&id=<?= $powiadomienia->id ?>">Odczytane</a></li>
						<li><a href="?x=powiadomienia&co=usun&id=<?= $powiadomienia->id ?>">Usuń</a></li>
					  </ul>
					</div>
				</td>
			</tr>
		<? } ?>
		</tbody>
	  </table>
	 </div>
	 <div class="box-footer">
	  <div class="pull-right">
	   <button type="submit" class="btn btn-default" onclick="window.location.href='?x=powiadomienia&co=odczytane_all'"><i class="fa fa fa-bell"></i> Odczytaj Wszystkie</button>
	   <button type="submit" class="btn btn-danger" onclick="window.location.href='?x=powiadomienia&co=usun_all'"><i class="fa fa-times"></i> Usuń Wszystkie</button>
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
