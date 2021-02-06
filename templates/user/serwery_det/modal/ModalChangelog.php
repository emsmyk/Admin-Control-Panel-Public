<div class="modal fade" id="changelog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Changelog</h4>
      </div>
      <div class="modal-body">
        <p>Tutaj dodasz wszelkie informacje o działaniach na serwerze, wybierz odpowiednią kategorię tematyczą lub wałasny wpis aby dodać wyjątkowy unikatowy wpis.</p>
        <button type="button" class="btn bg-olive btn-block" data-toggle="modal" data-target="#changelog-add">Dodanie Admina</a></button>
        <button type="button" class="btn bg-purple btn-block" data-toggle="modal" data-target="#changelog-awans-deg-rez">Awans/Degradacja/Rezygnacja Admina</a></button>
        <button type="button" class="btn bg-default btn-block" data-toggle="modal" data-target="#changelog-inne">Własny wpis</a></button>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Zamknij</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="changelog-add">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Changelog - Dodanie Admina</h4>
      </div>
      <div class="modal-body">
        <form method='post'>
          <p>
            <div class='form-group input-group'>
              <span class='input-group-addon'>Nick</span>
              <input class="form-control" name="nick">
            </div>
          </p>
          <p>
            <div class='form-group input-group'>
              <span class='input-group-addon'>Steam_ID</span>
              <input class="form-control" name="steam">
            </div>
          </p>
          <p>
            <div class='form-group input-group'>
              <span class='input-group-addon'>Okres próbny?</span>
              <select class="form-control" name="proba">
                <option value="1">Tak</option>
                <option value="0">Nie</option>
              </select>
            </div>
          </p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Zamknij</button>
          <button type="input" name="changelog_add" class="btn btn-primary">Dodaj</button>
        </form>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="changelog-awans-deg-rez">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Changelog - Awans/Degradacja/Rezygnacja</h4>
      </div>
      <div class="modal-body">
        <form method='post'>
          <p>
            <div class='form-group input-group'>
              <span class='input-group-addon'>Wybierz czynność</span>
              <select class="form-control" name="czynnosc">
                <option value="0">Brak</option>
                <option value="1">Awans</option>
                <option value="2">Degradacja</option>
                <option value="3">Rezygnacja</option>
              </select>
            </div>
          </p>
          <p>
            <div class='form-group input-group'>
              <span class='input-group-addon'>Nick</span>
              <input class="form-control" name="nick">
            </div>
          </p>
          <p>
            <div class='form-group input-group'>
              <span class='input-group-addon'>Steam_ID</span>
              <input class="form-control" name="steam">
            </div>
          </p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Zamknij</button>
          <button type="input" name="changelog_awans_deg_rez" class="btn btn-primary">Dodaj</button>
        </form>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="changelog-inne">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Changelog</h4>
        <p>Tutaj dodasz wszelkie informacje o działaniach na serwerze</p>
      </div>
      <div class="modal-body">
        <form method='post'>
          <p>
            <div class='form-group input-group'>
              <span class='input-group-addon'>Tekst</span>
              <input class="form-control" name="tekst">
            </div>
          </p>
          <p>
            <div class='form-group input-group'>
              <span class='input-group-addon'>Data</span>
              <input class="form-control" name="data" type="datetime-local">
            </div>
          </p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Zamknij</button>
          <button type="input" name="changelog_wlasny" class="btn btn-primary">Dodaj</button>
        </form>
      </div>
    </div>
  </div>
</div>
