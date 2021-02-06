<?php
class RegisterMgr{
  public function register($login, $pass, $pass2, $mail, $steam){
    $login = real_string($login);
  	$pass = real_string($pass);
  	$mail = real_string($mail);
  	$steam_comunity = toCommunityID($steam);

    if(empty($login) || empty($mail) || empty($steam)){
      $_SESSION['msg'] = komunikaty("Wypełnij wszystkie pola poprawnie..", 3);
      return;
    }

  	if(strlen($login) < 5 ){
      $_SESSION['msg'] = komunikaty("Login za krótki... (Minimalnie 5 znaków)", 3);
      return;
    }
  	elseif(strlen($login) > 15 ){
      $_SESSION['msg'] = komunikaty("Login za długi... (Maksymalnie 15 znaków)", 3);
      return;
    }

  	if(strlen($pass) < 5 ){
      $_SESSION['msg'] = komunikaty("Hasło za krótkie (Minimalnie 5 znaków)", 3);
      return;
    }

    if($pass != $pass2){
      $_SESSION['msg'] = komunikaty("Hasła nie są identyczne..", 3);
      return;
    }

    if($_POST['regulamin'] != 'on'){
      $_SESSION['msg'] = komunikaty("Aby utworzyć konto musisz zakceptować regulamin strony..", 3);
      return;
    }

    $info = row("SELECT count(`login`) AS `elogin`, count(`email`) AS `eemail`, count(`steam`) AS `ssteam` FROM `acp_users` WHERE `login`='".$login."' OR `email`='".$mail."' OR `steam`='".$steam_comunity."'; ");

    if(($info->elogin == 0) && ($info->eemail == 0) && ($info->ssteam == 0) ){
      query("INSERT INTO `acp_users` (`login`, `pass`, `email`, `data_rejestracji`, `steam`) VALUES ('".$login."','".md5($pass)."','".$mail."', NOW(), '".$steam_comunity."'); ");
      $_SESSION['msg'] = komunikaty("Poprawnie zarejestrowano użytkownika $login", 1);
      $_SESSION['register'] = 1;
      header("Location: ?x=login");
    }
    else {
      if($info->elogin != 0) $_SESSION['msg'] = komunikaty("Istnieje już użytkownik z takim loginem ($login)", 3);
      if($info->eemail != 0) $_SESSION['msg'] = komunikaty("Na ten mail został zarejstrowany już jeden użytkownik", 3);
      if($info->ssteam != 0) $_SESSION['msg'] = komunikaty("Istnieje juz użytkownik z takim samym Steam ID ($steam)", 3);
    }

  	return;
  }
  public function dodaj_usera($user){
    $dane->login = real_string($_POST['nick']);
    $dane->pass = real_string($_POST['haslo']);
    $dane->steam_comunity = toCommunityID($_POST['steam_id']);

    if(empty($dane->steam_comunity)) {
      $_SESSION['msg'] = komunikaty("Pole Steam ID jest wymagane!", 3);
      return;
    }
    if(strlen($dane->login) < 5 || strlen($dane->login) > 15) {
      $_SESSION['msg'] = komunikaty("Login musi posiadać minimum 5 znaków oraz nie może być dłuższy niż 15 znaków", 3);
    }
    else {
      $info = row("SELECT count(`login`) as `elogin`, count(`steam`) AS `ssteam` FROM `acp_users` WHERE `login`='".$dane->login."' or `steam`='".$dane->steam_comunity."'");
      if(($info->elogin == 0) && ($info->ssteam == 0)){
  			query("INSERT INTO `acp_users` (`login`, `pass`, `email`, `last_login`, `data_rejestracji`, `steam`) VALUES ('".$dane->login."','".md5($dane->pass)."','', unix_timestamp(), NOW(), '".$dane->steam_comunity."')");
        admin_log($user, "Poprawnie dodano nowego użytkownika $login");
        $_SESSION['msg'] = komunikaty("Poprawnie dodano nowego użytkownika $login", 1);
  		}
      else {
  			if($info->elogin != 0)
         $_SESSION['msg'] = komunikaty("Login $dane->login już istnieje w systemie", 3);
  			if($info->ssteam != 0)
         $_SESSION['msg'] = komunikaty("Użytkownik $info->elogin posiada identyczny Steam ID", 1);
  		}
    }
  }
  public function login($login, $pass){
    $login = real_string($login);
   	$pass = md5(real_string($pass));

    if(empty($login) || empty($pass)){
      $_SESSION['msg'] = komunikaty("Wypełnij wszystkie pola poprawnie..", 4);
      return;
    }

   	$user = row("SELECT `user`, `banned` FROM `acp_users` WHERE `login` = '$login' AND `pass` = '$pass' LIMIT 1");
    if(!empty($user) && is_numeric($user->user) && ($user->user > 0)){
      if($user->banned != 0){
        $_SESSION = array();
 				$_SESSION['user'] = $user->user;

        query("INSERT INTO `acp_users_login_logs` (`user_id`, `ip`, `przegladarka`, `poprawne`, `date`) VALUES ('$user->user', '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['HTTP_USER_AGENT']."', '1', NOW() ); ");
      }
      else {
        $_SESSION['msg'] = komunikaty("Twoje konto zostało zablokowane z powodu łamania zasad społeczności.", 2);
      }
    }
    else {
      $_SESSION['msg'] = komunikaty("Wprowadzone błędne dane.", 3);

      $user_id = one("SELECT `user`, `banned` FROM `acp_users` WHERE `login` = '$login' LIMIT 1");
      if(!empty($user_id)){
        query("INSERT INTO `acp_users_login_logs` (`user_id`, `ip`, `przegladarka`, `poprawne`, `date`) VALUES ('$user_id', '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['HTTP_USER_AGENT']."', '0', NOW() ); ");
      }
    }
    return;
 }

 public function forget_password($login, $mail, $acp_mail){
   $login = real_string($login);
   $mail = real_string($mail);

   if(empty($login) || empty($mail)){
     $_SESSION['msg'] = komunikaty("Wypełnij wszystkie pola poprawnie..", 3);
     return;
   }

   $dane = row("SELECT `user`, `pass_hash`, `email` FROM `acp_users` WHERE `login` = '$login' AND `email` = '$mail' LIMIT 1");
   if(empty($dane->email)){
     $_SESSION['msg'] = komunikaty("Nie został dodany adres email, skontaktuj się z administratorem..", 3);
     return;
   }

   if(empty($dane->pass_hash)){
     $hash = substr(md5(mt_rand()), 0, 30);
     query("UPDATE `acp_users` SET `pass_hash` = '$hash' WHERE `user` = $dane->user;");

     $subject = 'Przypomnienie hasła';
     $message = "<html>
     <head>
       <title>Przypomnienie hasła</title>
     </head>
     <body>
       <p>Witaj $login!</p>
       <p>Aby zrestartować hasło wejdź na link poniżej:</p>
       <p>https://".$_SERVER['HTTP_HOST']."/?x=forget_password&hash=$hash</p>
     </body>
     </html>";
     $headers[] = 'MIME-Version: 1.0';
     $headers[] = 'Content-type: text/html; charset=utf-8';
     $headers[] = "From: ACP <$acp_mail>";

     mail($dane->email, $subject, $message, implode("\r\n", $headers));

     $_SESSION['msg'] = komunikaty("$hash", 2);
     return;
   }
   else{
     $_SESSION['msg'] = komunikaty("Wygenerowany został już link, sprawdź pocztę.", 2);
     return;
   }
 }

 public function forget_password_new($pass, $pass2, $hash){
   $pass = real_string($pass);

   if(strlen($pass) < 5 ){
     $_SESSION['msg'] = komunikaty("Hasło za krótkie (Minimalnie 5 znaków)", 3);
     return;
   }

   if($pass != $pass2){
     $_SESSION['msg'] = komunikaty("Hasła nie są identyczne..", 3);
     return;
   }
   $user_id = one("SELECT `user` FROM `acp_users` WHERE `pass_hash` = '$hash' LIMIT 1");
   query("UPDATE `acp_users` SET `pass` = '".md5($pass)."', `pass_hash` = NULL WHERE `user` = $user_id;");
   $_SESSION['msg'] = komunikaty("Zaktualizowano hasło <a href='?x=login>'>przejdz tutaj</a> aby się zalgować", 1);
 }

}
?>
