<?
$func = getClass('Register');
?>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="row">
	<section class="col-lg-12">
		<p><? if(!empty($_SESSION['msg'])){ echo $_SESSION['msg']; unset($_SESSION['msg']); } ?></p>
	</section >
  </div>

<?
if(isset($_POST['przypomnij'])){
  $func->forget_password($_POST['login'], $_POST['mail'], $acp_system['acp_mail']);
  header("Location: ?x=$x");
}
if(isset($_POST['nowe_haslo'])){
  $func->forget_password_new($_POST['pass'], $_POST['pass2'], $_POST['hash']);
  header("Location: ?x=$x");
}
?>

<div class="login-box">
  <div class="login-logo">
    <a href="?x=default"><? echo $acp_system['acp_nazwa']; ?> | ACP</a>
  </div>
  <? if(empty($_GET['hash'])): ?>
  <div class="login-box-body">
    <p class="login-box-msg">Przypomnij hasło</p>

    <form name="przypomnij" action="?x=<?= $x ?>" method="post">
      <div class="form-group has-feedback">
        <input type="text" class="form-control" placeholder="Login" name="login" >
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="email" class="form-control" placeholder="Email" name="mail">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-12">
          <button name="przypomnij" type="submit" class="btn btn-primary btn-block btn-flat">Przypomnij</button>
        </div>
      </div>
    </form>
  </div>
<? else: ?>
  <div class="login-box-body">
    <p class="login-box-msg">Wpisz nowe hasło</p>

    <form name="nowe_haslo" action="?x=<?= $x ?>" method="post">
      <input type="hidden" value="<?= $_GET['hash'] ?>" name="hash">
      <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Hasło" name="pass" >
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Powtórz Hasło" name="pass2">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-12">
          <button name="nowe_haslo" type="submit" class="btn btn-primary btn-block btn-flat">Zapisz</button>
        </div>
      </div>
    </form>
  </div>
<? endif; ?>
</div>

<!-- jQuery 3 -->
<script src="./www/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="./www/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="./www/plugins/iCheck/icheck.min.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
  });
</script>
</body>
</html>
