<?
$xx_nazwa = one("SELECT `nazwa_wys` FROM `acp_moduly` WHERE `nazwa` LIKE '$x' LIMIT 1");
$nazwa_strony = $acp_system['acp_nazwa']." | $xx_nazwa";
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?= $nazwa_strony; ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="./www/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="./www/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="./www/bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="./www/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="./www/dist/css/skins/_all-skins.min.css">
  <!-- Morris chart -->
  <link rel="stylesheet" href="./www/bower_components/morris.js/morris.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="./www/bower_components/jvectormap/jquery-jvectormap.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="./www/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="./www/bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="./www/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="./www/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
  <!-- DataTables Responsive CSS -->
  <link rel="stylesheet" href="./www/bower_components/datatables.net-bs/css/dataTables.responsive.css">
  <!-- Pace style -->
  <link rel="stylesheet" href="./www/plugins/pace/pace.min.css">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <!-- Page Icon -->
  <link rel="shortcut icon" href="./www/icon.ico" type="image/x-icon">
</head>

<script> function playSound () { document.getElementById('play').play(); }</script>
<audio id="play" src="./www/powiadomienie.ogg"></audio>


<?
 $player->uklad_16_4 = ($player->uklad_16_4 == 1) ? 'layout-boxed' : '' ;
 $player->pudelkowy = ($player->pudelkowy == 1) ? 'fixed' : '' ;
 $player->menu = ($player->menu == 1) ? 'sidebar-collapse' : '' ;
 $player->prawy_kolor = ($player->prawy_kolor == 1) ? 'control-sidebar-light' : 'control-sidebar-dark' ;
 ?>

<body class="sidebar-mini <?= $player->szablon, " ", $player->uklad_16_4, " ", $player->menu, " ", $player->pudelkowy ?>">
	<div class="wrapper">
<?
if(strtotime($player->last_login) < (time() - 120)) {
	query("UPDATE `acp_users` SET `last_login` = NOW() WHERE `user` = ".$player->user." LIMIT 1;");
}
?>
