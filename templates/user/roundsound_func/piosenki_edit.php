<?
$func = getClass('Roundsound');
?>
<style>
.example-modal .modal { position: relative; top: auto; bottom: auto; right: auto; left: auto; display: block; z-index: 1; }
.example-modal .modal { background: transparent !important; }
</style>
<div class="content-wrapper">
<section class="content">
  <div class="row">
	<section class="col-lg-12">
		<p><? if(!empty($_SESSION['msg'])){ echo $_SESSION['msg']; unset($_SESSION['msg']); } ?></p>
	</section >
  </div>
<?
$id = (isset($_GET['id'])) ? (int)$_GET['id'] : null;
$co = (isset($_GET['co'])) ? $_GET['co'] : null;

$row = row("SELECT *, (SELECT `nazwa` FROM `rs_roundsound` WHERE `id` = `roundsound_propozycja` ) AS `roundsound_nazwa`, (SELECT `login` FROM `acp_users` WHERE `user` = `akcept` LIMIT 1) AS `login_akceptujacego` FROM `rs_utwory` WHERE `id` = $id LIMIT 1");
tytul_strony("RoundSound: Piosenka - $row->nazwa");

if(isset($_POST['edit'])) {
  $func->edytuj_piosenke($player->user, $dostep->RsPiosenkaEdytuj);
  header("Location: ?x=$x&xx=$xx&id=$row->id");
}
if(isset($_POST['wgraj_mp3'])) {
  $func->wgraj_mp3($player->user, $dostep->RsPiosenkaMp3);
  header("Location: ?x=$x&xx=$xx&id=$row->id");
}
if($co == 'usun'){
  $func->usun_piosenke($row->id, $player->user, $dostep->RsPiosenkaUsun);
  header("Location: ?x=$x");
}
if($co == 'akceptuj'){
  $func->akceptuj_piosenke($row->id, $player->user, $dostep->RsPiosenkaAkcept);
  header("Location: ?x=$x&xx=$xx&id=$row->id");
}
if($co == 'dodaj_do_listy'){
  $id_roundsound = (isset($_GET['id_roundsound'])) ? (int)$_GET['id_roundsound'] : null;
  $func->dodaj_do_listy($row->id, $id_roundsound, $player->user, $dostep->RsPiosenkaDodajDoListy);
  header("Location: ?x=$x&xx=$xx&id=$row->id");
}

