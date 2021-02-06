<?
$func = getClass('Serwer');
$func = getClass('SerwerUstawienia');
?>
<style>
.example-modal .modal { position: relative; top: auto; bottom: auto; right: auto; left: auto; display: block; z-index: 1; }
.example-modal .modal { background: transparent !important; }
.btn-file { position: relative; overflow: hidden; }
.btn-file input[type=file] { position: absolute; top: 0; right: 0; min-width: 100%; min-height: 100%; font-size: 999px; text-align: right; filter: alpha(opacity=0); opacity: 0; background: red; cursor: inherit; display: block; }
input[readonly] { background-color: white !important; cursor: text !important; }
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
$edycja_id = (isset($_GET['edycja'])) ? (int)$_GET['edycja'] : null;
$cron = (isset($_GET['cron'])) ? (int)$_GET['cron'] : null;
$powiadomienie_id = (isset($_GET['powiadomienie_id'])) ? (int)$_GET['powiadomienie_id'] : null;

if(!empty($co) && !empty($id) && $co == "usun"){
  $func->usun((int)$id, $player->user, $dostep->SerwerUsun);
	header("Location: ?x=$x");
}
if(isset($_POST['edycja'])) {
  $func->edytuj($player->user, $dostep->SerwerEdytuj);
	header("Location: ?x=$x&edycja=$edycja_id");
}
if(isset($_POST['cron'])) {
  $func->pracezdalne($player->user, $dostep->SerwerCron);
	header("Location: ?x=$x&cron=$cron");
}
if(isset($_POST['nowy_serwer'])) {
  $func->dodaj($player->user, $dostep->SerwerDodaj);
  header("Location: ?x=$x");
}
if(isset($_FILES['nazwa_pliku'])){
  $func->serwerbanner($player->user, $dostep->SerwerEdytuj);
  header("Location: ?x=$x");
}

$users_list = array(0 => 'Brak');
$users_list_q = all("SELECT `user`, `login` FROM `acp_users`");
foreach($users_list_q as $value){
  $users_list[$value->user]="$value->login";
}

