<?
$dane_publiczne = getClass('DanePubliczne');
$dane = $dane_publiczne->serwer_list($xx);
tytul_strony("GoSetti - Oddaj głosy");
foreach ($dane as $key => $value):
?>
<section class="content-header" style="<? echo $dane_publiczne->copy_pase('MICHU TY KU*** PRZESTAŃ MI KOD PRZEŚLADOWAĆ', 50); ?>">
  <h1>
    <?= $value->nazwa ?>
    <small>IP: <?= $value->ip ?>:<?= $value->port?></small>
  </h1>
</section>
<div class="row">
  <div class="col-lg-12">
    <section class="col-lg-9 connectedSortable ui-sortable" style="<? echo $dane_publiczne->copy_pase('MICHU TY KU*** PRZESTAŃ MI KOD PRZEŚLADOWAĆ', 50); ?>">
      <iframe src="https://gosetti.pl/serwery/<?= $value->ip ?>:<?= $value->port?>#server-voting-methods-votes" width="100%" height="700px" FRAMEBORDER="no" scrolling="no" BORDER="0" style="
      -webkit-transform:scale(0.75);
      -moz-trasform-scale(0.75);
      <? echo $dane_publiczne->copy_pase('MICHU TY KU*** PRZESTAŃ MI KOD PRZEŚLADOWAĆ', 50); ?>
      ">
        <p>Your browser does not support iframes.</p>
      </iframe>
    </section>
    <section class="col-lg-3 connectedSortable ui-sortable">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-widget widget-user-2">
           <div class="widget-user-header bg-yellow">
             <h3>Statystyki</h3>
             <h5>z poprzednich dni..</h5>
           </div>
           <div class="box-footer no-padding">
             <ul class="nav nav-stacked">
               <?
               $color_array = array(1 => 'bg-blue', 2 => 'bg-green', 3=> 'bg-red', );
               $i = 0;
               $gosetti = all("SELECT `gosetti_p_klik_tura`, `data` FROM `acp_serwery_gosetti` WHERE `serwer_id` = $value->serwer_id ORDER BY `acp_serwery_gosetti`.`data` DESC LIMIT 5");
               foreach ($gosetti as $key => $value):
               ?>
                 <li><a href="#"><?= date('Y-m-d', strtotime($value->data. ' - 1 days')) ?> <span class="pull-right badge <?= $color_array[$i++]?>"><?=  $value->gosetti_p_klik_tura ?> punktów</span></a></li>
               <? endforeach; ?>
            </ul>
           </div>
         </div>
         </div>
       </div>
    </section>
  </div>
</div>
<? endforeach; ?>
