<?
/*
  Moduł: Serwery Konfiguracja
  Zakładka:  Rangi
  PLUGINY: togsclantags & csgo_chat_colors

  ZOSTAJĄ ZAMKNIĘTE NA RZECZ HEXTAGS od dnia 26.06.2020
  MODUŁ->Zakładka nie będzie dalej rozwijana ani aktualizowana
*/
$func = getClass('SerwerKonfiguracja');
?>
<style>
.example-modal .modal { position: relative; top: auto; bottom: auto; right: auto; left: auto; display: block; z-index: 1; }
.example-modal .modal { background: transparent !important; }
</style>
<div class="content-wrapper">
<section class="content">
  <div class="row">
	<section class="col-lg-12">
		<p><?
    echo komunikaty_rozbudowany('fa fa-warning', 'MODUŁ WYCOFANY', 'Moduł: Serwery Konfiguracja<br>
    Zakładka:  Rangi<br>
    PLUGINY: togsclantags & csgo_chat_colors<br>
    <br>
    ZOSTAJĄ ZAMKNIĘTE NA RZECZ HEXTAGS od dnia 26.06.2020<br>
    Zakładka nie będzie dalej rozwijana ani aktualizowana.<br>', 4);
    if(!empty($_SESSION['msg'])){ echo $_SESSION['msg']; unset($_SESSION['msg']); }
    ?></p>
	</section >
  </div>
<?
$id = (int)$_GET['id'];
$co = real_string($_GET['co']);
$edycja_id = (int) $_GET['edycja'];

if(!empty($id) && !empty($co)) {
  switch ($co) {
    case 'usun':
      $func->rangi_usun($id, $player->user, $dostep->SerwerRangiUsun);
      header("Location: ?x=$x&xx=$xx");
      break;
  }
}
if(isset($_POST['edycja_from'])) {
  $func->rangi_edytuj($_POST['edycja_from'], $player->user, $dostep->SerwerRangiEdytuj);
	header("Location: ?x=$x&xx=$xx");
}
if(isset($_POST['nowy_rekord'])) {
  $func->rangi_dodaj($_POST['nowy_rekord'], $player->user, $dostep->SerwerRangiDodaj);
  header("Location: ?x=$x&xx=$xx");
}
if($_GET['wymus_aktualizacje'] == 1){
  $func->wymus_aktualizacje($xx, $player->user, $dostep->SerwerWymusAktualizacje);
  header("Location: ?x=$x&xx=$xx");
}

$serwer_array = array(0 => 'Wszystkie');
$serwer_array_q = all("SELECT `serwer_id`, `mod`, `nazwa` FROM `acp_serwery`");
foreach($serwer_array_q as $serwer_array_dane){
  $serwer_array[$serwer_array_dane->serwer_id]="$serwer_array_dane->nazwa";
}

$tak_nie_array = array(1 => 'Tak', 0 => 'Nie');
$kolory  = array('DEFAULT' => 'Podstawowy', 'TEAM' =>'Drużyna', 'RED' =>'Czarwony', 'GREEN' =>'Zielony', 'LIME' =>'Limonkowy', 'LIGHTGREEN' =>'Jasny Zielony', 'LIGHTRED' =>'Jasny Czerwony', 'GRAY' => 'Szary', 'LIGHTOLIVE' => 'Jasny Oliwkowy', 'OLIVE' => 'Oliwkowy', 'PURPLE' => 'Fioletowy', 'LIGHTBLUE' => 'Jasny Niebieski', 'BLUE' => 'Niebieski');

?>
<?
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
        <form name='edycja_from' method='post' action='<?= "?x=$x&xx=$xx&edycja=$edycja_id"; ?>'>
          <? $acp_r_d = row("SELECT * FROM `acp_serwery_rangi` WHERE `id` = $edycja_id LIMIT 1;"); ?>
          <input type='hidden' name='id' value='<?= $acp_r_d->id ?>'>
          <p><div class='form-group input-group'><span class='input-group-addon'>Serwer</span>
            <select class="form-control" name="e_serwerid">
              <?
              echo '<option value="'.$acp_r_d->serwer_id.'">'.$serwer_array[$acp_r_d->serwer_id].'</option>';
              foreach ($serwer_array as $key => $value):
                if($acp_r_d->serwer_id != $key)
                echo '<option value="'.$key.'">'.$value.'</option>';
              endforeach;
              ?>
            </select>
          </div></p>
					<p><div class='form-group input-group'><span class='input-group-addon'>Flagi</span><input class='form-control' type='text' name='e_flags' value='<?= $acp_r_d->flags ?>'/></div></p>
					<p><div class='form-group input-group'><span class='input-group-addon'>Tag Tabela</span><input class='form-control' type='text' name='e_tagtabela' value='<?= $acp_r_d->tag_tabela ?>'/></div></p>
					<p><div class='form-group input-group'><span class='input-group-addon'>Tag Say</span><input class='form-control' type='text' name='e_tagsay' value='<?= $acp_r_d->tag_say ?>'/></div></p>
          <p><div class='form-group input-group'><span class='input-group-addon'>Kolor Say</span>
            <select class="form-control" name="e_kolorsay">
              <?
              echo '<option value="'.$acp_r_d->tag_say_kolor.'">'.$kolory[$acp_r_d->tag_say_kolor].'</option>';
              foreach ($kolory as $key => $value):
                if($acp_r_d->tag_say_kolor != $key)
                echo '<option value="'.$key.'">'.$value.'</option>';
              endforeach;
              ?>
            </select>
          </div></p>
          <p><div class='form-group input-group'><span class='input-group-addon'>Kolor Nick Say</span>
            <select class="form-control" name="e_kolornick">
              <?
              echo '<option value="'.$acp_r_d->nick_say_kolor.'">'.$kolory[$acp_r_d->nick_say_kolor].'</option>';
              foreach ($kolory as $key => $value):
                if($acp_r_d->nick_say_kolor != $key)
                echo '<option value="'.$key.'">'.$value.'</option>';
              endforeach;
              ?>
            </select>
          </div></p>
					<p><div class='form-group input-group'><span class='input-group-addon'>Istotność</span><input class='form-control' type='text' name='e_istotnosc' value='<?= $acp_r_d->istotnosc ?>'/></div></p>
					<p><div class='form-group input-group'><span class='input-group-addon'>Komentarz</span><input class='form-control' type='text' name='e_komentarz' value='<?= $acp_r_d->komentarz ?>'/></div></p>

          <p><input name='edycja_from' class='btn btn-primary btn btn-block' type='submit' value='Edytuj'/></p>
        </form>
      </div>
    </div>
  </div>
</div>
<?
}
?>
	<div class="row">
		<div class="col-lg-9">
			<div class="box box">
				<div class="box-header">
				  <h3 class="box-title">Rangi Tablea/Say</h3>
				</div>
				<div class="box-body">
          <table data-page-length='10' id="example" class="table table-bordered table-striped" width="100%">
						<thead>
							<tr>
                <th>ID</th>
								<th>Serwer</th>
								<th>Typ</th>
								<th>Flagi</th>
								<th>Tag Tabela</th>
								<th>Tag Say</th>
								<th>Kolor: Say / Nick</th>
								<th>Istotność</th>
								<th>Komentarz</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
						<?
            $acp_r_q = all("SELECT *, `serwer_id` AS `serwer_id_table`, (SELECT `mod` FROM `acp_serwery` WHERE `serwer_id` = `serwer_id_table`) AS serwer_nazwa FROM `acp_serwery_rangi`");
  					foreach($acp_r_q as $acp_r){
              $acp_r->serwer_nazwa = ($acp_r->serwer_id_table==0) ? 'Wszystkie' : $acp_r->serwer_nazwa ;
              $acp_r->serwer_nazwa = (empty($acp_r->serwer_nazwa)) ? '<i>Serwer nie istnieje</i>' : $acp_r->serwer_nazwa;
              if($acp_r->czasowa == 0) { $acp_r->czasowa = 'Podstawowa'; } else if($acp_r->czasowa == 1) { $acp_r->czasowa = 'Dodatkowa'; }
						?>
            <tr class="odd gradeX">
              <td><?= $acp_r->id ?></td>
              <td><?= $acp_r->serwer_nazwa ?></td>
              <td><?= $acp_r->czasowa ?></td>
              <td><?= $acp_r->flags ?></td>
              <td><?= $acp_r->tag_tabela ?></td>
              <td><?= $acp_r->tag_say ?></td>
              <td><?= $acp_r->tag_say_kolor,' / ',$acp_r->nick_say_kolor ?></td>
              <td><?= $acp_r->istotnosc ?></td>
              <td><?= $acp_r->komentarz ?></td>
              <td>
                <a href="<?= "?x=$x&xx=$xx&edycja=$acp_r->id" ?>"><button type="button" class="btn btn-primary"><i class="fa fa-edit"></i></button></a>
                <a href="<?= "?x=$x&xx=$xx&co=usun&id=$acp_r->id" ?>"><button type="button" class="btn btn-danger"><i class="fa fa-times"></i></button></a>
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
    $ostatnia_aktualizacja = $func->ostatnia_aktualizacja($xx);
    $kolejna_aktualizacja = $func->kolejna_aktualizacja($xx);
    $serwery_aktualizowane = $func->serwery_aktualizowane();
    ?>
    <div class="col-lg-3 col-xs-12">
      <div class="small-box bg-maroon">
        <div class="inner">
          <h2>Ostatnia Aktualizcja</h2>
          <p><?= $ostatnia_aktualizacja; ?> </p>
        </div>
        <div class="icon">
          <i class="fa fa-clock-o"></i>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-xs-12">
      <div class="small-box bg-olive">
        <div class="inner">
          <h2>Kolejna aktualizacja</h2>
          <p><?= $kolejna_aktualizacja; ?> </p>
        </div>
        <div class="icon">
          <i class="fa fa-bolt"></i>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-xs-12">
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
    <!-- okno wyskakujace -->
    <div class="modal fade" id="dodaj-serwer">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Dodaj</h4>
          </div>
          <div class="modal-body">
            <form name='now_rekord' method='post' action='<?= "?x=$x&xx=$xx"; ?>'>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Serwer</span>
                  <select class="form-control" name="n_serwer">
                    <?
                    foreach ($serwer_array as $key => $value):
                      echo '<option value="'.$key.'">'.$value.'</option>';
                    endforeach;
                    ?>
                  </select>
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Flagi</span>
                  <input class="form-control" name="n_flags">
                </div>
              </p>
              <p class="help-block">Są dwie możliwości: Pierwsza flagi z listy SM lub steam id.</p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Tag Tabela</span>
                  <input class="form-control" name="n_tag_tabela">
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Tag Say</span>
                  <input class="form-control" name="n_tag_say">
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Kolor Tagu Say</span>
                  <select class="form-control" name="n_kolor_tag">
                    <?
                    foreach ($kolory as $key => $value):
                      echo '<option value="'.$key.'">'.$value.'</option>';
                    endforeach;
                    ?>
                  </select>
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Kolor Nicku Say</span>
                  <select class="form-control" name="n_kolor_nick">
                    <?
                    foreach ($kolory as $key => $value):
                      echo '<option value="'.$key.'">'.$value.'</option>';
                    endforeach;
                    ?>
                  </select>
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Istoność</span>
                  <input class="form-control" name="n_istotnosc">
                </div>
              </p>
              <p class="help-block">W przypadku wystąpienia dwóch (lub wiecej) uprawnień dla danej osoby otrzymuje on rangę o wyższej istotości.</p>
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
<?= js_table('#test'); ?>

<script>
  $(function () {
    $('#example').DataTable({ responsive: true, "order": [[ 7, "desc" ]] });
  })
</script>
</body>
</html>
