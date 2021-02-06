<?
tytul_strony("Zadania: Dodaj Zadanie");
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
if(isset($_POST['nowe'])){
  $func->nowe_zadanie($_POST['nowe'], $player->user, $dostep->ZadaniaDodaj);
  header("Location: ?x=$x&xx=$xx");
}
?>

	<div class="row">
		<div class="col-xs-12">
			<div class="box box">
        <div class="box-header">
				  <h3 class="box-title">Dodaj Zadanie</h3>
				</div>
        <form class="form-horizontal" name="nowe" method="post">
          <div class="box-body">
            <div class="col-xs-12">
              <div class="form-group">
                <label>Platforma</label>
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
              <div class="form-group">
                <label>Serwer</label>
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
              <div class="form-group">
                <label>Typ</label>
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
              <div class="form-group">
                <label>Temat</label>
                <input class="form-control" name="temat" type="text" placeholder="Temat zadania">
              </div>
              <div class="form-group">
                <label>Opis</label>
                <textarea class="form-control" rows="5" name="opis"></textarea>
              </div>
            </div>
          </div>
          <div class="box-footer">
            <button type="input" name="nowe" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Dodaj Zadanie</button>
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
<?= js_table_defaults(); ?>
<?= js_table_one('#example', 'desc'); ?>
</body>
</html>
