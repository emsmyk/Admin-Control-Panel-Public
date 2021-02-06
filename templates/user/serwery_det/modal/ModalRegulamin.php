<div class="modal fade" id="regulamin">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Regulamin</h4>
      </div>
      <div class="modal-body">
        <? $regulamin = row("SELECT `id`, `tekst`, `link` FROM `acp_serwery_regulamin` WHERE `serwer_id` = $serwer_id");
        if(empty($regulamin->link) && empty($regulamin->tekst)):
          echo '<p>Brak.. Uzupełnij tekst lub dodaj link..</p>';
        else:
          if(!empty($regulamin->link)):
            echo '<p>Regulamin jest przekierowaniem na stronę zewnętrzną np. forum</p>';
          else:
            htmlspecialchars_decode($regulamin->tekst);
          endif;
        endif;
        ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Zamknij</button>
        <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#regulamin-edit">Edytuj</a></button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="regulamin-edit">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Edytor Regulaminu</h4>
      </div>
      <div class="modal-body">
        <form method='post'>
          <? $regulamin = row("SELECT `id`, `tekst`, `link` FROM `acp_serwery_regulamin` WHERE `serwer_id` = $serwer_id"); ?>
          <p><textarea class="form-control" rows="15" name="tekst"><?= htmlspecialchars_decode($regulamin->tekst); ?></textarea></p>
          <p>
            <div class='form-group input-group'>
              <span class='input-group-addon'>Link</span>
              <input class="form-control" name="link" type="text" value="<?= $regulamin->link ?>">
            </div>
          </p>
          <input type="hidden" name="id" value="<?= $regulamin->id; ?>"></input>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Zamknij</button>
          <button type="input" name="regulamin" class="btn btn-primary">Zapisz</button>
        </form>
      </div>
    </div>
  </div>
</div>
