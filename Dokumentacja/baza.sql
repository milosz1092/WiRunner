SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE IF NOT EXISTS `aktywnosci` (
  `id_aktywnosci` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nr_sportu` tinyint(3) unsigned NOT NULL,
  `nr_uzytkownika` int(10) unsigned NOT NULL,
  `nazwa_treningu` varchar(45) DEFAULT NULL,
  `opis` varchar(45) DEFAULT NULL,
  `tempo` decimal(4,2) DEFAULT NULL,
  `dystans` decimal(6,3) DEFAULT NULL,
  `data_treningu` date NOT NULL,
  `data_dodania` datetime NOT NULL,
  PRIMARY KEY (`id_aktywnosci`),
  KEY `nr_uzytkownika_idx` (`nr_uzytkownika`),
  KEY `nr_sportu_idx` (`nr_sportu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `cele` (
  `id_celu` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nr_sportu` tinyint(3) unsigned DEFAULT NULL,
  `nr_usera` int(10) unsigned NOT NULL,
  `nazwa_celu` varchar(45) DEFAULT NULL,
  `opis` varchar(45) DEFAULT NULL,
  `data_poczatku` date DEFAULT NULL,
  `data_konca` date DEFAULT NULL,
  `data_dodania` datetime NOT NULL,
  `ilosc_km` decimal(6,3) unsigned DEFAULT NULL,
  PRIMARY KEY (`id_celu`),
  KEY `nr_usera_idx` (`nr_usera`),
  KEY `nr_sportu_idx` (`nr_sportu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `komentarze_do_aktywnosci` (
  `id_komentarza` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nr_aktywnosci` int(10) unsigned NOT NULL,
  `nr_uzytkownika` int(10) unsigned NOT NULL,
  `tresc` varchar(245) NOT NULL,
  `data_dodania` datetime NOT NULL,
  PRIMARY KEY (`id_komentarza`),
  KEY `nr_uzytkownika_idx` (`nr_uzytkownika`),
  KEY `nr_aktywnosci_idx` (`nr_aktywnosci`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `komentarze_do_profilu` (
  `id_komentarza` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nr_uzytkownika` int(10) unsigned NOT NULL,
  `nr_profilu` int(10) unsigned NOT NULL,
  `tresc` varchar(245) NOT NULL,
  `data_dodania` datetime NOT NULL,
  PRIMARY KEY (`id_komentarza`),
  KEY `nr_uzytkownika_idx` (`nr_uzytkownika`),
  KEY `nr_profilu_idx` (`nr_profilu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `polubienia` (
  `nr_uzytkownika` int(10) unsigned NOT NULL,
  `nr_aktywnosci` int(10) unsigned NOT NULL,
  `data_polubienia` datetime NOT NULL,
  PRIMARY KEY (`nr_uzytkownika`,`nr_aktywnosci`),
  KEY `nr_aktywnosci_idx` (`nr_aktywnosci`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `rangi` (
  `id_rangi` int(1) unsigned NOT NULL AUTO_INCREMENT,
  `ranga` varchar(20) NOT NULL,
  PRIMARY KEY (`id_rangi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `relacje_uzytkownikow` (
  `nr_pierwszego` int(10) unsigned NOT NULL,
  `nr_drugiego` int(10) unsigned NOT NULL,
  `nr_rodzaju` int(1) unsigned NOT NULL,
  `data_dodania` datetime NOT NULL,
  PRIMARY KEY (`nr_pierwszego`,`nr_drugiego`),
  KEY `nr_drugiego_idx` (`nr_drugiego`),
  KEY `nr_relacji_idx` (`nr_rodzaju`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `rodzaje_relacji` (
  `id_relacji` int(1) unsigned NOT NULL AUTO_INCREMENT,
  `relacja` varchar(20) NOT NULL,
  PRIMARY KEY (`id_relacji`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rywalizacje` (
  `id_rywalizacji` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `nazwa_rywalizacji` varchar(45) NOT NULL,
  `opis_rywalizacji` varchar(1445) DEFAULT NULL,
  `nr_sportu` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `data_startu` date NOT NULL,
  `data_konca` date NOT NULL,
  `data_dodania` datetime NOT NULL,
  PRIMARY KEY (`id_rywalizacji`),
  KEY `nr_sportu_idx` (`nr_sportu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `sporty` (
  `id_sportu` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `nazwa_sportu` varchar(45) NOT NULL,
  PRIMARY KEY (`id_sportu`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

CREATE TABLE IF NOT EXISTS `trasy` (
  `id_trasy` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nr_uzytkownika` int(10) unsigned DEFAULT NULL,
  `nazwa_trasy` varchar(36) NOT NULL,
  `dlugosc_trasy` decimal(8,3) unsigned DEFAULT NULL,
  `przebieg_trasy` varchar(245) DEFAULT NULL,
  `punkty_trasy` text,
  `data_dodania` datetime NOT NULL,
  PRIMARY KEY (`id_trasy`),
  KEY `nr_uzytkownika_idx` (`nr_uzytkownika`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

CREATE TABLE IF NOT EXISTS `uzytkownicy` (
  `id_uzytkownika` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(45) NOT NULL,
  `haslo` varchar(45) NOT NULL,
  `data_rejestracji` datetime NOT NULL,
  `nr_rangi` int(1) unsigned NOT NULL DEFAULT '1' COMMENT 'Rangi w systemie:',
  `imie` varchar(36) DEFAULT NULL,
  `nazwisko` varchar(45) DEFAULT NULL,
  `waga` tinyint(3) unsigned DEFAULT NULL,
  `wzrost` tinyint(3) unsigned DEFAULT NULL,
  `plec` set('m','k') DEFAULT NULL,
  `miejscowosc` varchar(45) DEFAULT NULL,
  `motto` varchar(245) DEFAULT NULL,
  `ostatnie_logowanie` datetime DEFAULT NULL,
  `potwierdzony_mail` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`id_uzytkownika`),
  KEY `nr_rangi_idx` (`nr_rangi`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

CREATE TABLE IF NOT EXISTS `wiadomosci` (
  `id_wiadomosci` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nr_nadawcy` int(10) unsigned NOT NULL,
  `nr_adresata` int(10) unsigned NOT NULL,
  `temat` varchar(45) DEFAULT NULL,
  `tresc` varchar(2000) DEFAULT NULL,
  `data_nadania` datetime NOT NULL,
  `data_przeczytania` datetime DEFAULT NULL,
  PRIMARY KEY (`id_wiadomosci`),
  KEY `nr_nadawcy_idx` (`nr_nadawcy`),
  KEY `nr_odbiorcy_idx` (`nr_adresata`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `wspolrzedne` (
  `nr_usera` int(10) unsigned NOT NULL,
  `szerokosc` decimal(8,5) NOT NULL,
  `dlugosc` decimal(8,5) NOT NULL,
  `data_ustawienia` datetime NOT NULL,
  PRIMARY KEY (`nr_usera`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `zadania_resetu_hasla` (
  `id_zadania` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nr_uzytkownika` int(10) unsigned NOT NULL,
  `data_zadania` datetime NOT NULL,
  PRIMARY KEY (`id_zadania`),
  KEY `id_uzytkownika_idx` (`nr_uzytkownika`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `zgloszenia_do_rywalizacji` (
  `nr_rywalizacji` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `nr_usera` int(10) unsigned NOT NULL,
  `data_zapisania` datetime DEFAULT NULL,
  PRIMARY KEY (`nr_rywalizacji`,`nr_usera`),
  KEY `nr_rywalizacji_idx` (`nr_rywalizacji`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


ALTER TABLE `wspolrzedne`
  ADD CONSTRAINT `nr_usera` FOREIGN KEY (`nr_usera`) REFERENCES `uzytkownicy` (`id_uzytkownika`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
