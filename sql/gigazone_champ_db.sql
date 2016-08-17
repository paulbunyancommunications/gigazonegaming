# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: w3.paulbunyan.net (MySQL 5.5.50-cll)
# Database: gigazone_champ_db
# Generation Time: 2016-08-17 21:12:15 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table games
# ------------------------------------------------------------

DROP TABLE IF EXISTS `games`;

CREATE TABLE `games` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `uri` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `games_name_unique` (`name`),
  KEY `games_title_index` (`title`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `games` WRITE;
/*!40000 ALTER TABLE `games` DISABLE KEYS */;

INSERT INTO `games` (`id`, `name`, `title`, `description`, `uri`, `created_at`, `updated_at`, `updated_by`, `updated_on`)
VALUES
	(1,'unknown','','Unknown game','',NULL,NULL,0,'0000-00-00 00:00:00'),
	(2,'league-of-legends','League of Legends','','http://leagueoflegends.com/','2016-05-23 22:58:54','2016-05-23 22:58:54',0,'0000-00-00 00:00:00');

/*!40000 ALTER TABLE `games` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table individual_players
# ------------------------------------------------------------

DROP TABLE IF EXISTS `individual_players`;

CREATE TABLE `individual_players` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `game_id` int(10) unsigned NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `individual_players_game_id_foreign` (`game_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `individual_players` WRITE;
/*!40000 ALTER TABLE `individual_players` DISABLE KEYS */;

INSERT INTO `individual_players` (`id`, `username`, `name`, `email`, `phone`, `created_at`, `updated_at`, `game_id`, `updated_by`, `updated_on`)
VALUES
	(5,'MidniteEclipse','','anderson.spencer@hotmail.com','2182142774','2016-05-26 21:10:43','2016-05-26 21:10:43',2,0,'0000-00-00 00:00:00'),
	(4,'Cheese Raviolyi','','alabama24@live.com','218-252-1225','2016-05-25 21:32:21','2016-05-25 21:32:21',2,0,'0000-00-00 00:00:00'),
	(6,'Reluxtris','Nathan miller','xolaces@gmail.com','2182526511','2016-06-11 11:34:11','2016-06-11 11:34:11',2,0,'0000-00-00 00:00:00'),
	(7,'Absalom276','Lateef Medley','ljmmedley@gmail.com','2675770650','2016-06-24 18:08:26','2016-06-24 18:08:26',2,0,'0000-00-00 00:00:00'),
	(8,'Grant2kx','Grant Patten','grantpatten@hotmail.com','2187606233','2016-06-28 21:26:52','2016-06-28 21:26:52',2,0,'0000-00-00 00:00:00'),
	(9,'Kiko','Kiki','kjuresic@gmail.com','063405888','2016-07-01 12:48:33','2016-07-01 12:48:33',2,0,'0000-00-00 00:00:00'),
	(10,'Nugeon1','Nugeon1','nugeon1@gmail.com','017647225571','2016-07-01 16:49:58','2016-07-01 16:49:58',2,0,'0000-00-00 00:00:00'),
	(11,'gorilla master','strulea teo','strulea78@mail.ru','068983435','2016-07-04 10:51:41','2016-07-04 10:51:41',2,0,'0000-00-00 00:00:00'),
	(12,'EmaNNueL','Emanuel','Knuckson91@gmail.com','053435453','2016-07-09 14:31:01','2016-07-09 14:31:01',2,0,'0000-00-00 00:00:00'),
	(13,'barkestar123','dawid ','dawid.kucek@onet.pl','6747879875','2016-07-16 13:31:56','2016-07-16 13:31:56',2,0,'0000-00-00 00:00:00'),
	(14,'FloodLights','Matthew Lagaard','bunnieboy13@gmail.com','2182518982','2016-07-18 05:51:28','2016-07-18 05:51:28',2,0,'0000-00-00 00:00:00'),
	(15,'Yokomson','DJ Yokom','djyokom@gmail.com','2183293362','2016-07-19 16:37:59','2016-07-19 16:37:59',2,0,'0000-00-00 00:00:00'),
	(16,'Doubledusk','(Sky) Sylvestor Grant','doubleduskgames@gmail.com','2183081259','2016-07-19 19:16:58','2016-07-19 19:16:58',2,0,'0000-00-00 00:00:00'),
	(17,'Cheese Raviolyi','Noah Hernandez','alabama24@live.com','218-252-1225','2016-07-19 20:12:14','2016-07-19 20:12:14',2,0,'0000-00-00 00:00:00'),
	(18,'noofeee','noofeee','noofeee2016@gmail.com','0552595538','2016-07-23 12:59:23','2016-07-23 12:59:23',2,0,'0000-00-00 00:00:00'),
	(19,'nooofeee','nooofeee','noiofeee@windowslive.com','0552595538','2016-07-23 13:00:10','2016-07-23 13:00:10',2,0,'0000-00-00 00:00:00'),
	(20,'gega123','gega','geg.beleshi@outlook.com','0682079002','2016-07-27 19:51:44','2016-07-27 19:51:44',2,0,'0000-00-00 00:00:00'),
	(21,'gega123','gega','geg.beleshi@outlook.com','0682079002','2016-07-27 19:51:51','2016-07-27 19:51:51',2,0,'0000-00-00 00:00:00'),
	(22,'Felthornz','Riordan Booth','www.riordan@gmail.com','12182142750','2016-08-03 03:32:52','2016-08-03 03:32:52',2,0,'0000-00-00 00:00:00');

/*!40000 ALTER TABLE `individual_players` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table players
# ------------------------------------------------------------

DROP TABLE IF EXISTS `players`;

CREATE TABLE `players` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `team_id` int(10) unsigned NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `players_team_id_foreign` (`team_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `players` WRITE;
/*!40000 ALTER TABLE `players` DISABLE KEYS */;

INSERT INTO `players` (`id`, `username`, `email`, `name`, `phone`, `created_at`, `updated_at`, `team_id`, `updated_by`, `updated_on`)
VALUES
	(29,'Wiftu','codyjmatthews@hotmail.com','','','2016-06-05 23:18:31','2016-06-05 23:18:31',6,0,'0000-00-00 00:00:00'),
	(28,'Tuckerz','alyisg19@hotmail.com','','','2016-06-05 23:18:31','2016-06-05 23:18:31',6,0,'0000-00-00 00:00:00'),
	(27,'Isske','ikrasmusson@gmail.com','','','2016-06-05 23:18:31','2016-06-05 23:18:31',6,0,'0000-00-00 00:00:00'),
	(26,'I J B','belgardeiain@gmail.com','Iain Belgarde','2182443092','2016-06-05 23:18:31','2016-06-05 23:18:31',6,0,'0000-00-00 00:00:00'),
	(25,'generalhabs','generalhabs@gmail.com','','','2016-05-25 20:17:52','2016-05-25 20:17:52',5,0,'0000-00-00 00:00:00'),
	(46,'123456789k','raij@email.com','123456789k',' didam','2016-08-08 20:10:29','2016-08-08 20:10:29',10,0,'0000-00-00 00:00:00'),
	(21,'champofsouls69','andrewfairbanks5@gmail.com','Andrew Fairbanks','2184075184','2016-05-25 20:17:52','2016-05-25 20:17:52',5,0,'0000-00-00 00:00:00'),
	(22,'Determination69','angrycommie32@gmail.com','','','2016-05-25 20:17:52','2016-05-25 20:17:52',5,0,'0000-00-00 00:00:00'),
	(23,'StevenBills69','alabasterelfeno@gmail.com','','','2016-05-25 20:17:52','2016-05-25 20:17:52',5,0,'0000-00-00 00:00:00'),
	(30,'LGD Mute','abombisgodly@outlook.com','','','2016-06-05 23:18:31','2016-06-05 23:18:31',6,0,'0000-00-00 00:00:00'),
	(31,'Asystole','asystole23@gmail.com','Ryan Quirin','2187667095','2016-06-11 05:04:09','2016-06-11 05:04:09',7,0,'0000-00-00 00:00:00'),
	(32,'Turtle Trampler','karlmork@paulbunyan.net','','','2016-06-11 05:04:09','2016-06-11 05:04:09',7,0,'0000-00-00 00:00:00'),
	(33,'klackalack','kymo73@gmail.com','','','2016-06-11 05:04:09','2016-06-11 05:04:09',7,0,'0000-00-00 00:00:00'),
	(34,'ollio11','ollila09@comcast.net','','','2016-06-11 05:04:09','2016-06-11 05:04:09',7,0,'0000-00-00 00:00:00'),
	(35,'Demonicbutters','Butters_1941@hotmail.com','','','2016-06-11 05:04:09','2016-06-11 05:04:09',7,0,'0000-00-00 00:00:00'),
	(36,'PudgeyPicklePal','devin.tooker@gmail.com','Devin Tooker','2182551958','2016-06-12 16:50:09','2016-06-12 16:50:09',8,0,'0000-00-00 00:00:00'),
	(37,'Ostrogothic Dame','kriseric1986@gmail.com','','','2016-06-12 16:50:09','2016-06-12 16:50:09',8,0,'0000-00-00 00:00:00'),
	(38,'xFuji','kuhrtluann@hotmail.com','','','2016-06-12 16:50:09','2016-06-12 16:50:09',8,0,'0000-00-00 00:00:00'),
	(39,'BigStank187','s_mccaffrey@hotmail.com','','','2016-06-12 16:50:09','2016-06-12 16:50:09',8,0,'0000-00-00 00:00:00'),
	(40,'GitGudNewb','onfleek218@gmail.com','','','2016-06-12 16:50:09','2016-06-12 16:50:09',8,0,'0000-00-00 00:00:00'),
	(41,'Chinchiila King','Matthan.Althiser@live.bemidjistate.edu','Matthan Althiser','218-209-9917','2016-07-12 05:01:02','2016-07-12 05:01:02',9,0,'0000-00-00 00:00:00'),
	(42,'Bard the Brad','kovacovichbradley@gmail.com','','','2016-07-12 05:01:02','2016-07-12 05:01:02',9,0,'0000-00-00 00:00:00'),
	(43,'Gromp Hentai','snowboarderkyle10@hotmail.com','','','2016-07-12 05:01:02','2016-07-12 05:01:02',9,0,'0000-00-00 00:00:00'),
	(44,'King Bear','taylorotness@gmail.com','','','2016-07-12 05:01:02','2016-07-12 05:01:02',9,0,'0000-00-00 00:00:00'),
	(45,'Benny Fufoo','beef260@gmail.com','','','2016-07-12 05:01:02','2016-07-12 05:01:02',9,0,'0000-00-00 00:00:00'),
	(47,'123456789k','raij@email.com','','','2016-08-08 20:10:29','2016-08-08 20:10:29',10,0,'0000-00-00 00:00:00'),
	(48,'didam','raij@email.com','','','2016-08-08 20:10:29','2016-08-08 20:10:29',10,0,'0000-00-00 00:00:00'),
	(49,'didam','','','','2016-08-08 20:10:29','2016-08-08 20:10:29',10,0,'0000-00-00 00:00:00'),
	(50,'123456789k','raij@email.com','123456789k','123456789k','2016-08-08 20:14:29','2016-08-08 20:14:29',11,0,'0000-00-00 00:00:00'),
	(51,'didam','raij@email.com','','','2016-08-08 20:14:29','2016-08-08 20:14:29',11,0,'0000-00-00 00:00:00'),
	(52,'123456789k','raij@email.com','','','2016-08-08 20:14:29','2016-08-08 20:14:29',11,0,'0000-00-00 00:00:00'),
	(53,'123456789k','','','','2016-08-08 20:14:29','2016-08-08 20:14:29',11,0,'0000-00-00 00:00:00'),
	(54,'123456789k','','','','2016-08-08 20:14:29','2016-08-08 20:14:29',11,0,'0000-00-00 00:00:00'),
	(55,'ArchonSenpie','patrickgibeau@yahoo.com','Patrick Gibeau','(218) - 910 - 6305','2016-08-09 01:20:51','2016-08-09 01:20:51',12,0,'0000-00-00 00:00:00'),
	(56,'TheBearEatingMan','Jacklahti09@gmail.com','','','2016-08-09 01:20:51','2016-08-09 01:20:51',12,0,'0000-00-00 00:00:00'),
	(57,'BlueWords','zeightygibeau@gmail.com','','','2016-08-09 01:20:51','2016-08-09 01:20:51',12,0,'0000-00-00 00:00:00'),
	(58,'TheLizardEater21','CrocodileEater09@gmail.com','','','2016-08-09 01:20:51','2016-08-09 01:20:51',12,0,'0000-00-00 00:00:00'),
	(59,'BearofLegends','Dragonface@live.com','','','2016-08-09 01:20:51','2016-08-09 01:20:51',12,0,'0000-00-00 00:00:00');

/*!40000 ALTER TABLE `players` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table teams
# ------------------------------------------------------------

DROP TABLE IF EXISTS `teams`;

CREATE TABLE `teams` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `emblem` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `captain` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tournament_id` int(10) unsigned NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `teams_tournament_id_foreign` (`tournament_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `teams` WRITE;
/*!40000 ALTER TABLE `teams` DISABLE KEYS */;

INSERT INTO `teams` (`id`, `name`, `emblem`, `captain`, `created_at`, `updated_at`, `tournament_id`, `updated_by`, `updated_on`)
VALUES
	(6,'ProFreakz','',26,'2016-06-05 23:18:31','2016-06-05 23:18:31',1,0,'0000-00-00 00:00:00'),
	(5,'Cowfee','',21,'2016-05-25 20:17:52','2016-05-25 20:17:52',1,0,'0000-00-00 00:00:00'),
	(7,'Reckless Decision','',31,'2016-06-11 05:04:08','2016-06-11 05:04:09',1,0,'0000-00-00 00:00:00'),
	(8,'Team Face Check','',36,'2016-06-12 16:50:09','2016-06-12 16:50:09',1,0,'0000-00-00 00:00:00'),
	(9,'Team Synergy','',41,'2016-07-12 05:01:02','2016-07-12 05:01:02',1,0,'0000-00-00 00:00:00'),
	(12,'Team Esteem ','',55,'2016-08-09 01:20:51','2016-08-09 01:20:51',1,0,'0000-00-00 00:00:00');

/*!40000 ALTER TABLE `teams` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table tournaments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tournaments`;

CREATE TABLE `tournaments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `game_id` int(10) unsigned NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `tournaments_name_unique` (`name`),
  KEY `tournaments_game_id_foreign` (`game_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `tournaments` WRITE;
/*!40000 ALTER TABLE `tournaments` DISABLE KEYS */;

INSERT INTO `tournaments` (`id`, `name`, `created_at`, `updated_at`, `game_id`, `updated_by`, `updated_on`)
VALUES
	(1,'gigazone-gaming-2016-league-of-legends','2016-05-23 22:58:54','2016-05-23 22:58:54',2,0,'0000-00-00 00:00:00');

/*!40000 ALTER TABLE `tournaments` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
