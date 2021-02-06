<?
$dash = getClass('Wpisy');
$wpis_one = real_string(clean($_GET['wpis']));
$wpis_id = (int)$_GET['wpisid'];

powiadomienie_odczytaj($_GET['powiadomienie_id']);
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
if(isset($_POST['komentarz'])){
  $dash->komentarz($_POST['komentarz'], $player->user);
  header("Location: ?x=$x&xx=$xx&wpis=$wpis_one&wpisid=$wpis_id");
}
if(isset($_POST['zmiana_kategori'])){
  $dash->zmiana_kategori($_POST['zmiana_kategori'], $player->user, $dostep->WpisyKategoria);
  header("Location: ?x=$x&xx=$xx&wpis=$wpis_one&wpisid=$wpis_id");
}
if(isset($_POST['edytuj_wpis'])){
  $dash->edytuj_wpis($_POST['zmiana_kategori'], $player->user, $dostep->WpisyEdytujWpis);
  header("Location: ?x=$x&xx=$xx&wpis=$wpis_one&wpisid=$wpis_id");
}
if(isset($_GET['close_open'])){
  $dash->close_open($_GET['close_open'], $player->user, $dostep->WpisyZamknij);
  header("Location: ?x=$x");
}
if(isset($_GET['usun'])){
  $dash->usun($_GET['usun'], $player->user, $dostep->WpisyUsun);
  header("Location: ?x=$x");
}
if(isset($_GET['ogloszenie'])){
  $dash->ogloszenie($_GET['ogloszenie'], $player->user, $dostep->WpisyOgloszenie);
  header("Location: ?x=$x");
}

