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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audittrail`
--

LOCK TABLES `audittrail` WRITE;
/*!40000 ALTER TABLE `audittrail` DISABLE KEYS */;
INSERT INTO `audittrail` VALUES (1,'2014-07-04 04:24:33','/war/login.php','admin','login','127.0.0.1','','','',''),(2,'2014-07-04 04:38:35','/war/logout.php','Administrator','logout','127.0.0.1','','','',''),(3,'2014-07-04 19:29:08','/war/login.php','admin','login','127.0.0.1','','','',''),(4,'2014-07-13 19:55:33','/war/login.php','admin','login','127.0.0.1','','','',''),(5,'2014-07-13 21:10:36','/war/logout.php','Administrator','logout','127.0.0.1','','','',''),(6,'2014-07-13 21:10:37','/war/login.php','admin','login','127.0.0.1','','','','');
/*!40000 ALTER TABLE `audittrail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `credenciales`
--

DROP TABLE IF EXISTS `credenciales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `credenciales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domainid` int(11) DEFAULT NULL,
  `nombre` varchar(45) DEFAULT NULL,
  `app` varchar(245) DEFAULT NULL,
  `secret` varchar(245) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `credenciales`
--

LOCK TABLES `credenciales` WRITE;
/*!40000 ALTER TABLE `credenciales` DISABLE KEYS */;
INSERT INTO `credenciales` VALUES (1,1,'ticoganga.com','299398536906086','21c1ef77f030a7b1374de769488d4db7');
/*!40000 ALTER TABLE `credenciales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `domains`
--

DROP TABLE IF EXISTS `domains`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `domains` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `domains`
--

