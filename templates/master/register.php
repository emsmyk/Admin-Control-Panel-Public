<?
$func = getClass('Register');
if($acp_system['acp_rejestracja'] == 1){
	$_SESSION['msg'] = komunikaty("Rejestracja została zablokowana przez administora ACP", 4);
}
if($_SESSION['register'] == 1){
	header("Location: ?x=login");
	unset($_SESSION['register']);
}
?>
<body class="hold-transition register-page">
<div class="login-box">
  <div class="row">
	<section class="col-lg-12">
		<p><? if(!empty($_SESSION['msg'])){ echo $_SESSION['msg']; unset($_SESSION['msg']); } ?></p>
	</section >
  </div>

<?
if(!empty($_POST)){
	if($acp_system['acp_rejestracja'] == 0){
	  $func->register($_POST['login'], $_POST['pass'], $_POST['pass2'], $_POST['mail'], $_POST['steam']);
	  header("Location: ?x=$x");
	}
}
?>

<div class="register-box">
  <div class="register-logo">
    <a href="?x=default"><? echo $acp_system['acp_nazwa']; ?> | ACP</a>
  </div>
  <div class="login-box-body">

    <form action="?x=<?= $x ?>" method="post">
			<div class="form-group has-feedback">
        <input type="text" class="form-control" placeholder="Login" name="login">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="email" class="form-control" placeholder="Email" name="mail">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Hasło" name="pass">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Powtórz hasło" name="pass2">
        <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
      </div>
			<div class="form-group">
				<input class="form-control"  pattern="^STEAM_[01]:[01]:\d+$" placeholder="STEAM_X:X:XXXXXXXX" name="steam" type="text" value="">
			</div>
      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
              <input type="checkbox" name="regulamin"> Akceptacja regulaminu</a>
            </label>
          </div>
        </div>
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Rejestracja</button>
        </div>
      </div>
    </form>
    <div class="social-auth-links text-center">

    </div>
    <a href="?x=login">Masz konto? Zaluguj się..</a><br>

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
