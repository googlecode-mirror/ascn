-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le : Lun 14 Mai 2012 à 19:47
-- Version du serveur: 5.1.36
-- Version de PHP: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `games`
--

-- --------------------------------------------------------

--
-- Structure de la table `jeu`
--

CREATE TABLE IF NOT EXISTS `jeu` (
  `jeu_id` int(11) NOT NULL AUTO_INCREMENT,
  `jeu_name` varchar(64) CHARACTER SET latin1 DEFAULT NULL,
  `jeu_title` varchar(128) CHARACTER SET latin1 DEFAULT NULL,
  `jeu_description` varchar(512) CHARACTER SET latin1 DEFAULT NULL,
  `jeu_visible` int(1) NOT NULL DEFAULT '1',
  `jeu_nbjoueur_min` int(11) DEFAULT NULL,
  `jeu_nbjoueur_max` int(11) DEFAULT NULL,
  PRIMARY KEY (`jeu_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Contenu de la table `jeu`
--

INSERT INTO `jeu` (`jeu_id`, `jeu_name`, `jeu_title`, `jeu_description`, `jeu_visible`, `jeu_nbjoueur_min`, `jeu_nbjoueur_max`) VALUES
(2, 'awale', 'Awalé', 'Jeu africain où vous devez nourrir l''autre joueur tout en remplissant votre grenier.', 1, 2, 2),
(1, 'tictactoe', 'Tic Tac Toe', 'Jeu simple ou il suffit d''aligner 3 signes identique avant son adversaire.', 1, 2, 2),
(3, 'dammes', 'Dammes (old)', 'Jeu classique des dammes où vous devez manger tous les pions de votre adversaire.', 0, 1, 2),
(4, 'test', 'Jeu test', 'Juste un test', 1, 1, 2),
(5, 'checkers', 'Dammes', 'Jeu classique des dammes où vous devez manger tous les pions de votre adversaire.', 1, 1, 2);

-- --------------------------------------------------------

--
-- Structure de la table `jeu_tag`
--

CREATE TABLE IF NOT EXISTS `jeu_tag` (
  `jeu_id` int(11) DEFAULT NULL,
  `tag_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `joueur`
--

CREATE TABLE IF NOT EXISTS `joueur` (
  `joueur_id` int(11) NOT NULL AUTO_INCREMENT,
  `joueur_pseudo` varchar(32) CHARACTER SET latin1 NOT NULL DEFAULT 'invité',
  `joueur_password_hash` varchar(32) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`joueur_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Contenu de la table `joueur`
--

INSERT INTO `joueur` (`joueur_id`, `joueur_pseudo`, `joueur_password_hash`) VALUES
(1, 'julien', '098f6bcd4621d373cade4e832627b4f6'),
(4, 'justine', '7cf2db5ec261a0fa27a502d3196a6f60'),
(2, 'chrome', '554838a8451ac36cb977e719e9d6623c'),
(5, 'Palmito', '078556981453bc8cc668d7b9c0e45cf3');

-- --------------------------------------------------------

--
-- Structure de la table `opt`
--

CREATE TABLE IF NOT EXISTS `opt` (
  `opt_id` int(11) NOT NULL AUTO_INCREMENT,
  `jeu_id` int(11) NOT NULL,
  `opt_name` varchar(64) NOT NULL,
  `opt_title` varchar(64) NOT NULL,
  `opt_values` varchar(1024) NOT NULL,
  `opt_position` int(8) NOT NULL DEFAULT '0',
  PRIMARY KEY (`opt_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Contenu de la table `opt`
--

INSERT INTO `opt` (`opt_id`, `jeu_id`, `opt_name`, `opt_title`, `opt_values`, `opt_position`) VALUES
(1, 2, 'nb_haricot_initial', 'Nombre initial de haricots par compartiments', '3|4|5', 0),
(2, 2, 'premier_joueur', 'Premier joueur à jouer', '0:aléatoire|1:Joueur 1|2:Joueur 2', 0),
(3, 3, 'premier_joueur', 'Premier joueur à jouer', '0:aléatoire|1:Joueur 1|2:Joueur 2', 0),
(4, 4, 'nom_option', 'title_option', '1:value1|2:value2|3:value3str|4:value4', 0),
(5, 5, 'regles', 'Règles', '1:francaises|2:anglaises', 0),
(6, 5, 'premier_joueur', 'Premier joueur à jouer', '0:aléatoire|1:Joueur 1|2:Joueur 2', 0);

-- --------------------------------------------------------

--
-- Structure de la table `partie`
--

CREATE TABLE IF NOT EXISTS `partie` (
  `partie_id` int(11) NOT NULL AUTO_INCREMENT,
  `jeu_id` int(11) DEFAULT NULL,
  `partie_host` int(11) DEFAULT NULL,
  `partie_title` varchar(64) CHARACTER SET latin1 DEFAULT NULL,
  `partie_etat` int(4) NOT NULL DEFAULT '1' COMMENT '1 : preparation, 2 : partie en cours, 3 : terminee',
  `partie_data` text CHARACTER SET latin1,
  PRIMARY KEY (`partie_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `partie_opt`
--

CREATE TABLE IF NOT EXISTS `partie_opt` (
  `partie_id` int(11) NOT NULL,
  `opt_id` int(11) NOT NULL,
  `opt_value` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `slot`
--

CREATE TABLE IF NOT EXISTS `slot` (
  `slot_id` int(11) NOT NULL AUTO_INCREMENT,
  `partie_id` int(11) DEFAULT NULL,
  `joueur_id` int(11) DEFAULT NULL,
  `slot_position` int(11) DEFAULT NULL,
  `slot_score` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`slot_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `tag`
--

CREATE TABLE IF NOT EXISTS `tag` (
  `tag_id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_name` varchar(64) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`tag_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
