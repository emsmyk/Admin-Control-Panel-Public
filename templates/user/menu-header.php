<?
$acp_mini_w_ilosc = one("SELECT COUNT(*) AS `count` FROM `acp_messages` INNER JOIN `acp_users` ON `m_from` = `user` WHERE `m_to` = $player->user AND `m_type` = 1 AND `m_status` = 0 LIMIT 4;");
$acp_mini_w_ilosc = ($acp_mini_w_ilosc == 0) ? '<i class="fa fa-envelope-o"></i>' : '<i class="fa fa-envelope-o"></i><span class="label label-success">'.$acp_mini_w_ilosc.'</span>';


$acp_mini_z = all("SELECT `id`, `temat`, `procent_wykonania`, `kolor_wykonania` FROM `acp_zadania_users` LEFT JOIN (`acp_zadania`) ON `acp_zadania_users`.`id_zadania` = `acp_zadania`.`id` WHERE `u_id` = $player->user AND `status` IN (0, 1, 2) ORDER BY `id` DESC; ");
$acp_mini_z_ilosc = (count((array)$acp_mini_z) == 0 || empty($acp_mini_z)) ? '<i class="fa fa-flag-o"></i>' : '<i class="fa fa-flag-o"></i><span class="label label-danger">'.count((array)$acp_mini_z).'</span>';


$acp_mini_n_ilosc = one("SELECT COUNT(*) AS `count` FROM `acp_users_notification` WHERE `read` = 1 AND `u_id` = $player->user LIMIT 11;");
$acp_mini_n_ilosc = ($acp_mini_n_ilosc == 0) ? '<i class="fa fa-bell-o"></i>' : '<i class="fa fa-bell-o"></i><span class="label label-warning">'.$acp_mini_n_ilosc.'</span>';
?>
<header class="main-header">
    <a href="?x=wpisy" class="logo">
      <span class="logo-mini"><b>A</b>CP</span>
      <span class="logo-lg"><b><?= $acp_system['acp_nazwa'] ?></b> | ACP</span>
    </a>
    <nav class="navbar navbar-static-top">
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <li class="dropdown messages-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" id="ilosc_wiadomosc"><?= $acp_mini_w_ilosc ?></a>
            <ul class="dropdown-menu">
              <li>
                <ul class="menu" id="wiadomosci">

                </ul>
              </li>
              <li class="footer"><a href="?x=wiadomosci&xx=skrzynka&type=1">Zobacz Wszystkie</a></li>
            </ul>
          </li>
          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle powiadomienia" data-toggle="dropdown" id="ilosc_powiadomien"><?= $acp_mini_n_ilosc ?></a>
            <ul class="dropdown-menu">
              <li>
                <ul class="menu" id="powiadomienia">

                </ul>
              </li>
              <li class="footer"><a href="?x=powiadomienia">Zobacz Wszystkie</a></li>
            </ul>
          </li>
          <li class="dropdown tasks-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" id="ilosc_zadan"><?= $acp_mini_z_ilosc ?></a>
            <ul class="dropdown-menu">
              <li>
                <ul class="menu" id="zadania">

				        </ul>
              </li>
            </ul>
          </li>
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="<?= $player->steam_avatar ?>" class="user-image" alt="User Image">
              <span class="hidden-xs"><?= $player->login ?></span>
            </a>
            <ul class="dropdown-menu">
              <li class="user-header">
                <img src="<?= $player->steam_avatar ?>" class="img-circle" alt="User Image">

                <p>
                  <?= $player->login ?>
                  <small>Użytkownik od <?= czas_relatywny($player->data_rejestracji) ?></small>
                </p>
              </li>
              <li class="user-footer">
                <div class="pull-left">
                  <a href="?x=account&id=<?= $player->user; ?>" class="btn btn-default btn-flat">Profil</a>
                </div>
                <div class="pull-right">
                  <a href="?x=logout" class="btn btn-default btn-flat">Wyloguj</a>
                </div>
              </li>
            </ul>
          </li>
          <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>
        </ul>
      </div>
    </nav>
  </header>
