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
  $serwer = (!empty($_GET['serwer'])) ? (int)$_GET['serwer'] : null;
  $serwer_detale = row("SELECT `nazwa`, `mod` FROM `acp_serwery` WHERE `serwer_id` = $serwer LIMIT 1 ");
  ?>

  <? if(empty($serwer)): ?>
	<div class="row">
		<div class="col-xs-12">
			<div class="box box">
				<div class="box-header">
				  <h3 class="box-title">Wybierz Serwer</h3>
				</div>
				<div class="box-body">
          <ul class="list-group">
            <?
            $lista_serwerow = all("SELECT `serwer_id`, `nazwa`, `mod` FROM `acp_serwery`");
            foreach ($lista_serwerow as $key => $value):
              $value->liczba_raportow = one("SELECT COUNT(*) FROM `raport_serwer` WHERE `serwer_id` = ".$value->serwer_id." ");
            ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <b><a href="">Serwer:</a></b><a href="<?= "?x=$x&xx=$xx&serwer=$value->serwer_id"?>"> <?= $value->mod ?> | <?= $value->nazwa ?></a>
              <span class="badge badge-primary badge-pill">Liczba Raportów: <?= $value->liczba_raportow ?></span>
            </li>
            <? endforeach; ?>
          </ul>
				</div>
			</div>
		</div>
	</div>
  <? elseif(!empty($serwer)): ?>
  <div class="row">
		<div class="col-xs-12">
			<div class="box box">
				<div class="box-header">
				  <h3 class="box-title">Raporty Opiekuna - Serwer: <?= $serwer_detale->mod ?> | <?= $serwer_detale->nazwa ?></h3>
				</div>
				<div class="box-body">
          <?
          $raporty= all("SELECT * FROM `raport_serwer` WHERE `serwer_id` = $serwer ORDER BY `rok`, `miesiac` DESC");
          foreach ($raporty as $key => $value):
            $raport_admini = all("SELECT * FROM `raport_opiekun` WHERE `serwer` = $value->serwer_id AND `miesiac` = $value->miesiac AND `rok` = $value->rok");
            $value->admin_miesiaca = toCommunityID($value->admin_miesiaca);
          ?>
          <div class="invoice">
            <div class="row">
              <div class="col-xs-12">
                <h2 class="page-header">
                  Raport: <?= $value->miesiac ?>/<?= $value->rok  ?>
                  <small class="pull-right">Data: <?= $value->data_raportu ?></small>
                </h2>
              </div>
            </div>
            <div class="row invoice-info">
              <div class="col-sm-4 invoice-col">
                <strong>Sourcebans</strong>
                <p>Ilość banów: <?= $value->sb_ban ?><br>
                lość mutów: <?= $value->sb_mute ?><br>
                Ilość gagów: <?= $value->sb_gag ?><br>
                Ilość unbanów: <?= $value->sb_unban ?><br>
                Ilość unmutów: <?= $value->sb_unmute ?><br>
                Ilość ungagów: <?= $value->sb_ungag ?></p>
              </div>
              <div class="col-sm-4 invoice-col">
                <strong>Statystyki HLS/GameTracker</strong>
                <p>GameTracker Rank: <?= $value->gt_rank ?><br>
                GameTracker Lowest: <?= $value->gt_low ?><br>
                GameTracker Higest: <?= $value->gt_hight ?><br>
                Hlstats: Ilość Graczy : <?= $value->hls_graczy ?></p>
              </div>
              <div class="col-sm-4 invoice-col">
                <strong>Finanse</strong>
                <p>Koszt Serwera: <?= $value->finanse_koszt ?><br>
                Sprzedanych Usług: <?= $value->sklep_uslugi ?><br>
                Cena sprzedanych Usług: <?= $value->sklep_uslugi_koszt ?><br>
                Liczba Adminów: <?= $value->admini_liczba ?></p>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-12 table-responsive">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th></th>
                      <th>Admin</th>
                      <th>SteamID</th>
                      <th>Grupa</th>
                      <th>Czas połaczenia</th>
                      <th>Forum Posty</th>
                      <th>Forum Warny</th>
                      <th>Składka</th>
                      <th>Opinia</th>
                    </tr>
                  </thead>
                  <tbody>
                    <? foreach ($raport_admini  as $value2):
                      $value2->skladka = ($value2->skladka == 1) ? 'Tak' : 'Nie';
                      $value2->opinia = (empty($value2->opinia)) ? 'Brak..': $value2->opinia;
                      $value2->steamid = toCommunityID($value2->steamid);
                      $value2->najlepszy = ($value->admin_miesiaca == $value2->steamid) ? '<i class="fa fa-heart"></i> ': '';
                    ?>
                    <tr>
                      <td><?= $value2->najlepszy ?><?= $value2->id ?></td>
                      <td><a href="https://steamcommunity.com/profiles/<?= $value2->steamid ?>"><?= $value2->admin_nick ?>(<?= $value2->admin_steam ?>)</a></td>
                      <td><?= $value2->steamid ?></td>
                      <td><?= $value2->grupa ?></td>
                      <td><?= sek_na_tekst((int)$value2->serwer_czaspolaczenia) ?></td>
                      <td><?= $value2->forum_posty ?></td>
                      <td><?= $value2->forum_warny ?></td>
                      <td><?= $value2->skladka ?> (<?= $value2->skladka_kwota ?>zł [<?= $value2->skladka_metoda ?>])</td>
                      <td><?= $value2->opinia ?></td>
                    </tr>
                    <? endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <? endforeach; ?>
				</div>
			</div>
		</div>
	</div>
  <? endif; ?>

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