LOCK TABLES `domains` WRITE;
/*!40000 ALTER TABLE `domains` DISABLE KEYS */;
INSERT INTO `domains` VALUES (1,'facebook grouos');
/*!40000 ALTER TABLE `domains` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `getjobs`
--

DROP TABLE IF EXISTS `getjobs`;
/*!50001 DROP VIEW IF EXISTS `getjobs`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `getjobs` (
  `id` tinyint NOT NULL,
  `status` tinyint NOT NULL,
  `type` tinyint NOT NULL,
  `targetid` tinyint NOT NULL,
  `sessionid` tinyint NOT NULL,
  `datetime` tinyint NOT NULL,
  `dataId` tinyint NOT NULL,
  `credencial` tinyint NOT NULL,
  `app` tinyint NOT NULL,
  `secret` tinyint NOT NULL,
  `fid` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` int(11) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL DEFAULT '0',
  `dataId` int(11) NOT NULL DEFAULT '0',
  `datetime` datetime DEFAULT NULL,
  `targetid` int(11) DEFAULT NULL,
  `credencial` int(11) DEFAULT NULL,
  `sessionid` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
INSERT INTO `jobs` VALUES (3,0,1,1,'2014-07-08 22:21:20',1,1,'1');
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobshistorical`
--

LOCK TABLES `jobshistorical` WRITE;
/*!40000 ALTER TABLE `jobshistorical` DISABLE KEYS */;
INSERT INTO `jobshistorical` VALUES (1,0,1,0,'2013-01-01 01:01:01',NULL,'php  /var/www/autofacebook/libs/face/cb334_.php \"299398536906086\" \"21c1ef77f030a7b1374de769488d4db7\" \"387953724607663\" \"8794-4598 GANGAAAA! ---SOLO GENTE SERIA---. PRECIO ESPECIAL, AROS DE LUJO 18, XENON DVD TACTIL, SHIFTTRONIC AUTOMATICO MANUAL. VIDA DEL MOTOR EXCELENTE: 90KM. GANGAAAA!  Aproveche no siempre salen autos asi. Le llevo el carro solo gente con el efectivo es algo serio. me urge el dinero no se cambia, me voy del país.  Vea el vídeo y precio aquí: http://autodeportivo.ticoganga.com/ #‎sele‬ ‪#‎costarica‬ ‪#‎sisepudo‬ ‪#‎selecr‬‬ ‪#‎carros\" \"http://new.landingi.com/uploads/12a104cc913a97a3cac4/pictures/d5152516e2c93a0495ba1fc8bd2cb15e.\" \"autodeportivo.ticoganga.com\" \"*.* UFF LUJOSO DEPORTIVO TIBURON PRO 2005 REGALADO! 8794-4598  LLAMEME YA!! *.*\" \"DESC\" \"c2\" \"http://nathanbolin.com\" \"comprar\" >> /var/www/autofacebook/logs/j_fb_post.log 2>&1',1,'2014-07-12 15:33:04',''),(2,0,1,0,'2013-01-01 01:01:01',NULL,'php  /var/www/autofacebook/libs/face/cb334_.php \"299398536906086\" \"21c1ef77f030a7b1374de769488d4db7\" \"387953724607663\" \"8794-4598 GANGAAAA! ---SOLO GENTE SERIA---. PRECIO ESPECIAL, AROS DE LUJO 18, XENON DVD TACTIL, SHIFTTRONIC AUTOMATICO MANUAL. VIDA DEL MOTOR EXCELENTE: 90KM. GANGAAAA!  Aproveche no siempre salen autos asi. Le llevo el carro solo gente con el efectivo es algo serio. me urge el dinero no se cambia, me voy del país.  Vea el vídeo y precio aquí: http://autodeportivo.ticoganga.com/ #‎sele‬ ‪#‎costarica‬ ‪#‎sisepudo‬ ‪#‎selecr‬‬ ‪#‎carros\" \"http://new.landingi.com/uploads/12a104cc913a97a3cac4/pictures/d5152516e2c93a0495ba1fc8bd2cb15e.\" \"autodeportivo.ticoganga.com\" \"*.* UFF LUJOSO DEPORTIVO TIBURON PRO 2005 REGALADO! 8794-4598  LLAMEME YA!! *.*\" \"DESC\" \"c2\" \"http://nathanbolin.com\" \"comprar\" >> /var/www/autofacebook/logs/j_fb_post.log 2>&1',1,'2014-07-12 15:45:01',''),(3,0,1,0,'2013-01-01 01:01:01',NULL,'php  /var/www/autofacebook/libs/face/cb334_.php \"299398536906086\" \"21c1ef77f030a7b1374de769488d4db7\" \"387953724607663\" \"8794-4598 GANGAAAA! ---SOLO GENTE SERIA---. PRECIO ESPECIAL, AROS DE LUJO 18, XENON DVD TACTIL, SHIFTTRONIC AUTOMATICO MANUAL. VIDA DEL MOTOR EXCELENTE: 90KM. GANGAAAA!  Aproveche no siempre salen autos asi. Le llevo el carro solo gente con el efectivo es algo serio. me urge el dinero no se cambia, me voy del país.  Vea el vídeo y precio aquí: http://autodeportivo.ticoganga.com/ #‎sele‬ ‪#‎costarica‬ ‪#‎sisepudo‬ ‪#‎selecr‬‬ ‪#‎carros\" \"http://new.landingi.com/uploads/12a104cc913a97a3cac4/pictures/d5152516e2c93a0495ba1fc8bd2cb15e.\" \"autodeportivo.ticoganga.com\" \"*.* UFF LUJOSO DEPORTIVO TIBURON PRO 2005 REGALADO! 8794-4598  LLAMEME YA!! *.*\" \"DESC\" \"c2\" \"http://nathanbolin.com\" \"comprar\" >> /var/www/autofacebook/logs/j_fb_post.log 2>&1',1,'2014-07-12 15:49:04','');
/*!40000 ALTER TABLE `jobshistorical` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` text,
  `picture` text,
  `link` text,
  `name` text,
  `description` text,
  `action_name` text,
  `action_link` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
INSERT INTO `posts` VALUES (1,'8794-4598 GANGAAAA! ---SOLO GENTE SERIA---. PRECIO ESPECIAL, AROS DE LUJO 18, XENON DVD TACTIL, SHIFTTRONIC AUTOMATICO MANUAL. VIDA DEL MOTOR EXCELENTE: 90KM. GANGAAAA!  Aproveche no siempre salen autos asi. Le llevo el carro solo gente con el efectivo es algo serio. me urge el dinero no se cambia, me voy del país.  Vea el vídeo y precio aquí: http://autodeportivo.ticoganga.com/ #‎sele‬ ‪#‎costarica‬ ‪#‎sisepudo‬ ‪#‎selecr‬‬ ‪#‎carros','http://new.landingi.com/uploads/12a104cc913a97a3cac4/pictures/d5152516e2c93a0495ba1fc8bd2cb15e.','autodeportivo.ticoganga.com','*.* UFF LUJOSO DEPORTIVO TIBURON PRO 2005 REGALADO! 8794-4598  LLAMEME YA!! *.*','DESC','comprar','http://nathanbolin.com'),(2,'8794-4598 GANGAAAA! ---SOLO GENTE SERIA---. PRECIO ESPECIAL, Volkswagen Gol 99 - Carro mega economico GANGAAAA!  Vea el vídeo y precio aquí: http://autoeconomico.ticoganga.com/ #‎sele‬ ‪#‎costarica‬ ‪#‎sisepudo‬ ‪#‎selecr‬‬ ‪#‎carros','http://new.landingi.com/uploads/12a104cc913a97a3cac4/pictures/d9f2eab34b29cf1e7d427d1ae1a1846e.jpg','http://autoeconomico.ticoganga.com','*.* UFF Volkswagen Gol 99 ! 8794-4598  LLAMEME YA!! *.*','DESC','comprar','http://nathanbolin.com'),(3,'A','A','A','A','A','A','A');
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `target_categorias`
--

DROP TABLE IF EXISTS `target_categorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `target_categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `target_categorias`
--

LOCK TABLES `target_categorias` WRITE;
/*!40000 ALTER TABLE `target_categorias` DISABLE KEYS */;
INSERT INTO `target_categorias` VALUES (1,'AUTOS'),(2,'GENERAL');
/*!40000 ALTER TABLE `target_categorias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `targets`
--

DROP TABLE IF EXISTS `targets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `targets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(145) NOT NULL,
  `fid` varchar(145) NOT NULL,
  `categoria` int(11) DEFAULT NULL,
  `domainid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `targets`
--

LOCK TABLES `targets` WRITE;
/*!40000 ALTER TABLE `targets` DISABLE KEYS */;
INSERT INTO `targets` VALUES (1,'cr autos','387953724607663',1,1),(2,'CRAUTOS-CRMOTOS-CRCAS','538270862854032',1,1),(3,'Grupo Del Cambalache','159944514166912',0,1),(4,'Que le Vendo Que le Compro','278458938931770',0,1),(5,'Compra y Venda en Costa Rica','447025715327233',0,1),(6,'Compra y Venta para Costa Rica','353603508060711',0,1),(7,'Auto Barato CR','355585494581050',1,1),(8,'Compra y Venta Costa Rica','413212712086732',0,1),(9,'GrupoHyundaiCR','563788747009614',1,1),(10,'Cambalache de carros','181615495329572',1,1),(11,'comprarvendercambiar','196412997182957',0,1),(12,'sevendecompracambiacr','310429702361405',0,1),(13,'sevendesecompraysecambia','166297816770483',0,1);
/*!40000 ALTER TABLE `targets` ENABLE KEYS */;
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
-- Final view structure for view `getjobs`
--

/*!50001 DROP TABLE IF EXISTS `getjobs`*/;
/*!50001 DROP VIEW IF EXISTS `getjobs`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `getjobs` AS select `jobs`.`id` AS `id`,`jobs`.`status` AS `status`,`jobs`.`type` AS `type`,`jobs`.`targetid` AS `targetid`,`jobs`.`sessionid` AS `sessionid`,`jobs`.`datetime` AS `datetime`,`jobs`.`dataId` AS `dataId`,`jobs`.`credencial` AS `credencial`,`credenciales`.`app` AS `app`,`credenciales`.`secret` AS `secret`,`targets`.`fid` AS `fid` from ((`jobs` join `credenciales` on((`jobs`.`credencial` = `credenciales`.`id`))) join `targets` on((`jobs`.`targetid` = `targets`.`id`))) */;
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

-- Dump completed on 2014-07-13 22:28:56
