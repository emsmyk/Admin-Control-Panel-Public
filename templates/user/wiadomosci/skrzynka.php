<?
tytul_strony("Wiadomości - Skrzynka");
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
$co = (isset($_GET['co'])) ? $_GET['co'] : null;
$strona = (isset($_GET['str'])) ? (int)$_GET['str'] : null;

if(!isset($typ)){ header('Location: ?x=wiadomosci&xx=skrzynka&type=1'); }

$dane = array(0 => "Kosz <br><small>Automatycznie usuwa wiadomości starsze niż ".$acp_system['cron_optym_stare_wiadomosci_day']." dni</small>", 1 => "Skrzynka Odbiorcza", 2 => "Skrzynka Nadawcza", 3 => "Kopie Robocze");

$limit =  20; //Liczba wpisów na jednej stronie
if (!isset($strona)) {
    $limit1 = 0;
    $limit2 = $limit;
	$nr_kolejnej_strony = 2;
} else {
    $limit1 = $limit * $strona - $limit;
    $limit2 = $limit;
	$nr_kolejnej_strony = $strona + 1;
	$nr_wczesniejszej_strony = $strona - 1;
}

//liczba wiadomosci strony
switch($typ){
	//kosz
	case 0:
		$liczba_wiadomosci = one("SELECT COUNT(*) FROM `acp_messages` WHERE m_type = $typ AND `m_czyja` = $player->user ORDER BY m_status ASC, m_id DESC;");
		$wiadomosci_q = all("SELECT * FROM `acp_messages` WHERE m_type = $typ AND `m_czyja` = $player->user ORDER BY m_status ASC, m_id DESC LIMIT $limit1, $limit2;");
	break;
	//odebrane
	case 1:
		$liczba_wiadomosci = one("SELECT COUNT(*) FROM `acp_messages` WHERE m_to = $player->user AND m_type = $typ ORDER BY m_status ASC, m_id DESC;");
		$wiadomosci_q = all("SELECT *, (SELECT `login` FROM `acp_users` WHERE `user` = `m_from`) AS login FROM `acp_messages` WHERE m_to = $player->user AND m_type = $typ ORDER BY m_status ASC, m_id DESC LIMIT $limit1, $limit2;");
	break;
	//wyslane
	case 2:
		$liczba_wiadomosci = one("SELECT COUNT(*) FROM `acp_messages` WHERE m_from = $player->user AND m_type = $typ ORDER BY m_status ASC, m_id DESC;");
		$wiadomosci_q = all("SELECT *, (SELECT `login` FROM `acp_users` WHERE `user` = `m_to`) AS login FROM `acp_messages` WHERE m_from = $player->user AND m_type = $typ ORDER BY m_status ASC, m_id DESC LIMIT $limit1, $limit2;");
	break;
	//robocze
	case 3:
		$liczba_wiadomosci = one("SELECT COUNT(*) FROM `acp_messages` WHERE `m_type` = $typ AND `m_czyja` = $player->user ORDER BY `m_status` ASC, `m_id` DESC;");
		$wiadomosci_q = all("SELECT *, (SELECT `login` FROM `acp_users` WHERE `user` = `m_to`) AS login FROM `acp_messages` WHERE `m_type` = $typ AND `m_czyja` = $player->user ORDER BY `m_status` ASC, `m_id` DESC LIMIT $limit1, $limit2;");
	break;
}
?>
  <div class="row">
    <? require_once("./templates/user/wiadomosci/menu.php");  ?>

  	<div class="col-md-9">
  	  <div class="box box-primary">
  			<div class="box-header with-border">
  			  <h3 class="box-title"><?= $dane[$typ] ?></h3>
  			</div>
  			<div class="box-body no-padding">
  			  <div class="mailbox-controls">
            <div class="btn-group">
              <a href="?x=wiadomosci&xx=skrzynka&type=<?= $typ ?>"><button type="button" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button></a>
    				</div>
    				<div class="pull-right">
    				  <?= $limit1 ?>-<?= $limit2 ?>/<?= $liczba_wiadomosci; ?>
    				  <div class="btn-group">
      					<a href="?x=wiadomosci&xx=skrzynka&type=<?= $typ ?>&str=<?= $nr_wczesniejszej_strony; ?>"><button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></button></a>
      					<a href="?x=wiadomosci&xx=skrzynka&type=<?= $typ ?>&str=<?= $nr_kolejnej_strony; ?>"><button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></button></a>
    				  </div>
    				</div>
  			  </div>
  			  <div class="table-responsive mailbox-messages">
    				<table class="table table-hover table-striped">
    				  <tbody>
                <?
                if(!empty($wiadomosci_q)):
                  foreach($wiadomosci_q as $wiadomosci){
                    if($wiadomosci->m_tytul == '') { $wiadomosci->m_tytul = 'Bez Tytułu'; }
                    if($typ == 3) { $link = "?x=wiadomosci&xx=wiadomosc&type=$typ&id=$wiadomosci->m_id&co=update"; } else { $link = "?x=wiadomosci&xx=czytaj&type=$typ&id=$wiadomosci->m_id"; }
                    $wiadomosci->login = (empty($wiadomosci->login)) ? 'brak loginu' : $wiadomosci->login;
                    //odczytane lub nie odczytane w ak
                    $wiadomosci->m_status = (empty($wiadomosci->m_status)) ? 0 : $wiadomosci->m_status;
                    $wiadomosci->m_statu_ikona = ($wiadomosci->m_status === 0) ? '<i class="fa fa-star text-yellow"></i>' : '<i class="fa fa-star-o text-yellow"></i>';
                ?>
                  <tr>
                   <? if($typ == 1) {?><td class="mailbox-star"><?= $wiadomosci->m_statu_ikona ?></td><? } ?>
                   <td class="mailbox-name"><a href="<?= $link; ?>"><?= $wiadomosci->login; ?></a></td>
                   <td class="mailbox-subject"><b><?= $wiadomosci->m_tytul; ?></b> - <?= substr(strip_tags($wiadomosci->m_text), 0, 30); ?>..</td>
                   <td class="mailbox-date"><?= czas_relatywny($wiadomosci->m_date); ?></td>
                  </tr>
                <? }
                else:?>
                  <tr>
                    <td colspan="x">
                      Brak wiadomości..
                    </td>
                  </tr>
                <? endif;?>
    				  </tbody>
    				</table>
  			  </div>
  			</div>
  			<div class="box-footer no-padding">
  			  <div class="mailbox-controls">
    				<div class="btn-group">
              <a href="?x=wiadomosci&xx=skrzynka&type=<?= $typ ?>"><button type="button" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button></a>
    				</div>
    				<div class="pull-right">
    				  <?= $limit1 ?>-<?= $limit2 ?>/<?= $liczba_wiadomosci; ?>
    				  <div class="btn-group">
      					<a href="?x=wiadomosci&xx=skrzynka&type=<?= $typ ?>&str=<?= $nr_wczesniejszej_strony; ?>"><button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></button></a>
      					<a href="?x=wiadomosci&xx=skrzynka&type=<?= $typ ?>&str=<?= $nr_kolejnej_strony; ?>"><button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></button></a>
    				  </div>
    				</div>
  			  </div>
  			</div>
        </from>
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
