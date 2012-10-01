-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Mar 31 Juillet 2012 à 17:33
-- Version du serveur: 5.1.44
-- Version de PHP: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `Jack`
--

-- --------------------------------------------------------

--
-- Structure de la table `commentaire_statut`
--

CREATE TABLE IF NOT EXISTS `commentaire_statut` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_statut` int(11) NOT NULL,
  `auteur` varchar(20) NOT NULL,
  `commentaire` text NOT NULL,
  `date` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

--
-- Contenu de la table `commentaire_statut`
--

INSERT INTO `commentaire_statut` (`id`, `id_statut`, `auteur`, `commentaire`, `date`) VALUES
(1, 5, 'admin', 'et ouai ta vu !!', 1308141678),
(2, 5, 'admin', 'oh yeah', 1308142443),
(10, 1, 'admin', 'pti con va', 1308143489),
(9, 1, 'admin', 'ahahah lolant !!!', 1308143476),
(8, 1, 'admin', 'qwerty', 1308143466),
(11, 6, 'admin', 'nani ?', 1308214372),
(12, 6, 'admin', 'why ? ', 1308214378),
(13, 6, 'admin', 'poupipoupippouuuuuu', 1308214393),
(14, 6, 'admin', 'are you mad ? !!\r\n', 1308214405),
(15, 6, 'admin', 'ohhuuuu', 1308214868),
(16, 6, 'admin', 'prout', 1308214876),
(17, 6, 'admin', 'yahou\r\n', 1316710290),
(18, 6, 'admin', 'nani', 1316710299),
(19, 6, 'admin', 'oO', 1316710306),
(20, 5, 'admin', 'euh tg pour voir oOoOoOo hihi', 1316710341),
(21, 5, 'admin', 'adadadad', 1316712926),
(22, 5, 'admin', 'adadadad', 1316712944),
(23, 5, 'admin', 'dddd', 1316712949),
(24, 7, 'admin', 'ok', 1316717158),
(25, 8, 'admin', 'kqsdqsd', 1316721691),
(26, 8, 'admin', 'qsdqsd', 1316721694),
(27, 9, 'admin', 'qsdqsdqsd', 1316878912);

-- --------------------------------------------------------

--
-- Structure de la table `evenement`
--

CREATE TABLE IF NOT EXISTS `evenement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titre` text NOT NULL,
  `auteur` text NOT NULL,
  `contenu` text NOT NULL,
  `date` varchar(20) NOT NULL,
  `date_post` bigint(20) NOT NULL,
  `type_event` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=36 ;

--
-- Contenu de la table `evenement`
--

INSERT INTO `evenement` (`id`, `titre`, `auteur`, `contenu`, `date`, `date_post`, `type_event`) VALUES
(33, 'efzefzefzeffez', 'admin', 'zfzfzfezfe', '02-11-2011', 0, 0),
(10, 'Attentionnnnnnnnnn', 'admin', '<p>asfrzgez</p>', '27-05-2011', 0, 0),
(11, 'ttttttttttttt', 'admin', '<p>ttttttt</p>', '08-06-2011', 0, 0),
(20, 'Mortel', 'admin', '<p>yahou</p>', '31-05-2011', 0, 0),
(19, 'test de folie', 'admin', '<p>dqsdqsdqsdqsdq</p>', '27-05-2011', 0, 0),
(34, 'labla', 'admin', 'qhsfkqshkdjqsd', '05-10-2011', 0, 0),
(35, 'hajhdaza', 'admin', 'ugkhkjhk', '24-09-2011', 0, 0),
(28, 's', 'admin', '<p>d</p>', '09-09-2011', 0, 0),
(30, 'sdfsdf', 'admin', '<p>sdfd</p>', '16-06-2011', 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `evenements`
--

CREATE TABLE IF NOT EXISTS `evenements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `presentation` text NOT NULL,
  `date_event` varchar(20) NOT NULL,
  `date_post` bigint(20) NOT NULL,
  `id_auteur` int(11) NOT NULL,
  `type_event` int(11) NOT NULL,
  `resume` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

