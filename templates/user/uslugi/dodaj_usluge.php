<?
tytul_strony("Usługi: Dodaj Usługę");
$func = getClass('Uslugi');
?>
<div class="content-wrapper">
<section class="content">
  <div class="row">
	<section class="col-lg-12">
		<p><? if(!empty($_SESSION['msg'])){ echo $_SESSION['msg']; unset($_SESSION['msg']); } ?></p>
	</section >
  </div>
<?
if(isset($_POST['nowe'])){
  $func->admin_dodaj_usluge($player->user, $dostep->ZadaniaDodaj);
  header("Location: ?x=$x&xx=$xx");
}
?>
	<div class="row">
		<div class="col-xs-12">
			<div class="box box">
        <div class="box-header">
				  <h3 class="box-title">Dodaj Usługę</h3>
				  <div class="pull-right box-tools">
				  </div>
				</div>
        <form class="form-horizontal" method="post">
          <div class="box-body">
            <div class="col-xs-12">
              <div class="form-group">
                <label>Usługa</label>
                <select class="form-control" name="rodzaj_uslugi">
                  <?
                  $list = all("SELECT `id`, `nazwa` FROM `acp_uslugi_rodzaje`");
                  foreach($list as $value){
                  ?>
                    <option value="<?= $value->id ?>"><?= $value->nazwa ?></option>
                  <? } ?>
                </select>
              </div>
              <div class="form-group">
                <label>Serwer</label>
                <select class="form-control" name="serwer">
                  <option value="0">Wszystkie</option>
                  <?
                  $serwery_q = all("SELECT `serwer_id`, `nazwa`, `mod` FROM `acp_serwery`");
                  foreach($serwery_q as $serwery){
                  ?>
                    <option value="<?= $serwery->serwer_id ?>"><?= $serwery->nazwa ?> (<?= $serwery->mod ?>)</option>
                  <? } ?>
                </select>
              </div>
              <div class="form-group">
                <label>Steam ID</label>
                <input class="form-control" name="steam" type="text" placeholder="STEAM_0:0:00">
              </div>
              <div class="form-group">
                <label>Ile dni</label>
                <input class="form-control" name="dni" type="number" placeholder="30">
              </div>
            </div>
          </div>
          <div class="box-footer">
            <button type="input" name="nowe" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Dodaj Usługę</button>
          </div>
        </form>

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