$tak_nie_array = array(1 => 'Tak', 0 => 'Nie', -1 => 'Zablokowane');
$mapy_plugin_array = array('UMC' => 'UMC Mapcycle', 'mapchooser' => 'Mapchooser Extended');
if(!empty($edycja_id)):
?>
<div class="row">
  <div class="col-xs-12">
    <? echo komunikat_and_powiadomienie($powiadomienie_id); ?>
    <div class="box box">
      <div class="box-header">
        <h3 class="box-title">Edycja Serwera</h3>
      </div>
      <div class="box-body">
        <? $acp_r_d = row("SELECT * FROM `acp_serwery` WHERE `serwer_id` = $edycja_id;"); ?>
        <form method='post'>
          <input type='hidden' name='id' value='<?= $acp_r_d->serwer_id ?>'>
          <input type='hidden' name='nazwa' value='<?= $acp_r_d->nazwa ?>'>
          <div class="col-xs-12">
            <p><div class='form-group input-group'><span class='input-group-addon'>ID</span><input class='form-control' type='number' value='<?= $acp_r_d->serwer_id ?>' disabled /></div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>Nazwa Serwera</span><input class='form-control' type='text' value='<?= $acp_r_d->nazwa ?>' disabled /></div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>Pozycja Serwera (Kolejność)</span><input class='form-control' type='number' name='e_istonosc' value='<?= $acp_r_d->istotnosc ?>'/></div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>Mod</span><input class='form-control' type='text' name='e_mod' value='<?= $acp_r_d->mod ?>'/></div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>IP</span><input class='form-control' type='text' name='e_ip' value='<?= $acp_r_d->ip ?>'/></div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>PORT</span><input class='form-control' type='text' name='e_port' value='<?= $acp_r_d->port ?>'/></div></p>
          </div>
          <div class="col-md-6 col-xs-12">
            <p>Podstawowe:</p>
            <p><div class='form-group input-group'><span class='input-group-addon'>Serwer Włączony</span>
              <select class="form-control" name="e_wlaczony">
                <?
                echo '<option value="'.$acp_r_d->serwer_on.'">'.$tak_nie_array[$acp_r_d->serwer_on].'</option>';
                foreach ($tak_nie_array as $key => $value):
                  if($acp_r_d->serwer_on != $key && $key != -1)
                  echo '<option value="'.$key.'">'.$value.'</option>';
                endforeach;
                ?>
              </select>
            </div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>Zdalna edycja plików?</span>
              <select class="form-control" name="e_cronjobs">
                <?
                echo '<option value="'.$acp_r_d->cronjobs.'">'.$tak_nie_array[$acp_r_d->cronjobs].'</option>';
                foreach ($tak_nie_array as $key => $value):
                  if($acp_r_d->cronjobs != $key && $key != -1)
                  echo '<option value="'.$key.'">'.$value.'</option>';
                endforeach;
                ?>
              </select>
            </div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>Prefix Sourcebans</span><input class='form-control' type='text' name='e_prefix_sb' value='<?= $acp_r_d->prefix_sb ?>'/></div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>Prefix HlStats</span><input class='form-control' type='text' name='e_prefix_hls' value='<?= $acp_r_d->prefix_hls ?>'/></div></p>

          </div>
          <div class="col-md-6 col-xs-12">
            <p>Połaczenie:</p>
            <p><div class='form-group input-group'><span class='input-group-addon'>RCON</span><input class='form-control' type='password' name='e_rcon' autocomplete='new-password'/></div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>FTP Login</span><input class='form-control' type='text' name='e_ftpu' value='<?= $acp_r_d->ftp_user ?>'/></div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>FTP Hasło</span><input class='form-control' type='password' name='e_ftpp' autocomplete='new-password'/></div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>FTP Host</span><input class='form-control' type='text' name='e_ftph' value='<?= $acp_r_d->ftp_host ?>'/></div></p>
          </div>
          <div class="col-md-6 col-xs-12">
            <p>Różne:</p>
            <p><div class='form-group input-group'><span class='input-group-addon'>Serwer Testowy</span>
              <select class="form-control" name="e_test_serwer">
                <?
                echo '<option value="'.$acp_r_d->test_serwer.'">'.$tak_nie_array[$acp_r_d->test_serwer].'</option>';
                foreach ($tak_nie_array as $key => $value):
                  if($acp_r_d->test_serwer != $key && $key != -1)
                  echo '<option value="'.$key.'">'.$value.'</option>';
                endforeach;
                ?>
              </select>
            </div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>IP Hlstats</span><input class='form-control' type='text' name='e_botip' value='<?= $acp_r_d->ip_bot_hlstats ?>'/></div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>Link GOTV</span><input class='form-control' type='text' name='e_gotvlink' value='<?= $acp_r_d->link_gotv ?>'/></div></p>
          </div>
          <div class="col-md-6 col-xs-12">
            <p>Osoby Odpowiedzialne:</p>
            <p><div class='form-group input-group'><span class='input-group-addon'>Junior Admin</span>
              <select class="form-control" name="e_junioradmin">
                <?
                echo '<option value="'.$acp_r_d->ser_a_jr.'">'.$users_list[$acp_r_d->ser_a_jr].'</option>';
                foreach ($users_list as $key => $value):
                  if($acp_r_d->ser_a_jr != $key)
                  echo '<option value="'.$key.'">'.$value.'</option>';
                endforeach;
                ?>
              </select>
            </div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>Opiekun</span>
              <select class="form-control" name="e_opiekun">
                <?
                echo '<option value="'.$acp_r_d->ser_a_opiekun.'">'.$users_list[$acp_r_d->ser_a_opiekun].'</option>';
                foreach ($users_list as $key => $value):
                  if($acp_r_d->ser_a_opiekun != $key)
                  echo '<option value="'.$key.'">'.$value.'</option>';
                endforeach;
                ?>
              </select>
            </div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>Chef Admin</span>
              <select class="form-control" name="e_copiekun">
                <?
                echo '<option value="'.$acp_r_d->ser_a_copiekun.'">'.$users_list[$acp_r_d->ser_a_copiekun].'</option>';
                foreach ($users_list as $key => $value):
                  if($acp_r_d->ser_a_copiekun != $key)
                  echo '<option value="'.$key.'">'.$value.'</option>';
                endforeach;
                ?>
              </select>
            </div></p>
          </div>
          <div class="col-md-12">
            <p><input name='edycja' class='btn btn-primary btn-block' type='submit' value='Edytuj'/></p>
          </div>
        </form>
        <form method="POST" enctype="multipart/form-data">
          <div class="col-md-12 col-xs-12">
            <p>Banner Serwera:</p>
            <?
              $serwer_banner_plik = "www/server_banner/$acp_r_d->serwer_id.png";
              $serwer_banner = file_exists($serwer_banner_plik);
              if(!$serwer_banner) {
                $serwer_banner_t = "Nie znaleziono bannera serwera..";
                $serwer_banner_png = "https://acp.sloneczny-dust.pl/www/server_banner/0.png";
              }
              else {
                $serwer_banner_t = "https://acp.sloneczny-dust.pl/$serwer_banner_plik";
                $serwer_banner_png = "https://acp.sloneczny-dust.pl/$serwer_banner_plik";

              }
            ?>

            <div class="col-md-6 col-xs-12">
              <p><img src='<?= $serwer_banner_png; ?>' class='img-fluid'></img></p>
            </div>
            <div class="col-md-6 col-xs-12">
              <p>Link:</p>
              <p class='text-muted well well-sm no-shadow' style='margin-top: 10px;'><?= $serwer_banner_png; ?></p>

              <p>
                <div class="input-group">
                  <span class="input-group-btn">
                    <span class="btn btn-default btn-file">
                      Wybierz Plik
                      <input name="nazwa_pliku" name="nazwa_pliku" type="file" id="image">
                      <input type="hidden" name="id" value="<?= $acp_r_d->serwer_id ?>">
                    </span>
                  </span>
                  <input readonly="readonly" placeholder="<?= $serwer_banner_png ?>" class="form-control" name="nazwa_pliku" size="35" type="text"/>
                </div>
              </p>
              <p class="help-block">Uwaga! Jeśli istnieje już obrazek zostanie on zastąpiony..</p>
              <p><input name='wgraj_grafike_mapy' class='btn btn-primary btn btn-block' type='submit' value='Prześlij'/></p>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?
