<?
$dash = getClass('Wpisy');
$dane_publiczne = getClass('DanePubliczne');
?>
<style>
@media (min-width: 1200px){.container { width: 1400px; }}
</style>
<body class="hold-transition <?= $player->szablon ?> layout-top-nav">
<div class="wrapper">
<? require_once("./templates/master/przybornik/menu-header.php");  ?>
  <div class="content-wrapper">
    <div class="container">
      <section class="content">
    		<div class="row">
    		  <div class="col-lg-8">
            <?  $ostatnie_ogloszenie_one = row("SELECT *,
            (SELECT `steam_avatar` FROM `acp_users` WHERE `user` = `u_id`) AS steam_avatar,
          	(SELECT `login` FROM `acp_users` WHERE `user` = `u_id`) AS login,
          	(SELECT `steam_login` FROM `acp_users` WHERE `user` = `u_id`) AS steam_login,

          	(SELECT `nazwa` FROM `acp_wpisy_kategorie` WHERE `id` = `kategoria`) AS kategoria_nazwa
            FROM `acp_wpisy` WHERE `ogloszenie` = '1' ORDER BY `id` DESC LIMIT 1");

            $avatar = $dash->dashbord_czy_puste_av($ostatnie_ogloszenie_one->steam_avatar);
            $login = $dash->dashbord_czy_puste_login($ostatnie_ogloszenie_one->steam_login, $ostatnie_ogloszenie_one->login);

            $komentarzy = one("SELECT COUNT(*) FROM `acp_wpisy_komentarze` WHERE `wpis_id` = $ostatnie_ogloszenie_one->id");
        		$komentowalo = one("SELECT COUNT(DISTINCT `user_id`) FROM `acp_wpisy_komentarze` WHERE `wpis_id` = $ostatnie_ogloszenie_one->id");
            ?>
            <div class="box box-widget">
              <div class="box-header with-border">
          		  <div class="user-block">
          			<img class="img-circle" src="<?= $avatar; ?>">
          			<span class="username"><a href="?x=account&id=<?= $ostatnie_ogloszenie_one->u_id; ?>"><?= $login; ?></a> - <span style="word-wrap:break-word;"><?= $ostatnie_ogloszenie_one->tytul; ?></span></span>
          			<span class="description"><a href="?x=wpisy&xx=category&nazwa=<?= $ostatnie_ogloszenie_one->kategoria_nazwa; ?>&id=<?= $ostatnie_ogloszenie_one->kategoria ?>"><?= $ostatnie_ogloszenie_one->kategoria_nazwa; ?></a> - <?= czas_relatywny($ostatnie_ogloszenie_one->data); ?></span>
          		  </div>
          		</div>
              <div class="box-body ogloszonko">
          		  <p style="word-wrap:break-word;"><?= $ostatnie_ogloszenie_one->text; ?></p>
          		</div>
              <div class="box-footer">
                <a href="?x=wpisy&xx=wpis&wpis=<?= clean($ostatnie_ogloszenie_one->tytul); ?>&wpisid=<?= $ostatnie_ogloszenie_one->id; ?>"><button type="button" class="btn btn-default btn-xs"><i class="fa fa-share"></i> Czytaj całość</button></a>
          		  <span class="pull-right text-muted"><?= $komentarzy ?> komentarzy - <?= $komentowalo ?> komentujacych</span>
              </div>
            </div>
    		  </div>
          <div class="col-md-4">
             <div class="box box-widget">
               <div class="box-header with-border">
                 <div class="user-block">
                   <h3 class="box-title">Ostatnie Ogłoszenia</h3>
                 </div>
               </div>
               <div class="box-body">
                 <? $ostatnie_ogloszenie_q = all("SELECT * FROM `acp_wpisy` WHERE `ogloszenie` = 1 ORDER BY `data` DESC LIMIT 5");
                 $i = 1;
                 foreach($ostatnie_ogloszenie_q as $ostatnie_ogloszenie) { ?>
                 <p><?= $i++ ?>. <a href="?x=wpisy&=wpis&wpis=<?= clean($ostatnie_ogloszenie->tytul); ?>&wpisid=<?= $ostatnie_ogloszenie->id; ?>"><?= $ostatnie_ogloszenie->tytul ?></a> <?= $ostatnie_ogloszenie->data ?></p>
                <? } ?>
               </div>
             </div>
          </div>
          <div class="col-lg-4">
            <ul class="list-group">
              <li class="list-group-item"><a href="?x=pub_admin_list">Lista Adminów</a></li>
              <li class="list-group-item"><a href="?x=pub_changelog">Changelog</a></li>
              <li class="list-group-item"><a href="?x=pub_admin_list">Lista Map</a></li>
              <li class="list-group-item"><a href="?x=pub_hlstats_top">Top Hlstats - Historia</a></li>
              <li class="list-group-item"><a href="?x=pub_galeria_map">Galeria Map</a></li>
            </ul>
          </div>

          <div class="col-md-4">
            <div class="box box-solid">
              <?
              $dane = $dane_publiczne->serwer_list($xx);
              foreach ($dane as $index => $row):
                if ($index == 0) {
                    $row->banner_active = 'active';
                }
                $row->banner = $dane_publiczne->serwer_banner($row->serwer_id);
                $row->status = ($row->status == 0) ? 'success' : 'danger';
              endforeach;
              ?>
              <div class="box-body">
                <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                  <ol class="carousel-indicators">
                    <? foreach ($dane as $index => $row): ?>
                      <li data-target="#carousel-example-generic" data-slide-to="<?= $row->kolejnosc+1 ?>" class="<?= $row->banner_active ?>"></li>
                    <? endforeach; ?>
                  </ol>
                  <div class="carousel-inner">
                    <? foreach ($dane as $index => $row): ?>
                      <div class="item <?= $row->banner_active ?>">
                        <img src="<?= $row->banner ?>" alt="<?= $row->serwer_id ?>">

                        <div class="carousel-caption">
                          <span class="label label-<?= $row->status ?>"><?= $row->mod ?></span>
                          <?= $row->graczy ?> / <?= $row->max_graczy ?> [<?= $row->boty ?>]
                          <a href="?x=pub_serwery&xx=<?= $row->serwer_id ?>"><span class="label"><i class="glyphicon glyphicon-info-sign"></i></a></span>
                        </div>
                      </div>
                    <? endforeach; ?>
                  </div>
                  <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev"><span class="fa fa-angle-left"></span></a>
                  <a class="right carousel-control" href="#carousel-example-generic" data-slide="next"><span class="fa fa-angle-right"></span></a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-12">
            <div class="box">
              <div class="box-header with-border">
                <h3 class="box-title">Statystyki Serwerow</h3>
              </div>
              <div class="box-body">
                <div class="row">
                  <div class="col-md-12">
                    <div class="chart">
                      <canvas id="salesChart" style="height: 280px;"></canvas>
                    </div>
                  </div>
                </div>
              </div>
              <div class="box-footer">
                <div class="row">
                  <div class="col-sm-3 col-xs-6">
                    <div class="description-block border-right">
                      <h5 class="description-header"><?= one("SELECT COUNT(`ip`) FROM `acp_serwery` WHERE `test_serwer` = '0'"); ?></h5>
                      <span class="description-text">Serwerow</span>
                    </div>
                  </div>
                  <div class="col-sm-3 col-xs-6">
                    <div class="description-block border-right">
                      <h5 class="description-header"><?= one("SELECT sum(`graczy`) AS `graczy` FROM `acp_serwery` WHERE `test_serwer` = '0'"); ?></h5>
                      <span class="description-text">Graczy</span>
                    </div>
                  </div>
                  <div class="col-sm-3 col-xs-6">
                    <div class="description-block border-right">
                      <h5 class="description-header"><?= one("SELECT sum(`max_graczy`) AS `max_graczy` FROM `acp_serwery` WHERE `test_serwer` = '0'"); ?></h5>
                      <span class="description-text">Slotow</span>
                    </div>
                  </div>
                  <div class="col-sm-3 col-xs-6">
                    <div class="description-block">
                      <h5 class="description-header"><? $procentowe_zapelnienie = one("SELECT sum(`graczy`)*100/sum(`max_graczy`) AS `graczy` FROM `acp_serwery` WHERE `test_serwer` = '0'"); echo round($procentowe_zapelnienie); ?> %</h5>
                      <span class="description-text">Zapełnienie</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="box">
            <div class="box-body">
              <div class="col-lg-12 col-md-12 col-12">
                <div class="text-center mb-10">
                  <div class="row">
                    <div class="col-lg-3 col-md-4 col-6">
                      <div class="mb-4 text-center">
                        <img src="https://img.shields.io/static/v1?style=for-the-badge&label=Sourcebans&message=1.6.3&color=red" alt="">
                      </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-6">
                      <div class="mb-4 text-center">
                        <img src="https://img.shields.io/static/v1?style=for-the-badge&label=HLStats:CE&message=1.7.0&color=red" alt="">
                      </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-6">
                      <div class="mb-4 text-center">
                        <img src="https://img.shields.io/static/v1?style=for-the-badge&label=Sourcemod&message=-&color=red" alt="">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
<? require_once("./templates/master/przybornik/stopka.php");  ?>
</div>

<!-- jQuery 3 -->
<script src="./www/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="./www/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="./www/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="./www/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="./www/dist/js/adminlte.min.js"></script>
<!-- Sparkline -->
<script src="./www/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<!-- jvectormap  -->
<script src="./www/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="./www/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- SlimScroll -->
<script src="./www/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- ChartJS -->
<script src="./www/bower_components/chart.js/Chart.js"></script>
<script>
$(document).ready(function () {
  $('.ogloszonko').slimScroll({height : '450px'});
});
</script>
<script>
$(function () {

  'use strict';

  var salesChartCanvas = $('#salesChart').get(0).getContext('2d');
  var salesChart       = new Chart(salesChartCanvas);

  var salesChartData = {
    labels  : [
      <?
      $dane_wykres_q = all("SELECT LEFT(`data`, 16) AS `data_cr` FROM `acp_serwery_logs_hour` GROUP BY `data_cr` ORDER BY `data_cr` DESC LIMIT 24;");
      foreach($dane_wykres_q as $dane_wykres) {
      print("'");  print(czas_relatywny($dane_wykres->data_cr)); print("',");
      }
      ?>
    ],
    datasets: [
      {
        label               : 'Sloty',
        fillColor           : 'rgb(210, 214, 222)',
        strokeColor         : 'rgb(210, 214, 222)',
        pointColor          : 'rgb(210, 214, 222)',
        pointStrokeColor    : '#c1c7d1',
        pointHighlightFill  : '#fff',
        pointHighlightStroke: 'rgb(220,220,220)',
        data                : [
           <?
          $dane_wykres_q = all("SELECT LEFT(`data`, 16) AS `data_cr`, SUM(`sloty`) AS sum_sloty FROM `acp_serwery_logs_hour` GROUP BY `data_cr` ORDER BY `data_cr` DESC LIMIT 24;");
          foreach($dane_wykres_q as $dane_wykres) {
          print($dane_wykres->sum_sloty); print(", ");
          }
          ?>
        ]
      },
      {
        label               : 'Graczy',
        fillColor           : 'rgba(60,141,188,0.9)',
        strokeColor         : 'rgba(60,141,188,0.8)',
        pointColor          : '#3b8bba',
        pointStrokeColor    : 'rgba(60,141,188,1)',
        pointHighlightFill  : '#fff',
        pointHighlightStroke: 'rgba(60,141,188,1)',
        data                : [
           <?
          $dane_wykres_q = all("SELECT LEFT(`data`, 16) AS `data_cr`, SUM(`graczy`) AS sum_graczy FROM `acp_serwery_logs_hour` GROUP BY `data_cr` ORDER BY `data_cr` DESC LIMIT 24; ");
          foreach($dane_wykres_q as $dane_wykres) {
          print($dane_wykres->sum_graczy); print(", ");
          }
          ?>
        ]
      }
    ]
  };

  var salesChartOptions = {
    showScale               : true,
    scaleShowGridLines      : false,
    scaleGridLineColor      : 'rgba(0,0,0,.05)',
    scaleGridLineWidth      : 1,
    scaleShowHorizontalLines: true,
    scaleShowVerticalLines  : true,
    bezierCurve             : true,
    bezierCurveTension      : 0.3,
    pointDot                : false,
    pointDotRadius          : 4,
    pointDotStrokeWidth     : 1,
    pointHitDetectionRadius : 20,
    datasetStroke           : true,
    datasetStrokeWidth      : 2,
    datasetFill             : true,
    legendTemplate          : '<ul class=\'<%=name.toLowerCase()%>-legend\'><% for (var i=0; i<datasets.length; i++){%><li><span style=\'background-color:<%=datasets[i].lineColor%>\'></span><%=datasets[i].label%></li><%}%></ul>',
    maintainAspectRatio     : true,
    responsive              : true
  };

  salesChart.Line(salesChartData, salesChartOptions);

  var pieChartCanvas = $('#pieChart').get(0).getContext('2d');
  var pieChart       = new Chart(pieChartCanvas);
  var PieData        = [
    {
      value    : 700,
      color    : '#f56954',
      highlight: '#f56954',
      label    : 'Chrome'
    },
    {
      value    : 500,
      color    : '#00a65a',
      highlight: '#00a65a',
      label    : 'IE'
    },
    {
      value    : 400,
      color    : '#f39c12',
      highlight: '#f39c12',
      label    : 'FireFox'
    },
    {
      value    : 600,
      color    : '#00c0ef',
      highlight: '#00c0ef',
      label    : 'Safari'
    },
    {
      value    : 300,
      color    : '#3c8dbc',
      highlight: '#3c8dbc',
      label    : 'Opera'
    },
    {
      value    : 100,
      color    : '#d2d6de',
      highlight: '#d2d6de',
      label    : 'Navigator'
    }
  ];
  var pieOptions     = {
    segmentShowStroke    : true,
    segmentStrokeColor   : '#fff',
    segmentStrokeWidth   : 1,
    percentageInnerCutout: 50, // This is 0 for Pie charts
    animationSteps       : 100,
    animationEasing      : 'easeOutBounce',
    animateRotate        : true,
    animateScale         : false,
    responsive           : true,
    maintainAspectRatio  : false,
    legendTemplate       : '<ul class=\'<%=name.toLowerCase()%>-legend\'><% for (var i=0; i<segments.length; i++){%><li><span style=\'background-color:<%=segments[i].fillColor%>\'></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>',
    tooltipTemplate      : '<%=value %> <%=label%> users'
  };

  pieChart.Doughnut(PieData, pieOptions);

  $('#world-map-markers').vectorMap({
    map              : 'world_mill_en',
    normalizeFunction: 'polynomial',
    hoverOpacity     : 0.7,
    hoverColor       : false,
    backgroundColor  : 'transparent',
    regionStyle      : {
      initial      : {
        fill            : 'rgba(210, 214, 222, 1)',
        'fill-opacity'  : 1,
        stroke          : 'none',
        'stroke-width'  : 0,
        'stroke-opacity': 1
      },
      hover        : {
        'fill-opacity': 0.7,
        cursor        : 'pointer'
      },
      selected     : {
        fill: 'yellow'
      },
      selectedHover: {}
    },
    markerStyle      : {
      initial: {
        fill  : '#00a65a',
        stroke: '#111'
      }
    },
    markers          : [
      { latLng: [41.90, 12.45], name: 'Vatican City' },
      { latLng: [43.73, 7.41], name: 'Monaco' },
      { latLng: [-0.52, 166.93], name: 'Nauru' },
      { latLng: [-8.51, 179.21], name: 'Tuvalu' },
      { latLng: [43.93, 12.46], name: 'San Marino' },
      { latLng: [47.14, 9.52], name: 'Liechtenstein' },
      { latLng: [7.11, 171.06], name: 'Marshall Islands' },
      { latLng: [17.3, -62.73], name: 'Saint Kitts and Nevis' },
      { latLng: [3.2, 73.22], name: 'Maldives' },
      { latLng: [35.88, 14.5], name: 'Malta' },
      { latLng: [12.05, -61.75], name: 'Grenada' },
      { latLng: [13.16, -61.23], name: 'Saint Vincent and the Grenadines' },
      { latLng: [13.16, -59.55], name: 'Barbados' },
      { latLng: [17.11, -61.85], name: 'Antigua and Barbuda' },
      { latLng: [-4.61, 55.45], name: 'Seychelles' },
      { latLng: [7.35, 134.46], name: 'Palau' },
      { latLng: [42.5, 1.51], name: 'Andorra' },
      { latLng: [14.01, -60.98], name: 'Saint Lucia' },
      { latLng: [6.91, 158.18], name: 'Federated States of Micronesia' },
      { latLng: [1.3, 103.8], name: 'Singapore' },
      { latLng: [1.46, 173.03], name: 'Kiribati' },
      { latLng: [-21.13, -175.2], name: 'Tonga' },
      { latLng: [15.3, -61.38], name: 'Dominica' },
      { latLng: [-20.2, 57.5], name: 'Mauritius' },
      { latLng: [26.02, 50.55], name: 'Bahrain' },
      { latLng: [0.33, 6.73], name: 'São Tomé and Príncipe' }
    ]
  });


  $('.sparkbar').each(function () {
    var $this = $(this);
    $this.sparkline('html', {
      type    : 'bar',
      height  : $this.data('height') ? $this.data('height') : '30',
      barColor: $this.data('color')
    });
  });

  $('.sparkpie').each(function () {
    var $this = $(this);
    $this.sparkline('html', {
      type       : 'pie',
      height     : $this.data('height') ? $this.data('height') : '90',
      sliceColors: $this.data('color')
    });
  });

  $('.sparkline').each(function () {
    var $this = $(this);
    $this.sparkline('html', {
      type     : 'line',
      height   : $this.data('height') ? $this.data('height') : '90',
      width    : '100%',
      lineColor: $this.data('linecolor'),
      fillColor: $this.data('fillcolor'),
      spotColor: $this.data('spotcolor')
    });
  });
});
</script>
