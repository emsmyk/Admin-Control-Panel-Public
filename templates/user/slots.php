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
$co = (isset($_GET['co'])) ? $_GET['co'] : null;
if(isset($co) && isset($xx)){
    $dane_xx = row("SELECT `serwer_id`, `nazwa`, `mod`, `max_graczy`, `min`, `max`, `h_start`, `h_koniec`, `style` FROM `acp_serwery` LEFT JOIN `acp_slots_serwery` ON `acp_serwery`.`serwer_id` = `acp_slots_serwery`.`serwer` WHERE `serwer_id` = $xx LIMIT 1;");
    $dane_xx->style_array = array('LOW' => 'LOW', 'HARD' => 'HARD');
    if(isset($_POST['edycja_from'])){
      $form = new stdClass();
      $form->min = $_POST['min'];
      $form->max = $_POST['max'];
      $form->h_start = $_POST['h_start'];
      $form->h_koniec = $_POST['h_koniec'];
      $form->style = $_POST['style'];
      query("UPDATE `acp_slots_serwery` SET `min` = $form->min, `max` = $form->max, `h_start` = $form->h_start, `h_koniec` = $form->h_koniec, `style` = '$form->style' WHERE `serwer` = $xx;");
      $_SESSION['msg'] = komunikaty("Zaktualizowano ustawienia dla serwera $dane_xx->nazwa - $dane_xx->mod", 1);
      header("Location: ?x=$x");
    }
    if(isset($_POST['dodaj_from'])){
      $form = new stdClass();
      $form->min = $_POST['min'];
      $form->max = $_POST['max'];
      $form->h_start = $_POST['h_start'];
      $form->h_koniec = $_POST['h_koniec'];
      $form->style = $_POST['style'];
      insert("acp_slots_serwery", array('`serwer`' => "$xx", '`min`' => "$form->min", '`max`' => "$form->max", '`h_start`' => "$form->h_start", '`h_koniec`' => "$form->h_koniec", '`style`' => "$form->style"));
      $_SESSION['msg'] = komunikaty("Dodano ustawienia dla serwera $dane_xx->nazwa - $dane_xx->mod", 1);
      header("Location: ?x=$x");
    }
}
?>

	<div class="row">
		<div class="col-lg-9">
      <? if($co == 'edytuj'): ?>
      <div class="box box">
        <div class="box-header">
          <h3 class="box-title">Edytuj #<?= $dane_xx->serwer_id ?> - <?= $dane_xx->nazwa ?></h3>
        </div>
        <div class="box-body">
          <form method="post">
            <h4>Ilość Slotów</h4>
            <p><div class='form-group input-group'><span class='input-group-addon'>Minium</span><input class='form-control' type='number' name='min' value='<?= $dane_xx->min ?>'/></div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>Maksimum</span><input class='form-control' type='number' name='max' value='<?= $dane_xx->max ?>'/></div></p>
            <h4>Godziny Przeliczania Graczy</h4>
            <p><div class='form-group input-group'><span class='input-group-addon'>Od godziny</span><input class='form-control' type='number' name='h_start' value='<?= $dane_xx->h_start ?>'/></div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>Do godziny</span><input class='form-control' type='number' name='h_koniec' value='<?= $dane_xx->h_koniec ?>'/></div></p>
            <h4>Analiza</h4>
            <p><div class='form-group input-group'><span class='input-group-addon'>Styl</span>
              <select class="form-control" name="style">
                <?
                echo '<option value="'.$dane_xx->style.'">'.$dane_xx->style_array[$dane_xx->style].'</option>';
                foreach ($dane_xx->style_array as $key => $value):
                  if($dane_xx->style != $key)
                  echo '<option value="'.$key.'">'.$value.'</option>';
                endforeach;
                ?>
              </select>
            </div></p>
            <p><input name='edycja_from' class='btn btn-primary btn btn-block' type='submit' value='Edytuj'/></p>
          </form>
        </div>
      </div>
      <? elseif($co == 'dodaj'): ?>
      <div class="box box">
        <div class="box-header">
          <h3 class="box-title">Dodaj #<?= $dane_xx->serwer_id ?> - <?= $dane_xx->nazwa ?></h3>
          <div class="pull-right box-tools">
          </div>
        </div>
        <div class="box-body">
          <form method="post">
            <h4>Ilość Slotów</h4>
            <p><div class='form-group input-group'><span class='input-group-addon'>Minium</span><input class='form-control' type='number' name='min' /></div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>Maksimum</span><input class='form-control' type='number' name='max' /></div></p>
            <h4>Godziny Przeliczania Graczy</h4>
            <p><div class='form-group input-group'><span class='input-group-addon'>Od godziny</span><input class='form-control' type='number' name='h_start' /></div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>Do godziny</span><input class='form-control' type='number' name='h_koniec'/></div></p>
            <h4>Analiza</h4>
            <p><div class='form-group input-group'><span class='input-group-addon'>Styl</span>
              <select class="form-control" name="style">
                <?
                foreach ($dane_xx->style_array as $key => $value):
                  echo '<option value="'.$key.'">'.$value.'</option>';
                endforeach;
                ?>
              </select>
            </div></p>
            <p><input name='dodaj_from' class='btn btn-primary btn btn-block' type='submit' value='Dodaj'/></p>
          </form>
        </div>
      </div>
      <? endif; ?>
      <div class="box box">
				<div class="box-header">
				  <h3 class="box-title">Serwery</h3>
				  <div class="pull-right box-tools">
				  </div>
				</div>
				<div class="box-body">
          <table class="table">
            <thead class="thead-dark">
              <tr>
                <th scope="col">#</th>
                <th scope="col">Nazwa - [MOD]</th>
                <th scope="col">Dodatkowe</th>
                <th scope="col">Śr. Zapełnienie</th>
              </tr>
            </thead>
            <tbody>
              <?
              $variable = all("SELECT `serwer_id`, `nazwa`, `mod`, `max_graczy`, `min`, `max`, `h_start`, `h_koniec`, `style` FROM `acp_serwery` LEFT JOIN `acp_slots_serwery` ON `acp_serwery`.`serwer_id` = `acp_slots_serwery`.`serwer`;");
              foreach ($variable as $key => $value){
                if(empty($value->h_start) || empty($value->h_koniec) || empty($value->min) || empty($value->max)){
              ?>
                <tr>
                  <th scope="row"><?= $value->serwer_id ?></th>
                  <td colspan="2"><i><?= $value->nazwa ?> - [<?= $value->mod ?>] - Brak danych..</i></td>
                  <td><a href="<?= "?x=$x&xx=$value->serwer_id&co=dodaj" ?>">Dodaj</a></td>
                </tr>
              <?
                }
                else {
                  $data = date('Y-m-d', strtotime(' -1 days'));
                  $data1 = $data.' '.$value->h_start.':00';
                  $data2 = $data.' '.$value->h_koniec.':00';

                  $value->ilosc_graczy = one("SELECT SUM(`graczy`) FROM `acp_serwery_logs_hour` WHERE `serwer_id` = $value->serwer_id AND `data` BETWEEN '$data1' AND '$data2'");
                  $value->sr_ilosc_graczy = round($value->ilosc_graczy / ($value->h_koniec - $value->h_start + 1), 2);
                  $value->sr_zapelnienie = round($value->sr_ilosc_graczy / $value->max_graczy*100, 2);
                  // show($value);
                  $tablica_low = array(95, 85, 50, 40);
                  $tablica_hard = array(90, 70, 60, 40);
                  switch ($value->style) {
                    case 'LOW':
                      // serwer ma tak malo ze mniej niz minimum aby dalo sie grac
                      if($value->sr_ilosc_graczy < $value->min && $value->min > $value->max_graczy){
                        $value->propozycja = '
                          <tr><td colspan="4" class="text-center">Minimalna ilość slotów dla tego serwera wynosi <b>'.$value->min.'</b>, a maksymalna <b>'.$value->max.'</b></td></tr>
                          <tr><td colspan="4" class="text-center">Średnia ilość graczy to <b>'.$value->sr_ilosc_graczy.'</b>, nastomiast średnie zapełnienie <b>'.$value->sr_zapelnienie.'%</b></td></tr>
                          <tr><td colspan="4" class="text-center">Styl analizy: <b>'.$value->style.'</b> Proponowa ilość slotów w kolejnym dniu to: <b>Brak Zmian</b></td></tr>
                        ';
                      }
                      // serwer ma tak duzo graczy ze wiecej sie już nie da
                      if($value->sr_ilosc_graczy > $value->max && $value->max > $value->max_graczy){
                        $value->propozycja = '
                          <tr><td colspan="4" class="text-center">Minimalna ilość slotów dla tego serwera wynosi <b>'.$value->min.'</b>, a maksymalna <b>'.$value->max.'</b></td></tr>
                          <tr><td colspan="4" class="text-center">Średnia ilość graczy to <b>'.$value->sr_ilosc_graczy.'</b>, nastomiast średnie zapełnienie <b>'.$value->sr_zapelnienie.'%</b></td></tr>
                          <tr><td colspan="4" class="text-center">Styl analizy: <b>'.$value->style.'</b> Proponowa ilość slotów w kolejnym dniu to: <b>Brak Zmian</b></td></tr>
                        ';
                      }
                      // gdy jest wiecej niż minium ale tez mniej niz maksium
                      if($value->max >= $value->max_graczy){
                        if($value->sr_zapelnienie > $tablica_low[0]){
                          $value->propozycja = '
                            <tr><td colspan="4" class="text-center">Minimalna ilość slotów dla tego serwera wynosi <b>'.$value->min.'</b>, a maksymalna <b>'.$value->max.'</b></td></tr>
                            <tr><td colspan="4" class="text-center">Średnia ilość graczy to <b>'.$value->sr_ilosc_graczy.'</b>, nastomiast średnie zapełnienie <b>'.$value->sr_zapelnienie.'%</b></td></tr>
                            <tr><td colspan="4" class="text-center">Styl analizy: <b>'.$value->style.'</b> Zapełnienie jest wieksze niż <b>'.$tablica_low[0].'%</b> dodaj <b>2 sloty</b> w najbliższym czasie</td></tr>
                          ';
                        }
                        elseif($value->sr_zapelnienie > $tablica_low[1]){
                          $value->propozycja = '
                            <tr><td colspan="4" class="text-center">Minimalna ilość slotów dla tego serwera wynosi <b>'.$value->min.'</b>, a maksymalna <b>'.$value->max.'</b></td></tr>
                            <tr><td colspan="4" class="text-center">Średnia ilość graczy to <b>'.$value->sr_ilosc_graczy.'</b>, nastomiast średnie zapełnienie <b>'.$value->sr_zapelnienie.'%</b></td></tr>
                            <tr><td colspan="4" class="text-center">Styl analizy: <b>'.$value->style.'</b> Zapełnienie jest wieksze niż <b>'.$tablica_low[1].'%</b> dodaj <b>1 slot</b> w najbliższym czasie</td></tr>
                          ';
                        }
                        elseif($value->sr_zapelnienie > $tablica_low[2]){
                          $value->propozycja = '
                            <tr><td colspan="4" class="text-center">Minimalna ilość slotów dla tego serwera wynosi <b>'.$value->min.'</b>, a maksymalna <b>'.$value->max.'</b></td></tr>
                            <tr><td colspan="4" class="text-center">Średnia ilość graczy to <b>'.$value->sr_ilosc_graczy.'</b>, nastomiast średnie zapełnienie <b>'.$value->sr_zapelnienie.'%</b></td></tr>
                            <tr><td colspan="4" class="text-center">Styl analizy: <b>'.$value->style.'</b>  Pomimo że zapełnienie jest wieksze niż <b>'.$tablica_low[2].'%</b> zmiesz ilosć slotów o <b>-1</b> w najbliższym czasie</td></tr>
                          ';
                        }
                        elseif($value->sr_zapelnienie > $tablica_low[3]){
                          $value->propozycja = '
                            <tr><td colspan="4" class="text-center">Minimalna ilość slotów dla tego serwera wynosi <b>'.$value->min.'</b>, a maksymalna <b>'.$value->max.'</b></td></tr>
                            <tr><td colspan="4" class="text-center">Średnia ilość graczy to <b>'.$value->sr_ilosc_graczy.'</b>, nastomiast średnie zapełnienie <b>'.$value->sr_zapelnienie.'%</b></td></tr>
                            <tr><td colspan="4" class="text-center">Styl analizy: <b>'.$value->style.'</b>  Pomimo że zapełnienie jest wieksze niż <b>'.$tablica_low[3].'%</b> zmiesz ilosć slotów o <b>-2</b> w najbliższym czasie</td></tr>
                          ';
                        }
                        else {
                          $value->propozycja = '
                            <tr><td colspan="4" class="text-center">Minimalna ilość slotów dla tego serwera wynosi <b>'.$value->min.'</b>, a maksymalna <b>'.$value->max.'</b></td></tr>
                            <tr><td colspan="4" class="text-center">Średnia ilość graczy to <b>'.$value->sr_ilosc_graczy.'</b>, nastomiast średnie zapełnienie <b>'.$value->sr_zapelnienie.'%</b></td></tr>
                            <tr><td colspan="4" class="danger text-center">Styl analizy: <b>'.$value->style.'</b> Ten Serwer jest w kiepskiej kondycji..</td></tr>
                          ';
                        }
                      }
                      break;
                    case 'HARD':
                      // serwer ma tak malo ze mniej niz minimum aby dalo sie grac
                      if($value->sr_ilosc_graczy < $value->min && $value->min > $value->max_graczy){
                        $value->propozycja = '
                          <tr><td colspan="4" class="text-center">Minimalna ilość slotów dla tego serwera wynosi <b>'.$value->min.'</b>, a maksymalna <b>'.$value->max.'</b></td></tr>
                          <tr><td colspan="4" class="text-center">Średnia ilość graczy to <b>'.$value->sr_ilosc_graczy.'</b>, nastomiast średnie zapełnienie <b>'.$value->sr_zapelnienie.'%</b></td></tr>
                          <tr><td colspan="4" class="text-center">Styl analizy: <b>'.$value->style.'</b> Proponowa ilość slotów w kolejnym dniu to: <b>Brak Zmian</b></td></tr>
                        ';
                      }
                      // serwer ma tak duzo graczy ze wiecej sie już nie da
                      if($value->sr_ilosc_graczy > $value->max && $value->max > $value->max_graczy){
                        $value->propozycja = '
                          <tr><td colspan="4" class="text-center">Minimalna ilość slotów dla tego serwera wynosi <b>'.$value->min.'</b>, a maksymalna <b>'.$value->max.'</b></td></tr>
                          <tr><td colspan="4" class="text-center">Średnia ilość graczy to <b>'.$value->sr_ilosc_graczy.'</b>, nastomiast średnie zapełnienie <b>'.$value->sr_zapelnienie.'%</b></td></tr>
                          <tr><td colspan="4" class="text-center">Styl analizy: <b>'.$value->style.'</b> Proponowa ilość slotów w kolejnym dniu to: <b>Brak Zmian</b></td></tr>
                        ';
                      }
                      // gdy jest wiecej niż minium ale tez mniej niz maksium
                      if($value->max >= $value->max_graczy){
                        if($value->sr_zapelnienie > $tablica_hard[0]){
                          $value->propozycja = '
                            <tr><td colspan="4" class="text-center">Minimalna ilość slotów dla tego serwera wynosi <b>'.$value->min.'</b>, a maksymalna <b>'.$value->max.'</b></td></tr>
                            <tr><td colspan="4" class="text-center">Średnia ilość graczy to <b>'.$value->sr_ilosc_graczy.'</b>, nastomiast średnie zapełnienie <b>'.$value->sr_zapelnienie.'%</b></td></tr>
                            <tr><td colspan="4" class="warning text-center">Styl analizy: <b>'.$value->style.'</b> Zapełnienie jest wieksze niż <b>'.$tablica_hard[0].'%</b> dodaj <b>+2 sloty</b> w najbliższym czasie</td></tr>
                          ';
                        }
                        elseif($value->sr_zapelnienie > $tablica_hard[1]){
                          $value->propozycja = '
                            <tr><td colspan="4" class="text-center">Minimalna ilość slotów dla tego serwera wynosi <b>'.$value->min.'</b>, a maksymalna <b>'.$value->max.'</b></td></tr>
                            <tr><td colspan="4" class="text-center">Średnia ilość graczy to <b>'.$value->sr_ilosc_graczy.'</b>, nastomiast średnie zapełnienie <b>'.$value->sr_zapelnienie.'%</b></td></tr>
                            <tr><td colspan="4" class="warning text-center">Styl analizy: <b>'.$value->style.'</b> Zapełnienie jest wieksze niż <b>'.$tablica_hard[1].'%</b> dodaj <b>+1 slot</b> w najbliższym czasie</td></tr>
                          ';
                        }
                        elseif($value->sr_zapelnienie > $tablica_hard[2]){
                          $value->propozycja = '
                            <tr><td colspan="4" class="text-center">Minimalna ilość slotów dla tego serwera wynosi <b>'.$value->min.'</b>, a maksymalna <b>'.$value->max.'</b></td></tr>
                            <tr><td colspan="4" class="text-center">Średnia ilość graczy to <b>'.$value->sr_ilosc_graczy.'</b>, nastomiast średnie zapełnienie <b>'.$value->sr_zapelnienie.'%</b></td></tr>
                            <tr><td colspan="4" class="text-center">Styl analizy: <b>'.$value->style.'</b> Pomimo że zapełnienie jest wieksze niż <b>'.$tablica_hard[2].'%</b> zmiesz ilosć slotów o <b>-1</b> w najbliższym czasie</td></tr>
                          ';
                        }
                        elseif($value->sr_zapelnienie > $tablica_hard[3]){
                          $value->propozycja = '
                            <tr><td colspan="4" class="text-center">Minimalna ilość slotów dla tego serwera wynosi <b>'.$value->min.'</b>, a maksymalna <b>'.$value->max.'</b></td></tr>
                            <tr><td colspan="4" class="text-center">Średnia ilość graczy to <b>'.$value->sr_ilosc_graczy.'</b>, nastomiast średnie zapełnienie <b>'.$value->sr_zapelnienie.'%</b></td></tr>
                            <tr><td colspan="4" class="text-center">Styl analizy: <b>'.$value->style.'</b> Pomimo że zapełnienie jest wieksze niż <b>'.$tablica_hard[3].'%</b> zmiesz ilosć slotów o <b>-2</b> w najbliższym czasie</td></tr>
                          ';
                        }
                        else {
                          $value->propozycja = '
                            <tr><td colspan="4" class="text-center">Minimalna ilość slotów dla tego serwera wynosi <b>'.$value->min.'</b>, a maksymalna <b>'.$value->max.'</b></td></tr>
                            <tr><td colspan="4" class="text-center">Średnia ilość graczy to <b>'.$value->sr_ilosc_graczy.'</b>, nastomiast średnie zapełnienie <b>'.$value->sr_zapelnienie.'%</b></td></tr>
                            <tr><td colspan="4" class="danger text-center">Styl analizy: <b>'.$value->style.'</b> Ten Serwer jest w kiepskiej kondycji..</td></tr>
                          ';
                        }
                      }
                      break;

                  }

              ?>
              <tr>
                <th scope="row"><?= $value->serwer_id ?></th>
                <td><b><?= $value->nazwa ?></b> - [<?= $value->mod ?>]</td>
                <td><b>Slotów:</b> <?= $value->max_graczy; ?> <b>Data:</b> <?=  $data ?> <b>Godziny:</b> <?= $value->h_start ?>:00 - <?= $value->h_koniec?>:00</td>
                <td><a href="<?= "?x=$x&xx=$value->serwer_id&co=edytuj" ?>">Edytuj</a></td>
              </tr>
              <?= $value->propozycja;
                }
              } ?>
            </tbody>
          </table>
				</div>
			</div>
		</div>
		<div class="col-lg-3">
			<div class="box box">
				<div class="box-header">
				  <h3 class="box-title">Jak liczmy?</h3>
				  <div class="pull-right box-tools">
				  </div>
				</div>
				<div class="box-body">
					<p>Proponowaną nową ilość slotów liczymy na podstawie średniej ilości graczy, średniego zapełnienie serwera z poprzedniego dnia. Serwery ze względu na swoje specyfikację mogą wymagać X slotów minimalnych oraz X slotów maksymalnych do zapewnienia udanej zabawy. System ACP sprawdza te dane i nie proponuje w takich przypadkach zmiany ilości slotów</p>
          <p>W zależności od wyboru stylu rozwoju serwera, ilość proponowanych slotów przy danym zapełnieniu będzie się różnić. Styl Low dba aby było mało zmian przez co wolniej będą proponowane zmiany sloty na wieksze lub mniejsze ilości, natomiast HARD jest przeciwieństwem i jest proponowany dla serwerów które chcemy jak najszybciej zapełniać.</p>
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
