<?
$dane_publiczne = getClass('DanePubliczne');

$zadanie = row("SELECT *,
  `serwer_id` AS `id_serwera`,
  (SELECT `login` FROM `acp_users` WHERE `user` = `zlecajacy_id` LIMIT 1) AS zlecajacy,
  (SELECT `login` FROM `acp_users` WHERE `user` = `technik_id` LIMIT 1) AS technik,
  (SELECT `login` FROM `acp_users` WHERE `user` = `akceptujacy_id` LIMIT 1) AS akceptujacy,
  (SELECT `nazwa` FROM `acp_zadania_status` WHERE `id` = `status` LIMIT 1) AS status_text,
  (SELECT `typ` FROM `acp_zadania_status` WHERE `id` = `status` LIMIT 1) AS status_kolor,
  (SELECT `nazwa` FROM `acp_serwery` WHERE `serwer_id` = `id_serwera` LIMIT 1) AS nazwa_serwera,
  (SELECT `nazwa` FROM `acp_zadania_platforma` WHERE `id` = `platforma` LIMIT 1) AS nazwa_platforma,
  (SELECT `nazwa` FROM `acp_zadania_typ` WHERE `id` = `typ` LIMIT 1) AS nazwa_typ
   FROM `acp_zadania` WHERE `public_code` = '$xx' LIMIT 1");
// show($zadanie);
tytul_strony("Zadanie: $zadanie->temat");
?>
<body class="hold-transition <?= $player->szablon ?> layout-top-nav">
  <?
  if(!empty($_SESSION['user'])){
    require_once("./templates/master/przybornik/menu-header.php");
  }
  ?>
  <div class="wrapper">
    <div class="content-wrapper">
      <div class="container">
        <section class="content">
    		 <div class="row">
           <div class="col-lg-12">
            <div class="box box">
       				<div class="box-body">
                <div class="row">
              		<div class="col-lg-12">
                    <div class="nav-tabs-custom">
                      <ul class="nav nav-tabs pull-right">
                        <li class="pull-left header"><i class="fa fa-info-circle"></i> Zadanie: <?= $zadanie->temat ?></li>
                      </ul>
                    </div>
                  </div>
                  <div class="col-lg-7">
                    <div class="nav-tabs-custom">
                      <ul class="nav nav-tabs pull-right">
                        <li class="pull-left header"><i class="fa fa-inbox"></i> Detale Zadania</li>
                      </ul>
                      <div class="tab-content">
                        <h4>Temat:</h4><p><?= $zadanie->temat ?></p>
              					<h4>Opis:</h4><p><?= str_replace(array("\r\n", "\n", "\r"), "<br>", $zadanie->opis) ?></p>
              					<h4>Status:</h4><p><button type='button' class='btn btn-<?= $zadanie->status_kolor ?> btn-xs'><?= $zadanie->status_text ?></button></p>
              					<h4>Serwer:</h4><p><?= $zadanie->nazwa_serwera ?></p>

              					<h4>Postęp:</h4>
                        <div class="progress text-center">
                          <div class="progress-bar progress-bar-<?= $zadanie->kolor_wykonania ?>" role="progressbar" aria-valuenow="<?= $zadanie->procent_wykonania ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $zadanie->procent_wykonania ?>%;">
                            <span><?= $zadanie->procent_wykonania ?>% Wykonano</span>
                          </div>
                            <span><?= 100-$zadanie->procent_wykonania ?>% Pozostało</span>
                        </div>
                      </div>
                    </div>
                    <div class="nav-tabs-custom">
                      <ul class="nav nav-tabs pull-right">
                        <li class="pull-left header"><i class="fa fa-comments-o"></i> Komentarze</li>
                      </ul>
                      <div class="box-body chat" id="chat-box">
                        <?
                        $komentarze_q = all("SELECT *, (SELECT `login` FROM `acp_users` WHERE `user` = `u_id`) AS `nick`,
                        (SELECT `steam_login` FROM `acp_users` WHERE `user` = `u_id`) AS `steam_login`,
                        (SELECT `steam_avatar` FROM `acp_users` WHERE `user` = `u_id`) AS `steam_avatar`,
                        (SELECT `last_login` FROM `acp_users` WHERE `user` = `u_id`) AS `last_login`
                         FROM `acp_zadania_com` WHERE `id_z` = $zadanie->id;");
                        if(empty($komentarze_q)) { echo '<div class="item">Brak komentarzy..</div>'; }
                        foreach ($komentarze_q as $komentarze) {
                          if(strtotime($komentarze->last_login) > (time() - 120)) { $online = 'online'; } else { $online = 'offline'; }
                        ?>
                        <div class="item">
                          <img src="<?= $komentarze->steam_avatar ?>" alt="user image" class="<?= $online ?>">

                          <p class="message">
                            <a href="?x=account&id=<?= $komentarze->u_id ?>" class="name">
                              <small class="text-muted pull-right"><i class="fa fa-clock-o"></i> <?= czas_relatywny($komentarze->data) ?></small>
                              <?= $komentarze->steam_login ?> (<?= $komentarze->nick ?>)
                            </a>
                            <?= $komentarze->text ?>
                          </p>
                        </div>
                        <? } ?>
                      </div>
                    </div>
                    <div class="nav-tabs-custom">
                      <ul class="nav nav-tabs pull-right">
                        <li class="pull-left header"><i class="ion ion-clipboard"></i> To Do List</li>
                      </ul>
                      <div class="tab-content">
                        <ul class="todo-list">
                          <?
                          $todo_q = all("SELECT * FROM `acp_zadania_todo` WHERE `zadanie_id` = $zadanie->id");
                          foreach ($todo_q as $todo) {
                            $todo->icon = (1 == $todo->zrealizowano) ? '<i class="fa fa-thumbs-down"></i>' : '<i class="fa fa-thumbs-up"></i>';
                            $todo->tekst_s = (1 == $todo->zrealizowano) ? "<s>$todo->tekst</s>" : $todo->tekst;
                            $todo->czas_realizacji = (strtotime($todo->zrealizowano_data)-strtotime($todo->data));
                            $todo->pozostalo = (empty($todo->pozostalo)) ? '~' : $todo->pozostalo;
                            $todo->realizuj_czas = (1 == $todo->zrealizowano) ? "Realizowano:  ".sek_na_tekst($todo->czas_realizacji) : "Czas planowany: $todo->pozostalo minut";
                            $todo->realizuj_kolor = (1 == $todo->zrealizowano) ? "default" : "success";
                          ?>
                            <li>
                              <span class="text">#<?= $todo->id  ?> <?= $todo->tekst_s ?></span>
                              <small class='label label-<?= $todo->realizuj_kolor ?>'><i class='fa fa-clock-o'></i> <?= $todo->realizuj_czas ?></small>
                            </li>
                          <? } ?>
                        </ul>
                      </div>
                      <div class="box-footer clearfix no-border">
                        <button type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#todo_dodaj"><i class="fa fa-plus"></i> Dodaj</button>
                      </div>
                    </div>
                    <div class="nav-tabs-custom">
                      <ul class="nav nav-tabs pull-right">
                        <li class="pull-left header"><i class="fa fa-file-archive-o"></i> Logi</li>
                      </ul>
                      <div class="tab-content">
                        <div class="logi_hight">
                          <? $logi_q = all("SELECT *,`user` AS `user_id`,(SELECT `login` FROM `acp_users` WHERE `user` = `user_id` LIMIT 1) AS `login` FROM `acp_log` WHERE `page` LIKE '%?x=zadania&xx=zadanie&id=$zadanie->id%' ORDER BY `id` DESC");
                          foreach ($logi_q as $logi): ?>
                            <p><?= $logi->data ?> - <?= $logi->tekst ?> ~ <?= $logi->login ?><p>
                          <? endforeach;?>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-5">
                    <div class="nav-tabs-custom">
                      <ul class="nav nav-tabs pull-right">
                        <li class="pull-left header"><i class="fa fa-glass"></i> Administracja</li>
                      </ul>
                      <div class="box-body tab-content">
                        <?
                         $zadanie->zlecajacy_blok_1 = (!empty($zadanie->zlecajacy)) ? "<a href='#'>$zadanie->zlecajacy</a>" : '<a href="#">Brak</a>' ;
                         $zadanie->zlecajacy_blok_2 = (!empty($zadanie->zlecajacy)) ? czas_relatywny($zadanie->data) : '';
                         $zadanie->akcept_blok_1 = (!empty($zadanie->akceptujacy)) ? "<a href='#'>$zadanie->akceptujacy</a>" : '<a href="#">Brak</a>' ;
                         $zadanie->akcept_blok_2 = (!empty($zadanie->akceptujacy)) ? czas_relatywny($zadanie->a_data) : '';
                         $zadanie->akcept_blok_1a = ($zadanie->status == '-1') ? 'Odrzucił: ' : 'Akceptował: ';
                         $zadanie->technik_blok_1 = (!empty($zadanie->technik)) ? "<a href='#'>$zadanie->technik</a>" : '<a href="#">Brak</a>' ;
                         $zadanie->technik_blok_2 = (!empty($zadanie->technik)) ? czas_relatywny($zadanie->t_data) : '';
                        ?>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                          <div class="info-box">
                            <span class="info-box-icon bg-red"><i class="fa fa-black-tie"></i></span>
                            <div class="info-box-content">
                              <span class="info-box-text">Zlecił</span>
                              <span class="info-box-number"><?= $zadanie->zlecajacy_blok_1 ?></span>
                              <span class="info-box-text"><?= $zadanie->zlecajacy_blok_2 ?></span>
                            </div>
                          </div>
                        </div>

                        <div class="col-md-12 col-sm-12 col-xs-12">
                          <div class="info-box">
                            <span class="info-box-icon bg-green"><i class="fa fa-mouse-pointer"></i></span>
                            <div class="info-box-content">
                              <span class="info-box-text"><?=  $zadanie->akcept_blok_1a ?></span>
                              <span class="info-box-number"><?= $zadanie->akcept_blok_1 ?></span>
                              <span class="info-box-text"><?= $zadanie->akcept_blok_2 ?></span>
                            </div>
                          </div>
                        </div>

                        <div class="col-md-12 col-sm-12 col-xs-12">
                          <div class="info-box">
                            <span class="info-box-icon bg-yellow"><i class="fa fa-odnoklassniki"></i></span>
                            <div class="info-box-content">
                              <span class="info-box-text">Zajmuje się:</span>
                              <span class="info-box-number"><?= $zadanie->technik_blok_1 ?></span>
                              <span class="info-box-text"><?= $zadanie->technik_blok_2 ?></span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="nav-tabs-custom">
                      <ul class="nav nav-tabs pull-right">
                        <li class="pull-left header"><i class="fa fa-asterisk"></i> Wzieli Udział</li>
                      </ul>
                      <div class="box-body tab-content">
                        <div class="box-body no-padding">
                    		  <ul class="users-list clearfix">
                            <?
                            $wzieli_udzial_q = all("SELECT *,
                              (SELECT `login` FROM `acp_users` WHERE `user` = `u_id`) AS `nick`,
                              (SELECT `steam_login` FROM `acp_users` WHERE `user` = `u_id`) AS `steam_login`,
                              (SELECT `steam_avatar` FROM `acp_users` WHERE `user` = `u_id`) AS `steam_avatar`
                              FROM `acp_zadania_users` WHERE `id_zadania` = $zadanie->id");

                            if(empty($wzieli_udzial_q)) { echo 'Brak uczestników..'; }
                            foreach ($wzieli_udzial_q as $wzieli_udzial) {
                            ?>
                              <li>
                                <img src="<?= $wzieli_udzial->steam_avatar ?>">
                                <a class="users-list-name" href="?x=account&id=<?= $wzieli_udzial->u_id ?>"><?= $wzieli_udzial->steam_login ?></a>
                                <span class="users-list-date">Dodany: <?= czas_relatywny($wzieli_udzial->data); ?></span>
                              </li>
                            <?
                            }
                            ?>
                    		  </ul>
                    		</div>
                      </div>
                    </div>
                  </div>
              	</div>
              </div>
            </div>
          </div>
         </div>
         <? echo $dane_publiczne->stopka($acp_system['acp_nazwa'], $acp_system['acp_wersja']); ?>
        </section>
      </div>
    </div>
  </div>
</body>
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
<script>
$(document).ready(function () {
  $('.chat').slimScroll({
    start: 'bottom',
  });
  $('.logi_hight').slimScroll({});
});
</script>