?>
<div class="row">
  <div class="col-md-12">
  	<? $acp_wpis = row("SELECT *,
  	(SELECT `steam_avatar` FROM `acp_users` WHERE `user` = `u_id`) AS `steam_avatar`,
  	(SELECT `login` FROM `acp_users` WHERE `user` = `u_id`) AS `login`,
  	(SELECT `steam_login` FROM `acp_users` WHERE `user` = `u_id`) AS `steam_login`,

  	(SELECT `nazwa` FROM `acp_wpisy_kategorie` WHERE `id` = `kategoria`) AS `kategoria_nazwa`
  	FROM `acp_wpisy` WHERE `id` = ".$wpis_id." LIMIT 1;");
    $avatar = $dash->dashbord_czy_puste_av($acp_wpis->steam_avatar);
    $login = $dash->dashbord_czy_puste_login($acp_wpis->steam_login, $acp_wpis->login);
    if($acp_wpis->closed == 1) { $text_button = 'Otwórz'; } else { $text_button = 'Zamknij'; }
      tytul_strony("Wpis: $acp_wpis->tytul");
  	?>
      <div class="box box-widget">
    	<div class="box-header with-border">
    	  <div class="user-block">
    		<img class="img-circle" src="<?= $avatar; ?>">
    		<span class="username"><a href="?x=account&id=<?= $acp_wpis->u_id; ?>"><?= $login; ?></a> - <span style="word-wrap:break-word;"><?= $acp_wpis->tytul; ?></span></span>
    		<span class="description"><a href="<?= "?x=$x&xx=category&nazwa=$acp_wpis->kategoria_nazwa&id=$acp_wpis->kategoria" ?>"><?= $acp_wpis->kategoria_nazwa; ?></a> - <?= czas_relatywny($acp_wpis->data); ?></span>
    	  </div>
    	</div>
    	<div class="box-body">
    	  <p style="word-wrap:break-word;"><?= $acp_wpis->text; ?></p>
        <span class="pull-right">
          <? if(uprawnienia($dostep->WpisyOgloszenie, $player->user) == 1): ?>
            <a href="<?= "?x=$x&xx=$xx&ogloszenie=$acp_wpis->id" ?>"><button type="button" class="btn btn-default btn-xs"><i class="glyphicon glyphicon-bullhorn"></i> Ogłoszenie</button></a>
          <? endif; ?>

          <? if(uprawnienia($dostep->WpisyKategoria, $player->user) == 1): ?>
            <button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#wpis_kategoria"><i class="fa fa fa-keyboard-o"></i> Kategoria</button>
          <? endif; ?>

          <? if(uprawnienia($dostep->WpisyZamknij, $player->user) == 1): ?>
            <a href="<?= "?x=$x&xx=$xx&close_open=$acp_wpis->id" ?>"><button type="button" class="btn btn-default btn-xs"><i class="glyphicon glyphicon-eye-open"></i> <?= $text_button ?></button></a>
          <? endif; ?>

          <? if(uprawnienia($dostep->WpisyEdytujWpis, $player->user) == 1): ?>
              <button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#wpis_edytuj"><i class="fa fa-edit"></i> Edytuj</button>
          <? endif; ?>

          <? if(uprawnienia($dostep->WpisyUsun, $player->user) == 1): ?>
              <a href="<?= "?x=$x&xx=$xx&usun=$acp_wpis->id" ?>"><button type="button" class="btn btn-default btn-xs"><i class="glyphicon glyphicon-remove"></i> Usuń</button></a>
          <? endif; ?>
        </span>
    	</div>
    	<div class="box-footer box-comments">
    		<?
    		$acp_wpis_com_q = all("SELECT *,
    		(SELECT `steam_avatar` FROM `acp_users` WHERE `user` = `user_id`) AS `koemntujacy_steam_avatar`,
    		(SELECT `login` FROM `acp_users` WHERE `user` = `user_id`) AS `koemntujacy_login`,
    		(SELECT `steam_login` FROM `acp_users` WHERE `user` = `user_id`) AS `koemntujacy_steam_login`

    		FROM `acp_wpisy_komentarze` WHERE `wpis_id` = ".$acp_wpis->id." ORDER BY `id` ASC;");
    		foreach($acp_wpis_com_q as $acp_wpis_com):
    			$com_avatar = $dash->dashbord_czy_puste_av($acp_wpis_com->koemntujacy_steam_avatar);
    			$com_login = $dash->dashbord_czy_puste_login($acp_wpis_com->koemntujacy_steam_login, $acp_wpis_com->koemntujacy_login);
    		?>
    		  <div class="box-comment">
    			<img class="img-circle img-sm" src="<?= $com_avatar; ?>">

    			<div class="comment-text">
    				  <span class="username">
    					<a href="?x=account&id=<?= $acp_wpis_com->user_id; ?>"><?= $com_login;  ?></a>
    					<span class="text-muted pull-right"><?= czas_relatywny($acp_wpis_com->data); ?></span>
    				  </span>
    			  <p><?= $acp_wpis_com->text; ?></p>
    			</div>
    		  </div>
        <? endforeach; ?>
    	</div>
    	<? if($acp_wpis->closed == 1):?>
    	<div class="box-footer">
    	 <div class="input-group-btn">
    	  <button type="button" class="btn btn-danger btn-block">Zablokowany</button>
    	 </div>
    	</div>
    	<? else: ?>
    	<div class="box-footer">
    	 <form name='komentarz' method='post' action='<?= "?x=$x&xx=$xx&wpisid=$acp_wpis->id" ?>'>
    	  <div class="input-group">
    		<input type='hidden' name='komentarz_id' value='<?= $acp_wpis->id; ?>'>
    		<input type="text" class="form-control" name='komentarz_tekst' placeholder="Wpisz wiadomość...">
    		<div class="input-group-btn">
    		<input name='komentarz' class='btn btn-success' type='submit' value='Napisz'/>
    		</div>
    	  </div>
    	 </form>
    	</div>
    	<? endif; ?>
    </div>
	</div>
</div>

  <div class="row">
    <div class="modal fade" id="wpis_kategoria">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Zmień Kateogrię</h4>
          </div>
          <div class="modal-body">
            <form name='zmiana_kategori' method='post' action='<? echo "?x=$x&xx=$xx&wpis=$wpis_one&wpisid=$wpis_id"; ?>'>
              <input type="hidden" name="id" value="<?=$acp_wpis->id ?>">
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Kategoria</span>
                  <select class="form-control" name="kategoria">
                    <?
                    $kat_list = array();
                    $kat_list_q = all("SELECT `id`, `nazwa` FROM `acp_wpisy_kategorie`");
                    foreach($kat_list_q as $kat_list_s){
                      $kat_list[$kat_list_s->id]="$kat_list_s->nazwa";
                    }

                    echo '<option value="'.$acp_wpis->kategoria.'">'.$kat_list[$acp_wpis->kategoria].'</option>';
                    foreach ($kat_list as $key => $value):
                      if($acp_wpis->kategoria != $key)
                      echo '<option value="'.$key.'">'.$value.'</option>';
                    endforeach;
                    ?>
                  </select>
                </div>
              </p>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Zamknij</button>
              <button type="input" name="zmiana_kategori" class="btn btn-primary">Zmień</button>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="wpis_edytuj">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Edytuj Wpis</h4>
          </div>
          <div class="modal-body">
            <form name='edytuj_wpis' method='post' action='<? echo "?x=$x&xx=$xx&wpis=$wpis_one&wpisid=$wpis_id"; ?>'>
              <input type="hidden" name="id" value="<?=$acp_wpis->id ?>">
              <p><div class="form-group input-group"><span class="input-group-addon">Tytuł</span><input class="form-control" type="text" name="tytul" value="<?=$acp_wpis->tytul ?>"></div></p>
              <p><textarea class="form-control" rows="7" name="tekst"><?= $acp_wpis->text ?></textarea></p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Zamknij</button>
              <button type="input" name="edytuj_wpis" class="btn btn-primary">Zapisz</button>
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
<!-- SlimScroll -->
<script src="./www/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="./www/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="./www/dist/js/adminlte.min.js"></script>
