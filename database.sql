-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Czas generowania: 06 Lut 2021, 23:42
-- Wersja serwera: 10.3.27-MariaDB-cll-lve
-- Wersja PHP: 7.4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `v55582717_publicacp`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `acp_system`
--

CREATE TABLE `acp_system` (
  `conf_name` varchar(255) NOT NULL,
  `conf_value` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `acp_system`
--

INSERT INTO `acp_system` (`conf_name`, `conf_value`) VALUES
('acp_cron_key', ''),
('time_rangi', ''),
('time_reklamy', '7200'),
('acp_cron_stats_date_hour', '2021-02-06 15:00:04'),
('time_uslugi', '3600'),
('time_baza', '86400'),
('cron_uslugi', '2021-02-06 14:16:12'),
('cron_reklamy', '2021-02-06 14:10:04'),
('cron_rangi', '2020-06-26 20:32:13'),
('cron_baza', '2021-02-05 21:18:03'),
('acp_nazwa', 'Admin Control Panel'),
('acp_wersja', '1.16'),
('acp_statystki_pobierz_dane', '2019-07-10'),
('acp_steam_api', 'TWÓJ API STEAM'),
('acp_timezone', 'Europe/Warsaw'),
('acp_steam_time', '3600'),
('cron_steam_update', '2019-08-04 09:18:02'),
('cron_naglowek', '//////////////////////////////////////\r\n//////////// EMCE ACP ////////////////\r\n//////////////////////////////////////'),
('cron_stopka', '//////////////////////////////////////\r\n//// Wykonano $czas //// \r\n//////////////////////////////////////'),
('acp_statystki_graczy_przelicz_dane_hour', '2019-08-07 19:10'),
('cron_serwery', '2021-02-06 15:04:22'),
('api_time_sb_bany', '120'),
('api_time_sb_muty', '120'),
('api_key', ''),
('api_time_sb_admini', '60'),
('acp_steam_count_limit', '20'),
('time_serwery', '60'),
('wpisy_ilosc_wpisow', '5'),
('wpisy_ilosc_komentarzy', '3'),
('time_cvary', '0'),
('cron_cvary', '2019-07-28 16:59:13'),
('acp_statystki_graczy_przelicz_dane', '2019-08-07'),
('acp_cron_stats_date_day', '2021-02-06'),
('acp_cron_stats_date_month', '2021-02-01'),
('cron_optym_log_serwerow_limit', '30000'),
('cron_optym_log_serwerow_day', '14'),
('cron_optym_stare_uslugi_limit', '30'),
('cron_optym_stare_uslugi_hour', '24'),
('cron_optym_po_logach_optym_day', ''),
('cron_optym_powiadomienia_odczytane', '30'),
('cron_optym_powiadomienia_usun', '30'),
('acp_cron_stats_gosetti', '2021-02-06'),
('acp_cron_stats_hlstats', '2021-01-14'),
('acp_cron_stats_gametracker', ''),
('api_sb_user', ''),
('api_sb_db', ''),
('api_sb_host', ''),
('api_sb_pass', ''),
('api_hlx_user', ''),
('api_hlx_db', ''),
('api_hlx_host', ''),
('api_hlx_pass', ''),
('time_hextags', '7200'),
('cron_hextags', '2021-02-06 13:30:03'),
('logo_podstawowe', 'https://i.imgur.com/QwWkBDD.png'),
('wpisy_nowy_dlugosc_tekstu', '5'),
('wpisy_nowy_dlugosc_tytulu_min', '5'),
('wpisy_nowy_dlugosc_tytulu_max', '60'),
('wpisy_komentarz_dlugosc_min', '5'),
('wpisy_last_login_on', '1'),
('wpisy_last_login_liczba', '8'),
('time_mapy', '14400'),
('cron_mapy', '2021-02-06 14:04:04'),
('logo_logo', 'https://i.imgur.com/miTL2VF.png'),
('logo_napis', 'https://i.imgur.com/uV86qjo.png'),
('logo_prawa', 'https://i.imgur.com/m26cmrY.png'),
('tlo_sourcebans', ''),
('tlo_adminlist', ''),
('tlo_hlstats', ''),
('tlo_changelog', ''),
('cron_serwery_time_off', '300'),
('cron_file_list_pluginy', '2021-02-06 14:56:06'),
('cron_file_list_pluginy_time', '1800'),
('cron_file_list_mapy', '2021-02-06 14:56:06'),
('cron_file_list_mapy_time', '1800'),
('galeria_map_noimage', 'www/maps/nomap.jpg'),
('cron_adminlist', '2021-02-06 15:04:22'),
('cron_adminlist_time', '120'),
('cron_optym_stare_reklamy_limit', '10'),
('cron_optym_stare_uslugi_day', '1'),
('acp_rejestracja', '1'),
('acp_mail', 'acp@sloneczny-dust.pl'),
('acp_strona_www', ''),
('acp_forum', ''),
('acp_statystyki', ''),
('logo_sourcebans', ''),
('logo_adminlist', ''),
('logo_hlstats', ''),
('logo_changelog', ''),
('tlo_galeria_map', ''),
('logo_galeria_map', ''),
('time_help_menu', '3600'),
('cron_help_menu', '2021-02-06 14:16:13'),
('cron_optym_stare_rangi_limit', '100'),
('cron_optym_stare_rangi_hour', '1'),
('cron_optym_stare_reklamy_hour', '1'),
('hlx_top50', '1'),
('hlx_top50_tag_tabela', 'TOP'),
('hlx_top50_tag_say', 'TOP'),
('hlx_top50_color_tag', 'lime'),
('hlx_top50_color_nick', 'lime'),
('hlx_top50_color_tekst', 'lime'),
('hlx_top_rangi', '1'),
('hlx_ilosc', '3'),
('AdmRaport_on', '1'),
('AdmRaport_start', '1'),
('AdmRaport_stop', '10'),
('AdmRaport_AdmM_Nagroda', '1'),
('AdmRaport_AdmM_Nagroda_flagi', '9'),
('AdmRaport_AdmM_Nagroda_czas', '10'),
('AdmRaport_AdmM_tag', '1'),
('AdmRaport_AdmM_tag_tabela', '- Admin -'),
('AdmRaport_AdmM_tag_say', 'Admin Miesiąca'),
('AdmRaport_AdmM_color_tag', 'red'),
('AdmRaport_AdmM_color_nick', 'red'),
('AdmRaport_AdmM_color_tekst', 'red'),
('AdmRaport_AdmM_ranga_czas', '10'),
('media_fb', ''),
('media_steam', ''),
('media_insta', ''),
('media_yt', ''),
('cron_optym_stare_wiadomosci_day', '10'),
('cron_optym_stare_wiadomosc_limit', '100'),
('GaleriaMap_wymiary_on', '1'),
('GaleriaMap_wymiary_wysokosc', '360'),
('GaleriaMap_wymiary_szerokosc', '640'),
('GaleriaMap_znak_on', '1'),
('GaleriaMap_znak_tekst', 'Admin Control Panel'),
('GaleriaMap_znak_tekst_wielkosc', '10'),
('GaleriaMap_znak_tekst_kolor', 'white'),
('GaleriaMap_api', 'TWÓJ API IMGUR'),
('dev_on', '0'),
('dev_modul', 'serwery_det'),
('sb_optymalize_time', '86400'),
('sb_optymalize_last', '2021-02-06 04:42:08'),
('hlx_optymalize_time', '86400'),
('hlx_optymalize_last', '2021-02-06 04:40:09'),
('cron_file_list_logi_time', '360'),
('sourceupdate_wymus', '0'),
('cron_file_list_logi', '2021-02-06 15:04:03'),
('danepub_menu_on', '1'),
('danepub_menu_list', '[{\"page\":\"Sourcebans\",\"link\":\"?x=pub_sourcebans\",\"blank\":\"\"},{\"page\":\"Admins List\",\"link\":\"?x=pub_admin_list\",\"blank\":\"\"},{\"page\":\"Galeria Map\",\"link\":\"?x=pub_galeria_map\",\"blank\":\"\"},{\"page\":\"Historia Top50 Hlstats\",\"link\":\"?x=pub_hlstats_top\",\"blank\":\"\"},{\"page\":\"Changelog\",\"link\":\"?x=pub_changelog\",\"blank\":\"\"},{\"page\":\"Lista Piosenek\",\"link\":\"?x=pub_roundsound\",\"blank\":\"\"}]');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `acp_system`
--
ALTER TABLE `acp_system`
  ADD PRIMARY KEY (`conf_name`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
