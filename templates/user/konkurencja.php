<?
$func = getClass('Konkurencja');
?>
<style>
.example-modal .modal { position: relative; top: auto; bottom: auto; right: auto; left: auto; display: block; z-index: 1; }
.example-modal .modal { background: transparent !important; }
</style>
<?
//
// Generowanie danych xml, aktualizacja cache
//
$fora_xml_q = all("SELECT `nazwa`, `url`, `ilosc`, `code`, `dane_time`  FROM `acp_konkurencja`;");
foreach ($fora_xml_q as $fora_xml) {
  $func->rss($fora_xml->url, $fora_xml->code, $fora_xml->dane_time, $fora_xml->ilosc);
}

$strony_dane = array();
$strony_dane_kolor = array();
$strony_dane_ilosc = array();
$fora_items_all = array();
$fora_xml_q = all("SELECT `nazwa`, `color`, `url`, `code` FROM `acp_konkurencja`;");
foreach ($fora_xml_q as $fora_xml):
  $fora_xml->file_xml = file_get_contents("rss/$fora_xml->code.htm");
  $fora_xml->file_xml = json_decode($fora_xml->file_xml);
  $strony_dane_ilosc[$fora_xml->code]=count($fora_xml->file_xml);
  $strony_dane[$fora_xml->code]="$fora_xml->nazwa";
  $strony_dane_kolor[$fora_xml->code]="$fora_xml->color";
  foreach ($fora_xml->file_xml as $file) {
    array_push($fora_items_all,$file);
  }
endforeach;

