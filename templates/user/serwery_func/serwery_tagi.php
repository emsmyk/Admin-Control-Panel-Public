<? $func = getClass('SerwerKonfiguracja'); ?>
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
$id = (isset($_GET['id'])) ? (int)$_GET['id'] : null;
$co = (isset($_GET['co'])) ? $_GET['co'] : null;
$edycja_id = (isset($_GET['edycja'])) ? (int)$_GET['edycja'] : null;

if(!empty( $co) && !empty($id) &&  $co == "usun"){
  $func->tagi_usun($id, $player->user, $dostep->SerwerReklamyUsun);
	header("Location: ?x=$x&xx=$xx");
}
if(isset($_POST['edycja_from'])) {
  $func->tagi_edytuj($player->user, $dostep->SerwerReklamyEdytuj);
	header("Location: ?x=$x&xx=$xx&edycja=$edycja_id");
}
if(isset($_POST['nowy_rekord'])) {
  $func->tagi_dodaj($player->user, $dostep->SerwerReklamyDodaj);
  header("Location: ?x=$x&xx=$xx");
}

$serwer_array = array(0 => 'Wszystkie');
$serwer_array_q = all("SELECT `serwer_id`, `mod`, `nazwa` FROM `acp_serwery`");
foreach($serwer_array_q as $serwer_array_dane){
  $serwer_array[$serwer_array_dane->serwer_id]="$serwer_array_dane->nazwa";
}

