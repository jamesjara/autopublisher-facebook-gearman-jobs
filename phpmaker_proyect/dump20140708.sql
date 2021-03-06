CREATE DATABASE  IF NOT EXISTS `socialcms` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `socialcms`;
-- MySQL dump 10.13  Distrib 5.6.13, for Win32 (x86)
--
-- Host: localhost    Database: socialcms
-- ------------------------------------------------------
-- Server version	5.6.17

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `audittrail`
--

DROP TABLE IF EXISTS `audittrail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `audittrail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `datetime` datetime NOT NULL,
  `script` varchar(255) DEFAULT NULL,
  `user` varchar(255) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `table` varchar(255) DEFAULT NULL,
  `field` varchar(255) DEFAULT NULL,
  `keyvalue` longtext,
  `oldvalue` longtext,
  `newvalue` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audittrail`
--

LOCK TABLES `audittrail` WRITE;
/*!40000 ALTER TABLE `audittrail` DISABLE KEYS */;
INSERT INTO `audittrail` VALUES (1,'2014-07-04 04:24:33','/war/login.php','admin','login','127.0.0.1','','','',''),(2,'2014-07-04 04:38:35','/war/logout.php','Administrator','logout','127.0.0.1','','','',''),(3,'2014-07-04 19:29:08','/war/login.php','admin','login','127.0.0.1','','','','');
/*!40000 ALTER TABLE `audittrail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fb_apps_credenciales`
--

DROP TABLE IF EXISTS `fb_apps_credenciales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fb_apps_credenciales` (
  `idfb_apps_credenciales` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) NOT NULL,
  `app` varchar(245) NOT NULL,
  `secret` varchar(245) NOT NULL,
  PRIMARY KEY (`idfb_apps_credenciales`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fb_apps_credenciales`
--

