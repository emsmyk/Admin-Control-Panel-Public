
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="./www/powiadomienia_na_zywo.js"></script>
<?
if(isset($_POST['user_ustawienia'])) {
	$from = post_to_stdclass();
	$from->szablon = (empty($from->szablon)) ? $player->szablon : $from->szablon;

	query("UPDATE `acp_users` SET `szablon` = '$from->szablon', `uklad_16_4` = '$from->uklad_16_4', `pudelkowy` = '$from->pudelkowy', `menu` = '$from->menu', `prawy_kolor` = '$from->prawy_kolor' WHERE `user` = $player->user;");
	$_SESSION['msg'] = komunikaty("Zaktualizowano styl oraz ustawienia strony", 1);
	header("Location: ?x=$x");
}
if(isset($_POST['acp_grupa_sesja'])){
	$_SESSION['acp_grupa_sesja'] = $_POST['id_grupy_sessja'];
	header("Location: ?x=$x");
}
?>

<footer class="main-footer">
	<div class="pull-right hidden-xs"><b>Version</b> <?= $acp_system['acp_wersja']; ?></div>
	<strong>Copyright &copy; <script>document.write(new Date().getFullYear());</script> Admin Control Panel  by <a href="https://steamcommunity.com/profiles/76561198015904879" target="_blank">EMCE!</a>
</footer>

<aside class="control-sidebar <?= $player->prawy_kolor ?>">
  <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
    <li class="active"><a href="#control-sidebar-szablon-tab" data-toggle="tab"><i class="fa fa-wrench"></i></a></li>
    <li><a href="<?= $acp_system['acp_statystyki']; ?>"><i class="glyphicon glyphicon-stats"></i></a></li>
    <li><a href="<?= $acp_system['acp_forum']; ?>"><i class="fa fa-forumbee"></i></a></li>
  </ul>

  <div class="tab-content">
    <div class="tab-pane active" id="control-sidebar-szablon-tab">
			<? if($player->role == 1): ?>
				<h3 class="control-sidebar-heading">Tryb Poglądowy</h3>
				<form name='acp_grupa_sesja' method='post'>
					<div class="form-group">
					 <p>
						  <select class="form-control select2" name="id_grupy_sessja" style="width: 100%;">
						   <option selected="selected" value="">Domyślna</option>
							 <?
							 $acp_grupa_sessja_lista = all("SELECT `id`, `nazwa` FROM `acp_users_grupy`");
							 foreach ($acp_grupa_sessja_lista as $value) {
							 	 echo '<option value="'.$value->id.'">'.$value->nazwa.'</option>';
							 }
							 ?>
						 	</select>
					 	</p>
						<p><input name='acp_grupa_sesja' class='btn btn-primary btn btn-block' type='submit' value='Zmień Grupę'/></p>
					</div>
				</form>
			<? endif; ?>
			<h3 class="control-sidebar-heading">Ustawienia Szablonu</h3>
			<form method='post'>
		    <div class="form-group">
		       <label class="control-sidebar-subheading">
		       <input type="checkbox" name="pudelkowy" class="pull-right" value="1" <? if(!empty($player->pudelkowy)) { echo "checked"; } ?>/>
		       Pudełkowy Układ
		       </label>
		       <p>Odziela menu od strony i nadaje każdemu przewijany układ.</p>
	      </div>
				<div class="form-group">
		       <label class="control-sidebar-subheading">
		       <input type="checkbox" name="uklad_16_4" class="pull-right" value="1" <? if(!empty($player->uklad_16_4)) { echo "checked"; } ?>/>
		       Układ 16:4
		       </label>
		       <p>Zmienia szerokość strony na wąski, typu starsze ekrany komputerowe.</p>
	      </div>
				<div class="form-group">
		       <label class="control-sidebar-subheading">
		       <input type="checkbox" name="menu" class="pull-right" value="1" <? if(!empty($player->menu)) { echo "checked"; } ?>/>
		       Wąskie Menu
		       </label>
		       <p>Inaczej same ikony, po najechaniu powiekszone dane.</p>
	      </div>
				<div class="form-group">
	         <label class="control-sidebar-subheading">
	         <input type="checkbox" name="prawy_kolor" class="pull-right" value="1" <? if($player->prawy_kolor == 'control-sidebar-light') { echo "checked"; } ?>/>
	         Kolor Boxu
	         </label>
	         <p>Zmienia koloru boxu na jasny.</p>
				</div>
				<div class="form-group">
				 <label>Szablon</label>
				  <select class="form-control select2" name="szablon" style="width: 100%;">
				   <option selected="selected" value="">Wybierz</option>
				   <option value="skin-blue">Niebieski</option>
				   <option value="skin-black">Czarny</option>
				   <option value="skin-red">Czerwony</option>
				   <option value="skin-yellow">Żółty</option>
				   <option value="skin-purple">Fioletowy</option>
				   <option value="skin-green">Zielony</option>
				   <option value="skin-blue-light">Niebieski Jasny</option>
				   <option value="skin-black-light">Czarny Jasny</option>
				   <option value="skin-red-light">Czerwony Jasny</option>
				   <option value="skin-yellow-light">Żółty Jasny</option>
				   <option value="skin-purple-light">Fioletowy Jasny</option>
				   <option value="skin-green-light">Zielony Jasny</option>
				  </select>
				</div>
				<div class="form-group">
			 		<p><input name='user_ustawienia' class='btn btn-primary btn btn-block' type='submit' value='Zapisz'/></p>
		 		</div>
			</form>
		</div>
  </div>
</aside>
