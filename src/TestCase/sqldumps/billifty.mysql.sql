/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.8.6-MariaDB, for debian-linux-gnu (aarch64)
--
-- Host: mysql    Database: app_db
-- ------------------------------------------------------
-- Server version	9.6.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `business_profiles`
--

DROP TABLE IF EXISTS `business_profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `business_profiles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `legal_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `license_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_line1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_line2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo_disk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'public',
  `logo_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branding_json` json DEFAULT NULL,
  `is_test` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `business_profiles_user_id_foreign` (`user_id`),
  CONSTRAINT `business_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `business_profiles`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `business_profiles` WRITE;
/*!40000 ALTER TABLE `business_profiles` DISABLE KEYS */;
INSERT INTO `business_profiles` VALUES
(1,4,'Test Company LLC','Test Company LLC','test_company_llc@gmail.com','87365245111','','','','129 Bernham street',NULL,'Houston','TX','1222','US','public',NULL,NULL,0,'2026-05-18 23:51:37','2026-05-18 23:51:37',NULL),
(2,4,'ILLCity Clothing LLC','ILLCity Clothing LLC','illCityClothing@gmail.com','87365245311','','','','7099 Blair Stone Rd',NULL,'Tallahasse','FL','32301','US','public',NULL,NULL,0,'2026-05-18 23:51:37','2026-05-18 23:51:37',NULL),
(4,6,'RASA Entertainment Inc.','RASA Entertainment Inc.','studio@rasaentertainment.com',NULL,NULL,'23234324-0921',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'public',NULL,NULL,0,'2026-05-19 00:35:02','2026-05-19 00:35:02',NULL),
(5,6,'Kaynamz International LLC','Kaynamz International LLC','gigs@kaynamz.com',NULL,NULL,'2345345-0091',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'public','logo_path/2026/5/logo_AsdNWYlr06JUSgVBj7FOmFSfE0MGWVW1TWipHKES.png',NULL,0,'2026-05-19 23:55:37','2026-05-19 23:55:37',NULL),
(6,6,'Mang Inasal LLC','Mang Inasal LLC','office@manginasal.com.ph',NULL,NULL,'EIN-009128981-091',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'public',NULL,NULL,0,'2026-05-19 23:57:17','2026-05-19 23:57:17',NULL);
/*!40000 ALTER TABLE `business_profiles` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `clients` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `license_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_line1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_line2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta` json DEFAULT NULL,
  `is_test` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `clients_user_id_foreign` (`user_id`),
  CONSTRAINT `clients_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clients`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `clients` WRITE;
/*!40000 ALTER TABLE `clients` DISABLE KEYS */;
INSERT INTO `clients` VALUES
(1,4,'John Doe','EvaSoft LLC','johndoe@gmail.com','9876381234','','','7900 S Post Oak',NULL,'Houston','TX','77890','US',NULL,1,'2026-05-18 23:51:37','2026-05-18 23:51:37',NULL),
(2,4,'Harry Doe','Wee LLC','harry@gmail.com','9876316234','','','1922 Pleasant Groove Rd',NULL,'Houston','TX','77840','US',NULL,1,'2026-05-18 23:51:37','2026-05-18 23:51:37',NULL),
(3,6,'Tina Pay','Rippled By Jen LLC','store@ripplesbyjenny.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,'2026-05-19 00:48:57','2026-05-19 00:48:57',NULL);
/*!40000 ALTER TABLE `clients` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `color_scheme`
--

DROP TABLE IF EXISTS `color_scheme`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `color_scheme` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `color_scheme_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `preview_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `color_scheme`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `color_scheme` WRITE;
/*!40000 ALTER TABLE `color_scheme` DISABLE KEYS */;
INSERT INTO `color_scheme` VALUES
(1,'Ocean Blue','ocean',NULL,'/images/invoice-selection/ocean-blue.png','2026-05-18 23:51:37','2026-05-18 23:51:37'),
(2,'Forest Green','forest',NULL,'/images/invoice-selection/forest-green.png','2026-05-18 23:51:37','2026-05-18 23:51:37'),
(3,'Royal Purple','royal',NULL,'/images/invoice-selection/royal-purple.png','2026-05-18 23:51:37','2026-05-18 23:51:37'),
(4,'Crimson Red','crimson',NULL,'/images/invoice-selection/crimson-red.png','2026-05-18 23:51:37','2026-05-18 23:51:37'),
(5,'Sunset Orange','sunset',NULL,'/images/invoice-selection/sunset-orange.png','2026-05-18 23:51:37','2026-05-18 23:51:37');
/*!40000 ALTER TABLE `color_scheme` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `color_scheme_color`
--

DROP TABLE IF EXISTS `color_scheme_color`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `color_scheme_color` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `color_scheme_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `color_scheme_color_color_scheme_id_foreign` (`color_scheme_id`),
  CONSTRAINT `color_scheme_color_color_scheme_id_foreign` FOREIGN KEY (`color_scheme_id`) REFERENCES `color_scheme` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `color_scheme_color`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `color_scheme_color` WRITE;