--
-- Contenu de la table `evenements`
--

INSERT INTO `evenements` (`id`, `titre`, `presentation`, `date_event`, `date_post`, `id_auteur`, `type_event`, `resume`) VALUES
(1, 'Raaaaah', 'Fais chier !!', '07-10-2011', 1319749244, 4, 1, 'Haha !!!!hdhzd\ndazd\nzdazdzda'),
(6, 'Titre vacance', 'ezadzdazdazda', 'N/D', 1334186729, 4, 2, 'Entrez un rÃ©sumÃ© de l\\''organisation...'),
(7, 'Vacance dÃ©finie', 'Bon j\\''Ã©cris une prÃ©sentation de merde, c\\''est pour tester. Bon j\\''Ã©cris une prÃ©sentation de merde, c\\''est pour tester. Bon j\\''Ã©cris une prÃ©sentation de merde, c\\''est pour tester. Bon j\\''Ã©cris une prÃ©sentation de merde, c\\''est pour tester', 'N/D', 1334187098, 4, 2, 'Tets !!fzefezfefezf\nfezfezf\nefezf'),
(17, 'teaggezagezgtrt', 'afeefeazfezfef', '15-04-2012', 1334421336, 4, 2, 'Haha test'),
(4, 'ezafezfezfezfe', 'fefezfezfeffezfezfezfezfe', '10-04-2012', 1333992183, 4, 1, 'Entrez un rÃ©sumÃ© pour l''Ã©venement...'),
(8, 'Cette fois c\\''est bonnn', 'dazdzeaafzaefez', '14-04-2012', 1334187193, 4, 2, 'Je fais ca, tu feras ca, ils feront ca ! Ouai c\\''est bien ca c\\''est cool Je fais ca, tu feras ca, ils feront ca ! Ouai c\\''est bien ca c\\''est cool Je fais ca, tu feras ca, ils feront ca ! Ouai c\\''est bien ca c\\''est cool '),
(15, 'rgeagergerz', 'geragregergreg', '14-04-2012', 1334233142, 4, 2, 'Entrez un rÃ©sumÃ© de l\\''organisation...'),
(16, 'htrhtrhtrh', 'trhtrherthtrh', '21-04-2012', 1334330479, 4, 2, 'Entrez un rÃ©sumÃ© de l\\''organisation...'),
(19, 'Test soirÃ©e', 'Super test pour une soirÃ©e', '28-04-2012', 1334844034, 4, 3, 'Test');

-- --------------------------------------------------------

--
-- Structure de la table `evenements_cadeau`
--

