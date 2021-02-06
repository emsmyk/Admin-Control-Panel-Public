<div class="modal fade" id="logi">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Lista dostępnych Logów</h4>
      </div>
      <div class="modal-body">
        <?
        $logi_sm = json_decode(one("SELECT `dane` FROM `acp_cache_api` WHERE `get` = 'serwer_id".$serwer_id."_logs_sm' LIMIT 1"));
        $logi_source = json_decode(one("SELECT `dane` FROM `acp_cache_api` WHERE `get` = 'serwer_id".$serwer_id."_logs' LIMIT 1"));
        ?>
        <div class="box-group" id="accordion">
          <div class="panel box">
            <div class="box-header with-border">
              <h4 class="box-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" class="">
                  Logi Sourcemod (Katalog: srv/addons/logs)
                </a>
              </h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse in" aria-expanded="true" style="">
              <div class="box-body">
                <ul>
                <? foreach ($logi_sm as $value):
                  $logi_link = (uprawnienia($dostep->serwery_det_logi, $player->user)) ? "?x=serwery_det&serwer_id=$serwer_id&logi=$value" : "#";
                ?>
                  <li><a href="<?= $logi_link ?>"><?= $value ?></a></li>
                <? endforeach; ?>
              </ul>
              </div>
            </div>
          </div>
          <div class="panel box">
            <div class="box-header with-border">
              <h4 class="box-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" class="collapsed" aria-expanded="false">
                  Logi Serwera (Katalog: srv/logs)
                </a>
              </h4>
            </div>
            <div id="collapseTwo" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
              <ul>
              <? foreach ($logi_source as $value):
                $logi_link = (uprawnienia($dostep->serwery_det_logi, $player->user)) ? "?x=serwery_det&serwer_id=$serwer_id&logi=$value" : "#";
              ?>
                <li><a href="<?= $logi_link ?>"><?= $value ?></a></li>
              <? endforeach; ?>
            </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Zamknij</button>
      </div>
    </div>
  </div>
</div>