if(!empty($edycja_id)){
?>
  <div class="row">
    <div class="col-xs-12">
      <div class="box box">
        <div class="box-header">
          <h3 class="box-title">Edycja</h3>
          <div class="pull-right box-tools">
          </div>
        </div>
        <div class="box-body">
          <form method='post'>
            <?
            $acp_r_d = row("SELECT * FROM `acp_serwery_tagi` WHERE `id` = $edycja_id LIMIT 1;");
            $acp_r_d->staly = ($acp_r_d->staly == 1) ? 'checked' : '';
            ?>
            <input type='hidden' name='id' value='<?= $acp_r_d->id ?>'>
            <div class="col-xs-12">
              <p><div class='form-group input-group'><span class='input-group-addon'>Serwer</span>
                <select class="form-control" name="serwer">
                  <?
                  echo '<option value="'.$acp_r_d->serwer.'">'.$serwer_array[$acp_r_d->serwer].'</option>';
                  foreach ($serwer_array as $key => $value):
                    if($acp_r_d->serwer != $key)
                    echo '<option value="'.$key.'">'.$value.'</option>';
                  endforeach;
                  ?>
                </select>
              </div></p>
              <p><div class='form-group input-group'><span class='input-group-addon'>Tag</span><input class='form-control' type='text' name='tag' value='<?= $acp_r_d->tekst ?>'/></div></p>
              <p>
                <div class="checkbox">
                  <label>
                    <input type="checkbox" name="staly" <?= $acp_r_d->staly ?>> Tag stały (Jest za każdym razem)
                  </label>
                </div>
              </p>
            </div>
            <div class="col-xs-12">
              <p><input name='edycja_from' class='btn btn-primary btn btn-block' type='submit' value='Edytuj'/></p>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
<?
}
?>
	<div class="row">
		<div class="col-lg-8">
			<div class="box box">
				<div class="box-header">
				  <h3 class="box-title">Lista Tagów</h3>
				  <div class="pull-right box-tools">
				  </div>
				</div>
				<div class="box-body">
          <table data-page-length='10' id="example" class="table table-bordered table-striped" width="100%">
						<thead>
							<tr>
                <th>ID</th>
								<th>Serwer</th>
								<th>Tag</th>
								<th>Stały</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
						<?
            $acp_wyswietl_all_q = all("SELECT *, (SELECT `mod` FROM `acp_serwery` WHERE `serwer_id` = `serwer`) AS `serwer_nazwa` FROM `acp_serwery_tagi`");
  					foreach($acp_wyswietl_all_q as $acp_r){
              $acp_r->serwer_nazwa = ($acp_r->serwer==0) ? 'Wszystkie' : $acp_r->serwer_nazwa ;
              $acp_r->serwer_nazwa = (empty($acp_r->serwer_nazwa)) ? '<i>Serwer nie istnieje</i>' : $acp_r->serwer_nazwa;
              $acp_r->staly = ($acp_r->staly == 1) ? 'Tak' : 'Nie';
						?>
            <tr class="odd gradeX">
              <td><?= $acp_r->id ?></td>
							<td><?= $acp_r->serwer_nazwa ?></td>
							<td><?= $acp_r->tekst ?></td>
							<td><?= $acp_r->staly ?></td>
              <td>
                <div class="btn-group">
                  <a href="<?= "?x=$x&xx=$xx&edycja=$acp_r->id" ?>" class="btn btn-primary" role="button" aria-pressed="true"><i class="fa fa-edit"></i> Edytuj</a>
                  <a href="<?= "?x=$x&xx=$xx&co=usun&id=$acp_r->id" ?>" class="btn btn-danger" role="button" aria-pressed="true"><i class="fa fa-times"></i> Usuń</a>
                </div>
              </td>
						</tr>
						<? } ?>
						</tbody>
					</table>
				</div>
        <div class="box-footer clearfix no-border">
          <button type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#dodaj-serwer"><i class="fa fa-plus"></i> Dodaj</button>
        </div>
			</div>
		</div>
    <?
    $serwery_aktualizowane = $func->serwery_aktualizowane();
    ?>
    <div class="col-lg-4 col-xs-12">
      <div class="small-box bg-blue">
        <div class="inner">
          <?= $serwery_aktualizowane; ?>
        </div>
        <div class="icon">
          <i class="fa fa-server"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="modal fade" id="dodaj-serwer">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Dodaj</h4>
          </div>
          <div class="modal-body">
            <form method='post'>
              <p><div class='form-group input-group'><span class='input-group-addon'>Serwer</span>
                <select class="form-control" name="serwer">
                  <?
                  foreach ($serwer_array as $key => $value):
                    echo '<option value="'.$key.'">'.$value.'</option>';
                  endforeach;
                  ?>
                </select>
              </div></p>
              <p><div class='form-group input-group'><span class='input-group-addon'>Tag</span><input class='form-control' type='text' name='tag'/></div></p>
              <p>
                <div class="checkbox">
                  <label>
                    <input type="checkbox" name="staly"> Tag stały (Jest za każdym razem)
                  </label>
                </div>
              </p>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Zamknij</button>
              <button type="input" name="nowy_rekord" class="btn btn-primary">Zapisz</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?
  // echo '/TESTY';
  // $serwery_q = all("SELECT `serwer_id`, `game`, `prefix_sb`, `ip`, `port`, `nazwa`, `status`, `status_data`, `rcon` FROM `acp_serwery` WHERE `ip` != '' AND `port` != ''; ");
  // foreach($serwery_q as $serwery){
  //   $serwery->rcon_dec = encrypt_decrypt('decrypt', $serwery->rcon);
  //   if(!empty($serwery->rcon_dec)){
  //     $list_tags = all("SELECT * FROM `acp_serwery_tagi` WHERE `serwer` IN (0, $serwery->serwer_id) ");
  //     $sv_tags = 'sv_tags "! !,';
  //     foreach ($list_tags as $key => $value) {
  //       if($value->staly == 0){
  //         $value->losowa = rand(0, 1);
  //         if($value->losowa == 0){
  //           $sv_tags .= "$value->tekst,";
  //         }
  //       }
  //       else {
  //         $sv_tags .= "$value->tekst,";
  //       }
  //     }
  //     $sv_tags .= '"';
  //     $serwery->sv_tags = $sv_tags;
  //   }
  // }

  ?>
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
