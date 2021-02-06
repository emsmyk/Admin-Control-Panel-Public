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
if(!empty($_SESSION['user'])){
  header("Location: ?x=wpisy");
}

if(!empty($_POST)){
  $func->login($_POST['login'], $_POST['pass']);
  header("Location: ?x=$x");
}
?>

<div class="login-box">
  <div class="login-logo">
    <a href="?x=default"><? echo $acp_system['acp_nazwa']; ?> | ACP</a>
  </div>
  <div class="login-box-body">

    <form action="?x=<?= $x ?>" method="post">
      <div class="form-group has-feedback">
        <input type="text" class="form-control" placeholder="Login" name="login">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Hasło" name="pass">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-12">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Zaloguj</button>
        </div>
      </div>
    </form>
    <div class="social-auth-links text-center">

    </div>
    <a href="?x=forget_password">Zapomniałeś hasła?</a><br>
    <a href="?x=register" class="text-center">Zarejestruj się</a>

  </div>
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
