
<?
$func = getClass('Logi');
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
$ss_acp_logi = $func->oddaj_zmienna_ss_logi_zdalne($_SESSION['ss_acp_logi']);
if($_GET['co'] == 'zdalny'){ $func->zmien_ss_logi_zdalne($ss_acp_logi);  header("Location: ?x=$x"); }
?>

	<div class="row">
		<div class="col-xs-12">
			<div class="box box">
        <div class="box-header with-border">
          <h3 class="box-title">ACP Logi</h3>

          <div class="box-tools pull-right">
            <div class="btn-group">
              <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown" aria-expanded="true"><i class="fa fa-wrench"></i></button>
              <ul class="dropdown-menu" role="menu">
                <?
                if($ss_acp_logi == 1) {
                  echo "<li><a href='?x=$x&co=zdalny'>Pokaż - Prace zdalne</a></li>";
                }
                else {
                  echo "<li><a href='?x=$x&co=zdalny'>Ukryj - Prace zdalne</a></li>";
                }
                ?>
              </ul>
            </div>
          </div>
        </div>
        <div class="box-body">
					<table data-page-length='50' id="example" class="table table-bordered table-striped" width="100%">
						<thead>
							<tr>
								<th>Data</th>
								<th>Użytkownik</th>
								<th>Log</th>
								<th>Moduł</th>
							</tr>
						</thead>
						<tbody>
						<?
            if($_SESSION['ss_acp_logi'] == 1) {
              $where = "WHERE `user` != 0";
            }
            else {
              $where = "";
            }

            $list_q = all("SELECT *,`user` AS `id_user`, (SELECT `login` FROM `acp_users` WHERE `user` = `id_user`) AS `nick` FROM `acp_log` $where");
						foreach($list_q as $list){
              if(empty($list->nick) || is_null($list->nick)) { $list->nick = 'Zdalna praca'; }

              $page = substr($list->page, 0, strpos($list->page, "&"));
              $page = str_replace("?x=", "", $page);
              $page = one("SELECT `nazwa_wys` FROM `acp_moduly` WHERE `nazwa` = '$page'; ");
              if(empty($page)) { $page = 'Brak'; }
              $list->tekst = ($list->link == '#') ? $list->tekst : "<a href='$list->link'>$list->tekst</a>";
						?>
							<tr class="odd gradeX">
								<td><? echo $list->data; ?></td>
								<td><a href="?x=account&id=<? echo $list->id_user; ?>"><? echo $list->nick; ?></a></td>
								<td><? echo $list->tekst; ?></td>
								<td><? echo $page; ?></td>
							</tr>
						<? } ?>
						</tbody>
					</table>
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
<!-- PACE -->
<script src="./www/bower_components/PACE/pace.min.js"></script>
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
