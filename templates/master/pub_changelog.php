<?
tytul_strony("Changelog");
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
              <?
              if($xx):
                $czy_istnieje_serwer = one("SELECT `serwer_id` FROM `acp_serwery` WHERE `serwer_id` = $id LIMIT 1");
                $dane_publiczne->serwer_czy_istnieje($xx);
              ?>
              <table id="example-top" class="table table-bordered table-striped" width="100%">
               <thead>
                 <tr>
                   <th scope="col">Data</th>
                   <th scope="col">Tekst</th>
                   <th scope="col">Dodał</th>
                 </tr>
               </thead>
               <tbody>
                 <?
                 $dane = $dane_publiczne->changelog_list($xx);
                 foreach ($dane as $row):
                 ?>
                 <tr>
                   <td><?= $row->data ?></td>
                   <td><?= $row->tekst ?></td>
                   <td><?= $row->user_name ?></td>
                 </tr>
                  <? endforeach; ?>
               </tbody>
              </table>
              <?  else: ?>
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
              <?
              endif;
              ?>
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
<?= js_table_one('#example-top'); ?>
