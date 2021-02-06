<?
$dane_publiczne = getClass('DanePubliczne');
$galeria = getClass('GaleriaMap');
?>
<style>
.content-wrapper { background: url('<?= $acp_system ['tlo_galeria_map']?>') no-repeat center center fixed; -webkit-background-size: cover; -moz-background-size: cover; background-size: cover; -o-background-size: cover; }
p { color: #fff; }
@media (min-width: 1200px){.container { width: 1400px; }}
</style>
<?
$logo = (empty($acp_system['logo_galeria_map'])) ? $acp_system['logo_prawa'] : $acp_system['logo_galeria_map'];
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
           <section class="col-lg-12">
               <p><? if(!empty($_SESSION['msg'])){ echo $_SESSION['msg']; unset($_SESSION['msg']); } ?></p>
           </section >
         </div>
         <div class="row text-center text-lg-left">
           <a href="<?= $acp_system['strona_www'] ?>"><img src="<?= $logo ?>"></a>
         </div>
         <? echo $dane_publiczne->menu($x, $acp_system['acp_strona_www'], $acp_system['acp_nazwa']); ?>
    		 <div class="row">
           <div class="col-lg-12">
             <?
             if($xx):
               $czy_istnieje_serwer = one("SELECT `serwer_id` FROM `acp_serwery` WHERE `serwer_id` = $id LIMIT 1");
               $dane_publiczne->serwer_czy_istnieje($xx);
             ?>
             <? $dane = $dane_publiczne->serwer_details($xx); tytul_strony("Serwer $dane->nazwa");?>
             <section class="col-lg-8 connectedSortable ui-sortable">
                <div class="box box-default">
                  <div class="box-header with-border">
                    <i class="fa fa-list"></i> <h3 class="box-title"><?= $dane->nazwa ?></h3>
                  </div>
                  <div class="box-body with-border">
                    <div class="col-lg-5">
                      <p><img src="<?= ($dane->mapa_img)?: './www/maps/nomap.jpg'; ?>" class="img-thumbnail"></p>
                    </div>
                    <div class="col-lg-7">
                      <h5>Nazwa: <?= $dane->nazwa ?></h5>
                      <h5>Mod: <?= $dane->mod ?></h5>
                      <h5>Mapa: <?= $dane->mapa ?></h5>
                      <h5>Graczy: <?= $dane->graczy ?>/<?= $dane->max_graczy ?> [<?= $dane->boty ?>]</h5>
                    </div>
                  </div>
                </div>
                <div class="box box-default">
                  <div class="box-header with-border">
                    <i class="fa fa-users"></i> <h3 class="box-title">Aktualnie na serwerze <small>(Łącznie Graczy <?= $dane->hlstats->hls_graczy ?> w tym <?= $dane->hlstats->hls_nowych_graczy ?> nowych)</small></h3>
                  </div>
                  <div class="box-body">
                    <table id="lista_graczy" class="table table-bordered table-striped" width="100%">
                      <thead>
                        <tr>
                          <th>Nick</th>
                          <th>Fragi</th>
                          <th>Czas</th>
                        </tr>
                      </thead>
                      <tbody>
                        <? foreach ($dane->graczy_live as $graczy_live): ?>
                        <tr>
                          <td><?= $graczy_live->Name ?></td>
                          <td><?= $graczy_live->Frags ?></td>
                          <td><?= $graczy_live->TimeF ?></td>
                        </tr>
                        <? endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </section>
             <section class="col-lg-4 connectedSortable ui-sortable">
               <div class="row">
                 <div class="col-md-12">
                  <? if($dane->ser_a_jr != 0 && !empty($dane->junioradmin->steam_avatar)): ?>
                    <div class="box box-widget widget-user-2">
                      <div class="widget-user-header bg-yellow">
                        <div class="widget-user-image">
                          <img class="img-circle" src="<?= $dane->junioradmin->steam_avatar ?>" alt="User Avatar">
                        </div>
                        <h3 class="widget-user-username">Junior Admin</h3>
                        <h5 class="widget-user-desc"><?= $dane->junioradmin->steam_login ?></h5>
                      </div>
                    </div>
                  <? endif; ?>
                  <? if($dane->ser_a_opiekun != 0 && !empty($dane->opiekun->steam_avatar)): ?>
                    <div class="box box-widget widget-user-2">
                      <div class="widget-user-header bg-purple">
                        <div class="widget-user-image">
                          <img class="img-circle" src="<?= $dane->opiekun->steam_avatar ?>" alt="User Avatar">
                        </div>
                        <h3 class="widget-user-username">Opiekun</h3>
                        <h5 class="widget-user-desc"><?= $dane->opiekun->steam_login ?></h5>
                      </div>
                    </div>
                  <? endif; ?>
                  <? if($dane->ser_a_copiekun != 0 && !empty($dane->zastepca->steam_avatar)): ?>
                    <div class="box box-widget widget-user-2">
                      <div class="widget-user-header bg-navy">
                        <div class="widget-user-image">
                          <img class="img-circle" src="<?= $dane->zastepca->steam_avatar ?>" alt="User Avatar">
                        </div>
                        <h3 class="widget-user-username">Zastępca</h3>
                        <h5 class="widget-user-desc"><?= $dane->zastepca->steam_login ?></h5>
                      </div>
                    </div>
                  <? endif; ?>
                  </div>
                </div>
                <ul class="nav nav-pills nav-stacked">
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="?x=pub_admin_list&xx=<?= $xx ?>">Lista Adminów</a>
                  </li>
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="?x=pub_changelog&xx=<?= $xx ?>">Changelog</a>
                  </li>
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="?x=pub_galeria_map&xx=<?= $xx ?>">Lista Map</a>
                  </li>
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="?x=pub_hlstats_top&xx=<?= $xx ?>">Top Hlstats - Historia</a>
                  </li>
                  <? if(!empty($dane->regulamin->link)): ?>
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="<?= $dane->regulamin->link ?>">Regulamin</a>
                  </li>
                  <? endif; ?>
                </ul>
              </section>



            <?  else: tytul_strony("Serwery");?>
             <div class="box box">
               <div class="box-body">
                 <table id="example-top" class="table table-bordered table-striped" width="100%">
                   <thead>
                     <tr>
                       <th></th>
                       <th>Serwer</th>
                       <th>Mod</th>
                       <th></th>
                     </tr>
                   </thead>
                   <tbody>
                     <?
                     $dane = $dane_publiczne->serwer_list($xx);
                     foreach ($dane as $row):
                     ?>
                     <tr>
                       <td><?= $row->istotnosc ?></td>
                       <td><a href="<?= "?x=$x&xx=$row->serwer_id" ?>"><?= $row->nazwa ?></a></td>
                       <td><?= $row->mod ?></td>
                       <td>
                         <a href="<?= "?x=$x&xx=$row->serwer_id" ?>"><button type="button" class="btn btn-primary"><i class="fa fa-ellipsis-h"></i></button></a>
                       </td>
                     </tr>
                     <? endforeach; ?>
                   </tbody>
                 </table>
               </div>
             </div>
             <? endif; ?>
           </div>
         </div>

         <? echo $dane_publiczne->social(); ?>
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
<?= js_table_defaults(); ?>
<?= js_table_one('#example-top', 'asc', 0, 10); ?>
<?= js_table_one('#lista_graczy', 'desc', 1, 10); ?>
