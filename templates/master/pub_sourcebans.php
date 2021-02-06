<?
tytul_strony("Sourcebans");
$dane_publiczne = getClass('DanePubliczne');
?>
<style>
.content-wrapper { background: url('<?= $acp_system ['tlo_sourcebans']?>') no-repeat center center fixed; -webkit-background-size: cover; -moz-background-size: cover; background-size: cover; -o-background-size: cover; }
p { color: #fff; }
.img-thumbnail{ background-color: transparent; border: 0px solid #ddd;}
@media (min-width: 1200px){.container { width: 1400px; }}
</style>
<?
$logo = (empty($acp_system['logo_sourcebans'])) ? $acp_system['logo_prawa'] : $acp_system['logo_sourcebans'];
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
           <?
           $dane = $dane_publiczne->serwer_list($xx);
           foreach ($dane as $row):
             $row->banner = $dane_publiczne->serwer_banner($row->serwer_id);
             $row->status = ($row->status == 0) ? 'success' : 'danger';
           ?>
             <div class="col-sm-4">
               <a href="https://sourcebans.sloneczny-dust.pl/<?= $row->prefix_sb ?>" target="_blank">
                 <img src="<?= $row->banner ?>" class="img-fluid img-thumbnail">
               </a>
               <div class="carousel-caption">
                   <span class="label label-<?= $row->status ?>"><?= $row->mod ?></span>
                   <?= $row->graczy ?> / <?= $row->max_graczy ?> [<?= $row->boty ?>]
                   <a href="?x=pub_serwery&xx=<?= $row->serwer_id ?>"><span class="label"><i class="glyphicon glyphicon-info-sign"></i></a></span>
               </div>
             </div>
           <? endforeach; ?>
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
