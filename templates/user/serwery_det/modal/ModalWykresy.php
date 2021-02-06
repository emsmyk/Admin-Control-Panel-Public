<div class="modal fade" id="wykresy">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Konfiguracja Wykresów</h4>
      </div>
      <div class="modal-body">
        <form method='post'>
          <h4>Wykres - Ilość Graczy:</h4>
          <p>
            <div class='form-group input-group'>
              <span class='input-group-addon'>Zakres</span>
              <select class="form-control" name="wyk-graczy-zakres">
                <option value="<?= $_SESSION["wyk-graczy-zakres-$serwer_id"] ?>">Aktualnie wybrany: <?= $_SESSION["wyk-graczy-zakres-$serwer_id"] ?></option>
                <option value="hour">Godzinowy</option>
                <option value="day">Dzienny</option>
                <option value="month">Miesięczny</option>
              </select>
            </div>
          </p>
          <p>
            <div class='form-group input-group'>
              <span class='input-group-addon'>Ilość</span>
              <input class="form-control" name="wyk-graczy-ilosc" type="number" value="<?= $_SESSION["srv_det_graczy_$serwer_id"] ?>">
            </div>
          </p>
          <h4>Wykres - GOSetti Pozycja:</h4>
          <p>
            <div class='form-group input-group'>
              <span class='input-group-addon'>Ilość</span>
              <input class="form-control" name="wyk-gosetti-pozycja-ilosc" type="number" value="<?= $_SESSION["srv_det_gosetti_pozycja_$serwer_id"] ?>">
            </div>
          </p>
          <h4>Wykres - GOSetti Punkty:</h4>
          <p>
            <div class='form-group input-group'>
              <span class='input-group-addon'>Ilość</span>
              <input class="form-control" name="wyk-gosetti-punkty-ilosc" type="number" value="<?= $_SESSION["srv_det_gosetti_tura_$serwer_id"] ?>">
            </div>
          </p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Zamknij</button>
          <button type="input" name="wykresy" class="btn btn-primary">Edytuj</button>
        </form>
      </div>
    </div>
  </div>
</div>
