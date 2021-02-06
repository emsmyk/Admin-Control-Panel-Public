<?
tytul_strony("Wiadomości - ");
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
$co = (isset($_GET['co'])) ? $_GET['co'] : null;

if($co == 'usun') {
	$mess->msg_del($player->user, $typ, $id);
  header("Location: ?x=$x&xx=skrzynka&type=$typ");
}
if($co == 'kosz') {
	$mess->msg_kosz($player->user, $typ, $id);
  header("Location: ?x=$x&xx=skrzynka&type=$typ");
}

$mess->msg_read($player->user, $id);
?>
  <div class="row">
    <? require_once("./templates/user/wiadomosci/menu.php");  ?>

  	<?
  		switch($typ){
  			case 0:
  				$wiadomosc_q = all("SELECT * FROM `acp_messages` WHERE `m_id` = $id AND m_type = $typ AND `m_czyja` = $player->user;");
  			break;
  			case 1:
  				$wiadomosc_q = all("SELECT *, (SELECT `login` FROM `acp_users` WHERE `user` = `m_from`) AS login FROM `acp_messages` WHERE `m_id` = $id AND m_to = $player->user AND m_type = $typ;");
  			break;
  			case 2:
  				$wiadomosc_q = all("SELECT *, (SELECT `login` FROM `acp_users` WHERE `user` = `m_to`) AS login FROM `acp_messages` WHERE `m_id` = $id AND m_from = $player->user AND m_type = $typ;");
  			break;
  		}
    ?>


    <?
    if(!empty($wiadomosc_q)):
      foreach($wiadomosc_q as $wiadomosc){
        if($typ == 0) {
          if($wiadomosc->m_czyja == $wiadomosc->m_form) {
            $wiadomosc->login = one("SELECT `login` FROM `acp_users` WHERE `user` = $wiadomosc->m_to");
          }
          else if($wiadomosc->m_czyja == $wiadomosc->m_to) {
            $wiadomosc->login = one("SELECT `login` FROM `acp_users` WHERE `user` = $wiadomosc->m_from");
          }
        }
    ?>
      <div class="col-md-9">
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">Wiadomość</h3>
          </div>
          <div class="box-body no-padding">
            <div class="mailbox-read-info">
              <h3>Temat: <?= $wiadomosc->m_tytul; ?></h3>
              <h5>Od: <?= $wiadomosc->login; ?>
              <span class="mailbox-read-time pull-right"><?= czas_relatywny($wiadomosc->m_date); ?></span></h5>
            </div>
            <div class="mailbox-read-message">
              <?= $wiadomosc->m_text; ?>
            </div>
          </div>
          <div class="box-footer">
            <div class="pull-right">
              <a href="?x=wiadomosci&xx=wiadomosc&to=<?= $wiadomosc->login; ?>&tytul=Re: <?= $wiadomosc->m_tytul; ?>"><button type="button" class="btn btn-default"><i class="fa fa-reply"></i> Odpowiedz</button></a>
            </div>
            <a href="?x=wiadomosci&xx=czytaj&type=<?= $typ; ?>&id=<?= $id; ?>&co=usun"><button type="button" class="btn btn-default"><i class="fa fa-close"></i> Usuń</button></a>
            <? if($typ != 0) {?>
              <a href="?x=wiadomosci&xx=czytaj&type=<?= $typ; ?>&id=<?= $id; ?>&co=kosz"><button type="button" class="btn btn-default"><i class="fa fa-trash-o"></i> Kosz</button></a>
            <? } ?>
          </div>
        </div>
      </div>
    <? } else: ?>
      <div class="col-md-9">
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">Błąd!</h3>
          </div>
          <div class="box-body">
            <p>Wiadomość nie istnieje..</p>
          </div>
        </div>
      </div>
    <? endif;?>
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
