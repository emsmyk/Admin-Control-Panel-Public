<script>
  $(function () {
    "use strict";

    // AREA CHART
    var area = new Morris.Area({
      element: 'iloscGraczy_Morris',
      resize: true,
      data: [
        <?= $srv->wykres_pobierz_dane('wykres_graczy_morris', 'data', $_SESSION["wyk-graczy-zakres-$serwer_id"], $serwer_id, $_SESSION["srv_det_graczy_$serwer_id"]); ?>
      ],
      xkey: 'y',
      ykeys: ['item1', 'item2'],
      labels: ['Graczy', 'Wolnych Slotów'],
      pointFillColors:['#ffffff'],
      pointStrokeColors: ['black'],
      lineColors:['red', 'gray'],
      hideHover: 'auto'
    });
    // var area = new Morris.Line({
    //   element: 'hlstats_wykres',
    //   resize: true,
    //   data: [
    //     <?= $srv->wykres_pobierz_dane('wykres_hlstats', 'data', $_SESSION["wyk-graczy-zakres-$serwer_id"], $serwer_id, $_SESSION["srv_det_graczy_$serwer_id"]); ?>
    //   ],
    //   xkey: 'y',
    //   ykeys: ['item1', 'item2', 'item3', 'item4', 'item5', 'item6'],
    //   labels: ['Graczy', 'Nowych Graczy', 'Zabójstw', 'Nowych Zabójstw', 'HS', 'Nowych HS'],
    //   lineColors: ['#3c8dbc', '#000'],
    //   hideHover: 'auto'
    // });

    // LINE CHART
    var line = new Morris.Line({
      element: 'GOSettiRANK',
      resize: true,
      data: [
          <?= $srv->wykres_pobierz_dane('gosetti', 'rank', $_SESSION["wyk-graczy-zakres-$serwer_id"], $serwer_id, $_SESSION["srv_det_gosetti_pozycja_$serwer_id"]); ?>
      ],
      xkey: 'y',
      ykeys: ['item1', 'item2'],
      labels: ['Rank Ogólnie', 'Rank Tura'],
      lineColors: ['#3c8dbc', '#000'],
      hideHover: 'auto'
    });

    //BAR CHART
    var bar = new Morris.Bar({
      element: 'GOSettiPUNKTY',
      resize: true,
      data: [
        <?= $srv->wykres_pobierz_dane('gosetti', 'punkty', $_SESSION["wyk-graczy-zakres-$serwer_id"], $serwer_id, $_SESSION["srv_det_gosetti_tura_$serwer_id"]); ?>
      ],
      barColors: ['#3498DB', '#34495E','#26B99A', '#DE8244'],
      xkey: 'y',
      ykeys: ['item1', 'item2', 'item3', 'item4'],
      labels: ['Klikniecia', 'Skiny', 'WPL', 'WWW'],
      hideHover: 'auto'
    });
  });
</script>
