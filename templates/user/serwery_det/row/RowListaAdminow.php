<div class="row">
  <div class="col-lg-12">
    <div class="box">
      <div class="box-header with-border">
        <i class="fa fa-users fa-fw"></i>
        <h3 class="box-title">Lista Adminów</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
      </div>
      <div class="box-body">
        <? $lista_adminow = row("SELECT * FROM `acp_serwery_listaadminow` WHERE `serwer` = $serwer_id LIMIT 1");
        if(!empty($lista_adminow)):
          $lista_adminow->dane = json_decode($lista_adminow->dane);
        ?>
        <div class="row">
          <div class="col-md-11">
            <table width="100%" class="table table-striped table-bordered table-hover" id="tab_lista_adminow">
              <thead>
                <tr>
                  <th>ID</th>
                  <th></th>
                  <th>Nick (Steam)</th>
                  <th>Steam</th>
                  <th>Ranga</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
              <?
              $lista_adminow_cache = json_decode(one("SELECT `dane` FROM `acp_cache_api` WHERE `get` = 'serwer_id".$serwer_id."_admin' LIMIT 1"));
              $api_api_sb_admins = $api->api_11_2020('sb', $config['site'], $acp_system['api_sb_host'], $acp_system['api_sb_db'], $acp_system['api_sb_user'], $acp_system['api_sb_pass'], "&xx=admins&srv=$srv_dane->prefix_sb", "GET", 10);

              $lista_adminow_dane = new stdClass();
              $lista_adminow_dane->adminow = 0;
              $lista_adminow_dane->legenda = 0;
              $lista_adminow_dane->nieznana = 0;
              $lista_adminow_dane->zgodnosc_nicku_tak = 0;
              $lista_adminow_dane->zgodnosc_nicku_nie = 0;

              if(!empty($api_api_sb_admins)):
                foreach($api_api_sb_admins as $adm_list):
                  //wzbogacenie danych
                  foreach ($lista_adminow_cache as $lista_adminow_cache_dane) {
                    if($lista_adminow_cache_dane->user == $adm_list->user){
                      $adm_list->steam = $lista_adminow_cache_dane->steam;
                      $adm_list->steam_nick = $lista_adminow_cache_dane->steam_nick;
                      $adm_list->steam_avatar = $lista_adminow_cache_dane->steam_avatar;
                      $adm_list->steam_profileurl = $lista_adminow_cache_dane->steam_profileurl;
                      $adm_list->steam_status = $lista_adminow_cache_dane->steam_status;
                      $adm_list->steam_lastlogoff = date("Y-m-d H:i:s", $lista_adminow_cache_dane->steam_lastlogoff);
                    }
                  }

                  $adm_list->srv_group = ($adm_list->srv_group) ?: 'Nieznana';

                  if($adm_list->steam_status == 0) {
                    $adm_list->steam_status_dot = '<i class="fa fa-circle text-red"></i>';
                  }
                  elseif($adm_list->steam_status == 1) {
                     $adm_list->steam_status_dot = '<i class="fa fa-circle text-green"></i>';
                  }
                  elseif($adm_list->steam_status == 3 || $adm_list->steam_status == 4) {
                    $adm_list->steam_status_dot = '<i class="fa fa-circle text-yellow"></i>';
                  }
                  else{
                    $adm_list->steam_status_dot = '<i class="fa fa-circle text-white"></i>';
                  }

                  // ilosc adminow w tabeli
                  $tab_lista_adminow_ilosc = $lista_adminow->ilosc_adminow;
                  //wykluczenia z ustawien listy
                  $grupy_wykluczone =  array();
                  if($lista_adminow->dane->pokaz_legende == 0) {
                    array_push($grupy_wykluczone, "Legenda");
                  }
                  if($lista_adminow->dane->pokaz_weteran == 0) {
                    array_push($grupy_wykluczone, "Weteran");
                  }
                  if($lista_adminow->dane->pokaz_bez_uprawnien == 0) {
                    array_push($grupy_wykluczone, "Nieznana");
                  }
                  if($lista_adminow->dane->pokaz_opiekuna == 0) {
                    array_push($grupy_wykluczone, "Opiekun");
                  }
                  if($lista_adminow->dane->pokaz_zastepce == 0) {
                    array_push($grupy_wykluczone, "Starszy Admin");
                  }

                  if(isset($adm_list->srv_group)){
                    if($adm_list->srv_group == "Admin"){ $lista_adminow_dane->adminow = $lista_adminow_dane->adminow + 1; }
                    if($adm_list->srv_group == "Legenda"){ $lista_adminow_dane->legenda = $lista_adminow_dane->legenda + 1; }
                    if($adm_list->srv_group == "Nieznana"){ $lista_adminow_dane->nieznana = $lista_adminow_dane->nieznana + 1; }
                  }
                  else {
                    $lista_adminow_dane->adminow = 0;
                    $lista_adminow_dane->legenda = 0;
                    $lista_adminow_dane->nieznana = 0;
                  }

                  if($adm_list->steam_status == 1 && $adm_list->srv_group != "Nieznana"){ $lista_adminow_dane->steam_status_online = $lista_adminow_dane->steam_status_online + 1; }
                  if($adm_list->steam_status == 0 && $adm_list->srv_group != "Nieznana"){ $lista_adminow_dane->steam_status_offline = $lista_adminow_dane->steam_status_offline + 1; }
                  if($adm_list->steam_status == 3 && $adm_list->srv_group != "Nieznana"){ $lista_adminow_dane->steam_status_away = $lista_adminow_dane->steam_status_away + 1; }
                  if($adm_list->steam_status == 4 && $adm_list->srv_group != "Nieznana"){ $lista_adminow_dane->steam_status_snooze = $lista_adminow_dane->steam_status_snooze + 1; }


                  // zgodność nicku steam oraz loginu api_sourcebans
                  if($adm_list->srv_group == "Admin") {
                    similar_text(preg_replace('/[^a-z]{1,}/i','', strtolower($adm_list->steam_nick)), preg_replace('/[^a-z]{1,}/i','', strtolower($adm_list->user)), $adm_list->zgodnosc_nicku_prc);
                    $adm_list->zgodnosc_nicku = ($adm_list->zgodnosc_nicku_prc > 50) ? 1 : 0;
                    if($adm_list->zgodnosc_nicku == 1){
                      $lista_adminow_dane->zgodnosc_nicku_tak = $lista_adminow_dane->zgodnosc_nicku_tak + 1;
                    } else {
                      $lista_adminow_dane->zgodnosc_nicku_nie = $lista_adminow_dane->zgodnosc_nicku_nie + 1;
                    }

                    $lista_adminow_dane->zgodnosc_nicku_prc_ogolnie = round($lista_adminow_dane->zgodnosc_nicku_tak*100/($lista_adminow_dane->zgodnosc_nicku_tak + $lista_adminow_dane->zgodnosc_nicku_nie));
                  }

                  // puste nadanie wartości..
                  $adm_list->steam_nick = (empty($adm_list->steam_nick)) ? 'Nieznany' : $adm_list->steam_nick;
                if(!in_array($adm_list->srv_group, $grupy_wykluczone)):
                  // tablica do raportu listy adminów
                  $edit_admin[] = $adm_list;
                  $raport_array[] = $adm_list;
              ?>
              <tr class="odd gradeX">
                <td><?= $adm_list->aid ?></td>
                <td><img src="<?= $adm_list->steam_avatar ?>" width="36px" height="auto"></td>
                <td><?= $adm_list->steam_status_dot ?> <?= $adm_list->user ?> <i>(<?= $adm_list->steam_nick ?>)</i></td>
                <td><a href="<?= $adm_list->steam_profileurl ?>" target="_blank"><?= toCommunityID($adm_list->authid) ?></a> <br><small><i>(Ostatnio: <?= czas_relatywny($adm_list->steam_lastlogoff) ?>)</i></small></td>
                <td><?= $adm_list->srv_group ?></td>
                <td>
                  <form method="post">
                    <div class="btn-group">
                      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#admin_list_edit_<?= $adm_list->aid ?>"><i class="fa fa-edit"></i> Edytuj</a></button>
                      <button type="button" class="btn btn-info" data-toggle="modal" data-target="#admin_list_rezygnacja_<?= $adm_list->aid ?>"><i class="fa fa-frown-o"></i> Rezygnacja</a></button>
                      <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#admin_list_degradacja_<?= $adm_list->aid ?>"><i class="fa fa-minus"></i> Degradacja</a></button>
                      <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#admin_list_usun_<?= $adm_list->aid ?>"><i class="fa fa-remove"></i> Usuń</a></button>
                    </div>
                  </fom>
                </td>
              </tr>
              <?
                endif;
                endforeach;
                $lista_adminow_dane->liczba_all = count($api_api_sb_admins);
              else:
              ?>
                <tr class="odd gradeX"> <td colspan="5">Błąd pobierania danych! Zgłoś błąd administracji..</id></tr>
              <? endif; ?>
              </tbody>
            </table>
          </div>
          <div class="col-md-1">
            <button type="button" class="btn btn-app bg-default" data-toggle="modal" data-target="#admin_list_ustawienia"><i class="fa fa-gear"></i>Ustawienia</a></button>
            <button type="button" class="btn btn-app bg-default" data-toggle="modal" data-target="#admin_list_raport"><i class="fa fa-paperclip"></i>Raport</a></button>
            <button type="button" class="btn btn-app bg-default" data-toggle="modal" data-target="#admin_list_add_admin"><i class="fa fa-plus"></i>Dodaj Admina</a></button>
          </div>
        </div>
        <? else:?>
        <div class="row">
          <div class="col-md-12">
            <p>Serwer nie posiada skonfigurowanej list adminów.. Przejdź <a href="#" data-toggle="modal" data-target="#admin_list_ustawienia">tutaj</a> aby uzupełnić ustawienia..</p>
          </div>
        </div>
        <? endif; ?>
      </div>
      <div class="box-footer">
        <div class="row">
          <div class="col-sm-3 col-xs-6">
            <div class="description-block border-right">
              <h4>Kadra</h4>
              <ul class="list-group">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  Adminów <span class="badge badge-primary badge-pill"><?= ($lista_adminow_dane->adminow) ?: '0';?></span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  Legend <span class="badge badge-primary badge-pill"><?= ($lista_adminow_dane->legenda) ?: '0';?></span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  Bez Uprawnień <span class="badge badge-primary badge-pill"><?= ($lista_adminow_dane->nieznana) ?: '0';?></span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  Łącznie <span class="badge badge-primary badge-pill"><?= ($lista_adminow_dane->liczba_all) ?: '0';?></span>
                </li>
              </ul>
            </div>
          </div>
          <div class="col-sm-3 col-xs-6">
            <div class="description-block border-right">
              <h4>Steam Status</h4>
              <ul class="list-group">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  Online <span class="badge badge-primary badge-pill"><?= ($lista_adminow_dane->steam_status_online) ?: '0';?></span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  Offline <span class="badge badge-primary badge-pill"><?= ($lista_adminow_dane->steam_status_offline) ?: '0';?></span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                   Away <span class="badge badge-primary badge-pill"><?= ($lista_adminow_dane->steam_status_away) ?: '0';?></span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  Snooze <span class="badge badge-primary badge-pill"><?= ($lista_adminow_dane->steam_status_snooze) ?: '0';?></span>
                </li>
              </ul>
            </div>
          </div>
          <div class="col-sm-3 col-xs-6">
            <div class="description-block border-right">
              <h4>Składki <br><small>Składki pobierane są z usług systemu ACP</small></h4>
              <ul class="list-group">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  Opłacone <span class="badge badge-primary badge-pill"><?= '0'; ?></span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  Nieopłacone <span class="badge badge-primary badge-pill"><?= '0'; ?></span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  Procent <span class="badge badge-primary badge-pill"><?= '0'; ?> %</span>
                </li>
              </ul>
            </div>
          </div>
          <div class="col-sm-3 col-xs-6">
            <div class="description-block border-right">
              <h4>Zgodność Nicku <br><small>Sprawdzenie pokrycia się nicku steam z nickiem sourcebans</small></h4>
              <ul class="list-group">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  Tak <span class="badge badge-primary badge-pill"><?= ($lista_adminow_dane->zgodnosc_nicku_tak) ?: '0';?></span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  Nie <span class="badge badge-primary badge-pill"><?= ($lista_adminow_dane->zgodnosc_nicku_nie) ?: '0';?></span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  Procent <span class="badge badge-primary badge-pill"><?= ($lista_adminow_dane->zgodnosc_nicku_prc_ogolnie) ?: '0'; ?> %</span>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
