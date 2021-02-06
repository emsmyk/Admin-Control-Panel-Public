<?
tytul_strony("Zadania: Statystyki");
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
	<div class="row">
		<div class="col-xs-12">
      <div class="box box">
        <div class="box-body">
          <?
          $zadania = new stdClass();
          $zadania->grupa = all("SELECT `id`, `nazwa` FROM `acp_users_grupy` WHERE `dostep` LIKE '%\"ZadaniePrzyjmnij\":\"1\"%' ");
          foreach ($zadania->grupa as $value) {
            $zadania->free_job = all("SELECT `user`, `login`, `steam`, `steam_avatar`, `steam_login`, `grupa` FROM `acp_users` WHERE `grupa` = '$value->id' ");
            foreach ($zadania->free_job as $key => $value) {
              $zadania->{technicy}->{$value->user} = $value;
              $zadania->{technicy}->{$value->user}->zrealizowane = one("SELECT COUNT(`id`) FROM `acp_zadania` WHERE `technik_id` = $value->user AND `status` = 3");
              $zadania->{technicy}->{$value->user}->w_realizacji = one("SELECT COUNT(`id`) FROM `acp_zadania` WHERE `technik_id` = $value->user AND `status` < 3 AND `status` != -2");
              $zadania->{technicy}->{$value->user}->zadan = $zadania->{technicy}->{$value->user}->zrealizowane + $zadania->{technicy}->{$value->user}->w_realizacji;
              $zadania->{technicy}->{$value->user}->zadan_prc = round($zadania->{technicy}->{$value->user}->zrealizowane*100/$zadania->{technicy}->{$value->user}->zadan, 2);
              $zadania->{technicy}->{$value->user}->kolor = ($zadania->{technicy}->{$value->user}->zadan_prc > 80) ? 'green' : 'red';
              $zadania->{technicy}->{$value->user}->grupa_nazwa = one("SELECT `nazwa` FROM `acp_users_grupy` WHERE `id` = $value->grupa LIMIT 1");
            }
          }
          $zadania->{status} = all("SELECT `status`, COUNT(`id`) AS `ilosc`, (SELECT `nazwa` FROM `acp_zadania_status` WHERE `id` = `status`) AS `nazwa` FROM `acp_zadania`GROUP BY `status` ORDER BY `status` DESC");
          $zadania->wszystkich_zadan = one("SELECT COUNT(`id`) FROM `acp_zadania`");
          ?>
            <p class="text-center">
              <strong>Realizowanych zadań według Userów z dostępem do realizacji</strong>
            </p>
            <? foreach ($zadania->technicy as $key => $value): ?>
            <div class="progress-group">
              <span class="progress-text"><?= $value->steam_login ?> <i>(<?= $value->login ?>)</i> - <b><?= $value->grupa_nazwa ?></b></span>
              <span class="progress-number"><b><?= $value->zrealizowane ?></b>/<?= $value->zadan ?> (<?= $value->zadan_prc ?> %)</span>

              <div class="progress sm">
                <div class="progress-bar progress-bar-<?= $value->kolor ?>" style="width: <?= $value->zadan_prc ?>%"></div>
              </div>
            </div>
          <? endforeach; ?>
            <p class="text-center">
              <strong>Ilość zadań według status</strong>
            </p>
            <? foreach ($zadania->status as $key => $value):?>
            <div class="progress-group">
              <span class="progress-text"><?= $value->nazwa ?></span>
              <span class="progress-number"><b><?= $value->ilosc ?></b>/<?= $zadania->wszystkich_zadan ?> </span>

              <div class="progress sm">
                <div class="progress-bar progress-bar-yellow" style="width: <?= round($value->ilosc*100/$zadania->wszystkich_zadan,2) ?>%"></div>
              </div>
            </div>
          <? endforeach; ?>
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
<?= js_table_one('#example', 'desc'); ?>
</body>
</html>