LOCK TABLES `fb_apps_credenciales` WRITE;
/*!40000 ALTER TABLE `fb_apps_credenciales` DISABLE KEYS */;
INSERT INTO `fb_apps_credenciales` VALUES (1,'ticoganga.com','299398536906086','21c1ef77f030a7b1374de769488d4db7');
/*!40000 ALTER TABLE `fb_apps_credenciales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fb_posts`
--

DROP TABLE IF EXISTS `fb_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fb_posts` (
  `idfb_posts` int(11) NOT NULL AUTO_INCREMENT,
  `app_credencial` int(250) NOT NULL,
  `target` varchar(245) NOT NULL,
  `message` text,
  `picture` text,
  `link` text,
  `name` text,
  `sessionid` int(11) DEFAULT '777',
  `description` text,
  `action_name` text,
  `action_link` text,
  PRIMARY KEY (`idfb_posts`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fb_posts`
--

LOCK TABLES `fb_posts` WRITE;
/*!40000 ALTER TABLE `fb_posts` DISABLE KEYS */;
INSERT INTO `fb_posts` VALUES (1,1,'387953724607663','8794-4598 GANGAAAA! ---SOLO GENTE SERIA---. PRECIO ESPECIAL, AROS DE LUJO 18, XENON DVD TACTIL, SHIFTTRONIC AUTOMATICO MANUAL. VIDA DEL MOTOR EXCELENTE: 90KM. GANGAAAA!  Aproveche no siempre salen autos asi. Le llevo el carro solo gente con el efectivo es algo serio. me urge el dinero no se cambia, me voy del país.  Vea el vídeo y precio aquí: http://autodeportivo.ticoganga.com/ #‎sele‬ ‪#‎costarica‬ ‪#‎sisepudo‬ ‪#‎selecr‬‬ ‪#‎carros','http://new.landingi.com/uploads/12a104cc913a97a3cac4/pictures/d5152516e2c93a0495ba1fc8bd2cb15e.','autodeportivo.ticoganga.com','*.* UFF LUJOSO DEPORTIVO TIBURON PRO 2005 REGALADO! 8794-4598  LLAMEME YA!! *.*',7778,'DESC','comprar','http://nathanbolin.com'),(2,1,'387953724607663','8794-4598 GANGAAAA! ---SOLO GENTE SERIA---. PRECIO ESPECIAL, Volkswagen Gol 99 - Carro mega economico GANGAAAA!  Vea el vídeo y precio aquí: http://autoeconomico.ticoganga.com/ #‎sele‬ ‪#‎costarica‬ ‪#‎sisepudo‬ ‪#‎selecr‬‬ ‪#‎carros','http://new.landingi.com/uploads/12a104cc913a97a3cac4/pictures/d9f2eab34b29cf1e7d427d1ae1a1846e.jpg','http://autoeconomico.ticoganga.com','*.* UFF Volkswagen Gol 99 ! 8794-4598  LLAMEME YA!! *.*',7778,'DESC','comprar','http://nathanbolin.com');
/*!40000 ALTER TABLE `fb_posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fb_targets`
--

DROP TABLE IF EXISTS `fb_targets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fb_targets` (
  `idfb_targets` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(145) NOT NULL,
  `fid` varchar(145) NOT NULL,
  `categoria` int(11) DEFAULT NULL,
  PRIMARY KEY (`idfb_targets`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fb_targets`
--

LOCK TABLES `fb_targets` WRITE;
/*!40000 ALTER TABLE `fb_targets` DISABLE KEYS */;
INSERT INTO `fb_targets` VALUES (1,'cr autos','387953724607663',1),(2,'CRAUTOS-CRMOTOS-CRCAS','538270862854032',1),(3,'Grupo Del Cambalache','159944514166912',0),(4,'Que le Vendo Que le Compro','278458938931770',0),(5,'Compra y Venda en Costa Rica','447025715327233',0),(6,'Compra y Venta para Costa Rica','353603508060711',0),(7,'Auto Barato CR','355585494581050',1),(8,'Compra y Venta Costa Rica','413212712086732',0),(9,'GrupoHyundaiCR','563788747009614',1),(10,'Cambalache de carros','181615495329572',1),(11,'comprarvendercambiar','196412997182957',0),(12,'sevendecompracambiacr','310429702361405',0),(13,'sevendesecompraysecambia','166297816770483',0);
/*!40000 ALTER TABLE `fb_targets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fb_targets_cats`
--

DROP TABLE IF EXISTS `fb_targets_cats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fb_targets_cats` (
  `idfb_targets_cats` int(11) NOT NULL AUTO_INCREMENT,
  `nombr` varchar(45) NOT NULL,
  PRIMARY KEY (`idfb_targets_cats`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fb_targets_cats`
--

LOCK TABLES `fb_targets_cats` WRITE;
/*!40000 ALTER TABLE `fb_targets_cats` DISABLE KEYS */;
INSERT INTO `fb_targets_cats` VALUES (0,'general'),(1,'autos');
/*!40000 ALTER TABLE `fb_targets_cats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `getfbjobsinfo`
--

DROP TABLE IF EXISTS `getfbjobsinfo`;
/*!50001 DROP VIEW IF EXISTS `getfbjobsinfo`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `getfbjobsinfo` (
  `idfb_posts` tinyint NOT NULL,
  `app_credencial` tinyint NOT NULL,
  `target` tinyint NOT NULL,
  `message` tinyint NOT NULL,
  `picture` tinyint NOT NULL,
  `link` tinyint NOT NULL,
  `name` tinyint NOT NULL,
  `description` tinyint NOT NULL,
  `action_link` tinyint NOT NULL,
  `action_name` tinyint NOT NULL,
  `sessionid` tinyint NOT NULL,
  `app` tinyint NOT NULL,
  `secret` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `idjobs` int(11) NOT NULL AUTO_INCREMENT,
  `status` int(11) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL DEFAULT '0',
  `dataId` int(11) NOT NULL DEFAULT '0',
  `datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`idjobs`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
INSERT INTO `jobs` VALUES (1,0,1,1,'2014-07-04 00:11:17'),(4,0,1,2,'2014-07-04 20:35:01');
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobshistorical`
--

DROP TABLE IF EXISTS `jobshistorical`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobshistorical` (
  `idjobs` int(11) NOT NULL AUTO_INCREMENT,
  `status` int(11) NOT NULL,
  `type` int(11) NOT NULL DEFAULT '0',
  `dataId` int(11) NOT NULL DEFAULT '0',
  `datetime` datetime DEFAULT NULL,
  `id` int(11) DEFAULT NULL,
  `exec` text,
  `data_id` tinyint(1) DEFAULT NULL,
  `finished` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `resultado` text,
  PRIMARY KEY (`idjobs`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobshistorical`
--

LOCK TABLES `jobshistorical` WRITE;
/*!40000 ALTER TABLE `jobshistorical` DISABLE KEYS */;
INSERT INTO `jobshistorical` VALUES (1,0,1,0,'2014-07-04 00:11:17',NULL,'D:\\windows/xampp\\php/php.exe  D:/dev/remates/frontend/remates/war/face/cb334.php  \"299398536906086\" \"21c1ef77f030a7b1374de769488d4db7\" \"387953724607663\" \"8794-4598 GANGAAAA! ---SOLO GENTE SERIA---. PRECIO ESPECIAL, AROS DE LUJO 18, XENON DVD TACTIL, SHIFTTRONIC AUTOMATICO MANUAL. VIDA DEL MOTOR EXCELENTE: 90KM. GANGAAAA!  Aproveche no siempre salen autos asi. Le llevo el carro solo gente con el efectivo es algo serio. me urge el dinero no se cambia, me voy del país.  Vea el vídeo y precio aquí: http://autodeportivo.ticoganga.com/ #‎sele‬ ‪#‎costarica‬ ‪#‎sisepudo‬ ‪#‎selecr‬‬ ‪#‎carros\" \"http://new.landingi.com/uploads/12a104cc913a97a3cac4/pictures/d5152516e2c93a0495ba1fc8bd2cb15e.\" \"autodeportivo.ticoganga.com\" \"*.* UFF LUJOSO DEPORTIVO TIBURON PRO 2005 REGALADO! 8794-4598  LLAMEME YA!! *.*\" \"DESC\" \"7778\" \"http://nathanbolin.com\" \"comprar\"',1,'2014-07-05 02:33:45','=======================================================Facebook\\FacebookSession Object|(|    [accessToken:Facebook\\FacebookSession:private] => Facebook\\Entities\\AccessToken Object|        (|            [accessToken:protected] => CAAEQTSdwRWYBAEdzSD8XokK96PPM6FIRrHTO9Sq6vztuuMbdNyv8wCGw5gPhmefoYMZCf8Vl5TxpvtZAdLTL8TEkgy63BXAaqJYxs6K6dJuvZB89aDEkoOSR1gs8nGvjZAZAiKMt5qErxhRB3IFZCA0aMbvvZCuVhCGfU1TQiWk3Mhu5EYNgBZBo|            [machineId:protected] =>|            [expiresAt:protected] =>|        )||    [signedRequest:Facebook\\FacebookSession:private] =>|)|Facebook\\Entities\\AccessToken Object|(|    [accessToken:protected] => CAAEQTSdwRWYBAEdzSD8XokK96PPM6FIRrHTO9Sq6vztuuMbdNyv8wCGw5gPhmefoYMZCf8Vl5TxpvtZAdLTL8TEkgy63BXAaqJYxs6K6dJuvZB89aDEkoOSR1gs8nGvjZAZAiKMt5qErxhRB3IFZCA0aMbvvZCuVhCGfU1TQiWk3Mhu5EYNgBZBo|    [machineId:protected] =>|    [expiresAt:protected] =>|)|-----------------------------------------------------------Array|(|    [access_token] => Facebook\\Entities\\AccessToken Object|        (|            [accessToken:protected] => CAAEQTSdwRWYBAEdzSD8XokK96PPM6FIRrHTO9Sq6vztuuMbdNyv8wCGw5gPhmefoYMZCf8Vl5TxpvtZAdLTL8TEkgy63BXAaqJYxs6K6dJuvZB89aDEkoOSR1gs8nGvjZAZAiKMt5qErxhRB3IFZCA0aMbvvZCuVhCGfU1TQiWk3Mhu5EYNgBZBo|            [machineId:protected] =>|            [expiresAt:protected] =>|        )||    [message] => 8794-4598 GANGAAAA! ---SOLO GENTE SERIA---. PRECIO ESPECIAL, AROS DE LUJO 18, XENON DVD TACTIL, SHIFTTRONIC AUTOMATICO MANUAL. VIDA DEL MOTOR EXCELENTE: 90KM. GANGAAAA!  Aproveche no siempre salen autos asi. Le llevo el carro solo gente con el efectivo es algo serio. me urge el dinero no se cambia, me voy del país.  Vea el vídeo y precio aquí: http://autodeportivo.ticoganga.com/ #‎sele‬ ‪#‎costarica‬ ‪#‎sisepudo‬ ‪#‎selecr‬‬ ‪#‎carros|    [name] => *.* UFF LUJOSO DEPORTIVO TIBURON PRO 2005 REGALADO! 8794-4598  LLAMEME YA!! *.*|    [link] => autodeportivo.ticoganga.com|    [description] => DESC|    [picture] => http://new.landingi.com/uploads/12a104cc913a97a3cac4/pictures/d5152516e2c93a0495ba1fc8bd2cb15e.|)|-----------------------------------------------------------Array|(|    [id] => 387953724607663_663308973738802|)|=======================================================');
/*!40000 ALTER TABLE `jobshistorical` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `iduser` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `login` varchar(45) DEFAULT NULL,
  `password` varchar(45) DEFAULT NULL,
  `metadata` text,
  `email` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`iduser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Final view structure for view `getfbjobsinfo`
--

/*!50001 DROP TABLE IF EXISTS `getfbjobsinfo`*/;
/*!50001 DROP VIEW IF EXISTS `getfbjobsinfo`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `getfbjobsinfo` AS select `fb_posts`.`idfb_posts` AS `idfb_posts`,`fb_posts`.`app_credencial` AS `app_credencial`,`fb_posts`.`target` AS `target`,`fb_posts`.`message` AS `message`,`fb_posts`.`picture` AS `picture`,`fb_posts`.`link` AS `link`,`fb_posts`.`name` AS `name`,`fb_posts`.`description` AS `description`,`fb_posts`.`action_link` AS `action_link`,`fb_posts`.`action_name` AS `action_name`,`fb_posts`.`sessionid` AS `sessionid`,`fb_apps_credenciales`.`app` AS `app`,`fb_apps_credenciales`.`secret` AS `secret` from (`fb_posts` left join `fb_apps_credenciales` on((`fb_apps_credenciales`.`idfb_apps_credenciales` = `fb_posts`.`app_credencial`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-07-08 17:12:48
