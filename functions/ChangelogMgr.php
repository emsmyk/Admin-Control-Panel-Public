<?php
class ChangelogMgr{
  public function changelog_edytuj($id, $admin, $dostep){
    if(uprawnienia($dostep, $admin) == 0){
      return;
    }

    $from = post_to_stdclass();

    query("UPDATE `acp_log_serwery` SET `tekst` = '$from->tekst', `data` = '$from->data' WHERE `id` = $from->id;");
    $_SESSION['msg'] = komunikaty("Edytowano wpis chanelogu ID: $from->id", 1);
    admin_log($admin, "Edytowano wpis chanelogu ID: $from->id", "?x=changelog_edit&xx=&edycja=$from->id");
  }

  public function changelog_usun($id, $admin, $dostep){
    if(uprawnienia($dostep, $admin) == 0){
      return;
    }

		query("DELETE FROM `acp_log_serwery` WHERE `id` = $id LIMIT 1;");
		$_SESSION['msg'] = komunikaty("Usunięto wpis w changelog ID: $id", 1);
    admin_log($admin, "Usunięto wpis w changelog ID: $id");
  }

  public function changelog_add($co, $post, $serwer, $wykonujacy){
    $from = post_to_stdclass();
    $from->steam = toCommunityID($from->steam);

    switch ($co) {
      case 'dodaj_admina':
        if(!is_numeric($from->steam)) {
          $_SESSION['msg'] = komunikaty("Changelog: Nie poprawny steam id", 2);
          return;
        }
        if(empty($from->nick)) {
          $_SESSION['msg'] = komunikaty("Changelog: Pole nick jest puste", 2);
          return;
        }
        if($from->proba == 1) {
          admin_log_srv($serwer, $wykonujacy, 0, "Dodano Admina: $from->nick (STEAM: $from->steam) na okres próbny");
          $_SESSION['msg'] = komunikaty("Changelog: Dodano Admina: $from->nick (STEAM: $from->steam) na okres próbny", 1);
          return;
        }
        else {
          admin_log_srv($id_serwer, $wykonujacy, 0, "Dodano Admina: $from->nick (STEAM: $from->steam)");
          $_SESSION['msg'] = komunikaty("Changelog: Dodano Admina: $from->nick (STEAM: $from->steam)", 1);
          return;
        }
        break;
      case 'awans_deg_rez':
        if(!is_numeric($from->steam)) {
          $_SESSION['msg'] = komunikaty("Changelog: Nie poprawny steam id", 2);
          return;
        }
        if($from->czynnosc == 0){
          $_SESSION['msg'] = komunikaty("Changelog: Wybierz czynność..", 2);
          return;
        }
        if(empty($from->nick)) {
          $_SESSION['msg'] = komunikaty("Changelog: Pole nick jest puste", 2);
          return;
        }
        switch ($from->czynnosc) {
          case 1:
            admin_log_srv($serwer, $wykonujacy, 0, "Przyznano Awans $from->nick (ID: $from->steam)");
            $_SESSION['msg'] = komunikaty("Changelog: Awansowano $from->nick (ID: $from->steam)", 1);
            return;
            break;
          case 2:
            admin_log_srv($serwer, $wykonujacy, 0, "Zdegradowano Admina $from->nick (ID: $from->steam)");
            $_SESSION['msg'] = komunikaty("Changelog: Zdegradowano Admina $from->nick (ID: $from->steam)", 1);
            return;
            break;
          case 3:
            admin_log_srv($serwer, $wykonujacy, 0, "Admin $from->nick zrezygnował z funkcji. (ID: $from->steam)");
            $_SESSION['msg'] = komunikaty("Changelog: Admin $from->nick zrezygnował z funkcji. (ID: $from->steam)", 1);
            return;
            break;
        }
        break;
      case 'wlasny':
        if(empty($from->tekst) || empty($from->data)) {
          $_SESSION['msg'] = komunikaty("Changelog: Pole tekst lub data nie może być puste..", 2);
          return;
        }

        admin_log_srv_time($serwer, $wykonujacy, 0, $from->tekst, $from->data);
        $_SESSION['msg'] = komunikaty("Changelog: $from->tekst [$from->data]", 1);
        return;
        break;
    }
  }
}
?>
