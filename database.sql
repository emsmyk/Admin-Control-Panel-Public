-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Czas generowania: 06 Lut 2021, 23:58
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
-- Struktura tabeli dla tabeli `acp_cache_api`
--

CREATE TABLE `acp_cache_api` (
  `get` varchar(255) NOT NULL,
  `dane` longtext NOT NULL,
  `data` datetime NOT NULL DEFAULT current_timestamp(),
  `modification_data` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `acp_konkurencja`
--

CREATE TABLE `acp_konkurencja` (
  `id` int(11) NOT NULL,
  `nazwa` varchar(255) NOT NULL,
  `color` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `url` text NOT NULL,
  `ilosc` int(11) NOT NULL DEFAULT 10,
  `dane_time` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `acp_konkurencja`
--

INSERT INTO `acp_konkurencja` (`id`, `nazwa`, `color`, `code`, `url`, `ilosc`, `dane_time`) VALUES
(1, 'MYGO', 'default', '123', 'https://mygo.pl/discover/1.xml/?member=23&key=30399e138720256b19ad07c16f7ae6c2', 25, '-10 minutes');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `acp_log`
--

CREATE TABLE `acp_log` (
  `id` int(11) NOT NULL,
  `page` varchar(150) NOT NULL,
  `user` int(11) NOT NULL,
  `tekst` text NOT NULL,
  `link` varchar(255) NOT NULL DEFAULT '#',
  `data` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `acp_log`
--

INSERT INTO `acp_log` (`id`, `page`, `user`, `tekst`, `link`, `data`) VALUES
(1, '?x=wpisy', 1, 'Dodano nowy wpis Pierwszy Wpis (ID: 1)', '?x=wpisy&xx=wpis&wpisid=1', '2021-02-06 15:41:37'),
(2, '?x=acp_users&password=1', 1, 'Wygenerowno nowe hasło dla użytkonika EMCE! (ID: 1)', '#', '2021-02-06 15:43:58');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `acp_log_serwery`
--

CREATE TABLE `acp_log_serwery` (
  `id` int(11) NOT NULL,
  `page` varchar(150) NOT NULL,
  `serwer_id` int(11) NOT NULL,
  `user` int(11) DEFAULT NULL,
  `object` text DEFAULT NULL,
  `tekst` text NOT NULL,
  `data` datetime NOT NULL DEFAULT current_timestamp(),
  `modification_data` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `acp_messages`
--

CREATE TABLE `acp_messages` (
  `m_id` int(11) NOT NULL,
  `m_from` int(11) NOT NULL,
  `m_to` int(11) NOT NULL,
  `m_type` int(11) NOT NULL,
  `m_date` datetime NOT NULL DEFAULT current_timestamp(),
  `m_date_modyfikacja` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `m_status` tinyint(4) NOT NULL DEFAULT 0,
  `m_czyja` int(11) NOT NULL,
  `m_tytul` text NOT NULL,
  `m_text` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `acp_moduly`
--

CREATE TABLE `acp_moduly` (
  `id` int(11) NOT NULL,
  `nazwa` varchar(255) NOT NULL,
  `ikona` varchar(255) NOT NULL,
  `nazwa_wys` text NOT NULL,
  `opis` text NOT NULL,
  `wlaczony` int(11) NOT NULL DEFAULT 0,
  `menu` int(11) NOT NULL DEFAULT 0,
  `menu_kategoria` int(11) NOT NULL DEFAULT 0,
  `data` datetime NOT NULL DEFAULT current_timestamp(),
  `modification_data` datetime DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `acp_moduly`
--

INSERT INTO `acp_moduly` (`id`, `nazwa`, `ikona`, `nazwa_wys`, `opis`, `wlaczony`, `menu`, `menu_kategoria`, `data`, `modification_data`) VALUES
(1, 'wpisy', 'fa fa-keyboard-o', 'Wpisy', 'Wewnętrzny komunikator do komunikowanie się wewnątrz systemu ACP', 0, 1, 0, '2020-03-29 19:08:11', NULL),
(2, 'serwery', 'fa fa-list', 'Serwery', 'Lista Serwerów', 0, 1, 1, '2020-03-29 19:08:11', NULL),
(10, 'account', '', 'Delale Konta', 'Szczegółowe informacje na temat użytkownika ACP', 0, 0, 0, '2020-03-29 19:08:11', NULL),
(11, 'serwery_det', 'fa fa-server', 'Detale Serwerów', 'Szczegółowe informacje, dla danego serwera.', 0, 0, 1, '2020-03-29 19:08:11', NULL),
(12, 'serwery_ust', 'fa fa-circle-o', 'Serwery Ustawienia', 'Ustawienia indywidualne dla serwerów dostępnych w systemie ACP, możliwość dodania, edycji oraz usunięcia serwera.', 0, 1, 1, '2020-03-29 19:08:11', NULL),
(18, 'acp_users', 'fa fa-user', 'ACP Użytkownicy', 'Edycja użytkowników ACP', 0, 1, 2, '2020-03-29 19:08:11', NULL),
(19, 'acp_grupy', 'fa  fa-users', 'ACP Grupy', 'Konfiguracja Grup ACP', 0, 1, 2, '2020-03-29 19:08:11', NULL),
(20, 'acp_moduly', 'fa fa-rocket', 'ACP Moduły', 'Konfiguracja Modułów ACP', 0, 1, 2, '2020-03-29 19:08:11', NULL),
(21, 'acp_ustawienia', 'fa fa-gear', 'ACP Ustawienia', 'Ustawienia systemu ACP', 0, 1, 2, '2020-03-29 19:08:11', NULL),
(23, 'serwery_konfiguracja', 'fa fa-circle-o', 'Serwery Konfiguracja', 'Konfigurowanie Rang, Reklam,  czy Listy Map', 0, 2, 1, '2020-03-29 19:08:11', '2020-11-26 08:49:35'),
(24, 'changelog_edit', 'fa fa-exchange', 'Changelog', 'Wszelkie zmiany na serwerach', 0, 1, 1, '2020-03-29 19:08:11', NULL),
(25, 'acp_logs', 'fa fa-file-text-o', 'ACP Logi', 'Logi ACP', 0, 1, 2, '2020-03-29 19:08:11', NULL),
(26, 'zadania', 'fa fa-flag-o', 'Zadania', 'Lista zadań, działań oraz projektów', 0, 2, 0, '2020-03-29 19:08:11', NULL),
(27, 'wgrywarka', 'fa fa-cloud-upload', 'Wgrywarka', 'Podgląd wgrywanych plików', 0, 1, 1, '2020-03-29 19:08:11', NULL),
(28, 'pluginy', 'fa fa-plug', 'Pluginy', 'Biblioteka Pluginów Sourcemod', 0, 1, 0, '2020-03-29 19:08:11', NULL),
(29, 'galeria_map', 'fa fa-map', 'Galeria Map', 'Lista obrazków map', 0, 1, 0, '2020-03-29 19:08:11', '2020-03-29 19:09:05'),
(30, 'forum', 'fa fa-forumbee', 'Forum', 'iframe forum w systemie ACP', 0, 1, 0, '2020-04-02 19:39:56', '2020-04-02 19:42:08'),
(31, 'konkurencja', 'fa fa-coffee', 'Konkurencja', '', 0, 1, 0, '2020-05-12 16:40:56', '2020-05-12 16:41:59'),
(32, 'roundsound', 'fa fa-music', 'RoundSound', 'Lista Utworów', 0, 2, 0, '2020-05-26 18:28:38', '2020-05-26 19:33:48'),
(33, 'raporty', 'fa fa-quote-right', 'Raporty', 'Raporty', 0, 2, 1, '2020-07-07 14:17:25', '2020-07-19 10:33:12'),
(34, 'uslugi', 'fa fa-shopping-cart', 'Usługi', 'Dodawanie różnych usług opart na flagach na serwerach', 0, 2, 0, '2020-08-24 17:55:02', '2020-08-24 17:55:32'),
(35, 'sourceupdate', '', 'Aktualizator SM&MM', 'Możliwość aktualizowania Sourcemod oraz Metamod', 0, 0, 0, '2020-09-16 19:55:20', '0000-00-00 00:00:00'),
(36, 'console', 'fa fa-terminal', 'Konsola Serwera', '', 0, 0, 0, '2020-10-08 16:15:32', '2020-12-12 21:03:20'),
(38, 'slots', 'fa fa-calculator', 'Kalkulator Slotów', 'Kalkulator ilości slotów', 0, 1, 0, '2020-11-17 14:10:55', '2020-11-17 14:11:42'),
(39, 'kokpit_serwerow', 'fa fa-desktop', 'Kokpit Serwerów', 'Kokpit Serwerów, jest miejscem gdzie znajdziemy podstawowe informacje na temat serwera', 0, 1, 1, '2020-12-12 17:57:40', '2020-12-12 18:24:46'),
(40, 'serwer_live_say', 'fa fa-text-height', 'Chat Serwera na Żywo', '', 0, 0, 0, '2020-12-12 20:10:08', '2020-12-12 21:03:36');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `acp_moduly_akcje`
--

CREATE TABLE `acp_moduly_akcje` (
  `id` int(11) NOT NULL,
  `modul_id` int(11) NOT NULL,
  `akcja` varchar(255) NOT NULL,
  `akcja_wys` varchar(255) NOT NULL,
  `opis` text NOT NULL,
  `data` datetime NOT NULL DEFAULT current_timestamp(),
  `modification_data` datetime DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `acp_moduly_akcje`
--

INSERT INTO `acp_moduly_akcje` (`id`, `modul_id`, `akcja`, `akcja_wys`, `opis`, `data`, `modification_data`) VALUES
(1, 1, 'WpisyUsun', 'Usuwanie Wpisów', 'Umożliwia usuwanie wpisów.', '2020-03-29 19:10:12', NULL),
(2, 1, 'WpisyZamknij', 'Zamykanie Wpisów', 'brak opisu', '2020-03-29 19:10:12', NULL),
(6, 12, 'SerwerUsun', 'Usuń Serwer', '', '2020-03-29 19:10:12', NULL),
(7, 12, 'SerwerDodaj', 'Dodaj Serwer', '', '2020-03-29 19:10:12', NULL),
(8, 12, 'SerwerEdytuj', 'Edytuj Serwer', '', '2020-03-29 19:10:12', NULL),
(9, 23, 'SerwerRangiUsun', '[Rangi] Usuń', 'Usuwanie rangi', '2020-03-29 19:10:12', NULL),
(10, 23, 'SerwerRangiEdytuj', '[Rangi] Edytuj', 'Edytowanie rangi', '2020-03-29 19:10:12', NULL),
(11, 23, 'SerwerRangiDodaj', '[Rangi] Dodaj', 'Dodawanie rangi', '2020-03-29 19:10:12', NULL),
(12, 23, 'SerwerReklamyUsun', '[Reklamy] Usuń', 'Usuwanie reklam', '2020-03-29 19:10:12', NULL),
(13, 23, 'SerwerReklamyEdytuj', '[Reklamy] Edytuj', 'Edytowanie reklam', '2020-03-29 19:10:12', NULL),
(14, 23, 'SerwerReklamyDodaj', '[Reklamy] Dodaj', 'Dodawanie reklam', '2020-03-29 19:10:12', NULL),
(15, 23, 'SerwerBazaUsun', '[Bazy Danych] Usuń', 'Usuwanie bazy danych', '2020-03-29 19:10:12', NULL),
(16, 23, 'SerwerBazaEdytuj', '[Bazy Danych] Edytuj', 'Edytowanie baz danych', '2020-03-29 19:10:12', NULL),
(17, 23, 'SerwerBazaDodaj', '[Bazy Danych] Dodaj', 'Dodawanie baz danych', '2020-03-29 19:10:12', NULL),
(21, 11, 'ustawienia_podstawowe', 'Ustawienia Podstawowe', 'Edycja Ustawień Podstawowych', '2020-03-29 19:10:12', NULL),
(22, 24, 'ChangelogUsun', 'Usuń wpis', 'Usuwanie wpisów changelog', '2020-03-29 19:10:12', NULL),
(23, 24, 'ChangelogEdytuj', 'Edytuj wpis', 'Edytowanie wpisów dodanych do changelogu', '2020-03-29 19:10:12', NULL),
(24, 23, 'SerwerWymusAktualizacje', '[Wszystkie] Wymuś aktualizację', 'Możliwość wymuszenia wcześniejszej aktualizacji plików', '2020-03-29 19:10:12', NULL),
(25, 11, 'serwery_det_CzytajPlik', 'Odczytaj Plik', 'Dostęp do odczytywania zawartości plików wgrywanych na serwer', '2020-03-29 19:10:12', NULL),
(26, 11, 'serwery_det_SerKonfiguracjaALL', 'Serwer Konfiguracja', 'Dostęp do podglądu rang, reklam, baz danych', '2020-03-29 19:10:12', NULL),
(27, 11, 'serwery_det_SerKonfiguracjaRangi', 'Serwer Konfiguracja Rangi', 'Dostęp do podglądu Rang', '2020-03-29 19:10:12', NULL),
(28, 11, 'serwery_det_SerKonfiguracjaReklamy', 'Serwer Konfiguracja Reklamy', 'Dostęp do podglądu Reklam', '2020-03-29 19:10:12', NULL),
(29, 11, 'serwery_det_SerKonfiguracjaBazy', 'Serwer Konfiguracja Bazy Danych', 'Dostęp do podglądu Baz Danych', '2020-03-29 19:10:12', NULL),
(30, 26, 'ZadaniaDodaj', 'Nowe Zadanie', 'Możliwość dodania nowego zadania', '2020-03-29 19:10:12', NULL),
(31, 26, 'ZadanieEdytuj', 'Edycja Zadania', 'Możliwość edycji utworzonego zadnia', '2020-03-29 19:10:12', NULL),
(32, 26, 'ZadanieUsun', 'Usuń Zadanie', 'Możliwość usunięcia zadania', '2020-03-29 19:10:12', NULL),
(33, 26, 'ZadanieAkcOdrz', 'Akceptuj/Odrzuć Zadanie', 'Możliwość akceptacji lub odrzucenia zadania', '2020-03-29 19:10:12', NULL),
(34, 26, 'ZadaniePrzyjmnij', 'Przyjmnij Zadanie', 'Możliwość przyjęcia zadania do realizacji', '2020-03-29 19:10:12', NULL),
(35, 26, 'ZadanieZakoncz', 'Zakończ Zadanie', 'Możliwość zakończenia zadania', '2020-03-29 19:10:12', NULL),
(36, 26, 'ZadanieAnuluj', 'Anuluj Zadanie', 'Możliwość anulowania zadania', '2020-03-29 19:10:12', NULL),
(37, 26, 'ZadanieKomentarze', 'Komentarze', 'Możliwość komentowania zadania', '2020-03-29 19:10:12', NULL),
(38, 26, 'ZadanieToDo', 'Lista To Do', 'Możliwość dodawania pozycji, usuwania oraz oznaczania jako wykonane', '2020-03-29 19:10:12', NULL),
(39, 26, 'ZadanieZapros', 'Uczestnicy Zadania', 'Możliwość dodawania osób które uczestniczą w zadaniu', '2020-03-29 19:10:12', NULL),
(40, 1, 'WpisyOgloszenie', 'Oznaczenie Ogłoszenia', 'Daje możliwość oznaczenia ogłoszenia', '2020-03-29 19:10:12', NULL),
(41, 12, 'SerwerCron', 'Edytuj Zdalne Prace', 'Możliwość tworzenia i edycji zdalnych prac', '2020-03-29 19:10:12', NULL),
(42, 23, 'SerwerMapyGrupaDodaj', '[Mapy] Dodaj Grupę Map', 'Możliwość dodani grupy map', '2020-03-29 19:10:12', NULL),
(43, 23, 'SerwerMapyGrupaEdytuj', '[Mapy] Edytuj Grupę Map', 'Możliwość edycji grupy map', '2020-03-29 19:10:12', NULL),
(44, 23, 'SerwerMapyZapisz', '[Mapy] Edytuj Grupę Mapę', 'Możliwość edycji nazwy map', '2020-03-29 19:10:12', NULL),
(45, 23, 'SerwerMapaDodaj', '[Mapy] Dodaj Mapę', 'Możliwość dodania nowej mapy', '2020-03-29 19:10:12', NULL),
(46, 23, 'SerwerMapaUsun', '[Mapy] Usuń Mapę', 'Możliwość usunięcia mapy', '2020-03-29 19:10:12', NULL),
(47, 23, 'SerwerMapaEdytuj', '[Mapy] Edytuj Mapę', 'Możliwość edycji nazwy mapy oraz jej parametrów', '2020-03-29 19:10:12', NULL),
(48, 23, 'SerwerMapaGaleria', '[Mapy] Galeria', 'Możliwość dodania obrazków mapy do galerii', '2020-03-29 19:10:12', NULL),
(49, 1, 'WpisyKategoria', 'Zmiana Kategorii Wpisu', 'Daje możliwość zmiany kategorii wpisu', '2020-03-29 19:10:12', NULL),
(50, 1, 'WpisyEdytujWpis', 'Edycja Wpisu', 'Daje możliwość edycji wpisu', '2020-03-29 19:10:12', NULL),
(51, 28, 'PluginyDodaj', 'Dodaj Plugin', 'Możliwość dodania pluginu', '2020-03-29 19:10:12', NULL),
(52, 28, 'PluginyEdytuj', 'Edytuj Plugin', 'Możliwość edycji pluginu', '2020-03-29 19:10:12', NULL),
(53, 28, 'PluginyUsun', 'Usuń Plugin', 'Możliwość usunięcia pluginu', '2020-03-29 19:10:12', NULL),
(54, 28, 'PluginyPlikDodaj', 'Dodaj Plik', 'Możliwość dodania plików do pluginu', '2020-03-29 19:10:12', NULL),
(55, 28, 'PluginyPlikEdytuj', 'Edytuj Plik', 'Możliwość edycji pliku', '2020-03-29 19:10:12', NULL),
(56, 28, 'PluginyPlikUsun', 'Usuń Plik', 'Możliwość skasowania pliku pluginu', '2020-03-29 19:10:12', NULL),
(57, 28, 'PluginyWgrywarka', 'Wgrywarka', 'Możliwość zlecenia wgrania pluginu na wybrany serwer', '2020-03-29 19:10:12', NULL),
(58, 23, 'SerwerMapyGrupaUsun', '[Mapy] Usuń Grupę Map', 'Kasowanie grupy map', '2020-03-29 19:10:12', NULL),
(59, 11, 'serwery_det_WgrajMape', 'Wgraj Mapę', 'Dostęp do zlecenia wgrania mapy z komputera', '2020-03-29 19:10:12', NULL),
(60, 29, 'GaleriaMapWgraj', 'Wgraj Obrazek', 'Możliwość wgrania, aktualizacji obrazka mapy', '2020-03-29 19:10:12', NULL),
(61, 23, 'SerwerHelpMenuDodaj', '[Help Menu] Dodaj', 'Możliwości dodania help menu dla serwera', '2020-04-21 22:07:18', '0000-00-00 00:00:00'),
(62, 23, 'SerwerHelpMenuEdytuj', '[Help Menu] Edytuj', 'Możliwość edycji help menu serwera', '2020-04-21 22:07:42', '0000-00-00 00:00:00'),
(63, 23, 'SerwerHelpMenuUsun', '[Help Menu] Usuń', 'Możliwość usunięcia help menu', '2020-04-21 22:08:11', '0000-00-00 00:00:00'),
(64, 23, 'SerwerHelpMenuKonfiguracja', '[Help Menu] Konfiguracja', 'Możliwość konfigurowania help menu, dodawania opisu vipa czy też listy komend', '2020-04-21 22:08:52', '0000-00-00 00:00:00'),
(65, 11, 'PraceCykliczneOdczytane', 'Prace Cykliczne - Błędy', 'Oznacz błędy jako odczytane', '2020-05-23 19:18:59', '0000-00-00 00:00:00'),
(66, 31, 'KonkurencjaEdytuj', 'Edytuj', 'Możliwość edycji dodanych stron', '2020-06-21 21:13:49', '0000-00-00 00:00:00'),
(67, 31, 'KonkurencjaDodaj', 'Dodaj', 'Możliwość dodawania stron', '2020-06-21 21:14:02', '0000-00-00 00:00:00'),
(68, 31, 'KonkurencjaUsun', 'Usuń', 'Możliwość kasowania stron', '2020-06-21 21:14:14', '0000-00-00 00:00:00'),
(69, 31, 'KonkurencjaCache', 'Cache', 'Możliwość usunięcia całej zawartości cache', '2020-06-21 21:14:33', '0000-00-00 00:00:00'),
(70, 32, 'RsListaDodaj', 'Nowa Lista', 'Możliwość dodania nowej listy utworów', '2020-06-29 16:33:02', '0000-00-00 00:00:00'),
(71, 32, 'RsListaEdycja', 'Edycja Listę', 'Możliwość edycji listy utworów', '2020-06-29 16:33:22', '2020-06-29 16:33:51'),
(72, 32, 'RsListaUsun', 'Usuń Listę', 'Możliwość skasowania listy utworów', '2020-06-29 16:33:46', '0000-00-00 00:00:00'),
(73, 32, 'RsListaDodajPiosenke', 'Piosenka Lista Dodaj', 'Możliwość dodania utworu na listę', '2020-06-29 16:34:21', '2020-06-29 16:34:55'),
(74, 32, 'RsListaUsunPiosenke', 'Piosenka Lista Usuń', 'Możliwość skasowania utworu z listy', '2020-06-29 16:34:47', '0000-00-00 00:00:00'),
(75, 32, 'RsPiosenkaDodaj', 'Dodaj Piosenkę', 'Możliwość dodania nowej piosenki', '2020-06-29 16:35:24', '0000-00-00 00:00:00'),
(76, 32, 'RsPiosenkaEdytuj', 'Edytuj Piosenkę', 'Możliwość edycji piosenki', '2020-06-29 16:35:55', '0000-00-00 00:00:00'),
(77, 32, 'RsPiosenkaUsun', 'Usuń Piosenkę', 'Możliwość skasowania piosenki', '2020-06-29 16:36:19', '0000-00-00 00:00:00'),
(78, 32, 'RsPiosenkaAkcept', 'Akceptuj Piosenkę', 'Możliwość akceptacji nowo dodanych piosenek', '2020-06-29 16:36:54', '0000-00-00 00:00:00'),
(79, 32, 'RsPiosenkaMp3', 'Mp3', 'Możliwość dodania, wgrania pliku mp3', '2020-06-29 16:37:13', '0000-00-00 00:00:00'),
(80, 32, 'RsUstPodstawowe', 'Ustawienia Podstawowe', 'Możliwość edycji ustawień podstawowych modułu', '2020-06-29 16:38:09', '0000-00-00 00:00:00'),
(81, 32, 'RsUstSerwery', 'Ustawienia Serwery', 'Możliwość  zmiany On/Off listy utworów dla danego serwera', '2020-06-29 16:38:49', '0000-00-00 00:00:00'),
(82, 11, 'serwery_det_RaportOpiekuna', 'Raport Opiekuna', 'Możliwość składania raportu Opiekuna', '2020-08-05 20:49:39', '0000-00-00 00:00:00'),
(83, 27, 'WgrywarkaDownloadFile', 'Pobieranie Pików', 'Możliwość pobrania plików, które zostały wgrane na serwer', '2020-08-22 19:31:13', '0000-00-00 00:00:00'),
(85, 34, 'UslugiUstawienia', 'Dostęp do zakładki - Kody promocyjne', 'Grupa posiadająca to uprawnienie będzie miała dostęp do zakładki', '2020-08-24 18:01:22', '0000-00-00 00:00:00'),
(86, 32, 'RsUstawStatus', 'Wybierz Roundsound', 'Możliwość ustawienia aktualnie granej lub w przygotowaniu listy utworów', '2020-08-27 21:25:40', '0000-00-00 00:00:00'),
(87, 32, 'RsPiosenkaDodajDoListy', 'Dodaj do listy', 'Możliwość dodania utworu do listy z poziomu edycji piosenki', '2020-08-27 21:26:11', '0000-00-00 00:00:00'),
(88, 35, 'SourceUpdate', 'SourceUpdate', 'Dostep do wykonania kodu oraz otrzymania powiadomienia o nowej wersji SM&MM', '2020-09-16 19:56:11', '0000-00-00 00:00:00'),
(89, 26, 'ZadanieLink', 'Link Publiczny', 'Możliwość generowania linku dla osób bez konta w systemie ACP', '2020-10-08 19:22:57', '0000-00-00 00:00:00'),
(90, 34, 'UslugiDodaj', 'Dostęp do zakładki - Dodaj usługę', 'Grupa posiadająca to uprawnienia będzie miała możliwość dodać nową usługę', '2020-10-18 19:10:42', '0000-00-00 00:00:00'),
(91, 11, 'serwery_det_logi', 'Logi Serwera', 'Możliwość odczytywania logów serwera, sourcemod', '2020-10-21 17:00:58', '0000-00-00 00:00:00'),
(92, 11, 'serwery_det_SB_adm_dodaj', 'Lista Adminów: Dodaj', 'Dodawanie adminów', '2020-11-24 22:02:22', '0000-00-00 00:00:00'),
(93, 11, 'serwery_det_SB_adm_edytuj', 'Lista Adminów: Edytuj', 'Edytowanie adminów', '2020-11-24 22:02:38', '0000-00-00 00:00:00'),
(94, 11, 'serwery_det_SB_adm_usun', 'Lista Adminów: Usuń', 'Kasowanie admina', '2020-11-24 22:02:55', '0000-00-00 00:00:00'),
(95, 11, 'serwery_det_SB_adm_degra_rezy', 'Lista Adminów: Degradacja / Rezygnacja', 'Oznaczanie statusu admina, który zrezygnował z funkcji', '2020-11-24 22:03:38', '0000-00-00 00:00:00'),
(96, 34, 'UslugiListaEdytuj', 'Lista Usług: Edycja', 'Możliwość edycji usługi', '2020-11-25 10:07:44', '0000-00-00 00:00:00'),
(97, 34, 'UslugiListaUsun', 'Lista Usług: Usuń', 'Możliwość kasowania usługi', '2020-11-25 10:08:11', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `acp_moduly_menu`
--

CREATE TABLE `acp_moduly_menu` (
  `id` int(11) NOT NULL,
  `modul_id` int(11) NOT NULL,
  `ikona` varchar(50) NOT NULL DEFAULT 'fa fa-circle-o',
  `nazwa` varchar(50) NOT NULL,
  `link` varchar(100) NOT NULL DEFAULT '#',
  `kolejnosc` int(11) NOT NULL DEFAULT 0,
  `data` datetime NOT NULL DEFAULT current_timestamp(),
  `modification_data` datetime DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `acp_moduly_menu`
--

INSERT INTO `acp_moduly_menu` (`id`, `modul_id`, `ikona`, `nazwa`, `link`, `kolejnosc`, `data`, `modification_data`) VALUES
(3, 23, 'fa fa-circle-o', 'Rangi', 'serwery_konfiguracja&xx=hextags', 0, '2020-03-29 19:11:11', '2020-06-26 20:39:16'),
(4, 23, 'fa fa-circle-o', 'Reklamy', 'serwery_konfiguracja&xx=reklamy', 0, '2020-03-29 19:11:11', NULL),
(5, 23, 'fa fa-circle-o', 'Bazy Danych', 'serwery_konfiguracja&xx=baza', 0, '2020-03-29 19:11:11', NULL),
(7, 23, 'fa fa-circle-o', 'Mapy', 'serwery_konfiguracja&xx=mapy', 0, '2020-03-29 19:11:11', NULL),
(9, 26, 'fa fa-circle-o', 'Moje Zadania', 'zadania&xx=moje', 0, '2020-03-29 19:11:11', NULL),
(10, 26, 'fa fa-circle-o', 'Lista Zadań', 'zadania&xx=lista', 0, '2020-03-29 19:11:11', NULL),
(11, 23, 'fa fa-circle-o', 'Help Menu', 'serwery_konfiguracja&xx=help_menu', 0, '2020-04-14 17:04:55', '0000-00-00 00:00:00'),
(12, 32, 'fa fa-circle-o', 'RoundSound', 'roundsound&xx=lista', 0, '2020-05-26 18:29:48', '2020-05-26 18:35:02'),
(13, 32, 'fa fa-circle-o', 'Piosenki', 'roundsound&xx=piosenki', 0, '2020-05-26 18:30:37', '0000-00-00 00:00:00'),
(14, 32, 'fa fa-circle-o', 'Głosy', 'roundsound&xx=vote', 0, '2020-05-26 18:30:52', '0000-00-00 00:00:00'),
(15, 32, 'fa fa-gear', 'Ustawienia', 'roundsound&xx=ustawienia', 0, '2020-05-26 18:31:16', '2020-08-04 11:04:16'),
(17, 33, 'fa fa-list', 'Raporty Miesięczne', 'raporty&xx=raport_miesieczny', 0, '2020-07-07 14:21:09', '2020-12-12 17:16:58'),
(19, 26, 'fa fa-line-chart', 'Statystyki', 'zadania&xx=statystyki', 0, '2020-08-03 18:45:48', '2020-09-11 21:15:13'),
(20, 34, 'fa fa-list', 'Moje Usługi', 'uslugi&xx=moje_uslugi', 0, '2020-08-24 17:56:39', '2020-08-24 17:57:18'),
(22, 34, 'fa fa-gear', 'Ustawienia', 'uslugi&xx=ustawienia', 0, '2020-08-24 17:58:51', '0000-00-00 00:00:00'),
(23, 26, 'fa fa-plus', 'Dodaj Zadanie', 'zadania&xx=dodaj', 0, '2020-09-11 21:14:56', '0000-00-00 00:00:00'),
(24, 34, 'fa fa-plus', 'Dodaj Usługę', 'uslugi&xx=dodaj_usluge', 0, '2020-10-18 19:10:04', '2020-10-18 19:11:01'),
(25, 34, 'fa fa-list', 'Wszystkie Usługi', 'uslugi&xx=uslugi', 0, '2020-11-24 22:09:13', '0000-00-00 00:00:00'),
(26, 23, 'fa fa-circle-o', 'Tagi', 'serwery_konfiguracja&xx=tagi', 0, '2020-11-26 08:50:08', '0000-00-00 00:00:00'),
(27, 33, 'fa fa-server', 'Raporty Serwery', 'raporty&xx=raport_serwer', 0, '2020-12-12 17:17:13', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `acp_pluginy`
--

CREATE TABLE `acp_pluginy` (
  `id` int(11) NOT NULL,
  `nazwa` varchar(255) NOT NULL,
  `opis` text NOT NULL,
  `cvary` text NOT NULL,
  `notatki` text NOT NULL,
  `data_dodania` datetime NOT NULL DEFAULT current_timestamp(),
  `modification_data` datetime DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  `u_id` int(11) NOT NULL,
  `lic_name` int(11) DEFAULT NULL,
  `lic_hash` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `acp_pluginy`
--

INSERT INTO `acp_pluginy` (`id`, `nazwa`, `opis`, `cvary`, `notatki`, `data_dodania`, `modification_data`, `u_id`, `lic_name`, `lic_hash`) VALUES
(1, 'Reklamy', 'brak', 'sm_reklama_reload -  Przeładowanie pliku z reklamami', 'brak', '2020-11-20 13:59:33', '2020-11-24 19:51:00', 1, 0, 0),
(2, 'Usuń Wiadomość o Kasie', 'Plugin kasuje informacje z say o otrzymanej gotówce za zabójstwo, podłożenie bomby itp', '', '', '2020-04-05 16:15:29', '0000-00-00 00:00:00', 1, NULL, NULL),
(3, 'Server Clean Up (1.2.2)', 'Plugin kasuje pliki logów starsze od X dni/godz.', '// Clean up automatically recorded demo files.<br />\r\n// -<br />\r\n// Default: \"0\"<br />\r\n// Minimum: \"0.000000\"<br />\r\n// Maximum: \"1.000000\"<br />\r\nsm_srvcln_demos \"0\"<br />\r\n<br />\r\n// Include archives when deleting old demos (if your server automatically compresses them, detects: bz2, zip, rar and 7z).<br />\r\n// -<br />\r\n// Default: \"0\"<br />\r\n// Minimum: \"0.000000\"<br />\r\n// Maximum: \"1.000000\"<br />\r\nsm_srvcln_demos_archives \"0\"<br />\r\n<br />\r\n// Optional directory path to your demo files if you are manually saving them elsewhere.<br />\r\n// -<br />\r\n// Default: \"./demos\"<br />\r\nsm_srvcln_demos_path \"./demos\"<br />\r\n<br />\r\n// Time (in hours) to keep demo files (default is one week, minimum value is 12 hours, set to -1 to keep current day only).<br />\r\n// -<br />\r\n// Default: \"168\"<br />\r\n// Minimum: \"-1.000000\"<br />\r\nsm_srvcln_demos_time \"168\"<br />\r\n<br />\r\n// Enable automatic server clean up (1 - enable, 0 - disable, manual clean up command only).<br />\r\n// -<br />\r\n// Default: \"1\"<br />\r\n// Minimum: \"0.000000\"<br />\r\n// Maximum: \"1.000000\"<br />\r\nsm_srvcln_enable \"1\"<br />\r\n<br />\r\n// Log what Server Clean Up deletes.<br />\r\n// -<br />\r\n// Default: \"0\"<br />\r\n// Minimum: \"0.000000\"<br />\r\n// Maximum: \"1.000000\"<br />\r\nsm_srvcln_logging_mode \"0\"<br />\r\n<br />\r\n// Clean up regular server logs.<br />\r\n// -<br />\r\n// Default: \"0\"<br />\r\n// Minimum: \"0.000000\"<br />\r\n// Maximum: \"1.000000\"<br />\r\nsm_srvcln_logs \"1\"<br />\r\n<br />\r\n// Time (in hours) to keep regular server logs (default is one week, minimum value is 12 hours, set to -1 to keep current day only).<br />\r\n// -<br />\r\n// Default: \"168\"<br />\r\n// Minimum: \"-1.000000\"<br />\r\nsm_srvcln_logs_time \"168\"<br />\r\n<br />\r\n// Clean up generated replay files.<br />\r\n// -<br />\r\n// Default: \"0\"<br />\r\n// Minimum: \"0.000000\"<br />\r\n// Maximum: \"1.000000\"<br />\r\nsm_srvcln_replays \"0\"<br />\r\n<br />\r\n// Include archives when deleting old replays (if your server automatically compresses them, detects: bz2, zip, rar and 7z).<br />\r\n// -<br />\r\n// Default: \"0\"<br />\r\n// Minimum: \"0.000000\"<br />\r\n// Maximum: \"1.000000\"<br />\r\nsm_srvcln_replays_archives \"0\"<br />\r\n<br />\r\n// Time (in hours) to keep generated replay files (default is one week, minimum value is 12 hours, set to -1 to keep current day only).<br />\r\n// -<br />\r\n// Default: \"168\"<br />\r\n// Minimum: \"-1.000000\"<br />\r\nsm_srvcln_replays_time \"168\"<br />\r\n<br />\r\n// Clean up CS:GO round backup files (note: only if none of the naming options have been changed from default settings for round backups).<br />\r\n// -<br />\r\n// Default: \"0\"<br />\r\n// Minimum: \"0.000000\"<br />\r\n// Maximum: \"1.000000\"<br />\r\nsm_srvcln_roundbackups \"0\"<br />\r\n<br />\r\n// Time (in hours) to keep round backup\'s in CS:GO (default is one week, minimum value is 12 hours, set to -1 to keep current day only).<br />\r\n// -<br />\r\n// Default: \"168\"<br />\r\n// Minimum: \"-1.000000\"<br />\r\nsm_srvcln_roundbackups_time \"168\"<br />\r\n<br />\r\n// Clean up SourceMod server logs.<br />\r\n// -<br />\r\n// Default: \"1\"<br />\r\n// Minimum: \"0.000000\"<br />\r\n// Maximum: \"1.000000\"<br />\r\nsm_srvcln_smlogs \"1\"<br />\r\n<br />\r\n// Time (in hours) to keep SourceMod server logs (default is one week, minimum value is 12 hours, set to -1 to keep current day only).<br />\r\n// -<br />\r\n// Default: \"168\"<br />\r\n// Minimum: \"-1.000000\"<br />\r\nsm_srvcln_smlogs_time \"168\"<br />\r\n<br />\r\n// What type of logs to delete from the SM logs folder (0 - just normal logs, 1 - normal logs and error logs, 2 - all logs).<br />\r\n// -<br />\r\n// Default: \"2\"<br />\r\n// Minimum: \"0.000000\"<br />\r\n// Maximum: \"2.000000\"<br />\r\nsm_srvcln_smlogs_type \"2\"<br />\r\n<br />\r\n// Clean up uploaded sprays.<br />\r\n// -<br />\r\n// Default: \"0\"<br />\r\n// Minimum: \"0.000000\"<br />\r\n// Maximum: \"1.000000\"<br />\r\nsm_srvcln_sprays \"0\"<br />\r\n<br />\r\n// Time (in hours) to keep uploaded sprays (default is one week, minimum value is 12 hours, set to -1 to keep current day only).<br />\r\n// -<br />\r\n// Default: \"168\"<br />\r\n// Minimum: \"-1.000000\"<br />\r\nsm_srvcln_sprays_time \"168\"', '', '2020-04-05 16:22:21', '0000-00-00 00:00:00', 1, NULL, NULL),
(4, 'MenuSerwera', '', '', '', '2020-04-23 18:19:59', '0000-00-00 00:00:00', 3, NULL, NULL),
(5, 'RoundSound - AbnerRound End v4.0.1', 'brak', 'brak', 'brak', '2020-06-15 21:35:42', '2020-06-21 21:33:22', 1, 0, 0),
(6, 'HexTags 2.02', 'Plugin wymaga Chat-Processor<br />\r\nhttps://forums.alliedmods.net/showthread.php?p=2448733', '', '', '2020-06-22 21:57:32', '0000-00-00 00:00:00', 1, NULL, NULL),
(7, 'Chat-Processor 2.2.3', 'Plugin wymagany do rang HexTags', '', '', '2020-06-22 22:24:30', '0000-00-00 00:00:00', 1, NULL, NULL),
(8, 'SteamWorks', '', '', '', '2020-06-27 02:41:02', '0000-00-00 00:00:00', 3, NULL, NULL),
(9, 'Ukryj Admina', 'Plugin umożliwia ukrycie admina na serwerze', '', 'sm_ukryj <br />\r\n/ukryj<br />\r\n!ukryj<br />\r\n<br />\r\nFlaga wymagana to B', '2020-07-07 16:08:01', '0000-00-00 00:00:00', 1, NULL, NULL),
(10, 'Rozszerzone info o zabójstwie 1.0.0', 'Plugin rozszerza standardowe informacje o zabiciu innej osoby w prawym górnym rogu ekranu.', '', 'Należy skomplikować plugin i wgrać do acp dodaję jedynie sp.', '2020-07-07 16:14:34', '0000-00-00 00:00:00', 1, NULL, NULL),
(11, 'Block sm and meta', 'Plugin uniemożliwia wpisywanie przez zwykłego gracza komend \"sm plugins\" oraz \"sm meta\", które odsłaniają poniekąd \"wrażliwe\" dane techniczne serwera. Dla osoby z flagą ADMFLAG_ROOT wpisanie tych komend nadal jest możliwe. Dodatkowo plugin loguje próby wpisania komend do pliku: logs/sbp-dzisiejsza_data.log', '', 'Plugin Wymaga:<br />\r\nDynamic Hooks<br />\r\nLUB<br />\r\nP Tools and Hooks', '2020-07-07 16:19:47', '0000-00-00 00:00:00', 1, NULL, NULL),
(12, 'RoundSound - Test Piosenek', 'Możliwość testowania piosenek dodanych do roundsounda', '', 'Komenda: sm_testmusic', '2020-11-20 13:39:23', '0000-00-00 00:00:00', 1, NULL, NULL),
(13, 'Nazwa Drużyny', 'Plugin zmienia nazwę drużyny', 'brak', 'sm_teamname_t<br />\r\nsm_teamname_ct', '2020-11-20 14:08:34', '2020-11-20 14:09:39', 1, 0, 0),
(14, 'Darmowy Vip - Losowanie', 'Możliwość losowania wśród graczy darmowego vipa', '', '', '2020-11-20 14:14:14', '0000-00-00 00:00:00', 1, NULL, NULL),
(15, 'GOTV fix', 'Fix poprawiający nagrywanie demek na gotv', '', '', '2020-11-20 14:15:04', '0000-00-00 00:00:00', 1, NULL, NULL),
(16, 'Czarna Lista', 'Plugin oparty o \'bad name kick\' polegający na kickowaniu graczy posiadających daną frazę w nicku', 'sm_bnkb_bantime - (0 = Perm, -1 = Kick)\\<br />\r\nsm_bnkb_reason - Powód', '', '2020-11-24 18:26:15', '0000-00-00 00:00:00', 1, NULL, NULL),
(17, 'Weapons - Skiny Broni', 'Plugin dodający możliwość zmiany skin broni na ten płaty ze sklepu', '', 'Wymaga  PTaH 1.1.0+', '2020-11-24 20:13:45', '0000-00-00 00:00:00', 1, NULL, NULL),
(18, 'Gloves - Rękawice', 'Plugin dający możliwość zmiany łapek na płatne', '', '', '2020-11-24 20:27:52', '0000-00-00 00:00:00', 1, NULL, NULL),
(19, 'Ukryj Zmiany Tagów', 'Plugin ukrywa wykonywane przez rcon zmiany tagów', '', '', '2020-11-27 13:05:57', '0000-00-00 00:00:00', 1, NULL, NULL),
(20, 'Toggle Music & Volume Control & Weapons Volume', 'Plugin do zarządzania głośnością mapy, głośności wystrzałów', 'brak', '', '2020-12-02 15:59:08', '2020-12-02 18:49:27', 1, 0, 0);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `acp_pluginy_pliki`
--

CREATE TABLE `acp_pluginy_pliki` (
  `id` int(11) NOT NULL,
  `plugin_id` int(11) NOT NULL,
  `ftp_directory` varchar(255) NOT NULL,
  `ftp_source_file_name` varchar(255) NOT NULL,
  `ftp_dest_file_name` varchar(255) NOT NULL,
  `kod_zrodlowy` int(11) DEFAULT NULL,
  `starsza_wersja` int(11) DEFAULT NULL,
  `data` datetime NOT NULL DEFAULT current_timestamp(),
  `modification_data` datetime DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `acp_pluginy_pliki`
--

INSERT INTO `acp_pluginy_pliki` (`id`, `plugin_id`, `ftp_directory`, `ftp_source_file_name`, `ftp_dest_file_name`, `kod_zrodlowy`, `starsza_wersja`, `data`, `modification_data`) VALUES
(2, 2, '/addons/sourcemod/plugins', 'www/server_plugins/2/ZcWurqCLR1AOU7jMdT9QavFIb.smx', 'usun_wiadomosci_o_kasie.smx', NULL, 1, '2020-04-05 16:16:13', '2020-11-20 19:55:50'),
(4, 3, '/addons/sourcemod/translations', 'www/server_plugins/3/6q9KG1DylYofN0CgJZksB284t.txt', 'servercleanup.phrases.txt', NULL, NULL, '2020-04-05 16:35:41', '2020-04-05 19:17:53'),
(5, 3, '/addons/sourcemod/plugins', 'www/server_plugins/3/QpubjU5LzvkN64hZW7FTwGHIy.smx', 'servercleanup.smx', NULL, NULL, '2020-04-05 16:35:57', '2020-04-05 19:17:28'),
(6, 3, '/addons/sourcemod/scripting', 'www/server_plugins/3/2ZFODBsRG6tpvbh3KWnLUm8lM.sp', 'servercleanup.sp', NULL, NULL, '2020-04-05 16:36:56', '2020-04-05 19:17:43'),
(7, 3, '/cfg/sourcemod', 'www/server_plugins/3/JRA4V3l7DKycrfnoC8jBObExk.cfg', 'plugin.servercleanup.cfg', NULL, NULL, '2020-04-05 16:37:56', '2020-04-05 19:17:36'),
(9, 5, '/addons/sourcemod/plugins', 'www/server_plugins/5/0zSN613sFYt5JnTV4QLibCaxv.smx', 'abner_res.smx', NULL, NULL, '2020-06-15 21:36:25', '0000-00-00 00:00:00'),
(10, 5, '/addons/sourcemod/translations', 'www/server_plugins/5/PHsazqyoQ3NBVeYCf7nGdE9Xg.txt', 'abner_res.phrases.txt', NULL, NULL, '2020-06-15 21:37:29', '0000-00-00 00:00:00'),
(13, 6, '/addons/sourcemod/scripting/include', 'www/server_plugins/6/ygP9N2xpJfH75RuzWcUrvCahm.inc', 'hextags.inc', 1, NULL, '2020-06-22 21:58:46', '2020-11-24 19:50:20'),
(14, 7, '/addons/sourcemod/configs', 'www/server_plugins/7/fm47dEHoLNOlCqGTsxS3ngYUX.cfg', 'chat_processor.cfg', NULL, NULL, '2020-06-22 22:25:02', '0000-00-00 00:00:00'),
(15, 7, '/addons/sourcemod/scripting', 'www/server_plugins/7/rFkzG2QJLxCtj5KRv7smeoU8d.sp', 'chat-processor.sp', NULL, NULL, '2020-06-22 22:25:34', '0000-00-00 00:00:00'),
(16, 7, '/addons/sourcemod/scripting/include', 'www/server_plugins/7/L1NYIkS2lHARZGK6yE8C49UQX.inc', 'chat-processor.inc', NULL, NULL, '2020-06-22 22:25:52', '0000-00-00 00:00:00'),
(17, 7, '/addons/sourcemod/scripting/include', 'www/server_plugins/7/LHbJVIhM3e67C4xUWYEvkciZn.inc', 'colorvariables.inc', NULL, NULL, '2020-06-22 22:25:59', '0000-00-00 00:00:00'),
(18, 7, '/addons/sourcemod/plugins', 'www/server_plugins/7/0xwn9Pq1IzeghmLW8jXJFpSCY.smx', 'chat-processor.smx', NULL, NULL, '2020-06-23 19:29:03', '0000-00-00 00:00:00'),
(19, 4, '/addons/sourcemod/plugins', 'www/server_plugins/4/gh1UyQOJPsLkWf4V5d6nBtAp9.smx', 'server_menu.smx', NULL, 1, '2020-06-24 00:02:09', '2020-11-24 18:37:47'),
(20, 8, '/addons/sourcemod/extensions', 'www/server_plugins/8/NSab9gnfMXjRKQAv2iW37pl5u.so', 'SteamWorks.ext.so', NULL, NULL, '2020-06-27 02:41:49', '0000-00-00 00:00:00'),
(21, 8, '/addons/sourcemod/scripting/include', 'www/server_plugins/8/NP4WCV7LaJuhd23KmEj9AvDIs.inc', 'SteamWorks.inc', NULL, NULL, '2020-06-27 02:42:21', '0000-00-00 00:00:00'),
(24, 6, '/addons/sourcemod/plugins', 'www/server_plugins/6/cUMN9y4rPaGuojflJQYbK5Vsq.smx', 'hextags.smx', NULL, NULL, '2020-06-27 16:02:53', '0000-00-00 00:00:00'),
(25, 6, '/addons/sourcemod/scripting', 'www/server_plugins/6/HLapAmNrCJkgUIvKul12RWEny.sp', 'hextags.sp', 1, NULL, '2020-06-27 16:03:12', '2020-11-24 19:50:15'),
(26, 9, '/addons/sourcemod/plugins', 'www/server_plugins/9/4hSgRkDuTyoe8lU61NtJqvfnz.smx', 'ukryj.smx', NULL, NULL, '2020-07-07 16:08:19', '0000-00-00 00:00:00'),
(27, 9, '/addons/sourcemod/scripting', 'www/server_plugins/9/6zlQfNousv7Y4P91j0Ix38gAt.sp', 'ukryj.sp', NULL, NULL, '2020-07-07 16:08:36', '0000-00-00 00:00:00'),
(28, 10, '/addons/sourcemod/plugins', 'www/server_plugins/10/Qprx4hYLveRjqcD1mZalVwJGs.smx', 'ExtendedPlayerDeathInfo.smx', NULL, NULL, '2020-07-07 16:15:26', '0000-00-00 00:00:00'),
(29, 10, '/addons/sourcemod/scripting', 'www/server_plugins/10/lc8VPs4fI6yZUAWxQRetmig1j.sp', 'ExtendedPlayerDeathInfo.sp', 1, NULL, '2020-07-07 16:15:39', '2020-12-02 16:14:31'),
(30, 11, '/addons/sourcemod/plugins', 'www/server_plugins/11/cKova4WfEgJhH1uj7UMYTDXL8.smx', 'sbp.smx', NULL, NULL, '2020-07-07 16:22:45', '0000-00-00 00:00:00'),
(31, 11, '/addons/sourcemod/scripting', 'www/server_plugins/11/ehdGSMRPA7xLv9UitWOFluy0N.sp', 'sbp.sp', NULL, NULL, '2020-07-07 16:23:16', '0000-00-00 00:00:00'),
(32, 11, '/addons/sourcemod/scripting/include', 'www/server_plugins/11/F8rgjmJQt0w2S5TUnZqoL6M1u.inc', 'PTaH.inc', NULL, NULL, '2020-07-07 16:23:37', '0000-00-00 00:00:00'),
(33, 11, '/addons/sourcemod/translations', 'www/server_plugins/11/R93jvwWU8z7JklHSoxdTsMOVe.txt', 'sbp.phrases.txt', NULL, NULL, '2020-07-07 16:24:47', '0000-00-00 00:00:00'),
(34, 5, '/cfg/sourcemod', 'www/server_plugins/5/FWTBLUDsoMEnhulNjYc8qC501.cfg', 'abner_res.cfg', NULL, NULL, '2020-08-05 18:30:21', '0000-00-00 00:00:00'),
(35, 12, '/addons/sourcemod/plugins', 'www/server_plugins/12/mNfKzpoTLYBtdPqiFZ1hw2UIl.smx', 'acp_roundsound_test_music.smx', NULL, NULL, '2020-11-20 13:39:48', '0000-00-00 00:00:00'),
(36, 12, '/addons/sourcemod/scripting', 'www/server_plugins/12/pn2ojCVxIbB9ZzrdM31NUvFDX.sp', 'acp_roundsound_test_music.sp', 1, NULL, '2020-11-20 13:40:03', '2020-11-20 20:03:29'),
(37, 1, '/addons/sourcemod/plugins', 'www/server_plugins/1/eo496X0xt2jLhdYRwurCyZQGO.smx', 'reklama.smx', NULL, 1, '2020-11-20 13:59:55', '2020-11-20 19:52:57'),
(40, 14, '/addons/sourcemod/plugins', 'www/server_plugins/14/Jt3AlHuY7gByZPCaNKnGe8s1E.smx', 'darmowy_vip.smx', NULL, NULL, '2020-11-20 14:14:28', '0000-00-00 00:00:00'),
(41, 15, '/addons/sourcemod/plugins', 'www/server_plugins/15/8Jw7hnM4NQCq5KsFbzAuUmprR.smx', 'gotv_map_fix.smx', NULL, 1, '2020-11-20 14:15:23', '2020-11-20 20:10:19'),
(42, 1, '/addons/sourcemod/scripting', 'www/server_plugins/1/JiYqC1V5FEAhecrKRonHMILfG.sp', 'reklama.sp', 1, 1, '2020-11-20 19:53:14', '2020-11-24 18:33:07'),
(44, 2, '/addons/sourcemod/plugins', 'www/server_plugins/2/RsozwCjAk0HOEDUauixbpnLIT.smx', 'usun_wiadomosci_o_kasie.smx', NULL, NULL, '2020-11-20 19:56:44', '0000-00-00 00:00:00'),
(45, 2, '/addons/sourcemod/scripting', 'www/server_plugins/2/g2TtuLevaw8hBoIj4lXQAMRxW.sp', 'usun_wiadomosci_o_kasie.sp', 1, NULL, '2020-11-20 19:57:08', '2020-11-20 19:57:12'),
(46, 13, 'addons/sourcemod/scripting', 'www/server_plugins/13/AZnyO0fSXhKt5WEGYLjxMC12T.sp', 'TeamName.sp', 1, NULL, '2020-11-20 20:07:23', '2020-11-20 20:07:26'),
(47, 13, '/addons/sourcemod/plugins', 'www/server_plugins/13/ZAl9TEHGs2io5kJP7qO1xQrY6.smx', 'TeamName.smx', NULL, NULL, '2020-11-20 20:07:35', '0000-00-00 00:00:00'),
(48, 15, '/addons/sourcemod/plugins', 'www/server_plugins/15/xbIA4B8tTYel3yc2wpzEZP7Ri.smx', 'gotv_map_fix.smx', NULL, NULL, '2020-11-20 20:10:30', '0000-00-00 00:00:00'),
(49, 15, '/addons/sourcemod/scripting', 'www/server_plugins/15/jX3K9rpClOQRkbmNDLVf6SABH.sp', 'gotv_map_fix.sp', 1, NULL, '2020-11-20 20:10:46', '2020-11-20 20:10:51'),
(53, 1, '/addons/sourcemod/plugins', 'www/server_plugins/1/2eXkmdjP1JV9bpqLyf0KnwcF5.smx', 'acp_reklama.smx', NULL, NULL, '2020-11-24 18:32:59', '2021-01-22 20:30:38'),
(54, 1, '/addons/sourcemod/scripting', 'www/server_plugins/1/jHgE2UOdm5lXx9NGa46LWvteS.sp', 'acp_reklama.sp', 1, NULL, '2020-11-24 18:34:25', '2020-11-24 18:34:31'),
(55, 4, '/addons/sourcemod/plugins', 'www/server_plugins/4/8JwHevSKaGPMATjDhny7Wct19.smx', 'server_menu.smx', NULL, NULL, '2020-11-24 18:38:07', '0000-00-00 00:00:00'),
(56, 4, '/addons/sourcemod/scripting', 'www/server_plugins/4/r0CsIMHFJjVgBDwqtP5OSQZNA.sp', 'server_menu.sp', 1, NULL, '2020-11-24 18:39:23', '2020-11-24 18:39:29'),
(57, 4, '/addons/sourcemod/configs', 'www/server_plugins/4/YNFUdwOQjut6CfhZb57kSonB3.cfg', 'acp_command_menu.cfg', NULL, NULL, '2020-11-24 19:07:12', '0000-00-00 00:00:00'),
(58, 4, '/addons/sourcemod/configs', 'www/server_plugins/4/CTirOERmBMsVGDhzodP48fFQt.cfg', 'acp_details_menu.cfg', NULL, NULL, '2020-11-24 19:07:20', '0000-00-00 00:00:00'),
(59, 4, '/addons/sourcemod/configs', 'www/server_plugins/4/nPBFi1bmvCrIpUOJwVTYM92Zg.cfg', 'acp_main_menu.cfg', NULL, NULL, '2020-11-24 19:07:34', '0000-00-00 00:00:00'),
(60, 4, '/addons/sourcemod/configs', 'www/server_plugins/4/Bbhu2idwpNGlgMXt9LJ6Aqr7H.cfg', 'acp_servers_menu.cfg', NULL, NULL, '2020-11-24 19:07:42', '0000-00-00 00:00:00'),
(61, 4, '/addons/sourcemod/configs', 'www/server_plugins/4/190LYZ4UhKSdXbaeuFPfAOHxg.cfg', 'acp_admins_menu.cfg', NULL, NULL, '2020-11-24 19:07:48', '0000-00-00 00:00:00'),
(62, 4, '/addons/sourcemod/configs', 'www/server_plugins/4/b4n3iIMzOqLydNpjP0JaKolBQ.cfg', 'acp_stats_menu.cfg', NULL, NULL, '2020-11-24 19:07:55', '0000-00-00 00:00:00'),
(63, 16, '/addons/sourcemod/plugins', 'www/server_plugins/16/qgQsjOPnlfzct0aYw3kyMW7EX.smx', 'name-links-remover.smx', NULL, NULL, '2020-11-24 19:48:03', '0000-00-00 00:00:00'),
(64, 16, '/addons/sourcemod/translations', 'www/server_plugins/16/XRFdNynbj65LK9phOmkTBEWUe.txt', 'name-links-remover.phrases.txt', NULL, NULL, '2020-11-24 19:48:33', '0000-00-00 00:00:00'),
(65, 16, '/addons/sourcemod/configs', 'www/server_plugins/16/LNsXWDcl258r4VSfCgMoRa6mz.txt', 'name-links-remover.txt', NULL, NULL, '2020-11-24 19:48:49', '0000-00-00 00:00:00'),
(66, 17, '/addons/sourcemod/plugins', 'www/server_plugins/17/JKVaLZTtdmjMNrDF5yl1oEvOY.smx', 'weapons.smx', NULL, NULL, '2020-11-24 20:16:23', '0000-00-00 00:00:00'),
(67, 17, '/cfg/sourcemod', 'www/server_plugins/17/cBsWM2rYOwhUZd6ex0nTajI4L.cfg', 'weapons.cfg', NULL, NULL, '2020-11-24 20:16:49', '0000-00-00 00:00:00'),
(68, 17, '/addons/sourcemod/configs/weapons', 'www/server_plugins/17/p7FTkurRxY6EBgOsidMvXWIlm.cfg', 'weapons_polish.cfg', NULL, NULL, '2020-11-24 20:17:28', '0000-00-00 00:00:00'),
(69, 17, '/addons/sourcemod/translations', 'www/server_plugins/17/pOZeL5qujVw3WaCYGn6kcy1ih.txt', 'weapons.phrases.txt', NULL, NULL, '2020-11-24 20:17:54', '0000-00-00 00:00:00'),
(70, 17, '/addons/sourcemod/translations/pl', 'www/server_plugins/17/g3eEdYaHWVp82FMPcBhUw9tQZ.txt', 'weapons.phrases.txt', NULL, NULL, '2020-11-24 20:18:04', '0000-00-00 00:00:00'),
(71, 18, '/addons/sourcemod/plugins', 'www/server_plugins/18/PDBMu1qegpvbZUYWomFGx8VSy.smx', 'gloves.smx', NULL, NULL, '2020-11-24 20:29:42', '0000-00-00 00:00:00'),
(72, 18, '/addons/sourcemod/translations', 'www/server_plugins/18/f8MOkI6q4CmhEGRbPXzatHBFx.txt', 'gloves.phrases.txt', NULL, NULL, '2020-11-24 20:30:09', '0000-00-00 00:00:00'),
(73, 18, '/addons/sourcemod/configs/gloves', 'www/server_plugins/18/bhLc2IkG1tg8ZJfPzUoCQVndi.cfg', 'gloves_polish.cfg', NULL, NULL, '2020-11-24 20:30:42', '0000-00-00 00:00:00'),
(74, 18, '/cfg/sourcemod', 'www/server_plugins/18/SPAkOoZCszBDQnKmJqfgFX3TG.cfg', 'gloves.cfg', NULL, NULL, '2020-11-24 20:31:05', '0000-00-00 00:00:00'),
(79, 19, '/addons/sourcemod/plugins', 'www/server_plugins/19/8Sqd9l17ybTNkiRveCOXF3QHx.smx', 'acp_hide_change_tags.smx', NULL, NULL, '2020-11-27 13:23:50', '0000-00-00 00:00:00'),
(80, 19, '/addons/sourcemod/scripting', 'www/server_plugins/19/FD6GAJbOxj8zXrPmBWaC7NSTf.sp', 'acp_hide_change_tags.sp', 1, NULL, '2020-11-27 13:24:05', '2020-11-27 13:24:09'),
(81, 20, '/addons/sourcemod/plugins', 'www/server_plugins/20/GODXPUV82bvgkar7zjtpC1lZB.smx', 'togglemusic_dhook_clientprefs.smx', NULL, NULL, '2020-12-02 15:59:34', '0000-00-00 00:00:00'),
(82, 20, '/addons/sourcemod/scripting', 'www/server_plugins/20/FUIJc4SRtHhDKbivjdO9WT1lE.sp', 'togglemusic_dhook_clientprefs.sp', 1, NULL, '2020-12-02 15:59:54', '2020-12-02 15:59:59'),
(84, 20, '/addons/sourcemod/translations', 'www/server_plugins/20/dNuzn0eJiowMcHlRQgASskbx2.txt', 'togglemusic_dhook.phrases.txt', NULL, NULL, '2020-12-02 18:48:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `acp_serwery`
--

CREATE TABLE `acp_serwery` (
  `serwer_id` int(11) NOT NULL,
  `serwer_on` int(11) NOT NULL DEFAULT 0,
  `istotnosc` int(11) NOT NULL DEFAULT 0,
  `game` varchar(50) NOT NULL,
  `test_serwer` int(11) NOT NULL DEFAULT 0,
  `ip` varchar(50) NOT NULL,
  `port` varchar(10) NOT NULL,
  `mod` varchar(100) NOT NULL,
  `status` int(11) NOT NULL,
  `status_data` datetime NOT NULL,
  `nazwa` varchar(255) NOT NULL,
  `graczy` int(11) NOT NULL,
  `max_graczy` int(11) NOT NULL,
  `boty` int(11) NOT NULL,
  `tags` text NOT NULL,
  `rcon` varchar(255) NOT NULL,
  `mapa` varchar(255) NOT NULL,
  `czas_reklam` int(11) NOT NULL,
  `liczba_map` int(11) NOT NULL,
  `ip_bot_hlstats` varchar(40) CHARACTER SET latin1 NOT NULL,
  `fastdl` varchar(50) CHARACTER SET latin1 NOT NULL,
  `link_gotv` varchar(40) CHARACTER SET latin1 NOT NULL DEFAULT '#',
  `ftp_user` varchar(50) CHARACTER SET latin1 NOT NULL,
  `ftp_haslo` varchar(150) CHARACTER SET latin1 NOT NULL,
  `ftp_host` varchar(50) CHARACTER SET latin1 NOT NULL,
  `cronjobs` int(11) NOT NULL DEFAULT 0,
  `ser_a_jr` int(11) NOT NULL,
  `ser_a_opiekun` int(11) NOT NULL,
  `ser_a_copiekun` int(11) NOT NULL,
  `prefix_sb` varchar(64) DEFAULT NULL,
  `prefix_hls` varchar(64) DEFAULT NULL,
  `data` datetime NOT NULL DEFAULT current_timestamp(),
  `modification_data` datetime DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `acp_serwery_baza`
--

CREATE TABLE `acp_serwery_baza` (
  `id` int(11) NOT NULL,
  `serwer_id` int(11) NOT NULL,
  `nazwa` varchar(255) NOT NULL,
  `d_driver` varchar(255) NOT NULL,
  `d_host` varchar(255) NOT NULL,
  `d_baze` varchar(255) NOT NULL,
  `d_user` varchar(255) NOT NULL,
  `d_pass` varchar(255) NOT NULL,
  `d_timeout` varchar(255) NOT NULL,
  `d_port` varchar(255) NOT NULL,
  `d_time_port_on` int(11) NOT NULL,
  `data` datetime NOT NULL DEFAULT current_timestamp(),
  `modification_data` datetime DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `acp_serwery_baza`
--

INSERT INTO `acp_serwery_baza` (`id`, `serwer_id`, `nazwa`, `d_driver`, `d_host`, `d_baze`, `d_user`, `d_pass`, `d_timeout`, `d_port`, `d_time_port_on`, `data`, `modification_data`) VALUES
(1, 0, 'default', 'default', 'localhost', 'sourcemod', 'root', '', '', '', 0, '2020-03-29 19:13:24', NULL),
(2, 0, 'clientprefs', 'sqlite', '', 'clientprefs-sqlite', 'root', '', '', '', 0, '2020-03-29 19:13:24', NULL);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `acp_serwery_bledy`
--

CREATE TABLE `acp_serwery_bledy` (
  `id` int(11) NOT NULL,
  `serwer_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `modul` varchar(255) NOT NULL,
  `tekst` text NOT NULL,
  `tekst_admin` text NOT NULL,
  `data` datetime NOT NULL DEFAULT current_timestamp(),
  `modification_data` datetime DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `acp_serwery_cronjobs`
--

CREATE TABLE `acp_serwery_cronjobs` (
  `id` int(11) NOT NULL,
  `serwer` int(11) NOT NULL,
  `typ_polaczenia` varchar(25) DEFAULT NULL,
  `katalog` varchar(255) NOT NULL,
  `rangi` int(11) NOT NULL DEFAULT 0,
  `reklamy` int(11) NOT NULL DEFAULT 0,
  `bazy` int(11) NOT NULL DEFAULT 0,
  `cvary` int(11) NOT NULL DEFAULT 0,
  `mapy` int(11) NOT NULL DEFAULT 0,
  `mapy_plugin` varchar(10) DEFAULT NULL,
  `hextags` int(11) NOT NULL DEFAULT 0,
  `help_menu` int(11) NOT NULL DEFAULT 0,
  `uslugi` int(11) NOT NULL DEFAULT 0,
  `data` datetime NOT NULL DEFAULT current_timestamp(),
  `modification_data` datetime DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `acp_serwery_cvary`
--

CREATE TABLE `acp_serwery_cvary` (
  `id` int(11) NOT NULL,
  `serwer_id` int(11) NOT NULL DEFAULT 0,
  `cvar` varchar(255) NOT NULL,
  `komentarz` text NOT NULL,
  `data` datetime NOT NULL DEFAULT current_timestamp(),
  `modification_data` datetime DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `acp_serwery_gosetti`
--

CREATE TABLE `acp_serwery_gosetti` (
  `id` int(11) NOT NULL,
  `serwer_id` int(11) NOT NULL,
  `data` date NOT NULL,
  `gosetti_rank_all` int(11) NOT NULL,
  `gosetti_rank_tura` int(11) NOT NULL,
  `gosetti_p_klik_tura` int(11) NOT NULL,
  `gosetti_p_skiny_tura` int(11) NOT NULL,
  `gosetti_p_pln_tura` int(11) NOT NULL,
  `gosetti_p_www_tura` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `acp_serwery_helpmenu`
--

CREATE TABLE `acp_serwery_helpmenu` (
  `id` int(11) NOT NULL,
  `serwer_id` int(11) NOT NULL,
  `lista_serwerow` int(11) NOT NULL DEFAULT 1,
  `lista_adminow` int(11) NOT NULL DEFAULT 0,
  `opis_vipa` int(11) NOT NULL DEFAULT 1,
  `lista_komend` int(11) NOT NULL DEFAULT 1,
  `statystyki` int(11) NOT NULL DEFAULT 1,
  `data` datetime NOT NULL DEFAULT current_timestamp(),
  `modification_data` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `acp_serwery_helpmenu_komendy`
--

CREATE TABLE `acp_serwery_helpmenu_komendy` (
  `id` int(11) NOT NULL,
  `serwer_id` int(11) NOT NULL,
  `helpmenu_id` int(11) NOT NULL,
  `komenda` varchar(255) NOT NULL,
  `tekst` varchar(255) NOT NULL,
  `kolejnosc` int(11) NOT NULL,
  `data` datetime NOT NULL DEFAULT current_timestamp(),
  `modification_data` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `acp_serwery_helpmenu_vip`
--

CREATE TABLE `acp_serwery_helpmenu_vip` (
  `id` int(11) NOT NULL,
  `serwer_id` int(11) NOT NULL,
  `helpmenu_id` int(11) NOT NULL,
  `tekst` varchar(255) NOT NULL,
  `kolejnosc` int(11) NOT NULL,
  `data` datetime NOT NULL DEFAULT current_timestamp(),
  `modification_data` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `acp_serwery_hextags`
--

CREATE TABLE `acp_serwery_hextags` (
  `id` int(11) NOT NULL,
  `serwer_id` int(11) NOT NULL,
  `hextags` varchar(20) NOT NULL,
  `TagName` varchar(25) NOT NULL DEFAULT '',
  `ScoreTag` varchar(20) NOT NULL,
  `TagColor` varchar(40) NOT NULL DEFAULT 'default',
  `ChatTag` varchar(40) NOT NULL,
  `ChatColor` varchar(40) NOT NULL DEFAULT 'default',
  `NameColor` varchar(40) NOT NULL DEFAULT 'default',
  `Force` int(11) DEFAULT 0,
  `istotnosc` int(11) NOT NULL DEFAULT 0,
  `czasowa` int(11) DEFAULT 0,
  `czasowa_end` datetime NOT NULL,
  `komentarz` varchar(255) DEFAULT NULL,
  `data` datetime NOT NULL DEFAULT current_timestamp(),
  `modification_data` datetime DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `acp_serwery_hlstats`
--

CREATE TABLE `acp_serwery_hlstats` (
  `id` int(11) NOT NULL,
  `serwer_id` int(11) NOT NULL,
  `data` date NOT NULL,
  `hls_graczy` int(11) NOT NULL DEFAULT 0,
  `hls_nowych_graczy` int(11) NOT NULL DEFAULT 0,
  `hls_zabojstw` int(11) NOT NULL DEFAULT 0,
  `hls_nowych_zabojstw` int(11) NOT NULL DEFAULT 0,
  `hls_hs` int(11) NOT NULL DEFAULT 0,
  `hls_nowych_hs` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `acp_serwery_hlstats_top`
--

CREATE TABLE `acp_serwery_hlstats_top` (
  `id` int(11) NOT NULL,
  `serwer_id` int(11) NOT NULL,
  `data` date NOT NULL,
  `dane` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `acp_serwery_listaadminow`
--

CREATE TABLE `acp_serwery_listaadminow` (
  `id` int(11) NOT NULL,
  `serwer` int(11) NOT NULL,
  `dane` text NOT NULL,
  `ilosc_adminow` int(11) NOT NULL DEFAULT 10,
  `data` datetime NOT NULL DEFAULT current_timestamp(),
  `modification_data` datetime DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `acp_serwery_logs`
--

CREATE TABLE `acp_serwery_logs` (
  `id` int(11) NOT NULL,
  `serwer_id` int(11) NOT NULL,
  `graczy` int(11) NOT NULL,
  `boty` int(11) NOT NULL,
  `sloty` int(11) NOT NULL,
  `data` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `acp_serwery_logs_day`
--

CREATE TABLE `acp_serwery_logs_day` (
  `id` int(11) NOT NULL,
  `serwer_id` int(11) NOT NULL,
  `graczy` int(11) NOT NULL,
  `boty` int(11) NOT NULL,
  `sloty` int(11) NOT NULL,
  `suma_graczy` int(11) NOT NULL,
  `suma_botow` int(11) NOT NULL,
  `suma_sloty` int(11) NOT NULL,
  `data` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `acp_serwery_logs_hour`
--

CREATE TABLE `acp_serwery_logs_hour` (
  `id` int(11) NOT NULL,
  `serwer_id` int(11) NOT NULL,
  `graczy` int(11) NOT NULL,
  `boty` int(11) NOT NULL,
  `sloty` int(11) NOT NULL,
  `suma_graczy` int(11) NOT NULL,
  `suma_botow` int(11) NOT NULL,
  `suma_sloty` int(11) NOT NULL,
  `data` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `acp_serwery_logs_month`
--

CREATE TABLE `acp_serwery_logs_month` (
  `id` int(11) NOT NULL,
  `serwer_id` int(11) NOT NULL,
  `graczy` int(11) NOT NULL,
  `boty` int(11) NOT NULL,
  `sloty` int(11) NOT NULL,
  `suma_graczy` int(11) NOT NULL,
  `suma_botow` int(11) NOT NULL,
  `suma_sloty` int(11) NOT NULL,
  `data` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `acp_serwery_mapy`
--

CREATE TABLE `acp_serwery_mapy` (
  `id` int(11) NOT NULL,
  `serwer_id` int(10) NOT NULL,
  `nazwa` varchar(255) DEFAULT NULL,
  `display_template` varchar(255) DEFAULT NULL,
  `maps_invote` int(11) DEFAULT NULL,
  `group_weight` float DEFAULT NULL,
  `next_mapgroup` varchar(255) DEFAULT NULL,
  `default_min_players` int(4) DEFAULT NULL,
  `default_max_players` int(11) DEFAULT NULL,
  `default_min_time` varchar(4) DEFAULT NULL,
  `default_max_time` varchar(4) DEFAULT NULL,
  `default_allow_every` int(11) DEFAULT NULL,
  `command` varchar(255) DEFAULT NULL,
  `nominate_flags` varchar(255) DEFAULT NULL,
  `adminmenu_flag` varchar(255) DEFAULT NULL,
  `data` datetime NOT NULL DEFAULT current_timestamp(),
  `modification_data` datetime DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `acp_serwery_mapy_det`
--

CREATE TABLE `acp_serwery_mapy_det` (
  `id` int(11) NOT NULL,
  `mapy_id` int(11) DEFAULT NULL,
  `nazwa` varchar(255) NOT NULL,
  `display` varchar(255) DEFAULT NULL,
  `weight` float DEFAULT NULL,
  `next_mapgroup` varchar(255) DEFAULT NULL,
  `min_players` int(11) DEFAULT NULL,
  `max_players` int(11) DEFAULT NULL,
  `min_time` varchar(4) DEFAULT NULL,
  `max_time` varchar(4) DEFAULT NULL,
  `allow_every` int(11) DEFAULT NULL,
  `command` varchar(255) DEFAULT NULL,
  `nominate_flags` varchar(255) DEFAULT NULL,
  `adminmenu_flags` varchar(255) DEFAULT NULL,
  `nominate_group` varchar(255) DEFAULT NULL,
  `data` datetime NOT NULL DEFAULT current_timestamp(),
  `modification_data` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `acp_serwery_mapy_img`
--

CREATE TABLE `acp_serwery_mapy_img` (
  `id` int(11) NOT NULL,
  `id_mapy` int(11) NOT NULL,
  `imgur_url` varchar(255) NOT NULL,
  `data` datetime NOT NULL DEFAULT current_timestamp(),
  `modification_data` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `acp_serwery_rangi`
--

CREATE TABLE `acp_serwery_rangi` (
  `id` int(11) NOT NULL,
  `serwer_id` int(11) NOT NULL,
  `flags` varchar(20) NOT NULL,
  `tag_tabela` varchar(20) NOT NULL,
  `tag_say` varchar(40) NOT NULL,
  `tag_say_kolor` varchar(10) NOT NULL,
  `nick_say_kolor` varchar(10) NOT NULL,
  `istotnosc` int(11) NOT NULL DEFAULT 0,
  `czasowa` int(11) NOT NULL DEFAULT 0,
  `komentarz` varchar(255) DEFAULT NULL,
  `data` datetime NOT NULL DEFAULT current_timestamp(),
  `modification_data` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `acp_serwery_regulamin`
--

CREATE TABLE `acp_serwery_regulamin` (
  `id` int(11) NOT NULL,
  `serwer_id` int(11) NOT NULL,
  `tekst` longtext NOT NULL,
  `link` varchar(255) NOT NULL,
  `data` datetime NOT NULL DEFAULT current_timestamp(),
  `modification_data` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `acp_serwery_reklamy`
--

CREATE TABLE `acp_serwery_reklamy` (
  `id` int(11) NOT NULL,
  `serwer_id` int(11) NOT NULL,
  `tekst` longtext NOT NULL,
  `gdzie` varchar(10) NOT NULL,
  `czasowa` int(11) NOT NULL DEFAULT 0,
  `czasowa_end` date NOT NULL,
  `zakres` int(11) NOT NULL DEFAULT 0,
  `zakres_start` int(11) DEFAULT 0,
  `zakres_stop` int(11) NOT NULL DEFAULT 0,
  `data` datetime NOT NULL DEFAULT current_timestamp(),
  `modification_data` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `acp_serwery_tagi`
--

CREATE TABLE `acp_serwery_tagi` (
  `id` int(11) NOT NULL,
  `serwer` int(11) NOT NULL,
  `tekst` varchar(12) NOT NULL,
  `staly` int(11) NOT NULL DEFAULT 0,
  `data` datetime NOT NULL DEFAULT current_timestamp(),
  `modification_data` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `acp_serwery_update`
--

CREATE TABLE `acp_serwery_update` (
  `id` int(11) NOT NULL,
  `serwer_id` int(11) NOT NULL,
  `source` varchar(255) DEFAULT NULL,
  `meta` varchar(255) NOT NULL,
  `data` datetime DEFAULT current_timestamp(),
  `modification_data` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `acp_slots_serwery`
--

CREATE TABLE `acp_slots_serwery` (
  `id` int(11) NOT NULL,
  `serwer` int(11) NOT NULL,
  `min` int(11) NOT NULL DEFAULT 10,
  `max` int(11) NOT NULL DEFAULT 32,
  `h_start` int(11) NOT NULL DEFAULT 14,
  `h_koniec` int(11) NOT NULL DEFAULT 22,
  `style` varchar(8) DEFAULT 'LOW'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `acp_sourceupdate`
--

CREATE TABLE `acp_sourceupdate` (
  `id` int(11) NOT NULL,
  `sm` varchar(255) NOT NULL,
  `mm` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `data` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `acp_users`
--

CREATE TABLE `acp_users` (
  `user` int(11) NOT NULL,
  `login` varchar(20) NOT NULL,
  `pass` varchar(40) NOT NULL,
  `pass_hash` varchar(255) DEFAULT NULL,
  `email` varchar(40) NOT NULL,
  `role` tinyint(4) NOT NULL DEFAULT 0,
  `grupa` int(11) NOT NULL,
  `last_login` datetime NOT NULL,
  `data_rejestracji` datetime NOT NULL,
  `urodziny` date NOT NULL,
  `banned` int(11) NOT NULL DEFAULT -1,
  `cash` int(11) NOT NULL,
  `ulubiony_serwer` int(11) NOT NULL,
  `lokalizacja` text NOT NULL,
  `wyksztalcenie` text NOT NULL,
  `steam` varchar(20) NOT NULL,
  `steam_update` datetime NOT NULL,
  `steam_avatar` varchar(200) NOT NULL DEFAULT '	www/img/av_default.jpg	',
  `steam_login` varchar(100) NOT NULL,
  `szablon` varchar(50) NOT NULL DEFAULT 'skin-blue',
  `uklad_16_4` int(11) NOT NULL DEFAULT 0,
  `pudelkowy` int(11) NOT NULL DEFAULT 0,
  `menu` int(11) NOT NULL DEFAULT 0,
  `prawy_kolor` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `acp_users`
--

INSERT INTO `acp_users` (`user`, `login`, `pass`, `pass_hash`, `email`, `role`, `grupa`, `last_login`, `data_rejestracji`, `urodziny`, `banned`, `cash`, `ulubiony_serwer`, `lokalizacja`, `wyksztalcenie`, `steam`, `steam_update`, `steam_avatar`, `steam_login`, `szablon`, `uklad_16_4`, `pudelkowy`, `menu`, `prawy_kolor`) VALUES
(1, 'admin', 'd69c2069eb7a0cb4d112575e79e2b836', NULL, 'admin@poczta.pl', 1, 1, '2021-02-06 15:46:17', '2018-01-01 16:25:30', '1994-05-06', -1, 0, 0, 'Polska', '-', 'NONE', '2021-02-06 14:50:05', '	www/img/av_default.jpg	', 'Admin', 'skin-red', 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `acp_users_departament`
--

CREATE TABLE `acp_users_departament` (
  `id` int(11) NOT NULL,
  `nazwa` varchar(100) NOT NULL,
  `odpowiedzialny` int(11) NOT NULL,
  `zastepca` int(11) NOT NULL,
  `grupa_1` int(11) NOT NULL,
  `grupa_2` int(11) NOT NULL,
  `grupa_3` int(11) NOT NULL,
  `data` datetime NOT NULL DEFAULT current_timestamp(),
  `modification_data` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `acp_users_grupy`
--

CREATE TABLE `acp_users_grupy` (
  `id` int(11) NOT NULL,
  `departament` int(11) NOT NULL,
  `kolor` varchar(7) NOT NULL,
  `nazwa` varchar(255) NOT NULL,
  `moduly` longtext NOT NULL,
  `dostep` longtext NOT NULL,
  `data` datetime NOT NULL DEFAULT current_timestamp(),
  `modification_data` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `acp_users_grupy`
--

INSERT INTO `acp_users_grupy` (`id`, `departament`, `kolor`, `nazwa`, `moduly`, `dostep`, `data`, `modification_data`) VALUES
(0, 0, '#ffffff', 'Użytkownik', '[\"wpisy\",\"serwery\",\"account\",\"acp_logs\",\"forum\"]', '[{\"WpisyUsun\":\"0\",\"WpisyZamknij\":\"0\",\"WpisyOgloszenie\":\"0\",\"WpisyKategoria\":\"0\",\"WpisyEdytujWpis\":\"0\",\"ustawienia_podstawowe\":\"0\",\"serwery_det_CzytajPlik\":\"0\",\"serwery_det_SerKonfiguracjaALL\":\"0\",\"serwery_det_SerKonfiguracjaRangi\":\"0\",\"serwery_det_SerKonfiguracjaReklamy\":\"0\",\"serwery_det_SerKonfiguracjaBazy\":\"0\",\"serwery_det_WgrajMape\":\"0\",\"SerwerUsun\":\"0\",\"SerwerDodaj\":\"0\",\"SerwerEdytuj\":\"0\",\"SerwerCron\":\"0\",\"SerwerRangiUsun\":\"0\",\"SerwerRangiEdytuj\":\"0\",\"SerwerRangiDodaj\":\"0\",\"SerwerReklamyUsun\":\"0\",\"SerwerReklamyEdytuj\":\"0\",\"SerwerReklamyDodaj\":\"0\",\"SerwerBazaUsun\":\"0\",\"SerwerBazaEdytuj\":\"0\",\"SerwerBazaDodaj\":\"0\",\"SerwerCvaryUsun\":\"0\",\"SerwerCvaryEdytuj\":\"0\",\"SerwerCvaryDodaj\":\"0\",\"SerwerWymusAktualizacje\":\"0\",\"SerwerMapyGrupaDodaj\":\"0\",\"SerwerMapyGrupaEdytuj\":\"0\",\"SerwerMapyZapisz\":\"0\",\"SerwerMapaDodaj\":\"0\",\"SerwerMapaUsun\":\"0\",\"SerwerMapaEdytuj\":\"0\",\"SerwerMapaGaleria\":\"0\",\"SerwerMapyGrupaUsun\":\"0\",\"ChangelogUsun\":\"0\",\"ChangelogEdytuj\":\"0\",\"ZadaniaDodaj\":\"0\",\"ZadanieEdytuj\":\"0\",\"ZadanieUsun\":\"0\",\"ZadanieAkcOdrz\":\"0\",\"ZadaniePrzyjmnij\":\"0\",\"ZadanieZakoncz\":\"0\",\"ZadanieAnuluj\":\"0\",\"ZadanieKomentarze\":\"0\",\"ZadanieToDo\":\"0\",\"ZadanieZapros\":\"0\",\"PluginyDodaj\":\"0\",\"PluginyEdytuj\":\"0\",\"PluginyUsun\":\"0\",\"PluginyPlikDodaj\":\"0\",\"PluginyPlikEdytuj\":\"0\",\"PluginyPlikUsun\":\"0\",\"PluginyWgrywarka\":\"0\",\"GaleriaMapWgraj\":\"0\"}]', '2020-03-29 19:19:08', '2020-04-02 19:41:35'),
(1, 0, '', 'Administrator ACP', '[\"wpisy\",\"serwery\",\"account\",\"serwery_det\",\"serwery_ust\",\"acp_users\",\"acp_grupy\",\"acp_moduly\",\"acp_ustawienia\",\"serwery_konfiguracja\",\"changelog_edit\",\"acp_logs\",\"zadania\",\"wgrywarka\",\"pluginy\",\"galeria_map\",\"forum\",\"konkurencja\",\"roundsound\",\"raporty\",\"uslugi\",\"sourceupdate\",\"console\",\"slots\",\"kokpit_serwerow\",\"serwer_live_say\"]', '[{\"WpisyUsun\":\"1\",\"WpisyZamknij\":\"1\",\"WpisyOgloszenie\":\"1\",\"WpisyKategoria\":\"0\",\"WpisyEdytujWpis\":\"0\",\"ustawienia_podstawowe\":\"1\",\"serwery_det_CzytajPlik\":\"1\",\"serwery_det_SerKonfiguracjaALL\":\"1\",\"serwery_det_SerKonfiguracjaRangi\":\"1\",\"serwery_det_SerKonfiguracjaReklamy\":\"1\",\"serwery_det_SerKonfiguracjaBazy\":\"1\",\"serwery_det_WgrajMape\":\"0\",\"PraceCykliczneOdczytane\":\"0\",\"serwery_det_RaportOpiekuna\":\"1\",\"serwery_det_logi\":\"0\",\"serwery_det_SB_adm_dodaj\":\"0\",\"serwery_det_SB_adm_edytuj\":\"0\",\"serwery_det_SB_adm_usun\":\"0\",\"serwery_det_SB_adm_degra_rezy\":\"0\",\"SerwerUsun\":\"1\",\"SerwerDodaj\":\"1\",\"SerwerEdytuj\":\"1\",\"SerwerCron\":\"1\",\"SerwerRangiUsun\":\"1\",\"SerwerRangiEdytuj\":\"1\",\"SerwerRangiDodaj\":\"1\",\"SerwerReklamyUsun\":\"1\",\"SerwerReklamyEdytuj\":\"1\",\"SerwerReklamyDodaj\":\"1\",\"SerwerBazaUsun\":\"1\",\"SerwerBazaEdytuj\":\"1\",\"SerwerBazaDodaj\":\"1\",\"SerwerWymusAktualizacje\":\"1\",\"SerwerMapyGrupaDodaj\":\"1\",\"SerwerMapyGrupaEdytuj\":\"1\",\"SerwerMapyZapisz\":\"1\",\"SerwerMapaDodaj\":\"1\",\"SerwerMapaUsun\":\"1\",\"SerwerMapaEdytuj\":\"1\",\"SerwerMapaGaleria\":\"1\",\"SerwerMapyGrupaUsun\":\"0\",\"SerwerHelpMenuDodaj\":\"0\",\"SerwerHelpMenuEdytuj\":\"0\",\"SerwerHelpMenuUsun\":\"0\",\"SerwerHelpMenuKonfiguracja\":\"0\",\"ChangelogUsun\":\"1\",\"ChangelogEdytuj\":\"1\",\"ZadaniaDodaj\":\"1\",\"ZadanieEdytuj\":\"1\",\"ZadanieUsun\":\"1\",\"ZadanieAkcOdrz\":\"1\",\"ZadaniePrzyjmnij\":\"1\",\"ZadanieZakoncz\":\"1\",\"ZadanieAnuluj\":\"1\",\"ZadanieKomentarze\":\"1\",\"ZadanieToDo\":\"1\",\"ZadanieZapros\":\"1\",\"ZadanieLink\":\"0\",\"WgrywarkaDownloadFile\":\"1\",\"PluginyDodaj\":\"0\",\"PluginyEdytuj\":\"0\",\"PluginyUsun\":\"0\",\"PluginyPlikDodaj\":\"0\",\"PluginyPlikEdytuj\":\"0\",\"PluginyPlikUsun\":\"0\",\"PluginyWgrywarka\":\"0\",\"GaleriaMapWgraj\":\"0\",\"KonkurencjaEdytuj\":\"0\",\"KonkurencjaDodaj\":\"0\",\"KonkurencjaUsun\":\"0\",\"KonkurencjaCache\":\"0\",\"RsListaDodaj\":\"1\",\"RsListaEdycja\":\"1\",\"RsListaUsun\":\"1\",\"RsListaDodajPiosenke\":\"1\",\"RsListaUsunPiosenke\":\"1\",\"RsPiosenkaDodaj\":\"1\",\"RsPiosenkaEdytuj\":\"1\",\"RsPiosenkaUsun\":\"1\",\"RsPiosenkaAkcept\":\"1\",\"RsPiosenkaMp3\":\"1\",\"RsUstPodstawowe\":\"1\",\"RsUstSerwery\":\"1\",\"RsUstawStatus\":\"0\",\"RsPiosenkaDodajDoListy\":\"0\",\"UslugiUstawienia\":\"0\",\"UslugiDodaj\":\"0\",\"UslugiListaEdytuj\":\"0\",\"UslugiListaUsun\":\"0\",\"SourceUpdate\":\"1\"}]', '2020-03-29 19:19:08', '2020-12-12 20:10:20');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `acp_users_login_logs`
--

CREATE TABLE `acp_users_login_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `przegladarka` varchar(255) NOT NULL,
  `poprawne` int(11) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `acp_users_notification`
--

CREATE TABLE `acp_users_notification` (
  `id` int(11) NOT NULL,
  `u_id` int(11) NOT NULL,
  `link` text NOT NULL,
  `text` text NOT NULL,
  `icon` text NOT NULL,
  `data` datetime NOT NULL DEFAULT current_timestamp(),
  `read` int(11) NOT NULL DEFAULT 1,
  `read_date` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `acp_uslugi`
--

CREATE TABLE `acp_uslugi` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `serwer` int(11) NOT NULL,
  `steam` varchar(20) NOT NULL,
  `steam_id` varchar(32) NOT NULL,
  `koniec` datetime NOT NULL,
  `rodzaj` int(11) NOT NULL,
  `code_promo` int(11) NOT NULL,
  `data` datetime NOT NULL DEFAULT current_timestamp(),
  `modification_data` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `acp_uslugi_code`
--

CREATE TABLE `acp_uslugi_code` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `rodzaj` int(11) NOT NULL,
  `code` int(11) NOT NULL,
  `ilosc` int(11) NOT NULL,
  `ilosc_pozostalo` int(11) NOT NULL,
  `data` datetime NOT NULL DEFAULT current_timestamp(),
  `data_koniec` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `acp_uslugi_rodzaje`
--

CREATE TABLE `acp_uslugi_rodzaje` (
  `id` int(11) NOT NULL,
  `nazwa` varchar(255) NOT NULL,
  `flags` varchar(12) NOT NULL,
  `publiczna` int(11) NOT NULL DEFAULT 0,
  `serwery` text NOT NULL DEFAULT '[]',
  `img` varchar(100) NOT NULL,
  `opis` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `acp_wgrywarka`
--

CREATE TABLE `acp_wgrywarka` (
  `id` int(11) NOT NULL,
  `serwer_id` int(11) NOT NULL,
  `u_id` int(11) NOT NULL,
  `nazwa` varchar(255) DEFAULT NULL,
  `kat` varchar(255) DEFAULT NULL,
  `file` longtext DEFAULT NULL,
  `status` int(11) DEFAULT 0,
  `data` datetime DEFAULT current_timestamp(),
  `data_upload` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `acp_wpisy`
--

CREATE TABLE `acp_wpisy` (
  `id` int(11) NOT NULL,
  `u_id` int(11) NOT NULL,
  `tytul` varchar(60) NOT NULL,
  `text` longtext NOT NULL,
  `kategoria` int(11) NOT NULL,
  `data` datetime NOT NULL DEFAULT current_timestamp(),
  `modification_data` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `closed` int(11) NOT NULL DEFAULT 0,
  `closed_data` datetime DEFAULT NULL,
  `ogloszenie` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `acp_wpisy`
--

INSERT INTO `acp_wpisy` (`id`, `u_id`, `tytul`, `text`, `kategoria`, `data`, `modification_data`, `closed`, `closed_data`, `ogloszenie`) VALUES
(1, 1, 'Pierwszy Wpis', 'Pierwszy Wpis', 0, '2021-02-06 15:41:37', NULL, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `acp_wpisy_kategorie`
--

CREATE TABLE `acp_wpisy_kategorie` (
  `id` int(11) NOT NULL,
  `nazwa` varchar(100) NOT NULL,
  `data` datetime NOT NULL DEFAULT current_timestamp(),
  `modification_data` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `acp_wpisy_komentarze`
--

CREATE TABLE `acp_wpisy_komentarze` (
  `id` int(11) NOT NULL,
  `wpis_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `text` longtext NOT NULL,
  `data` datetime NOT NULL DEFAULT current_timestamp(),
  `modification_data` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `acp_zadania`
--

CREATE TABLE `acp_zadania` (
  `id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `platforma` int(11) NOT NULL,
  `typ` int(11) NOT NULL,
  `serwer_id` int(11) DEFAULT NULL,
  `temat` varchar(40) NOT NULL,
  `opis` text NOT NULL,
  `data` datetime NOT NULL DEFAULT current_timestamp(),
  `modification_data` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `zlecajacy_id` int(11) NOT NULL,
  `technik_id` int(11) NOT NULL DEFAULT 0,
  `t_data` datetime DEFAULT NULL,
  `akceptujacy_id` int(11) NOT NULL DEFAULT 0,
  `a_data` datetime DEFAULT NULL,
  `time_end` datetime DEFAULT NULL,
  `procent_wykonania` int(11) DEFAULT NULL,
  `kolor_wykonania` varchar(60) DEFAULT NULL,
  `public_code` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `acp_zadania_com`
--

CREATE TABLE `acp_zadania_com` (
  `id` int(11) NOT NULL,
  `id_z` int(11) NOT NULL,
  `u_id` int(11) NOT NULL,
  `text` longtext NOT NULL,
  `data` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `acp_zadania_platforma`
--

CREATE TABLE `acp_zadania_platforma` (
  `id` int(11) NOT NULL,
  `nazwa` text NOT NULL,
  `web` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `acp_zadania_platforma`
--

INSERT INTO `acp_zadania_platforma` (`id`, `nazwa`, `web`) VALUES
(1, 'Strona WWW', 1),
(2, 'Serwer CS:GO', 0),
(3, 'Serwer TS3', 0),
(4, 'Discord', 0);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `acp_zadania_status`
--

CREATE TABLE `acp_zadania_status` (
  `id` int(11) NOT NULL,
  `nazwa` text NOT NULL,
  `typ` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `acp_zadania_status`
--

INSERT INTO `acp_zadania_status` (`id`, `nazwa`, `typ`) VALUES
(-2, 'Anulowane', 'default'),
(-1, 'Odrzucone', 'default'),
(0, 'Nowe', 'info'),
(1, 'Do realizacji', 'warning'),
(2, 'W realizacji', 'info'),
(3, 'Zakończone', 'success');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `acp_zadania_todo`
--

CREATE TABLE `acp_zadania_todo` (
  `id` int(11) NOT NULL,
  `zadanie_id` int(11) NOT NULL,
  `tekst` text NOT NULL,
  `data` datetime NOT NULL DEFAULT current_timestamp(),
  `pozostalo` int(11) NOT NULL,
  `zrealizowano` int(11) DEFAULT 0,
  `zrealizowano_data` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `acp_zadania_typ`
--

CREATE TABLE `acp_zadania_typ` (
  `id` int(11) NOT NULL,
  `nazwa` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `acp_zadania_typ`
--

INSERT INTO `acp_zadania_typ` (`id`, `nazwa`) VALUES
(1, 'Modyfikacja'),
(2, 'FIX'),
(3, 'Nowość');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `acp_zadania_users`
--

CREATE TABLE `acp_zadania_users` (
  `id_zadania` int(11) NOT NULL,
  `u_id` int(11) NOT NULL,
  `data` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `raport_opiekun`
--

CREATE TABLE `raport_opiekun` (
  `id` int(11) NOT NULL,
  `serwer` int(11) NOT NULL,
  `opiekun` int(11) NOT NULL,
  `chefadmin` int(11) NOT NULL,
  `steamid` varchar(255) NOT NULL,
  `admin_nick` text NOT NULL,
  `admin_steam` text NOT NULL,
  `grupa` varchar(255) NOT NULL,
  `forum_posty` int(11) NOT NULL,
  `forum_warny` int(11) NOT NULL,
  `serwer_czaspolaczenia` int(11) NOT NULL,
  `skladka` int(11) NOT NULL,
  `skladka_kwota` int(11) NOT NULL,
  `skladka_metoda` varchar(255) NOT NULL,
  `opinia` text NOT NULL,
  `data_raportu` datetime NOT NULL,
  `miesiac` int(11) NOT NULL,
  `rok` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `raport_serwer`
--

CREATE TABLE `raport_serwer` (
  `id` int(11) NOT NULL,
  `save` int(11) NOT NULL DEFAULT 0,
  `serwer_id` int(11) NOT NULL,
  `mod` varchar(255) NOT NULL,
  `nazwa` varchar(255) NOT NULL,
  `hls_graczy` int(11) NOT NULL,
  `finanse_koszt` int(11) NOT NULL,
  `sklep_uslugi` int(11) NOT NULL,
  `sklep_uslugi_koszt` int(11) NOT NULL,
  `admini_liczba` int(11) NOT NULL,
  `admin_miesiaca` varchar(255) NOT NULL,
  `gt_rank` int(11) NOT NULL,
  `gt_low` int(11) NOT NULL,
  `gt_hight` int(11) NOT NULL,
  `sb_ban` int(11) NOT NULL,
  `sb_mute` int(11) NOT NULL,
  `sb_gag` int(11) NOT NULL,
  `sb_unban` int(11) NOT NULL,
  `sb_unmute` int(11) NOT NULL,
  `sb_ungag` int(11) NOT NULL,
  `data_raportu` datetime NOT NULL DEFAULT current_timestamp(),
  `miesiac` int(11) NOT NULL,
  `rok` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `rs_roundsound`
--

CREATE TABLE `rs_roundsound` (
  `id` int(11) NOT NULL,
  `nazwa` varchar(255) NOT NULL,
  `u_id` int(11) NOT NULL,
  `lista_piosenek` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `data` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `rs_ustawienia`
--

CREATE TABLE `rs_ustawienia` (
  `conf_name` varchar(255) NOT NULL,
  `conf_value` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `rs_utwory`
--

CREATE TABLE `rs_utwory` (
  `id` int(11) NOT NULL,
  `nazwa` varchar(255) NOT NULL,
  `wykonawca` varchar(255) NOT NULL,
  `album` varchar(255) NOT NULL,
  `start` varchar(10) NOT NULL,
  `end` varchar(10) NOT NULL,
  `link_yt` varchar(255) NOT NULL,
  `roundsound_propozycja` int(11) NOT NULL DEFAULT 0,
  `roundsound_propozycja_dodane` int(11) DEFAULT 0,
  `vote` int(11) NOT NULL,
  `mp3` int(11) NOT NULL DEFAULT 0,
  `mp3_code` varchar(10) NOT NULL,
  `akcept` int(11) NOT NULL DEFAULT 0,
  `data_akcept` datetime NOT NULL,
  `data_dodania` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `rs_vote`
--

CREATE TABLE `rs_vote` (
  `id` int(11) NOT NULL,
  `roundsound` int(11) NOT NULL,
  `utwor` int(11) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `przegladarka` varchar(255) NOT NULL,
  `data` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `acp_cache_api`
--
ALTER TABLE `acp_cache_api`
  ADD UNIQUE KEY `get` (`get`);

--
-- Indeksy dla tabeli `acp_konkurencja`
--
ALTER TABLE `acp_konkurencja`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indeksy dla tabeli `acp_log`
--
ALTER TABLE `acp_log`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `acp_log_serwery`
--
ALTER TABLE `acp_log_serwery`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `acp_messages`
--
ALTER TABLE `acp_messages`
  ADD PRIMARY KEY (`m_id`),
  ADD KEY `m_type` (`m_type`),
  ADD KEY `m_from` (`m_from`),
  ADD KEY `m_to` (`m_to`);

--
-- Indeksy dla tabeli `acp_moduly`
--
ALTER TABLE `acp_moduly`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nazwa` (`nazwa`);

--
-- Indeksy dla tabeli `acp_moduly_akcje`
--
ALTER TABLE `acp_moduly_akcje`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `acp_moduly_menu`
--
ALTER TABLE `acp_moduly_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `acp_pluginy`
--
ALTER TABLE `acp_pluginy`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `acp_pluginy_pliki`
--
ALTER TABLE `acp_pluginy_pliki`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `acp_serwery`
--
ALTER TABLE `acp_serwery`
  ADD PRIMARY KEY (`serwer_id`);

--
-- Indeksy dla tabeli `acp_serwery_baza`
--
ALTER TABLE `acp_serwery_baza`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `acp_serwery_bledy`
--
ALTER TABLE `acp_serwery_bledy`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `acp_serwery_cronjobs`
--
ALTER TABLE `acp_serwery_cronjobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `serwer` (`serwer`);

--
-- Indeksy dla tabeli `acp_serwery_cvary`
--
ALTER TABLE `acp_serwery_cvary`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `acp_serwery_gosetti`
--
ALTER TABLE `acp_serwery_gosetti`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `acp_serwery_helpmenu`
--
ALTER TABLE `acp_serwery_helpmenu`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `serwer_id` (`serwer_id`);

--
-- Indeksy dla tabeli `acp_serwery_helpmenu_komendy`
--
ALTER TABLE `acp_serwery_helpmenu_komendy`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `acp_serwery_helpmenu_vip`
--
ALTER TABLE `acp_serwery_helpmenu_vip`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `acp_serwery_hextags`
--
ALTER TABLE `acp_serwery_hextags`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `acp_serwery_hlstats`
--
ALTER TABLE `acp_serwery_hlstats`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `acp_serwery_hlstats_top`
--
ALTER TABLE `acp_serwery_hlstats_top`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `acp_serwery_listaadminow`
--
ALTER TABLE `acp_serwery_listaadminow`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `serwer` (`serwer`);

--
-- Indeksy dla tabeli `acp_serwery_logs`
--
ALTER TABLE `acp_serwery_logs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indeksy dla tabeli `acp_serwery_logs_day`
--
ALTER TABLE `acp_serwery_logs_day`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `acp_serwery_logs_hour`
--
ALTER TABLE `acp_serwery_logs_hour`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `acp_serwery_logs_month`
--
ALTER TABLE `acp_serwery_logs_month`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `acp_serwery_mapy`
--
ALTER TABLE `acp_serwery_mapy`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `acp_serwery_mapy_det`
--
ALTER TABLE `acp_serwery_mapy_det`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `acp_serwery_mapy_img`
--
ALTER TABLE `acp_serwery_mapy_img`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `acp_serwery_rangi`
--
ALTER TABLE `acp_serwery_rangi`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `acp_serwery_regulamin`
--
ALTER TABLE `acp_serwery_regulamin`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `acp_serwery_reklamy`
--
ALTER TABLE `acp_serwery_reklamy`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `acp_serwery_tagi`
--
ALTER TABLE `acp_serwery_tagi`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `acp_serwery_update`
--
ALTER TABLE `acp_serwery_update`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `acp_slots_serwery`
--
ALTER TABLE `acp_slots_serwery`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `acp_sourceupdate`
--
ALTER TABLE `acp_sourceupdate`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `acp_system`
--
ALTER TABLE `acp_system`
  ADD PRIMARY KEY (`conf_name`);

--
-- Indeksy dla tabeli `acp_users`
--
ALTER TABLE `acp_users`
  ADD PRIMARY KEY (`user`),
  ADD UNIQUE KEY `login` (`login`);

--
-- Indeksy dla tabeli `acp_users_departament`
--
ALTER TABLE `acp_users_departament`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `acp_users_grupy`
--
ALTER TABLE `acp_users_grupy`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `acp_users_login_logs`
--
ALTER TABLE `acp_users_login_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `acp_users_notification`
--
ALTER TABLE `acp_users_notification`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indeksy dla tabeli `acp_uslugi`
--
ALTER TABLE `acp_uslugi`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `acp_uslugi_code`
--
ALTER TABLE `acp_uslugi_code`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `acp_uslugi_rodzaje`
--
ALTER TABLE `acp_uslugi_rodzaje`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `acp_wgrywarka`
--
ALTER TABLE `acp_wgrywarka`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `acp_wpisy`
--
ALTER TABLE `acp_wpisy`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `acp_wpisy_kategorie`
--
ALTER TABLE `acp_wpisy_kategorie`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `acp_wpisy_komentarze`
--
ALTER TABLE `acp_wpisy_komentarze`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `acp_zadania`
--
ALTER TABLE `acp_zadania`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `acp_zadania_com`
--
ALTER TABLE `acp_zadania_com`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `acp_zadania_platforma`
--
ALTER TABLE `acp_zadania_platforma`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `acp_zadania_status`
--
ALTER TABLE `acp_zadania_status`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `acp_zadania_todo`
--
ALTER TABLE `acp_zadania_todo`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `acp_zadania_typ`
--
ALTER TABLE `acp_zadania_typ`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `raport_opiekun`
--
ALTER TABLE `raport_opiekun`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indeksy dla tabeli `raport_serwer`
--
ALTER TABLE `raport_serwer`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `rs_roundsound`
--
ALTER TABLE `rs_roundsound`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `rs_ustawienia`
--
ALTER TABLE `rs_ustawienia`
  ADD PRIMARY KEY (`conf_name`);

--
-- Indeksy dla tabeli `rs_utwory`
--
ALTER TABLE `rs_utwory`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `rs_vote`
--
ALTER TABLE `rs_vote`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `acp_konkurencja`
--
ALTER TABLE `acp_konkurencja`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT dla tabeli `acp_log`
--
ALTER TABLE `acp_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT dla tabeli `acp_log_serwery`
--
ALTER TABLE `acp_log_serwery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `acp_messages`
--
ALTER TABLE `acp_messages`
  MODIFY `m_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `acp_moduly`
--
ALTER TABLE `acp_moduly`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT dla tabeli `acp_moduly_akcje`
--
ALTER TABLE `acp_moduly_akcje`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT dla tabeli `acp_moduly_menu`
--
ALTER TABLE `acp_moduly_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT dla tabeli `acp_pluginy`
--
ALTER TABLE `acp_pluginy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT dla tabeli `acp_pluginy_pliki`
--
ALTER TABLE `acp_pluginy_pliki`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT dla tabeli `acp_serwery`
--
ALTER TABLE `acp_serwery`
  MODIFY `serwer_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `acp_serwery_baza`
--
ALTER TABLE `acp_serwery_baza`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT dla tabeli `acp_serwery_bledy`
--
ALTER TABLE `acp_serwery_bledy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `acp_serwery_cronjobs`
--
ALTER TABLE `acp_serwery_cronjobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `acp_serwery_cvary`
--
ALTER TABLE `acp_serwery_cvary`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `acp_serwery_gosetti`
--
ALTER TABLE `acp_serwery_gosetti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `acp_serwery_helpmenu`
--
ALTER TABLE `acp_serwery_helpmenu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `acp_serwery_helpmenu_komendy`
--
ALTER TABLE `acp_serwery_helpmenu_komendy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `acp_serwery_helpmenu_vip`
--
ALTER TABLE `acp_serwery_helpmenu_vip`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `acp_serwery_hextags`
--
ALTER TABLE `acp_serwery_hextags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `acp_serwery_hlstats`
--
ALTER TABLE `acp_serwery_hlstats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `acp_serwery_hlstats_top`
--
ALTER TABLE `acp_serwery_hlstats_top`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `acp_serwery_listaadminow`
--
ALTER TABLE `acp_serwery_listaadminow`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `acp_serwery_logs`
--
ALTER TABLE `acp_serwery_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `acp_serwery_logs_day`
--
ALTER TABLE `acp_serwery_logs_day`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `acp_serwery_logs_hour`
--
ALTER TABLE `acp_serwery_logs_hour`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `acp_serwery_logs_month`
--
ALTER TABLE `acp_serwery_logs_month`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `acp_serwery_mapy`
--
ALTER TABLE `acp_serwery_mapy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `acp_serwery_mapy_det`
--
ALTER TABLE `acp_serwery_mapy_det`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `acp_serwery_mapy_img`
--
ALTER TABLE `acp_serwery_mapy_img`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `acp_serwery_rangi`
--
ALTER TABLE `acp_serwery_rangi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `acp_serwery_regulamin`
--
ALTER TABLE `acp_serwery_regulamin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `acp_serwery_reklamy`
--
ALTER TABLE `acp_serwery_reklamy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `acp_serwery_tagi`
--
ALTER TABLE `acp_serwery_tagi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `acp_serwery_update`
--
ALTER TABLE `acp_serwery_update`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `acp_slots_serwery`
--
ALTER TABLE `acp_slots_serwery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `acp_sourceupdate`
--
ALTER TABLE `acp_sourceupdate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `acp_users`
--
ALTER TABLE `acp_users`
  MODIFY `user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT dla tabeli `acp_users_departament`
--
ALTER TABLE `acp_users_departament`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `acp_users_grupy`
--
ALTER TABLE `acp_users_grupy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT dla tabeli `acp_users_login_logs`
--
ALTER TABLE `acp_users_login_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `acp_users_notification`
--
ALTER TABLE `acp_users_notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `acp_uslugi`
--
ALTER TABLE `acp_uslugi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `acp_uslugi_code`
--
ALTER TABLE `acp_uslugi_code`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `acp_uslugi_rodzaje`
--
ALTER TABLE `acp_uslugi_rodzaje`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `acp_wgrywarka`
--
ALTER TABLE `acp_wgrywarka`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `acp_wpisy`
--
ALTER TABLE `acp_wpisy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT dla tabeli `acp_wpisy_kategorie`
--
ALTER TABLE `acp_wpisy_kategorie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `acp_wpisy_komentarze`
--
ALTER TABLE `acp_wpisy_komentarze`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `acp_zadania`
--
ALTER TABLE `acp_zadania`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `acp_zadania_com`
--
ALTER TABLE `acp_zadania_com`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `acp_zadania_platforma`
--
ALTER TABLE `acp_zadania_platforma`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT dla tabeli `acp_zadania_status`
--
ALTER TABLE `acp_zadania_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT dla tabeli `acp_zadania_todo`
--
ALTER TABLE `acp_zadania_todo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `acp_zadania_typ`
--
ALTER TABLE `acp_zadania_typ`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT dla tabeli `raport_opiekun`
--
ALTER TABLE `raport_opiekun`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `raport_serwer`
--
ALTER TABLE `raport_serwer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `rs_roundsound`
--
ALTER TABLE `rs_roundsound`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `rs_utwory`
--
ALTER TABLE `rs_utwory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `rs_vote`
--
ALTER TABLE `rs_vote`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
