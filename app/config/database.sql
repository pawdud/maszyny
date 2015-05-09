-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Czas wygenerowania: 09 May 2015, 08:53
-- Wersja serwera: 5.5.38
-- Wersja PHP: 5.4.38-1+deb.sury.org~precise+2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Baza danych: `maszyny`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `event`
--

CREATE TABLE IF NOT EXISTS `event` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `time_start` datetime NOT NULL COMMENT 'data rozpoczęcia eventu',
  `time_end` datetime NOT NULL COMMENT 'data zakończenia eventu',
  `user_id` int(10) unsigned NOT NULL COMMENT 'id użytkownika',
  `notice` varchar(500) DEFAULT NULL COMMENT 'komentarze użytkownika do eventu',
  `technology2part_id` int(10) NOT NULL COMMENT 'id powiązania technologi z częścią',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `technology2part_id` (`technology2part_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Zrzut danych tabeli `event`
--

INSERT INTO `event` (`id`, `time_start`, `time_end`, `user_id`, `notice`, `technology2part_id`) VALUES
(2, '2015-04-17 04:00:00', '2015-04-17 08:00:00', 3, NULL, 1),
(3, '2015-04-17 12:00:00', '2015-04-17 18:00:00', 4, NULL, 1),
(4, '2015-01-01 00:00:00', '2016-01-01 00:00:00', 3, 'To jest zdarzenie testowe', 1),
(5, '2015-04-21 21:00:00', '2015-04-21 22:45:00', 3, 'Spawanie', 1),
(6, '2015-04-21 22:00:00', '2015-04-21 22:45:00', 3, 'Gotowanie', 1),
(7, '2015-04-23 00:00:00', '2015-04-23 03:30:00', 3, 'Pilowanie', 4);

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `fabric`
--

CREATE TABLE IF NOT EXISTS `fabric` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fabric_category_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL COMMENT 'Id użytkownika który utworzył ten materiał',
  `code` char(100) NOT NULL COMMENT 'Kod materiału',
  `quantity` decimal(10,2) unsigned NOT NULL COMMENT 'Stan magazynowy',
  `fabric_unit_id` int(10) unsigned DEFAULT NULL COMMENT 'id jednostki',
  `name` varchar(500) NOT NULL,
  `time_updated` datetime DEFAULT NULL,
  `time_add` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Materiały' AUTO_INCREMENT=6 ;

--
-- Zrzut danych tabeli `fabric`
--

INSERT INTO `fabric` (`id`, `fabric_category_id`, `user_id`, `code`, `quantity`, `fabric_unit_id`, `name`, `time_updated`, `time_add`) VALUES
(1, 1, 3, 'GWO', 2.00, 4, 'Gwozdzie', '2015-03-21 08:49:30', '2015-03-21 08:43:20'),
(2, 1, 3, 'GWO30', 2.00, 3, 'Gwozdie 30', NULL, '2015-03-21 08:46:03'),
(3, 1, 3, 'GWO20', 3.21, 3, 'Gwozdzie', '2015-04-14 22:41:56', '2015-04-14 22:41:25'),
(4, 3, 3, 'DYB20', 2000.00, 5, 'Dyble', NULL, '2015-04-14 22:46:15'),
(5, 1, 3, 'BLACH_FAL', 5.00, 3, 'Blacha falista', '2015-05-08 22:32:16', '2015-04-23 00:01:28');

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `fabric2part`
--

CREATE TABLE IF NOT EXISTS `fabric2part` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `part_id` int(10) unsigned NOT NULL,
  `fabric_id` int(10) unsigned NOT NULL,
  `quantity` decimal(10,4) NOT NULL COMMENT 'Ilość',
  `time_updated` datetime DEFAULT NULL,
  `time_add` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `part_id` (`part_id`),
  KEY `fabric_id` (`fabric_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Powiązanie materiałów z częściami' AUTO_INCREMENT=16 ;

--
-- Zrzut danych tabeli `fabric2part`
--

INSERT INTO `fabric2part` (`id`, `part_id`, `fabric_id`, `quantity`, `time_updated`, `time_add`) VALUES
(1, 3, 1, 1.0000, NULL, '0000-00-00 00:00:00'),
(5, 3, 2, 20.0000, NULL, '0000-00-00 00:00:00'),
(6, 5, 5, 0.2000, NULL, '0000-00-00 00:00:00'),
(7, 2, 1, 50.0000, NULL, '0000-00-00 00:00:00'),
(8, 3, 3, 20.0000, NULL, '0000-00-00 00:00:00'),
(9, 3, 5, 15.0000, NULL, '0000-00-00 00:00:00'),
(10, 3, 4, 7.0000, NULL, '0000-00-00 00:00:00'),
(11, 6, 3, 20.0000, NULL, '0000-00-00 00:00:00'),
(13, 7, 1, 10.0000, NULL, '0000-00-00 00:00:00'),
(14, 7, 5, 5.0000, NULL, '0000-00-00 00:00:00'),
(15, 6, 5, 6.0000, NULL, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `fabricorder`
--

CREATE TABLE IF NOT EXISTS `fabricorder` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `fabric2part_id` int(10) unsigned NOT NULL COMMENT 'id_part z tabeli fabric2part',
  `quantity` decimal(10,2) NOT NULL,
  `status_id` int(10) unsigned NOT NULL COMMENT '0 - oczekujace 5 - zatwierdzone 9 - anulowane',
  PRIMARY KEY (`id`),
  KEY `fabric2part_id` (`fabric2part_id`),
  KEY `status_id` (`status_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='zapotrzebowanie na materiały do projektu' AUTO_INCREMENT=14 ;

--
-- Zrzut danych tabeli `fabricorder`
--

INSERT INTO `fabricorder` (`id`, `fabric2part_id`, `quantity`, `status_id`) VALUES
(10, 6, 15.00, 5),
(11, 7, 5.00, 0),
(12, 14, 5.00, 5),
(13, 15, 6.00, 0);

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `fabric_category`
--

CREATE TABLE IF NOT EXISTS `fabric_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(500) NOT NULL,
  `time_updated` datetime DEFAULT NULL,
  `time_add` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Kategorie materiałów' AUTO_INCREMENT=5 ;

--
-- Zrzut danych tabeli `fabric_category`
--

INSERT INTO `fabric_category` (`id`, `name`, `time_updated`, `time_add`) VALUES
(1, 'Blachy', '2015-04-10 21:00:18', '2015-04-10 21:00:18'),
(3, 'Sruby', NULL, '2015-04-10 21:40:37'),
(4, 'Folie', NULL, '2015-04-14 22:52:06');

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `fabric_unit`
--

CREATE TABLE IF NOT EXISTS `fabric_unit` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(500) NOT NULL,
  `unit` char(20) NOT NULL COMMENT 'Symbol np.: ("m3", "m2", "mb","kg")',
  `scale` tinyint(1) unsigned NOT NULL COMMENT 'Precyzja (ile miejsc po przecinku)',
  `time_updated` datetime DEFAULT NULL,
  `time_add` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Jednostki materiałów' AUTO_INCREMENT=6 ;

--
-- Zrzut danych tabeli `fabric_unit`
--

INSERT INTO `fabric_unit` (`id`, `name`, `unit`, `scale`, `time_updated`, `time_add`) VALUES
(3, 'metr biezacy', 'mb', 2, '2015-04-10 22:53:57', '2015-04-10 22:43:29'),
(4, 'centymetr', 'cm', 0, NULL, '2015-04-10 22:44:43'),
(5, 'sztuka', 'szt', 0, NULL, '2015-04-14 22:38:04');

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `part`
--

CREATE TABLE IF NOT EXISTS `part` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `project_id` int(10) unsigned NOT NULL COMMENT 'Id projektu do którego należy ten materiał',
  `user_id` int(10) unsigned NOT NULL COMMENT 'Id użytkownika który utworzył ten materiał',
  `name` varchar(500) NOT NULL,
  `is_drawing` tinyint(1) DEFAULT NULL COMMENT 'Czy wydrukowano rysunek',
  `is_completed` tinyint(1) DEFAULT NULL COMMENT 'Czy część jest gotowa',
  `quantity` int(10) unsigned DEFAULT NULL COMMENT 'Ilość',
  `time_updated` datetime DEFAULT NULL,
  `time_add` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`),
  KEY `user_id` (`user_id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Cześci tworzące projekt' AUTO_INCREMENT=8 ;

--
-- Zrzut danych tabeli `part`
--

INSERT INTO `part` (`id`, `parent_id`, `project_id`, `user_id`, `name`, `is_drawing`, `is_completed`, `quantity`, `time_updated`, `time_add`) VALUES
(2, 0, 8, 3, 'Pret', 1, 1, NULL, '2015-03-20 21:25:07', '2015-03-20 21:24:27'),
(3, 2, 8, 3, 'Noga', 1, 1, NULL, '2015-03-26 00:01:00', '2015-03-25 21:35:52'),
(5, 0, 9, 3, 'Silnik', 1, 0, NULL, NULL, '2015-04-22 23:57:01'),
(6, 5, 9, 3, 'Cylinder', 1, 0, NULL, NULL, '2015-04-22 23:58:32'),
(7, 0, 10, 3, 'nowa część', 0, 0, NULL, NULL, '2015-05-08 01:12:42');

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `project`
--

CREATE TABLE IF NOT EXISTS `project` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `name` varchar(500) NOT NULL,
  `is_drawing` tinyint(1) DEFAULT NULL COMMENT 'Czy załadowano rysunek',
  `time_updated` datetime DEFAULT NULL,
  `time_add` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Projekty' AUTO_INCREMENT=11 ;

--
-- Zrzut danych tabeli `project`
--

INSERT INTO `project` (`id`, `user_id`, `name`, `is_drawing`, `time_updated`, `time_add`) VALUES
(8, 3, 'Szlifierka', 0, NULL, '2015-03-18 20:28:36'),
(9, 3, 'Samochod', 1, '2015-04-23 21:28:56', '2015-03-31 21:44:03'),
(10, 3, 'Samolot', NULL, NULL, '2015-04-14 22:49:52');

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `statusy`
--

CREATE TABLE IF NOT EXISTS `statusy` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Zrzut danych tabeli `statusy`
--

INSERT INTO `statusy` (`id`, `name`) VALUES
(0, 'oczekujące'),
(5, 'zaakceptowane'),
(9, 'anulowane');

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `technology`
--

CREATE TABLE IF NOT EXISTS `technology` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(500) NOT NULL,
  `time_updated` datetime DEFAULT NULL,
  `time_add` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Technologie (Frezowanie, Spawanie, Zgrzewanie itd itp)' AUTO_INCREMENT=4 ;

--
-- Zrzut danych tabeli `technology`
--

INSERT INTO `technology` (`id`, `name`, `time_updated`, `time_add`) VALUES
(1, 'Spawanie', NULL, '2015-04-01 21:22:58'),
(2, 'Wiercenie', NULL, '2015-04-14 22:48:43'),
(3, 'Wiercenie', NULL, '2015-04-14 22:49:02');

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `technology2part`
--

CREATE TABLE IF NOT EXISTS `technology2part` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `part_id` int(10) unsigned NOT NULL,
  `technology_id` int(10) unsigned NOT NULL,
  `is_completed` tinyint(1) DEFAULT NULL COMMENT 'Czy technologia została wykonana',
  PRIMARY KEY (`id`),
  KEY `part_id` (`part_id`),
  KEY `technology_id` (`technology_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Zrzut danych tabeli `technology2part`
--

INSERT INTO `technology2part` (`id`, `part_id`, `technology_id`, `is_completed`) VALUES
(1, 3, 1, 0),
(2, 2, 2, 1),
(3, 2, 2, 0),
(4, 5, 2, 0),
(5, 6, 1, 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` char(100) NOT NULL,
  `salt` varchar(50) NOT NULL,
  `role` char(50) NOT NULL,
  `status` tinyint(3) unsigned DEFAULT NULL COMMENT 'Status użytkownika (aktywny - 1, usunięty - 10)',
  `name` varchar(500) DEFAULT NULL COMMENT 'Imię',
  `surname` varchar(500) DEFAULT NULL COMMENT 'Nazwisko',
  `time_updated` datetime DEFAULT NULL,
  `time_add` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `time_updated` (`time_updated`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Uzytkownicy (administratorzy, pracownicy itd)' AUTO_INCREMENT=7 ;

--
-- Zrzut danych tabeli `user`
--

INSERT INTO `user` (`id`, `email`, `password`, `salt`, `role`, `status`, `name`, `surname`, `time_updated`, `time_add`) VALUES
(3, 'pawel.dudka@gazeta.pl', 'pxpCsikGf3UsldE3j/ThjDOpU0/AszStY4wlUP7QOfwlKvNXmZu6X+RVbtMgcwHRDdGsbq+x2pBjpYurR3kaag==', '526bd8bfe7556dd36056c9e2da2bb8298e5f1b3b', 'ROLE_ADMIN', NULL, 'Pawel', 'Dudka', NULL, '2015-03-13 22:37:05'),
(4, 'hubert@tomedia.pl', 'hmojpK4u5f15ZrUMIXXYYCHMW3pdpOU9Z+GKGWZuSrhbfSw6K0KuEpynmT6bk03De6Bfu7onV6Z/fk/NupT9Mw==', '367f408ce3e5ca8c91a7579c222d821799ecbb7d', 'ROLE_ADMIN', NULL, 'Hubert', 'Osipowicz', NULL, '2015-03-18 22:09:06'),
(5, 'pawel@slsystems.pl', 'hFJa+XTr2DY/efGmxG56gK1ba1ZrW8Nd33r5CKFJdyaQRgfFYBIIyeH/yAVnkV/swjM7M2N3qwUrVGYRchURkQ==', '201d08edb070fc1d486ccbc40d6748a03aa0f936', 'ROLE_ADMIN', NULL, 'Pawel', 'Dudek', NULL, '2015-04-15 21:56:38'),
(6, 'pawel.dudkiewicz@gazeta.pl', 'lwkBIsssJdxyiZYVCbnGNAfm1qMXfJpm70maoUOjgRThqGQdPB+JlH+ooxA+KYNa3Pl38whDeiYG2kIsLmkmOg==', '14c8ede4d7dd0669963a9af1ef63f3fc90411004', 'ROLE_EMPLOYEE', NULL, 'Pawel', 'Dudkiewicz', NULL, '2015-04-16 21:08:59');

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `event`
--
ALTER TABLE `event`
  ADD CONSTRAINT `event_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `event_ibfk_2` FOREIGN KEY (`technology2part_id`) REFERENCES `technology2part` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `fabric`
--
ALTER TABLE `fabric`
  ADD CONSTRAINT `fabric_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Ograniczenia dla tabeli `fabric2part`
--
ALTER TABLE `fabric2part`
  ADD CONSTRAINT `fabric2part_ibfk_1` FOREIGN KEY (`part_id`) REFERENCES `part` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fabric2part_ibfk_2` FOREIGN KEY (`fabric_id`) REFERENCES `fabric` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `fabricorder`
--
ALTER TABLE `fabricorder`
  ADD CONSTRAINT `fabricorder_ibfk_2` FOREIGN KEY (`status_id`) REFERENCES `statusy` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fabricorder_ibfk_1` FOREIGN KEY (`fabric2part_id`) REFERENCES `fabric2part` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `part`
--
ALTER TABLE `part`
  ADD CONSTRAINT `part_ibfk_4` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  ADD CONSTRAINT `part_ibfk_5` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
