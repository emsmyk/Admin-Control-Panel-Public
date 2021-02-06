<?
tytul_strony("Lista Piosenek");
$dane_publiczne = getClass('DanePubliczne');
$rs = getClass('Roundsound');
?>
<style>
.content-wrapper { background: url('<?= $acp_system ['tlo_galeria_map']?>') no-repeat center center fixed; -webkit-background-size: cover; -moz-background-size: cover; background-size: cover; -o-background-size: cover; }
@media (min-width: 1200px){.container { width: 1400px; }}
</style>
<?
$id_roundsound = (isset($_GET['id_roundsound'])) ? (int)$_GET['id_roundsound'] : null;

$logo = (empty($acp_system['logo_galeria_map'])) ? $acp_system['logo_prawa'] : $acp_system['logo_galeria_map'];
$dane = $rs->DanePubliczne($id_roundsound);
?>
<body class="hold-transition <?= $player->szablon ?> layout-top-nav">
  <?
  if(!empty($_SESSION['user'])){
    require_once("./templates/master/przybornik/menu-header.php");
  }
  ?>
  <div class="wrapper">
    <div class="content-wrapper">
      <div class="container">
        <section class="content">
         <div class="row">
           <section class="col-lg-12">
               <p><? if(!empty($_SESSION['msg'])){ echo $_SESSION['msg']; unset($_SESSION['msg']); } ?></p>
           </section >
         </div>
        <?
        if(isset($_POST['propozycja'])){
          $rs->propozycja($dane->rs->id);
          header("Location: ?x=$x&id_roundsound=$id_roundsound");
        }
        if($_GET['co'] == 'vote'){
          $rs->vote($dane->rs->id, $_GET['id']);
          header("Location: ?x=$x&id_roundsound=$id_roundsound");
        }
        ?>
         <div class="row text-center text-lg-left">
           <a href="<?= $acp_system['strona_www'] ?>"><img src="<?= $logo ?>"></a>
         </div>
         <? echo $dane_publiczne->menu($x, $acp_system['acp_strona_www'], $acp_system['acp_nazwa']); ?>
    		 <div class="row">
          <div class="col-lg-8">
            <div class="box box">
              <div class="box-header">
                <i class="fa fa-music"></i>
      				  <h3 class="box-title">Lista Utworów: <?= $dane->rs->nazwa ?></h3>
      				</div>
       				<div class="box-body">
                <div class="table-responsive">
                  <table class="table">
                    <tbody>
                      <?
                      foreach ($dane->lista_piosenek as $value):
                        $dane->piosenka->$value = row("SELECT `id`, `nazwa`, `wykonawca`, `album`, `link_yt`, `vote`, `mp3_code` FROM `rs_utwory` WHERE `id` = $value LIMIT 1");
                        $dane->piosenka_vote[$value] = array('id' => $value, 'vote' => $dane->piosenka->$value->vote);
                      endforeach;

                      array_multisort(array_map(function($element) {
                          return $element['vote'];
                      }, $dane->piosenka_vote), SORT_DESC, $dane->piosenka_vote);
                      ?>

                      <?
                      $i=1;
                      foreach ($dane->piosenka_vote as $piosenka):
                        $piosenka_id = $piosenka[id];
                        if(!empty($dane->piosenka->$piosenka_id->nazwa)):
                          $dane->piosenka->$piosenka_id->yt_cover = explode("?v=",$dane->piosenka->$piosenka_id->link_yt);
                      ?>
                        <tr>
                          <td style="vertical-align: middle;"><button class="btn bg-blue-active color-palette pull-right btn-block btn-sm"> <br><?= $i++ ?><br> &nbsp;</button></td>
                          <td style="vertical-align: middle;"><img src="https://img.youtube.com/vi/<?= $dane->piosenka->$piosenka_id->yt_cover[1] ?>/mqdefault.jpg" class="img-rounded"></img></td>
                          <td style="vertical-align: middle;">
                            <p><?= $dane->piosenka->$piosenka_id->nazwa ?> -  <?= $dane->piosenka->$piosenka_id->wykonawca ?></p>
                            <p><small>Album: <?= $dane->piosenka->$piosenka_id->album ?></small></p>
                            <audio controls preload="none" controlsList="nodownload" id="bgAudio">
                              <source src="www/mp3/<?= $dane->piosenka->$piosenka_id->mp3_code  ?>.mp3" type="audio/mpeg">
                            </audio>
                            <p><a href="<?= "?x=$x&id_roundsound=$id_roundsound&co=vote&id=$piosenka_id" ?>" class="btn btn-success btn-block btn-xs">Oddaj Głos</a></p>
                          </td>
                          <td style="vertical-align: middle;">
                            <button class="btn bg-orange-active color-palette pull-right btn-block btn-sm"><?= $dane->piosenka->$piosenka_id->vote ?><br>Głosów</button>
                          </td>
                          <td style="vertical-align: middle;">
                            <a href="<?= $dane->piosenka->$piosenka_id->link_yt ?>" target="_blank" ><button type="button" class="btn btn btn"><i class="fa fa-play"></i><br>PLAY <br>YT</button></a>
                          </td>
                        </tr>
                      <?
                        endif;
                      endforeach;
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="box box">
              <div class="box-body">
                <ul class="nav nav-pills nav-stacked">
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="<?= "?x=$x&id_roundsound=".$rs->DanePubliczneAktualnyID() ?>">Aktualnie Gramy: <b><?= $rs->DanePubliczneAktualny(); ?></b></a>
                  </li>
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="<?= "?x=$x&id_roundsound=".$rs->DanePubliczneKolejnyID() ?>">W trakcie przygotowania <b><?= $rs->DanePubliczneKolejny(); ?></b></a>
                  <!-- </li>
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="">Historia</a>
                  </li> -->
                </ul>
              </div>
            </div>
            <div class="box box">
              <div class="box-header">
                <i class="fa fa-plus"></i>
      				  <h3 class="box-title">Dodaj Propozycję!</h3>
      				</div>
       				<div class="box-body">
                <form name='propozycja' method='post'>
                  <p>
                    <div class='form-group input-group'>
                      <span class='input-group-addon'>Tytuł</span>
                      <input class="form-control" name="nazwa" type="text" placeholder="Tytuł piosenki">
                    </div>
                  </p>
                  <p>
                    <div class='form-group input-group'>
                      <span class='input-group-addon'>Wykonawca</span>
                      <input class="form-control" name="wykonawca" type="text" placeholder="Nazwa artysty">
                    </div>
                  </p>
                  <p>
                    <div class='form-group input-group'>
                      <span class='input-group-addon'>Album</span>
                      <input class="form-control" name="album" type="text" placeholder="Tytuł albumu">
                    </div>
                  </p>
                  <p>
                    <div class='form-group input-group'>
                      <span class='input-group-addon'>Początek</span>
                      <input type="text" pattern="^[0-5][0-9]\:[0-5][0-9]\.[0-9]{1,3}$" class="form-control" name="start" placeholder="1:21">
                    </div>
                  </p>
                  <p>
                    <div class='form-group input-group'>
                      <span class='input-group-addon'>Koniec</span>
                      <input type="text" pattern="^[0-5][0-9]\:[0-5][0-9]\.[0-9]{1,3}$" class="form-control" name="end" placeholder="1:40">
                    </div>
                  </p>
                  <p>
                    <div class='form-group input-group'>
                      <span class='input-group-addon'>Link YT</span>
                      <input class="form-control" name="link" type="text" type="url" placeholder="https://www.youtube.com/watch?v=_ae5Ap77b7k">
                    </div>
                  </p>
                  <button type="input" name="propozycja" class="btn btn-block btn-primary">Zaproponuj</button>
                </form>
              </div>
            </div>
          </div>
         </div>

         <? echo $dane_publiczne->social(); ?>
         <? echo $dane_publiczne->stopka($acp_system['acp_nazwa'], $acp_system['acp_wersja']); ?>

        </section>
      </div>
    </div>
  </div>
</body>
<!-- jQuery 3 -->
<script src="./www/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="./www/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Page  -->