endif;
if(!empty($cron)):
?>
<div class="row">
  <div class="col-xs-12">
    <div class="box box">
      <div class="box-header">
        <h3 class="box-title">Cronjobs</h3>
      </div>
      <div class="box-body">
        <? $acp_r_d = row("SELECT *, (SELECT `nazwa` FROM `acp_serwery` WHERE `serwer_id` = $cron LIMIT 1) AS `nazwa` FROM `acp_serwery_cronjobs` WHERE `serwer` = $cron;");
        $acp_r_d->rangi = ($acp_r_d->rangi) ?: 0;
        $acp_r_d->reklamy = ($acp_r_d->reklamy) ?: 0;
        $acp_r_d->mapy = ($acp_r_d->mapy) ?: 0;
        $acp_r_d->bazy = ($acp_r_d->bazy) ?: 0;
        $acp_r_d->cvary = ($acp_r_d->cvary) ?: 0;
        $acp_r_d->hextags = ($acp_r_d->hextags) ?: 0;
        ?>
        <form method='post'>
          <input type='hidden' name='id' value='<?= $cron ?>'>
          <div class="col-xs-12">
            <p><div class='form-group input-group'><span class='input-group-addon'>ID</span><input class='form-control' type='number' value='<?= $cron ?>' disabled /></div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>Nazwa Serwera</span><input class='form-control' type='text' value='<?= $acp_r_d->nazwa ?>' disabled /></div></p>
          </div>
          <div class="col-md-12 col-xs-12">
            <p><div class='form-group input-group'><span class='input-group-addon'>Katalog Główny</span><input class='form-control' type='text' name='katalog' value='<?= $acp_r_d->katalog ?>'/></div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>Typ połaczenia</span>
              <select class="form-control" name="typ_polaczenia">
                <?
                if($acp_r_d->typ_polaczenia == 'ftp') {
                  echo '
                    <option value="ftp">FTP</option>
                    <option value="sftp">SFTP</option>
                    ';
                }
                else{
                  echo '
                    <option value="sftp">SFTP</option>
                    <option value="ftp">FTP</option>
                    ';
                }
                ?>
              </select>
            </div></p>
          </div>
          <div class="col-md-6 col-xs-12">
            <p><div class='form-group input-group'><span class='input-group-addon'>Rangi [HexTags]</span>
              <select class="form-control" name="hextags">
                <?
                echo '<option value="'.$acp_r_d->hextags.'">'.$tak_nie_array[$acp_r_d->hextags].'</option>';
                foreach ($tak_nie_array as $key => $value):
                  if($acp_r_d->hextags != $key && $key != -1)
                  echo '<option value="'.$key.'">'.$value.'</option>';
                endforeach;
                ?>
              </select>
            </div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>Reklamy</span>
              <select class="form-control" name="reklamy">
                <?
                echo '<option value="'.$acp_r_d->reklamy.'">'.$tak_nie_array[$acp_r_d->reklamy].'</option>';
                foreach ($tak_nie_array as $key => $value):
                  if($acp_r_d->reklamy != $key && $key != -1)
                  echo '<option value="'.$key.'">'.$value.'</option>';
                endforeach;
                ?>
              </select>
            </div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>Bazy Danych</span>
              <select class="form-control" name="bazy">
                <?
                echo '<option value="'.$acp_r_d->bazy.'">'.$tak_nie_array[$acp_r_d->bazy].'</option>';
                foreach ($tak_nie_array as $key => $value):
                  if($acp_r_d->bazy != $key && $key != -1)
                  echo '<option value="'.$key.'">'.$value.'</option>';
                endforeach;
                ?>
              </select>
            </div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>Usługi</span>
              <select class="form-control" name="uslugi">
                <?
                echo '<option value="'.$acp_r_d->uslugi.'">'.$tak_nie_array[$acp_r_d->uslugi].'</option>';
                foreach ($tak_nie_array as $key => $value):
                  if($acp_r_d->uslugi != $key && $key != -1)
                  echo '<option value="'.$key.'">'.$value.'</option>';
                endforeach;
                ?>
              </select>
            </div></p>
          </div>
          <div class="col-md-6 col-xs-12">
            <p><div class='form-group input-group'><span class='input-group-addon'>Mapy</span>
              <select class="form-control" name="mapy">
                <?
                echo '<option value="'.$acp_r_d->mapy.'">'.$tak_nie_array[$acp_r_d->mapy].'</option>';
                foreach ($tak_nie_array as $key => $value):
                  if($acp_r_d->mapy != $key && $key != -1)
                  echo '<option value="'.$key.'">'.$value.'</option>';
                endforeach;
                ?>
              </select>
            </div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>Mapy Plugin</span>
              <select class="form-control" name="mapy_plugin">
                <?
                if(is_null($acp_r_d->mapy_plugin)):
                  echo '<option value="">Domyślny</option>';
                else:
                  echo '<option value="'.$acp_r_d->mapy_plugin.'">'.$mapy_plugin_array[$acp_r_d->mapy_plugin].'</option>';
                endif;

                foreach ($mapy_plugin_array as $key => $value):
                  if($acp_r_d->mapy_plugin != $key && $key != -1)
                  echo '<option value="'.$key.'">'.$value.'</option>';
                endforeach;
                ?>
              </select>
            </div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>Cvary</span>
              <select class="form-control" name="cvary">
                <?
                echo '<option value="'.$acp_r_d->cvary.'">'.$tak_nie_array[$acp_r_d->cvary].'</option>';
                foreach ($tak_nie_array as $key => $value):
                  if($acp_r_d->cvary != $key && $key != -1)
                  echo '<option value="'.$key.'">'.$value.'</option>';
                endforeach;
                ?>
              </select>
            </div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>Help Menu</span>
              <select class="form-control" name="helpmenu">
                <?
                echo '<option value="'.$acp_r_d->help_menu.'">'.$tak_nie_array[$acp_r_d->help_menu].'</option>';
                foreach ($tak_nie_array as $key => $value):
                  if($acp_r_d->help_menu != $key && $key != -1)
                  echo '<option value="'.$key.'">'.$value.'</option>';
                endforeach;
                ?>
              </select>
            </div></p>
          </div>
          <div class="col-md-12">
            <p><input name='cron' class='btn btn-primary btn-block' type='submit' value='Edytuj'/></p>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?
