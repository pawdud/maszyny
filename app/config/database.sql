-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Czas wygenerowania: 15 Kwi 2015, 21:29
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

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `fabric2part`
--

CREATE TABLE IF NOT EXISTS `fabric2part` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `part_id` int(10) unsigned NOT NULL,
  `fabric_id` int(10) unsigned NOT NULL,
  `time_updated` datetime DEFAULT NULL,
  `time_add` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `part_id` (`part_id`),
  KEY `fabric_id` (`fabric_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Powiązanie materiałów z częściami' AUTO_INCREMENT=4 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Uzytkownicy (administratorzy, pracownicy itd)' AUTO_INCREMENT=5 ;

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
