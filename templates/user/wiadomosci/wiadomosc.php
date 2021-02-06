<?
tytul_strony("Wiadomości - Nowa");
$mess = getClass('Msg');
?>
<div class="content-wrapper">
<section class="content">
  <div class="row">
	<section class="col-lg-12">
		<p><? if(!empty($_SESSION['msg'])){ echo $_SESSION['msg']; unset($_SESSION['msg']); } ?></p>
	</section >
  </div>
<?php
$typ = (isset($_GET['type'])) ? (int)$_GET['type'] : null;
$id = (isset($_GET['id'])) ? (int)$_GET['id'] : null;
$tytul = (isset($_GET['tytul'])) ? $_GET['tytul'] : null;
$to = (isset($_GET['to'])) ? $_GET['to'] : null;
$co = (isset($_GET['co'])) ? $_GET['co'] : null;

if(isset($_POST['nowa_wiadomosc'])) {
	$mess->wyslij_wiadomosc($player->user, $_POST['to'], $_POST['tytul'], $_POST['text']);
	header("Location: ?x=wiadomosci&xx=skrzynka&type=$typ");
}
if(isset($_POST['odrzuc_wiadomosc'])) {
	$mess->odrzuc_wiadomosc($player->user, $_POST['id']);
  header("Location: ?x=wiadomosci&xx=skrzynka&type=$typ");
}
if(isset($_POST['zapisz_wiadomosc'])) {
	$mess->zapisz_wiadomosc($player->user, $_POST['to'], $_POST['tytul'], $_POST['text']);
  header("Location: ?x=wiadomosci&xx=skrzynka&type=$typ");
}
if(isset($_POST['zapisz_wiadomosc_update'])) {
	$mess->zapisz_wiadomosc_update($player->user, $_POST['to'], $_POST['tytul'], $_POST['text'], $_POST['id']);
	header("Location: ?x=wiadomosci&xx=wiadomosc&type=$typ&id=".$_POST['id']."&co=update");
}
?>
  <div class="row">
    <? require_once("./templates/user/wiadomosci/menu.php");  ?>

		<?
		if(!empty($typ) && $typ == 3) {
			$wiadomosc_q = all("SELECT `m_id`, `m_tytul`, `m_text`, `m_to` FROM `acp_messages` WHERE `m_id` = $id AND `m_type` = 3 AND `m_czyja` = $player->user LIMIT 1;");
			foreach($wiadomosc_q as $wiadomosc){
        if($wiadomosc->m_to === 0){
          $wiadomosc->m_to = null;
        }
        else{
          $wiadomosc->m_to = one("SELECT `login` FROM `acp_users` WHERE `user` = $wiadomosc->m_to");
        }
        $wiadomosc->m_text = (empty($wiadomosc->m_text)) ? null : $wiadomosc->m_text;
			}
		}
    elseif(empty($typ)) {
      $wiadomosc = new \stdClass;
      $wiadomosc->m_to = null;
      $wiadomosc->m_tytul = null;
      $wiadomosc->m_text = null;
    }
		?>
		<div class="col-md-9">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Nowa Wiadomość</h3>
        </div>
        <form method='post'>
        <div class="box-body">
    			<input type='hidden' name='id' value='<?= $wiadomosc->m_id; ?>'>
          <div class="form-group">
            <input class="form-control" name='to' placeholder="To:" value="<?= $wiadomosc->m_to; ?><?= $to; ?>">
          </div>
          <div class="form-group">
            <input class="form-control" name='tytul' placeholder="Subject:" value="<?= $wiadomosc->m_tytul ?><?= $tytul;?>">
          </div>
          <div class="form-group">
    				<textarea name="text" id="compose-textarea" class="form-control" style="height: 300px">
    					<?= $wiadomosc->m_text; ?>
    				</textarea>
          </div>
        </div>
        <div class="box-footer">
          <div class="pull-right">
    			  <? if($co == 'update') {?>
              <button type="submit" name="odrzuc_wiadomosc" value="aktualizuj" class="btn btn-default"><i class="fa fa-times"></i> Odrzuć</button>
              <button type="submit" name="zapisz_wiadomosc_update" value="aktualizuj" class="btn btn-default"><i class="fa fa-pencil"></i> Aktualizuj</button>
	          <? } else {?>
              <button type="submit" name="zapisz_wiadomosc" value="zapisz" class="btn btn-default"><i class="fa fa-pencil"></i> Zapisz</button>
	           <? } ?>
		      <input name='nowa_wiadomosc' class='btn btn-primary' type='submit' value='Wyślij'/>
          </div>
          <button type="reset" class="btn btn-default"><i class="fa fa-times"></i> Wyczyść</button>
        </div>
	      </form>
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
<!-- Slimscroll -->
<script src="./www/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="./www/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="./www/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="./www/dist/js/demo.js"></script>
<!-- iCheck -->
<script src="./www/plugins/iCheck/icheck.min.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="./www/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Page Script -->
<script>
  $(function () {
    $("#compose-textarea").wysihtml5();
  });
</script>