endif;
?>

	<div class="row">
		<div class="col-xs-12">
			<div class="box box">
				<div class="box-header">
				  <h3 class="box-title">Lista Serwerów</h3>
				  <div class="pull-right box-tools">
				  </div>
				</div>
				<div class="box-body">
          <table data-page-length='10' id="example" class="table table-bordered table-striped" width="100%">
						<thead>
							<tr>
                <th>Nr</th>
                <th>ID</th>
                <th>Nazwa</th>
                <th>Mod</th>
  							<th>IP:Port</th>
  							<th>Junior Admin</th>
  							<th>Opiekun</th>
  							<th>Chef Admin</th>
  							<th></th>
							</tr>
						</thead>
						<tbody>
						<?
            $acp_serwery_ustawienia_list = all("SELECT *,
  					(SELECT `login` FROM `acp_users` WHERE `user` = `ser_a_jr`) AS ser_jr,
  					(SELECT `login` FROM `acp_users` WHERE `user` = `ser_a_opiekun`) AS ser_opiekun,
  					(SELECT `login` FROM `acp_users` WHERE `user` = `ser_a_copiekun`) AS ser_copiekun
  					FROM `acp_serwery`;");
  					foreach($acp_serwery_ustawienia_list as $acp_r){
              $acp_r->test_serwer = ($acp_r->test_serwer == 0) ? '' : '<span class="btn btn-xs btn-warning">SERWER TESTOWY</span>';
              $acp_r->serwer_on = ($acp_r->serwer_on == 1) ? 'Włączony' : '<span class="btn btn-xs btn-danger">Nie</span>';
              $acp_r->cronjobs = ($acp_r->cronjobs == 1) ? '<span class="btn btn-xs btn-warning">Wyłączone</span>' : $acp_r->cronjobs;
              $acp_r->cronjobs = ($acp_r->cronjobs == -1) ? '<span class="btn btn-xs btn-danger">Zablokowane</span>' : 'Poprawne';

              $acp_r->jr_link = (empty($acp_r->ser_jr)) ? "<i>Brak</i>" : "<a href='?x=account&id=$acp_r->ser_a_jr'>$acp_r->ser_jr</a>";
              $acp_r->opiekun_link = (empty($acp_r->ser_opiekun)) ? "<i>Brak</i>" : "<a href='?x=account&id=$acp_r->ser_a_opiekun'>$acp_r->ser_opiekun</a>";
              $acp_r->copiekun_link = (empty($acp_r->ser_copiekun)) ? "<i>Brak</i>" : "<a href='?x=account&id=$acp_r->ser_a_copiekun'>$acp_r->ser_copiekun</a>";

              if($acp_r->status == 1) { $acp_r->status = '<i class="fa fa-circle text-red"></i>'; } else { $acp_r->status = '<i class="fa fa-circle text-green"></i>'; }
						?>
            <tr class="odd gradeX">
							<td><?= $acp_r->istotnosc; ?></td>
              <td><?= $acp_r->serwer_id; ?></td>
              <td>
                <?= $acp_r->test_serwer ?>
                <a href="?x=serwery_det&serwer_id=<?= $acp_r->serwer_id; ?>"><?= $acp_r->nazwa; ?></a><br>
                <b>Status:</b> <?= $acp_r->serwer_on; ?> <b>Połączenie FTP:</b> <?= $acp_r->cronjobs ?>
              </td>
							<td><?= $acp_r->mod; ?></td>
							<td><a href="steam://connect/<?= $acp_r->ip.":".$acp_r->port ?>/"><?= $acp_r->ip.":".$acp_r->port ?></a></td>
							<td><?= $acp_r->jr_link; ?></td>
							<td><?= $acp_r->opiekun_link; ?></td>
							<td><?= $acp_r->copiekun_link; ?></td>
              <td>
                <div class="btn-group">
                  <a href="<?= "?x=$x&edycja=$acp_r->serwer_id" ?>" class="btn btn-primary" role="button" aria-pressed="true"><i class="fa fa-edit"></i> Edytuj</a>
                  <a href="<?= "?x=$x&cron=$acp_r->serwer_id" ?>" class="btn btn-primary" role="button" aria-pressed="true"><i class="fa fa-cube"></i> Prace</a>
                  <a href="<?= "?x=$x&co=usun&id=$acp_r->serwer_id" ?>" class="btn btn-danger" role="button" aria-pressed="true"><i class="fa fa-times"></i> Usuń</a>
                </div>
              </td>
						</tr>
						<? } ?>
						</tbody>
					</table>
				</div>
        <div class="box-footer clearfix no-border">
          <button type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#dodaj-serwer">
            <i class="fa fa-plus"></i>
            Dodaj Serwer</button>
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
            <h4 class="modal-title">Dodaj Serwer</h4>
          </div>
          <div class="modal-body">
            <form name='nowy_serwer' method='post' action='?x=<?= $x ?>'>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Gra</span>
                  <input class="form-control" name="new_gra" value="CSGO">
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Mod</span>
                  <input class="form-control" name="new_mod">
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>IP</span>
                  <input class="form-control" name="new_ip">
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Port</span>
                  <input class="form-control" name="new_port">
                </div>
              </p>
              <p>
                <div class="checkbox">
                  <label>
                    <input type="checkbox" name="test_serwer"> Serwer testowy <br><small>Serwer nie jest wyświetalny wszelkich listach publicznych.</small>
                  </label>
                </div>
              </p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Zamknij</button>
              <button type="input" name="nowy_serwer" class="btn btn-primary">Zapisz</button>
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
