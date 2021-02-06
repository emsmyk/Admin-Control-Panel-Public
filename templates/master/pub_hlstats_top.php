<?
$dane_publiczne = getClass('DanePubliczne');
?>
<style>
.content-wrapper { background: url('<?= $acp_system ['tlo_changelog']?>') no-repeat center center fixed; -webkit-background-size: cover; -moz-background-size: cover; background-size: cover; -o-background-size: cover; }
p { color: #fff; }
@media (min-width: 1200px){.container { width: 1400px; }}
</style>
<?
$logo = (empty($acp_system['logo_changelog'])) ? $acp_system['logo_prawa'] : $acp_system['logo_changelog'];
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
            <div class="box box">
       				<div class="box-body">
                <? $id = (int)$_GET['id'];
                if(!empty($id)): ?>
                <? $dane_pobrania = $dane_publiczne->hlstats_top_details_dane($id); tytul_strony("Top50 - $dane_pobrania->mod - $dane_pobrania->data"); ?>
                <div class="col-md-12">
                  <div class="box box-widget widget-user">
                    <div class="widget-user-header bg-blue">
                      <h5 class="widget-user-username">Mod: <?= $dane_pobrania->mod ?></h5>
                      <h5 class="widget-user-desc">Nazwa Serwera: <?= $dane_pobrania->nazwa ?></h5>
                      <ul class="nav nav-stacked">
                        <li>Pobrano dane: <?= $dane_pobrania->data ?></li>
                      </ul>
                    </div>
                  </div>
                </div>
                <div class="col-md-12">
                  <table id="hlstats_details" class="table table-bordered table-striped" width="100%">
                   <thead>
                     <tr>
                       <th scope="col">Pozycja</th>
                       <th scope="col">Nick</th>
                       <th scope="col">STEAM</th>
                       <th scope="col">Punkty</th>
                       <th scope="col">Czas Gry</th>
                       <th scope="col">Zabójstw</th>
                       <th scope="col">Śmierci</th>
                       <th scope="col">HS</th>
                       <th scope="col">Headshots</th>
                       <th scope="col">HS:K</th>
                       <th scope="col">Accuracy</th>
                     </tr>
                   </thead>
                   <tbody>
                     <? $dane = $dane_publiczne->hlstats_top_details($id);
                     $i = 1;
                     foreach ($dane as $row):
                       $row->steam_comunity = toCommunityID("STEAM_0:".$row->steam);
                     ?>
                     <tr>
                       <td scope="row"><?= $i++ ?></td>
                       <td><a href="https://hlstats.sloneczny-dust.pl/hlstats.php?mode=playerinfo&player=<?= $row->playerId ?>"><?= $row->lastName ?></a></td>
                       <td><a href="https://steamcommunity.com/profiles/<?= $row->steam_comunity ?>"><?= $row->steam_comunity ?></a></td>
                       <td><?= $row->skill ?></td>
                       <td><?= sek_na_tekst($row->connection_time) ?></td>
                       <td><?= $row->kills ?></td>
                       <td><?= $row->deaths ?></td>
                       <td><?= $row->kpd ?></td>
                       <td><?= $row->headshots ?></td>
                       <td><?= $row->hpk ?></td>
                       <td><?= $row->acc ?></td>
                     </tr>
                      <? endforeach; ?>
                   </tbody>
                  </table>
                </div>
              <? elseif(!empty($xx)): tytul_strony("Top50");?>
              <table id="hlstats_top" class="table table-bordered table-striped" width="100%">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Nazwa</th>
                    <th>Data</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  <?
                  $dane = $dane_publiczne->hlstats_top_list($xx);
                  foreach ($dane as $row):
                  ?>
                  <tr>
                    <td><?= $row->id ?></td>
                    <td><a href="<?= "?x=$x&xx=$xx&id=$row->id" ?>"><?= $row->nazwa ?></a></td>
                    <td><a href="<?= "?x=$x&xx=$xx&id=$row->id" ?>"><?= $row->new_data ?></a></td>
                    <td>
                      <a href="<?= "?x=$x&xx=$xx&id=$row->id" ?>"><button type="button" class="btn btn-primary"><i class="fa fa-ellipsis-h"></i></button></a>
                    </td>
                  </tr>
                  <? endforeach; ?>
                </tbody>
              </table>
              <?  else: tytul_strony("Top50");?>
              <table id="serwery" class="table table-bordered table-striped" width="100%">
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
              <? endif; ?>
              </div>
            </div>
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
<?= js_table_one('#serwery', 'asc', 0, 10); ?>
<?= js_table_one('#hlstats_top', 'desc', 2, 10); ?>
<?= js_table_one('#hlstats_details', 'asc', 0, 25); ?>
