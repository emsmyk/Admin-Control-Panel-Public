<?
$liczba_nowych = one("SELECT COUNT(*) FROM `acp_messages` WHERE m_to = $player->user AND m_type = 1 AND `m_status` = 0");
$liczba_nowych = (empty($liczba_nowych)) ? '' : "<span class=\"label label-primary pull-right\">$liczba_nowych</span>";

function zakladka($nazwa, $nazwa2) {
	if($nazwa == $nazwa2) {
     echo "class=\"active\"";
	}
}

?>
<div class="col-md-3">
  <a href="?x=wiadomosci&xx=wiadomosc" class="btn btn-primary btn-block margin-bottom">Nowa Wiadomość</a>

  <div class="box box-solid">
    <div class="box-header with-border">
      <h3 class="box-title">Foldery</h3>

      <div class="box-tools">
        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
        </button>
      </div>
    </div>
    <div class="box-body no-padding">
      <ul class="nav nav-pills nav-stacked">
        <li <? zakladka($typ, 1);?>><a href="?x=wiadomosci&xx=skrzynka&type=1"><i class="fa fa-inbox"></i> Odebrane <? $liczba_nowych; ?></a></li>
        <li <? zakladka($typ, 2);?>><a href="?x=wiadomosci&xx=skrzynka&type=2"><i class="fa fa-envelope-o"></i> Wysłane</a></li>
        <li <? zakladka($typ, 3);?>><a href="?x=wiadomosci&xx=skrzynka&type=3"><i class="fa fa-file-text-o"></i> Robocze</a></li>
        <li <? zakladka($typ, 0);?>><a href="?x=wiadomosci&xx=skrzynka&type=0"><i class="fa fa-trash-o"></i> Kosz</a></li>
      </ul>
    </div>
  </div>
</div>
