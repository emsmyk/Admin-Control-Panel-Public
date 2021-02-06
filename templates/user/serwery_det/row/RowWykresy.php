<div class="row">
  <div class="col-lg-12">
    <div class="box">
      <div class="box-header with-border">
        <i class="glyphicon glyphicon-stats"></i>
        <h3 class="box-title">Ilość Graczy (<?= $_SESSION["wyk-graczy-zakres-$serwer_id"] ?>)</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button type="button" class="btn btn-box-tool" data-toggle="modal" data-target="#wykresy"><i class="fa fa-wrench"></i></button>
          <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
      </div>
      <div class="box-body">
        <div class="chart" id="iloscGraczy_Morris" style="height: 300px;"></div>
      </div>
    </div>
  </div>
  <div class="col-lg-6">
    <div class="box">
      <div class="box-header with-border">
        <i class="glyphicon glyphicon-stats"></i>
        <h3 class="box-title">GoSetti Pozycja</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button type="button" class="btn btn-box-tool" data-toggle="modal" data-target="#wykresy"><i class="fa fa-wrench"></i></button>
          <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
      </div>
      <div class="box-body">
        <div class="chart" id="GOSettiRANK" style="height: 300px;"></div>
      </div>
    </div>
  </div>
  <div class="col-lg-6">
    <div class="box">
      <div class="box-header with-border">
        <i class="glyphicon glyphicon-stats"></i>
        <h3 class="box-title">GoSetti Punkty</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button type="button" class="btn btn-box-tool" data-toggle="modal" data-target="#wykresy"><i class="fa fa-wrench"></i></button>
          <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
      </div>
      <div class="box-body">
        <div class="chart" id="GOSettiPUNKTY" style="height: 300px;"></div>
      </div>
    </div>
  </div>

  <div class="col-md-12">
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#HexTags" data-toggle="tab">Rangi</a></li>
        <li><a href="#Reklamy" data-toggle="tab">Reklamy</a></li>
        <li><a href="#BazyDanych" data-toggle="tab">Bazy Danych</a></li>
        <li><a href="#Mapy" data-toggle="tab">Mapy</a></li>
      </ul>
      <div class="tab-content serwery_konfiguracja table-responsive no-padding">
        <div class="tab-pane active" id="HexTags">
          <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
              <tr>
                <th>Flags</th>
                <th>Ranga Tabela</th>
                <th>Ranga Say</th>
                <th>Komentarz</th>
                <th></th>
              </tr>
              <?= $srv->get_table_serwer_file('hextags', $serwer_id, $dostep->serwery_det_SerKonfiguracjaRangi, $dostep->serwery_det_SerKonfiguracjaALL); ?>
            </table>
          </div>
        </div>
        <div class="tab-pane" id="Reklamy">
          <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
              <tr>
                <th>Gdzie</th>
                <th>Tekst</th>
                <th></th>
              </tr>
              <?= $srv->get_table_serwer_file('reklamy', $serwer_id, $dostep->serwery_det_SerKonfiguracjaReklamy, $dostep->serwery_det_SerKonfiguracjaALL); ?>
            </table>
          </div>
        </div>
        <div class="tab-pane" id="BazyDanych">
          <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
              <tr>
                <th>Nazwa</th>
                <th>Driver</th>
                <th>Baza</th>
                <th></th>
              </tr>
              <?= $srv->get_table_serwer_file('bazydanych', $serwer_id, $dostep->serwery_det_SerKonfiguracjaBazy, $dostep->serwery_det_SerKonfiguracjaALL); ?>
            </table>
          </div>
        </div>
        <div class="tab-pane" id="Mapy">
          <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
              <tr>
                <th>MapGroup</th>
                <th>Nazwa</th>
                <th></th>
              </tr>
              <?= $srv->get_table_serwer_file('mapy', $serwer_id, $dostep->serwery_det_SerKonfiguracjaBazy, $dostep->serwery_det_SerKonfiguracjaALL); ?>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
