-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Czas wygenerowania: 20 Mar 2015, 21:46
-- Wersja serwera: 5.5.38
-- Wersja PHP: 5.4.38-1+deb.sury.org~precise+2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Baza danych: `maszyny`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `fabric`
--

CREATE TABLE IF NOT EXISTS `fabric` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL COMMENT 'Id użytkownika który utworzył ten materiał',
  `name` varchar(500) NOT NULL,
  `time_updated` datetime NOT NULL,
  `time_add` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Materiały' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Powiązanie materiałów z częściami' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla 
--

CREATE TABLE IF NOT EXISTS `part` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `project_id` int(10) unsigned NOT NULL COMMENT 'Id projektu do którego należy ten materiał',
  `user_id` int(10) unsigned NOT NULL COMMENT 'Id użytkownika który utworzył ten materiał',
  `name` varchar(500) NOT NULL,
  `time_updated` datetime DEFAULT NULL,
  `time_add` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`),
  KEY `user_id` (`user_id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Cześci tworzące projekt' AUTO_INCREMENT=3 ;

--
-- Zrzut danych tabeli `part`
--

INSERT INTO `part` (`id`, `parent_id`, `project_id`, `user_id`, `name`, `time_updated`, `time_add`) VALUES
(2, NULL, 8, 3, 'Pręt dd', '2015-03-20 21:25:07', '2015-03-20 21:24:27');

-- --------------------------------------------------------

--
-- Struktura tabeli dla  `project`
--

CREATE TABLE IF NOT EXISTS `project` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `name` varchar(500) NOT NULL,
  `time_updated` datetime DEFAULT NULL,
  `time_add` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Projekty' AUTO_INCREMENT=9 ;

--
-- Zrzut danych tabeli `project`
--

INSERT INTO `project` (`id`, `user_id`, `name`, `time_updated`, `time_add`) VALUES
(8, 3, 'Szlifierka', NULL, '2015-03-18 20:28:36');

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
-- Zrzut danych tabeli `user`
--

INSERT INTO `user` (`id`, `email`, `password`, `salt`, `role`, `name`, `surname`, `time_updated`, `time_add`) VALUES
(3, 'pawel.dudka@gazeta.pl', 'M1T2YdCq2dz/gKvVWaF9bWBmzHnBSX4EF8csBA3QLqfLwCuL7w8jOXjs+H6lz1AJURGLYGdP8NOUVUK0k13Gog==', '04fe46dcddcae717b9c8cbf2a463622e605ea439', 'ADMIN', 'Pablo', 'Picasso', NULL, '2015-03-13 22:37:05'),
(4, 'hubert@tomedia.pl', 'hmojpK4u5f15ZrUMIXXYYCHMW3pdpOU9Z+GKGWZuSrhbfSw6K0KuEpynmT6bk03De6Bfu7onV6Z/fk/NupT9Mw==', '367f408ce3e5ca8c91a7579c222d821799ecbb7d', 'ADMIN', 'Hubert', 'Osipowicz', NULL, '2015-03-18 22:09:06');

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `fabric`
--
ALTER TABLE `fabric`
  ADD CONSTRAINT `fabric_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `part` (`user_id`);

--
-- Ograniczenia dla tabeli `fabric2part`
--
ALTER TABLE `fabric2part`
  ADD CONSTRAINT `fabric2part_ibfk_1` FOREIGN KEY (`part_id`) REFERENCES `part` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fabric2part_ibfk_2` FOREIGN KEY (`fabric_id`) REFERENCES `fabric` (`id`) ON DELETE CASCADE;

--
-- Ograniczenia dla tabeli `part`
--
ALTER TABLE `part`
  ADD CONSTRAINT `part_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `part_ibfk_3` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`) ON DELETE CASCADE;

--
-- Ograniczenia dla tabeli `project`
--
ALTER TABLE `project`
  ADD CONSTRAINT `project_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

