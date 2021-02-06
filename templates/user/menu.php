<? require_once("./templates/user/menu-header.php");

function akt_li($nazwa) {
	if($nazwa == $_GET['x'])
     echo "active";
}
function akt_li_srv($nazwa, $id = NULL) {
	if($nazwa == $_GET['x'] && $id == $_GET['serwer_id'])
     echo "active";
}
function akt_kat($nazwa) {
	$nazwa = explode(", ", $nazwa);

	if(in_Array($_GET['x'],$nazwa))
     echo "active treeview menu-open";
	else
	 echo "treeview";
}
$menu_q = all("SELECT `id`, `nazwa`, `ikona`, `nazwa_wys`, `wlaczony`, `menu`, `menu_kategoria` FROM `acp_moduly` WHERE `menu` != 0; ");
?>
   <aside class="main-sidebar">
    <section class="sidebar">
			<div class="user-panel">
        <div class="pull-left image">
          <img src="<?= $player->steam_avatar ?>" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?= $player->steam_login ?></p>
        	<small>Nick ACP: <i><?= $player->login ?></i></small>
        </div>
      </div>
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
          <span class="input-group-btn">
            <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
            </button>
          </span>
        </div>
      </form>

    <ul class="sidebar-menu" data-widget="tree">

		<!--- Publiczne --->
    <li class="header">Podstawowe Funkcje</li>
		<li><a href='?x=default'><i class='fa fa-home'></i> <span>Strona Główna</span></a></li>
		<?
		foreach ($menu_q as $menu) {
			if(in_Array($menu->nazwa, $moduly)) {
				if($menu->menu_kategoria == 0) {
					if($menu->menu == 1) {
						if($menu->nazwa == $x) { $aktywne = 'active'; } else { $aktywne = ''; }
						echo "<li class='$aktywne'><a href='?x=$menu->nazwa'><i class='$menu->ikona'></i> <span>$menu->nazwa_wys</span></a></li>";
					}
					else {
						if($menu->nazwa == $x) { $aktywne_sub_menu = 'active'; } else { $aktywne_sub_menu = ''; }
						echo "
						<li class='treeview $aktywne_sub_menu'>
						<a href='#'>
							<i class='$menu->ikona'></i>
							<span>$menu->nazwa_wys</span>
							<span class='pull-right-container'>
								<i class='fa fa-angle-left pull-right'></i>
							</span>
						</a>
						<ul class='treeview-menu'>
						";
						$sub_menu_q = all("SELECT * FROM `acp_moduly_menu` WHERE `modul_id` = $menu->id; ");
						foreach ($sub_menu_q as $sub_menu) {
							$wyszukaj_menu = strpos($sub_menu->link, 'http');
							if($wyszukaj_menu == FALSE) {
								$sub_menu_link_x = "?x=$sub_menu->link";
								$sub_menu_link = str_replace("$menu->nazwa&xx=", '', $sub_menu->link);
								if($sub_menu_link == $xx) { $aktywne_sub = 'active'; } else { $aktywne_sub = ''; }
							}
							echo "<li class='$aktywne_sub'><a href='$sub_menu_link_x'><i class='$sub_menu->ikona'></i> <span>$sub_menu->nazwa</span></a></li>";
						}
						echo "</ul></li>";
					}
				}
			}
		}
		?>
		<li class="header">Serwery Gry</li>
		<?
		foreach ($menu_q as $menu) {
			if(in_Array($menu->nazwa, $moduly)) {
				if($menu->menu_kategoria == 1) {
					if($menu->menu == 1) {
						if($menu->nazwa == $x) { $aktywne = 'active'; } else { $aktywne = ''; }
						echo "<li class='$aktywne'><a href='?x=$menu->nazwa'><i class='$menu->ikona'></i> <span>$menu->nazwa_wys</span></a></li>";
					}
					else {
						if($menu->nazwa == $x) { $aktywne_sub_menu = 'active'; } else { $aktywne_sub_menu = ''; }
						echo "
						<li class='treeview $aktywne_sub_menu'>
						<a href='#'>
							<i class='$menu->ikona'></i>
							<span>$menu->nazwa_wys</span>
							<span class='pull-right-container'>
								<i class='fa fa-angle-left pull-right'></i>
							</span>
						</a>
						<ul class='treeview-menu'>
						";
						$sub_menu_q = all("SELECT * FROM `acp_moduly_menu` WHERE `modul_id` = $menu->id; ");
						foreach ($sub_menu_q as $sub_menu) {
							$wyszukaj_menu = strpos($sub_menu->link, 'http');
							if($wyszukaj_menu == FALSE) {
								$sub_menu_link_x = "?x=$sub_menu->link";
								$sub_menu_link = str_replace("$menu->nazwa&xx=", '', $sub_menu->link);
								if($sub_menu_link == $xx) { $aktywne_sub = 'active'; } else { $aktywne_sub = ''; }
							}
							echo "<li class='$aktywne_sub'><a href='$sub_menu_link_x'><i class='$sub_menu->ikona'></i> <span>$sub_menu->nazwa</span></a></li>";
						}
						echo "</ul></li>";
					}
				}
			}
		}
		?>
		<li class="treeview <? akt_kat("serwery_det");?>">
			<a href="#">
				<i class="fa fa-server"></i>
				<span>Detale Serwerów</span>
				<span class="pull-right-container">
					<i class="fa fa-angle-left pull-right"></i>
				</span>
			</a>
			<ul class="treeview-menu">
				<?
				if($player->role == 1) {
					$acp_dostep_serwer = all("SELECT `serwer_id`,`istotnosc`, `mod` FROM `acp_serwery` ORDER BY `istotnosc` ASC");
					foreach($acp_dostep_serwer as $acp_dostep_s){ ?>
							<li class="<? akt_li_srv("serwery_det", $acp_dostep_s->serwer_id);?>"><a href="?x=serwery_det&serwer_id=<?= $acp_dostep_s->serwer_id ?>"><i class="fa fa-circle-o"></i> <?= $acp_dostep_s->istotnosc ?> - <?= $acp_dostep_s->mod ?></a></li>
					<? }
				}
				else {
					$acp_dostep_serwer = all("SELECT `serwer_id`,`istotnosc`, `mod` FROM `acp_serwery` WHERE `ser_a_jr` = '$player->user' OR `ser_a_opiekun` = '$player->user' OR `ser_a_copiekun` = '$player->user' ORDER BY `istotnosc` ASC");
					foreach($acp_dostep_serwer as $acp_dostep_s){ ?>
						<li class="<? akt_li_srv("serwery_det", $acp_dostep_s->serwer_id);?>"><a href="?x=serwery_det&serwer_id=<?= $acp_dostep_s->serwer_id ?>"><i class="fa fa-circle-o"></i> <?= $acp_dostep_s->istotnosc ?> - <?= $acp_dostep_s->mod ?></a></li>
					<? }
				}?>
			</ul>
		</li>

		<? if($player->role == 1): ?>
		<li class="header">Administracja ACP</li>
			<?
			foreach ($menu_q as $menu) {
				if(in_Array($menu->nazwa, $moduly)) {
					if($menu->menu_kategoria == 2) {
						if($menu->menu == 1) {
							if($menu->nazwa == $x) { $aktywne = 'active'; } else { $aktywne = ''; }
							echo "<li class='$aktywne'><a href='?x=$menu->nazwa'><i class='$menu->ikona'></i> <span>$menu->nazwa_wys</span></a></li>";
						}
						else {
							if($menu->nazwa == $x) { $aktywne_sub_menu = 'active'; } else { $aktywne_sub_menu = ''; }
							echo "
							<li class='treeview $aktywne_sub_menu'>
							<a href='#'>
								<i class='$menu->ikona'></i>
								<span>$menu->nazwa_wys</span>
								<span class='pull-right-container'>
									<i class='fa fa-angle-left pull-right'></i>
								</span>
							</a>
							<ul class='treeview-menu'>
							";
							$sub_menu_q = all("SELECT * FROM `acp_moduly_menu` WHERE `modul_id` = $menu->id; ");
							foreach ($sub_menu_q as $sub_menu) {
								$wyszukaj_menu = strpos($sub_menu->link, 'http');
								if($wyszukaj_menu == FALSE) {
									$sub_menu_link_x = "?x=$sub_menu->link";
									$sub_menu_link = str_replace("$menu->nazwa&xx=", '', $sub_menu->link);
									if($sub_menu_link == $xx) { $aktywne_sub = 'active'; } else { $aktywne_sub = ''; }
								}
								echo "<li class='$aktywne_sub'><a href='$sub_menu_link_x'><i class='$sub_menu->ikona'></i> <span>$sub_menu->nazwa</span></a></li>";
							}
							echo "</ul></li>";
						}
					}
				}
			}
			?>
		<? endif; ?>


    </ul>
  </section>
</aside>
