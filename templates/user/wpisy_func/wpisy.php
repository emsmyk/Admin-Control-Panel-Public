<?
tytul_strony("Wpisy");

$dash = getClass('Wpisy');

$limit =  $acp_system['wpisy_ilosc_wpisow']; //Liczba wpisów na jednej stronie
$limit_komentarzy = $acp_system['wpisy_ilosc_komentarzy'];
$strona = $_GET['str']; // Pobranie numeru strony

if (!isset($strona)) {
 $limit1 = 0;
 $limit2 = $limit;
 $nr_kolejnej_strony = 2;
} else {
 $limit1 = $limit * $strona - $limit;
 $limit2 = $limit;
 $nr_kolejnej_strony = $strona + 1;
}
?>
<div class="content-wrapper">
<section class="content">
  <div class="row">
	<section class="col-lg-12">
		<p><? if(!empty($_SESSION['msg'])){ echo $_SESSION['msg']; unset($_SESSION['msg']); } ?></p>
	</section >
  </div>

<?
if(isset($_POST['komentarz'])) {
	$dash->komentarz($_POST['komentarz'], $player->user);
  header("Location: ?x=$x&xx=$xx");
}
if(isset($_POST['nowy_wpis'])) {
	$dash->nowy_wpis($_POST['nowy_wpis'], $player->user);
  header("Location: ?x=$x&xx=$xx");
}
?>
  <div class="row">
	<div class="col-lg-12">
		<div class="box box-info">
			<div class="box-header with-border">
				<h3 class="box-title">Dodaj Wpis</h3>
			</div>
			<div class="box-body">
				<form name='nowy_wpis' method='post' action='?x=wpisy'>
					<div class='form-group input-group'><span class='input-group-addon'>Kategoria</span>
          <select class="form-control" name="nowy_kategoria">
					 <option value="0">Brak</option>
					 <? $new_kategoria_q = all("SELECT `id`, `nazwa` FROM `acp_wpisy_kategorie` WHERE `id` != 0;");
					 foreach($new_kategoria_q as $new_kategoria){ ?>
					 	<option value="<?= $new_kategoria->id ?>"><?= $new_kategoria->nazwa ?></option>
					 <? } ?>
					</select>
          </div>
					<p><div class='form-group input-group'><span class='input-group-addon'>Tytuł</span><input class='form-control' type='text' name='nowy_tytul'/></div></p>
					<p><textarea class="form-control" rows="3" name="nowy_tekst"></textarea></p>
					<p><input name='nowy_wpis' class='btn btn-primary btn btn-block' type='submit' value='Dodaj nowy wpis'/></p>
				</form>
			</div>
		</div>
	</div>
	<div class="col-lg-8">
  	<? $acp_dashboard_k1_q = all("SELECT *,
  	(SELECT `steam_avatar` FROM `acp_users` WHERE `user` = `u_id`) AS steam_avatar,
  	(SELECT `login` FROM `acp_users` WHERE `user` = `u_id`) AS login,
  	(SELECT `steam_login` FROM `acp_users` WHERE `user` = `u_id`) AS steam_login,

  	(SELECT `nazwa` FROM `acp_wpisy_kategorie` WHERE `id` = `kategoria`) AS kategoria_nazwa
  	FROM `acp_wpisy` ORDER BY `id` DESC LIMIT $limit1, $limit2;");
  	foreach($acp_dashboard_k1_q as $acp_k1){
  		$avatar = $dash->dashbord_czy_puste_av($acp_k1->steam_avatar);
  		$login = $dash->dashbord_czy_puste_login($acp_k1->steam_login, $acp_k1->login);

  		$komentarzy = one("SELECT COUNT(`id`) FROM `acp_wpisy_komentarze` WHERE `wpis_id` = $acp_k1->id");
  		$komentowalo = one("SELECT COUNT(DISTINCT `user_id`) FROM `acp_wpisy_komentarze` WHERE `wpis_id` = $acp_k1->id");

      $acp_k1->kategoria_nazwa = (empty($acp_k1->kategoria_nazwa)) ? 'Brak kategorii' : $acp_k1->kategoria_nazwa;
  	?>
	  <div class="box box-widget">
		<div class="box-header with-border">
		  <div class="user-block">
			<img class="img-circle" src="<?= $avatar; ?>">
			<span class="username"><a href="?x=account&id=<?= $acp_k1->u_id; ?>"><?= $login; ?></a> - <span style="word-wrap:break-word;"><?= $acp_k1->tytul; ?></span></span>
			<span class="description"><a href="?x=wpisy&xx=category&nazwa=<?= $acp_k1->kategoria_nazwa; ?>&id=<?= $acp_k1->kategoria ?>"><?= $acp_k1->kategoria_nazwa; ?></a> - <?= czas_relatywny($acp_k1->data); ?></span>
		  </div>
		  <div class="box-tools">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
			</button>
			<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
		  </div>
		</div>
		<div class="box-body">
		  <p style="word-wrap:break-word;"><?= $acp_k1->text; ?></p>
		  <a href="?x=wpisy&xx=wpis&wpis=<?= clean($acp_k1->tytul); ?>&wpisid=<?= $acp_k1->id; ?>"><button type="button" class="btn btn-default btn-xs"><i class="fa fa-share"></i> Czytaj całość</button></a>
		  <span class="pull-right text-muted"><?= $komentarzy ?> komentarzy - <?= $komentowalo ?> komentujacych</span>
		</div>
		<div class="box-footer box-comments">
			<?
			$ilosc_comentarzy = 0;
			$acp_dashboard_k1_com_liczba = one("SELECT COUNT(*) FROM `acp_wpisy_komentarze` WHERE `wpis_id` = $acp_k1->id;");
			if($acp_dashboard_k1_com_liczba > $limit_komentarzy) { $acp_dashboard_k1_offset = $acp_dashboard_k1_com_liczba - $limit_komentarzy; } else { $acp_dashboard_k1_offset = 0; }
			$acp_dashboard_k1_com_q = all("SELECT *,
			(SELECT `steam_avatar` FROM `acp_users` WHERE `user` = `user_id`) AS koemntujacy_steam_avatar,
			(SELECT `login` FROM `acp_users` WHERE `user` = `user_id`) AS koemntujacy_login,
			(SELECT `steam_login` FROM `acp_users` WHERE `user` = `user_id`) AS koemntujacy_steam_login

			FROM `acp_wpisy_komentarze` WHERE `wpis_id` = $acp_k1->id ORDER BY `id` ASC LIMIT $limit_komentarzy OFFSET $acp_dashboard_k1_offset;");
      if(is_array($acp_dashboard_k1_com_q))
			foreach($acp_dashboard_k1_com_q as $acp_k1_com){
				$com_avatar = $dash->dashbord_czy_puste_av($acp_k1_com->koemntujacy_steam_avatar);
				$com_login = $dash->dashbord_czy_puste_login($acp_k1_com->koemntujacy_steam_login, $acp_k1_com->koemntujacy_login);
				$ilosc_comentarzy++;
			?>
		  <div class="box-comment">
			<img class="img-circle img-sm" src="<?= $com_avatar; ?>" alt="User Image">

			<div class="comment-text">
				  <span class="username">
					<a href="?x=account&id=<?= $acp_k1_com->user_id; ?>"><?= $com_login;  ?></a>
					<span class="text-muted pull-right"><?=  czas_relatywny($acp_k1_com->data); ?></span>
				  </span>
			  <?=  $acp_k1_com->text; ?>
			</div>
		  </div>
  		<? } ?>
		</div>
		<? if($acp_k1->closed == 1) {?>
		<div class="box-footer">
		 <div class="input-group-btn">
		  <button type="button" class="btn btn-danger btn-block btn-xs">Zablokowany</button>
		 </div>
		</div>
		<? } else {?>
		<div class="box-footer">
		 <form name='komentarz' method='post' action='<?= "?x=$x&xx=$xx" ?>'>
		  <div class="input-group">
			<input type='hidden' name='komentarz_id' value='<?= $acp_k1->id; ?>'>
			<input type="text" class="form-control" name='komentarz_tekst' placeholder="Wpisz wiadomość...">
			<div class="input-group-btn">
			<input name='komentarz' class='btn btn-success' type='submit' value='Napisz'/>
			</div>
		  </div>
		 </form>
		</div>
		<? } ?>
	  </div>
	<? }
	if(is_null($acp_k1->id)) {?>
	 <div class="box box-widget">
		<div class="box-body">
		 <p> Brak wpisów, coś za daleko trafiłeś..</p>
		</div>
		<div class="box-footer text-center">
		  <a href="?x=wpisy">Powórt</a>
		</div>
	 </div>
	<?} else { 	?>
	 <div class="box box-widget">
		<div class="box-footer text-center">
		  <a href="?x=wpisy&str=<?= $nr_kolejnej_strony; ?>">Załaduj Kolejne</a>
		</div>
	 </div>
	<? } ?>
	</div>







	<div class="col-lg-4">
	  <div class="info-box">
		<span class="info-box-icon bg-green"><i class="fa fa-keyboard-o"></i></span>

		<div class="info-box-content">
		  <span class="info-box-text">Wszystkich Wpisów</span>
		  <? $wszystkich_wpisow = one("SELECT COUNT(*) FROM `acp_wpisy`;");?>
		  <span class="info-box-number"><?= $wszystkich_wpisow ?></span>
		</div>
	  </div>
	</div>
	<div class="col-lg-4">
	  <div class="info-box">
		<span class="info-box-icon bg-aqua"><i class="fa fa-comment-o"></i></span>

		<div class="info-box-content">
		  <span class="info-box-text">Komentarzy</span>
		   <? $wszystkich_komen = one("SELECT COUNT(*) FROM `acp_wpisy_komentarze`;");?>
		  <span class="info-box-number"><?= $wszystkich_komen ?></span>
		</div>
	  </div>
	</div>
	<div class="col-lg-4">
	  <div class="box box-success">
		<div class="box-header with-border">
		  <h3 class="box-title">Dostępne Kategorie</h3>
		  <div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
		  </div>
		</div>
		<div class="box-body"><ul>
		<?
		$dostepne_kategorie_q = all("SELECT * FROM `acp_wpisy_kategorie` WHERE `id` != 0;");
		foreach($dostepne_kategorie_q as $dostepne_kategorie){
		?>
			<li><a href="?x=wpisy&xx=category&nazwa=<?= $dostepne_kategorie->nazwa; ?>&id=<?= $dostepne_kategorie->id; ?>"><?= $dostepne_kategorie->nazwa; ?></a></li>
		<? } ?>
		</ul></div>
	  </div>
	</div>
<? if($acp_system['wpisy_last_login_on'] == 1) { ?>
	<div class="col-lg-4">
	  <div class="box box-danger">
		<div class="box-header with-border">
		  <h3 class="box-title">Ostatnio Zalogowani</h3>
		  <div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
			</button>
		  </div>
		</div>
		<div class="box-body no-padding">
		  <ul class="users-list clearfix">
			<?
      $limit_last_login = $acp_system['wpisy_last_login_liczba'];
			$wpisy_ostatnio_logowani_q = all("SELECT `login`, `last_login`, `user`, `steam_login`, `steam_avatar` FROM `acp_users` ORDER BY `last_login` DESC LIMIT $limit_last_login ");
			foreach($wpisy_ostatnio_logowani_q as $wpisy_ostatnio){
				$wpisy_ostatnio_av = $dash->dashbord_czy_puste_av($wpisy_ostatnio->steam_avatar);
				$wpisy_ostatnio_login = $dash->dashbord_czy_puste_login($wpisy_ostatnio->steam_login, $wpisy_ostatnio->login);
        $wpisy_ostatnio->last_login = (strtotime($wpisy_ostatnio->last_login) > (time() - 60)) ? 'Aktywny Teraz' : czas_relatywny($wpisy_ostatnio->last_login);;
			?>
			<li>
			  <img src="<?= $wpisy_ostatnio_av ?>">
			  <a class="users-list-name" href="?x=account&id=<?= $wpisy_ostatnio->user ?>"><?= $wpisy_ostatnio_login ?></a>
			  <span class="users-list-date"><?= $wpisy_ostatnio->last_login; ?></span>
			</li>
			<? } ?>
		  </ul>
		</div>
	  </div>
	</div>
<? } ?>
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
<!-- SlimScroll -->
<script src="./www/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="./www/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="./www/dist/js/adminlte.min.js"></script>
