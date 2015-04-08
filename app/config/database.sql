-- phpMyAdmin SQL Dump
-- version 4.0.9deb1.precise~ppa.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Czas wygenerowania: 08 Kwi 2015, 23:41
-- Wersja serwera: 5.5.35-0ubuntu0.12.04.2
-- Wersja PHP: 5.5.11-3+deb.sury.org~precise+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Baza danych: `maszyny`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `event`
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Zrzut danych tabeli `event`
--

INSERT INTO `event` (`id`, `time_start`, `time_end`, `user_id`, `notice`, `technology2part_id`) VALUES
(1, '2015-04-07 04:01:01', '2015-04-07 07:06:00', 4, 'jakieś uwagi', 1),
(2, '2015-04-08 03:08:00', '2015-04-08 11:00:00', 4, 'żadnych uwag', 1),
(3, '2015-04-01 05:00:00', '2015-04-01 12:00:00', 4, 'poprzedni tydzien', 1),
(4, '2015-03-02 03:00:00', '2015-03-02 11:00:00', 4, 'marcowy event', 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `fabric`
--

CREATE TABLE IF NOT EXISTS `fabric` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL COMMENT 'Id użytkownika który utworzył ten materiał',
  `code` char(100) NOT NULL COMMENT 'Kod materiału',
  `quantity` decimal(10,2) unsigned NOT NULL COMMENT 'Stan magazynowy',
  `unit_id` int(10) unsigned DEFAULT NULL COMMENT 'id jednostki',
  `name` varchar(500) NOT NULL,
  `time_updated` datetime DEFAULT NULL,
  `time_add` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Materiały' AUTO_INCREMENT=3 ;

--
-- Zrzut danych tabeli `fabric`
--

INSERT INTO `fabric` (`id`, `user_id`, `code`, `quantity`, `unit_id`, `name`, `time_updated`, `time_add`) VALUES
(1, 3, 'GWO', 10.00, NULL, 'Gwoździe 20', '2015-03-21 08:49:30', '2015-03-21 08:43:20'),
(2, 3, 'GWO30', 2.00, NULL, 'Gwozdie 30', NULL, '2015-03-21 08:46:03');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `fabric2part`
--

CREATE TABLE IF NOT EXISTS `fabric2part` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `part_id` int(10) unsigned NOT NULL,
  `fabric_id` int(10) unsigned NOT NULL,
  `time_updated` datetime DEFAULT NULL,
  `time_add` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `part_id` (`part_id`),
  KEY `fabric_id` (`fabric_id`),
  KEY `part_id_2` (`part_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Powiązanie materiałów z częściami' AUTO_INCREMENT=3 ;

--
-- Zrzut danych tabeli `fabric2part`
--

INSERT INTO `fabric2part` (`id`, `part_id`, `fabric_id`, `time_updated`, `time_add`) VALUES
(1, 4, 1, NULL, '0000-00-00 00:00:00'),
(2, 4, 2, NULL, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `part`
--

CREATE TABLE IF NOT EXISTS `part` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `project_id` int(10) unsigned NOT NULL COMMENT 'Id projektu do którego należy ten materiał',
  `user_id` int(10) unsigned NOT NULL COMMENT 'Id użytkownika który utworzył ten materiał',
  `is_drawing` tinyint(1) DEFAULT NULL COMMENT 'Czy rysunek jest wczytany',
  `is_completed` tinyint(1) DEFAULT NULL,
  `name` varchar(500) NOT NULL,
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

INSERT INTO `part` (`id`, `parent_id`, `project_id`, `user_id`, `is_drawing`, `is_completed`, `name`, `time_updated`, `time_add`) VALUES
(2, 0, 8, 3, NULL, NULL, 'Pręt d', '2015-03-20 21:25:07', '2015-03-20 21:24:27'),
(3, 0, 8, 3, NULL, NULL, 'Noga', '2015-03-26 00:01:00', '2015-03-25 21:35:52'),
(4, 3, 8, 3, NULL, NULL, 'Listwa', '2015-03-25 23:29:32', '2015-03-25 21:36:06');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `project`
--

CREATE TABLE IF NOT EXISTS `project` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `name` varchar(500) NOT NULL,
  `time_updated` datetime DEFAULT NULL,
  `time_add` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Projekty' AUTO_INCREMENT=10 ;

--
-- Zrzut danych tabeli `project`
--

INSERT INTO `project` (`id`, `user_id`, `name`, `time_updated`, `time_add`) VALUES
(8, 3, 'Szlifierka', NULL, '2015-03-18 20:28:36'),
(9, 3, 'nowy projekt', NULL, '2015-04-02 00:59:29');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `technology`
--

CREATE TABLE IF NOT EXISTS `technology` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(500) NOT NULL,
  `time_updated` datetime DEFAULT NULL,
  `time_add` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Technologie (Frezowanie, Spawanie, Zgrzewanie itd itp)' AUTO_INCREMENT=2 ;

--
-- Zrzut danych tabeli `technology`
--

INSERT INTO `technology` (`id`, `name`, `time_updated`, `time_add`) VALUES
(1, 'Proces technologiczny 1', '2015-03-26 21:56:43', '2015-03-23 08:29:04');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `technology2part`
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
(1, 4, 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user`
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
  ADD CONSTRAINT `fabric2part_ibfk_3` FOREIGN KEY (`part_id`) REFERENCES `part` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fabric2part_ibfk_4` FOREIGN KEY (`fabric_id`) REFERENCES `fabric` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `part`
--
ALTER TABLE `part`
  ADD CONSTRAINT `part_ibfk_4` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  ADD CONSTRAINT `part_ibfk_5` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Ograniczenia dla tabeli `project`
--
ALTER TABLE `project`
  ADD CONSTRAINT `project_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `technology2part`
--
ALTER TABLE `technology2part`
  ADD CONSTRAINT `technology2part_ibfk_6` FOREIGN KEY (`part_id`) REFERENCES `part` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `technology2part_ibfk_7` FOREIGN KEY (`technology_id`) REFERENCES `technology` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