usort($fora_items_all, function($a, $b) {
    return $a->pubDate_srt < $b->pubDate_srt ? 1 : -1;
});
?>
<div class="content-wrapper">
<section class="content">
  <div class="row">
  	<section class="col-lg-12">
  		<p><? if(!empty($_SESSION['msg'])){ echo $_SESSION['msg']; unset($_SESSION['msg']); } ?></p>
  	</section>
  </div>
  <?
  if(isset($_POST['edytuj'])){
    $func->edytuj($player->user, $dostep->KonkurencjaEdytuj);
    header("Location: ?x=$x");
  }
  if(isset($_POST['dodaj'])){
    $func->dodaj($player->user, $dostep->KonkurencjaDodaj);
    header("Location: ?x=$x");
  }
  if(!empty($_GET['usun_cache'])){
    $func->usun_cache($player->user, $dostep->KonkurencjaCache);
    header("Location: ?x=$x");
  }

  if(empty($_SESSION['konkurencja_show'])){
    $_SESSION['konkurencja_show'] = array('sloneczny');
  }
  if($_GET['co'] == 'konkurencja_show'){
    if(in_array($_GET['code'], $_SESSION['konkurencja_show'])){
      foreach ($_SESSION['konkurencja_show'] as $key => $value) {
        if($value == $_GET['code']){
          unset($_SESSION['konkurencja_show'][$key]);
          $_SESSION['msg'] = komunikaty("Na czas trwania sesji strona została ukryta z głównego strumienia danych.", 2);
        }
      }
    }
    else {
      array_push($_SESSION['konkurencja_show'], $_GET['code']);
      $_SESSION['msg'] = komunikaty("Na czas trwania sesji strona została dodana do głównego strumienia danych.", 2);
    }
    header("Location: ?x=$x");
  }
  if($_GET['co'] == 'usun'){
    $func->usun($_GET['id'], $player->user, $dostep->KonkurencjaCache);
    header("Location: ?x=$x");
  }
  ?>
  <div class="row">
    <section class="col-lg-9">
      <div class="col-lg-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Aktualności świata konkurencji</h3>
          </div>
          <div class="box-body">
            <ul class="list-group">
            <? foreach ($fora_items_all as $item):
              if(in_array($item->code, $_SESSION['konkurencja_show'])):
            ?>
              <li class="list-group-item bg-<?= $strony_dane_kolor[$item->code] ?>"><?= $strony_dane[$item->code] ?> <span class="badge badge-primary badge-pill"><?= czas_relatywny($item->pubDate); ?></span></li>
              <a href="<?= $item->link ?>" class="list-group-item list-group-item-action flex-column align-items-start">
                <div class="d-flex w-100 justify-content-between">
                  <h5 class="mb-1">Temat: <?= $item->title ?></h5>
                </div>
                <p class="mb-1 tekst">
                  <?
                  $item->description_convert = str_replace(array("\r\n", "\n", "\r"), "<br>", strip_tags($item->description));
                  $item->description_convert_last = preg_replace('/(\s*<br[^>]*>){2,}/', '\1', $item->description_convert);
                  echo $item->description_convert_last;
                  ?>
                </p>
              </a>
            <? endif; endforeach; ?>
            </ul>
          </div>
        </div>
      </div>
    </section>
    <section class="col-lg-3">
      <div class="col-lg-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Witryny</h3>
          </div>
          <div class="box-body">
            <ul class="list-group">
              <?
              $lista_stron_q = all("SELECT `id`, `nazwa` , `code` FROM `acp_konkurencja`");
              if(!empty($lista_stron_q)):
                  foreach ($lista_stron_q as $lista_stron):
              ?>
                <li class="list-group-item">
                  <?= $lista_stron->nazwa ?></br>
                  <div class="btn-group">
                    <?
                    $lista_stron->konkurencja_show = (in_array($lista_stron->code, $_SESSION['konkurencja_show'])) ? 'glyphicon glyphicon-eye-open' : 'glyphicon glyphicon-eye-close' ;?>
                      <a href="<?= "?x=$x&co=konkurencja_show&code=$lista_stron->code" ?>" class="btn btn-xs btn-default"><i class="<?= $lista_stron->konkurencja_show ?>"></i></a>
                    <? if(uprawnienia($dostep->KonkurencjaEdytuj, $player->user) == 1): ?>
                      <a href="#kon_<?= $lista_stron->id ?>" data-toggle="modal" data-target="#kon_<?= $lista_stron->id ?>" class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i> Edytuj</a>
                    <? endif;
                    if(uprawnienia($dostep->KonkurencjaEdytuj, $player->user) == 1): ?>
                      <a href="<?= "?x=$x&co=usun&id=$lista_stron->id" ?>" class="btn btn-xs btn-danger"><i class="fa fa-remove"></i> Usuń</a>
                    <? endif; ?>
                  </div>
                  <span class="badge badge-primary badge-pill"><?= $strony_dane_ilosc[$lista_stron->code] ?></span>
                </li>
              <?  endforeach;
              else: ?>
                <li class="list-group-item">Brak stron...</li>
              <? endif ?>
            </ul>
          </div>
          <div class="box-footer clearfix no-border">
            <div class="pull-right">
              <button type="button" class="btn btn-success pull-right" data-toggle="modal" data-target="#dodaj"><i class="fa fa-plus"></i> Dodaj Stronę</button>
            </div>
            <div class="pull-left">
              <button type="button" class="btn btn-danger pull-right" data-toggle="modal" data-target="#cache"><i class="fa fa-remove"></i> Cache</button>
            </div>
          </div>
        </div>
      </div>
    </section>

    <div class="col-lg-12">
      <?
      if(uprawnienia($dostep->KonkurencjaEdytuj, $player->user) != 0):
      $lista_stron_q = all("SELECT `id`, `nazwa` , `code`, `color`, `url`, `ilosc`, `dane_time` FROM `acp_konkurencja`");
      if(!empty($lista_stron_q)):
          foreach ($lista_stron_q as $lista_stron):
      ?>
      <div class="modal fade" id="kon_<?= $lista_stron->id ?>">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Edytuj stronę <?= $lista_stron->nazwa ?> (ID: <?= $lista_stron->id ?>)</h4>
            </div>
            <div class="modal-body">
              <form  method='post' action='<?= "?x=$x" ?>'>
                <input type="hidden" name="id" value="<?= $lista_stron->id ?>">
                <p>
                  <div class='form-group input-group'>
                    <span class='input-group-addon'>Nazwa</span>
                    <input class="form-control" name="nazwa" value="<?= $lista_stron->nazwa?>">
                  </div>
                </p>
                <p>
                  <div class='form-group input-group'>
                    <span class='input-group-addon'>Url</span>
                    <input class="form-control" name="url" value="<?= $lista_stron->url?>">
                  </div>
                </p>
                <p>
                  <div class='form-group input-group'>
                    <span class='input-group-addon'>Kolor</span>
                    <select class="form-control" name="kolor">
                      <?
                      $kolory = array('default' => 'Podstawowy', 'primary' => 'Niebieski', 'info' => 'Niebieski Jasny', 'success' => 'Zielony', 'yellow' => 'Żółty', 'danger' => 'Czerwony', 'gray' => 'Szary', 'navy' => 'Ciemny Niebieski', 'teal' => 'Herbaciany', 'purple' => 'Fioletowy', 'orange' => 'Pomarańczowy', 'maroon' => 'Różowy', 'black' => 'Czarny');
                      echo '<option value="'.$lista_stron->color.'">'.$kolory[$lista_stron->color].'</option>';
                      foreach ($kolory as $key => $value):
                        if($lista_stron->color != $key)
                        echo '<option value="'.$key.'">'.$value.'</option>';
                      endforeach;
                      ?>
                    </select>
                  </div>
                </p>
                <p>
                  <div class='form-group input-group'>
                    <span class='input-group-addon'>Aktualizcja</span>
                    <select class="form-control" name="aktualizacja">
                      <?
                      $co_ile = array('-5 minutes' => 'co 5 minut', '-10 minutes' => 'co 10 minut', '-30 minutes' => 'co 30 minut');
                      echo '<option value="'.$lista_stron->dane_time.'">'.$co_ile[$lista_stron->dane_time].'</option>';
                      foreach ($co_ile as $key => $value):
                        if($lista_stron->dane_time != $key)
                        echo '<option value="'.$key.'">'.$value.'</option>';
                      endforeach;
                      ?>
                    </select>
                  </div>
                </p>
                <p>
                  <div class='form-group input-group'>
                    <span class='input-group-addon'>Ilość</span>
                    <input class="form-control" name="ilosc" type="number" value="<?= $lista_stron->ilosc?>" min="1" max="25">
                  </div>
                </p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Zamknij</button>
                <button type="input" name="edytuj" class="btn btn-primary">Edytuj</button>
              </form>
            </div>
          </div>
        </div>
      </div>
      <? endforeach; endif; endif;?>

      <div class="modal fade" id="dodaj">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Dodaj nową stronę</h4>
            </div>
            <div class="modal-body">
              <form  method='post' action='<?= "?x=$x" ?>'>
                <p>
                  <div class='form-group input-group'>
                    <span class='input-group-addon'>Nazwa</span>
                    <input class="form-control" name="nazwa">
                  </div>
                </p>
                <p>
                  <div class='form-group input-group'>
                    <span class='input-group-addon'>Url</span>
                    <input class="form-control" name="url">
                  </div>
                </p>
                <p>
                  <div class='form-group input-group'>
                    <span class='input-group-addon'>Kolor</span>
                    <select class="form-control" name="kolor">
                      <?
                      $kolory = array('default' => 'Podstawowy', 'primary' => 'Niebieski', 'info' => 'Niebieski Jasny', 'success' => 'Zielony', 'yellow' => 'Żółty', 'danger' => 'Czerwony', 'gray' => 'Szary', 'navy' => 'Ciemny Niebieski', 'teal' => 'Herbaciany', 'purple' => 'Fioletowy', 'orange' => 'Pomarańczowy', 'maroon' => 'Różowy', 'black' => 'Czarny');
                      foreach ($kolory as $key => $value):
                        echo '<option value="'.$key.'">'.$value.'</option>';
                      endforeach;
                      ?>
                    </select>
                  </div>
                </p>
                <p>
                  <div class='form-group input-group'>
                    <span class='input-group-addon'>Aktualizcja</span>
                    <select class="form-control" name="aktualizacja">
                      <?
                      $co_ile = array('-5 minutes' => 'co 5 minut', '-10 minutes' => 'co 10 minut', '-30 minutes' => 'co 30 minut');
                      foreach ($co_ile as $key => $value):
                        echo '<option value="'.$key.'">'.$value.'</option>';
                      endforeach;
                      ?>
                    </select>
                  </div>
                </p>
                <p>
                  <div class='form-group input-group'>
                    <span class='input-group-addon'>Ilość</span>
                    <input class="form-control" name="ilosc" type="number" min="1" max="25">
                  </div>
                </p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Zamknij</button>
                <button type="input" name="dodaj" class="btn btn-primary">Dodaj</button>
              </form>
            </div>
          </div>
        </div>
      </div>
      <? if(uprawnienia($dostep->KonkurencjaCach, $player->user) != 0): ?>
      <div class="modal fade" id="cache" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content modal-danger">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Wyczyść cache</h4>
            </div>
            <div class="modal-body">
              Usuń cache zarejstrowanych stron?
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Nie</button>
              <a class="btn btn-outline pull-right" class="button"  href="<?= "?x=$x&xx=$xx&id=$id&usun_cache=tak" ?>">Tak</a>
            </div>
          </div>
        </div>
      </div>
      <? endif; ?>
    </div>
	</div>

</section>
</div>
<? require_once("./templates/user/stopka.php");  ?>


<div class="control-sidebar-bg"></div>
</div>

<!-- jQuery 3 -->
<script src="./www/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="./www/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="./www/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="./www/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="./www/bower_components/datatables.net-bs/js/dataTables.responsive.js"></script>
<!-- SlimScroll -->
<script src="./www/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="./www/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="./www/dist/js/adminlte.min.js"></script>
<!-- page script -->
<script>
$(document).ready(function () {
  $('.tekst').slimScroll({height : '100px'});
});
</script>
</body>
</html>
