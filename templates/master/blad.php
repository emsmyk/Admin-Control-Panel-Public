<body class="hold-transition <? echo $player->szablon ?> layout-top-nav">
<div class="wrapper">
<? require_once("./templates/master/przybornik/menu-header.php"); ?>
  <div class="content-wrapper">
    <div class="container">
      <section class="content">
        <section class="content">
          <div class="error-page">
            <h2 class="headline text-red"><i class="fa fa-lock text-red"></i></h2>

            <div class="error-content">
              <h3><i class="fa fa-warning text-red"></i> Oops! Coś poszło nie tak..</h3>

              <p>
                Nie posiadasz dostępu do tej strony..
                Proponujemy <a href="?x=default">powrót do strony głównej</a> oraz/lub kontakt z administratorem systemu ACP
              </p>

            </div>
          </div>
        </section>
      </section>
    </div>
  </div>
<? require_once("./templates/master/przybornik/stopka.php"); ?>
</div>


<!-- jQuery 3 -->
<script src="./www/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="./www/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- FastClick -->
<script src="./www/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="./www/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="./www/dist/js/demo.js"></script>
