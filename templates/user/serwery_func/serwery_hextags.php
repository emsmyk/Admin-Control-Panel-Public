<?
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
		<p><? if(!empty($_SESSION['msg'])){ echo $_SESSION['msg']; unset($_SESSION['msg']); }  ?></p>
	</section >
  </div>
<?
$id = (isset($_GET['id'])) ? (int)$_GET['id'] : null;
$co = (isset($_GET['co'])) ? $_GET['co'] : null;
$edycja_id = (isset($_GET['edycja'])) ? (int)$_GET['edycja'] : null;
$wymus_aktualizacje = (isset($_GET['wymus_aktualizacje'])) ? (int)$_GET['wymus_aktualizacje'] : null;

if(!empty($id) && $co == "usun"){
  $func->hextags_usun($id, $player->user, $dostep->SerwerRangiUsun);
	header("Location: ?x=$x&xx=$xx");
}
if(isset($_POST['edycja_from'])) {
  $func->hextags_edytuj($_POST['edycja_from'], $player->user, $dostep->SerwerRangiEdytuj);
	header("Location: ?x=$x&xx=$xx");
}
if(isset($_POST['nowy_rekord'])) {
  $func->hextags_dodaj($_POST['nowy_rekord'], $player->user, $dostep->SerwerRangiDodaj);
  header("Location: ?x=$x&xx=$xx");
}
if(!empty($id) && $co == "kolejonosc_up"){
  $func->kolejnosc('up', $id, 'istotnosc', 'acp_serwery_hextags', $player->user, $dostep->SerwerRangiEdytuj);
  header("Location: ?x=$x&xx=$xx");
}
if(!empty($id) && $co == "kolejonosc_down"){
  $func->kolejnosc('down', $id, 'istotnosc', 'acp_serwery_hextags', $player->user, $dostep->SerwerRangiEdytuj);
  header("Location: ?x=$x&xx=$xx");
}
if($wymus_aktualizacje == 1){
  $func->wymus_aktualizacje($xx, $player->user, $dostep->SerwerWymusAktualizacje);
  header("Location: ?x=$x&xx=$xx");
}

$serwer_array = array(0 => 'Wszystkie');
$serwer_array_q = all("SELECT `serwer_id`, `mod`, `nazwa` FROM `acp_serwery`");
foreach($serwer_array_q as $serwer_array_dane){
  $serwer_array[$serwer_array_dane->serwer_id]="$serwer_array_dane->nazwa";
}

