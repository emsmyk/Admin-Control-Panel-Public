<?php
class MsgMgr{

	public function wyslij_wiadomosc($user, $to, $tytul, $text){
		$user = (int)$user;
		$to = real_string($to);

		$tytul = real_string($tytul);
		$to_user = one("select user from acp_users where login ='".$to."' limit 1");


		if(!empty($to) && !empty($tytul) && !empty($text)){
			if (!empty($to_user)){
				query("insert into acp_messages (m_from, m_to, m_type, m_tytul, m_text) values($user, $to_user , 2, '$tytul', '$text'),($user, $to_user, 1, '$tytul', '$text')");
			}
			else {
				$_SESSION['msg'] = komunikaty("Nie ma takiego użytkownika. ( Pamiętaj że login steam nie jest takie sam jak login użytkownika )", 3);
			}
		}
		else {
			$_SESSION['msg'] = komunikaty("Wypełnij wszystkie pola!", 4);
		}
	}

	public function odrzuc_wiadomosc($user, $msg_id) {
		$user = (int)$user;
		$msg_id = (int)$msg_id;

		query("delete from acp_messages where m_id = $msg_id and m_czyja = $user and m_type = 3 limit 1");
		$_SESSION['msg'] = komunikaty("Kopia została odrzucona.", 1);
	}
	public function zapisz_wiadomosc($user, $to, $tytul, $text){
		$user = (int)$user;
		$to = real_string($to);

		$tytul = real_string($tytul);

		$to_user = one("select user from acp_users where login ='".$to."' limit 1");
		if(is_null($to_user)) { $to_user = 0; }

		query("insert into acp_messages (m_from, m_to, m_type, m_czyja, m_tytul, m_text) values($user, $to_user , 3, $user, '$tytul', '$text')");
		$_SESSION['msg'] = komunikaty("Wiadomość została zapisana jako kopia robocza!", 1);

	}

	public function zapisz_wiadomosc_update($user, $to, $tytul, $text, $id){
		$id = (int)$id;
		$user = (int)$user;
		$to = real_string($to);

		$tytul = real_string($tytul);
		$to_user = one("select user from acp_users where login ='".$to."' limit 1");
		if(is_null($to_user)) { $to_user = 0; }

		query("UPDATE `acp_messages` SET `m_to` = '$to_user', `m_tytul` = '$tytul', `m_text` = '$text' WHERE `m_id` = $id;");
		$_SESSION['msg'] = komunikaty("Kopia robocza została zaaktualizowana.", 1);

	}

	public function msg_del($user, $type, $msg_id){
		$user = (int)$user;
		$type = (int)$type;
		$msg_id = (int)$msg_id;
		$status = one("select m_status from acp_messages where m_id = $msg_id and m_to = $user and m_type = 1 limit 1");
		$_SESSION['msg'] = komunikaty("Wiadomość została usunięta.", 1);
		switch($type){
			case 1:
				query("delete from acp_messages where m_id = $msg_id and m_to = $user and m_type = 1 limit 1");

			break;
			case 2:
				query("delete from acp_messages where m_id = $msg_id and m_from = $user and m_type = 2 limit 1");
			break;

			default:
			break;
		}
	}
	public function msg_kosz($user, $type, $msg_id){
		$user = (int)$user;
		$type = (int)$type;
		$msg_id = (int)$msg_id;
		$_SESSION['msg'] = komunikaty("Wiadomość została przeniesiona do kosza.", 1);
		switch($type){
			case 1:
				query("UPDATE `acp_messages` SET `m_type` = '0', `m_czyja` = '$user' WHERE `m_id` = $msg_id and m_to = $user and m_type = 1 limit 1");
			break;
			case 2:
				query("UPDATE `acp_messages` SET `m_type` = '0', `m_czyja` = '$user' WHERE `m_id` = $msg_id and m_from = $user and m_type = 2 limit 1");
			break;

			default:
			break;
		}
	}
	public function msg_read($user,  $msg_id){
		$type = row("select m_type, m_status from acp_messages where m_id = $msg_id and m_to = $user limit 1");

		query("update acp_messages set m_status = 1 where m_id = $msg_id and m_to = $user limit 1");

	}


}
?>
