<?
if(empty($player)){
  die ('user nie zalogowany');
}

$xxx = $_GET['xxx'];

switch ($xx) {
  case 'header':
    switch ($xxx) {
      case 'ilosc_powiadomien':
        $return = one("SELECT COUNT(*) AS `count` FROM `acp_users_notification` WHERE `read` = 1 AND `u_id` = $player->user;");
        $return = ($return == 0) ? '<i class="fa fa-bell-o"></i>' : '<i class="fa fa-bell-o"></i><span class="label label-warning">'.$return.'</span><script>playSound();</script>';
        echo $return;
        break;
      case 'ilosc_zadan':
        $return = one("SELECT COUNT(`id`) FROM `acp_zadania_users` LEFT JOIN (`acp_zadania`) ON `acp_zadania_users`.`id_zadania` = `acp_zadania`.`id` WHERE `u_id` = $player->user AND `status` IN (0, 1, 2); ");
        $return = ($return == 0) ? '<i class="fa fa-flag-o"></i>' : '<i class="fa fa-flag-o"></i><span class="label label-danger">'.$return.'</span>';
        echo $return;
        break;
      case 'ilosc_wiadomosc':
        $return = one("SELECT COUNT(*) AS `count` FROM `acp_messages` INNER JOIN `acp_users` ON `m_from` = `user` WHERE `m_to` = $player->user AND `m_type` = 1 AND `m_status` = 0;");
        $return = ($return == 0) ? '<i class="fa fa-envelope-o"></i>' : '<i class="fa fa-envelope-o"></i><span class="label label-success">'.$return.'</span><script>playSound();</script>';
        echo $return;
        break;

      case 'powiadomienia':
        $query_q = all("SELECT `id`, `link`, `text`, `icon`, `data`, `read` FROM `acp_users_notification` WHERE `u_id` = $player->user AND `read` = 1 ORDER BY `id` DESC LIMIT 10;");
        if(empty($query_q)) {
          break;
        }
        foreach($query_q as $query){
          $query->icon_kolor = ($query->read==1) ? 'text-aqua' : '';
        ?>
        <li>
          <a href="<?= $query->link; ?>&powiadomienie_id=<?= $query->id; ?>">
            <i class="fa <?= $query->icon.' '.$query->icon_kolor ?>"></i> <?= $query->text ?>
          </a>
        </li>
        <? }
        break;
      case 'zadania':
        $query_q = all("SELECT `id`, `temat`, `procent_wykonania`, `kolor_wykonania` FROM `acp_zadania_users` LEFT JOIN (`acp_zadania`) ON `acp_zadania_users`.`id_zadania` = `acp_zadania`.`id` WHERE `u_id` = $player->user AND `status` IN (0, 1, 2) ORDER BY `id` DESC;");
        if(empty($query_q)) {
          break;
        }
        foreach($query_q as $query){
        ?>
        <li>
          <a href="?x=zadania&xx=zadanie&id=<?= $query->id; ?>">
            <h3>
              <?= limit_text($query->temat, 50) ?>
              <small class="pull-right"><?= $query->procent_wykonania; ?>%</small>
            </h3>
            <div class="progress xs">
              <div class="progress-bar progress-bar-<?=  $query->kolor_wykonania; ?>" style="width: <?= $query->procent_wykonania; ?>%" role="progressbar"
                   aria-valuenow="<?= $query->procent_wykonania; ?>" aria-valuemin="0" aria-valuemax="100">
                <span class="sr-only"><?= $query->procent_wykonania; ?>% Complete</span>
              </div>
            </div>
          </a>
        </li>
        <? }
        break;
      case 'wiadomosci':
        $query_q = all("SELECT `m_id`, `m_type`, `login`, `m_date`, `m_status`, `m_text`, `steam_avatar` FROM `acp_messages` INNER JOIN `acp_users` ON `m_from` = `user` WHERE `m_to` = $player->user AND `m_type` = 1 ORDER BY `m_status` ASC, `m_id` DESC LIMIT 5;");
        if(empty($query_q)) {
          break;
        }
        foreach($query_q as $query){
        ?>
        <li>
          <a href="?x=wiadomosci&xx=czytaj&type=1&id=<?= $query->m_id; ?>&read=1">
            <div class="pull-left">
              <img src="<?= $query->steam_avatar ?>" class="img-circle" alt="User Image">
            </div>
            <h4>
              <?= $query->login; ?>
              <small><i class="fa fa-clock-o"></i> <?= czas_relatywny($query->m_date); ?></small>
            </h4>
            <p><?= limit_text(strip_tags($query->m_text), 80); ?></p>
          </a>
        </li>
        <? }
        break;
    }
    break;

}
?>