$tak_nie_array = array(1 => 'Tak', 0 => 'Nie');
$czasowa = array(1 => 'Tymczasowa', 0 => 'Stała');
$kolory  = array('default' => 'Domyślny', 'teamcolor' =>'Kolor Teamu', 'red' =>'Czarwony', 'lightred' =>'Jasny Czerwony', 'darkred' =>'Ciemno Czerwony', 'bluegrey' =>'Niebisko Szary', 'blue' =>'Niebieski', 'darkblue' =>'Ciemny Niebieski', 'orchid' =>'Fioletowy', 'yellow' =>'Żółty', 'gold' =>'Złoty', 'lightgreen' =>'Jasny Zielony', 'green' =>'Zielony', 'lime' =>'Limonkowy', 'grey' =>'Szary', 'grey2' =>'Szary 2', 'orange' => 'Pomarańczowy');
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
          <? $acp_r_d = row("SELECT * FROM `acp_serwery_hextags` WHERE `id` = $edycja_id LIMIT 1;"); ?>
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
					<p><div class='form-group input-group'><span class='input-group-addon'>Typ</span><input class='form-control' type='text' name='e_hextags' value='<?= $acp_r_d->hextags ?>'/></div></p>
					<p><div class='form-group input-group'><span class='input-group-addon'>Nazwa Tagu</span><input class='form-control' type='text' name='e_TagName' value='<?= $acp_r_d->TagName ?>'/></div></p>
					<p><div class='form-group input-group'><span class='input-group-addon'>Tag Tabela</span><input class='form-control' type='text' name='e_ScoreTag' value='<?= $acp_r_d->ScoreTag ?>'/></div></p>
					<p><div class='form-group input-group'><span class='input-group-addon'>Tag Say</span><input class='form-control' type='text' name='e_ChatTag' value='<?= $acp_r_d->ChatTag ?>'/></div></p>
          <p><div class='form-group input-group'><span class='input-group-addon'>Kolor Tagu Say</span>
            <select class="form-control" name="e_TagColor">
              <?
              echo '<option value="'.$acp_r_d->TagColor.'">'.$kolory[$acp_r_d->TagColor].'</option>';
              foreach ($kolory as $key => $value):
                if($acp_r_d->TagColor != $key)
                echo '<option value="'.$key.'">'.$value.'</option>';
              endforeach;
              ?>
            </select>
          </div></p>
          <p><div class='form-group input-group'><span class='input-group-addon'>Kolor Say</span>
            <select class="form-control" name="e_ChatColor">
              <?
              echo '<option value="'.$acp_r_d->ChatColor.'">'.$kolory[$acp_r_d->ChatColor].'</option>';
              foreach ($kolory as $key => $value):
                if($acp_r_d->ChatColor != $key)
                echo '<option value="'.$key.'">'.$value.'</option>';
              endforeach;
              ?>
            </select>
          </div></p>
          <p><div class='form-group input-group'><span class='input-group-addon'>Kolor Nicku Say</span>
            <select class="form-control" name="e_NameColor">
              <?
              echo '<option value="'.$acp_r_d->NameColor.'">'.$kolory[$acp_r_d->NameColor].'</option>';
              foreach ($kolory as $key => $value):
                if($acp_r_d->NameColor != $key)
                echo '<option value="'.$key.'">'.$value.'</option>';
              endforeach;
              ?>
            </select>
          </div></p>
          <p><div class='form-group input-group'><span class='input-group-addon'>Force</span>
            <select class="form-control" name="e_Force">
              <?
              echo '<option value="'.$acp_r_d->Force.'">'.$tak_nie_array[$acp_r_d->Force].'</option>';
              foreach ($tak_nie_array as $key => $value):
                if($acp_r_d->Force != $key)
                echo '<option value="'.$key.'">'.$value.'</option>';
              endforeach;
              ?>
            </select>
          </div></p>
					<p><div class='form-group input-group'><span class='input-group-addon'>Komentarz</span><input class='form-control' type='text' name='e_komentarz' value='<?= $acp_r_d->komentarz ?>'/></div></p>
					<p><div class='form-group input-group'><span class='input-group-addon'>Czasowa</span><input class='form-control' type='text' name='e_komentarz' value='<?= $czasowa[$acp_r_d->czasowa] ?>' disabled /></div></p>
					<p><div class='form-group input-group'><span class='input-group-addon'>Czasowa Koniec</span><input class='form-control' type='text' name='e_komentarz' value='<?= $acp_r_d->czasowa_end ?>' disabled /></div></p>

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
				  <h3 class="box-title">HexTags</h3>
				</div>
				<div class="box-body">
          <table data-page-length='10' id="example" class="table table-bordered table-striped" width="100%">
						<thead>
							<tr>
                <th>ID</th>
								<th>Serwer</th>
								<th>Typ</th>
								<th>Tag Tabela</th>
								<th>Tag Say</th>
								<th>Kolor Tag Say</th>
								<th>Kolor Say</th>
								<th>Kolor Nick</th>
								<th>Force</th>
								<th>Istotność</th>
								<th>Komentarz</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
						<?
            $acp_r_q = all("SELECT *, `serwer_id` AS `serwer_id_table`, (SELECT `mod` FROM `acp_serwery` WHERE `serwer_id` = `serwer_id_table`) AS serwer_nazwa FROM `acp_serwery_hextags`");
  					foreach($acp_r_q as $acp_r){
              $acp_r->serwer_nazwa = ($acp_r->serwer_id_table==0) ? 'Wszystkie' : $acp_r->serwer_nazwa ;
              $acp_r->serwer_nazwa = (empty($acp_r->serwer_nazwa)) ? '<i>Serwer nie istnieje</i>' : $acp_r->serwer_nazwa;
					  ?>
            <tr class="odd gradeX">
              <td><?= $acp_r->id ?></td>
              <td><?= $acp_r->serwer_nazwa ?></td>
              <td><?= $acp_r->hextags ?> <br><i><small>Ranga: <?= $czasowa[$acp_r->czasowa] ?></i><small></td>
              <td><?= $acp_r->ScoreTag ?></td>
              <td><?= $acp_r->ChatTag ?></td>
              <td><?= $kolory[$acp_r->TagColor] ?></td>
              <td><?= $kolory[$acp_r->ChatColor] ?></td>
              <td><?= $kolory[$acp_r->NameColor] ?></td>
              <td><?= $tak_nie_array[$acp_r->Force] ?></td>
              <td><?= $acp_r->istotnosc ?></td>
              <td><?= $acp_r->komentarz ?></td>
              <td width="100%">
                <div class="btn-group">
                  <a href="<?= "?x=$x&xx=$xx&co=kolejonosc_up&id=$acp_r->id" ?>" class="btn btn-default" role="button" aria-pressed="true"><i class="fa fa-angle-double-up"></i></a>
                  <a href="<?= "?x=$x&xx=$xx&co=kolejonosc_down&id=$acp_r->id" ?>" class="btn btn-default" role="button" aria-pressed="true"><i class="fa fa-angle-double-down"></i></a>
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
                  <span class='input-group-addon'>Typ</span>
                  <input class="form-control" name="n_typ">
                </div>
              </p>
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
                  <select class="form-control" name="n_kolor_tag_tag">
                    <option value="default">Domyślny</option>
                    <option value="teamcolor">Kolor Team'u</option>
                    <option value="red">Czerwony</option>
                    <option value="lightred">Jasny Czerwony</option>
                    <option value="darkred">Ciemny Czerwony</option>
                    <option value="bluegrey">Niebisko Szary</option>
                    <option value="blue">Niebieski</option>
                    <option value="darkblue">Ciemny Niebieski</option>
                    <option value="purple">Purpurowy</option>
                    <option value="orchid">Orchid</option>
                    <option value="yellow">Żółty</option>
                    <option value="gold">Złoty</option>
                    <option value="lightgreen">Jasny Zielony</option>
                    <option value="green">Zielony</option>
                    <option value="lime">Limonkowy</option>
                    <option value="grey">Szary</option>
                    <option value="grey2">Szary 2</option>
                  </select>
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Kolor Say</span>
                  <select class="form-control" name="n_kolor_tag">
                    <option value="default">Domyślny</option>
                    <option value="teamcolor">Kolor Team'u</option>
                    <option value="red">Czerwony</option>
                    <option value="lightred">Jasny Czerwony</option>
                    <option value="darkred">Ciemny Czerwony</option>
                    <option value="bluegrey">Niebisko Szary</option>
                    <option value="blue">Niebieski</option>
                    <option value="darkblue">Ciemny Niebieski</option>
                    <option value="purple">Purpurowy</option>
                    <option value="orchid">Orchid</option>
                    <option value="yellow">Żółty</option>
                    <option value="gold">Złoty</option>
                    <option value="lightgreen">Jasny Zielony</option>
                    <option value="green">Zielony</option>
                    <option value="lime">Limonkowy</option>
                    <option value="grey">Szary</option>
                    <option value="grey2">Szary 2</option>
                  </select>
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Kolor Nicku Say</span>
                  <select class="form-control" name="n_kolor_nick">
                    <option value="default">Domyślny</option>
                    <option value="teamcolor">Kolor Team'u</option>
                    <option value="red">Czerwony</option>
                    <option value="lightred">Jasny Czerwony</option>
                    <option value="darkred">Ciemny Czerwony</option>
                    <option value="bluegrey">Niebisko Szary</option>
                    <option value="blue">Niebieski</option>
                    <option value="darkblue">Ciemny Niebieski</option>
                    <option value="purple">Purpurowy</option>
                    <option value="orchid">Orchid</option>
                    <option value="yellow">Żółty</option>
                    <option value="gold">Złoty</option>
                    <option value="lightgreen">Jasny Zielony</option>
                    <option value="green">Zielony</option>
                    <option value="lime">Limonkowy</option>
                    <option value="grey">Szary</option>
                    <option value="grey2">Szary 2</option>
                  </select>
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Force</span>
                  <select class="form-control" name="n_force">
                    <option value="0">Nie</option>
                    <option value="1">Tak</option>
                  </select>
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Komentarz</span>
                  <input class="form-control" name="n_komentarz">
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
<?= js_table_one('#example', 'desc', 9, 10); ?>
</body>
</html>
