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
require __DIR__ . './../../functions/SourceQuery/bootstrap.php';
use xPaw\SourceQuery\SourceQuery;

$id = (isset($_GET['serwer'])) ? (int)$_GET['serwer'] : null;
$serwer = row("SELECT `serwer_id`, `serwer_on`, `mod`, `nazwa`, `ip`, `port`, `rcon` FROM `acp_serwery` WHERE `serwer_id` = $id LIMIT 1");
$serwer->rcon_dec = encrypt_decrypt('decrypt', $serwer->rcon);
if($id == null || $id == 0){
  $_SESSION['msg'] = komunikaty("Brak wybranego serwera..", 3);
  header("Location: ?x=wpisy");
}
elseif(empty($serwer->ip)){
  $_SESSION['msg'] = komunikaty("Wybrany serwer nie istnieje", 3);
  header("Location: ?x=wpisy");
}
elseif(empty($serwer->rcon_dec)){
  $_SESSION['msg'] = komunikaty("Nie byliśmy w stanie rozkodować hasła rcon. Możliwe że nie zostało dodane..", 3);
  header("Location: ?x=wpisy");
}

if(isset($_POST['komenda'])){
  admin_log($player->user, "[RCON] Wysłano komendę: ".$_POST['text']." Serwer: $serwer->mod (ID: $id)");
}


define( 'SQ_SERVER_ADDR', $serwer->ip );
define( 'SQ_SERVER_PORT', $serwer->port );
define( 'SQ_TIMEOUT',     1 );
define( 'SQ_ENGINE',      SourceQuery::SOURCE );

$Query = new SourceQuery( );
?>

	<div class="row">
		<div class="col-lg-12">
			<div class="box box">
        <div class="box-header">
          <i class="fa fa-terminal fa-fw"></i>
				  <h3 class="box-title">Konsola Serwera</h3> <br>
          <small><?= $serwer->mod ?> | <?= $serwer->nazwa ?></small>
          <div class="box-tools pull-right">
            <a href="?x=serwery_det&serwer_id=<?= $serwer->serwer_id ?>" class="btn btn-box-tool"><i class="fa fa-reply"></i></a>
          </div>
				</div>
				<div class="box-body">
          <pre><?
            if(!isset($_POST['komenda'])){
              echo 'Wpisz komendę.. A my wyświetlimy odpowiedź serwera.';
            }
            else {
              try
              {
              	$Query->Connect( SQ_SERVER_ADDR, SQ_SERVER_PORT, SQ_TIMEOUT, SQ_ENGINE );
              	$Query->SetRconPassword($serwer->rcon_dec);
              	print_r( $Query->Rcon( $_POST['text'] ) );
              }
              catch( Exception $e )
              {
              	echo $e->getMessage();
              }
              finally
              {
              	$Query->Disconnect( );
              }
            }
            ?></pre>
					<form method="post">
            <div class="input-group">
              <input class="form-control" placeholder="Wpisz komendę..." type="tekst" name="text">
              <div class="input-group-btn">
                <button type="input" name="komenda" class="btn btn-success"><i class="fa fa-plus"></i></button>
              </div>
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
</body>
</html>
