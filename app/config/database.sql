-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Czas wygenerowania: 17 Kwi 2015, 22:09
-- Wersja serwera: 5.5.38
-- Wersja PHP: 5.4.38-1+deb.sury.org~precise+2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Baza danych: `maszyny`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `calendar_settings`
--

CREATE TABLE IF NOT EXISTS `calendar_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `event_categories`
--

CREATE TABLE IF NOT EXISTS `event_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `color` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Materiały' AUTO_INCREMENT=5 ;

--
-- Zrzut danych tabeli `fabric`
--

INSERT INTO `fabric` (`id`, `fabric_category_id`, `user_id`, `code`, `quantity`, `fabric_unit_id`, `name`, `time_updated`, `time_add`) VALUES
(1, 1, 3, 'GWO', 10.00, 4, 'Gwoździe 20', '2015-03-21 08:49:30', '2015-03-21 08:43:20'),
(2, 1, 3, 'GWO30', 2.00, NULL, 'Gwozdie 30', NULL, '2015-03-21 08:46:03'),
(3, 1, 3, 'GWO20', 3.21, 3, 'Gwoździe', '2015-04-14 22:41:56', '2015-04-14 22:41:25'),
(4, 3, 3, 'DYB20', 2000.00, 5, 'Dyble', NULL, '2015-04-14 22:46:15');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Powiązanie materiałów z częściami' AUTO_INCREMENT=4 ;

--
-- Zrzut danych tabeli `fabric2part`
--

INSERT INTO `fabric2part` (`id`, `part_id`, `fabric_id`, `quantity`, `time_updated`, `time_add`) VALUES
(1, 3, 1, 1.0000, NULL, '0000-00-00 00:00:00'),
(2, 2, 1, 2.0000, NULL, '0000-00-00 00:00:00'),
(3, 4, 1, 5.0000, NULL, '0000-00-00 00:00:00');

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
(3, 'Śruby', NULL, '2015-04-10 21:40:37'),
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
(3, 'metr bieżący', 'mb', 2, '2015-04-10 22:53:57', '2015-04-10 22:43:29'),
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
  `time_updated` datetime DEFAULT NULL,
  `time_add` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`),
  KEY `user_id` (`user_id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Cześci tworzące projekt' AUTO_INCREMENT=5 ;

--
-- Zrzut danych tabeli `part`
--

INSERT INTO `part` (`id`, `parent_id`, `project_id`, `user_id`, `name`, `is_drawing`, `is_completed`, `time_updated`, `time_add`) VALUES
(2, 0, 8, 3, 'Pręt d', 1, 1, '2015-03-20 21:25:07', '2015-03-20 21:24:27'),
(3, 2, 8, 3, 'Noga', 1, 1, '2015-03-26 00:01:00', '2015-03-25 21:35:52'),
(4, 3, 8, 3, 'Przykładowa część', 0, 1, '2015-04-01 22:26:41', '2015-04-01 22:21:42');

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
(9, 3, 'Samochód', 1, NULL, '2015-03-31 21:44:03'),
(10, 3, 'Samolot', NULL, NULL, '2015-04-14 22:49:52');

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
  PRIMARY KEY (`id`),
  KEY `part_id` (`part_id`),
  KEY `technology_id` (`technology_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Zrzut danych tabeli `technology2part`
--

INSERT INTO `technology2part` (`id`, `part_id`, `technology_id`) VALUES
(1, 3, 1);

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

INSERT INTO `user` (`id`, `email`, `password`, `salt`, `role`, `name`, `surname`, `time_updated`, `time_add`) VALUES
(3, 'pawel.dudka@gazeta.pl', 'pxpCsikGf3UsldE3j/ThjDOpU0/AszStY4wlUP7QOfwlKvNXmZu6X+RVbtMgcwHRDdGsbq+x2pBjpYurR3kaag==', '526bd8bfe7556dd36056c9e2da2bb8298e5f1b3b', 'ADMIN', 'Paweł', 'Dudka', NULL, '2015-03-13 22:37:05'),
(4, 'hubert@tomedia.pl', 'hmojpK4u5f15ZrUMIXXYYCHMW3pdpOU9Z+GKGWZuSrhbfSw6K0KuEpynmT6bk03De6Bfu7onV6Z/fk/NupT9Mw==', '367f408ce3e5ca8c91a7579c222d821799ecbb7d', 'ADMIN', 'Hubert', 'Osipowicz', NULL, '2015-03-18 22:09:06'),
(5, 'pawel@slsystems.pl', 'HK0PXbJTsWJusHKxTJr60X6FtywDRZDnX/1kJaYu1nGxrqjYjzUrkV1INdDaZPhi7IwtG+Q0+ktlzfbHj5U9Ag==', '869ec324a67bb27bc7f7f86902fab461b03326e6', 'ADMIN', 'Paweł', 'Dudek', NULL, '2015-04-15 21:56:38'),
(6, 'pawel.dudkiewicz@gazeta.pl', 'GHB30xKxMaq0MHK0C305FHW4lvo9Z+VEzyNQxM7viREQqGPzp2p3vYp/qGM4ttKSj688bFL+hEeS5RMi9NCaBA==', '03bb197c7ddf060e54177ebc2db756561c8e2252', 'EMPLOYEE', 'Paweł', 'Dudkiewicz', NULL, '2015-04-16 21:08:59');

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `fabric`
--
ALTER TABLE `fabric`
  ADD CONSTRAINT `fabric_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Ograniczenia dla tabeli `fabric2part`
--
ALTER TABLE `fabric2part`
  ADD CONSTRAINT `fabric2part_ibfk_3` FOREIGN KEY (`part_id`) REFERENCES `part` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fabric2part_ibfk_4` FOREIGN KEY (`fabric_id`) REFERENCES `fabric` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `part`
--
ALTER TABLE `part`
  ADD CONSTRAINT `part_ibfk_4` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  ADD CONSTRAINT `part_ibfk_5` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
