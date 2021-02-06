<?
$func = getClass('Account');
$wpisy_funkcje = getClass('Wpisy');
?>
<div class="content-wrapper">
<section class="content">
  <div class="row">
	 <section class="col-lg-12">
		<p><? if(!empty($_SESSION['msg'])){ echo $_SESSION['msg']; unset($_SESSION['msg']); } ?></p>
	 </section >
  </div>
  <?
  $id = (int)$_GET['id'];
  $id = $func->user($id, $player->user);

  $user = row("SELECT `user`, `login`, `role`, `grupa`, `last_login`, `data_rejestracji`, `urodziny`, `banned`, `cash`, `ulubiony_serwer`, `lokalizacja`, `wyksztalcenie`, `steam`, `steam_update`, `steam_avatar`, `steam_login`, (SELECT `nazwa` FROM `acp_serwery` WHERE `serwer_id` = `ulubiony_serwer` LIMIT 1) AS `ulubiony_serwer_nazwa`, (SELECT `nazwa` FROM `acp_users_grupy` WHERE `id` = `grupa` lIMIT 1) AS `nazwa_grupy`, (SELECT COUNT(*) FROM `acp_wpisy` WHERE `u_id` = `user`) AS `ilosc_wpisow` FROM `acp_users` WHERE `user` = $id;");

  tytul_strony("Profil: $user->login");

  if(empty($user->steam_avatar)) { $user->steam_avatar = './www/img/av_default.jpg'; }
  if(empty($user->ulubiony_serwer_nazwa)) { $user->ulubiony_serwer_nazwa = 'brak danych'; }
  if(empty($user->wyksztalcenie)) { $user->wyksztalcenie = 'brak danych'; }
  if(empty($user->lokalizacja)) { $user->lokalizacja = 'brak danych'; }


  if(isset($_POST['user_edytuj'])){
    $_POST[haslo] = md5($_POST[haslo]);

    if($_POST[haslo] != $player->pass) {
      $_SESSION['msg'] = komunikaty("Nie poprawne hasło. Aby zedytować profil wpisz poprawne hasło logowania..", 4);
    }
    else {
      $_POST[steam] = toCommunityID($_POST[steam]);
      $_POST[new_haslo] = real_string($_POST[new_haslo]);

      if ($_POST[new_haslo] == '') {
        $_POST[new_haslo] = $player->pass;
      }
      else {
        $_POST[new_haslo] = md5($_POST[new_haslo]);
      }

      query("UPDATE `acp_users` SET `pass` = '$_POST[new_haslo]', `urodziny` ='$_POST[urodziny]', `ulubiony_serwer` = '$_POST[ulubiony_serwer]', `wyksztalcenie` = '$_POST[wyksztalcenie]', `lokalizacja` = '$_POST[lokalizacja]', `steam` = '$_POST[steam]' WHERE `user` = $player->user;");
      $_SESSION['msg'] = komunikaty("Twoje ustawienia zostały zmienione", 1);
    }
    header("Location: ?x=$x&id=$user->user");
  }
  ?>
  <div class="row">
  	<div class="col-md-3">
  	  <div class="box box-primary">
  		<div class="box-body box-profile">
  		  <img class="profile-user-img img-responsive img-circle" src="<?= $user->steam_avatar ?>" alt="User profile picture">
  		  <h3 class="profile-username text-center"><?= $user->login; ?> <p><small>(<?= $user->steam_login; ?>)</small></p></h3>
  		  <p class="text-muted text-center"><?= $user->nazwa_grupy; ?></p>
  		  <ul class="list-group list-group-unbordered">
    			<li class="list-group-item">
    			  <b>Ilość Wpisów</b> <a class="pull-right"><?= $user->ilosc_wpisow ?></a>
    			</li>
    			<li class="list-group-item">
    			  <b>Zadań</b> <a class="pull-right">X</a>
    			</li>
    			<li class="list-group-item">
    			  <b>Usług</b> <a class="pull-right">X</a>
    			</li>
  		  </ul>
  		  <a href="?x=wiadomosci&xx=wiadomosc&&to=<?= $user->login ?>" class="btn btn-primary btn-block"><b>Wiadomość</b></a>
  		  <a href="https://steamcommunity.com/profiles/<?= $user->steam ?>" target="_blank" class="btn bg-navy btn-block"><b>Profil Steam</b></a>
  		</div>
  	  </div>

  	  <div class="box box-primary">
  		<div class="box-header with-border">
  		  <h3 class="box-title">O mnie</h3>
  		</div>
  		<div class="box-body">
  		  <strong><i class="fa fa-book margin-r-5"></i> Wyksztalcenie</strong>
  		  <p class="text-muted"><?= $user->wyksztalcenie; ?></p>

  		  <hr>

  		  <strong><i class="fa fa-map-marker margin-r-5"></i> Lokalizacja</strong>
  		  <p class="text-muted"><?= $user->lokalizacja; ?></p>

  		  <hr>

  		  <strong><i class="fa fa-pencil margin-r-5"></i> Data Urodzin</strong>
  		  <p class="text-muted"><?= $user->urodziny; ?></p>

  		</div>
  	  </div>
  	</div>
  	<div class="col-md-9">
  	  <div class="nav-tabs-custom">
  		<ul class="nav nav-tabs">
  		  <li class="active"><a href="#activity" data-toggle="tab">Aktywność</a></li>
  		  <li><a href="#detale" data-toggle="tab">Detale</a></li>
        <? if($user->user == $player->user): ?>
          <li><a href="#settings" data-toggle="tab">Ustawienia</a></li>
        <? endif; ?>
        <? if($player->role == 1): ?>
          <li><a href="#dostep" data-toggle="tab">Dostęp</a></li>
        <? endif; ?>
  		</ul>
  		<div class="tab-content">
  		  <div class="active tab-pane" id="activity">
          <?
          $wpisy = all("SELECT *,
        	(SELECT `steam_avatar` FROM `acp_users` WHERE `user` = `u_id`) AS `steam_avatar`,
        	(SELECT `login` FROM `acp_users` WHERE `user` = `u_id`) AS `login`,
        	(SELECT `steam_login` FROM `acp_users` WHERE `user` = `u_id`) AS `steam_login`,

        	(SELECT `nazwa` FROM `acp_wpisy_kategorie` WHERE `id` = `kategoria`) AS `kategoria_nazwa`,
          (SELECT COUNT(*) FROM `acp_wpisy_komentarze` WHERE `wpis_id` = `id`) AS `komentarzy`
        	FROM `acp_wpisy` WHERE `u_id` = $user->user ORDER BY `id` DESC LIMIT 5");
          if(is_array($wpisy)):
            foreach($wpisy as $wpisyy):
              $avatar = $wpisy_funkcje->dashbord_czy_puste_av($wpisyy->steam_avatar);
              $login = $wpisy_funkcje->dashbord_czy_puste_login($wpisyy->steam_login, $wpisyy->login);
          ?>
          <div class="post">
    			  <div class="user-block">
      				<img class="img-circle img-bordered-sm" src="<?= $avatar?>" alt="user image">
    					<span class="username">
    					  <a href="?x=wpisy-one&wpisid=<?= $wpisyy->id ?>"><?= $login ?></a>
    					</span>
      				<span class="description"><?= $wpisyy->kategoria_nazwa; ?> - <?= czas_relatywny($wpisyy->data); ?></span>
    			  </div>
    			  <p> <?= str_replace(array("\r\n", "\n", "\r"), "<br>", limit_text(strip_tags($wpisyy->text), 1500)); ?></p>
    			  <ul class="list-inline">
    				  <li>
                <a href="?x=wpisy&xx=wpis&wpis=<?= clean($wpisyy->tytul); ?>&wpisid=<?= $wpisyy->id; ?>" class="link-black text-sm"><i class="fa fa-share margin-r-5"></i> Idź do wpisu..</a>
              </li>
      				<li class="pull-right">
      				  <i class="fa fa-comments-o margin-r-5"></i> <?= $wpisyy->komentarzy; ?> Komentarzy
              </li>
    			  </ul>
    			</div>
          <?
            endforeach;
          else:
          ?>
          <div class="post">
            <p>Brak zawartości do wyświetlenia..</p>
          </div>
        <? endif; ?>
  		  </div>

        <div class="tab-pane" id="detale">
          <div class="panel panel-default">
  				<div class="panel-heading">Dane</div>
    				<div class="panel-body">
    					<p><b>Login Steam:</b> <?= $user->steam_login; ?></p>
    					<p><b>Login ACP:</b> <?= $user->login; ?></p>
    					<p><b>Steam:</b> <?= toSteamID($user->steam); ?></p>
    					<p><b>SteamID64:</b> <?= $user->steam; ?></p>
    				</div>
  			  </div>
  			  <div class="panel panel-default">
  				<div class="panel-heading">Ulubiony Serwer</div>
    				<div class="panel-body">
    					<p><b>Ulubiony Serwer:</b> <?= $user->ulubiony_serwer_nazwa; ?></p>
    				</div>
  			  </div>
  			  <div class="panel panel-default">
  				<div class="panel-heading">Ostatnio Aktywny</div>
    				<div class="panel-body">
    					<p><b>Ostatnio widziany:</b> <?= czas_relatywny($user->last_login); ?></p>
    					<p><b>Rejestracja:</b> <?= czas_relatywny($user->data_rejestracji);  ?></p>
    					<p><b>Data Urodzenia:</b> <?= $user->urodziny;  ?></p>
    				</div>
  			  </div>
  			  <div class="panel panel-default">
          <? if($user->user == $player->user || $player->role == 1):?>
          <div class="panel-heading">Ostatnie logowania</div>
    				<div class="panel-body">
              <ul class="list-group">
              <?
              $ostatnie_logowania_q = all("SELECT * FROM `acp_users_login_logs` WHERE `user_id` = $user->user ORDER BY `date` DESC LIMIT 5");
              foreach ($ostatnie_logowania_q as $ostatnie_logowania):
                $ostatnie_logowania->poprawne_tekst = ($ostatnie_logowania->poprawne == 1) ? '' : 'Bład logowania';
              ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  <?= $func->get_browser_name($ostatnie_logowania->przegladarka) ?> | <?= $ostatnie_logowania->ip ?> - <?= czas_relatywny($ostatnie_logowania->date) ?>
                  <span class="badge badge-primary badge-pill"><?= $ostatnie_logowania->poprawne_tekst ?></span>
                </li>
              <? endforeach; ?>
              </ul>
    				</div>
  			  </div>
        <? endif;?>
        </div>


        <? if($user->user == $player->user): ?>
  		  <div class="tab-pane" id="settings">
  			<form method='post' class='form-horizontal'>
          <div class="form-group">
    				<label for="inputSkills" class="col-sm-2 control-label">Hasło</label>
    				<div class="col-sm-10">
    				  <input type="password" class="form-control" name="haslo">
    				</div>
  			  </div>
          <div class="form-group">
    				<label for="inputSkills" class="col-sm-2 control-label">Nowe Hasło</label>
    				<div class="col-sm-10">
    				  <input type="password" class="form-control" name="new_haslo">
    				</div>
  			  </div>
          <div class="form-group">
            <label for="inputExperience" class="col-sm-2 control-label">Steam</label>
            <div class="col-sm-10">
              <input class="form-control" type="number" name="steam" value="<?= $user->steam ?>">
            </div>
          </div>
          <div class="form-group">
  				  <label for="inputName" class="col-sm-2 control-label">Ulubiony Serwer</label>
    				<div class="col-sm-10">
    				  <select class="form-control" name="ulubiony_serwer">
                <option value="<?=$user->ulubiony_serwer ?>"><?= one("SELECT `nazwa` FROM `acp_serwery` WHERE `serwer_id` = $user->ulubiony_serwer LIMIT 1 "); ?></option>
                <?
                $ulubiony_serwer_q = all("SELECT `serwer_id`, `nazwa` FROM `acp_serwery` WHERE `serwer_id` != $user->ulubiony_serwer; ");
                foreach ($ulubiony_serwer_q as $ulubiony_serwer) {
                  echo "<option value='$ulubiony_serwer->serwer_id'>$ulubiony_serwer->nazwa</option>";
                }
                ?>
              </select>
    				</div>
  			  </div>
  			  <div class="form-group">
  				  <label for="inputExperience" class="col-sm-2 control-label">Wykształcenie</label>
    				<div class="col-sm-10">
    				  <textarea class="form-control" id="wyksztalcenie" placeholder="Szkoła X w Warszawie" name="wyksztalcenie"><?= $user->wyksztalcenie; ?></textarea>
    				</div>
  			  </div>
          <div class="form-group">
    				<label for="inputExperience" class="col-sm-2 control-label">Lokalizacja</label>
    				<div class="col-sm-10">
    				  <textarea class="form-control" id="lokalizacja" placeholder="Warszawa, Polska" name="lokalizacja"><?= $user->lokalizacja; ?></textarea>
    				</div>
  			  </div>
  			  <div class="form-group">
    				<label for="inputSkills" class="col-sm-2 control-label">Data Urodzenia</label>
    				<div class="col-sm-10">
    				  <input type="date" class="form-control" id="data" value="<?= $user->urodziny; ?>" name="urodziny">
    				</div>
  			  </div>

  			  <div class="form-group">
    				<div class="col-sm-offset-2 col-sm-10">
    				  <button name="user_edytuj" type="submit" class="btn btn-danger">Zapisz</button>
    				</div>
  			  </div>
  			</form>
  		  </div>
        <? endif; ?>
        <? if($player->role == 1): ?>
  		  <div class="tab-pane" id="dostep">
          <?
          $user->dostep = row("SELECT `moduly`, `dostep` FROM `acp_users_grupy` WHERE `id` = '$user->grupa' LIMIT 1");
          $user->dostep->moduly = json_decode($user->dostep->moduly);
          $user->dostep->dostep = json_decode($user->dostep->dostep)[0];
          ?>
          <div class="panel panel-default">
            <div class="panel-heading">
              Dostęp - Moduły<br>
              <small>Lista modułów które są dostępne</small>
            </div>
            <div class="panel-body">
              <?
              foreach ($user->dostep->moduly as $value):
                $dane = row("SELECT `id`, `nazwa_wys`, `ikona`, `opis` FROM `acp_moduly` WHERE `nazwa` = '$value' LIMIT 1");
                $dane->opis = (empty($dane->opis)) ?'Brak opisu..' : $dane->opis;
              ?>
              <dl>
                <dt><i class="<?= $dane->ikona ?>"></i> <b><?= $dane->nazwa_wys ?></b></dt>
                <dd><?= $dane->opis ?></dd>
              </dl>
              <p>
                <ul class="list-unstyled">
                  <?
                  $dane_akcje = all("SELECT `akcja`, `akcja_wys`, `opis` FROM `acp_moduly_akcje` WHERE `modul_id` = $dane->id");
                  if(!empty($dane_akcje)):
                    $nr = 0;
                    foreach ($dane_akcje as $akcja):
                      if($user->dostep->dostep->{$akcja->akcja} == 1):
                        $nr++;
                  ?>
                    <li> <?= $nr ?>. <?= $akcja->akcja_wys ?> - <?= $akcja->opis ?></li>
                  <?  endif;
                    endforeach;
                  endif; ?>
                </ul>
              </p>
              <? endforeach; ?>

            </div>
          </div>
  		  </div>
        <? endif; ?>
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
<!-- FastClick -->
<script src="./www/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="./www/dist/js/adminlte.min.js"></script>
