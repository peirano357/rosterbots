-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-08-2017 a las 03:58:38
-- Versión del servidor: 5.6.17
-- Versión de PHP: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `rosterbots_testing`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bot`
--

CREATE TABLE IF NOT EXISTS `bot` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text,
  `idTeam` int(11) DEFAULT NULL,
  `speed` int(11) DEFAULT '0',
  `strength` int(11) NOT NULL DEFAULT '0',
  `agility` int(11) NOT NULL DEFAULT '0',
  `dateCreated` datetime NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `sequence` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=334 ;

--
-- Volcado de datos para la tabla `bot`
--

INSERT INTO `bot` (`id`, `name`, `idTeam`, `speed`, `strength`, `agility`, `dateCreated`, `dateUpdated`, `sequence`) VALUES
(319, 'EON735', 12, 2, 2, 8, '2017-08-19 12:55:50', '2017-08-19 12:55:50', 'AAA0001'),
(320, 'HQX698', 12, 5, 1, 5, '2017-08-19 12:55:50', '2017-08-19 12:55:50', 'AAA0002'),
(321, 'YVQ122', 12, 3, 3, 4, '2017-08-19 12:55:50', '2017-08-19 12:55:50', 'AAA0003'),
(322, 'SLX913', 12, 2, 3, 4, '2017-08-19 12:55:50', '2017-08-19 12:55:50', 'AAA0004'),
(323, 'UCK106', 12, 0, 4, 4, '2017-08-19 12:55:50', '2017-08-19 12:55:50', 'AAA0005'),
(324, 'MEL886', 12, 1, 2, 4, '2017-08-19 12:55:50', '2017-08-19 12:55:50', 'AAA0006'),
(325, 'DRL283', 12, 1, 1, 4, '2017-08-19 12:55:50', '2017-08-19 12:55:50', 'AAA0007'),
(326, 'TXX872', 12, 1, 1, 3, '2017-08-19 12:55:50', '2017-08-19 12:55:50', 'AAA0008'),
(327, 'XIX517', 12, 2, 1, 1, '2017-08-19 12:55:50', '2017-08-19 12:55:50', 'AAA0009'),
(328, 'FDF402', 12, 6, 4, 3, '2017-08-19 12:55:50', '2017-08-19 12:55:50', 'AAA0010'),
(329, 'MIN556', 12, 4, 7, 3, '2017-08-19 12:55:50', '2017-08-19 12:55:50', 'AAA0011'),
(330, 'ZYU241', 12, 6, 4, 5, '2017-08-19 12:55:50', '2017-08-19 12:55:50', 'AAA0012'),
(331, 'TLJ271', 12, 9, 4, 3, '2017-08-19 12:55:50', '2017-08-19 12:55:50', 'AAA0013'),
(332, 'TVM709', 12, 9, 3, 5, '2017-08-19 12:55:50', '2017-08-19 12:55:50', 'AAA0014'),
(333, 'EZE252', 12, 13, 9, 6, '2017-08-19 12:55:50', '2017-08-19 12:55:50', 'AAA0015');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `maxBotPoints` int(11) DEFAULT NULL,
  `maxTeamPoints` int(11) DEFAULT NULL,
  `dateCreated` datetime DEFAULT NULL,
  `dateUpdated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `settings`
--

INSERT INTO `settings` (`id`, `maxBotPoints`, `maxTeamPoints`, `dateCreated`, `dateUpdated`) VALUES
(1, 100, 175, '2017-08-02 00:00:00', '2017-08-17 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `team`
--

CREATE TABLE IF NOT EXISTS `team` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idUser` int(11) DEFAULT NULL,
  `dateCreated` datetime DEFAULT NULL,
  `dateUpdated` datetime DEFAULT NULL,
  `rosterSent` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=32 ;

--
-- Volcado de datos para la tabla `team`
--

INSERT INTO `team` (`id`, `idUser`, `dateCreated`, `dateUpdated`, `rosterSent`) VALUES
(12, 1, '2017-08-19 12:55:50', '2017-08-19 12:55:50', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstName` text,
  `lastName` text,
  `userName` text,
  `password` text,
  `email` text,
  `token` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- Volcado de datos para la tabla `user`
--

INSERT INTO `user` (`id`, `firstName`, `lastName`, `userName`, `password`, `email`, `token`) VALUES
(1, 'José', 'Peirano', 'peirano357', '21232f297a57a5a743894a0e4a801fc3', 'peirano357@gmail.com', '9428da678cd2389bfc8a70283beede00'),
(3, 'Elvis', 'Presley', 'tester333', 'd9258ec36b0430511f05fe617abab15b', 'elvislives@gmail.com', '0feeda53d100a960a2158eb54793461e'),
(4, 'Juancito', 'Gonzalez', 'mexico999', 'd2104a400c7f629a197f33bb33fe80c0', 'tester@mock.com', 'd2104a400c7f629a197f33bb33fe80c0');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