if(empty($row)){
  header("Location: ?x=roundsound&xx=piosenki");
}
?>
  <div class="row">
    <div class="col-lg-9">
      <? echo komunikat_and_powiadomienie($_GET['powiadomienie_id']); ?>
      <div class="box box">
        <div class="box-header">
				  <h3 class="box-title">Piosenki - <?= $row->nazwa ?></h3>
				</div>
        <div class="box-body">
          <h4>Nazwa:</h4><p><?= $row->nazwa ?></p>
          <h4>Wykonawca:</h4><p><?= $row->wykonawca ?></p>
          <h4>Album:</h4><p><?= $row->album ?></p>
          <h4>Start - Koniec:</h4><p><?= $row->start ?> - <?= $row->end ?></p>
          <div class="input-group">
            <input type="text" class="form-control" value="<?= $row->link_yt ?>" placeholder="Some path" id="copy-input">
            <span class="input-group-btn">
              <button onclick="CopyInput()" class="btn btn-default" type="button">Kopiuj Link</button>
          </div>
        </div>
        <div class="box-footer clearfix no-border">
          <button type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#edit"><i class="fa fa-edit"></i> Edytuj</button>
          <? if($row->akcept == 0): ?>
            <a class="btn btn-success" href="<?= "?x=$x&xx=$xx&id=$row->id&co=akceptuj" ?>" role="button"><i class="fa fa-up"></i> Akceptuj</a>
          <? endif; ?>
          <a class="btn btn-danger" href="<?= "?x=$x&xx=$xx&id=$row->id&co=usun" ?>" role="button"><i class="fa fa-close"></i> Usuń</a>
          <a class="btn btn-primary" href="<?= $row->link_yt ?>" role="button" target="_blank"><i class="fa fa-music"></i> Piosenka</a>
        </div>
      </div>
      <div class="box box">
        <div class="box-header">
				  <h3 class="box-title">Plik Mp3</h3>
				</div>
        <div class="box-body">
          <form action="" method="POST" enctype="multipart/form-data">
            <p><a href="https://www.clipconverter.cc/" target="_blank">Konwerter #1</a> | <a href="https://youtube-cutter.org/" target="_blank">Konwerter #2 (Polecany)</a></p>
            <p><a href="https://online-audio-converter.com/pl/">Konwersja rate to 44 100 Khz</a></p>
            <p>
              <div class="input-group">
                <span class="input-group-btn">
                  <span class="btn btn-default btn-file">
                    Wybierz Plik
                    <input name="nazwa_pliku" name="nazwa_pliku" type="file">
                    <input type="hidden" name="id" value="<?= $row->id ?>">
                  </span>
                </span>
                <input readonly="readonly" placeholder="Wybierz plik.." class="form-control" name="nazwa_pliku" size="35" type="text"/>
              </div>
            </p>
            <p><input name='wgraj_mp3' class='btn btn-primary btn btn-block' type='submit' value='Prześlij MP3'/></p></form>
          </form>
        </div>
      </div>
      <div class="box box">
        <div class="box-header">
				  <h3 class="box-title">Grana (Listy Utworów):</h3>
				</div>
        <div class="box-body">
          <ul class="todo-list">
            <?
            $listy_utworow_q = all("SELECT `id`, `nazwa`, `lista_piosenek` FROM `rs_roundsound`");
            foreach ($listy_utworow_q as $value):
              $value->lista_piosenek = json_decode($value->lista_piosenek);
              if(in_array($row->id, $value->lista_piosenek)):
            ?>
              <li class="list-group-item">
                <b>#<?= $value->id ?></b> - <?= $value->nazwa ?>
                <div class="tools">
                  <a href="<?= "?x=roundsound&xx=lista_edit&id=$value->id" ?>"><i class="fa fa-edit"></i> Więcej</a>
                </div>
              </li>
            <?
              endif;
            endforeach;
            ?>
          </ul>
        </div>
      </div>
    </div>
    <? if($row->roundsound_propozycja_dodane == 0): ?>
    <div class="col-lg-3 col-xs-12">
      <div class="small-box bg-maroon">
        <div class="inner">
          <h3>Zgłoszona</h3>
          <p>do listy <a class="text-black" href="?x=roundsound&xx=lista_edit&id=<?= $row->roundsound_propozycja ?>"><?= $row->roundsound_nazwa ?></a></p>
        </div>
        <div class="icon">
          <i class="fa fa-clone"></i>
        </div>
        <a href="<?= "?x=$x&xx=$xx&id=$id&co=dodaj_do_listy&id_roundsound=$row->roundsound_propozycja" ?>" class="small-box-footer"><i class="fa fa-plus"></i> Dodaj do Listy Utworów</i></a>
      </div>
    </div>
    <? endif; ?>
    <? if(!empty($row->mp3_code) && !empty($row->mp3)): ?>
    <div class="col-lg-3 col-xs-12">
      <div class="small-box bg-aqua">
        <div class="inner">
          <h3>Przesłuchaj</h3>
          <p>
            <audio controls preload="none" controlsList="nodownload" id="bgAudio">
              <source src="www/mp3/<?= $row->mp3_code  ?>.mp3" type="audio/mpeg">
            </audio>
          </p>
        </div>
        <div class="icon">
          <i class="fa fa-music"></i>
        </div>
      </div>
    </div>
    <? endif; ?>
    <div class="col-lg-3 col-xs-12">
      <div class="small-box bg-aqua">
        <div class="inner">
          <h3><?= $row->vote ?></h3>
          <p>Głosów</p>
        </div>
        <div class="icon">
          <i class="fa fa-thumbs-o-up"></i>
        </div>
      </div>
    </div>
    <?
    $row->mp3_color = ($row->mp3 == 0) ? 'red' : 'green';
    $row->mp3_up = ($row->mp3 == 0) ? 'Nieistnieje' : 'Istnieje';
    $row->mp3_down = ($row->mp3 == 0) ? 'Brak Pliku Mp3' : 'Wgrany Plik';
    ?>
    <div class="col-lg-3 col-xs-12">
      <div class="small-box bg-<?= $row->mp3_color ?>">
        <div class="inner">
          <h3><?= $row->mp3_up ?></h3>
          <p><?= $row->mp3_down ?></p>
        </div>
        <div class="icon">
          <i class="fa fa-music"></i>
        </div>
      </div>
    </div>
    <?
    $row->akcept_color = ($row->akcept == 0) ? 'red' : 'green';
    $row->akcept_up = ($row->akcept == 0) ? 'Piosenka' : $row->login_akceptujacego;
    $row->akcept_down = ($row->akcept == 0) ? 'nie została zaakceptowana' : $row->data_akcept;
    ?>
    <div class="col-lg-3 col-xs-12">
      <div class="small-box bg-<?= $row->akcept_color ?>">
        <div class="inner">
          <h3><?= $row->akcept_up ?></h3>
          <p><?= $row->akcept_down ?></p>
        </div>
        <div class="icon">
          <i class="fa fa-user"></i>
        </div>
      </div>
    </div>

  <div class="row">
    <div class="modal fade" id="edit">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Dodaj nowy utówr </h4>
          </div>
          <div class="modal-body">
            <form name='edit' method='post'>
              <input type="hidden" name="id" value="<?= $row->id ?>">
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Nazwa</span>
                  <input class="form-control" name="nazwa" type="text" value="<?= $row->nazwa ?>">
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Wykonawca</span>
                  <input class="form-control" name="wykonawca" type="text" value="<?= $row->wykonawca ?>">
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Album</span>
                  <input class="form-control" name="album" type="text" value="<?= $row->album ?>">
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Start</span>
                  <input class="form-control" name="start" type="text" value="<?= $row->start ?>">
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Koniec</span>
                  <input class="form-control" name="end" type="text" value="<?= $row->end ?>">
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Link YT</span>
                  <input class="form-control" name="link" type="text" value="<?= $row->link_yt ?>">
                </div>
              </p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Zamknij</button>
              <button type="input" name="edit" class="btn btn-primary">Edytuj</button>
            </form>
          </div>
        </div>
      </div>
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
<?= js_table_defaults(); ?>
<?= js_table_one('#example'); ?>
<script>
function CopyInput() {
  var copyText = document.getElementById("copy-input");
  copyText.select();
  copyText.setSelectionRange(0, 99999);
  document.execCommand("copy");
}
</script>
</body>
</html>
