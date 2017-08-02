-- MySQL dump 10.13  Distrib 5.5.53, for Win32 (AMD64)
--
-- Host: localhost    Database: leadpointcrm
-- ------------------------------------------------------
-- Server version	5.5.53

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
-- Table structure for table `campaign_lead_fields_rel`
--

DROP TABLE IF EXISTS `campaign_lead_fields_rel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `campaign_lead_fields_rel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `campaign_id` int(30) NOT NULL,
  `field_id` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `mandatory` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=452 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `campaigns`
--

DROP TABLE IF EXISTS `campaigns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `campaigns` (
  `id` tinyint(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL DEFAULT '',
  `source` varchar(32) NOT NULL DEFAULT '',
  `cost` float DEFAULT NULL,
  `status` tinyint(2) DEFAULT NULL,
  `user_id` int(5) DEFAULT NULL,
  `NSW` int(11) DEFAULT NULL,
  `QLD` int(11) DEFAULT NULL,
  `SA` int(11) DEFAULT NULL,
  `TAS` int(11) DEFAULT NULL,
  `VIC` int(11) DEFAULT NULL,
  `WA` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `client_campaigns`
--

DROP TABLE IF EXISTS `client_campaigns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client_campaigns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `camp_name` varchar(120) CHARACTER SET utf8 NOT NULL,
  `weekly` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `postcodes` text CHARACTER SET utf8 NOT NULL,
  `camp_status` tinyint(4) NOT NULL,
  `coords` varchar(255) DEFAULT NULL,
  `radius` int(4) NOT NULL,
  `nearest` int(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=121 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clients` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `campaign_name` varchar(120) DEFAULT NULL,
  `email` varchar(120) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `full_name` varchar(120) DEFAULT NULL,
  `lead_cost` decimal(5,2) DEFAULT NULL,
  `phone` varchar(14) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(60) DEFAULT NULL,
  `state` varchar(320) DEFAULT NULL,
  `country` varchar(60) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `abn` varchar(255) DEFAULT NULL,
  `authorised_person` varchar(100) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `name_on_card` varchar(100) DEFAULT NULL,
  `credit_card_number` varchar(50) DEFAULT NULL,
  `expires_mm` int(11) DEFAULT NULL,
  `expires_yy` int(11) DEFAULT NULL,
  `cvc` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=249 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `clients_billing`
--

DROP TABLE IF EXISTS `clients_billing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clients_billing` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `xero_id` int(30) NOT NULL,
  `xero_name` varchar(120) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=249 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `clients_criteria`
--

DROP TABLE IF EXISTS `clients_criteria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clients_criteria` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weekly` int(11) DEFAULT NULL,
  `states_filter` varchar(60) DEFAULT '',
  `postcodes` text,
  `coords` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=249 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `lead_conversations`
--

DROP TABLE IF EXISTS `lead_conversations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lead_conversations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lead_id` int(11) NOT NULL,
  `author` int(3) NOT NULL,
  `message` varchar(255) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `seen` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3932 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `lead_fields`
--

DROP TABLE IF EXISTS `lead_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lead_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(60) NOT NULL DEFAULT '',
  `name` varchar(60) NOT NULL DEFAULT '',
  `type` varchar(32) CHARACTER SET latin1 DEFAULT 'text',
  `object` varchar(300) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `lead_settings`
--

DROP TABLE IF EXISTS `lead_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lead_settings` (
  `days` int(3) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `leads`
--

DROP TABLE IF EXISTS `leads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `leads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `campaign_id` int(11) NOT NULL,
  `datetime` varchar(30) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12771 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `leads_delivery`
--

DROP TABLE IF EXISTS `leads_delivery`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `leads_delivery` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `lead_id` int(11) DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `timedate` int(10) unsigned DEFAULT NULL,
  `open_email` tinyint(4) DEFAULT NULL,
  `open_time` int(10) unsigned DEFAULT NULL,
  `camp_id` int(11) NOT NULL,
  `seen` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11251 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `leads_lead_fields_rel`
--

DROP TABLE IF EXISTS `leads_lead_fields_rel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `leads_lead_fields_rel` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `source` varchar(10) NOT NULL DEFAULT '',
  `full_name` varchar(60) DEFAULT NULL,
  `email` varchar(60) DEFAULT NULL,
  `phone` varchar(14) DEFAULT NULL,
  `address` varchar(120) DEFAULT NULL,
  `city` varchar(60) DEFAULT NULL,
  `state` varchar(12) DEFAULT NULL,
  `country` varchar(60) DEFAULT NULL,
  `postcode` varchar(6) DEFAULT NULL,
  `suburb` varchar(60) DEFAULT NULL,
  `system_size` varchar(60) DEFAULT NULL,
  `roof_type` varchar(60) DEFAULT NULL,
  `house_type` varchar(60) DEFAULT NULL,
  `system_for` varchar(60) DEFAULT NULL,
  `electricity` varchar(60) DEFAULT NULL,
  `house_age` varchar(60) DEFAULT NULL,
  `note` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12771 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `leads_rejection`
--

DROP TABLE IF EXISTS `leads_rejection`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `leads_rejection` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int(11) DEFAULT NULL,
  `lead_id` int(11) DEFAULT NULL,
  `reason` enum('Outside of nominated area service','Duplicate','Incorrect Phone Number','Indicated they won''t purchase the specified service within 6 month','Customer is wanting Off Grid System','Unable to contact within 7 days') DEFAULT NULL,
  `approval` tinyint(11) DEFAULT NULL,
  `date` varchar(30) DEFAULT NULL,
  `note` varchar(500) DEFAULT NULL,
  `decline_reason` varchar(500) DEFAULT NULL,
  `audiofile` varchar(255) DEFAULT NULL,
  `camp_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11251 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `queue`
--

DROP TABLE IF EXISTS `queue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `queue` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `id_lead` int(20) NOT NULL,
  `id_client` int(20) NOT NULL,
  `timedata` int(10) NOT NULL,
  `status` tinyint(20) NOT NULL,
  `timequeue` int(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `states_postcodes`
--

DROP TABLE IF EXISTS `states_postcodes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `states_postcodes` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `state` enum('NSW','QLD','SA','TAS','VIC','WA','ACT') NOT NULL,
  `postcodes` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `token`
--

DROP TABLE IF EXISTS `token`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `token` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `token` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` smallint(5) NOT NULL AUTO_INCREMENT,
  `email` varchar(60) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `level` tinyint(4) NOT NULL,
  `active` tinyint(4) NOT NULL,
  `full_name` varchar(32) DEFAULT NULL,
  `rem_the_sys` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=249 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-07-27 13:30:35