/*!40000 ALTER TABLE `color_scheme_color` DISABLE KEYS */;
INSERT INTO `color_scheme_color` VALUES
(1,3,'main','#8B5CF6','2026-05-18 23:51:37','2026-05-18 23:51:37'),
(2,3,'light','#D8B4FE','2026-05-18 23:51:37','2026-05-18 23:51:37'),
(3,3,'extra_light','rgba(253, 242, 248, 0.3)','2026-05-18 23:51:37','2026-05-18 23:51:37'),
(4,3,'gradient_bg_1','90deg,rgba(147, 51, 234, 1) 0%, rgba(168, 85, 247, 0.67) 55%, rgba(236, 72, 153, 1) 100%','2026-05-18 23:51:37','2026-05-18 23:51:37'),
(5,3,'table_tbody_color','#FDF2F8','2026-05-18 23:51:37','2026-05-18 23:51:37'),
(6,3,'gradient_bg_1_light','142deg, rgba(255, 255, 255, 1) 0%, rgba(238, 242, 255, 1) 100%','2026-05-18 23:51:37','2026-05-18 23:51:37'),
(7,1,'main','#3B82F6','2026-05-18 23:51:37','2026-05-18 23:51:37'),
(8,1,'light','#93C5FD','2026-05-18 23:51:37','2026-05-18 23:51:37'),
(9,1,'extra_light','rgba(255, 255, 255, 0.3)','2026-05-18 23:51:37','2026-05-18 23:51:37'),
(10,1,'gradient_bg_1','90deg,#020024 0%, #090979 35%, #00D4FF 100%','2026-05-18 23:51:37','2026-05-18 23:51:37'),
(11,1,'table_tbody_color','','2026-05-18 23:51:37','2026-05-18 23:51:37'),
(12,1,'gradient_bg_1_light','142deg, rgba(255, 255, 255, 1) 0%, rgba(238, 242, 255, 1) 100%','2026-05-18 23:51:37','2026-05-18 23:51:37'),
(13,2,'main','#22C55E','2026-05-18 23:51:37','2026-05-18 23:51:37'),
(14,2,'light','#86EFAC','2026-05-18 23:51:37','2026-05-18 23:51:37'),
(15,2,'extra_light','rgba(255, 255, 255, 0.3)','2026-05-18 23:51:37','2026-05-18 23:51:37'),
(16,2,'gradient_bg_1','90deg,#2A7B9B 0%, #57C785 50%, #EDDD53 100%','2026-05-18 23:51:37','2026-05-18 23:51:37'),
(17,2,'table_tbody_color','','2026-05-18 23:51:37','2026-05-18 23:51:37'),
(18,2,'gradient_bg_1_light','142deg, rgba(255, 255, 255, 1) 0%, rgba(238, 242, 255, 1) 100%','2026-05-18 23:51:37','2026-05-18 23:51:37'),
(19,4,'main','#EF4444','2026-05-18 23:51:37','2026-05-18 23:51:37'),
(20,4,'light','#FCA5A5','2026-05-18 23:51:37','2026-05-18 23:51:37'),
(21,4,'extra_light','rgba(255, 255, 255, 0.3)','2026-05-18 23:51:37','2026-05-18 23:51:37'),
(22,4,'gradient_bg_1','90deg,rgba(253, 29, 29, 1) 0%, rgba(252, 176, 69, 0.67) 55%, rgba(235, 143, 143, 1) 79%','2026-05-18 23:51:37','2026-05-18 23:51:37'),
(23,4,'table_tbody_color','','2026-05-18 23:51:37','2026-05-18 23:51:37'),
(24,4,'gradient_bg_1_light','','2026-05-18 23:51:37','2026-05-18 23:51:37'),
(25,5,'main','#F97316','2026-05-18 23:51:37','2026-05-18 23:51:37'),
(26,5,'light','#FDBA74','2026-05-18 23:51:37','2026-05-18 23:51:37'),
(27,5,'extra_light','rgba(255, 255, 255, 0.3)','2026-05-18 23:51:37','2026-05-18 23:51:37'),
(28,5,'gradient_bg_1','142deg,rgba(249, 115, 22, 1) 1%, rgba(253, 186, 116, 1) 100%','2026-05-18 23:51:37','2026-05-18 23:51:37'),
(29,5,'table_tbody_color','','2026-05-18 23:51:37','2026-05-18 23:51:37'),
(30,5,'gradient_bg_1_light','142deg, rgba(255, 255, 255, 1) 0%, rgba(238, 242, 255, 1) 100%','2026-05-18 23:51:37','2026-05-18 23:51:37');
/*!40000 ALTER TABLE `color_scheme_color` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `currency`
--

DROP TABLE IF EXISTS `currency`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `currency` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `symbol` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `precision` tinyint NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `currency`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `currency` WRITE;
/*!40000 ALTER TABLE `currency` DISABLE KEYS */;
INSERT INTO `currency` VALUES
(1,'USD','United States Dollar','$',2,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(2,'EUR','Euro','€',2,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(3,'GBP','British Pound Sterling','£',2,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(4,'JPY','Japanese Yen','¥',0,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(5,'AUD','Australian Dollar','A$',2,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(6,'CAD','Canadian Dollar','C$',2,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(7,'CHF','Swiss Franc','CHF',2,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(8,'CNY','Chinese Yuan Renminbi','¥',2,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(9,'HKD','Hong Kong Dollar','HK$',2,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(10,'NZD','New Zealand Dollar','NZ$',2,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(11,'SGD','Singapore Dollar','S$',2,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(12,'SEK','Swedish Krona','kr',2,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(13,'NOK','Norwegian Krone','kr',2,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(14,'DKK','Danish Krone','kr',2,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(15,'INR','Indian Rupee','₹',2,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(16,'KRW','South Korean Won','₩',0,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(17,'ZAR','South African Rand','R',2,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(18,'BRL','Brazilian Real','R$',2,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(19,'MXN','Mexican Peso','$',2,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(20,'PHP','Philippine Peso','₱',2,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(21,'THB','Thai Baht','฿',2,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(22,'AED','UAE Dirham','د.إ',2,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(23,'SAR','Saudi Riyal','﷼',2,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(24,'TRY','Turkish Lira','₺',2,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(25,'RUB','Russian Ruble','₽',2,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(26,'PLN','Polish Zloty','zł',2,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(27,'HUF','Hungarian Forint','Ft',0,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(28,'CZK','Czech Koruna','Kč',2,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(29,'ILS','Israeli Shekel','₪',2,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(30,'MYR','Malaysian Ringgit','RM',2,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(31,'IDR','Indonesian Rupiah','Rp',0,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(32,'VND','Vietnamese Dong','₫',0,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(33,'PKR','Pakistani Rupee','₨',2,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(34,'BDT','Bangladeshi Taka','৳',2,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(35,'NGN','Nigerian Naira','₦',2,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(36,'EGP','Egyptian Pound','£',2,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(37,'KES','Kenyan Shilling','KSh',2,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(38,'GHS','Ghanaian Cedi','₵',2,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(39,'CLP','Chilean Peso','$',0,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(40,'ARS','Argentine Peso','$',2,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(41,'COP','Colombian Peso','$',2,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(42,'PEN','Peruvian Sol','S/',2,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(43,'UYU','Uruguayan Peso','$U',2,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(44,'TWD','New Taiwan Dollar','NT$',2,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(45,'QAR','Qatari Riyal','﷼',2,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(46,'BHD','Bahraini Dinar','.د.ب',3,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(47,'OMR','Omani Rial','﷼',3,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(48,'KWD','Kuwaiti Dinar','د.ك',3,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(49,'LKR','Sri Lankan Rupee','Rs',2,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(50,'MMK','Myanmar Kyat','K',0,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(51,'NPR','Nepalese Rupee','₨',2,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(52,'BND','Brunei Dollar','B$',2,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(53,'LAK','Lao Kip','₭',2,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(54,'KHR','Cambodian Riel','៛',2,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(55,'MOP','Macanese Pataca','MOP$',2,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(56,'BMD','Bermudian Dollar','$',2,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(57,'JMD','Jamaican Dollar','J$',2,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(58,'TTD','Trinidad and Tobago Dollar','TT$',2,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(59,'BBD','Barbadian Dollar','Bds$',2,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(60,'XOF','West African CFA Franc','CFA',0,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(61,'XAF','Central African CFA Franc','FCFA',0,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(62,'MUR','Mauritian Rupee','₨',2,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(63,'SCR','Seychellois Rupee','₨',2,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(64,'TZS','Tanzanian Shilling','TSh',2,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(65,'UGX','Ugandan Shilling','USh',0,1,'2026-05-18 23:51:37','2026-05-18 23:51:37');
/*!40000 ALTER TABLE `currency` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `invoice_items`
--

DROP TABLE IF EXISTS `invoice_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `invoice_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint unsigned NOT NULL,
  `position` int unsigned NOT NULL DEFAULT '1',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `quantity` decimal(12,4) NOT NULL DEFAULT '1.0000',
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_price_cents` bigint NOT NULL DEFAULT '0',
  `line_discount_cents` bigint NOT NULL DEFAULT '0',
  `line_discount_rate` decimal(8,4) NOT NULL DEFAULT '0.0000',
  `tax_rate` decimal(8,4) NOT NULL DEFAULT '0.0000',
  `tax_cents` bigint NOT NULL DEFAULT '0',
  `line_total_cents` bigint NOT NULL DEFAULT '0',
  `meta` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoice_items_invoice_id_position_index` (`invoice_id`,`position`),
  CONSTRAINT `invoice_items_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoice_items`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `invoice_items` WRITE;
/*!40000 ALTER TABLE `invoice_items` DISABLE KEYS */;
INSERT INTO `invoice_items` VALUES
(1,1,1,'User Login Authentication','Create a functinality for the user where they all be needed for verification before they proceed.',1.0000,'',20000,0,0.0000,0.0000,0,20000,NULL,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(2,1,1,'Landing Page Design','Home Page Design',2.0000,'',15050,0,0.0000,0.0000,0,30100,NULL,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(3,1,1,'Logo Design','Logo Design',2.0000,'',5000,0,0.0000,0.0000,0,10000,NULL,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(4,2,1,NULL,'RTW',100.0000,NULL,2000,0,0.0000,10.0000,20000,220000,NULL,'2026-05-19 00:49:35','2026-05-19 00:49:35'),
(5,3,1,NULL,'Beauty Care',400.0000,NULL,2500,0,0.0000,0.0000,0,1000000,NULL,'2026-05-19 07:22:05','2026-05-19 07:22:05'),
(6,3,2,NULL,'Lip Stick',300.0000,NULL,3400,0,0.0000,0.0000,0,1020000,NULL,'2026-05-19 07:22:05','2026-05-19 07:22:05'),
(7,4,1,NULL,'Clothes',20.0000,NULL,4000,0,0.0000,0.0000,0,80000,NULL,'2026-05-19 16:09:58','2026-05-19 16:09:58'),
(8,4,2,NULL,'Cap',30.0000,NULL,3500,0,0.0000,0.0000,0,105000,NULL,'2026-05-19 16:09:58','2026-05-19 16:09:58'),
(9,5,1,NULL,'Contentency',1.0000,NULL,50000,0,0.0000,0.0000,0,50000,NULL,'2026-05-19 23:01:10','2026-05-19 23:01:10'),
(10,6,1,NULL,'Lip Balm',20.0000,NULL,5500,0,0.0000,0.0000,0,110000,NULL,'2026-05-19 23:27:23','2026-05-19 23:27:23'),
(11,7,1,NULL,'Thread #33421',20.0000,NULL,2500,0,0.0000,10.0000,5000,55000,NULL,'2026-05-19 23:45:16','2026-05-20 01:43:26'),
(12,7,2,NULL,'Underwears',30.0000,NULL,3200,0,0.0000,10.0000,9600,105600,NULL,'2026-05-20 01:43:26','2026-05-20 01:43:26'),
(13,8,1,NULL,'Watch',1.0000,NULL,70000,0,0.0000,0.0000,0,70000,NULL,'2026-05-20 02:41:42','2026-05-20 02:41:42'),
(14,8,2,NULL,'Mac Book Pro',1.0000,NULL,280000,0,0.0000,0.0000,0,280000,NULL,'2026-05-20 02:41:42','2026-05-20 02:41:42');
/*!40000 ALTER TABLE `invoice_items` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `invoice_template_categories`
--

DROP TABLE IF EXISTS `invoice_template_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `invoice_template_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `preview_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int unsigned NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `metadata` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoice_template_categories_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoice_template_categories`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `invoice_template_categories` WRITE;
/*!40000 ALTER TABLE `invoice_template_categories` DISABLE KEYS */;
INSERT INTO `invoice_template_categories` VALUES
(1,'modern','Modern','/images/invoice-selection/modern.png',1,1,'[]','2026-05-18 23:51:37','2026-05-18 23:51:37'),
(2,'classic','Classic','/images/invoice-selection/classic.png',2,1,'[]','2026-05-18 23:51:37','2026-05-18 23:51:37'),
(3,'minimal','Minimal','/images/invoice-selection/minimal.png',3,1,'[]','2026-05-18 23:51:37','2026-05-18 23:51:37');
/*!40000 ALTER TABLE `invoice_template_categories` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `invoice_template_versions`
--

DROP TABLE IF EXISTS `invoice_template_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `invoice_template_versions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `invoice_template_id` bigint unsigned NOT NULL,
  `version` int unsigned NOT NULL,
  `changelog` json DEFAULT NULL,
  `released_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoice_template_versions_invoice_template_id_foreign` (`invoice_template_id`),
  CONSTRAINT `invoice_template_versions_invoice_template_id_foreign` FOREIGN KEY (`invoice_template_id`) REFERENCES `invoice_templates` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoice_template_versions`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `invoice_template_versions` WRITE;
/*!40000 ALTER TABLE `invoice_template_versions` DISABLE KEYS */;
/*!40000 ALTER TABLE `invoice_template_versions` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `invoice_templates`
--

DROP TABLE IF EXISTS `invoice_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `invoice_templates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `invoice_template_category_id` bigint unsigned NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `current_version` int unsigned NOT NULL DEFAULT '1',
  `preview_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `metadata` json DEFAULT NULL,
  `view` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoice_templates_slug_unique` (`slug`),
  KEY `invoice_templates_invoice_template_category_id_foreign` (`invoice_template_category_id`),
  CONSTRAINT `invoice_templates_invoice_template_category_id_foreign` FOREIGN KEY (`invoice_template_category_id`) REFERENCES `invoice_template_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoice_templates`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `invoice_templates` WRITE;
/*!40000 ALTER TABLE `invoice_templates` DISABLE KEYS */;
INSERT INTO `invoice_templates` VALUES
(1,1,'moderno','Moderno',1,'/images/templates/moderno.jpg',1,NULL,'modern.v1.moderno','2026-05-18 23:51:37','2026-05-18 23:51:37'),
(2,1,'neo','Neo',1,'/images/templates/neo.jpg',1,NULL,'modern.v1.neo-columns','2026-05-18 23:51:37','2026-05-18 23:51:37'),
(3,1,'mono','Mono',1,'/images/templates/mono.jpg',1,NULL,'modern.v1.mono','2026-05-18 23:51:37','2026-05-18 23:51:37'),
(4,2,'aurora','Aurora',1,'/images/templates/aurora.jpg',1,NULL,'classic.v1.aurora','2026-05-18 23:51:37','2026-05-18 23:51:37'),
(5,2,'ledger','Ledger',1,'/images/templates/ledger.jpg',1,NULL,'classic.v1.ledger','2026-05-18 23:51:37','2026-05-18 23:51:37'),
(6,2,'simplifi','Simplifi',1,'/images/templates/simplifi.jpg',1,NULL,'classic.v1.simplifi','2026-05-18 23:51:37','2026-05-18 23:51:37'),
(7,3,'nexxus','Nexxus',1,'/images/templates/nexxus.jpg',1,NULL,'minimal.v1.nexxus','2026-05-18 23:51:37','2026-05-18 23:51:37'),
(8,3,'pulse','Pulse',1,'/images/templates/pulse.jpg',1,NULL,'minimal.v1.pulse','2026-05-18 23:51:37','2026-05-18 23:51:37');
/*!40000 ALTER TABLE `invoice_templates` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `invoices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `workspace_id` bigint unsigned NOT NULL,
  `business_profile_id` bigint unsigned NOT NULL,
  `client_id` bigint unsigned NOT NULL,
  `invoice_template_id` bigint unsigned NOT NULL,
  `color_scheme_id` bigint unsigned NOT NULL,
  `currency_id` bigint unsigned NOT NULL,
  `invoice_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_address` text COLLATE utf8mb4_unicode_ci,
  `issued_on` date DEFAULT NULL,
  `issued_at` date DEFAULT NULL,
  `due_on` date DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `status` enum('draft','issued','sent','partially','paid','void') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `template_slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `template_version` int unsigned NOT NULL DEFAULT '1',
  `theme_json` json DEFAULT NULL,
  `subtotal_cents` bigint NOT NULL DEFAULT '0',
  `discount_mode` enum('none','amount','percent','per-line') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'none',
  `discount_cents` bigint NOT NULL DEFAULT '0',
  `discount_rate` decimal(8,2) NOT NULL DEFAULT '0.00',
  `tax_cents` bigint NOT NULL DEFAULT '0',
  `shipping_cents` bigint NOT NULL DEFAULT '0',
  `shipping_tax_rate` decimal(6,3) NOT NULL DEFAULT '0.000',
  `shipping_tax_cents` bigint NOT NULL DEFAULT '0',
  `total_cents` bigint NOT NULL DEFAULT '0',
  `amount_due_cents` bigint NOT NULL DEFAULT '0',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `terms` text COLLATE utf8mb4_unicode_ci,
  `pdf_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `render_snapshot_html` longtext COLLATE utf8mb4_unicode_ci,
  `meta` json DEFAULT NULL,
  `pdf_path` text COLLATE utf8mb4_unicode_ci,
  `pdf_disk` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `csv_path` text COLLATE utf8mb4_unicode_ci,
  `is_test` tinyint NOT NULL DEFAULT '0',
  `pdf_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pdf_generated_at` timestamp NULL DEFAULT NULL,
  `pdf_error` text COLLATE utf8mb4_unicode_ci,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoices_business_profile_id_foreign` (`business_profile_id`),
  KEY `invoices_client_id_foreign` (`client_id`),
  KEY `invoices_invoice_template_id_foreign` (`invoice_template_id`),
  KEY `invoices_color_scheme_id_foreign` (`color_scheme_id`),
  KEY `invoices_currency_id_foreign` (`currency_id`),
  KEY `invoices_user_invoice_unique` (`workspace_id`,`invoice_number`),
  CONSTRAINT `invoices_business_profile_id_foreign` FOREIGN KEY (`business_profile_id`) REFERENCES `business_profiles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `invoices_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `invoices_color_scheme_id_foreign` FOREIGN KEY (`color_scheme_id`) REFERENCES `color_scheme` (`id`) ON DELETE CASCADE,
  CONSTRAINT `invoices_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currency` (`id`) ON DELETE CASCADE,
  CONSTRAINT `invoices_invoice_template_id_foreign` FOREIGN KEY (`invoice_template_id`) REFERENCES `invoice_templates` (`id`) ON DELETE CASCADE,
  CONSTRAINT `invoices_workspace_id_foreign` FOREIGN KEY (`workspace_id`) REFERENCES `workspace` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoices`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `invoices` WRITE;
/*!40000 ALTER TABLE `invoices` DISABLE KEYS */;
INSERT INTO `invoices` VALUES
(1,4,1,1,1,1,1,'INV-0001',NULL,NULL,NULL,NULL,NULL,NULL,'draft','test-company-llc',1,NULL,60100,'none',0,0.00,0,0,0.000,0,60100,0,'','',NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(2,6,4,3,1,2,1,'RPL-00001',NULL,NULL,'2026-05-18',NULL,NULL,'2026-05-19 07:01:01','paid',NULL,1,NULL,200000,'amount',150000,0.00,20000,0,0.000,0,70000,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,'2026-05-19 00:49:35','2026-05-19 07:01:01'),
(3,6,4,3,6,2,20,'RPL-00002',NULL,NULL,'2026-05-19',NULL,NULL,'2026-05-19 07:23:57','paid',NULL,1,NULL,2020000,'amount',1500000,0.00,0,0,0.000,0,520000,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,'2026-05-19 07:22:05','2026-05-19 07:23:57'),
(4,6,4,3,8,2,20,'RPL-00003',NULL,NULL,'2026-05-19','2026-05-19',NULL,'2026-05-19 22:57:09','paid',NULL,1,NULL,185000,'none',0,0.00,0,0,0.000,0,185000,0,NULL,NULL,NULL,NULL,NULL,'invoice_pdfs/2026/05/rpl_00003_f25db660.pdf','public','csv-invoices/invoices/2026/5/6/invoice-RPL-00003-20260519-161332-63ca7ace.csv',0,'ready','2026-05-19 16:13:32',NULL,NULL,'2026-05-19 16:09:58','2026-05-19 22:57:09'),
(5,6,4,3,1,2,20,'RPL-00004',NULL,NULL,'2026-05-19','2026-05-19',NULL,'2026-05-19 23:04:40','paid',NULL,1,NULL,50000,'none',0,0.00,0,0,0.000,0,50000,50000,NULL,NULL,NULL,NULL,NULL,'invoice_pdfs/2026/05/rpl_00004_7ddf4baf.pdf','public',NULL,0,'ready','2026-05-19 23:02:21',NULL,NULL,'2026-05-19 23:01:10','2026-05-19 23:04:40'),
(6,6,4,3,6,2,20,'RPL-00005',NULL,NULL,'2026-05-19','2026-05-19',NULL,'2026-05-19 23:29:16','paid',NULL,1,NULL,110000,'none',0,0.00,0,0,0.000,0,110000,0,NULL,NULL,NULL,NULL,NULL,'invoice_pdfs/2026/05/rpl_00005_fd10cf1b.pdf','public',NULL,0,'ready','2026-05-19 23:27:43',NULL,NULL,'2026-05-19 23:27:23','2026-05-19 23:29:16'),
(7,6,4,3,4,1,3,'RPL-00006',NULL,NULL,'2026-05-19',NULL,NULL,NULL,'draft',NULL,1,NULL,146000,'percent',43800,30.00,14600,0,0.000,0,116800,116800,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,'2026-05-19 23:45:16','2026-05-20 01:43:26'),
(8,6,4,3,6,2,6,'RPL-00007',NULL,'12911 SW Rimville','2026-05-19',NULL,NULL,NULL,'draft',NULL,1,NULL,350000,'none',0,0.00,0,4500,10.000,450,354950,354950,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,'2026-05-20 02:41:42','2026-05-20 02:43:54');
/*!40000 ALTER TABLE `invoices` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES
(1,'0001_01_01_000000_create_plans_table',1),
(2,'0001_01_01_000000_create_users_table',1),
(3,'0001_01_01_000001_create_cache_table',1),
(4,'0001_01_01_000001_user_subscriptions',1),
(5,'0001_01_01_000002_create_jobs_table',1),
(6,'2025_03_25_000806_create_workspace_table',1),
(7,'2025_03_26_120000_backfill_invoice_workspaces',1),
(8,'2025_10_09_163456_create_oauth_auth_codes_table',1),
(9,'2025_10_09_163457_create_oauth_access_tokens_table',1),
(10,'2025_10_09_163458_create_oauth_refresh_tokens_table',1),
(11,'2025_10_09_163459_create_oauth_clients_table',1),
(12,'2025_10_09_163500_create_oauth_device_codes_table',1),
(13,'2025_10_19_172528_create_table_payment_information',1),
(14,'2025_10_20_034007_create_business_profiles_table',1),
(15,'2025_10_20_035753_create_clients_table',1),
(16,'2025_10_20_040134_create_invoice_templates_table',1),
(17,'2025_10_20_040222_create_invoice_template_versions_table',1),
(18,'2025_10_20_040630_create_user_template_settings_table',1),
(19,'2025_10_20_041124_create_currency_table',1),
(20,'2025_10_20_041125_create_invoices_table',1),
(21,'2025_10_20_041828_create_invoice_items_table',1),
(22,'2025_10_24_175951_create_color_scheme_color_table',1),
(23,'2025_12_10_031329_create_migration_to_seed_plans_table',1),
(24,'2025_12_10_032438_create_migration_to_seed_tests_and_categories',1),
(25,'2025_12_10_171714_create_table_plan_capabilities',1),
(26,'2025_12_10_172917_seed_plan_capabilities',1),
(27,'2025_12_15_070922_stripe_webhook_events',1),
(28,'2026_03_30_000001_add_ai_invoice_assistant_plan_capability',1),
(29,'2026_05_07_182120_create_invoice_payment_link',1),
(30,'2026_05_11_063524_create_payment_records',1),
(31,'2026_05_13_000001_add_business_profile_id_to_payment_information_table',1),
(32,'2026_05_19_000001_add_paypal_ids_to_payment_link_table',2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `oauth_access_tokens`
--

DROP TABLE IF EXISTS `oauth_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `oauth_access_tokens` (
  `id` char(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `client_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_access_tokens_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_access_tokens`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `oauth_access_tokens` WRITE;
/*!40000 ALTER TABLE `oauth_access_tokens` DISABLE KEYS */;
INSERT INTO `oauth_access_tokens` VALUES
('f159f9e3614abea7e967f91301627e43fbd57c40a107b4d9b69cdc3da5bd0ba46bb30c7a06054b0a',6,'019e3d87-6b41-7269-8f02-12cb9621d915','Billifty Web App','[]',0,'2026-05-18 23:59:09','2026-05-18 23:59:09','2026-11-18 23:59:09');
/*!40000 ALTER TABLE `oauth_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `oauth_auth_codes`
--

DROP TABLE IF EXISTS `oauth_auth_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `oauth_auth_codes` (
  `id` char(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `client_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_auth_codes_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_auth_codes`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `oauth_auth_codes` WRITE;
/*!40000 ALTER TABLE `oauth_auth_codes` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_auth_codes` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `oauth_clients`
--

DROP TABLE IF EXISTS `oauth_clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `oauth_clients` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `owner_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `redirect_uris` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `grant_types` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_clients_owner_type_owner_id_index` (`owner_type`,`owner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_clients`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `oauth_clients` WRITE;
/*!40000 ALTER TABLE `oauth_clients` DISABLE KEYS */;
INSERT INTO `oauth_clients` VALUES
('019e3d87-6b41-7269-8f02-12cb9621d915',NULL,NULL,'Billifty','$2y$12$WI5Vp6syLYocPFHBjE.8V.LBtd9W1V5Yx26HisgTEO0PcU9d7EdD2','users','[]','[\"personal_access\"]',0,'2026-05-18 23:59:05','2026-05-18 23:59:05');
/*!40000 ALTER TABLE `oauth_clients` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `oauth_device_codes`
--

DROP TABLE IF EXISTS `oauth_device_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `oauth_device_codes` (
  `id` char(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `client_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_code` char(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `user_approved_at` datetime DEFAULT NULL,
  `last_polled_at` datetime DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `oauth_device_codes_user_code_unique` (`user_code`),
  KEY `oauth_device_codes_user_id_index` (`user_id`),
  KEY `oauth_device_codes_client_id_index` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_device_codes`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `oauth_device_codes` WRITE;
/*!40000 ALTER TABLE `oauth_device_codes` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_device_codes` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `oauth_refresh_tokens`
--

DROP TABLE IF EXISTS `oauth_refresh_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `oauth_refresh_tokens` (
  `id` char(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` char(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_refresh_tokens`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `oauth_refresh_tokens` WRITE;
/*!40000 ALTER TABLE `oauth_refresh_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_refresh_tokens` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `payment_information`
--

DROP TABLE IF EXISTS `payment_information`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment_information` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `business_profile_id` bigint unsigned DEFAULT NULL,
  `payment_method` enum('bank_transfer','paypal','stripe','cash_app') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `routing_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `iban` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `swift_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paypal_merchant_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paypal_payer_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paypal_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paypal_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `paypal_payments_receivable` tinyint(1) NOT NULL DEFAULT '0',
  `paypal_primary_email_confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `paypal_onboarded_at` timestamp NULL DEFAULT NULL,
  `paypal_disconnected_at` timestamp NULL DEFAULT NULL,
  `stripe_account_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stripe_connected_at` timestamp NULL DEFAULT NULL,
  `cash_app` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `is_test` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payment_information_profile_method_index` (`business_profile_id`,`payment_method`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_information`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `payment_information` WRITE;
/*!40000 ALTER TABLE `payment_information` DISABLE KEYS */;
INSERT INTO `payment_information` VALUES
(1,NULL,'bank_transfer','BoFa','John Doe','123456789','12345678914662',NULL,NULL,NULL,NULL,NULL,0,0,0,NULL,NULL,NULL,NULL,NULL,'Test',1,'2026-05-18 23:51:37','2026-05-18 23:51:37',NULL),
(3,4,'bank_transfer','Bank of the Philippine Islands','Bank of the Philippine Islands','2323113309','022981114','DE88998819983GHT','CHAISSR66729910',NULL,NULL,NULL,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,'2026-05-19 00:35:02','2026-05-19 00:35:02',NULL),
(4,4,'paypal',NULL,NULL,NULL,NULL,NULL,NULL,'6Q2PZJ3KUUJPW','6Q2PZJ3KUUJPW','sb-kqc1s51189762@business.example.com',0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,'2026-05-19 01:36:12','2026-05-19 01:51:38',NULL),
(5,4,'stripe',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,NULL,NULL,'acct_1TUyXrEgxqsR1VDL',NULL,NULL,NULL,0,'2026-05-19 19:31:58','2026-05-19 19:31:58',NULL),
(6,5,'bank_transfer','BoFa','Bank of Ameria','234235-002','023391232','DE89887711','BOFUUIIGHG7761',NULL,NULL,NULL,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,'2026-05-19 23:55:37','2026-05-19 23:55:37',NULL),
(7,6,'stripe',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,NULL,NULL,'acct_1TUyXrEgxqsR1VDL',NULL,NULL,NULL,0,'2026-05-19 23:57:17','2026-05-19 23:57:17',NULL);
/*!40000 ALTER TABLE `payment_information` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `payment_link`
--

DROP TABLE IF EXISTS `payment_link`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment_link` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint unsigned NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` timestamp NOT NULL,
  `paypal_order_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paypal_capture_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payment_link_token_unique` (`token`),
  KEY `payment_link_invoice_id_foreign` (`invoice_id`),
  KEY `payment_link_paypal_order_id_index` (`paypal_order_id`),
  KEY `payment_link_paypal_capture_id_index` (`paypal_capture_id`),
  CONSTRAINT `payment_link_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_link`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `payment_link` WRITE;
/*!40000 ALTER TABLE `payment_link` DISABLE KEYS */;
INSERT INTO `payment_link` VALUES
(1,2,'pay_f84_01KRYVBA4CV0S7001WP7PDWR4H','2026-05-26 00:49:35','43R047382T552224G','9JF7500489229051J',NULL,'2026-05-19 00:49:35','2026-05-19 07:01:01'),
(2,3,'pay_E18_01KRZHSZJ2MBD6QAB02M2N7C9X','2026-05-26 07:22:05','04D65578KU3951116','9W2920057E417860S',NULL,'2026-05-19 07:22:05','2026-05-19 07:23:57'),
(3,4,'pay_1rv_01KS0G0J0M9P60VZ35RYE84B3T','2026-05-26 16:09:58','35J98555CF855620N','15P72404V9905114F',NULL,'2026-05-19 16:09:58','2026-05-19 22:57:09'),
(4,5,'pay_gnn_01KS17HGA02XSKD2DBBS0ZW6TN','2026-05-26 23:01:10',NULL,NULL,NULL,'2026-05-19 23:01:10','2026-05-19 23:01:10'),
(5,6,'pay_elD_01KS191G0QJT2EYB5QZH3B6M6V','2026-05-26 23:27:23','3N011361R7331972B','6EJ71889YM641523V',NULL,'2026-05-19 23:27:23','2026-05-19 23:29:16'),
(6,7,'pay_vT4_01KS1A2868TXJAD8MEPJQX2H0T','2026-05-26 23:45:16','0AJ74190XU5696726',NULL,NULL,'2026-05-19 23:45:16','2026-05-20 02:37:40'),
(7,8,'pay_Opj_01KS1M59NM5FMWTS3EYMF4Z1R1','2026-05-27 02:41:42','1UN52170BF1760324',NULL,NULL,'2026-05-20 02:41:42','2026-05-20 02:44:06');
/*!40000 ALTER TABLE `payment_link` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `payment_records`
--

DROP TABLE IF EXISTS `payment_records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment_records` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint unsigned NOT NULL,
  `payment_method` enum('stripe','paypal','cash_app','bank_transfer') COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` json NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payment_records_invoice_id_foreign` (`invoice_id`),
  CONSTRAINT `payment_records_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_records`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `payment_records` WRITE;
/*!40000 ALTER TABLE `payment_records` DISABLE KEYS */;
INSERT INTO `payment_records` VALUES
(1,2,'paypal','{\"token\": \"pay_f84_01KRYVBA4CV0S7001WP7PDWR4H\", \"currency\": \"USD\", \"amount_paid\": 70000, \"payment_date\": \"2026-05-19T07:01:01.967451Z\", \"invoice_number\": \"RPL-00001\", \"paypal_order_id\": \"43R047382T552224G\", \"webhook_event_id\": null, \"paypal_capture_id\": \"9JF7500489229051J\", \"webhook_event_type\": \"PAYPAL.RETURN.CAPTURE\", \"invoice_payment_method\": \"paypal\"}','pay_f84_01KRYVBA4CV0S7001WP7PDWR4H',NULL,'2026-05-19 07:01:01','2026-05-19 07:01:01'),
(2,3,'paypal','{\"token\": \"pay_E18_01KRZHSZJ2MBD6QAB02M2N7C9X\", \"currency\": \"PHP\", \"amount_paid\": 520000, \"payment_date\": \"2026-05-19T07:23:57.613596Z\", \"invoice_number\": \"RPL-00002\", \"paypal_order_id\": \"04D65578KU3951116\", \"webhook_event_id\": null, \"paypal_capture_id\": \"9W2920057E417860S\", \"webhook_event_type\": \"PAYPAL.RETURN.CAPTURE\", \"invoice_payment_method\": \"paypal\"}','pay_E18_01KRZHSZJ2MBD6QAB02M2N7C9X',NULL,'2026-05-19 07:23:57','2026-05-19 07:23:57'),
(3,4,'paypal','{\"token\": \"pay_1rv_01KS0G0J0M9P60VZ35RYE84B3T\", \"currency\": \"PHP\", \"amount_paid\": 185000, \"payment_date\": \"2026-05-19T22:57:09.488051Z\", \"invoice_number\": \"RPL-00003\", \"paypal_order_id\": \"35J98555CF855620N\", \"webhook_event_id\": null, \"paypal_capture_id\": \"15P72404V9905114F\", \"webhook_event_type\": \"PAYPAL.RETURN.CAPTURE\", \"invoice_payment_method\": \"paypal\"}','pay_1rv_01KS0G0J0M9P60VZ35RYE84B3T',NULL,'2026-05-19 22:57:09','2026-05-19 22:57:09'),
(4,5,'stripe','{\"token\": \"pay_gnn_01KS17HGA02XSKD2DBBS0ZW6TN\", \"currency\": \"php\", \"card_brand\": \"mastercard\", \"card_last4\": \"8210\", \"line_items\": [{\"currency\": \"php\", \"quantity\": 1, \"description\": \"Contentency\", \"amount_total\": 50000}], \"amount_paid\": 50000, \"receipt_url\": \"https://pay.stripe.com/receipts/payment/CAcaFwoVYWNjdF8xVFV5WHJFZ3hxc1IxVkRMKIjZs9AGMgb3cyhd1kU6LBZ_dGGbXo-fbDXFvBfSBhWho9viHRpqpO-mzlA1bfCtzSc5O4itFlePrrff\", \"payment_date\": \"2026-05-19T23:04:38.000000Z\", \"invoice_number\": \"RPL-00004\", \"payment_method\": \"card\", \"currency_symbol\": \"₱\", \"stripe_session_id\": \"cs_test_a1e122zRjGg2hXJn9FtY5oXqwHvqqtSoEWWSe9bPJJOFXlw5bELNWaocCl\", \"invoice_payment_method\": \"stripe\", \"stripe_payment_intent_id\": \"pi_3TYwgkEgxqsR1VDL169R9kqT\"}','pay_gnn_01KS17HGA02XSKD2DBBS0ZW6TN',NULL,'2026-05-19 23:04:40','2026-05-19 23:04:40'),
(5,6,'paypal','{\"token\": \"pay_elD_01KS191G0QJT2EYB5QZH3B6M6V\", \"currency\": \"PHP\", \"amount_paid\": 110000, \"payment_date\": \"2026-05-19T23:29:16.058968Z\", \"invoice_number\": \"RPL-00005\", \"paypal_order_id\": \"3N011361R7331972B\", \"webhook_event_id\": null, \"paypal_capture_id\": \"6EJ71889YM641523V\", \"webhook_event_type\": \"PAYPAL.RETURN.CAPTURE\", \"invoice_payment_method\": \"paypal\"}','pay_elD_01KS191G0QJT2EYB5QZH3B6M6V',NULL,'2026-05-19 23:29:16','2026-05-19 23:29:16');
/*!40000 ALTER TABLE `payment_records` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `plan_capabilities`
--

DROP TABLE IF EXISTS `plan_capabilities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `plan_capabilities` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `plan_id` bigint unsigned NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'string',
  `value` text COLLATE utf8mb4_unicode_ci,
  `meta` json DEFAULT NULL,
  `model_relationship` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `group` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `plan_capabilities_plan_id_key_unique` (`plan_id`,`key`),
  KEY `plan_capabilities_is_active_index` (`is_active`),
  CONSTRAINT `plan_capabilities_plan_id_foreign` FOREIGN KEY (`plan_id`) REFERENCES `plans` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plan_capabilities`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `plan_capabilities` WRITE;
/*!40000 ALTER TABLE `plan_capabilities` DISABLE KEYS */;
INSERT INTO `plan_capabilities` VALUES
(1,1,'max_business_profiles','Business Profiles','int','1',NULL,'businessProfiles',NULL,'limits',1,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(2,1,'max_clients','Clients','int','5',NULL,'clients',NULL,'limits',1,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(3,1,'max_invoices_per_month','Invoices per month','int','5','{\"usage\": \"monthly\"}','invoices',NULL,'limits',1,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(4,1,'pdf_watermark','PDF Watermark','bool','true',NULL,NULL,'“Powered by Billifty” watermark','features',1,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(5,1,'email_watermark','Email Watermark','bool','true',NULL,NULL,'Billifty branding in emails','features',1,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(6,1,'custom_prefix','Custom Invoice Numbering','bool','false',NULL,NULL,'Basic invoice numbering','features',1,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(7,1,'custom_branding','Custom Brand Colors','bool','false',NULL,NULL,'Basic invoice template','features',1,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(8,1,'multi_templates','Templates','bool','false',NULL,NULL,'Basic invoice template','features',1,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(9,1,'logo_upload','Logo Upload','bool','false',NULL,NULL,NULL,'features',1,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(10,1,'automated_reminders','Automated Reminders','string','none',NULL,NULL,NULL,'features',1,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(11,1,'online_payments','Online Payments','bool','false',NULL,NULL,NULL,'features',1,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(12,1,'multi_currency','Multi-Currency','bool','false',NULL,NULL,NULL,'features',1,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(13,1,'ai_invoice_assistant','AI Invoice Assistant','bool','false',NULL,NULL,NULL,'features',1,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(14,1,'analytics_tier','Analytics','string','basic',NULL,NULL,NULL,'features',0,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(15,1,'email_branding','Email Branding','string','billifty_footer',NULL,NULL,NULL,'features',1,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(16,1,'templates_tier','Templates','string','basic',NULL,NULL,NULL,'features',1,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(17,1,'support_level','Support','string','email',NULL,NULL,NULL,'features',1,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(18,1,'cta_text1',NULL,'string','Perfect for trying out Billifty.',NULL,NULL,NULL,'marketing',1,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(19,1,'cta_btn',NULL,'string','Get started free',NULL,NULL,NULL,'marketing',1,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(20,1,'cta_upper_text',NULL,'string','Start here',NULL,NULL,NULL,'marketing',1,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(21,1,'cta_card_label',NULL,'string',NULL,NULL,NULL,NULL,'marketing',1,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(22,2,'max_business_profiles','Business Profiles','int','3',NULL,'businessProfiles',NULL,'limits',1,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(23,2,'max_clients','Clients','int','0','{\"unlimited\": true}','clients',NULL,'limits',1,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(24,2,'max_invoices_per_month','Invoices per month','int','10','{\"usage\": \"monthly\"}','invoices',NULL,'limits',1,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(25,2,'pdf_watermark','PDF Watermark','bool','false',NULL,NULL,'No PDF watermark','features',1,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(26,2,'email_watermark','Email Watermark','bool','true',NULL,NULL,'Watermark on emails','features',1,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(27,2,'custom_prefix','Custom Invoice Numbering','bool','true',NULL,NULL,'Custom invoice numbering','features',1,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(28,2,'custom_branding','Custom Brand Colors','bool','true',NULL,NULL,'Custom brand colors','features',1,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(29,2,'multi_templates','Templates','bool','true',NULL,NULL,'Multiple invoice templates','features',1,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(30,2,'logo_upload','Logo Upload','bool','true',NULL,NULL,'Upload business logo','features',1,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(31,2,'automated_reminders','Automated Reminders','string','manual',NULL,NULL,'Manual reminders','features',1,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(32,2,'online_payments','Online Payments','bool','false',NULL,NULL,NULL,'features',1,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(33,2,'multi_currency','Multi-Currency','bool','false',NULL,NULL,NULL,'features',1,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(34,2,'ai_invoice_assistant','AI Invoice Assistant','bool','true',NULL,NULL,'AI invoice assistant chat','features',1,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(35,2,'analytics_tier','Analytics','string','standard',NULL,NULL,NULL,'features',0,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(36,2,'email_branding','Email Branding','string','small_footer',NULL,NULL,NULL,'features',1,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(37,2,'templates_tier','Templates','string','multiple',NULL,NULL,NULL,'features',1,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(38,2,'support_level','Support','string','email',NULL,NULL,NULL,'features',1,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(39,2,'cta_text1',NULL,'string','Everything you need to invoice clients professionally.',NULL,NULL,NULL,'marketing',1,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(40,2,'cta_btn',NULL,'string','Upgrade to Pro',NULL,NULL,NULL,'marketing',1,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(41,2,'cta_upper_text',NULL,'string',NULL,NULL,NULL,NULL,'marketing',1,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(42,2,'cta_card_label',NULL,'string','BEST FOR FREELANCERS',NULL,NULL,NULL,'marketing',1,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(43,3,'max_business_profiles','Business Profiles','int','0','{\"unlimited\": true}','businessProfiles',NULL,'limits',1,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(44,3,'max_clients','Clients','int','0','{\"unlimited\": true}','clients',NULL,'limits',1,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(45,3,'max_invoices_per_month','Invoices per month','int','0','{\"usage\": \"monthly\", \"unlimited\": true}','invoices',NULL,'limits',1,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(46,3,'pdf_watermark','PDF Watermark','bool','false',NULL,NULL,'No branding on PDFs','features',1,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(47,3,'email_watermark','Email Watermark','bool','false',NULL,NULL,'No branding on emails','features',1,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(48,3,'custom_prefix','Custom Invoice Numbering','bool','true',NULL,NULL,'Custom invoice numbering','features',1,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(49,3,'custom_branding','Custom Brand Colors','bool','true',NULL,NULL,'Custom brand colors','features',1,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(50,3,'multi_templates','Templates','bool','true',NULL,NULL,'All advanced templates','features',1,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(51,3,'logo_upload','Logo Upload','bool','true',NULL,NULL,'Upload business logo','features',1,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(52,3,'automated_reminders','Automated Reminders','string','automatic',NULL,NULL,'Automated invoice reminders','features',1,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(53,3,'online_payments','Online Payments','bool','true',NULL,NULL,'Online payment links','features',1,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(54,3,'multi_currency','Multi-Currency','bool','true',NULL,NULL,'Multi-currency support','features',1,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(55,3,'ai_invoice_assistant','AI Invoice Assistant','bool','true',NULL,NULL,'AI invoice assistant chat','features',1,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(56,3,'analytics_tier','Analytics','string','advanced',NULL,NULL,'Advanced analytics dashboard','features',0,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(57,3,'email_branding','Email Branding','string','none',NULL,NULL,NULL,'features',1,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(58,3,'templates_tier','Templates','string','all_advanced',NULL,NULL,NULL,'features',1,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(59,3,'support_level','Support','string','priority',NULL,NULL,NULL,'features',1,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(60,3,'cta_text1',NULL,'string','Unlimited invoicing with advanced automation.',NULL,NULL,NULL,'marketing',1,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(61,3,'cta_btn',NULL,'string','Go Premium',NULL,NULL,NULL,'marketing',1,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(62,3,'cta_upper_text',NULL,'string','For growing teams',NULL,NULL,NULL,'marketing',1,'2026-05-18 23:51:38','2026-05-18 23:51:38'),
(63,3,'cta_card_label',NULL,'string',NULL,NULL,NULL,NULL,'marketing',1,'2026-05-18 23:51:38','2026-05-18 23:51:38');
/*!40000 ALTER TABLE `plan_capabilities` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `plans`
--

DROP TABLE IF EXISTS `plans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `plans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price_monthly` decimal(8,2) NOT NULL DEFAULT '0.00',
  `price_yearly` decimal(8,2) DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `sort_order` tinyint unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `plans_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plans`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `plans` WRITE;
/*!40000 ALTER TABLE `plans` DISABLE KEYS */;
INSERT INTO `plans` VALUES
(1,'free','Free','Try Billifty with limited clients and invoices.',0.00,NULL,1,1,'2026-05-18 23:51:36','2026-05-18 23:51:36'),
(2,'pro','Pro','For freelancers and small teams.',4.99,49.99,0,2,'2026-05-18 23:51:36','2026-05-18 23:51:36'),
(3,'premium','Premium','Unlimited invoicing and automation.',9.99,99.99,0,3,'2026-05-18 23:51:36','2026-05-18 23:51:36');
/*!40000 ALTER TABLE `plans` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES
('3jpDS695ZCvLFcB4RZgFNTFvw3W2iOGR44LfXeyK',NULL,'142.250.115.95','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZEpwRkVLUHlCMEpPSkUzcElHSG96eUJCTUxlMmVEeXlFMUszZUVIVSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDc6Imh0dHBzOi8vaW50LmJpbGxpZnR5LmNvbS9kZXYvaW52b2ljZXMvOC9wcmV2aWV3IjtzOjU6InJvdXRlIjtzOjE5OiJkZXYuaW52b2ljZS5wcmV2aWV3Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1779246840),
('EvG2VrGpoE4MKweNlKLPLwVq17DcGGOydskWkaoK',NULL,'192.168.65.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoieWFYWExHaVhLWXc2bjBOc0lIZUtyRWdoaWJ1UWVqV0U1ckczMHNOWSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDc6Imh0dHBzOi8vaW50LmJpbGxpZnR5LmNvbS9kZXYvaW52b2ljZXMvOC9wcmV2aWV3IjtzOjU6InJvdXRlIjtzOjE5OiJkZXYuaW52b2ljZS5wcmV2aWV3Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1779292590);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `stripe_webhook_events`
--

DROP TABLE IF EXISTS `stripe_webhook_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `stripe_webhook_events` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `stripe_customer_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stripe_subscription_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `api_version` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `livemode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payload` json NOT NULL,
  `received_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `stripe_webhook_events_event_id_unique` (`event_id`),
  KEY `stripe_webhook_events_user_id_index` (`user_id`),
  KEY `stripe_webhook_events_stripe_customer_id_index` (`stripe_customer_id`),
  KEY `stripe_webhook_events_stripe_subscription_id_index` (`stripe_subscription_id`),
  KEY `stripe_webhook_events_type_index` (`type`),
  CONSTRAINT `stripe_webhook_events_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stripe_webhook_events`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `stripe_webhook_events` WRITE;
/*!40000 ALTER TABLE `stripe_webhook_events` DISABLE KEYS */;
INSERT INTO `stripe_webhook_events` VALUES
(1,6,'cus_UXgQzt0kW6AD7W','ch_3TYb4hEgxqQWB3tM0aD7GHoF','evt_3TYb4hEgxqQWB3tM0JwkNHH0','charge.succeeded','2025-11-17.clover','0','{\"id\": \"evt_3TYb4hEgxqQWB3tM0JwkNHH0\", \"data\": {\"object\": {\"id\": \"ch_3TYb4hEgxqQWB3tM0aD7GHoF\", \"paid\": true, \"order\": null, \"amount\": 999, \"object\": \"charge\", \"review\": null, \"source\": null, \"status\": \"succeeded\", \"created\": 1779148796, \"dispute\": null, \"outcome\": {\"type\": \"authorized\", \"reason\": null, \"risk_level\": \"normal\", \"risk_score\": 59, \"advice_code\": null, \"network_status\": \"approved_by_network\", \"seller_message\": \"Payment complete.\", \"network_advice_code\": null, \"network_decline_code\": null}, \"captured\": true, \"currency\": \"usd\", \"customer\": \"cus_UXgQzt0kW6AD7W\", \"disputed\": false, \"livemode\": false, \"metadata\": [], \"refunded\": false, \"shipping\": null, \"application\": null, \"description\": \"Subscription creation\", \"destination\": null, \"receipt_url\": \"https://pay.stripe.com/receipts/invoices/CAcaFwoVYWNjdF8xU2QxV2pFZ3hxUVdCM3RNKP7PrtAGMgZBlg74I5Q6LBYdlgrsX4FMlwmRB0QeoytOLEPtCeP7DIyRvpcmlCFAt_kJ1n8sntKkvGlA?s=ap\", \"failure_code\": null, \"on_behalf_of\": null, \"fraud_details\": [], \"radar_options\": [], \"receipt_email\": null, \"transfer_data\": null, \"payment_intent\": \"pi_3TYb4hEgxqQWB3tM0gcKHEEM\", \"payment_method\": \"pm_1TYb4gEgxqQWB3tMlh05bEH9\", \"receipt_number\": null, \"transfer_group\": null, \"amount_captured\": 999, \"amount_refunded\": 0, \"application_fee\": null, \"billing_details\": {\"name\": \"Alex P\", \"email\": \"alexanderpierce+test1@gmail.com\", \"phone\": null, \"tax_id\": null, \"address\": {\"city\": null, \"line1\": null, \"line2\": null, \"state\": null, \"country\": \"US\", \"postal_code\": \"12323\"}}, \"failure_message\": null, \"source_transfer\": null, \"balance_transaction\": \"txn_3TYb4hEgxqQWB3tM0U1SUvJV\", \"statement_descriptor\": null, \"application_fee_amount\": null, \"payment_method_details\": {\"card\": {\"brand\": \"discover\", \"last4\": \"1113\", \"checks\": {\"cvc_check\": \"pass\", \"address_line1_check\": null, \"address_postal_code_check\": \"unchecked\"}, \"wallet\": null, \"country\": \"US\", \"funding\": \"debit\", \"mandate\": null, \"network\": \"discover\", \"exp_year\": 2043, \"exp_month\": 3, \"fingerprint\": \"bBvGGJNL2tpJNryZ\", \"overcapture\": {\"status\": \"unavailable\", \"maximum_amount_capturable\": 999}, \"installments\": null, \"multicapture\": {\"status\": \"unavailable\"}, \"network_token\": {\"used\": false}, \"three_d_secure\": null, \"regulated_status\": \"unregulated\", \"amount_authorized\": 999, \"ds_transaction_id\": null, \"authorization_code\": \"vn0hWg\", \"extended_authorization\": {\"status\": \"disabled\"}, \"network_transaction_id\": \"986611871717478\", \"incremental_authorization\": {\"status\": \"unavailable\"}}, \"type\": \"card\"}, \"failure_balance_transaction\": null, \"statement_descriptor_suffix\": null, \"calculated_statement_descriptor\": \"Stripe\"}}, \"type\": \"charge.succeeded\", \"object\": \"event\", \"created\": 1779148796, \"request\": {\"id\": null, \"idempotency_key\": \"fb90f48d-567c-4534-aa9b-95b3cdec902b\"}, \"livemode\": false, \"api_version\": \"2025-11-17.clover\", \"pending_webhooks\": 3}','2026-05-18 23:59:58','2026-05-18 23:59:58','2026-05-18 23:59:58'),
(2,6,'cus_UXgQzt0kW6AD7W','in_1TYb4hEgxqQWB3tMBiRh0BMD','evt_1TYb4lEgxqQWB3tMslXcr5sc','invoice.payment_succeeded','2025-11-17.clover','0','{\"id\": \"evt_1TYb4lEgxqQWB3tMslXcr5sc\", \"data\": {\"object\": {\"id\": \"in_1TYb4hEgxqQWB3tMBiRh0BMD\", \"lines\": {\"url\": \"/v1/invoices/in_1TYb4hEgxqQWB3tMBiRh0BMD/lines\", \"data\": [{\"id\": \"il_1TYb4hEgxqQWB3tMRTfwc3k1\", \"taxes\": [], \"amount\": 999, \"object\": \"line_item\", \"parent\": {\"type\": \"subscription_item_details\", \"invoice_item_details\": null, \"subscription_item_details\": {\"proration\": false, \"invoice_item\": null, \"subscription\": \"sub_1TYb4jEgxqQWB3tMEaVaWy1i\", \"proration_details\": {\"credited_items\": null}, \"subscription_item\": \"si_UXgRBUh17mxhjR\"}, \"license_fee_subscription_details\": null}, \"period\": {\"end\": 1781827195, \"start\": 1779148795}, \"invoice\": \"in_1TYb4hEgxqQWB3tMBiRh0BMD\", \"pricing\": {\"type\": \"price_details\", \"price_details\": {\"price\": \"price_1Sd3SQEgxqQWB3tMwm9eDbPZ\", \"product\": \"prod_TaDuix6Cq3Omo9\"}, \"unit_amount_decimal\": \"999\"}, \"currency\": \"usd\", \"livemode\": false, \"metadata\": {\"plan_code\": \"premium\", \"billing_cycle\": \"monthly\", \"billifty_user_id\": \"6\"}, \"quantity\": 1, \"subtotal\": 999, \"discounts\": [], \"description\": \"1 × Premium – Monthly (at $9.99 / month)\", \"discountable\": true, \"discount_amounts\": [], \"quantity_decimal\": \"1\", \"pretax_credit_amounts\": []}], \"object\": \"list\", \"has_more\": false, \"total_count\": 1}, \"total\": 999, \"footer\": null, \"issuer\": {\"type\": \"self\"}, \"number\": \"FSYDIEFX-0001\", \"object\": \"invoice\", \"parent\": {\"type\": \"subscription_details\", \"quote_details\": null, \"subscription_details\": {\"metadata\": {\"plan_code\": \"premium\", \"billing_cycle\": \"monthly\", \"billifty_user_id\": \"6\"}, \"subscription\": \"sub_1TYb4jEgxqQWB3tMEaVaWy1i\"}}, \"status\": \"paid\", \"created\": 1779148795, \"currency\": \"usd\", \"customer\": \"cus_UXgQzt0kW6AD7W\", \"due_date\": null, \"livemode\": false, \"metadata\": [], \"subtotal\": 999, \"attempted\": true, \"discounts\": [], \"rendering\": null, \"amount_due\": 999, \"period_end\": 1779148795, \"test_clock\": null, \"amount_paid\": 999, \"application\": null, \"description\": null, \"invoice_pdf\": \"https://pay.stripe.com/invoice/acct_1Sd1WjEgxqQWB3tM/test_YWNjdF8xU2QxV2pFZ3hxUVdCM3RNLF9VWGdSZExMNnR1aWVPd3J2SEFuc1NpRXdFdEhPSkVtLDE2OTY4OTU5OQ0200BZ160FGn/pdf?s=ap\", \"total_taxes\": [], \"account_name\": \"Billifty sandbox\", \"auto_advance\": false, \"effective_at\": 1779148795, \"from_invoice\": null, \"on_behalf_of\": null, \"period_start\": 1779148795, \"attempt_count\": 0, \"automatic_tax\": {\"status\": null, \"enabled\": false, \"provider\": null, \"liability\": null, \"disabled_reason\": null}, \"custom_fields\": null, \"customer_name\": \"Alexander Pierce\", \"shipping_cost\": null, \"billing_reason\": \"subscription_create\", \"customer_email\": \"alexanderpierce+test1@gmail.com\", \"customer_phone\": null, \"default_source\": null, \"ending_balance\": 0, \"receipt_number\": null, \"account_country\": \"US\", \"account_tax_ids\": null, \"amount_overpaid\": 0, \"amount_shipping\": 0, \"latest_revision\": null, \"amount_remaining\": 0, \"customer_account\": null, \"customer_address\": null, \"customer_tax_ids\": [], \"payment_settings\": {\"default_mandate\": null, \"payment_method_types\": null, \"payment_method_options\": {\"pix\": null, \"upi\": null, \"card\": {\"request_three_d_secure\": \"automatic\"}, \"payto\": null, \"konbini\": null, \"acss_debit\": null, \"bancontact\": null, \"sepa_debit\": null, \"us_bank_account\": null, \"customer_balance\": null}}, \"shipping_details\": null, \"starting_balance\": 0, \"collection_method\": \"charge_automatically\", \"customer_shipping\": null, \"default_tax_rates\": [], \"hosted_invoice_url\": \"https://invoice.stripe.com/i/acct_1Sd1WjEgxqQWB3tM/test_YWNjdF8xU2QxV2pFZ3hxUVdCM3RNLF9VWGdSZExMNnR1aWVPd3J2SEFuc1NpRXdFdEhPSkVtLDE2OTY4OTU5OQ0200BZ160FGn?s=ap\", \"status_transitions\": {\"paid_at\": 1779148796, \"voided_at\": null, \"finalized_at\": 1779148795, \"marked_uncollectible_at\": null}, \"customer_tax_exempt\": \"none\", \"total_excluding_tax\": 999, \"next_payment_attempt\": null, \"statement_descriptor\": null, \"webhooks_delivered_at\": null, \"default_payment_method\": null, \"subtotal_excluding_tax\": 999, \"total_discount_amounts\": [], \"last_finalization_error\": null, \"automatically_finalizes_at\": null, \"total_pretax_credit_amounts\": [], \"pre_payment_credit_notes_amount\": 0, \"post_payment_credit_notes_amount\": 0}}, \"type\": \"invoice.payment_succeeded\", \"object\": \"event\", \"created\": 1779148798, \"request\": {\"id\": null, \"idempotency_key\": \"fb90f48d-567c-4534-aa9b-95b3cdec902b\"}, \"livemode\": false, \"api_version\": \"2025-11-17.clover\", \"pending_webhooks\": 3}','2026-05-18 23:59:59','2026-05-18 23:59:59','2026-05-18 23:59:59'),
(3,6,'cus_UXgQzt0kW6AD7W','sub_1TYb4jEgxqQWB3tMEaVaWy1i','evt_1TYb4lEgxqQWB3tMDKQsUTRk','checkout.session.completed','2025-11-17.clover','0','{\"id\": \"evt_1TYb4lEgxqQWB3tMDKQsUTRk\", \"data\": {\"object\": {\"id\": \"cs_test_b19kzQBX4fqX5ZUe7pKBhBJ3iyzWk5hgap7trr8DDcf6kujeaMPEi3mu7H\", \"url\": null, \"mode\": \"subscription\", \"locale\": null, \"object\": \"checkout.session\", \"status\": \"complete\", \"consent\": null, \"created\": 1779148752, \"invoice\": \"in_1TYb4hEgxqQWB3tMBiRh0BMD\", \"ui_mode\": \"hosted\", \"currency\": \"usd\", \"customer\": \"cus_UXgQzt0kW6AD7W\", \"livemode\": false, \"metadata\": {\"plan_code\": \"premium\", \"billing_cycle\": \"monthly\", \"billifty_user_id\": \"6\"}, \"discounts\": [], \"cancel_url\": \"https://int.billifty.com/app/account/manage-subscription\", \"expires_at\": 1779235152, \"custom_text\": {\"submit\": null, \"after_submit\": null, \"shipping_address\": null, \"terms_of_service_acceptance\": null}, \"permissions\": null, \"submit_type\": null, \"success_url\": \"https://int.billifty.com/app/checkout/success?session_id={CHECKOUT_SESSION_ID}\", \"amount_total\": 999, \"payment_link\": null, \"setup_intent\": null, \"subscription\": \"sub_1TYb4jEgxqQWB3tMEaVaWy1i\", \"automatic_tax\": {\"status\": null, \"enabled\": false, \"provider\": null, \"liability\": null}, \"client_secret\": null, \"custom_fields\": [], \"shipping_cost\": null, \"total_details\": {\"amount_tax\": 0, \"amount_discount\": 0, \"amount_shipping\": 0}, \"customer_email\": null, \"origin_context\": null, \"payment_intent\": null, \"payment_status\": \"paid\", \"recovered_from\": null, \"wallet_options\": null, \"amount_subtotal\": 999, \"adaptive_pricing\": {\"enabled\": true}, \"after_expiration\": null, \"customer_account\": null, \"customer_details\": {\"name\": \"Alexander Pierce\", \"email\": \"alexanderpierce+test1@gmail.com\", \"phone\": null, \"address\": {\"city\": null, \"line1\": null, \"line2\": null, \"state\": null, \"country\": \"US\", \"postal_code\": \"12323\"}, \"tax_ids\": [], \"tax_exempt\": \"none\", \"business_name\": null, \"individual_name\": null}, \"invoice_creation\": null, \"managed_payments\": {\"enabled\": false}, \"shipping_options\": [], \"branding_settings\": {\"icon\": null, \"logo\": null, \"font_family\": \"default\", \"border_style\": \"rounded\", \"button_color\": \"#0074d4\", \"display_name\": \"Billifty sandbox\", \"background_color\": \"#ffffff\"}, \"customer_creation\": null, \"consent_collection\": null, \"client_reference_id\": null, \"currency_conversion\": null, \"payment_method_types\": [\"card\", \"klarna\", \"link\", \"cashapp\", \"amazon_pay\"], \"allow_promotion_codes\": true, \"collected_information\": {\"business_name\": null, \"individual_name\": null, \"shipping_details\": null}, \"integration_identifier\": null, \"payment_method_options\": {\"card\": {\"request_three_d_secure\": \"automatic\"}}, \"phone_number_collection\": {\"enabled\": false}, \"payment_method_collection\": \"always\", \"billing_address_collection\": null, \"shipping_address_collection\": null, \"saved_payment_method_options\": {\"payment_method_save\": null, \"payment_method_remove\": \"disabled\", \"allow_redisplay_filters\": [\"always\"]}, \"payment_method_configuration_details\": {\"id\": \"pmc_1Sd1XIEgxqQWB3tMJxgIok4L\", \"parent\": null}}}, \"type\": \"checkout.session.completed\", \"object\": \"event\", \"created\": 1779148799, \"request\": {\"id\": null, \"idempotency_key\": null}, \"livemode\": false, \"api_version\": \"2025-11-17.clover\", \"pending_webhooks\": 3}','2026-05-18 23:59:59','2026-05-18 23:59:59','2026-05-18 23:59:59'),
(4,6,'cus_UXgQzt0kW6AD7W','in_1TYb4hEgxqQWB3tMBiRh0BMD','evt_1TYb4lEgxqQWB3tMjH6oPYHz','invoice.created','2025-11-17.clover','0','{\"id\": \"evt_1TYb4lEgxqQWB3tMjH6oPYHz\", \"data\": {\"object\": {\"id\": \"in_1TYb4hEgxqQWB3tMBiRh0BMD\", \"lines\": {\"url\": \"/v1/invoices/in_1TYb4hEgxqQWB3tMBiRh0BMD/lines\", \"data\": [{\"id\": \"il_1TYb4hEgxqQWB3tMRTfwc3k1\", \"taxes\": [], \"amount\": 999, \"object\": \"line_item\", \"parent\": {\"type\": \"subscription_item_details\", \"invoice_item_details\": null, \"subscription_item_details\": {\"proration\": false, \"invoice_item\": null, \"subscription\": \"sub_1TYb4jEgxqQWB3tMEaVaWy1i\", \"proration_details\": {\"credited_items\": null}, \"subscription_item\": \"si_UXgRBUh17mxhjR\"}, \"license_fee_subscription_details\": null}, \"period\": {\"end\": 1781827195, \"start\": 1779148795}, \"invoice\": \"in_1TYb4hEgxqQWB3tMBiRh0BMD\", \"pricing\": {\"type\": \"price_details\", \"price_details\": {\"price\": \"price_1Sd3SQEgxqQWB3tMwm9eDbPZ\", \"product\": \"prod_TaDuix6Cq3Omo9\"}, \"unit_amount_decimal\": \"999\"}, \"currency\": \"usd\", \"livemode\": false, \"metadata\": {\"plan_code\": \"premium\", \"billing_cycle\": \"monthly\", \"billifty_user_id\": \"6\"}, \"quantity\": 1, \"subtotal\": 999, \"discounts\": [], \"description\": \"1 × Premium – Monthly (at $9.99 / month)\", \"discountable\": true, \"discount_amounts\": [], \"quantity_decimal\": \"1\", \"pretax_credit_amounts\": []}], \"object\": \"list\", \"has_more\": false, \"total_count\": 1}, \"total\": 999, \"footer\": null, \"issuer\": {\"type\": \"self\"}, \"number\": \"FSYDIEFX-0001\", \"object\": \"invoice\", \"parent\": {\"type\": \"subscription_details\", \"quote_details\": null, \"subscription_details\": {\"metadata\": {\"plan_code\": \"premium\", \"billing_cycle\": \"monthly\", \"billifty_user_id\": \"6\"}, \"subscription\": \"sub_1TYb4jEgxqQWB3tMEaVaWy1i\"}}, \"status\": \"paid\", \"created\": 1779148795, \"currency\": \"usd\", \"customer\": \"cus_UXgQzt0kW6AD7W\", \"due_date\": null, \"livemode\": false, \"metadata\": [], \"subtotal\": 999, \"attempted\": true, \"discounts\": [], \"rendering\": null, \"amount_due\": 999, \"period_end\": 1779148795, \"test_clock\": null, \"amount_paid\": 999, \"application\": null, \"description\": null, \"invoice_pdf\": \"https://pay.stripe.com/invoice/acct_1Sd1WjEgxqQWB3tM/test_YWNjdF8xU2QxV2pFZ3hxUVdCM3RNLF9VWGdSZExMNnR1aWVPd3J2SEFuc1NpRXdFdEhPSkVtLDE2OTY4OTU5OQ0200BZ160FGn/pdf?s=ap\", \"total_taxes\": [], \"account_name\": \"Billifty sandbox\", \"auto_advance\": false, \"effective_at\": 1779148795, \"from_invoice\": null, \"on_behalf_of\": null, \"period_start\": 1779148795, \"attempt_count\": 0, \"automatic_tax\": {\"status\": null, \"enabled\": false, \"provider\": null, \"liability\": null, \"disabled_reason\": null}, \"custom_fields\": null, \"customer_name\": \"Alexander Pierce\", \"shipping_cost\": null, \"billing_reason\": \"subscription_create\", \"customer_email\": \"alexanderpierce+test1@gmail.com\", \"customer_phone\": null, \"default_source\": null, \"ending_balance\": 0, \"receipt_number\": null, \"account_country\": \"US\", \"account_tax_ids\": null, \"amount_overpaid\": 0, \"amount_shipping\": 0, \"latest_revision\": null, \"amount_remaining\": 0, \"customer_account\": null, \"customer_address\": null, \"customer_tax_ids\": [], \"payment_settings\": {\"default_mandate\": null, \"payment_method_types\": null, \"payment_method_options\": {\"pix\": null, \"upi\": null, \"card\": {\"request_three_d_secure\": \"automatic\"}, \"payto\": null, \"konbini\": null, \"acss_debit\": null, \"bancontact\": null, \"sepa_debit\": null, \"us_bank_account\": null, \"customer_balance\": null}}, \"shipping_details\": null, \"starting_balance\": 0, \"collection_method\": \"charge_automatically\", \"customer_shipping\": null, \"default_tax_rates\": [], \"hosted_invoice_url\": \"https://invoice.stripe.com/i/acct_1Sd1WjEgxqQWB3tM/test_YWNjdF8xU2QxV2pFZ3hxUVdCM3RNLF9VWGdSZExMNnR1aWVPd3J2SEFuc1NpRXdFdEhPSkVtLDE2OTY4OTU5OQ0200BZ160FGn?s=ap\", \"status_transitions\": {\"paid_at\": 1779148796, \"voided_at\": null, \"finalized_at\": 1779148795, \"marked_uncollectible_at\": null}, \"customer_tax_exempt\": \"none\", \"total_excluding_tax\": 999, \"next_payment_attempt\": null, \"statement_descriptor\": null, \"webhooks_delivered_at\": null, \"default_payment_method\": null, \"subtotal_excluding_tax\": 999, \"total_discount_amounts\": [], \"last_finalization_error\": null, \"automatically_finalizes_at\": null, \"total_pretax_credit_amounts\": [], \"pre_payment_credit_notes_amount\": 0, \"post_payment_credit_notes_amount\": 0}}, \"type\": \"invoice.created\", \"object\": \"event\", \"created\": 1779148798, \"request\": {\"id\": null, \"idempotency_key\": \"fb90f48d-567c-4534-aa9b-95b3cdec902b\"}, \"livemode\": false, \"api_version\": \"2025-11-17.clover\", \"pending_webhooks\": 3}','2026-05-18 23:59:59','2026-05-18 23:59:59','2026-05-18 23:59:59'),
(5,6,'cus_UXgQzt0kW6AD7W','in_1TYb4hEgxqQWB3tMBiRh0BMD','evt_1TYb4lEgxqQWB3tMOb03qxWk','invoice.finalized','2025-11-17.clover','0','{\"id\": \"evt_1TYb4lEgxqQWB3tMOb03qxWk\", \"data\": {\"object\": {\"id\": \"in_1TYb4hEgxqQWB3tMBiRh0BMD\", \"lines\": {\"url\": \"/v1/invoices/in_1TYb4hEgxqQWB3tMBiRh0BMD/lines\", \"data\": [{\"id\": \"il_1TYb4hEgxqQWB3tMRTfwc3k1\", \"taxes\": [], \"amount\": 999, \"object\": \"line_item\", \"parent\": {\"type\": \"subscription_item_details\", \"invoice_item_details\": null, \"subscription_item_details\": {\"proration\": false, \"invoice_item\": null, \"subscription\": \"sub_1TYb4jEgxqQWB3tMEaVaWy1i\", \"proration_details\": {\"credited_items\": null}, \"subscription_item\": \"si_UXgRBUh17mxhjR\"}, \"license_fee_subscription_details\": null}, \"period\": {\"end\": 1781827195, \"start\": 1779148795}, \"invoice\": \"in_1TYb4hEgxqQWB3tMBiRh0BMD\", \"pricing\": {\"type\": \"price_details\", \"price_details\": {\"price\": \"price_1Sd3SQEgxqQWB3tMwm9eDbPZ\", \"product\": \"prod_TaDuix6Cq3Omo9\"}, \"unit_amount_decimal\": \"999\"}, \"currency\": \"usd\", \"livemode\": false, \"metadata\": {\"plan_code\": \"premium\", \"billing_cycle\": \"monthly\", \"billifty_user_id\": \"6\"}, \"quantity\": 1, \"subtotal\": 999, \"discounts\": [], \"description\": \"1 × Premium – Monthly (at $9.99 / month)\", \"discountable\": true, \"discount_amounts\": [], \"quantity_decimal\": \"1\", \"pretax_credit_amounts\": []}], \"object\": \"list\", \"has_more\": false, \"total_count\": 1}, \"total\": 999, \"footer\": null, \"issuer\": {\"type\": \"self\"}, \"number\": \"FSYDIEFX-0001\", \"object\": \"invoice\", \"parent\": {\"type\": \"subscription_details\", \"quote_details\": null, \"subscription_details\": {\"metadata\": {\"plan_code\": \"premium\", \"billing_cycle\": \"monthly\", \"billifty_user_id\": \"6\"}, \"subscription\": \"sub_1TYb4jEgxqQWB3tMEaVaWy1i\"}}, \"status\": \"paid\", \"created\": 1779148795, \"currency\": \"usd\", \"customer\": \"cus_UXgQzt0kW6AD7W\", \"due_date\": null, \"livemode\": false, \"metadata\": [], \"subtotal\": 999, \"attempted\": true, \"discounts\": [], \"rendering\": null, \"amount_due\": 999, \"period_end\": 1779148795, \"test_clock\": null, \"amount_paid\": 999, \"application\": null, \"description\": null, \"invoice_pdf\": \"https://pay.stripe.com/invoice/acct_1Sd1WjEgxqQWB3tM/test_YWNjdF8xU2QxV2pFZ3hxUVdCM3RNLF9VWGdSZExMNnR1aWVPd3J2SEFuc1NpRXdFdEhPSkVtLDE2OTY4OTU5OQ0200BZ160FGn/pdf?s=ap\", \"total_taxes\": [], \"account_name\": \"Billifty sandbox\", \"auto_advance\": false, \"effective_at\": 1779148795, \"from_invoice\": null, \"on_behalf_of\": null, \"period_start\": 1779148795, \"attempt_count\": 0, \"automatic_tax\": {\"status\": null, \"enabled\": false, \"provider\": null, \"liability\": null, \"disabled_reason\": null}, \"custom_fields\": null, \"customer_name\": \"Alexander Pierce\", \"shipping_cost\": null, \"billing_reason\": \"subscription_create\", \"customer_email\": \"alexanderpierce+test1@gmail.com\", \"customer_phone\": null, \"default_source\": null, \"ending_balance\": 0, \"receipt_number\": null, \"account_country\": \"US\", \"account_tax_ids\": null, \"amount_overpaid\": 0, \"amount_shipping\": 0, \"latest_revision\": null, \"amount_remaining\": 0, \"customer_account\": null, \"customer_address\": null, \"customer_tax_ids\": [], \"payment_settings\": {\"default_mandate\": null, \"payment_method_types\": null, \"payment_method_options\": {\"pix\": null, \"upi\": null, \"card\": {\"request_three_d_secure\": \"automatic\"}, \"payto\": null, \"konbini\": null, \"acss_debit\": null, \"bancontact\": null, \"sepa_debit\": null, \"us_bank_account\": null, \"customer_balance\": null}}, \"shipping_details\": null, \"starting_balance\": 0, \"collection_method\": \"charge_automatically\", \"customer_shipping\": null, \"default_tax_rates\": [], \"hosted_invoice_url\": \"https://invoice.stripe.com/i/acct_1Sd1WjEgxqQWB3tM/test_YWNjdF8xU2QxV2pFZ3hxUVdCM3RNLF9VWGdSZExMNnR1aWVPd3J2SEFuc1NpRXdFdEhPSkVtLDE2OTY4OTU5OQ0200BZ160FGn?s=ap\", \"status_transitions\": {\"paid_at\": 1779148796, \"voided_at\": null, \"finalized_at\": 1779148795, \"marked_uncollectible_at\": null}, \"customer_tax_exempt\": \"none\", \"total_excluding_tax\": 999, \"next_payment_attempt\": null, \"statement_descriptor\": null, \"webhooks_delivered_at\": null, \"default_payment_method\": null, \"subtotal_excluding_tax\": 999, \"total_discount_amounts\": [], \"last_finalization_error\": null, \"automatically_finalizes_at\": null, \"total_pretax_credit_amounts\": [], \"pre_payment_credit_notes_amount\": 0, \"post_payment_credit_notes_amount\": 0}}, \"type\": \"invoice.finalized\", \"object\": \"event\", \"created\": 1779148798, \"request\": {\"id\": null, \"idempotency_key\": \"fb90f48d-567c-4534-aa9b-95b3cdec902b\"}, \"livemode\": false, \"api_version\": \"2025-11-17.clover\", \"pending_webhooks\": 3}','2026-05-18 23:59:59','2026-05-18 23:59:59','2026-05-18 23:59:59'),
(6,6,'cus_UXgQzt0kW6AD7W','in_1TYb4hEgxqQWB3tMBiRh0BMD','evt_1TYb4lEgxqQWB3tMwTvczujL','invoice.paid','2025-11-17.clover','0','{\"id\": \"evt_1TYb4lEgxqQWB3tMwTvczujL\", \"data\": {\"object\": {\"id\": \"in_1TYb4hEgxqQWB3tMBiRh0BMD\", \"lines\": {\"url\": \"/v1/invoices/in_1TYb4hEgxqQWB3tMBiRh0BMD/lines\", \"data\": [{\"id\": \"il_1TYb4hEgxqQWB3tMRTfwc3k1\", \"taxes\": [], \"amount\": 999, \"object\": \"line_item\", \"parent\": {\"type\": \"subscription_item_details\", \"invoice_item_details\": null, \"subscription_item_details\": {\"proration\": false, \"invoice_item\": null, \"subscription\": \"sub_1TYb4jEgxqQWB3tMEaVaWy1i\", \"proration_details\": {\"credited_items\": null}, \"subscription_item\": \"si_UXgRBUh17mxhjR\"}, \"license_fee_subscription_details\": null}, \"period\": {\"end\": 1781827195, \"start\": 1779148795}, \"invoice\": \"in_1TYb4hEgxqQWB3tMBiRh0BMD\", \"pricing\": {\"type\": \"price_details\", \"price_details\": {\"price\": \"price_1Sd3SQEgxqQWB3tMwm9eDbPZ\", \"product\": \"prod_TaDuix6Cq3Omo9\"}, \"unit_amount_decimal\": \"999\"}, \"currency\": \"usd\", \"livemode\": false, \"metadata\": {\"plan_code\": \"premium\", \"billing_cycle\": \"monthly\", \"billifty_user_id\": \"6\"}, \"quantity\": 1, \"subtotal\": 999, \"discounts\": [], \"description\": \"1 × Premium – Monthly (at $9.99 / month)\", \"discountable\": true, \"discount_amounts\": [], \"quantity_decimal\": \"1\", \"pretax_credit_amounts\": []}], \"object\": \"list\", \"has_more\": false, \"total_count\": 1}, \"total\": 999, \"footer\": null, \"issuer\": {\"type\": \"self\"}, \"number\": \"FSYDIEFX-0001\", \"object\": \"invoice\", \"parent\": {\"type\": \"subscription_details\", \"quote_details\": null, \"subscription_details\": {\"metadata\": {\"plan_code\": \"premium\", \"billing_cycle\": \"monthly\", \"billifty_user_id\": \"6\"}, \"subscription\": \"sub_1TYb4jEgxqQWB3tMEaVaWy1i\"}}, \"status\": \"paid\", \"created\": 1779148795, \"currency\": \"usd\", \"customer\": \"cus_UXgQzt0kW6AD7W\", \"due_date\": null, \"livemode\": false, \"metadata\": [], \"subtotal\": 999, \"attempted\": true, \"discounts\": [], \"rendering\": null, \"amount_due\": 999, \"period_end\": 1779148795, \"test_clock\": null, \"amount_paid\": 999, \"application\": null, \"description\": null, \"invoice_pdf\": \"https://pay.stripe.com/invoice/acct_1Sd1WjEgxqQWB3tM/test_YWNjdF8xU2QxV2pFZ3hxUVdCM3RNLF9VWGdSZExMNnR1aWVPd3J2SEFuc1NpRXdFdEhPSkVtLDE2OTY4OTU5OQ0200BZ160FGn/pdf?s=ap\", \"total_taxes\": [], \"account_name\": \"Billifty sandbox\", \"auto_advance\": false, \"effective_at\": 1779148795, \"from_invoice\": null, \"on_behalf_of\": null, \"period_start\": 1779148795, \"attempt_count\": 0, \"automatic_tax\": {\"status\": null, \"enabled\": false, \"provider\": null, \"liability\": null, \"disabled_reason\": null}, \"custom_fields\": null, \"customer_name\": \"Alexander Pierce\", \"shipping_cost\": null, \"billing_reason\": \"subscription_create\", \"customer_email\": \"alexanderpierce+test1@gmail.com\", \"customer_phone\": null, \"default_source\": null, \"ending_balance\": 0, \"receipt_number\": null, \"account_country\": \"US\", \"account_tax_ids\": null, \"amount_overpaid\": 0, \"amount_shipping\": 0, \"latest_revision\": null, \"amount_remaining\": 0, \"customer_account\": null, \"customer_address\": null, \"customer_tax_ids\": [], \"payment_settings\": {\"default_mandate\": null, \"payment_method_types\": null, \"payment_method_options\": {\"pix\": null, \"upi\": null, \"card\": {\"request_three_d_secure\": \"automatic\"}, \"payto\": null, \"konbini\": null, \"acss_debit\": null, \"bancontact\": null, \"sepa_debit\": null, \"us_bank_account\": null, \"customer_balance\": null}}, \"shipping_details\": null, \"starting_balance\": 0, \"collection_method\": \"charge_automatically\", \"customer_shipping\": null, \"default_tax_rates\": [], \"hosted_invoice_url\": \"https://invoice.stripe.com/i/acct_1Sd1WjEgxqQWB3tM/test_YWNjdF8xU2QxV2pFZ3hxUVdCM3RNLF9VWGdSZExMNnR1aWVPd3J2SEFuc1NpRXdFdEhPSkVtLDE2OTY4OTU5OQ0200BZ160FGn?s=ap\", \"status_transitions\": {\"paid_at\": 1779148796, \"voided_at\": null, \"finalized_at\": 1779148795, \"marked_uncollectible_at\": null}, \"customer_tax_exempt\": \"none\", \"total_excluding_tax\": 999, \"next_payment_attempt\": null, \"statement_descriptor\": null, \"webhooks_delivered_at\": null, \"default_payment_method\": null, \"subtotal_excluding_tax\": 999, \"total_discount_amounts\": [], \"last_finalization_error\": null, \"automatically_finalizes_at\": null, \"total_pretax_credit_amounts\": [], \"pre_payment_credit_notes_amount\": 0, \"post_payment_credit_notes_amount\": 0}}, \"type\": \"invoice.paid\", \"object\": \"event\", \"created\": 1779148798, \"request\": {\"id\": null, \"idempotency_key\": \"fb90f48d-567c-4534-aa9b-95b3cdec902b\"}, \"livemode\": false, \"api_version\": \"2025-11-17.clover\", \"pending_webhooks\": 3}','2026-05-18 23:59:59','2026-05-18 23:59:59','2026-05-18 23:59:59'),
(7,6,'cus_UXgQzt0kW6AD7W','pm_1TYb4gEgxqQWB3tMlh05bEH9','evt_1TYb4lEgxqQWB3tMwiVxUjmm','payment_method.attached','2025-11-17.clover','0','{\"id\": \"evt_1TYb4lEgxqQWB3tMwiVxUjmm\", \"data\": {\"object\": {\"id\": \"pm_1TYb4gEgxqQWB3tMlh05bEH9\", \"card\": {\"brand\": \"discover\", \"last4\": \"1113\", \"checks\": {\"cvc_check\": \"pass\", \"address_line1_check\": null, \"address_postal_code_check\": \"unchecked\"}, \"wallet\": null, \"country\": \"US\", \"funding\": \"debit\", \"exp_year\": 2043, \"networks\": {\"available\": [\"discover\"], \"preferred\": null}, \"exp_month\": 3, \"fingerprint\": \"bBvGGJNL2tpJNryZ\", \"display_brand\": \"discover\", \"generated_from\": null, \"regulated_status\": \"unregulated\", \"three_d_secure_usage\": {\"supported\": true}}, \"type\": \"card\", \"object\": \"payment_method\", \"created\": 1779148794, \"customer\": \"cus_UXgQzt0kW6AD7W\", \"livemode\": false, \"metadata\": [], \"allow_redisplay\": \"limited\", \"billing_details\": {\"name\": \"Alex P\", \"email\": \"alexanderpierce+test1@gmail.com\", \"phone\": null, \"tax_id\": null, \"address\": {\"city\": null, \"line1\": null, \"line2\": null, \"state\": null, \"country\": \"US\", \"postal_code\": \"12323\"}}, \"customer_account\": null, \"shared_payment_granted_token\": null}}, \"type\": \"payment_method.attached\", \"object\": \"event\", \"created\": 1779148796, \"request\": {\"id\": null, \"idempotency_key\": \"fb90f48d-567c-4534-aa9b-95b3cdec902b\"}, \"livemode\": false, \"api_version\": \"2025-11-17.clover\", \"pending_webhooks\": 3}','2026-05-18 23:59:59','2026-05-18 23:59:59','2026-05-18 23:59:59'),
(8,6,NULL,'cus_UXgQzt0kW6AD7W','evt_1TYb4lEgxqQWB3tMYt9Yk4ts','customer.updated','2025-11-17.clover','0','{\"id\": \"evt_1TYb4lEgxqQWB3tMYt9Yk4ts\", \"data\": {\"object\": {\"id\": \"cus_UXgQzt0kW6AD7W\", \"name\": \"Alexander Pierce\", \"email\": \"alexanderpierce+test1@gmail.com\", \"phone\": null, \"object\": \"customer\", \"address\": null, \"balance\": 0, \"created\": 1779148751, \"currency\": \"usd\", \"discount\": null, \"livemode\": false, \"metadata\": {\"billifty_user_id\": \"6\"}, \"shipping\": null, \"delinquent\": false, \"tax_exempt\": \"none\", \"test_clock\": null, \"description\": null, \"default_source\": null, \"invoice_prefix\": \"FSYDIEFX\", \"customer_account\": null, \"invoice_settings\": {\"footer\": null, \"custom_fields\": null, \"rendering_options\": null, \"default_payment_method\": null}, \"preferred_locales\": [], \"next_invoice_sequence\": 2}, \"previous_attributes\": {\"currency\": null}}, \"type\": \"customer.updated\", \"object\": \"event\", \"created\": 1779148795, \"request\": {\"id\": null, \"idempotency_key\": \"fb90f48d-567c-4534-aa9b-95b3cdec902b\"}, \"livemode\": false, \"api_version\": \"2025-11-17.clover\", \"pending_webhooks\": 3}','2026-05-18 23:59:59','2026-05-18 23:59:59','2026-05-18 23:59:59'),
(9,6,'cus_UXgQzt0kW6AD7W','sub_1TYb4jEgxqQWB3tMEaVaWy1i','evt_1TYb4lEgxqQWB3tMYLS2ZYDX','customer.subscription.created','2025-11-17.clover','0','{\"id\": \"evt_1TYb4lEgxqQWB3tMYLS2ZYDX\", \"data\": {\"object\": {\"id\": \"sub_1TYb4jEgxqQWB3tMEaVaWy1i\", \"plan\": {\"id\": \"price_1Sd3SQEgxqQWB3tMwm9eDbPZ\", \"meter\": null, \"active\": true, \"amount\": 999, \"object\": \"plan\", \"created\": 1765434874, \"product\": \"prod_TaDuix6Cq3Omo9\", \"currency\": \"usd\", \"interval\": \"month\", \"livemode\": false, \"metadata\": [], \"nickname\": null, \"tiers_mode\": null, \"usage_type\": \"licensed\", \"amount_decimal\": \"999\", \"billing_scheme\": \"per_unit\", \"interval_count\": 1, \"transform_usage\": null, \"trial_period_days\": null}, \"items\": {\"url\": \"/v1/subscription_items?subscription=sub_1TYb4jEgxqQWB3tMEaVaWy1i\", \"data\": [{\"id\": \"si_UXgRBUh17mxhjR\", \"plan\": {\"id\": \"price_1Sd3SQEgxqQWB3tMwm9eDbPZ\", \"meter\": null, \"active\": true, \"amount\": 999, \"object\": \"plan\", \"created\": 1765434874, \"product\": \"prod_TaDuix6Cq3Omo9\", \"currency\": \"usd\", \"interval\": \"month\", \"livemode\": false, \"metadata\": [], \"nickname\": null, \"tiers_mode\": null, \"usage_type\": \"licensed\", \"amount_decimal\": \"999\", \"billing_scheme\": \"per_unit\", \"interval_count\": 1, \"transform_usage\": null, \"trial_period_days\": null}, \"price\": {\"id\": \"price_1Sd3SQEgxqQWB3tMwm9eDbPZ\", \"type\": \"recurring\", \"active\": true, \"object\": \"price\", \"created\": 1765434874, \"product\": \"prod_TaDuix6Cq3Omo9\", \"currency\": \"usd\", \"livemode\": false, \"metadata\": [], \"nickname\": null, \"recurring\": {\"meter\": null, \"interval\": \"month\", \"usage_type\": \"licensed\", \"interval_count\": 1, \"trial_period_days\": null}, \"lookup_key\": null, \"tiers_mode\": null, \"unit_amount\": 999, \"tax_behavior\": \"unspecified\", \"billing_scheme\": \"per_unit\", \"custom_unit_amount\": null, \"transform_quantity\": null, \"unit_amount_decimal\": \"999\"}, \"object\": \"subscription_item\", \"created\": 1779148795, \"metadata\": [], \"quantity\": 1, \"discounts\": [], \"tax_rates\": [], \"subscription\": \"sub_1TYb4jEgxqQWB3tMEaVaWy1i\", \"billing_thresholds\": null, \"current_period_end\": 1781827195, \"current_period_start\": 1779148795}], \"object\": \"list\", \"has_more\": false, \"total_count\": 1}, \"object\": \"subscription\", \"status\": \"active\", \"created\": 1779148795, \"currency\": \"usd\", \"customer\": \"cus_UXgQzt0kW6AD7W\", \"ended_at\": null, \"livemode\": false, \"metadata\": {\"plan_code\": \"premium\", \"billing_cycle\": \"monthly\", \"billifty_user_id\": \"6\"}, \"quantity\": 1, \"schedule\": null, \"cancel_at\": null, \"discounts\": [], \"trial_end\": null, \"start_date\": 1779148795, \"test_clock\": null, \"application\": null, \"canceled_at\": null, \"description\": null, \"trial_start\": null, \"billing_mode\": {\"type\": \"flexible\", \"flexible\": {\"proration_discounts\": \"included\"}, \"updated_at\": 1779148752}, \"on_behalf_of\": null, \"automatic_tax\": {\"enabled\": false, \"liability\": null, \"disabled_reason\": null}, \"transfer_data\": null, \"days_until_due\": null, \"default_source\": null, \"latest_invoice\": \"in_1TYb4hEgxqQWB3tMBiRh0BMD\", \"pending_update\": null, \"trial_settings\": {\"end_behavior\": {\"missing_payment_method\": \"create_invoice\"}}, \"customer_account\": null, \"invoice_settings\": {\"issuer\": {\"type\": \"self\"}, \"account_tax_ids\": null}, \"managed_payments\": {\"enabled\": false}, \"pause_collection\": null, \"payment_settings\": {\"payment_method_types\": null, \"payment_method_options\": {\"pix\": null, \"upi\": null, \"card\": {\"network\": null, \"request_three_d_secure\": \"automatic\"}, \"payto\": null, \"konbini\": null, \"acss_debit\": null, \"bancontact\": null, \"sepa_debit\": null, \"us_bank_account\": null, \"customer_balance\": null}, \"save_default_payment_method\": \"off\"}, \"collection_method\": \"charge_automatically\", \"default_tax_rates\": [], \"billing_thresholds\": null, \"billing_cycle_anchor\": 1779148795, \"cancel_at_period_end\": false, \"cancellation_details\": {\"reason\": null, \"comment\": null, \"feedback\": null}, \"pending_setup_intent\": null, \"default_payment_method\": \"pm_1TYb4gEgxqQWB3tMlh05bEH9\", \"application_fee_percent\": null, \"billing_cycle_anchor_config\": null, \"pending_invoice_item_interval\": null, \"next_pending_invoice_item_invoice\": null}}, \"type\": \"customer.subscription.created\", \"object\": \"event\", \"created\": 1779148798, \"request\": {\"id\": null, \"idempotency_key\": \"fb90f48d-567c-4534-aa9b-95b3cdec902b\"}, \"livemode\": false, \"api_version\": \"2025-11-17.clover\", \"pending_webhooks\": 3}','2026-05-18 23:59:59','2026-05-18 23:59:59','2026-05-18 23:59:59'),
(10,6,'cus_UXgQzt0kW6AD7W','pi_3TYb4hEgxqQWB3tM0gcKHEEM','evt_3TYb4hEgxqQWB3tM0iCXMLOf','payment_intent.succeeded','2025-11-17.clover','0','{\"id\": \"evt_3TYb4hEgxqQWB3tM0iCXMLOf\", \"data\": {\"object\": {\"id\": \"pi_3TYb4hEgxqQWB3tM0gcKHEEM\", \"amount\": 999, \"object\": \"payment_intent\", \"review\": null, \"source\": null, \"status\": \"succeeded\", \"created\": 1779148795, \"currency\": \"usd\", \"customer\": \"cus_UXgQzt0kW6AD7W\", \"livemode\": false, \"metadata\": [], \"shipping\": null, \"processing\": null, \"application\": null, \"canceled_at\": null, \"description\": \"Subscription creation\", \"next_action\": null, \"on_behalf_of\": null, \"client_secret\": \"pi_3TYb4hEgxqQWB3tM0gcKHEEM_secret_smQ1SCQdNJ6gMWP1kz9P3tZ3l\", \"latest_charge\": \"ch_3TYb4hEgxqQWB3tM0aD7GHoF\", \"receipt_email\": null, \"transfer_data\": null, \"amount_details\": {\"tip\": []}, \"capture_method\": \"automatic\", \"payment_method\": \"pm_1TYb4gEgxqQWB3tMlh05bEH9\", \"transfer_group\": null, \"amount_received\": 999, \"payment_details\": {\"order_reference\": \"cs_test_b19kzQBX4fqX5ZUe7pKBhBJ3iyzWk5hgap7trr8DDcf6kujeaMPEi3mu7H\", \"customer_reference\": null}, \"customer_account\": null, \"managed_payments\": {\"enabled\": false}, \"amount_capturable\": 0, \"last_payment_error\": null, \"setup_future_usage\": \"off_session\", \"cancellation_reason\": null, \"confirmation_method\": \"automatic\", \"payment_method_types\": [\"card\"], \"statement_descriptor\": null, \"application_fee_amount\": null, \"payment_method_options\": {\"card\": {\"network\": null, \"installments\": null, \"mandate_options\": null, \"setup_future_usage\": \"off_session\", \"request_three_d_secure\": \"automatic\"}}, \"automatic_payment_methods\": null, \"statement_descriptor_suffix\": null, \"shared_payment_granted_token\": null, \"excluded_payment_method_types\": null, \"payment_method_configuration_details\": null}}, \"type\": \"payment_intent.succeeded\", \"object\": \"event\", \"created\": 1779148796, \"request\": {\"id\": null, \"idempotency_key\": \"fb90f48d-567c-4534-aa9b-95b3cdec902b\"}, \"livemode\": false, \"api_version\": \"2025-11-17.clover\", \"pending_webhooks\": 3}','2026-05-18 23:59:59','2026-05-18 23:59:59','2026-05-18 23:59:59'),
(11,6,'cus_UXgQzt0kW6AD7W','pi_3TYb4hEgxqQWB3tM0gcKHEEM','evt_3TYb4hEgxqQWB3tM05xQipUI','payment_intent.created','2025-11-17.clover','0','{\"id\": \"evt_3TYb4hEgxqQWB3tM05xQipUI\", \"data\": {\"object\": {\"id\": \"pi_3TYb4hEgxqQWB3tM0gcKHEEM\", \"amount\": 999, \"object\": \"payment_intent\", \"review\": null, \"source\": null, \"status\": \"requires_payment_method\", \"created\": 1779148795, \"currency\": \"usd\", \"customer\": \"cus_UXgQzt0kW6AD7W\", \"livemode\": false, \"metadata\": [], \"shipping\": null, \"processing\": null, \"application\": null, \"canceled_at\": null, \"description\": \"Subscription creation\", \"next_action\": null, \"on_behalf_of\": null, \"client_secret\": \"pi_3TYb4hEgxqQWB3tM0gcKHEEM_secret_smQ1SCQdNJ6gMWP1kz9P3tZ3l\", \"latest_charge\": null, \"receipt_email\": null, \"transfer_data\": null, \"amount_details\": {\"tip\": []}, \"capture_method\": \"automatic\", \"payment_method\": null, \"transfer_group\": null, \"amount_received\": 0, \"customer_account\": null, \"managed_payments\": {\"enabled\": false}, \"amount_capturable\": 0, \"last_payment_error\": null, \"setup_future_usage\": \"off_session\", \"cancellation_reason\": null, \"confirmation_method\": \"automatic\", \"payment_method_types\": [\"amazon_pay\", \"card\", \"cashapp\", \"klarna\", \"link\"], \"statement_descriptor\": null, \"application_fee_amount\": null, \"payment_method_options\": {\"card\": {\"network\": null, \"installments\": null, \"mandate_options\": null, \"request_three_d_secure\": \"automatic\"}, \"link\": {\"persistent_token\": null}, \"klarna\": {\"preferred_locale\": null}, \"cashapp\": [], \"amazon_pay\": {\"express_checkout_element_session_id\": null}}, \"automatic_payment_methods\": null, \"statement_descriptor_suffix\": null, \"shared_payment_granted_token\": null, \"excluded_payment_method_types\": null, \"payment_method_configuration_details\": null}}, \"type\": \"payment_intent.created\", \"object\": \"event\", \"created\": 1779148795, \"request\": {\"id\": null, \"idempotency_key\": \"fb90f48d-567c-4534-aa9b-95b3cdec902b\"}, \"livemode\": false, \"api_version\": \"2025-11-17.clover\", \"pending_webhooks\": 3}','2026-05-19 00:00:00','2026-05-19 00:00:00','2026-05-19 00:00:00'),
(12,NULL,NULL,'inpay_1TYb4kEgxqQWB3tM2OQ6SYXr','evt_1TYb5EEgxqQWB3tM1h5jXY4P','invoice_payment.paid','2025-11-17.clover','0','{\"id\": \"evt_1TYb5EEgxqQWB3tM1h5jXY4P\", \"data\": {\"object\": {\"id\": \"inpay_1TYb4kEgxqQWB3tM2OQ6SYXr\", \"object\": \"invoice_payment\", \"status\": \"paid\", \"created\": 1779148795, \"invoice\": \"in_1TYb4hEgxqQWB3tMBiRh0BMD\", \"payment\": {\"type\": \"payment_intent\", \"payment_intent\": \"pi_3TYb4hEgxqQWB3tM0gcKHEEM\"}, \"currency\": \"usd\", \"livemode\": false, \"is_default\": true, \"amount_paid\": 999, \"amount_requested\": 999, \"status_transitions\": {\"paid_at\": 1779148796, \"canceled_at\": null}}}, \"type\": \"invoice_payment.paid\", \"object\": \"event\", \"created\": 1779148828, \"request\": {\"id\": null, \"idempotency_key\": null}, \"livemode\": false, \"api_version\": \"2025-11-17.clover\", \"pending_webhooks\": 3}','2026-05-19 00:00:29','2026-05-19 00:00:29','2026-05-19 00:00:29'),
(13,NULL,NULL,'po_1TYbkFEgxqsR1VDL0u2sQzxM','evt_1TYbkGEgxqsR1VDLlhnaSPZ7','payout.created','2025-11-17.clover','0','{\"id\": \"evt_1TYbkGEgxqsR1VDLlhnaSPZ7\", \"data\": {\"object\": {\"id\": \"po_1TYbkFEgxqsR1VDL0u2sQzxM\", \"type\": \"bank_account\", \"amount\": 344052, \"method\": \"standard\", \"object\": \"payout\", \"status\": \"in_transit\", \"created\": 1779151371, \"currency\": \"usd\", \"livemode\": false, \"metadata\": [], \"trace_id\": {\"value\": null, \"status\": \"pending\"}, \"automatic\": true, \"description\": \"STRIPE PAYOUT\", \"destination\": \"ba_1TUyY6EgxqsR1VDLiUyEzHN7\", \"reversed_by\": null, \"source_type\": \"card\", \"arrival_date\": 1779148800, \"failure_code\": null, \"payout_method\": null, \"application_fee\": null, \"failure_message\": null, \"original_payout\": null, \"balance_transaction\": \"txn_1TYbkGEgxqsR1VDLtOvvgPwY\", \"statement_descriptor\": null, \"reconciliation_status\": \"completed\", \"application_fee_amount\": null, \"failure_balance_transaction\": null}}, \"type\": \"payout.created\", \"object\": \"event\", \"account\": \"acct_1TUyXrEgxqsR1VDL\", \"context\": \"acct_1TUyXrEgxqsR1VDL\", \"created\": 1779151372, \"request\": {\"id\": null, \"idempotency_key\": null}, \"livemode\": false, \"api_version\": \"2025-11-17.clover\", \"pending_webhooks\": 2}','2026-05-19 00:42:53','2026-05-19 00:42:53','2026-05-19 00:42:53'),
(14,NULL,NULL,'po_1TYbkFEgxqsR1VDL0u2sQzxM','evt_1TYbkHEgxqsR1VDLuQnlj7iT','payout.reconciliation_completed','2025-11-17.clover','0','{\"id\": \"evt_1TYbkHEgxqsR1VDLuQnlj7iT\", \"data\": {\"object\": {\"id\": \"po_1TYbkFEgxqsR1VDL0u2sQzxM\", \"type\": \"bank_account\", \"amount\": 344052, \"method\": \"standard\", \"object\": \"payout\", \"status\": \"in_transit\", \"created\": 1779151371, \"currency\": \"usd\", \"livemode\": false, \"metadata\": [], \"trace_id\": {\"value\": null, \"status\": \"pending\"}, \"automatic\": true, \"description\": \"STRIPE PAYOUT\", \"destination\": \"ba_1TUyY6EgxqsR1VDLiUyEzHN7\", \"reversed_by\": null, \"source_type\": \"card\", \"arrival_date\": 1779148800, \"failure_code\": null, \"payout_method\": null, \"application_fee\": null, \"failure_message\": null, \"original_payout\": null, \"balance_transaction\": \"txn_1TYbkGEgxqsR1VDLtOvvgPwY\", \"statement_descriptor\": null, \"reconciliation_status\": \"completed\", \"application_fee_amount\": null, \"failure_balance_transaction\": null}}, \"type\": \"payout.reconciliation_completed\", \"object\": \"event\", \"account\": \"acct_1TUyXrEgxqsR1VDL\", \"context\": \"acct_1TUyXrEgxqsR1VDL\", \"created\": 1779151373, \"request\": {\"id\": null, \"idempotency_key\": null}, \"livemode\": false, \"api_version\": \"2025-11-17.clover\", \"pending_webhooks\": 2}','2026-05-19 00:42:53','2026-05-19 00:42:53','2026-05-19 00:42:53'),
(15,NULL,NULL,'po_1TYbkFEgxqsR1VDL0u2sQzxM','evt_1TYbp7EgxqsR1VDLgwxJLYYP','payout.paid','2025-11-17.clover','0','{\"id\": \"evt_1TYbp7EgxqsR1VDLgwxJLYYP\", \"data\": {\"object\": {\"id\": \"po_1TYbkFEgxqsR1VDL0u2sQzxM\", \"type\": \"bank_account\", \"amount\": 344052, \"method\": \"standard\", \"object\": \"payout\", \"status\": \"paid\", \"created\": 1779151371, \"currency\": \"usd\", \"livemode\": false, \"metadata\": [], \"trace_id\": {\"value\": null, \"status\": \"pending\"}, \"automatic\": true, \"description\": \"STRIPE PAYOUT\", \"destination\": \"ba_1TUyY6EgxqsR1VDLiUyEzHN7\", \"reversed_by\": null, \"source_type\": \"card\", \"arrival_date\": 1779148800, \"failure_code\": null, \"payout_method\": null, \"application_fee\": null, \"failure_message\": null, \"original_payout\": null, \"balance_transaction\": \"txn_1TYbkGEgxqsR1VDLtOvvgPwY\", \"statement_descriptor\": null, \"reconciliation_status\": \"completed\", \"application_fee_amount\": null, \"failure_balance_transaction\": null}}, \"type\": \"payout.paid\", \"object\": \"event\", \"account\": \"acct_1TUyXrEgxqsR1VDL\", \"context\": \"acct_1TUyXrEgxqsR1VDL\", \"created\": 1779151673, \"request\": {\"id\": null, \"idempotency_key\": null}, \"livemode\": false, \"api_version\": \"2025-11-17.clover\", \"pending_webhooks\": 2}','2026-05-19 00:47:53','2026-05-19 00:47:53','2026-05-19 00:47:53'),
(16,NULL,NULL,'po_1TYbkFEgxqsR1VDL0u2sQzxM','evt_1TYbp7EgxqsR1VDLg6fUCIji','payout.updated','2025-11-17.clover','0','{\"id\": \"evt_1TYbp7EgxqsR1VDLg6fUCIji\", \"data\": {\"object\": {\"id\": \"po_1TYbkFEgxqsR1VDL0u2sQzxM\", \"type\": \"bank_account\", \"amount\": 344052, \"method\": \"standard\", \"object\": \"payout\", \"status\": \"paid\", \"created\": 1779151371, \"currency\": \"usd\", \"livemode\": false, \"metadata\": [], \"trace_id\": {\"value\": \"7UF6L35cH6YN4g96tE7YR5TN5kC54a7nu7g989g1F\", \"status\": \"supported\"}, \"automatic\": true, \"description\": \"STRIPE PAYOUT\", \"destination\": \"ba_1TUyY6EgxqsR1VDLiUyEzHN7\", \"reversed_by\": null, \"source_type\": \"card\", \"arrival_date\": 1779148800, \"failure_code\": null, \"payout_method\": null, \"application_fee\": null, \"failure_message\": null, \"original_payout\": null, \"balance_transaction\": \"txn_1TYbkGEgxqsR1VDLtOvvgPwY\", \"statement_descriptor\": null, \"reconciliation_status\": \"completed\", \"application_fee_amount\": null, \"failure_balance_transaction\": null}, \"previous_attributes\": {\"trace_id\": {\"value\": null, \"status\": \"pending\"}}}, \"type\": \"payout.updated\", \"object\": \"event\", \"account\": \"acct_1TUyXrEgxqsR1VDL\", \"context\": \"acct_1TUyXrEgxqsR1VDL\", \"created\": 1779151673, \"request\": {\"id\": null, \"idempotency_key\": null}, \"livemode\": false, \"api_version\": \"2025-11-17.clover\", \"pending_webhooks\": 2}','2026-05-19 00:47:53','2026-05-19 00:47:53','2026-05-19 00:47:53'),
(17,NULL,'cus_ULNDU4ML6NFZDn','pi_3TYeDgEgxqQWB3tM0MDoQg9u','evt_3TYeDgEgxqQWB3tM0wfu4jN4','payment_intent.succeeded','2025-11-17.clover','0','{\"id\": \"evt_3TYeDgEgxqQWB3tM0wfu4jN4\", \"data\": {\"object\": {\"id\": \"pi_3TYeDgEgxqQWB3tM0MDoQg9u\", \"amount\": 999, \"object\": \"payment_intent\", \"review\": null, \"source\": null, \"status\": \"succeeded\", \"created\": 1779160884, \"currency\": \"usd\", \"customer\": \"cus_ULNDU4ML6NFZDn\", \"livemode\": false, \"metadata\": [], \"shipping\": null, \"processing\": null, \"application\": null, \"canceled_at\": null, \"description\": \"Subscription update\", \"next_action\": null, \"on_behalf_of\": null, \"client_secret\": \"pi_3TYeDgEgxqQWB3tM0MDoQg9u_secret_iu1bMY8iG06nsoUH8LUsjtFiV\", \"latest_charge\": \"ch_3TYeDgEgxqQWB3tM0m96kApA\", \"receipt_email\": null, \"transfer_data\": null, \"amount_details\": {\"tip\": []}, \"capture_method\": \"automatic\", \"payment_method\": \"pm_1TMgU2EgxqQWB3tM7SoadUSe\", \"transfer_group\": null, \"amount_received\": 999, \"payment_details\": {\"order_reference\": \"in_1TXYmWEgxqQWB3tMQsclBu2M\", \"customer_reference\": null}, \"customer_account\": null, \"managed_payments\": {\"enabled\": false}, \"amount_capturable\": 0, \"last_payment_error\": null, \"setup_future_usage\": null, \"cancellation_reason\": null, \"confirmation_method\": \"automatic\", \"payment_method_types\": [\"amazon_pay\", \"card\", \"cashapp\", \"klarna\", \"link\"], \"statement_descriptor\": null, \"application_fee_amount\": null, \"payment_method_options\": {\"card\": {\"network\": null, \"installments\": null, \"mandate_options\": null, \"request_three_d_secure\": \"automatic\"}, \"link\": {\"persistent_token\": null}, \"klarna\": {\"preferred_locale\": null}, \"cashapp\": [], \"amazon_pay\": {\"express_checkout_element_session_id\": null}}, \"automatic_payment_methods\": null, \"statement_descriptor_suffix\": null, \"shared_payment_granted_token\": null, \"excluded_payment_method_types\": null, \"payment_method_configuration_details\": null}}, \"type\": \"payment_intent.succeeded\", \"object\": \"event\", \"created\": 1779160887, \"request\": {\"id\": null, \"idempotency_key\": \"in_1TXYmWEgxqQWB3tMQsclBu2M-initial_attempt-aa5b7b95659bd93ff\"}, \"livemode\": false, \"api_version\": \"2025-11-17.clover\", \"pending_webhooks\": 3}','2026-05-19 03:21:27','2026-05-19 03:21:27','2026-05-19 03:21:27'),
(18,NULL,'cus_ULNDU4ML6NFZDn','ch_3TYeDgEgxqQWB3tM0m96kApA','evt_3TYeDgEgxqQWB3tM0P2iBFDE','charge.succeeded','2025-11-17.clover','0','{\"id\": \"evt_3TYeDgEgxqQWB3tM0P2iBFDE\", \"data\": {\"object\": {\"id\": \"ch_3TYeDgEgxqQWB3tM0m96kApA\", \"paid\": true, \"order\": null, \"amount\": 999, \"object\": \"charge\", \"review\": null, \"source\": null, \"status\": \"succeeded\", \"created\": 1779160886, \"dispute\": null, \"outcome\": {\"type\": \"authorized\", \"reason\": null, \"risk_level\": \"normal\", \"risk_score\": 21, \"advice_code\": null, \"network_status\": \"approved_by_network\", \"seller_message\": \"Payment complete.\", \"network_advice_code\": null, \"network_decline_code\": null}, \"captured\": true, \"currency\": \"usd\", \"customer\": \"cus_ULNDU4ML6NFZDn\", \"disputed\": false, \"livemode\": false, \"metadata\": [], \"refunded\": false, \"shipping\": null, \"application\": null, \"description\": \"Subscription update\", \"destination\": null, \"receipt_url\": \"https://pay.stripe.com/receipts/invoices/CAcaFwoVYWNjdF8xU2QxV2pFZ3hxUVdCM3RNKLeur9AGMgbjGEabrjo6LBZGiPaTxGT_mAa835tM0KYgZ85FW-seAyJEjvKKQ4Bx5xXyWEtcsUxaScX7?s=ap\", \"failure_code\": null, \"on_behalf_of\": null, \"fraud_details\": [], \"radar_options\": [], \"receipt_email\": null, \"transfer_data\": null, \"payment_intent\": \"pi_3TYeDgEgxqQWB3tM0MDoQg9u\", \"payment_method\": \"pm_1TMgU2EgxqQWB3tM7SoadUSe\", \"receipt_number\": null, \"transfer_group\": null, \"amount_captured\": 999, \"amount_refunded\": 0, \"application_fee\": null, \"billing_details\": {\"name\": \"John Doe King\", \"email\": \"jokoy@gmail.com\", \"phone\": null, \"tax_id\": null, \"address\": {\"city\": null, \"line1\": null, \"line2\": null, \"state\": null, \"country\": \"US\", \"postal_code\": \"12112\"}}, \"failure_message\": null, \"source_transfer\": null, \"balance_transaction\": \"txn_3TYeDgEgxqQWB3tM050W9Jlm\", \"statement_descriptor\": null, \"application_fee_amount\": null, \"payment_method_details\": {\"card\": {\"brand\": \"diners\", \"last4\": \"0004\", \"checks\": {\"cvc_check\": null, \"address_line1_check\": null, \"address_postal_code_check\": \"unchecked\"}, \"wallet\": null, \"country\": \"US\", \"funding\": \"credit\", \"mandate\": null, \"network\": \"diners\", \"exp_year\": 2033, \"exp_month\": 4, \"fingerprint\": \"nZVZDhtg1nTjta0V\", \"overcapture\": {\"status\": \"unavailable\", \"maximum_amount_capturable\": 999}, \"installments\": null, \"multicapture\": {\"status\": \"unavailable\"}, \"network_token\": {\"used\": false}, \"three_d_secure\": null, \"regulated_status\": \"unregulated\", \"amount_authorized\": 999, \"ds_transaction_id\": null, \"authorization_code\": \"IQdDIF\", \"extended_authorization\": {\"status\": \"disabled\"}, \"network_transaction_id\": \"110908690681041\", \"incremental_authorization\": {\"status\": \"unavailable\"}}, \"type\": \"card\"}, \"failure_balance_transaction\": null, \"statement_descriptor_suffix\": null, \"calculated_statement_descriptor\": \"Stripe\"}}, \"type\": \"charge.succeeded\", \"object\": \"event\", \"created\": 1779160886, \"request\": {\"id\": null, \"idempotency_key\": \"in_1TXYmWEgxqQWB3tMQsclBu2M-initial_attempt-aa5b7b95659bd93ff\"}, \"livemode\": false, \"api_version\": \"2025-11-17.clover\", \"pending_webhooks\": 3}','2026-05-19 03:21:27','2026-05-19 03:21:27','2026-05-19 03:21:27'),
(19,NULL,'cus_ULNDU4ML6NFZDn','pi_3TYeDgEgxqQWB3tM0MDoQg9u','evt_3TYeDgEgxqQWB3tM040csCHN','payment_intent.created','2025-11-17.clover','0','{\"id\": \"evt_3TYeDgEgxqQWB3tM040csCHN\", \"data\": {\"object\": {\"id\": \"pi_3TYeDgEgxqQWB3tM0MDoQg9u\", \"amount\": 999, \"object\": \"payment_intent\", \"review\": null, \"source\": null, \"status\": \"requires_payment_method\", \"created\": 1779160884, \"currency\": \"usd\", \"customer\": \"cus_ULNDU4ML6NFZDn\", \"livemode\": false, \"metadata\": [], \"shipping\": null, \"processing\": null, \"application\": null, \"canceled_at\": null, \"description\": \"Subscription update\", \"next_action\": null, \"on_behalf_of\": null, \"client_secret\": \"pi_3TYeDgEgxqQWB3tM0MDoQg9u_secret_iu1bMY8iG06nsoUH8LUsjtFiV\", \"latest_charge\": null, \"receipt_email\": null, \"transfer_data\": null, \"amount_details\": {\"tip\": []}, \"capture_method\": \"automatic\", \"payment_method\": null, \"transfer_group\": null, \"amount_received\": 0, \"customer_account\": null, \"managed_payments\": {\"enabled\": false}, \"amount_capturable\": 0, \"last_payment_error\": null, \"setup_future_usage\": null, \"cancellation_reason\": null, \"confirmation_method\": \"automatic\", \"payment_method_types\": [\"amazon_pay\", \"card\", \"cashapp\", \"klarna\", \"link\"], \"statement_descriptor\": null, \"application_fee_amount\": null, \"payment_method_options\": {\"card\": {\"network\": null, \"installments\": null, \"mandate_options\": null, \"request_three_d_secure\": \"automatic\"}, \"link\": {\"persistent_token\": null}, \"klarna\": {\"preferred_locale\": null}, \"cashapp\": [], \"amazon_pay\": {\"express_checkout_element_session_id\": null}}, \"automatic_payment_methods\": null, \"statement_descriptor_suffix\": null, \"shared_payment_granted_token\": null, \"excluded_payment_method_types\": null, \"payment_method_configuration_details\": null}}, \"type\": \"payment_intent.created\", \"object\": \"event\", \"created\": 1779160888, \"request\": {\"id\": null, \"idempotency_key\": null}, \"livemode\": false, \"api_version\": \"2025-11-17.clover\", \"pending_webhooks\": 3}','2026-05-19 03:21:28','2026-05-19 03:21:28','2026-05-19 03:21:28'),
(20,NULL,'cus_ULNDU4ML6NFZDn','in_1TXYmWEgxqQWB3tMQsclBu2M','evt_1TYeDkEgxqQWB3tMRN98YIb9','invoice.updated','2025-11-17.clover','0','{\"id\": \"evt_1TYeDkEgxqQWB3tMRN98YIb9\", \"data\": {\"object\": {\"id\": \"in_1TXYmWEgxqQWB3tMQsclBu2M\", \"lines\": {\"url\": \"/v1/invoices/in_1TXYmWEgxqQWB3tMQsclBu2M/lines\", \"data\": [{\"id\": \"il_1TXYmVEgxqQWB3tM4vcZ4b2C\", \"taxes\": [], \"amount\": 999, \"object\": \"line_item\", \"parent\": {\"type\": \"subscription_item_details\", \"invoice_item_details\": null, \"subscription_item_details\": {\"proration\": false, \"invoice_item\": null, \"subscription\": \"sub_1TMgU5EgxqQWB3tMtubFNFwO\", \"proration_details\": {\"credited_items\": null}, \"subscription_item\": \"si_ULNEQ7q2wVGm5l\"}, \"license_fee_subscription_details\": null}, \"period\": {\"end\": 1781580051, \"start\": 1778901651}, \"invoice\": \"in_1TXYmWEgxqQWB3tMQsclBu2M\", \"pricing\": {\"type\": \"price_details\", \"price_details\": {\"price\": \"price_1Sd3SQEgxqQWB3tMwm9eDbPZ\", \"product\": \"prod_TaDuix6Cq3Omo9\"}, \"unit_amount_decimal\": \"999\"}, \"currency\": \"usd\", \"livemode\": false, \"metadata\": {\"plan_code\": \"premium\", \"billing_cycle\": \"monthly\", \"billifty_user_id\": \"7\"}, \"quantity\": 1, \"subtotal\": 999, \"discounts\": [], \"description\": \"1 × Premium – Monthly (at $9.99 / month)\", \"discountable\": true, \"discount_amounts\": [], \"quantity_decimal\": \"1\", \"pretax_credit_amounts\": []}], \"object\": \"list\", \"has_more\": false, \"total_count\": 1}, \"total\": 999, \"footer\": null, \"issuer\": {\"type\": \"self\"}, \"number\": \"WQ4RFXYV-0002\", \"object\": \"invoice\", \"parent\": {\"type\": \"subscription_details\", \"quote_details\": null, \"subscription_details\": {\"metadata\": {\"plan_code\": \"premium\", \"billing_cycle\": \"monthly\", \"billifty_user_id\": \"7\"}, \"subscription\": \"sub_1TMgU5EgxqQWB3tMtubFNFwO\"}}, \"status\": \"paid\", \"created\": 1778901652, \"currency\": \"usd\", \"customer\": \"cus_ULNDU4ML6NFZDn\", \"due_date\": null, \"livemode\": false, \"metadata\": [], \"subtotal\": 999, \"attempted\": true, \"discounts\": [], \"rendering\": null, \"amount_due\": 999, \"period_end\": 1778901651, \"test_clock\": null, \"amount_paid\": 999, \"application\": null, \"description\": null, \"invoice_pdf\": \"https://pay.stripe.com/invoice/acct_1Sd1WjEgxqQWB3tM/test_YWNjdF8xU2QxV2pFZ3hxUVdCM3RNLF9VV2MwYXVSWWFNSWdRdTFHVkJPUU9yNzRNWDFaOVo0LDE2OTcwMTY4OA0200IA8UMI8P/pdf?s=ap\", \"total_taxes\": [], \"account_name\": \"Billifty sandbox\", \"auto_advance\": false, \"effective_at\": 1779160882, \"from_invoice\": null, \"on_behalf_of\": null, \"period_start\": 1776309651, \"attempt_count\": 1, \"automatic_tax\": {\"status\": null, \"enabled\": false, \"provider\": null, \"liability\": null, \"disabled_reason\": null}, \"custom_fields\": null, \"customer_name\": \"Jo Koy\", \"shipping_cost\": null, \"billing_reason\": \"subscription_cycle\", \"customer_email\": \"jokoy@gmail.com\", \"customer_phone\": null, \"default_source\": null, \"ending_balance\": 0, \"receipt_number\": null, \"account_country\": \"US\", \"account_tax_ids\": null, \"amount_overpaid\": 0, \"amount_shipping\": 0, \"latest_revision\": null, \"amount_remaining\": 0, \"customer_account\": null, \"customer_address\": null, \"customer_tax_ids\": [], \"payment_settings\": {\"default_mandate\": null, \"payment_method_types\": null, \"payment_method_options\": {\"pix\": null, \"upi\": null, \"card\": {\"request_three_d_secure\": \"automatic\"}, \"payto\": null, \"konbini\": null, \"acss_debit\": null, \"bancontact\": null, \"sepa_debit\": null, \"us_bank_account\": null, \"customer_balance\": null}}, \"shipping_details\": null, \"starting_balance\": 0, \"collection_method\": \"charge_automatically\", \"customer_shipping\": null, \"default_tax_rates\": [], \"hosted_invoice_url\": \"https://invoice.stripe.com/i/acct_1Sd1WjEgxqQWB3tM/test_YWNjdF8xU2QxV2pFZ3hxUVdCM3RNLF9VV2MwYXVSWWFNSWdRdTFHVkJPUU9yNzRNWDFaOVo0LDE2OTcwMTY4OA0200IA8UMI8P?s=ap\", \"status_transitions\": {\"paid_at\": 1779160882, \"voided_at\": null, \"finalized_at\": 1779160882, \"marked_uncollectible_at\": null}, \"customer_tax_exempt\": \"none\", \"total_excluding_tax\": 999, \"next_payment_attempt\": null, \"statement_descriptor\": null, \"webhooks_delivered_at\": null, \"default_payment_method\": null, \"subtotal_excluding_tax\": 999, \"total_discount_amounts\": [], \"last_finalization_error\": null, \"automatically_finalizes_at\": null, \"total_pretax_credit_amounts\": [], \"pre_payment_credit_notes_amount\": 0, \"post_payment_credit_notes_amount\": 0}, \"previous_attributes\": {\"number\": null, \"status\": \"draft\", \"attempted\": false, \"amount_paid\": 0, \"invoice_pdf\": null, \"auto_advance\": true, \"effective_at\": null, \"attempt_count\": 0, \"ending_balance\": null, \"amount_remaining\": 999, \"hosted_invoice_url\": null, \"status_transitions\": {\"paid_at\": null, \"finalized_at\": null}, \"next_payment_attempt\": 1778905252, \"automatically_finalizes_at\": 1779160853}}, \"type\": \"invoice.updated\", \"object\": \"event\", \"created\": 1779160888, \"request\": {\"id\": null, \"idempotency_key\": null}, \"livemode\": false, \"api_version\": \"2025-11-17.clover\", \"pending_webhooks\": 3}','2026-05-19 03:21:28','2026-05-19 03:21:28','2026-05-19 03:21:28'),
(21,NULL,'cus_ULNDU4ML6NFZDn','in_1TXYmWEgxqQWB3tMQsclBu2M','evt_1TYeDkEgxqQWB3tMGJXX4xTq','invoice.finalized','2025-11-17.clover','0','{\"id\": \"evt_1TYeDkEgxqQWB3tMGJXX4xTq\", \"data\": {\"object\": {\"id\": \"in_1TXYmWEgxqQWB3tMQsclBu2M\", \"lines\": {\"url\": \"/v1/invoices/in_1TXYmWEgxqQWB3tMQsclBu2M/lines\", \"data\": [{\"id\": \"il_1TXYmVEgxqQWB3tM4vcZ4b2C\", \"taxes\": [], \"amount\": 999, \"object\": \"line_item\", \"parent\": {\"type\": \"subscription_item_details\", \"invoice_item_details\": null, \"subscription_item_details\": {\"proration\": false, \"invoice_item\": null, \"subscription\": \"sub_1TMgU5EgxqQWB3tMtubFNFwO\", \"proration_details\": {\"credited_items\": null}, \"subscription_item\": \"si_ULNEQ7q2wVGm5l\"}, \"license_fee_subscription_details\": null}, \"period\": {\"end\": 1781580051, \"start\": 1778901651}, \"invoice\": \"in_1TXYmWEgxqQWB3tMQsclBu2M\", \"pricing\": {\"type\": \"price_details\", \"price_details\": {\"price\": \"price_1Sd3SQEgxqQWB3tMwm9eDbPZ\", \"product\": \"prod_TaDuix6Cq3Omo9\"}, \"unit_amount_decimal\": \"999\"}, \"currency\": \"usd\", \"livemode\": false, \"metadata\": {\"plan_code\": \"premium\", \"billing_cycle\": \"monthly\", \"billifty_user_id\": \"7\"}, \"quantity\": 1, \"subtotal\": 999, \"discounts\": [], \"description\": \"1 × Premium – Monthly (at $9.99 / month)\", \"discountable\": true, \"discount_amounts\": [], \"quantity_decimal\": \"1\", \"pretax_credit_amounts\": []}], \"object\": \"list\", \"has_more\": false, \"total_count\": 1}, \"total\": 999, \"footer\": null, \"issuer\": {\"type\": \"self\"}, \"number\": \"WQ4RFXYV-0002\", \"object\": \"invoice\", \"parent\": {\"type\": \"subscription_details\", \"quote_details\": null, \"subscription_details\": {\"metadata\": {\"plan_code\": \"premium\", \"billing_cycle\": \"monthly\", \"billifty_user_id\": \"7\"}, \"subscription\": \"sub_1TMgU5EgxqQWB3tMtubFNFwO\"}}, \"status\": \"paid\", \"created\": 1778901652, \"currency\": \"usd\", \"customer\": \"cus_ULNDU4ML6NFZDn\", \"due_date\": null, \"livemode\": false, \"metadata\": [], \"subtotal\": 999, \"attempted\": true, \"discounts\": [], \"rendering\": null, \"amount_due\": 999, \"period_end\": 1778901651, \"test_clock\": null, \"amount_paid\": 999, \"application\": null, \"description\": null, \"invoice_pdf\": \"https://pay.stripe.com/invoice/acct_1Sd1WjEgxqQWB3tM/test_YWNjdF8xU2QxV2pFZ3hxUVdCM3RNLF9VV2MwYXVSWWFNSWdRdTFHVkJPUU9yNzRNWDFaOVo0LDE2OTcwMTY4OA0200IA8UMI8P/pdf?s=ap\", \"total_taxes\": [], \"account_name\": \"Billifty sandbox\", \"auto_advance\": false, \"effective_at\": 1779160882, \"from_invoice\": null, \"on_behalf_of\": null, \"period_start\": 1776309651, \"attempt_count\": 1, \"automatic_tax\": {\"status\": null, \"enabled\": false, \"provider\": null, \"liability\": null, \"disabled_reason\": null}, \"custom_fields\": null, \"customer_name\": \"Jo Koy\", \"shipping_cost\": null, \"billing_reason\": \"subscription_cycle\", \"customer_email\": \"jokoy@gmail.com\", \"customer_phone\": null, \"default_source\": null, \"ending_balance\": 0, \"receipt_number\": null, \"account_country\": \"US\", \"account_tax_ids\": null, \"amount_overpaid\": 0, \"amount_shipping\": 0, \"latest_revision\": null, \"amount_remaining\": 0, \"customer_account\": null, \"customer_address\": null, \"customer_tax_ids\": [], \"payment_settings\": {\"default_mandate\": null, \"payment_method_types\": null, \"payment_method_options\": {\"pix\": null, \"upi\": null, \"card\": {\"request_three_d_secure\": \"automatic\"}, \"payto\": null, \"konbini\": null, \"acss_debit\": null, \"bancontact\": null, \"sepa_debit\": null, \"us_bank_account\": null, \"customer_balance\": null}}, \"shipping_details\": null, \"starting_balance\": 0, \"collection_method\": \"charge_automatically\", \"customer_shipping\": null, \"default_tax_rates\": [], \"hosted_invoice_url\": \"https://invoice.stripe.com/i/acct_1Sd1WjEgxqQWB3tM/test_YWNjdF8xU2QxV2pFZ3hxUVdCM3RNLF9VV2MwYXVSWWFNSWdRdTFHVkJPUU9yNzRNWDFaOVo0LDE2OTcwMTY4OA0200IA8UMI8P?s=ap\", \"status_transitions\": {\"paid_at\": 1779160882, \"voided_at\": null, \"finalized_at\": 1779160882, \"marked_uncollectible_at\": null}, \"customer_tax_exempt\": \"none\", \"total_excluding_tax\": 999, \"next_payment_attempt\": null, \"statement_descriptor\": null, \"webhooks_delivered_at\": null, \"default_payment_method\": null, \"subtotal_excluding_tax\": 999, \"total_discount_amounts\": [], \"last_finalization_error\": null, \"automatically_finalizes_at\": null, \"total_pretax_credit_amounts\": [], \"pre_payment_credit_notes_amount\": 0, \"post_payment_credit_notes_amount\": 0}}, \"type\": \"invoice.finalized\", \"object\": \"event\", \"created\": 1779160888, \"request\": {\"id\": null, \"idempotency_key\": null}, \"livemode\": false, \"api_version\": \"2025-11-17.clover\", \"pending_webhooks\": 3}','2026-05-19 03:21:29','2026-05-19 03:21:29','2026-05-19 03:21:29'),
(22,NULL,'cus_ULNDU4ML6NFZDn','in_1TXYmWEgxqQWB3tMQsclBu2M','evt_1TYeDkEgxqQWB3tMXqV8QF7l','invoice.paid','2025-11-17.clover','0','{\"id\": \"evt_1TYeDkEgxqQWB3tMXqV8QF7l\", \"data\": {\"object\": {\"id\": \"in_1TXYmWEgxqQWB3tMQsclBu2M\", \"lines\": {\"url\": \"/v1/invoices/in_1TXYmWEgxqQWB3tMQsclBu2M/lines\", \"data\": [{\"id\": \"il_1TXYmVEgxqQWB3tM4vcZ4b2C\", \"taxes\": [], \"amount\": 999, \"object\": \"line_item\", \"parent\": {\"type\": \"subscription_item_details\", \"invoice_item_details\": null, \"subscription_item_details\": {\"proration\": false, \"invoice_item\": null, \"subscription\": \"sub_1TMgU5EgxqQWB3tMtubFNFwO\", \"proration_details\": {\"credited_items\": null}, \"subscription_item\": \"si_ULNEQ7q2wVGm5l\"}, \"license_fee_subscription_details\": null}, \"period\": {\"end\": 1781580051, \"start\": 1778901651}, \"invoice\": \"in_1TXYmWEgxqQWB3tMQsclBu2M\", \"pricing\": {\"type\": \"price_details\", \"price_details\": {\"price\": \"price_1Sd3SQEgxqQWB3tMwm9eDbPZ\", \"product\": \"prod_TaDuix6Cq3Omo9\"}, \"unit_amount_decimal\": \"999\"}, \"currency\": \"usd\", \"livemode\": false, \"metadata\": {\"plan_code\": \"premium\", \"billing_cycle\": \"monthly\", \"billifty_user_id\": \"7\"}, \"quantity\": 1, \"subtotal\": 999, \"discounts\": [], \"description\": \"1 × Premium – Monthly (at $9.99 / month)\", \"discountable\": true, \"discount_amounts\": [], \"quantity_decimal\": \"1\", \"pretax_credit_amounts\": []}], \"object\": \"list\", \"has_more\": false, \"total_count\": 1}, \"total\": 999, \"footer\": null, \"issuer\": {\"type\": \"self\"}, \"number\": \"WQ4RFXYV-0002\", \"object\": \"invoice\", \"parent\": {\"type\": \"subscription_details\", \"quote_details\": null, \"subscription_details\": {\"metadata\": {\"plan_code\": \"premium\", \"billing_cycle\": \"monthly\", \"billifty_user_id\": \"7\"}, \"subscription\": \"sub_1TMgU5EgxqQWB3tMtubFNFwO\"}}, \"status\": \"paid\", \"created\": 1778901652, \"currency\": \"usd\", \"customer\": \"cus_ULNDU4ML6NFZDn\", \"due_date\": null, \"livemode\": false, \"metadata\": [], \"subtotal\": 999, \"attempted\": true, \"discounts\": [], \"rendering\": null, \"amount_due\": 999, \"period_end\": 1778901651, \"test_clock\": null, \"amount_paid\": 999, \"application\": null, \"description\": null, \"invoice_pdf\": \"https://pay.stripe.com/invoice/acct_1Sd1WjEgxqQWB3tM/test_YWNjdF8xU2QxV2pFZ3hxUVdCM3RNLF9VV2MwYXVSWWFNSWdRdTFHVkJPUU9yNzRNWDFaOVo0LDE2OTcwMTY4OA0200IA8UMI8P/pdf?s=ap\", \"total_taxes\": [], \"account_name\": \"Billifty sandbox\", \"auto_advance\": false, \"effective_at\": 1779160882, \"from_invoice\": null, \"on_behalf_of\": null, \"period_start\": 1776309651, \"attempt_count\": 1, \"automatic_tax\": {\"status\": null, \"enabled\": false, \"provider\": null, \"liability\": null, \"disabled_reason\": null}, \"custom_fields\": null, \"customer_name\": \"Jo Koy\", \"shipping_cost\": null, \"billing_reason\": \"subscription_cycle\", \"customer_email\": \"jokoy@gmail.com\", \"customer_phone\": null, \"default_source\": null, \"ending_balance\": 0, \"receipt_number\": null, \"account_country\": \"US\", \"account_tax_ids\": null, \"amount_overpaid\": 0, \"amount_shipping\": 0, \"latest_revision\": null, \"amount_remaining\": 0, \"customer_account\": null, \"customer_address\": null, \"customer_tax_ids\": [], \"payment_settings\": {\"default_mandate\": null, \"payment_method_types\": null, \"payment_method_options\": {\"pix\": null, \"upi\": null, \"card\": {\"request_three_d_secure\": \"automatic\"}, \"payto\": null, \"konbini\": null, \"acss_debit\": null, \"bancontact\": null, \"sepa_debit\": null, \"us_bank_account\": null, \"customer_balance\": null}}, \"shipping_details\": null, \"starting_balance\": 0, \"collection_method\": \"charge_automatically\", \"customer_shipping\": null, \"default_tax_rates\": [], \"hosted_invoice_url\": \"https://invoice.stripe.com/i/acct_1Sd1WjEgxqQWB3tM/test_YWNjdF8xU2QxV2pFZ3hxUVdCM3RNLF9VV2MwYXVSWWFNSWdRdTFHVkJPUU9yNzRNWDFaOVo0LDE2OTcwMTY4OA0200IA8UMI8P?s=ap\", \"status_transitions\": {\"paid_at\": 1779160882, \"voided_at\": null, \"finalized_at\": 1779160882, \"marked_uncollectible_at\": null}, \"customer_tax_exempt\": \"none\", \"total_excluding_tax\": 999, \"next_payment_attempt\": null, \"statement_descriptor\": null, \"webhooks_delivered_at\": null, \"default_payment_method\": null, \"subtotal_excluding_tax\": 999, \"total_discount_amounts\": [], \"last_finalization_error\": null, \"automatically_finalizes_at\": null, \"total_pretax_credit_amounts\": [], \"pre_payment_credit_notes_amount\": 0, \"post_payment_credit_notes_amount\": 0}}, \"type\": \"invoice.paid\", \"object\": \"event\", \"created\": 1779160888, \"request\": {\"id\": null, \"idempotency_key\": null}, \"livemode\": false, \"api_version\": \"2025-11-17.clover\", \"pending_webhooks\": 3}','2026-05-19 03:21:29','2026-05-19 03:21:29','2026-05-19 03:21:29'),
(23,NULL,'cus_ULNDU4ML6NFZDn','in_1TXYmWEgxqQWB3tMQsclBu2M','evt_1TYeDlEgxqQWB3tMFoiPut9M','invoice.payment_succeeded','2025-11-17.clover','0','{\"id\": \"evt_1TYeDlEgxqQWB3tMFoiPut9M\", \"data\": {\"object\": {\"id\": \"in_1TXYmWEgxqQWB3tMQsclBu2M\", \"lines\": {\"url\": \"/v1/invoices/in_1TXYmWEgxqQWB3tMQsclBu2M/lines\", \"data\": [{\"id\": \"il_1TXYmVEgxqQWB3tM4vcZ4b2C\", \"taxes\": [], \"amount\": 999, \"object\": \"line_item\", \"parent\": {\"type\": \"subscription_item_details\", \"invoice_item_details\": null, \"subscription_item_details\": {\"proration\": false, \"invoice_item\": null, \"subscription\": \"sub_1TMgU5EgxqQWB3tMtubFNFwO\", \"proration_details\": {\"credited_items\": null}, \"subscription_item\": \"si_ULNEQ7q2wVGm5l\"}, \"license_fee_subscription_details\": null}, \"period\": {\"end\": 1781580051, \"start\": 1778901651}, \"invoice\": \"in_1TXYmWEgxqQWB3tMQsclBu2M\", \"pricing\": {\"type\": \"price_details\", \"price_details\": {\"price\": \"price_1Sd3SQEgxqQWB3tMwm9eDbPZ\", \"product\": \"prod_TaDuix6Cq3Omo9\"}, \"unit_amount_decimal\": \"999\"}, \"currency\": \"usd\", \"livemode\": false, \"metadata\": {\"plan_code\": \"premium\", \"billing_cycle\": \"monthly\", \"billifty_user_id\": \"7\"}, \"quantity\": 1, \"subtotal\": 999, \"discounts\": [], \"description\": \"1 × Premium – Monthly (at $9.99 / month)\", \"discountable\": true, \"discount_amounts\": [], \"quantity_decimal\": \"1\", \"pretax_credit_amounts\": []}], \"object\": \"list\", \"has_more\": false, \"total_count\": 1}, \"total\": 999, \"footer\": null, \"issuer\": {\"type\": \"self\"}, \"number\": \"WQ4RFXYV-0002\", \"object\": \"invoice\", \"parent\": {\"type\": \"subscription_details\", \"quote_details\": null, \"subscription_details\": {\"metadata\": {\"plan_code\": \"premium\", \"billing_cycle\": \"monthly\", \"billifty_user_id\": \"7\"}, \"subscription\": \"sub_1TMgU5EgxqQWB3tMtubFNFwO\"}}, \"status\": \"paid\", \"created\": 1778901652, \"currency\": \"usd\", \"customer\": \"cus_ULNDU4ML6NFZDn\", \"due_date\": null, \"livemode\": false, \"metadata\": [], \"subtotal\": 999, \"attempted\": true, \"discounts\": [], \"rendering\": null, \"amount_due\": 999, \"period_end\": 1778901651, \"test_clock\": null, \"amount_paid\": 999, \"application\": null, \"description\": null, \"invoice_pdf\": \"https://pay.stripe.com/invoice/acct_1Sd1WjEgxqQWB3tM/test_YWNjdF8xU2QxV2pFZ3hxUVdCM3RNLF9VV2MwYXVSWWFNSWdRdTFHVkJPUU9yNzRNWDFaOVo0LDE2OTcwMTY4OQ02001jpKw00n/pdf?s=ap\", \"total_taxes\": [], \"account_name\": \"Billifty sandbox\", \"auto_advance\": false, \"effective_at\": 1779160882, \"from_invoice\": null, \"on_behalf_of\": null, \"period_start\": 1776309651, \"attempt_count\": 1, \"automatic_tax\": {\"status\": null, \"enabled\": false, \"provider\": null, \"liability\": null, \"disabled_reason\": null}, \"custom_fields\": null, \"customer_name\": \"Jo Koy\", \"shipping_cost\": null, \"billing_reason\": \"subscription_cycle\", \"customer_email\": \"jokoy@gmail.com\", \"customer_phone\": null, \"default_source\": null, \"ending_balance\": 0, \"receipt_number\": null, \"account_country\": \"US\", \"account_tax_ids\": null, \"amount_overpaid\": 0, \"amount_shipping\": 0, \"latest_revision\": null, \"amount_remaining\": 0, \"customer_account\": null, \"customer_address\": null, \"customer_tax_ids\": [], \"payment_settings\": {\"default_mandate\": null, \"payment_method_types\": null, \"payment_method_options\": {\"pix\": null, \"upi\": null, \"card\": {\"request_three_d_secure\": \"automatic\"}, \"payto\": null, \"konbini\": null, \"acss_debit\": null, \"bancontact\": null, \"sepa_debit\": null, \"us_bank_account\": null, \"customer_balance\": null}}, \"shipping_details\": null, \"starting_balance\": 0, \"collection_method\": \"charge_automatically\", \"customer_shipping\": null, \"default_tax_rates\": [], \"hosted_invoice_url\": \"https://invoice.stripe.com/i/acct_1Sd1WjEgxqQWB3tM/test_YWNjdF8xU2QxV2pFZ3hxUVdCM3RNLF9VV2MwYXVSWWFNSWdRdTFHVkJPUU9yNzRNWDFaOVo0LDE2OTcwMTY4OQ02001jpKw00n?s=ap\", \"status_transitions\": {\"paid_at\": 1779160882, \"voided_at\": null, \"finalized_at\": 1779160882, \"marked_uncollectible_at\": null}, \"customer_tax_exempt\": \"none\", \"total_excluding_tax\": 999, \"next_payment_attempt\": null, \"statement_descriptor\": null, \"webhooks_delivered_at\": null, \"default_payment_method\": null, \"subtotal_excluding_tax\": 999, \"total_discount_amounts\": [], \"last_finalization_error\": null, \"automatically_finalizes_at\": null, \"total_pretax_credit_amounts\": [], \"pre_payment_credit_notes_amount\": 0, \"post_payment_credit_notes_amount\": 0}}, \"type\": \"invoice.payment_succeeded\", \"object\": \"event\", \"created\": 1779160888, \"request\": {\"id\": null, \"idempotency_key\": null}, \"livemode\": false, \"api_version\": \"2025-11-17.clover\", \"pending_webhooks\": 3}','2026-05-19 03:21:29','2026-05-19 03:21:29','2026-05-19 03:21:29'),
(24,NULL,NULL,'inpay_1TYeDgEgxqQWB3tMwXIax8w8','evt_1TYeEJEgxqQWB3tMfiDA6m8r','invoice_payment.paid','2025-11-17.clover','0','{\"id\": \"evt_1TYeEJEgxqQWB3tMfiDA6m8r\", \"data\": {\"object\": {\"id\": \"inpay_1TYeDgEgxqQWB3tMwXIax8w8\", \"object\": \"invoice_payment\", \"status\": \"paid\", \"created\": 1779160884, \"invoice\": \"in_1TXYmWEgxqQWB3tMQsclBu2M\", \"payment\": {\"type\": \"payment_intent\", \"payment_intent\": \"pi_3TYeDgEgxqQWB3tM0MDoQg9u\"}, \"currency\": \"usd\", \"livemode\": false, \"is_default\": true, \"amount_paid\": 999, \"amount_requested\": 999, \"status_transitions\": {\"paid_at\": 1779160886, \"canceled_at\": null}}}, \"type\": \"invoice_payment.paid\", \"object\": \"event\", \"created\": 1779160923, \"request\": {\"id\": null, \"idempotency_key\": null}, \"livemode\": false, \"api_version\": \"2025-11-17.clover\", \"pending_webhooks\": 3}','2026-05-19 03:22:03','2026-05-19 03:22:03','2026-05-19 03:22:03'),
(25,NULL,NULL,'ch_3TYwgkEgxqsR1VDL1Ka91dTQ','evt_3TYwgkEgxqsR1VDL1tNHWsis','charge.succeeded','2025-11-17.clover','0','{\"id\": \"evt_3TYwgkEgxqsR1VDL1tNHWsis\", \"data\": {\"object\": {\"id\": \"ch_3TYwgkEgxqsR1VDL1Ka91dTQ\", \"paid\": true, \"order\": null, \"amount\": 50000, \"object\": \"charge\", \"review\": null, \"source\": null, \"status\": \"succeeded\", \"created\": 1779231878, \"dispute\": null, \"outcome\": {\"type\": \"authorized\", \"reason\": null, \"risk_level\": \"normal\", \"risk_score\": 8, \"advice_code\": null, \"network_status\": \"approved_by_network\", \"seller_message\": \"Payment complete.\", \"network_advice_code\": null, \"network_decline_code\": null}, \"captured\": true, \"currency\": \"php\", \"customer\": null, \"disputed\": false, \"livemode\": false, \"metadata\": {\"invoice_id\": \"5\"}, \"refunded\": false, \"shipping\": null, \"application\": \"ca_UTwP7MaAWJB2eaIwcpl0rsJACxbuwyyQ\", \"description\": null, \"destination\": null, \"receipt_url\": \"https://pay.stripe.com/receipts/payment/CAcaFwoVYWNjdF8xVFV5WHJFZ3hxc1IxVkRMKIfZs9AGMgacM5Fm9AQ6LBam4EM_v86wUVeE95MdSC8a0TG5K6C1rwDoNb5L8AKft1aXbgxgA0xiak_f\", \"failure_code\": null, \"on_behalf_of\": null, \"fraud_details\": [], \"radar_options\": [], \"receipt_email\": null, \"transfer_data\": null, \"payment_intent\": \"pi_3TYwgkEgxqsR1VDL169R9kqT\", \"payment_method\": \"pm_1TYwgjEgxqsR1VDL8s3t1Rvr\", \"receipt_number\": null, \"transfer_group\": null, \"amount_captured\": 50000, \"amount_refunded\": 0, \"application_fee\": null, \"billing_details\": {\"name\": \"John G.\", \"email\": \"store@ripplesbyjenny.com\", \"phone\": null, \"tax_id\": null, \"address\": {\"city\": null, \"line1\": null, \"line2\": null, \"state\": null, \"country\": \"US\", \"postal_code\": \"12342\"}}, \"failure_message\": null, \"source_transfer\": null, \"balance_transaction\": null, \"statement_descriptor\": null, \"application_fee_amount\": null, \"payment_method_details\": {\"card\": {\"brand\": \"mastercard\", \"last4\": \"8210\", \"checks\": {\"cvc_check\": \"pass\", \"address_line1_check\": null, \"address_postal_code_check\": \"pass\"}, \"wallet\": null, \"country\": \"US\", \"funding\": \"debit\", \"mandate\": null, \"network\": \"mastercard\", \"exp_year\": 2043, \"exp_month\": 3, \"fingerprint\": \"AAIOt0JS40tbv1yt\", \"overcapture\": {\"status\": \"unavailable\", \"maximum_amount_capturable\": 50000}, \"installments\": null, \"multicapture\": {\"status\": \"unavailable\"}, \"network_token\": {\"used\": false}, \"three_d_secure\": null, \"regulated_status\": \"unregulated\", \"amount_authorized\": 50000, \"ds_transaction_id\": null, \"authorization_code\": \"561907\", \"extended_authorization\": {\"status\": \"disabled\"}, \"network_transaction_id\": \"MCCIJLOLU0519\", \"incremental_authorization\": {\"status\": \"unavailable\"}}, \"type\": \"card\"}, \"failure_balance_transaction\": null, \"statement_descriptor_suffix\": null, \"calculated_statement_descriptor\": \"TEST ACCOUNT\"}}, \"type\": \"charge.succeeded\", \"object\": \"event\", \"account\": \"acct_1TUyXrEgxqsR1VDL\", \"context\": \"acct_1TUyXrEgxqsR1VDL\", \"created\": 1779231879, \"request\": {\"id\": null, \"idempotency_key\": \"f5d851de-3a3c-4a93-84a5-75b3c6f05291\"}, \"livemode\": false, \"api_version\": \"2025-11-17.clover\", \"pending_webhooks\": 2}','2026-05-19 23:04:39','2026-05-19 23:04:39','2026-05-19 23:04:39'),
(26,NULL,NULL,'pi_3TYwgkEgxqsR1VDL169R9kqT','evt_3TYwgkEgxqsR1VDL1D3mkzmL','payment_intent.succeeded','2025-11-17.clover','0','{\"id\": \"evt_3TYwgkEgxqsR1VDL1D3mkzmL\", \"data\": {\"object\": {\"id\": \"pi_3TYwgkEgxqsR1VDL169R9kqT\", \"amount\": 50000, \"object\": \"payment_intent\", \"review\": null, \"source\": null, \"status\": \"succeeded\", \"created\": 1779231878, \"currency\": \"php\", \"customer\": null, \"livemode\": false, \"metadata\": {\"invoice_id\": \"5\"}, \"shipping\": null, \"processing\": null, \"application\": \"ca_UTwP7MaAWJB2eaIwcpl0rsJACxbuwyyQ\", \"canceled_at\": null, \"description\": null, \"next_action\": null, \"on_behalf_of\": null, \"client_secret\": \"pi_3TYwgkEgxqsR1VDL169R9kqT_secret_UAwZAPX5aDTqRS7JdxBgzRLLd\", \"latest_charge\": \"ch_3TYwgkEgxqsR1VDL1Ka91dTQ\", \"receipt_email\": null, \"transfer_data\": null, \"amount_details\": {\"tax\": {\"total_tax_amount\": 0}, \"tip\": [], \"shipping\": {\"amount\": 0, \"to_postal_code\": null, \"from_postal_code\": null}}, \"capture_method\": \"automatic_async\", \"payment_method\": \"pm_1TYwgjEgxqsR1VDL8s3t1Rvr\", \"transfer_group\": null, \"amount_received\": 50000, \"payment_details\": {\"order_reference\": \"cs_test_a1e122zRjGg2hXJn9FtY5oXqwHvqqtSoEWWSe9bPJJOFXlw5bELNWaocCl\", \"customer_reference\": null}, \"customer_account\": null, \"managed_payments\": {\"enabled\": false}, \"amount_capturable\": 0, \"last_payment_error\": null, \"setup_future_usage\": null, \"cancellation_reason\": null, \"confirmation_method\": \"automatic\", \"payment_method_types\": [\"card\"], \"statement_descriptor\": null, \"application_fee_amount\": null, \"payment_method_options\": {\"card\": {\"network\": null, \"installments\": null, \"mandate_options\": null, \"request_three_d_secure\": \"automatic\"}}, \"automatic_payment_methods\": null, \"statement_descriptor_suffix\": null, \"shared_payment_granted_token\": null, \"excluded_payment_method_types\": null, \"payment_method_configuration_details\": null}}, \"type\": \"payment_intent.succeeded\", \"object\": \"event\", \"account\": \"acct_1TUyXrEgxqsR1VDL\", \"context\": \"acct_1TUyXrEgxqsR1VDL\", \"created\": 1779231879, \"request\": {\"id\": null, \"idempotency_key\": \"f5d851de-3a3c-4a93-84a5-75b3c6f05291\"}, \"livemode\": false, \"api_version\": \"2025-11-17.clover\", \"pending_webhooks\": 2}','2026-05-19 23:04:39','2026-05-19 23:04:39','2026-05-19 23:04:39'),
(27,NULL,NULL,'cs_test_a1e122zRjGg2hXJn9FtY5oXqwHvqqtSoEWWSe9bPJJOFXlw5bELNWaocCl','evt_1TYwglEgxqsR1VDLTwsh2YbL','checkout.session.completed','2025-11-17.clover','0','{\"id\": \"evt_1TYwglEgxqsR1VDLTwsh2YbL\", \"data\": {\"object\": {\"id\": \"cs_test_a1e122zRjGg2hXJn9FtY5oXqwHvqqtSoEWWSe9bPJJOFXlw5bELNWaocCl\", \"url\": null, \"mode\": \"payment\", \"locale\": null, \"object\": \"checkout.session\", \"status\": \"complete\", \"consent\": null, \"created\": 1779231833, \"invoice\": null, \"ui_mode\": \"hosted\", \"currency\": \"php\", \"customer\": null, \"livemode\": false, \"metadata\": {\"invoice_id\": \"5\"}, \"discounts\": [], \"cancel_url\": \"https://int.billifty.com/app/invoices/pay_gnn_01KS17HGA02XSKD2DBBS0ZW6TN/payment-cancelled\", \"expires_at\": 1779318232, \"custom_text\": {\"submit\": null, \"after_submit\": null, \"shipping_address\": null, \"terms_of_service_acceptance\": null}, \"permissions\": null, \"submit_type\": null, \"success_url\": \"https://int.billifty.com/app/invoices/pay_gnn_01KS17HGA02XSKD2DBBS0ZW6TN/payment-success\", \"amount_total\": 50000, \"payment_link\": null, \"setup_intent\": null, \"subscription\": null, \"automatic_tax\": {\"status\": null, \"enabled\": false, \"provider\": null, \"liability\": null}, \"client_secret\": null, \"custom_fields\": [], \"shipping_cost\": null, \"total_details\": {\"amount_tax\": 0, \"amount_discount\": 0, \"amount_shipping\": 0}, \"customer_email\": \"store@ripplesbyjenny.com\", \"origin_context\": null, \"payment_intent\": \"pi_3TYwgkEgxqsR1VDL169R9kqT\", \"payment_status\": \"paid\", \"recovered_from\": null, \"wallet_options\": null, \"amount_subtotal\": 50000, \"adaptive_pricing\": {\"enabled\": true}, \"after_expiration\": null, \"customer_account\": null, \"customer_details\": {\"name\": \"John G.\", \"email\": \"store@ripplesbyjenny.com\", \"phone\": null, \"address\": {\"city\": null, \"line1\": null, \"line2\": null, \"state\": null, \"country\": \"US\", \"postal_code\": \"12342\"}, \"tax_ids\": [], \"tax_exempt\": \"none\", \"business_name\": null, \"individual_name\": null}, \"invoice_creation\": {\"enabled\": false, \"invoice_data\": {\"footer\": null, \"issuer\": null, \"metadata\": [], \"description\": null, \"custom_fields\": null, \"account_tax_ids\": null, \"rendering_options\": null}}, \"managed_payments\": {\"enabled\": false}, \"shipping_options\": [], \"branding_settings\": {\"icon\": null, \"logo\": null, \"font_family\": \"default\", \"border_style\": \"rounded\", \"button_color\": \"#0074d4\", \"display_name\": \"Test account\", \"background_color\": \"#ffffff\"}, \"customer_creation\": \"if_required\", \"consent_collection\": null, \"client_reference_id\": null, \"currency_conversion\": null, \"payment_method_types\": [\"card\", \"link\"], \"allow_promotion_codes\": null, \"collected_information\": {\"business_name\": null, \"individual_name\": null, \"shipping_details\": null}, \"integration_identifier\": null, \"payment_method_options\": {\"card\": {\"request_three_d_secure\": \"automatic\"}}, \"phone_number_collection\": {\"enabled\": false}, \"payment_method_collection\": \"if_required\", \"billing_address_collection\": null, \"shipping_address_collection\": null, \"saved_payment_method_options\": null, \"payment_method_configuration_details\": {\"id\": \"pmc_1TUyYOEgxqsR1VDLpTECBPfq\", \"parent\": \"pmc_1TUyY4EgxqQWB3tMI0wGs2o7\"}}}, \"type\": \"checkout.session.completed\", \"object\": \"event\", \"account\": \"acct_1TUyXrEgxqsR1VDL\", \"context\": \"acct_1TUyXrEgxqsR1VDL\", \"created\": 1779231879, \"request\": {\"id\": null, \"idempotency_key\": null}, \"livemode\": false, \"api_version\": \"2025-11-17.clover\", \"pending_webhooks\": 2}','2026-05-19 23:04:39','2026-05-19 23:04:39','2026-05-19 23:04:39'),
(28,NULL,NULL,'pi_3TYwgkEgxqsR1VDL169R9kqT','evt_3TYwgkEgxqsR1VDL1Tffz31E','payment_intent.created','2025-11-17.clover','0','{\"id\": \"evt_3TYwgkEgxqsR1VDL1Tffz31E\", \"data\": {\"object\": {\"id\": \"pi_3TYwgkEgxqsR1VDL169R9kqT\", \"amount\": 50000, \"object\": \"payment_intent\", \"review\": null, \"source\": null, \"status\": \"requires_payment_method\", \"created\": 1779231878, \"currency\": \"php\", \"customer\": null, \"livemode\": false, \"metadata\": {\"invoice_id\": \"5\"}, \"shipping\": null, \"processing\": null, \"application\": \"ca_UTwP7MaAWJB2eaIwcpl0rsJACxbuwyyQ\", \"canceled_at\": null, \"description\": null, \"next_action\": null, \"on_behalf_of\": null, \"client_secret\": \"pi_3TYwgkEgxqsR1VDL169R9kqT_secret_UAwZAPX5aDTqRS7JdxBgzRLLd\", \"latest_charge\": null, \"receipt_email\": null, \"transfer_data\": null, \"amount_details\": {\"tip\": []}, \"capture_method\": \"automatic_async\", \"payment_method\": null, \"transfer_group\": null, \"amount_received\": 0, \"customer_account\": null, \"managed_payments\": {\"enabled\": false}, \"amount_capturable\": 0, \"last_payment_error\": null, \"setup_future_usage\": null, \"cancellation_reason\": null, \"confirmation_method\": \"automatic\", \"payment_method_types\": [\"card\"], \"statement_descriptor\": null, \"application_fee_amount\": null, \"payment_method_options\": {\"card\": {\"network\": null, \"installments\": null, \"mandate_options\": null, \"request_three_d_secure\": \"automatic\"}}, \"automatic_payment_methods\": null, \"statement_descriptor_suffix\": null, \"shared_payment_granted_token\": null, \"excluded_payment_method_types\": null, \"payment_method_configuration_details\": null}}, \"type\": \"payment_intent.created\", \"object\": \"event\", \"account\": \"acct_1TUyXrEgxqsR1VDL\", \"context\": \"acct_1TUyXrEgxqsR1VDL\", \"created\": 1779231878, \"request\": {\"id\": null, \"idempotency_key\": \"f5d851de-3a3c-4a93-84a5-75b3c6f05291\"}, \"livemode\": false, \"api_version\": \"2025-11-17.clover\", \"pending_webhooks\": 2}','2026-05-19 23:04:39','2026-05-19 23:04:39','2026-05-19 23:04:39'),
(29,NULL,NULL,'ch_3TYwgkEgxqsR1VDL1Ka91dTQ','evt_3TYwgkEgxqsR1VDL14tHzRgE','charge.updated','2025-11-17.clover','0','{\"id\": \"evt_3TYwgkEgxqsR1VDL14tHzRgE\", \"data\": {\"object\": {\"id\": \"ch_3TYwgkEgxqsR1VDL1Ka91dTQ\", \"paid\": true, \"order\": null, \"amount\": 50000, \"object\": \"charge\", \"review\": null, \"source\": null, \"status\": \"succeeded\", \"created\": 1779231878, \"dispute\": null, \"outcome\": {\"type\": \"authorized\", \"reason\": null, \"risk_level\": \"normal\", \"risk_score\": 8, \"advice_code\": null, \"network_status\": \"approved_by_network\", \"seller_message\": \"Payment complete.\", \"network_advice_code\": null, \"network_decline_code\": null}, \"captured\": true, \"currency\": \"php\", \"customer\": null, \"disputed\": false, \"livemode\": false, \"metadata\": {\"invoice_id\": \"5\"}, \"refunded\": false, \"shipping\": null, \"application\": \"ca_UTwP7MaAWJB2eaIwcpl0rsJACxbuwyyQ\", \"description\": null, \"destination\": null, \"receipt_url\": \"https://pay.stripe.com/receipts/payment/CAcaFwoVYWNjdF8xVFV5WHJFZ3hxc1IxVkRMKIrZs9AGMgZiar2AEpY6LBa9EeaS4ohEfQNHaaSsrXkgzFCrE0AVaCCidzKJeVgb9ma4GjCA3psUV3O4\", \"failure_code\": null, \"on_behalf_of\": null, \"fraud_details\": [], \"radar_options\": [], \"receipt_email\": null, \"transfer_data\": null, \"payment_intent\": \"pi_3TYwgkEgxqsR1VDL169R9kqT\", \"payment_method\": \"pm_1TYwgjEgxqsR1VDL8s3t1Rvr\", \"receipt_number\": null, \"transfer_group\": null, \"amount_captured\": 50000, \"amount_refunded\": 0, \"application_fee\": null, \"billing_details\": {\"name\": \"John G.\", \"email\": \"store@ripplesbyjenny.com\", \"phone\": null, \"tax_id\": null, \"address\": {\"city\": null, \"line1\": null, \"line2\": null, \"state\": null, \"country\": \"US\", \"postal_code\": \"12342\"}}, \"failure_message\": null, \"source_transfer\": null, \"balance_transaction\": \"txn_3TYwgkEgxqsR1VDL1RWK1Yxb\", \"statement_descriptor\": null, \"application_fee_amount\": null, \"payment_method_details\": {\"card\": {\"brand\": \"mastercard\", \"last4\": \"8210\", \"checks\": {\"cvc_check\": \"pass\", \"address_line1_check\": null, \"address_postal_code_check\": \"pass\"}, \"wallet\": null, \"country\": \"US\", \"funding\": \"debit\", \"mandate\": null, \"network\": \"mastercard\", \"exp_year\": 2043, \"exp_month\": 3, \"fingerprint\": \"AAIOt0JS40tbv1yt\", \"overcapture\": {\"status\": \"unavailable\", \"maximum_amount_capturable\": 50000}, \"installments\": null, \"multicapture\": {\"status\": \"unavailable\"}, \"network_token\": {\"used\": false}, \"three_d_secure\": null, \"regulated_status\": \"unregulated\", \"amount_authorized\": 50000, \"ds_transaction_id\": null, \"authorization_code\": \"561907\", \"extended_authorization\": {\"status\": \"disabled\"}, \"network_transaction_id\": \"MCCIJLOLU0519\", \"incremental_authorization\": {\"status\": \"unavailable\"}}, \"type\": \"card\"}, \"failure_balance_transaction\": null, \"statement_descriptor_suffix\": null, \"calculated_statement_descriptor\": \"TEST ACCOUNT\"}, \"previous_attributes\": {\"receipt_url\": \"https://pay.stripe.com/receipts/payment/CAcaFwoVYWNjdF8xVFV5WHJFZ3hxc1IxVkRMKIrZs9AGMgYejsWIN6M6LBZfpa-87ihOryqzQVGCNq5twU7UucYma8ZUl8kUBcecphUeSGcNgk9K-lLc\", \"balance_transaction\": null}}, \"type\": \"charge.updated\", \"object\": \"event\", \"account\": \"acct_1TUyXrEgxqsR1VDL\", \"context\": \"acct_1TUyXrEgxqsR1VDL\", \"created\": 1779231882, \"request\": {\"id\": null, \"idempotency_key\": null}, \"livemode\": false, \"api_version\": \"2025-11-17.clover\", \"pending_webhooks\": 2}','2026-05-19 23:04:42','2026-05-19 23:04:42','2026-05-19 23:04:42');
/*!40000 ALTER TABLE `stripe_webhook_events` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `user_subscriptions`
--

DROP TABLE IF EXISTS `user_subscriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_subscriptions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `plan_id` bigint unsigned NOT NULL,
  `plan_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `billing_cycle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stripe_customer_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stripe_subscription_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'usd',
  `unit_amount` int DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `starts_at` timestamp NULL DEFAULT NULL,
  `renews_at` timestamp NULL DEFAULT NULL,
  `cancels_at` timestamp NULL DEFAULT NULL,
  `canceled_at` timestamp NULL DEFAULT NULL,
  `raw_payload` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_subscriptions_stripe_subscription_id_unique` (`stripe_subscription_id`),
  KEY `user_subscriptions_plan_id_foreign` (`plan_id`),
  KEY `user_subscriptions_user_id_status_index` (`user_id`,`status`),
  CONSTRAINT `user_subscriptions_plan_id_foreign` FOREIGN KEY (`plan_id`) REFERENCES `plans` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_subscriptions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_subscriptions`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `user_subscriptions` WRITE;
/*!40000 ALTER TABLE `user_subscriptions` DISABLE KEYS */;
INSERT INTO `user_subscriptions` VALUES
(1,1,1,'free','',NULL,NULL,'usd',NULL,'active',NULL,NULL,NULL,NULL,NULL,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(2,2,2,'pro','',NULL,NULL,'usd',NULL,'active',NULL,NULL,NULL,NULL,NULL,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(3,3,3,'premium','',NULL,NULL,'usd',NULL,'active',NULL,NULL,NULL,NULL,NULL,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(4,4,3,'premium','',NULL,NULL,'usd',NULL,'active',NULL,NULL,NULL,NULL,NULL,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(5,6,3,'premium','monthly','cus_UXgQzt0kW6AD7W','sub_1TYb4jEgxqQWB3tMEaVaWy1i','usd',999,'active',NULL,NULL,NULL,NULL,'{\"id\": \"sub_1TYb4jEgxqQWB3tMEaVaWy1i\", \"plan\": {\"id\": \"price_1Sd3SQEgxqQWB3tMwm9eDbPZ\", \"meter\": null, \"active\": true, \"amount\": 999, \"object\": \"plan\", \"created\": 1765434874, \"product\": \"prod_TaDuix6Cq3Omo9\", \"currency\": \"usd\", \"interval\": \"month\", \"livemode\": false, \"metadata\": [], \"nickname\": null, \"tiers_mode\": null, \"usage_type\": \"licensed\", \"amount_decimal\": \"999\", \"billing_scheme\": \"per_unit\", \"interval_count\": 1, \"transform_usage\": null, \"trial_period_days\": null}, \"items\": {\"url\": \"/v1/subscription_items?subscription=sub_1TYb4jEgxqQWB3tMEaVaWy1i\", \"data\": [{\"id\": \"si_UXgRBUh17mxhjR\", \"plan\": {\"id\": \"price_1Sd3SQEgxqQWB3tMwm9eDbPZ\", \"meter\": null, \"active\": true, \"amount\": 999, \"object\": \"plan\", \"created\": 1765434874, \"product\": \"prod_TaDuix6Cq3Omo9\", \"currency\": \"usd\", \"interval\": \"month\", \"livemode\": false, \"metadata\": [], \"nickname\": null, \"tiers_mode\": null, \"usage_type\": \"licensed\", \"amount_decimal\": \"999\", \"billing_scheme\": \"per_unit\", \"interval_count\": 1, \"transform_usage\": null, \"trial_period_days\": null}, \"price\": {\"id\": \"price_1Sd3SQEgxqQWB3tMwm9eDbPZ\", \"type\": \"recurring\", \"active\": true, \"object\": \"price\", \"created\": 1765434874, \"product\": \"prod_TaDuix6Cq3Omo9\", \"currency\": \"usd\", \"livemode\": false, \"metadata\": [], \"nickname\": null, \"recurring\": {\"meter\": null, \"interval\": \"month\", \"usage_type\": \"licensed\", \"interval_count\": 1, \"trial_period_days\": null}, \"lookup_key\": null, \"tiers_mode\": null, \"unit_amount\": 999, \"tax_behavior\": \"unspecified\", \"billing_scheme\": \"per_unit\", \"custom_unit_amount\": null, \"transform_quantity\": null, \"unit_amount_decimal\": \"999\"}, \"object\": \"subscription_item\", \"created\": 1779148795, \"metadata\": [], \"quantity\": 1, \"discounts\": [], \"tax_rates\": [], \"subscription\": \"sub_1TYb4jEgxqQWB3tMEaVaWy1i\", \"billing_thresholds\": null, \"current_period_end\": 1781827195, \"current_period_start\": 1779148795}], \"object\": \"list\", \"has_more\": false, \"total_count\": 1}, \"object\": \"subscription\", \"status\": \"active\", \"created\": 1779148795, \"currency\": \"usd\", \"customer\": \"cus_UXgQzt0kW6AD7W\", \"ended_at\": null, \"livemode\": false, \"metadata\": {\"plan_code\": \"premium\", \"billing_cycle\": \"monthly\", \"billifty_user_id\": \"6\"}, \"quantity\": 1, \"schedule\": null, \"cancel_at\": null, \"discounts\": [], \"trial_end\": null, \"start_date\": 1779148795, \"test_clock\": null, \"application\": null, \"canceled_at\": null, \"description\": null, \"trial_start\": null, \"billing_mode\": {\"type\": \"flexible\", \"flexible\": {\"proration_discounts\": \"included\"}, \"updated_at\": 1779148752}, \"on_behalf_of\": null, \"automatic_tax\": {\"enabled\": false, \"liability\": null, \"disabled_reason\": null}, \"transfer_data\": null, \"days_until_due\": null, \"default_source\": null, \"latest_invoice\": \"in_1TYb4hEgxqQWB3tMBiRh0BMD\", \"pending_update\": null, \"trial_settings\": {\"end_behavior\": {\"missing_payment_method\": \"create_invoice\"}}, \"customer_account\": null, \"invoice_settings\": {\"issuer\": {\"type\": \"self\"}, \"account_tax_ids\": null}, \"managed_payments\": {\"enabled\": false}, \"pause_collection\": null, \"payment_settings\": {\"payment_method_types\": null, \"payment_method_options\": {\"pix\": null, \"upi\": null, \"card\": {\"network\": null, \"request_three_d_secure\": \"automatic\"}, \"payto\": null, \"konbini\": null, \"acss_debit\": null, \"bancontact\": null, \"sepa_debit\": null, \"us_bank_account\": null, \"customer_balance\": null}, \"save_default_payment_method\": \"off\"}, \"collection_method\": \"charge_automatically\", \"default_tax_rates\": [], \"billing_thresholds\": null, \"billing_cycle_anchor\": 1779148795, \"cancel_at_period_end\": false, \"cancellation_details\": {\"reason\": null, \"comment\": null, \"feedback\": null}, \"pending_setup_intent\": null, \"default_payment_method\": \"pm_1TYb4gEgxqQWB3tMlh05bEH9\", \"application_fee_percent\": null, \"billing_cycle_anchor_config\": null, \"pending_invoice_item_interval\": null, \"next_pending_invoice_item_invoice\": null}','2026-05-18 23:59:59','2026-05-19 00:00:13');
/*!40000 ALTER TABLE `user_subscriptions` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `user_template_settings`
--

DROP TABLE IF EXISTS `user_template_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_template_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `business_profile_id` bigint unsigned DEFAULT NULL,
  `default_template_slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `default_template_version` int unsigned DEFAULT NULL,
  `default_theme_json` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_template_settings_business_profile_id_foreign` (`business_profile_id`),
  KEY `user_template_settings_user_id_foreign` (`user_id`),
  KEY `user_template_settings_default_template_slug_index` (`default_template_slug`),
  CONSTRAINT `user_template_settings_business_profile_id_foreign` FOREIGN KEY (`business_profile_id`) REFERENCES `business_profiles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_template_settings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_template_settings`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `user_template_settings` WRITE;
/*!40000 ALTER TABLE `user_template_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_template_settings` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `plan_id` bigint unsigned DEFAULT '1',
  `fname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `provider` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stripe_customer_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` text COLLATE utf8mb4_unicode_ci,
  `is_test` tinyint NOT NULL DEFAULT '0',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_plan_id_foreign` (`plan_id`),
  KEY `users_provider_id_index` (`provider_id`),
  KEY `users_stripe_customer_id_index` (`stripe_customer_id`),
  CONSTRAINT `users_plan_id_foreign` FOREIGN KEY (`plan_id`) REFERENCES `plans` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(1,1,NULL,NULL,'John Paine','john+free@billifty.czom',NULL,'$2y$12$hVhMw/emJ3Pka18VkiDs3.CHjCcbfNK/zSUnEF6XAOrOxthC.3.MC',NULL,NULL,NULL,NULL,0,NULL,NULL,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(2,2,NULL,NULL,'Kirk McDonald','kirk+pro@billifty.com',NULL,'$2y$12$4ecM9KjGjvbFNiZBHoDuj.o6DTBHV99.1LKFJpPjPfhfIOQtGDI0y',NULL,NULL,NULL,NULL,0,NULL,NULL,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(3,3,NULL,NULL,'James Harris','james+premium@billifty.com',NULL,'$2y$12$FSCR/uG2p/Q0VVXz7RRS7.khni6bDOSOZKRb1u5sy//lc9mbA966.',NULL,NULL,NULL,NULL,0,NULL,NULL,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(4,3,NULL,NULL,'Ed Bedia','fordbedia@billifty.com',NULL,'$2y$12$UNdlDECz9i97HyVc4K46MOaXfgE9rxygjAvpYT1jGupoNDNXBhjQq',NULL,NULL,NULL,NULL,0,NULL,NULL,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(6,3,'Alexander','Pierce','Alexander Pierce','alexanderpierce+test1@gmail.com',NULL,'$2y$12$A7tvjphmTH7VDiVQERW33OqFfrJshDTS7wHebTJ7FwGiRd3lThGx.',NULL,NULL,'cus_UXgQzt0kW6AD7W',NULL,0,'iIZOA2EdwQUipmBiFhlrRu47zbt8yChwswMEbMm7NmlTyUXkLitymwqwb5y1',NULL,'2026-05-18 23:59:09','2026-05-18 23:59:59');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `workspace`
--

DROP TABLE IF EXISTS `workspace`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `workspace` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint NOT NULL DEFAULT '1',
  `is_default` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `workspace_user_id_foreign` (`user_id`),
  CONSTRAINT `workspace_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `workspace`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `workspace` WRITE;
/*!40000 ALTER TABLE `workspace` DISABLE KEYS */;
INSERT INTO `workspace` VALUES
(1,1,'default',1,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(2,2,'default',1,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(3,3,'default',1,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(4,4,'default',1,1,'2026-05-18 23:51:37','2026-05-18 23:51:37'),
(6,6,'default',1,1,'2026-05-18 23:59:09','2026-05-18 23:59:09');
/*!40000 ALTER TABLE `workspace` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Dumping events for database 'app_db'
--

--
-- Dumping routines for database 'app_db'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2026-05-20 15:59:11