CREATE TABLE IF NOT EXISTS `evenements_cadeau` (
  `membre_anniv` int(11) NOT NULL,
  `id_event` int(11) NOT NULL,
  `p_achat` text NOT NULL,
  PRIMARY KEY (`id_event`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Contenu de la table `evenements_cadeau`
--

INSERT INTO `evenements_cadeau` (`membre_anniv`, `id_event`, `p_achat`) VALUES
(1, 1, 'Un test pour voir si jarrive a '),
(1, 4, '');

-- --------------------------------------------------------

--
-- Structure de la table `evenements_cadeau_c`
--

CREATE TABLE IF NOT EXISTS `evenements_cadeau_c` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `prix` float NOT NULL,
  `id_auteur` int(11) NOT NULL,
  `id_event` int(11) NOT NULL,
  `valide` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

--
-- Contenu de la table `evenements_cadeau_c`
--

INSERT INTO `evenements_cadeau_c` (`id`, `titre`, `description`, `prix`, `id_auteur`, `id_event`, `valide`) VALUES
(14, 'fsferfrefef', 'gahaa\r\naaa\r\nggg', 14, 50, 1, 0),
(8, 'Essais prix', 'efef', 24, 50, 1, 0),
(6, 'roooh', 'frferg', 0, 50, 1, 0),
(10, 'Test', 's,slaa', 6, 50, 1, 1),
(9, 'mouahahaha', 'nskals,a', 67, 50, 1, 1),
(15, 'miaouuuu', 'et oyai c\\''et cool', 15.4, 4, 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `evenements_cadeau_c_sond`
--

CREATE TABLE IF NOT EXISTS `evenements_cadeau_c_sond` (
  `id_cadeau` int(11) NOT NULL,
  `id_membre` int(11) NOT NULL,
  `sond` tinyint(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Contenu de la table `evenements_cadeau_c_sond`
--

INSERT INTO `evenements_cadeau_c_sond` (`id_cadeau`, `id_membre`, `sond`) VALUES
(17, 4, 0),
(2, 1, 1),
(10, 4, 1),
(6, 4, 1),
(16, 4, 1),
(8, 4, 1),
(14, 4, 0),
(15, 4, 1);

-- --------------------------------------------------------

--
-- Structure de la table `evenements_com`
--

CREATE TABLE IF NOT EXISTS `evenements_com` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idEvent` int(11) NOT NULL,
  `idMembre` int(11) NOT NULL,
  `commentaire` text NOT NULL,
  `date` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Contenu de la table `evenements_com`
--

INSERT INTO `evenements_com` (`id`, `idEvent`, `idMembre`, `commentaire`, `date`) VALUES
(2, 1, 4, 'blaaaaa', 1334841438),
(3, 19, 4, 'haha', 1335530155),
(4, 7, 4, 'hahahaha !!!', 1336078201),
(5, 7, 4, 'blaaaaaa', 1336078633),
(6, 17, 4, 'Je fais un super test de commentaire pour voir comment ca fait !! hÃ© ouai lol Je fais un super test de commentaire pour voir comment ca fait !! hÃ© ouai lol Je fais un super test de commentaire pour voir comment ca fait !! hÃ© ouai lol Je fais un super test de commentaire pour voir comment ca fait !! hÃ© ouai lol ', 1336559651),
(7, 8, 4, 'Je fais un super test de commentaire pour voir comment ca fait !! hÃ© ouai lol Je fais un super test de commentaire pour voir comment ca fait !! hÃ© ouai lol Je fais un super test de commentaire pour voir comment ca fait !! hÃ© ouai lol Je fais un super test de commentaire pour voir comment ca fait !! hÃ© ouai lol Je fais un super test de commentaire pour voir comment ca fait !! hÃ© ouai lol ', 1336559680),
(8, 8, 4, 'Et ouai t\\''as raison', 1336559688);

-- --------------------------------------------------------

--
-- Structure de la table `evenements_participants`
--

CREATE TABLE IF NOT EXISTS `evenements_participants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_membre` int(11) NOT NULL,
  `id_event` int(11) NOT NULL,
  `participe` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Contenu de la table `evenements_participants`
--

INSERT INTO `evenements_participants` (`id`, `id_membre`, `id_event`, `participe`) VALUES
(1, 4, 1, 1),
(2, 1, 1, 2),
(4, 4, 17, 0),
(5, 4, 19, 2),
(6, 0, 6, 2),
(7, 0, 6, 2),
(8, 0, 6, 2),
(9, 4, 6, 2),
(10, 4, 7, 2),
(11, 4, 15, 1),
(12, 4, 8, 0);

-- --------------------------------------------------------

--
-- Structure de la table `evenements_soirjour`
--

CREATE TABLE IF NOT EXISTS `evenements_soirjour` (
  `idEvent` int(11) NOT NULL,
  `type` tinyint(2) NOT NULL,
  `heure` varchar(20) NOT NULL,
  `prix` varchar(20) NOT NULL,
  `lieu` varchar(255) NOT NULL,
  `apporter` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Contenu de la table `evenements_soirjour`
--

INSERT INTO `evenements_soirjour` (`idEvent`, `type`, `heure`, `prix`, `lieu`, `apporter`) VALUES
(19, 1, 'vers 23h', '8', 'haha', '');

-- --------------------------------------------------------

--
-- Structure de la table `evenements_soirjour_l`
--

CREATE TABLE IF NOT EXISTS `evenements_soirjour_l` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idEvent` int(11) NOT NULL,
  `idAuteur` int(11) NOT NULL,
  `lieu` varchar(255) NOT NULL,
  `infoPrix` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `evenements_soirjour_l`
--

INSERT INTO `evenements_soirjour_l` (`id`, `idEvent`, `idAuteur`, `lieu`, `infoPrix`) VALUES
(1, 19, 4, 'fdfe', 'zefazefaze');

-- --------------------------------------------------------

--
-- Structure de la table `evenements_soirjour_l_sond`
--

CREATE TABLE IF NOT EXISTS `evenements_soirjour_l_sond` (
  `id_membre` int(11) NOT NULL,
  `idLieu` int(11) NOT NULL,
  `Sond` tinyint(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Contenu de la table `evenements_soirjour_l_sond`
--

INSERT INTO `evenements_soirjour_l_sond` (`id_membre`, `idLieu`, `Sond`) VALUES
(4, 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `evenements_vacances`
--

CREATE TABLE IF NOT EXISTS `evenements_vacances` (
  `idEvent` int(11) NOT NULL,
  `dateFin` varchar(10) NOT NULL,
  `prix` float NOT NULL,
  `lieu` varchar(255) NOT NULL,
  PRIMARY KEY (`idEvent`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Contenu de la table `evenements_vacances`
--

INSERT INTO `evenements_vacances` (`idEvent`, `dateFin`, `prix`, `lieu`) VALUES
(6, 'N/D', 75, 'N/D'),
(7, 'N/D', 79, 'N/D'),
(8, '17-04-2012', 1002, 'Marsz'),
(17, 'N/D', 90, 'N/D'),
(15, 'N/D', 90, 'N/D'),
(16, 'N/D', 10, 'thrhrethrte');

-- --------------------------------------------------------

--
-- Structure de la table `evenements_vacances_d`
--

CREATE TABLE IF NOT EXISTS `evenements_vacances_d` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idEvent` int(11) NOT NULL,
  `idAuteur` int(11) NOT NULL,
  `dateDebut` varchar(10) NOT NULL,
  `dateFin` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `evenements_vacances_d`
--

INSERT INTO `evenements_vacances_d` (`id`, `idEvent`, `idAuteur`, `dateDebut`, `dateFin`) VALUES
(1, 15, 4, '16-04-2012', '20-04-2012'),
(2, 15, 4, '14-04-2012', '21-04-2012'),
(3, 7, 4, '13-04-2012', '27-04-2012'),
(4, 16, 4, '21-04-2012', '29-04-2012');

-- --------------------------------------------------------

--
-- Structure de la table `evenements_vacances_d_sond`
--

CREATE TABLE IF NOT EXISTS `evenements_vacances_d_sond` (
  `id_membre` int(11) NOT NULL,
  `idDates` int(11) NOT NULL,
  `sond` tinyint(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Contenu de la table `evenements_vacances_d_sond`
--

INSERT INTO `evenements_vacances_d_sond` (`id_membre`, `idDates`, `sond`) VALUES
(4, 1, 1),
(4, 2, 1),
(4, 4, 1),
(4, 3, 1);

-- --------------------------------------------------------

--
-- Structure de la table `evenements_vacances_l`
--

CREATE TABLE IF NOT EXISTS `evenements_vacances_l` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idEvent` int(11) NOT NULL,
  `idAuteur` int(11) NOT NULL,
  `lieu` varchar(255) NOT NULL,
  `infoPrix` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `evenements_vacances_l`
--

INSERT INTO `evenements_vacances_l` (`id`, `idEvent`, `idAuteur`, `lieu`, `infoPrix`) VALUES
(1, 15, 4, 'premier test!', 'genre 20â‚¬'),
(2, 15, 4, 'lieuuu', 'surement 40â‚¬'),
(3, 15, 4, 'geczdfed', 'r'),
(4, 17, 4, 'paris', 'egragrgeegrÂ§(\\''Â§\\''');

-- --------------------------------------------------------

--
-- Structure de la table `evenements_vacances_l_sond`
--

CREATE TABLE IF NOT EXISTS `evenements_vacances_l_sond` (
  `id_membre` int(11) NOT NULL,
  `idLieu` int(11) NOT NULL,
  `sond` tinyint(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Contenu de la table `evenements_vacances_l_sond`
--

INSERT INTO `evenements_vacances_l_sond` (`id_membre`, `idLieu`, `sond`) VALUES
(4, 1, 0),
(4, 4, 1),
(4, 2, 1);

-- --------------------------------------------------------

--
-- Structure de la table `galerie_albums`
--

CREATE TABLE IF NOT EXISTS `galerie_albums` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `id_auteur` int(11) NOT NULL,
  `date` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

--
-- Contenu de la table `galerie_albums`
--

INSERT INTO `galerie_albums` (`id`, `titre`, `description`, `id_auteur`, `date`) VALUES
(1, 'Un testee', 'Ceci est une description pour l\\\\\\''album hÃ©hÃ©e', 50, 1317752483),
(22, 'Mortelll', 'hahaha', 4, 1337258544),
(14, 'Test 3', 'Description du 3em test', 50, 1318356103),
(7, 'testa', 'youhougu', 50, 1317837810),
(20, 'dqdfdad', 'Super !', 0, 1332930857),
(21, 'Un testa', 'Ceci est une description pour l\\\\\\''album hÃ©hÃ©fff', 0, 1332946686);

-- --------------------------------------------------------

--
-- Structure de la table `galerie_photos`
--

CREATE TABLE IF NOT EXISTS `galerie_photos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `photo` varchar(255) NOT NULL,
  `miniature` varchar(255) NOT NULL,
  `date` bigint(20) NOT NULL,
  `id_album` int(11) NOT NULL,
  `id_auteur` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=54 ;

--
-- Contenu de la table `galerie_photos`
--

INSERT INTO `galerie_photos` (`id`, `titre`, `description`, `photo`, `miniature`, `date`, `id_album`, `id_auteur`) VALUES
(1, 'hahaÃ©Ã©', 'lol ca marche c\\''est bien<br />\r\n<br />\r\nhaha', 'upload/DSCN7013.JPG', 'upload/mini/DSCN7013.JPG', 1317753570, 0, 50),
(2, '', '', 'upload/DSCN7014.JPG', 'upload/mini/DSCN7014.JPG', 1317753856, 0, 50),
(3, '', '', 'upload/DSCN7015.JPG', 'upload/mini/DSCN7015.JPG', 1317755634, 0, 50),
(25, 'As !', 'Description....', 'upload/21373-1680x1050.jpg', 'upload/mini/21373-1680x1050.jpg', 1331241943, 14, 50),
(5, 'Essais :)', 'xD', 'upload/DSCN7020.JPG', 'upload/mini/DSCN7020.JPG', 1317837900, 1, 50),
(19, 'test1', '', 'upload/DSCN7346.JPG', 'upload/mini/DSCN7346.JPG', 1318614621, 1, 51),
(20, '', '', 'upload/DSCN7353.JPG', 'upload/mini/DSCN7353.JPG', 1318614763, 1, 51),
(21, '', '', 'upload/DSCN7388.JPG', 'upload/mini/DSCN7388.JPG', 1318614764, 1, 51),
(22, 'test1', '', 'upload/DSCN7319.JPG', 'upload/mini/DSCN7319.JPG', 1318615001, 1, 51),
(26, 'fefef', 'grerger', 'upload/25225-1920x1080.jpg', 'upload/mini/25225-1920x1080.jpg', 1331241943, 14, 50),
(27, 'gssqd', 'qdsgdss', 'upload/201002103901-19114.jpg', 'upload/mini/201002103901-19114.jpg', 1331241944, 14, 50),
(28, 'geeg', 'greer', 'upload/201006071545-18485.jpg', 'upload/mini/201006071545-18485.jpg', 1331242109, 14, 51),
(29, 'tzerter', 'tzert', 'upload/200904025855-11952.jpg', 'upload/mini/200904025855-11952.jpg', 1333054661, 1, 50),
(18, 'ah !', 'testhÃ©hÃ© \r\njsjs\r\na\r\na', 'upload/DSCN7313.JPG', 'upload/mini/DSCN7313.JPG', 1318611465, 7, 51),
(7, 'jshaosja', 'osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans ', 'upload/DSCN7015.JPG', 'upload/mini/DSCN7015.JPG', 1317837970, 1, 50),
(8, 'Youhou !', '', 'upload/DSCN7021.JPG', 'upload/mini/DSCN7021.JPG', 1317837970, 1, 50),
(9, 'jaoajaka', 'osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans osjajsk skjasj knsaj skans', 'upload/DSCN7028.JPG', 'upload/mini/DSCN7028.JPG', 1317838124, 1, 50),
(10, 'Cool !', '', 'upload/DSCN7017.JPG', 'upload/mini/DSCN7017.JPG', 1317838125, 1, 50),
(12, '', '', 'upload/certificat_scolarita©.jpeg', 'upload/mini/certificat_scolarita©.jpeg', 1318020466, 3, 50),
(31, 'Carotteee', 'coooool ^^', '../Upload/21315-1440x900.jpg', '../Upload/Mini/21315-1440x900.jpg', 1333055184, 1, 0),
(42, 'zetezt', 'ezteztze', '../Upload/Galerie/IMG\\_1885.JPG', '../Upload/Galerie/Mini/IMG\\_1885.JPG', 1336939732, 20, 0),
(41, 'fzeaf', 'zefaf', '../Upload/Galerie/IMG\\_1938.JPG', '../Upload/Galerie/Mini/IMG\\_1938.JPG', 1336938661, 20, 0),
(40, 'eyzrrezy', 'yeryeryrz', '../Upload/Galerie/DSCN7017.JPG', '../Upload/Galerie/Mini/DSCN7017.JPG', 1336938290, 20, 0),
(39, 'tyyt', 'teutru', '../Upload/Galerie/IMG\\_1878.JPG', '../Upload/Galerie/Mini/IMG\\_1878.JPG', 1336938231, 20, 0),
(38, 'ryreyezry', 'rezyrezyz', '../Upload/Galerie/DSCN7022.JPG', '../Upload/Galerie/Mini/DSCN7022.JPG', 1336926122, 20, 0),
(37, 'erzf', 'zefr', '../Upload/Galerie/28753-2464x1632.jpg', '../Upload/Galerie/Mini/28753-2464x1632.jpg', 1336923903, 21, 4),
(43, 'y(ezy', '\\''yz\\''Ã©', '../Upload/Galerie/IMG\\_2034.JPG', '../Upload/Galerie/Mini/IMG\\_2034.JPG', 1337006461, 20, 4),
(44, 'rtyuytru', 'tryuty', '../Upload/Galerie/DSCN7325.JPG', '../Upload/Galerie/Mini/DSCN7325.JPG', 1337006554, 20, 4),
(45, 'iytity', 'tiyiy', '../Upload/Galerie/DSCN7316.JPG', '../Upload/Galerie/Mini/DSCN7316.JPG', 1337006591, 20, 4),
(46, 'trhreth', 'hrteth', '../Upload/Galerie/IMG\\_2039.JPG', '../Upload/Galerie/Mini/IMG\\_2039.JPG', 1337006695, 20, 4),
(47, 'Mortel', 'gerege', '../Upload/Galerie/308973\\_2611521377978\\_1553316949\\_2723408\\_2002534643\\_n.jpg', '../Upload/Galerie/Mini/308973\\_2611521377978\\_1553316949\\_2723408\\_2002534643\\_n.jpg', 1337258584, 22, 4),
(48, 'fezrg', 'grzeger', '../Upload/Galerie/302218\\_2711014057096\\_1309227465\\_3125384\\_136244354\\_n.jpg', '../Upload/Galerie/Mini/302218\\_2711014057096\\_1309227465\\_3125384\\_136244354\\_n.jpg', 1337261546, 22, 4),
(49, 'rzegeg', 'grzere', '../Upload/Galerie/316695\\_2611445736087\\_1553316949\\_2723375\\_745232985\\_n.jpg', '../Upload/Galerie/Mini/316695\\_2611445736087\\_1553316949\\_2723375\\_745232985\\_n.jpg', 1337262157, 22, 4),
(50, 'gzerg', 'gzeer', '../Upload/Galerie/316161\\_2611528298151\\_1553316949\\_2723418\\_1002357190\\_n.jpg', '../Upload/Galerie/Mini/316161\\_2611528298151\\_1553316949\\_2723418\\_1002357190\\_n.jpg', 1337262174, 22, 4),
(51, 'gzerg', 'rgzre', '../Upload/Galerie/377821\\_2611443496031\\_1553316949\\_2723371\\_1564186050\\_n.jpg', '../Upload/Galerie/Mini/377821\\_2611443496031\\_1553316949\\_2723371\\_1564186050\\_n.jpg', 1337262247, 22, 4),
(52, 'ferzf', 'fzer', '../Upload/Galerie/388418\\_2611388854665\\_1553316949\\_2723332\\_69017570\\_njpg', '../Upload/Galerie/Mini/388418\\_2611388854665\\_1553316949\\_2723332\\_69017570\\_njpg', 1337263273, 22, 4),
(53, 'gerg', 'regre', '../Upload/Galerie/315796\\_2711012057046\\_1309227465\\_3125383\\_638707070\\_n.jpg', '../Upload/Galerie/Mini/315796\\_2711012057046\\_1309227465\\_3125383\\_638707070\\_n.jpg', 1337263418, 22, 4);

-- --------------------------------------------------------

--
-- Structure de la table `galerie_photos_com`
--

CREATE TABLE IF NOT EXISTS `galerie_photos_com` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_photo` int(11) NOT NULL,
  `id_membre` int(11) NOT NULL,
  `commentaire` text NOT NULL,
  `date` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=34 ;

--
-- Contenu de la table `galerie_photos_com`
--

INSERT INTO `galerie_photos_com` (`id`, `id_photo`, `id_membre`, `commentaire`, `date`) VALUES
(1, 1, 50, 'Test hÃ©hÃ© c\\''est cool !! <strong>test</strong>', 0),
(7, 5, 50, 'Haha :D', 0),
(8, 8, 50, 'hihi c\\''est excellent je fais un test pour voir si ca marche !!\r\net ouai c\\''est cool ca quand meme !!', 0),
(21, 8, 51, 'haha', 1318611435),
(26, 25, 51, 'Un commentaire postÃ© par le membre', 1331242006),
(27, 26, 51, 'Test', 1331242057),
(32, 47, 4, 'rgezgzegezg', 1337265762),
(29, 31, 50, 'test', 1333119296),
(30, 31, 50, 'haha', 1333119401),
(31, 31, 50, 'aaas', 1333119615),
(33, 47, 4, 'Ã©Ã©eea', 1338543416),
(25, 25, 50, 'Et un deuxiÃ¨me tjrs postÃ© par l\\''admin', 1331241972),
(13, 0, 50, 'test', 1318528962),
(14, 0, 50, 'test', 1318529016),
(15, 0, 50, 'hihi', 1318529057),
(24, 25, 50, 'Un premier commentaire !', 1331241961),
(20, 5, 50, 'haha !', 1318529891),
(23, 19, 50, 'test', 1319122984);

-- --------------------------------------------------------

--
-- Structure de la table `galerie_photos_notes`
--

CREATE TABLE IF NOT EXISTS `galerie_photos_notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_membre` int(11) NOT NULL,
  `id_photo` int(11) NOT NULL,
  `note` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Contenu de la table `galerie_photos_notes`
--

INSERT INTO `galerie_photos_notes` (`id`, `id_membre`, `id_photo`, `note`) VALUES
(1, 50, 2, 1),
(2, 50, 3, 0),
(3, 50, 1, 1),
(4, 50, 4, 1),
(5, 51, 4, 1),
(6, 50, 10, 0),
(7, 50, 7, 1),
(8, 50, 8, 1),
(9, 50, 9, 0),
(10, 50, 5, 1),
(11, 51, 5, 0),
(12, 50, 19, 1),
(13, 50, 22, 0),
(14, 50, 20, 0),
(15, 50, 25, 1),
(16, 51, 25, 0),
(17, 50, 31, 1),
(18, 4, 48, 1);

-- --------------------------------------------------------

--
-- Structure de la table `membre`
--

CREATE TABLE IF NOT EXISTS `membre` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pseudo` text NOT NULL,
  `mdp_md5` text NOT NULL,
  `email` text NOT NULL,
  `groupe` text NOT NULL,
  `date_inscription` int(11) NOT NULL,
  `question` text NOT NULL,
  `reponse` text NOT NULL,
  `etat` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=52 ;

--
-- Contenu de la table `membre`
--

INSERT INTO `membre` (`id`, `pseudo`, `mdp_md5`, `email`, `groupe`, `date_inscription`, `question`, `reponse`, `etat`) VALUES
(50, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin@admin.com', 'administrateur', 1288450667, 'animal', 'tchoupie', 1),
(51, 'membre', '5a99c8cac333affeed05a24fe0d6f61c', 'membre@membre.fr', 'membre', 1290862312, 'animal', 'tamere', 1);

-- --------------------------------------------------------

--
-- Structure de la table `membres`
--

CREATE TABLE IF NOT EXISTS `membres` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `groupe` varchar(20) NOT NULL,
  `etat` tinyint(1) NOT NULL,
  `dateNaiss` bigint(20) NOT NULL,
  `dateInsc` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `membres`
--

INSERT INTO `membres` (`id`, `pseudo`, `password`, `nom`, `prenom`, `email`, `photo`, `groupe`, `etat`, `dateNaiss`, `dateInsc`) VALUES
(1, 'Paycheur', '86cf69ddd3e368dc4579a90a6800af53', '', 'Yoann', 'yo.lefevre@gmail.com', '', 'membre', 0, 0, 0),
(2, 'membre', '5a99c8cac333affeed05a24fe0d6f61c', '', 'haha', 'test@gdzadzh.com', '', 'membre', 0, 0, 1333303682),
(3, '', 'd41d8cd98f00b204e9800998ecf8427e', '', '', '', '', 'membre', 0, 0, 1333363747),
(4, 'admin', '9c1ad00a16a7c67e2727b471ac969e96', 'admin', 'admin', 'admin@dzadzadz.fr', '', 'administrateur', 1, 0, 1333545650);

-- --------------------------------------------------------

--
-- Structure de la table `message`
--

CREATE TABLE IF NOT EXISTS `message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `objet` text NOT NULL,
  `contenu` text NOT NULL,
  `expediteur` text NOT NULL,
  `destinataire` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `message`
--

INSERT INTO `message` (`id`, `objet`, `contenu`, `expediteur`, `destinataire`) VALUES
(1, 'test', 'bdfqjfdjqfdsq\r\nfsqd\r\n\r\n\r\nqsdf\r\nsq\r\nf\r\n\r\nf\r\nqsdfsqdfqsfdqsdf', 'caca', 'chiotte'),
(2, 'etstqdqsd', 'dgfdgfdsg', 'sdgdsfg', 'sdgdsg');

-- --------------------------------------------------------

--
-- Structure de la table `statut`
--

CREATE TABLE IF NOT EXISTS `statut` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `auteur` varchar(20) NOT NULL,
  `contenu` text NOT NULL,
  `date` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Contenu de la table `statut`
--

INSERT INTO `statut` (`id`, `auteur`, `contenu`, `date`) VALUES
(1, 'admin', 'azertyui', 1308139635),
(7, 'admin', 'Test de fou', 1316717151),
(6, 'admin', 'OMAGAD', 1308214365),
(5, 'admin', 'super soirée les jack !!!', 1308140090),
(8, 'admin', 'fhskjdfsdfsdfsfsd', 1316721686),
(9, 'admin', 'igkjshkjdhqkjshdkqjshdqsd', 1316878909);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
