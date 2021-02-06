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

?>

	<div class="row">
		<div class="col-lg-4">
			<div class="box box">
				<div class="box-header">
				  <h3 class="box-title">Dostęp</h3>
				  <div class="pull-right box-tools">
				  </div>
				</div>
				<div class="box-body table-responsive no-padding">
          <table class="table table-dark">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Mod</th>
                <th scope="col">Dostep</th>
                <th scope="col">RCON</th>
              </tr>
            </thead>
          <?
          $dostep = all("SELECT `serwer_id`, `cronjobs`, `mod`, `ftp_user`, `ftp_haslo`, `ftp_host`, `rcon` FROM `acp_serwery` ORDER BY `acp_serwery`.`istotnosc` ASC");
          foreach ($dostep as $value):
            $value->ftp_haslo_decode = encrypt_decrypt('decrypt', $value->ftp_haslo);
            $value->tekst_ftp = (empty($value->ftp_haslo_decode)) ? '<button type="button" class="btn btn-danger">Złe Hasło</button>' : '<button type="button" class="btn btn-success">OK</button>';

            $value->ftp_rcon_decode = encrypt_decrypt('decrypt', $value->rcon);
            $value->tekst_rcon = (empty($value->ftp_rcon_decode)) ? '<button type="button" class="btn btn-danger">Złe Hasło</button>' : '<button type="button" class="btn btn-success">OK</button>';
          ?>
            <tbody>
              <tr>
                <th scope="row"><?= $value->serwer_id ?></th>
                <td><?= $value->mod ?></td>
                <td><?= $value->tekst_ftp ?></td>
                <td><?= $value->tekst_rcon ?></td>
              </tr>
            </tbody>
          <? endforeach; ?>
          </table>
				</div>
			</div>
		</div>
		<div class="col-lg-8">
			<div class="box box">
				<div class="box-header">
				  <h3 class="box-title">Prace Zdalne</h3>
				  <div class="pull-right box-tools">
				  </div>
				</div>
        <div class="box-body table-responsive no-padding">
          <table class="table table-dark">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Mod</th>
                <th scope="col">Rangi</th>
                <th scope="col">Mapy</th>
                <th scope="col">Reklamy</th>
                <th scope="col">Bazy Danych</th>
                <th scope="col">Usługi</th>
                <th scope="col">Help Menu</th>
              </tr>
            </thead>
          <?
          foreach ($dostep as $value):
            if($value->cronjobs == 1):
              $value->prace = row("SELECT `reklamy`, `bazy`, `mapy`, `hextags`, `help_menu`, `uslugi` FROM `acp_serwery_cronjobs` WHERE `serwer` = $value->serwer_id LIMIT 1");
              $value->rangi = ($value->prace->hextags == 0) ? '<button type="button" class="btn btn-danger">OFF</button>' : '<button type="button" class="btn btn-success">ON</button>';
              $value->reklamy = ($value->prace->reklamy == 0) ? '<button type="button" class="btn btn-danger">OFF</button>' : '<button type="button" class="btn btn-success">ON</button>';
              $value->bazy = ($value->prace->bazy == 0) ? '<button type="button" class="btn btn-danger">OFF</button>' : '<button type="button" class="btn btn-success">ON</button>';
              $value->mapy = ($value->prace->mapy == 0) ? '<button type="button" class="btn btn-danger">OFF</button>' : '<button type="button" class="btn btn-success">ON</button>';
              $value->help_menu = ($value->prace->help_menu == 0) ? '<button type="button" class="btn btn-danger">OFF</button>' : '<button type="button" class="btn btn-success">ON</button>';
              $value->uslugi = ($value->prace->uslugi == 0) ? '<button type="button" class="btn btn-danger">OFF</button>' : '<button type="button" class="btn btn-success">ON</button>';
          ?>
            <tbody>
              <tr>
                <th scope="row"><?= $value->serwer_id ?></th>
                <td><?= $value->mod ?></td>
                <td><?= $value->rangi ?></td>
                <td><?= $value->mapy ?></td>
                <td><?= $value->reklamy ?></td>
                <td><?= $value->bazy ?></td>
                <td><?= $value->uslugi ?></td>
                <td><?= $value->help_menu ?></td>
              </tr>
            </tbody>
          <? else: ?>
          <tbody>
            <tr>
              <th scope="row"><?= $value->serwer_id ?></th>
              <td><?= $value->mod ?></td>
              <td class="text-center" colspan="6"><button type="button" class="btn btn-block btn-warning">Prace Zdalne zostały wyłączone całkowcie na tym serwerze..</button></td>
            </tr>
          </tbody>
          <? endif;
          endforeach;
          ?>
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
