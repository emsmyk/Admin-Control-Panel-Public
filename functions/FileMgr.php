<?
class FileMgr {
  public function sprawdz_katalog($serwer){
    $path = "www/upload/serwer_$serwer";
    if(!file_exists("$path")) {
      mkdir("www/upload/serwer_$serwer", 0700, true);
      return "<p>Utworzono brakujący katalog dla serwera: $serwer</p>";
    }
  }

  function file_tworzy($file_name, $serwer, $i){
    $path = "www/upload/serwer_$serwer";
    $file_path = $path.'/'.$file_name;

    // naglowek i stopka
    $czas_stopka = date("Y-m-d H:i:s");
    $naglowek = "////////////////////////////////////// \n//////////// EMCE ACP //////////////// \n////////////////////////////////////// \n\n";
    $stopka = "\n////////////////////////////////////// \n//// Wykonano $czas_stopka //// \n//////////////////////////////////////";


    switch ($i) {
      case 'uslugi':
        $file = fopen($file_path, 'w');
        fwrite($file,$naglowek);

        $srv_uslugi = all("SELECT *, (SELECT `nazwa` FROM `acp_uslugi_rodzaje` WHERE `id` = `rodzaj`) AS `nazwa`, (SELECT `flags` FROM `acp_uslugi_rodzaje` WHERE `id` = `rodzaj`) AS `flagi` FROM `acp_uslugi` WHERE `serwer` = $serwer");
        foreach($srv_uslugi as $srv_uslugi_q){
          $uslugi = "//Rodzaj: $srv_uslugi_q->nazwa\n//Usługa do: $srv_uslugi_q->koniec  \n\"$srv_uslugi_q->steam_id\" \"$srv_uslugi_q->flagi\" \n";
          fwrite($file,$uslugi);
        }

        fwrite($file,$stopka);
        fclose($file);
      break;
      case 'reklamy':
        $file = fopen($file_path, 'w');
        fwrite($file,$naglowek);

        $co_ile_reklama = one("SELECT `czas_reklam` FROM `acp_serwery` WHERE `serwer_id` = $serwer");
        if(empty($co_ile_reklama) || $co_ile_reklama == '' || $co_ile_reklama > 10){
          $co_ile_reklama = '30';
        }
        $pierwsza_linia = "\"Reklama\" \n{ \n\"time\" \"$co_ile_reklama\" \n\"text\" \n	{ \n";
        fwrite($file,$pierwsza_linia);

        $id = 1;
        $dzis =  date("j");
        $srv_reklamy = all("SELECT * FROM `acp_serwery_reklamy` WHERE `serwer_id` IN ( 0, '".$serwer."' ) ORDER BY `id` +0 ASC;");
        foreach($srv_reklamy as $srv_reklamy_q){
          if($dzis >= (int)$srv_reklamy_q->zakres_start && $dzis <= (int)$srv_reklamy_q->zakres_stop && (int)$srv_reklamy_q->zakres == 1):
            $reklamy = "		\"".$id++."\" \n		{ \n			\"$srv_reklamy_q->gdzie\" \"$srv_reklamy_q->tekst\" \n		} \n";
            fwrite($file,$reklamy);
          endif;
          if($srv_reklamy_q->zakres == 0):
            $reklamy = "		\"".$id++."\" \n		{ \n			\"$srv_reklamy_q->gdzie\" \"$srv_reklamy_q->tekst\" \n		} \n";
            fwrite($file,$reklamy);
          endif;
        }
        fwrite($file,"	} \n}");

        fwrite($file,$stopka);
        fclose($file);
      break;
      case 'rangi-tabela':
        $file = fopen($file_path, 'w');
        fwrite($file,$naglowek);

        fwrite($file,"\"Setups\" \n{");
        $srv_rangi_tabela = all("SELECT * FROM `acp_serwery_rangi` WHERE `serwer_id` IN ( 0, '".$serwer."' ) AND `istotnosc` != '0' ORDER BY `istotnosc` +0 DESC;");
        foreach($srv_rangi_tabela as $srv_rangi_tabela_q){
          $rangi_tabela = "	\n	\"$srv_rangi_tabela_q->id\" \n	{ \n		\"flag\"	\"$srv_rangi_tabela_q->flags\" \n		\"tag\"	\"$srv_rangi_tabela_q->tag_tabela\" \n	}";
          fwrite($file,$rangi_tabela);
        }
        fwrite($file,"	\n	\"all\" \n	{ \n		\"flag\"	\"\" \n		\"tag\"	\"- Gracz -\" \n	} \n}");

        fwrite($file,$stopka);
        fclose($file);
      break;
      case 'rangi-say':
        $file = fopen($file_path, 'w');
        fwrite($file,$naglowek);

  			fwrite($file,"\"chat_colors_csgo\" \n{");
  			$srv_rangi_tabela = all("SELECT * FROM `acp_serwery_rangi` WHERE `serwer_id` IN ( 0, '".$serwer."' ) AND `istotnosc` != '0' ORDER BY `istotnosc` +0 DESC;");
  			foreach($srv_rangi_tabela as $srv_rangi_tabela_q){
  				$rangi_say = "	\n	\"$srv_rangi_tabela_q->flags\" \n	{ \n		\"tag\"	\"$srv_rangi_tabela_q->tag_say \" \n		\"tag_Color\"	\"{".$srv_rangi_tabela_q->tag_say_kolor."}\" \n		\"name_Color\"	\"{".$srv_rangi_tabela_q->nick_say_kolor."}\" \n		\"chat_Color\"	\"{DEFAULT}\" \n	}";
  				fwrite($file,$rangi_say);
  			}
  			fwrite($file,"\n}");

        fwrite($file,$stopka);
        fclose($file);
      break;
      case 'hextags':
        $file = fopen($file_path, 'w');
        fwrite($file,$naglowek);

  			fwrite($file,"\"HexTags\" \n{");
  			$srv_hextags_q = all("SELECT * FROM `acp_serwery_hextags` WHERE `serwer_id` IN ( 0, '".$serwer."' ) ORDER BY `istotnosc` +0 DESC;");
  			foreach($srv_hextags_q as $srv_h){
  				$rangi_say = "	\n	\"$srv_h->hextags\" \n	{ \n		\"TagName\"	\"$srv_h->TagName \" \n		\"ScoreTag\"	\"$srv_h->ScoreTag \" \n		\"ChatTag\"	\"{".$srv_h->TagColor."} $srv_h->ChatTag \" \n		\"ChatColor\"	\"{".$srv_h->ChatColor."}\" \n		\"NameColor\"	\"{".$srv_h->NameColor."}\" \n		\"Force\"	\"$srv_h->Force\" \n	}";
  				fwrite($file,$rangi_say);
  			}
  			fwrite($file,"\n}");

        fwrite($file,$stopka);
        fclose($file);
      break;
      case 'database':
        $file = fopen($file_path, 'w');
        fwrite($file,$naglowek);

        fwrite($file,"\"Databases\" \n{ \n \"driver_default\"		\"mysql\" \n");
        $storge = "\n	\"storage-local\" \n	{ \n		\"driver\"	\"sqlite\" \n		\"host\"	\"sourcemod-local\"  \n	}";
        fwrite($file,$storge);
        $srv_q = all("SELECT * FROM `acp_serwery_baza` WHERE `serwer_id` IN ( 0, $serwer);");
        foreach($srv_q as $srv){
          if($srv->d_time_port_on == 1) {

            $tresc = "\n	\"$srv->nazwa\" \n	{ \n		\"driver\"	\"$srv->d_driver\" \n		\"host\"	\"$srv->d_host\" \n		\"database\"	\"$srv->d_baze\" \n		\"user\"	\"$srv->d_user\" \n		\"pass\"	\"$srv->d_pass\" \n		\"timeout\"	\"$srv->d_timeout\" \n		\"port\"	\"$srv->d_port\" \n	}";
            fwrite($file,$tresc);
          }
          else {
            $tresc = "\n	\"$srv->nazwa\" \n	{ \n		\"driver\"	\"$srv->d_driver\" \n		\"host\"	\"$srv->d_host\" \n		\"database\"	\"$srv->d_baze\" \n		\"user\"	\"$srv->d_user\" \n		\"pass\"	\"$srv->d_pass\"  \n	}";
            fwrite($file,$tresc);
          }
        }
        fwrite($file,"\n}");

        fwrite($file,$stopka);
        fclose($file);
      break;
      case 'mapy_umc':
        $file = fopen($file_path, 'w');
        fwrite($file,$naglowek);

        fwrite($file,"\"umc_mapcycle\" \n{");

        $grupy_q = all("SELECT * FROM `acp_serwery_mapy` WHERE `serwer_id` IN ( 0, $serwer); ");
        foreach($grupy_q as $grupy){
          fwrite($file,"\n  \"$grupy->nazwa\" \n  {");
            if(!empty($grupy->display_template) || $grupy->display_template != 0){
              fwrite($file,"\n    \"display-template\"     \"$grupy->display_template\" ");
            }
            if(!empty($grupy->maps_invote) || $grupy->maps_invote != 0){
              fwrite($file,"\n    \"maps_invote\"     \"$grupy->maps_invote\" ");
            }
            if(!empty($grupy->group_weight) || $grupy->group_weight != 0){
              fwrite($file,"\n    \"group_weight\"     \"$grupy->group_weight\" ");
            }
            if(!empty($grupy->next_mapgroup) || $grupy->next_mapgroup != 0){
              fwrite($file,"\n    \"next_mapgroup\"     \"$grupy->next_mapgroup\" ");
            }
            if(!empty($grupy->default_min_players) || $grupy->default_min_players != 0){
              fwrite($file,"\n    \"default_min_players\"     \"$grupy->default_min_players\" ");
            }
            if(!empty($grupy->default_max_players) || $grupy->default_max_players != 0){
              fwrite($file,"\n    \"default_max_players\"     \"$grupy->default_max_players\" ");
            }
            if(!empty($grupy->default_min_time) || $grupy->default_min_time != 0){
              fwrite($file,"\n    \"default_min_time\"     \"$grupy->default_min_time\" ");
            }
            if(!empty($grupy->default_max_time) || $grupy->default_max_time != 0){
              fwrite($file,"\n    \"default_max_time\"     \"$grupy->default_max_time\" ");
            }
            if(!empty($grupy->default_allow_every) || $grupy->default_allow_every != 0){
              fwrite($file,"\n    \"default_allow_every\"     \"$grupy->default_allow_every\" ");
            }
            if(!empty($grupy->command) || $grupy->command != 0){
              fwrite($file,"\n    \"command\"     \"$grupy->command\" ");
            }
            if(!empty($grupy->nominate_flags) || $grupy->nominate_flags != 0){
              fwrite($file,"\n    \"nominate_flags\"     \"$grupy->nominate_flags\" ");
            }
            if(!empty($grupy->adminmenu_flags) || $grupy->adminmenu_flags != 0){
              fwrite($file,"\n    \"adminmenu_flags\"     \"$grupy->adminmenu_flags\" ");
            }

            $mapy_q = all("SELECT * FROM `acp_serwery_mapy_det` WHERE `mapy_id` = $grupy->id ORDER BY `nazwa` ASC; ");
            foreach($mapy_q as $mapy){
              fwrite($file,"\n    \"$mapy->nazwa\" \n     {");
                if(!empty($mapy->display) || $mapy->display != 0){
                  fwrite($file,"\n      \"display\"     \"$mapy->display\" ");
                }
                if(!empty($mapy->weight) || $mapy->weight != 0){
                  fwrite($file,"\n      \"weight\"     \"$mapy->weight\" ");
                }
                if(!empty($mapy->next_mapgroup) || $mapy->next_mapgroup != 0){
                  fwrite($file,"\n      \"next_mapgroup\"     \"$mapy->next_mapgroup\" ");
                }
                if(!empty($mapy->min_players) || $mapy->min_players != 0){
                  fwrite($file,"\n      \"min_players\"     \"$mapy->min_players\" ");
                }
                if(!empty($mapy->max_players) || $mapy->max_players != 0){
                  fwrite($file,"\n      \"max_players\"     \"$mapy->max_players\" ");
                }
                if(!empty($mapy->min_time) || $mapy->min_time != 0){
                  fwrite($file,"\n      \"min_time\"     \"$mapy->min_time\" ");
                }
                if(!empty($mapy->max_time) || $mapy->max_time != 0){
                  fwrite($file,"\n      \"max_time\"     \"$mapy->max_time\" ");
                }
                if(!empty($mapy->allow_every) || $mapy->allow_every != 0){
                  fwrite($file,"\n      \"allow_every\"     \"$mapy->allow_every\" ");
                }
                if(!empty($mapy->command) || $mapy->command != 0){
                  fwrite($file,"\n      \"command\"     \"$mapy->command\" ");
                }
                if(!empty($mapy->nominate_flags) || $mapy->nominate_flags != 0){
                  fwrite($file,"\n      \"nominate_flags\"     \"$mapy->nominate_flags\" ");
                }
                if(!empty($mapy->adminmenu_flags) || $mapy->adminmenu_flags != 0){
                  fwrite($file,"\n      \"adminmenu_flags\"     \"$mapy->adminmenu_flags\" ");
                }
                if(!empty($mapy->nominate_group) || $mapy->nominate_group != 0){
                  fwrite($file,"\n      \"nominate_group\"     \"$mapy->nominate_group\" ");
                }
              fwrite($file,"\n     } \n");
            }

          fwrite($file,"\n  } \n");
        }

        fwrite($file,"  \n} \n");

        fwrite($file,$stopka);
        fclose($file);
      break;
      case 'mapchooser':
        $file = fopen($file_path, 'w');
        fwrite($file,$naglowek);

        $grupy_q = all("SELECT * FROM `acp_serwery_mapy` WHERE `serwer_id` IN ( 0, $serwer); ");
        foreach($grupy_q as $grupy){
          fwrite($file,"//Grupa Map: $grupy->nazwa\n");
          $mapy_q = all("SELECT * FROM `acp_serwery_mapy_det` WHERE `mapy_id` = $grupy->id ORDER BY `nazwa` ASC; ");
          foreach($mapy_q as $mapy){
            fwrite($file,"$mapy->nazwa\n");
          }
        }

        fwrite($file,$stopka);
        fclose($file);
      break;
      case 'help_menu':
        $file = fopen($file_path, 'w');
        fwrite($file,$naglowek);

        fwrite($file,"\"helpmenu\" \n{");

        $query = all("SELECT * FROM `acp_serwery_helpmenu` WHERE `serwer_id` = $serwer; ");
        foreach($query as $row){
          if($row->lista_serwerow == 1){
            fwrite($file,"\n    \"listserwer\" \n     {\n      \"nazwa\"  \"Lista Serwerów\" \n      \"komenda\"  \"sm_serwery\" \n     }");
          }
          if($row->lista_adminow == 1){
            fwrite($file,"\n    \"listaadminow\" \n     {\n      \"nazwa\"  \"Lista Adminów\" \n      \"komenda\"  \"sm_admini\" \n     }");
          }
          if($row->opis_vipa == 1){
            fwrite($file,"\n    \"vip\" \n     {\n      \"nazwa\"  \"Opis Vipa\" \n      \"komenda\"  \"sm_vip\" \n     }");
          }
          if($row->lista_komend == 1){
            fwrite($file,"\n    \"komendy\" \n     {\n      \"nazwa\"  \"Lista komend\" \n      \"komenda\"  \"sm_komendy\" \n     }");
          }
          if($row->statystyki == 1){
            fwrite($file,"\n    \"statytki\" \n     {\n      \"nazwa\"  \"Statystki\" \n      \"komenda\"  \"sm_statystki\" \n     }");
          }
        }

        fwrite($file,"  \n} \n");

        fwrite($file,$stopka);
        fclose($file);
      break;
      case 'help_menu_listaserwerow':
        $file = fopen($file_path, 'w');
        fwrite($file,$naglowek);

        fwrite($file,"\"listserwer\" \n{");

        $query = all("SELECT `istotnosc`, `nazwa`, `mod`, `graczy`, `max_graczy` FROM `acp_serwery` WHERE `serwer_on` = 1 ORDER BY `istotnosc` ASC");
        foreach($query as $row){
          fwrite($file,"\n    \"$row->istotnosc\" \n     {\n      \"nazwa\"  \"[$row->mod] $row->nazwa\" \n      \"graczy\"  \"$row->graczy\" \n      \"sloty\"  \"$row->max_graczy\" \n      \"ID\"  \"$row->istotnosc\" \n     }");
        }

        fwrite($file,"  \n} \n");

        fwrite($file,$stopka);
        fclose($file);
      break;
      case 'help_menu_listaserwerow_details':
        $file = fopen($file_path, 'w');
        fwrite($file,$naglowek);

        fwrite($file,"\"serverdetale\" \n{");

        $query = all("SELECT `istotnosc`, `nazwa`, `mod`, `graczy`, `max_graczy`, `ip`, `port`, `mapa` FROM `acp_serwery` WHERE `serwer_on` = 1 ORDER BY `istotnosc` ASC");
        foreach($query as $row){
          fwrite($file,"\n    \"$row->istotnosc\" \n     {\n      \"nazwa\"  \"[$row->mod] $row->nazwa\" \n      \"graczy\"  \"$row->graczy\" \n      \"sloty\"  \"$row->max_graczy\" \n      \"mapa\"  \"$row->mapa\" \n      \"ip\"  \"$row->ip:$row->port\" \n      \"id\"  \"$row->istotnosc\" \n     }");
        }

        fwrite($file,"  \n} \n");

        fwrite($file,$stopka);
        fclose($file);
      break;
      case 'help_menu_listaadminow':
        $file = fopen($file_path, 'w');
        fwrite($file,$naglowek);

        fwrite($file,"\"admins\" \n{");

        $dane = one("SELECT `dane` FROM `acp_cache_api` WHERE `get` = 'serwer_id".$serwer."_admin' LIMIT 1;");
        $dane = json_decode($dane); $i = 1;
        foreach($dane as $row){
          switch ($row->steam_status) {
            case 0:
              $row->steam_status_tekst = 'Offline';
              break;
            case 1:
              $row->steam_status_tekst = 'Online';
              break;
            case 3:
              $row->steam_status_tekst = 'Zajęty';
              break;
            case 4:
              $row->steam_status_tekst = 'Zajęty';
              break;
            default:
              $row->steam_status_tekst = '-';
              break;
          }
          fwrite($file,"\n    \"".$i++."\" \n     {\n      \"nick\"  \"$row->steam_nick\" \n      \"ranga\"  \"$row->srv_group\" \n      \"steamID\"  \"$row->authid\" \n      \"status\"  \"$row->steam_status_tekst\" \n     }");
        }

        fwrite($file,"  \n} \n");

        fwrite($file,$stopka);
        fclose($file);
      break;
      case 'help_menu_opisvipa':
        $file = fopen($file_path, 'w');
        fwrite($file,$naglowek);

        fwrite($file,"\"vippanel\" \n{");

        $dane = all("SELECT `tekst`, `kolejnosc` FROM `acp_serwery_helpmenu_vip` WHERE `serwer_id` = '$serwer' ORDER BY `kolejnosc` ASC;");
        foreach($dane as $row){
          fwrite($file,"\n    \"$row->kolejnosc\" \n     {\n      \"nazwa\"  \"$row->tekst\" \n      \"nr\"  \"$row->kolejnosc\" \n     }");
        }

        fwrite($file,"  \n} \n");

        fwrite($file,$stopka);
        fclose($file);
      break;
      case 'help_menu_komendy':
        $file = fopen($file_path, 'w');
        fwrite($file,$naglowek);

        fwrite($file,"\"komendy\" \n{");

        $dane = all("SELECT `komenda`, `tekst`, `kolejnosc` FROM `acp_serwery_helpmenu_komendy` WHERE `serwer_id` = '$serwer' ORDER BY `kolejnosc` ASC;");
        foreach($dane as $row){
          fwrite($file,"\n    \"$row->kolejnosc\" \n     {\n      \"komenda\"  \"$row->komenda\" \n      \"opis\"  \"$row->tekst\" \n     }");
        }

        fwrite($file,"  \n} \n");

        fwrite($file,$stopka);
        fclose($file);
      break;
      case 'help_menu_statystyki':
        $file = fopen($file_path, 'w');
        fwrite($file,$naglowek);

        fwrite($file,"\"statystyki\" \n{");
          fwrite($file,"\n    \"1\" \n     {\n      \"komenda\"  \"say top10\" \n      \"opis\"  \"Top10\" \n     }");
          fwrite($file,"\n    \"2\" \n     {\n      \"komenda\"  \"say rank\" \n      \"opis\"  \"Moja pozycja\" \n     }");

        fwrite($file,"  \n} \n");

        fwrite($file,$stopka);
        fclose($file);
      break;
      case 'roundsound':
        $file = fopen($file_path, 'w');
        fwrite($file,$naglowek);

        fwrite($file,"\"Abner Res\" \n{");
          $aktualiny_rs = one("SELECT `conf_value` FROM `rs_ustawienia` WHERE `conf_name` = 'rs_roundsound' LIMIT 1");
          $lista_piosenek = one("SELECT `lista_piosenek` FROM `rs_roundsound` WHERE `id` = $aktualiny_rs LIMIT 1");
          $lista_piosenek = json_decode($lista_piosenek);
          foreach ($lista_piosenek as $value) {
            $piosenka = row("SELECT `id`, `nazwa`, `wykonawca`, `album`, `mp3_code` FROM `rs_utwory` WHERE `id` = $value LIMIT 1");
            fwrite($file,"\n    \"".$piosenka->mp3_code.".mp3\" \n     {\n      \"songname\"  \"".$piosenka->nazwa." - ".$piosenka->wykonawca."\" \n     }");
          }

        fwrite($file,"  \n} \n");

        fwrite($file,$stopka);
        fclose($file);
      break;
      case 'roundsound_cfg':
        $file = fopen($file_path, 'w');
        fwrite($file,$naglowek);

        fwrite($file,"\n");
        fwrite($file,"// ConVars for plugin abner_res.smx\n");
        fwrite($file," abner_res_version \"4.1\" \n");
        fwrite($file," res_client_preferences \"1\" \n");
        $rs_katalog = one("SELECT `conf_value` FROM `rs_ustawienia` WHERE `conf_name` = 'rs_katalog' LIMIT 1");
        $rs_roundsound = one("SELECT `conf_value` FROM `rs_ustawienia` WHERE `conf_name` = 'rs_roundsound' LIMIT 1");

        fwrite($file," res_ct_path \"$rs_katalog/$rs_roundsound\" \n");
        fwrite($file," res_tr_path \"$rs_katalog/$rs_roundsound\" \n");
        fwrite($file," res_default_volume \"0.75\" \n");
        fwrite($file," res_draw_path \"1\" \n");
        fwrite($file," res_play_to_the_end \"0\" \n");
        fwrite($file," res_play_type \"1\" \n");
        fwrite($file," res_print_to_chat_mp3_name \"1\" \n");
        fwrite($file," res_stop_map_music \"1\" \n");

        fwrite($file,$stopka);
        fclose($file);
      break;
    }
    return "<p>Utworzono plik: $file_name w katalogu $path dla serwera ID: $serwer</p>";
  }
}
?>
