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
		<p><? if(!empty($_SESSION['msg'])){ echo $_SESSION['msg']; unset($_SESSION['msg']); } ?></p>
	</section >
  </div>
<?
$id = (isset($_GET['id'])) ? (int)$_GET['id'] : null;
$co = (isset($_GET['co'])) ? $_GET['co'] : null;
$konfiguruj = (isset($_GET['konfiguruj'])) ? $_GET['konfiguruj'] : null;
$edycja_id = (isset($_GET['edycja'])) ? (int)$_GET['edycja'] : null;
$wymus_aktualizacje = (isset($_GET['wymus_aktualizacje'])) ? (int)$_GET['wymus_aktualizacje'] : null;

if(!empty($co) && !empty($id) &&  $co == "usun"){
  $func->help_menu_usun($id, $player->user, $dostep->SerwerHelpMenuUsun);
  header("Location: ?x=$x&xx=$xx");
}
if(isset($_POST['edycja_from'])) {
  $func->help_menu_edytuj($player->user, $dostep->SerwerHelpMenuEdytuj);
  header("Location: ?x=$x&xx=$xx&edycja=$edycja_id");
}
if(isset($_POST['nowy_rekord'])) {
  $func->help_menu_nowy($player->user, $dostep->SerwerHelpMenuDodaj);
  header("Location: ?x=$x&xx=$xx");
}

if(isset($_POST['opis_vipa_form_add'])) {
  $func->help_menu_vip_nowy($player->user, $dostep->SerwerHelpMenuKonfiguracja);
  header("Location: ?x=$x&xx=$xx&edycja=$edycja_id&konfiguruj=opis_vipa");
}
if(isset($_POST['opis_vipa_form_zapisz'])) {
  $func->help_menu_vip_zapisz($player->user, $dostep->SerwerHelpMenuKonfiguracja);
  header("Location: ?x=$x&xx=$xx&edycja=$edycja_id&konfiguruj=opis_vipa");
}
if(isset($_POST['opis_vipa_kolejonosc_up'])) {
  $func->help_menu_vip_kolejnosc('up', $player->user, $dostep->SerwerHelpMenuKonfiguracja);
  header("Location: ?x=$x&xx=$xx&edycja=$edycja_id&konfiguruj=opis_vipa");
}
if(isset($_POST['opis_vipa_kolejonosc_down'])) {
  $func->help_menu_vip_kolejnosc('down', $player->user, $dostep->SerwerHelpMenuKonfiguracja);
  header("Location: ?x=$x&xx=$xx&edycja=$edycja_id&konfiguruj=opis_vipa");
}
if(isset($_POST['opis_vipa_usun'])){
 $func->help_menu_vip_usun($player->user, $dostep->SerwerHelpMenuKonfiguracja);
 header("Location: ?x=$x&xx=$xx&edycja=$edycja_id&konfiguruj=opis_vipa");
}

if(isset($_POST['komenda_form_add'])) {
  $func->help_menu_komenda_nowy($player->user, $dostep->SerwerHelpMenuKonfiguracja);
  header("Location: ?x=$x&xx=$xx&edycja=$edycja_id&konfiguruj=lista_komend");
}
if(isset($_POST['komenda_form_zapisz'])) {
  $func->help_menu_komenda_zapisz($player->user, $dostep->SerwerHelpMenuKonfiguracja);
  header("Location: ?x=$x&xx=$xx&edycja=$edycja_id&konfiguruj=lista_komend");
}
if(isset($_POST['komenda_kolejonosc_up'])){
  $func->help_menu_komenda_kolejnosc('up', $player->user, $dostep->SerwerHelpMenuKonfiguracja);
  header("Location: ?x=$x&xx=$xx&edycja=$edycja_id&konfiguruj=lista_komend");
}
if(isset($_POST['komenda_kolejonosc_down'])){
  $func->help_menu_komenda_kolejnosc('down', $player->user, $dostep->SerwerHelpMenuKonfiguracja);
  header("Location: ?x=$x&xx=$xx&edycja=$edycja_id&konfiguruj=lista_komend");
}
if(isset($_POST['komenda_usun'])){
 $func->help_menu_komenda_usun($player->user, $dostep->SerwerHelpMenuKonfiguracja);
 header("Location: ?x=$x&xx=$xx&edycja=$edycja_id&konfiguruj=lista_komend");
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
$wl_wyl_array = array(1 => 'Włączony', 0 => 'Wyłączony');
?>

<? if(!empty($edycja_id)): ?>
<div class="row">
  <div class="col-xs-12">
    <div class="box box">
      <div class="box-header">
        <h3 class="box-title">Edycja</h3>
        <div class="pull-right box-tools">
        </div>
      </div>
      <div class="box-body">
        <? $acp_r_d = row("SELECT * FROM `acp_serwery_helpmenu` WHERE `id` = $edycja_id LIMIT 1;"); ?>
        <? if(empty($konfiguruj)): ?>
        <form method='post' action='<?= "?x=$x&xx=$xx&edycja=$edycja_id"; ?>'>
          <input type='hidden' name='id' value='<?= $acp_r_d->id ?>'>
          <input type='hidden' name='serwer' value='<?= $acp_r_d->serwer_id ?>'>
          <p><div class='form-group input-group'><span class='input-group-addon'>Serwer</span><select class="form-control" name="e_serwerid" disabled>
              <?
              echo '<option value="'.$acp_r_d->serwer_id.'">'.$serwer_array[$acp_r_d->serwer_id].'</option>';
              foreach ($serwer_array as $key => $value):
                if($acp_r_d->serwer_id != $key)
                echo '<option value="'.$key.'">'.$value.'</option>';
              endforeach;
              ?>
            </select>
          </div></p>
          <p><div class='form-group input-group'><span class='input-group-addon'>Lista Serwerów</span>
            <select class="form-control" name="lista_serwerow">
              <?
              echo '<option value="'.$acp_r_d->lista_serwerow.'">'.$wl_wyl_array[$acp_r_d->lista_serwerow].'</option>';
              foreach ($wl_wyl_array as $key => $value):
                if($acp_r_d->lista_serwerow != $key)
                echo '<option value="'.$key.'">'.$value.'</option>';
              endforeach;
              ?>
            </select>
          </div></p>
          <p><div class='form-group input-group'><span class='input-group-addon'>Lista Adminów</span>
            <select class="form-control" name="lista_adminow">
              <?
              echo '<option value="'.$acp_r_d->lista_adminow.'">'.$wl_wyl_array[$acp_r_d->lista_adminow].'</option>';
              foreach ($wl_wyl_array as $key => $value):
                if($acp_r_d->lista_adminow != $key)
                echo '<option value="'.$key.'">'.$value.'</option>';
              endforeach;
              ?>
            </select>
          </div></p>
          <p><div class='form-group input-group'><span class='input-group-addon'>Opis Vipa</span>
            <select class="form-control" name="opis_vipa">
              <?
              echo '<option value="'.$acp_r_d->opis_vipa.'">'.$wl_wyl_array[$acp_r_d->opis_vipa].'</option>';
              foreach ($wl_wyl_array as $key => $value):
                if($acp_r_d->opis_vipa != $key)
                echo '<option value="'.$key.'">'.$value.'</option>';
              endforeach;
              ?>
            </select>
          </div></p>
          <p><div class='form-group input-group'><span class='input-group-addon'>Lista Komend</span>
            <select class="form-control" name="lista_komend">
              <?
              echo '<option value="'.$acp_r_d->lista_komend.'">'.$wl_wyl_array[$acp_r_d->lista_komend].'</option>';
              foreach ($wl_wyl_array as $key => $value):
                if($acp_r_d->lista_komend != $key)
                echo '<option value="'.$key.'">'.$value.'</option>';
              endforeach;
              ?>
            </select>
          </div></p>
          <p><div class='form-group input-group'><span class='input-group-addon'>Statystyki</span>
            <select class="form-control" name="statystyki">
              <?
              echo '<option value="'.$acp_r_d->statystyki.'">'.$wl_wyl_array[$acp_r_d->statystyki].'</option>';
              foreach ($wl_wyl_array as $key => $value):
                if($acp_r_d->statystyki != $key)
                echo '<option value="'.$key.'">'.$value.'</option>';
              endforeach;
              ?>
            </select>
          </div></p>
          <p><input name='edycja_from' class='btn btn-primary btn btn-block' type='submit' value='Edytuj'/></p>
        </form>
        <? endif; ?>

        <? if($konfiguruj == 'opis_vipa'): ?>
        <div class="col-xs-12">
          <table class="table table-condensed">
            <tbody>
              <tr>
                <th class="col-xs-1">#</th>
                <th class="col-xs-8">Tekst</th>
                <th class="col-xs-3"></th>
              </tr>
              <? $opis_vipa_q = all("SELECT * FROM `acp_serwery_helpmenu_vip` WHERE `helpmenu_id` = $edycja_id ORDER BY `kolejnosc` ASC");?>
              <?php foreach ($opis_vipa_q as $key): ?>
                <tr>
                  <form method='post' action='<?= "?x=$x&xx=$xx&edycja=$acp_r_d->id"; ?>'>
                    <input type="hidden" name="id" value="<?= $key->id ?>">
                    <input type="hidden" name="helpmenu_id" value="<?= $key->helpmenu_id ?>">
                    <input type="hidden" name="serwer_id" value="<?= $key->serwer_id ?>">

                    <td><input type="number" class="form-control" value="<?= $key->kolejnosc ?>" disabled></td>
                    <td><input type="text" class="form-control" name="tekst" value="<?= $key->tekst ?>" ></td>
                    <td>
                      <div class="btn-group">
                        <input name='opis_vipa_form_zapisz' type="submit" class="btn btn-default" value='Zapisz'>
                        <input name='opis_vipa_kolejonosc_up' type="submit" class="btn btn-default" value='UP'>
                        <input name='opis_vipa_kolejonosc_down' type="submit" class="btn btn-default" value='DOWN'>
                        <input name='opis_vipa_usun' type="submit" class="btn btn-danger" value='Usuń'>
                      </div>
                    </td>
                  </form>
                </tr>
              <?php endforeach; ?>
              <tr>
                <form method='post' action='<?= "?x=$x&xx=$xx&edycja=$acp_r_d->id"; ?>'>
                  <input type="hidden" name="id" value="<?= $key->id ?>">
                  <input type="hidden" name="helpmenu_id" value="<?= $acp_r_d->id ?>">
                  <input type="hidden" name="serwer_id" value="<?= $acp_r_d->serwer_id ?>">

                  <td><input type="number" class="form-control" disabled></td>
                  <td><input type="text" class="form-control" name="tekst"></td>
                  <td>
                    <input name='opis_vipa_form_add' type="submit" class="btn btn-default" value='Dodaj'>
                  </td>
                </form>
              </tr>
            </tbody>
          </table>
        </div>
        <? endif; ?>
        <? if($konfiguruj == 'lista_komend'): ?>
        <div class="col-xs-12">
          <table class="table table-condensed">
            <tbody>
              <tr>
                <th class="col-xs-1">#</th>
                <th class="col-xs-4">Komenda</th>
                <th class="col-xs-4">Tekst</th>
                <th class="col-xs-3"></th>
              </tr>
              <? $lista_komend_q = all("SELECT * FROM `acp_serwery_helpmenu_komendy` WHERE `helpmenu_id` = $edycja_id ORDER BY `kolejnosc` ASC");?>
              <?php foreach ($lista_komend_q as $key):?>
                <tr>
                  <form method='post' action='<?= "?x=$x&xx=$xx&edycja=$acp_r_d->id"; ?>'>
                    <input type="hidden" name="id" value="<?= $key->id ?>">
                    <input type="hidden" name="helpmenu_id" value="<?= $key->helpmenu_id ?>">
                    <input type="hidden" name="serwer_id" value="<?= $key->serwer_id ?>">

                    <td><input type="number" class="form-control" value="<?= $key->kolejnosc ?>" disabled></td>
                    <td><input type="text" class="form-control" name="komenda" value="<?= $key->komenda ?>" ></td>
                    <td><input type="text" class="form-control" name="tekst" value="<?= $key->tekst ?>" ></td>
                    <td>
                      <div class="btn-group">
                        <input name='komenda_form_zapisz' type="submit" class="btn btn-default" value='Zapisz'>
                        <input name='komenda_kolejonosc_up' type="submit" class="btn btn-default" value='UP'>
                        <input name='komenda_kolejonosc_down' type="submit" class="btn btn-default" value='DOWN'>
                        <input name='komenda_usun' type="submit" class="btn btn-danger" value='Usuń'>
                      </div>
                    </td>
                  </form>
                </tr>
              <?php endforeach; ?>
              <tr>
                <form method='post' action='<?= "?x=$x&xx=$xx&edycja=$acp_r_d->id"; ?>'>
                  <input type="hidden" name="id" value="<?= $key->id ?>">
                  <input type="hidden" name="helpmenu_id" value="<?= $acp_r_d->id ?>">
                  <input type="hidden" name="serwer_id" value="<?= $acp_r_d->serwer_id ?>">

                  <td><input type="number" class="form-control" disabled></td>
                  <td><input type="text" class="form-control" name="komenda"></td>
                  <td><input type="text" class="form-control" name="tekst"></td>
                  <td>
                    <input name='komenda_form_add' type="submit" class="btn btn-default" value='Dodaj'>
                  </td>
                </form>
              </tr>
            </tbody>
          </table>
        </div>
        <? endif; ?>

      </div>
    </div>
  </div>
</div>
<? endif; ?>

	<div class="row">
		<div class="col-lg-8">
			<div class="box box">
				<div class="box-header">
				  <h3 class="box-title">Help Menu</h3>
				  <div class="pull-right box-tools">
				  </div>
				</div>
				<div class="box-body">
          <table data-page-length='10' id="example" class="table table-bordered table-striped" width="100%">
						<thead>
							<tr>
                <th>ID</th>
								<th>Serwer</th>
								<th>Lista Serwerów</th>
								<th>Lista Adminów</th>
								<th>Opis Vipa</th>
								<th>Lista Komend</th>
								<th>Statytyki</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
						<?
            $acp_lista_q = all("SELECT *, `serwer_id` AS `serwer_id_table`, (SELECT `mod` FROM `acp_serwery` WHERE `serwer_id` = `serwer_id_table`) AS serwer_nazwa FROM `acp_serwery_helpmenu`");
  					foreach($acp_lista_q as $acp_r){
              $acp_r->serwer_nazwa = ($acp_r->serwer_id_table==0) ? 'Wszystkie' : $acp_r->serwer_nazwa ;
              $acp_r->serwer_nazwa = (empty($acp_r->serwer_nazwa)) ? '<i>Serwer nie istnieje</i>' : $acp_r->serwer_nazwa;
						?>
            <tr class="odd gradeX">
              <td><?= $acp_r->id ?></td>
							<td><?= $acp_r->serwer_nazwa ?></td>
							<td><?= $wl_wyl_array[$acp_r->lista_serwerow] ?></td>
							<td><?= $wl_wyl_array[$acp_r->lista_adminow] ?></td>
							<td><?= $wl_wyl_array[$acp_r->opis_vipa] ?> <a href="<?= "?x=$x&xx=$xx&edycja=$acp_r->id&konfiguruj=opis_vipa" ?>"><span class="label label-primary">Konfiguruj</span></a></td>
							<td><?= $wl_wyl_array[$acp_r->lista_komend] ?> <a href="<?= "?x=$x&xx=$xx&edycja=$acp_r->id&konfiguruj=lista_komend" ?>"><span class="label label-primary">Konfiguruj</span></a></td>
							<td><?= $wl_wyl_array[$acp_r->statystyki] ?></td>
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
    $ostatnia_aktualizacja = $func->ostatnia_aktualizacja($xx);
    $kolejna_aktualizacja = $func->kolejna_aktualizacja($xx);
    $serwery_aktualizowane = $func->serwery_aktualizowane();
    ?>
    <div class="col-lg-4 col-xs-12">
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
    <div class="col-lg-4 col-xs-12">
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
    <!-- okno wyskakujace -->
    <div class="modal fade" id="dodaj-serwer">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Utwórz <b>Help Menu</b> dla serwera</h4>
          </div>
          <div class="modal-body">
            <form method='post' action='<?= "?x=$x&xx=$xx"; ?>'>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Serwer</span>
                  <select class="form-control" name="serwer">
                    <?
                    $acp_wybor_serwera_q = all("SELECT `serwer_id`, `mod`, `nazwa` FROM `acp_serwery`");
                    foreach($acp_wybor_serwera_q as $acp_wybor_serwera){
                    ?>
                      <option value="<?= $acp_wybor_serwera->serwer_id ?>"><?= $acp_wybor_serwera->nazwa ?> ( <?= $acp_wybor_serwera->mod ?> )</option>
                    <? } ?>
                  </select>
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Lista Serwerów</span>
                  <select class="form-control" name="lista_serwerow">
                    <option value="1">Włączony</option>
                    <option value="0">Wyłączony</option>
                  </select>
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Lista Adminów</span>
                  <select class="form-control" name="lista_adminow">
                    <option value="1">Włączony</option>
                    <option value="0">Wyłączony</option>
                  </select>
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Opis Vipa</span>
                  <select class="form-control" name="opis_vipa">
                    <option value="1">Włączony</option>
                    <option value="0">Wyłączony</option>
                  </select>
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Lista Komend</span>
                  <select class="form-control" name="lista_komend">
                    <option value="1">Włączony</option>
                    <option value="0">Wyłączony</option>
                  </select>
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Statystyki</span>
                  <select class="form-control" name="statystyki">
                    <option value="1">Włączony</option>
                    <option value="0">Wyłączony</option>
                  </select>
                </div>
              </p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Zamknij</button>
              <button type="input" class="btn btn-primary" name="nowy_rekord">Dodaj</button>
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
<?= js_table_one('#example'); ?>
</body>
</html>
