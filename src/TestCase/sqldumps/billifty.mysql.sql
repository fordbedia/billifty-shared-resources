-- MySQL dump 10.13  Distrib 9.6.0, for Linux (aarch64)
--
-- Host: localhost    Database: app_db
-- ------------------------------------------------------
-- Server version	9.6.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
SET @MYSQLDUMP_TEMP_LOG_BIN = @@SESSION.SQL_LOG_BIN;
SET @@SESSION.SQL_LOG_BIN= 0;

--
-- GTID state at the beginning of the backup 
--

SET @@GLOBAL.GTID_PURGED=/*!80000 '+'*/ 'a78bc077-f348-11f0-bb14-0a44ebe37432:1-3839';

--
-- Table structure for table `business_profiles`
--

DROP TABLE IF EXISTS `business_profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `business_profiles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `payment_information_id` bigint unsigned DEFAULT NULL,
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
  KEY `business_profiles_payment_information_id_foreign` (`payment_information_id`),
  CONSTRAINT `business_profiles_payment_information_id_foreign` FOREIGN KEY (`payment_information_id`) REFERENCES `payment_information` (`id`) ON DELETE CASCADE,
  CONSTRAINT `business_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `business_profiles`
--

LOCK TABLES `business_profiles` WRITE;
/*!40000 ALTER TABLE `business_profiles` DISABLE KEYS */;
INSERT INTO `business_profiles` VALUES (1,4,1,'Test Company LLC','Test Company LLC','test_company_llc@gmail.com','87365245111','','','','129 Bernham street',NULL,'Houston','TX','1222','US','public',NULL,NULL,0,'2026-04-25 05:35:35','2026-04-25 05:35:35',NULL),(2,4,1,'ILLCity Clothing LLC','ILLCity Clothing LLC','illCityClothing@gmail.com','87365245311','','','','7099 Blair Stone Rd',NULL,'Tallahasse','FL','32301','US','public',NULL,NULL,0,'2026-04-25 05:35:35','2026-04-25 05:35:35',NULL),(3,8,2,'Yukon Enterprise Inc.','Yukon Enterprise Inc.','yukonenterprise@yei.com',NULL,NULL,'121234234',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'public',NULL,NULL,0,'2026-04-26 17:57:51','2026-04-26 17:58:17','2026-04-26 17:58:17'),(4,8,3,'Yukon Enterprise Inc.','Yukon Enterprise Inc.','yukonentrprise@yiep.com',NULL,NULL,'2e3234',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'public',NULL,NULL,0,'2026-04-26 17:58:52','2026-04-26 17:58:52',NULL),(5,16,4,'Jamish LLC','Jamish LLC','jj@jamesh@gmail.com',NULL,NULL,'12342345',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'public',NULL,NULL,0,'2026-04-26 21:33:07','2026-04-26 21:33:07',NULL),(6,16,5,'Stat Asia LTD','Stat Asia LTD','stat@statasioa.com',NULL,NULL,'12342345',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'public',NULL,NULL,0,'2026-04-26 21:34:10','2026-04-26 21:34:10',NULL);
/*!40000 ALTER TABLE `business_profiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('billifty-cache-0ade7c2cf97f75d009975f4d720d1fa6c19f4897','i:1;',1777232410),('billifty-cache-0ade7c2cf97f75d009975f4d720d1fa6c19f4897:timer','i:1777232410;',1777232410),('billifty-cache-472b07b9fcf2c2451e8781e944bf5f77cd8457c8','i:2;',1777251089),('billifty-cache-472b07b9fcf2c2451e8781e944bf5f77cd8457c8:timer','i:1777251089;',1777251089),('billifty-cache-fe5dbbcea5ce7e2988b8c69bcfdfde8904aabc1f','i:1;',1777229100),('billifty-cache-fe5dbbcea5ce7e2988b8c69bcfdfde8904aabc1f:timer','i:1777229100;',1777229100);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clients`
--

LOCK TABLES `clients` WRITE;
/*!40000 ALTER TABLE `clients` DISABLE KEYS */;
INSERT INTO `clients` VALUES (1,4,'John Doe','EvaSoft LLC','johndoe@gmail.com','9876381234','','','7900 S Post Oak',NULL,'Houston','TX','77890','US',NULL,1,'2026-04-25 05:35:35','2026-04-25 05:35:35',NULL),(2,4,'Harry Doe','Wee LLC','harry@gmail.com','9876316234','','','1922 Pleasant Groove Rd',NULL,'Houston','TX','77840','US',NULL,1,'2026-04-25 05:35:35','2026-04-25 05:35:35',NULL),(3,8,'Artesa LLC','Artesa LLC','me@artrsa.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,'2026-04-26 17:59:26','2026-04-26 17:59:26',NULL),(4,16,'Stat Com LLC','Stat Com LLC','me@statcom.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,'2026-04-26 21:33:43','2026-04-26 21:33:43',NULL);
/*!40000 ALTER TABLE `clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `color_scheme`
--

DROP TABLE IF EXISTS `color_scheme`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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

LOCK TABLES `color_scheme` WRITE;
/*!40000 ALTER TABLE `color_scheme` DISABLE KEYS */;
INSERT INTO `color_scheme` VALUES (1,'Ocean Blue','ocean',NULL,'/images/invoice-selection/ocean-blue.png','2026-04-25 05:35:35','2026-04-25 05:35:35'),(2,'Forest Green','forest',NULL,'/images/invoice-selection/forest-green.png','2026-04-25 05:35:35','2026-04-25 05:35:35'),(3,'Royal Purple','royal',NULL,'/images/invoice-selection/royal-purple.png','2026-04-25 05:35:35','2026-04-25 05:35:35'),(4,'Crimson Red','crimson',NULL,'/images/invoice-selection/crimson-red.png','2026-04-25 05:35:35','2026-04-25 05:35:35'),(5,'Sunset Orange','sunset',NULL,'/images/invoice-selection/sunset-orange.png','2026-04-25 05:35:35','2026-04-25 05:35:35');
/*!40000 ALTER TABLE `color_scheme` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `color_scheme_color`
--

DROP TABLE IF EXISTS `color_scheme_color`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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

LOCK TABLES `color_scheme_color` WRITE;
/*!40000 ALTER TABLE `color_scheme_color` DISABLE KEYS */;
INSERT INTO `color_scheme_color` VALUES (1,3,'main','#8B5CF6','2026-04-25 05:35:35','2026-04-25 05:35:35'),(2,3,'light','#D8B4FE','2026-04-25 05:35:35','2026-04-25 05:35:35'),(3,3,'extra_light','rgba(253, 242, 248, 0.3)','2026-04-25 05:35:35','2026-04-25 05:35:35'),(4,3,'gradient_bg_1','90deg,rgba(147, 51, 234, 1) 0%, rgba(168, 85, 247, 0.67) 55%, rgba(236, 72, 153, 1) 100%','2026-04-25 05:35:35','2026-04-25 05:35:35'),(5,3,'table_tbody_color','#FDF2F8','2026-04-25 05:35:35','2026-04-25 05:35:35'),(6,3,'gradient_bg_1_light','142deg, rgba(255, 255, 255, 1) 0%, rgba(238, 242, 255, 1) 100%','2026-04-25 05:35:35','2026-04-25 05:35:35'),(7,1,'main','#3B82F6','2026-04-25 05:35:35','2026-04-25 05:35:35'),(8,1,'light','#93C5FD','2026-04-25 05:35:35','2026-04-25 05:35:35'),(9,1,'extra_light','rgba(255, 255, 255, 0.3)','2026-04-25 05:35:35','2026-04-25 05:35:35'),(10,1,'gradient_bg_1','90deg,#020024 0%, #090979 35%, #00D4FF 100%','2026-04-25 05:35:35','2026-04-25 05:35:35'),(11,1,'table_tbody_color','','2026-04-25 05:35:35','2026-04-25 05:35:35'),(12,1,'gradient_bg_1_light','142deg, rgba(255, 255, 255, 1) 0%, rgba(238, 242, 255, 1) 100%','2026-04-25 05:35:35','2026-04-25 05:35:35'),(13,2,'main','#22C55E','2026-04-25 05:35:35','2026-04-25 05:35:35'),(14,2,'light','#86EFAC','2026-04-25 05:35:35','2026-04-25 05:35:35'),(15,2,'extra_light','rgba(255, 255, 255, 0.3)','2026-04-25 05:35:35','2026-04-25 05:35:35'),(16,2,'gradient_bg_1','90deg,#2A7B9B 0%, #57C785 50%, #EDDD53 100%','2026-04-25 05:35:35','2026-04-25 05:35:35'),(17,2,'table_tbody_color','','2026-04-25 05:35:35','2026-04-25 05:35:35'),(18,2,'gradient_bg_1_light','142deg, rgba(255, 255, 255, 1) 0%, rgba(238, 242, 255, 1) 100%','2026-04-25 05:35:35','2026-04-25 05:35:35'),(19,4,'main','#EF4444','2026-04-25 05:35:35','2026-04-25 05:35:35'),(20,4,'light','#FCA5A5','2026-04-25 05:35:35','2026-04-25 05:35:35'),(21,4,'extra_light','rgba(255, 255, 255, 0.3)','2026-04-25 05:35:35','2026-04-25 05:35:35'),(22,4,'gradient_bg_1','90deg,rgba(253, 29, 29, 1) 0%, rgba(252, 176, 69, 0.67) 55%, rgba(235, 143, 143, 1) 79%','2026-04-25 05:35:35','2026-04-25 05:35:35'),(23,4,'table_tbody_color','','2026-04-25 05:35:35','2026-04-25 05:35:35'),(24,4,'gradient_bg_1_light','','2026-04-25 05:35:35','2026-04-25 05:35:35'),(25,5,'main','#F97316','2026-04-25 05:35:35','2026-04-25 05:35:35'),(26,5,'light','#FDBA74','2026-04-25 05:35:35','2026-04-25 05:35:35'),(27,5,'extra_light','rgba(255, 255, 255, 0.3)','2026-04-25 05:35:35','2026-04-25 05:35:35'),(28,5,'gradient_bg_1','142deg,rgba(249, 115, 22, 1) 1%, rgba(253, 186, 116, 1) 100%','2026-04-25 05:35:35','2026-04-25 05:35:35'),(29,5,'table_tbody_color','','2026-04-25 05:35:35','2026-04-25 05:35:35'),(30,5,'gradient_bg_1_light','142deg, rgba(255, 255, 255, 1) 0%, rgba(238, 242, 255, 1) 100%','2026-04-25 05:35:35','2026-04-25 05:35:35');
/*!40000 ALTER TABLE `color_scheme_color` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `currency`
--

DROP TABLE IF EXISTS `currency`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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

LOCK TABLES `currency` WRITE;
/*!40000 ALTER TABLE `currency` DISABLE KEYS */;
INSERT INTO `currency` VALUES (1,'USD','United States Dollar','$',2,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(2,'EUR','Euro','€',2,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(3,'GBP','British Pound Sterling','£',2,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(4,'JPY','Japanese Yen','¥',0,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(5,'AUD','Australian Dollar','A$',2,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(6,'CAD','Canadian Dollar','C$',2,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(7,'CHF','Swiss Franc','CHF',2,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(8,'CNY','Chinese Yuan Renminbi','¥',2,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(9,'HKD','Hong Kong Dollar','HK$',2,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(10,'NZD','New Zealand Dollar','NZ$',2,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(11,'SGD','Singapore Dollar','S$',2,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(12,'SEK','Swedish Krona','kr',2,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(13,'NOK','Norwegian Krone','kr',2,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(14,'DKK','Danish Krone','kr',2,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(15,'INR','Indian Rupee','₹',2,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(16,'KRW','South Korean Won','₩',0,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(17,'ZAR','South African Rand','R',2,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(18,'BRL','Brazilian Real','R$',2,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(19,'MXN','Mexican Peso','$',2,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(20,'PHP','Philippine Peso','₱',2,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(21,'THB','Thai Baht','฿',2,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(22,'AED','UAE Dirham','د.إ',2,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(23,'SAR','Saudi Riyal','﷼',2,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(24,'TRY','Turkish Lira','₺',2,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(25,'RUB','Russian Ruble','₽',2,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(26,'PLN','Polish Zloty','zł',2,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(27,'HUF','Hungarian Forint','Ft',0,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(28,'CZK','Czech Koruna','Kč',2,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(29,'ILS','Israeli Shekel','₪',2,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(30,'MYR','Malaysian Ringgit','RM',2,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(31,'IDR','Indonesian Rupiah','Rp',0,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(32,'VND','Vietnamese Dong','₫',0,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(33,'PKR','Pakistani Rupee','₨',2,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(34,'BDT','Bangladeshi Taka','৳',2,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(35,'NGN','Nigerian Naira','₦',2,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(36,'EGP','Egyptian Pound','£',2,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(37,'KES','Kenyan Shilling','KSh',2,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(38,'GHS','Ghanaian Cedi','₵',2,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(39,'CLP','Chilean Peso','$',0,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(40,'ARS','Argentine Peso','$',2,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(41,'COP','Colombian Peso','$',2,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(42,'PEN','Peruvian Sol','S/',2,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(43,'UYU','Uruguayan Peso','$U',2,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(44,'TWD','New Taiwan Dollar','NT$',2,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(45,'QAR','Qatari Riyal','﷼',2,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(46,'BHD','Bahraini Dinar','.د.ب',3,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(47,'OMR','Omani Rial','﷼',3,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(48,'KWD','Kuwaiti Dinar','د.ك',3,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(49,'LKR','Sri Lankan Rupee','Rs',2,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(50,'MMK','Myanmar Kyat','K',0,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(51,'NPR','Nepalese Rupee','₨',2,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(52,'BND','Brunei Dollar','B$',2,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(53,'LAK','Lao Kip','₭',2,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(54,'KHR','Cambodian Riel','៛',2,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(55,'MOP','Macanese Pataca','MOP$',2,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(56,'BMD','Bermudian Dollar','$',2,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(57,'JMD','Jamaican Dollar','J$',2,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(58,'TTD','Trinidad and Tobago Dollar','TT$',2,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(59,'BBD','Barbadian Dollar','Bds$',2,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(60,'XOF','West African CFA Franc','CFA',0,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(61,'XAF','Central African CFA Franc','FCFA',0,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(62,'MUR','Mauritian Rupee','₨',2,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(63,'SCR','Seychellois Rupee','₨',2,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(64,'TZS','Tanzanian Shilling','TSh',2,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(65,'UGX','Ugandan Shilling','USh',0,1,'2026-04-25 05:35:35','2026-04-25 05:35:35');
/*!40000 ALTER TABLE `currency` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoice_items`
--

DROP TABLE IF EXISTS `invoice_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoice_items`
--

LOCK TABLES `invoice_items` WRITE;
/*!40000 ALTER TABLE `invoice_items` DISABLE KEYS */;
INSERT INTO `invoice_items` VALUES (1,1,1,'User Login Authentication','Create a functinality for the user where they all be needed for verification before they proceed.',1.0000,'',20000,0,0.0000,0.0000,0,20000,NULL,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(2,1,1,'Landing Page Design','Home Page Design',2.0000,'',15050,0,0.0000,0.0000,0,30100,NULL,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(3,1,1,'Logo Design','Logo Design',2.0000,'',5000,0,0.0000,0.0000,0,10000,NULL,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(4,2,1,NULL,'Pile Chart',32.0000,NULL,13400,0,0.0000,0.0000,0,428800,NULL,'2026-04-26 18:00:35','2026-04-26 18:00:35');
/*!40000 ALTER TABLE `invoice_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoice_template_categories`
--

DROP TABLE IF EXISTS `invoice_template_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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

LOCK TABLES `invoice_template_categories` WRITE;
/*!40000 ALTER TABLE `invoice_template_categories` DISABLE KEYS */;
INSERT INTO `invoice_template_categories` VALUES (1,'modern','Modern','/images/invoice-selection/modern.png',1,1,'[]','2026-04-25 05:35:35','2026-04-25 05:35:35'),(2,'classic','Classic','/images/invoice-selection/classic.png',2,1,'[]','2026-04-25 05:35:35','2026-04-25 05:35:35'),(3,'minimal','Minimal','/images/invoice-selection/minimal.png',3,1,'[]','2026-04-25 05:35:35','2026-04-25 05:35:35');
/*!40000 ALTER TABLE `invoice_template_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoice_template_versions`
--

DROP TABLE IF EXISTS `invoice_template_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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

LOCK TABLES `invoice_template_versions` WRITE;
/*!40000 ALTER TABLE `invoice_template_versions` DISABLE KEYS */;
/*!40000 ALTER TABLE `invoice_template_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoice_templates`
--

DROP TABLE IF EXISTS `invoice_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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

LOCK TABLES `invoice_templates` WRITE;
/*!40000 ALTER TABLE `invoice_templates` DISABLE KEYS */;
INSERT INTO `invoice_templates` VALUES (1,1,'moderno','Moderno',1,'/images/templates/moderno.jpg',1,NULL,'modern.v1.moderno','2026-04-25 05:35:35','2026-04-25 05:35:35'),(2,1,'neo','Neo',1,'/images/templates/neo.jpg',1,NULL,'modern.v1.neo-columns','2026-04-25 05:35:35','2026-04-25 05:35:35'),(3,1,'mono','Mono',1,'/images/templates/mono.jpg',1,NULL,'modern.v1.mono','2026-04-25 05:35:35','2026-04-25 05:35:35'),(4,2,'aurora','Aurora',1,'/images/templates/aurora.jpg',1,NULL,'classic.v1.aurora','2026-04-25 05:35:35','2026-04-25 05:35:35'),(5,2,'ledger','Ledger',1,'/images/templates/ledger.jpg',1,NULL,'classic.v1.ledger','2026-04-25 05:35:35','2026-04-25 05:35:35'),(6,2,'simplifi','Simplifi',1,'/images/templates/simplifi.jpg',1,NULL,'classic.v1.simplifi','2026-04-25 05:35:35','2026-04-25 05:35:35'),(7,3,'nexxus','Nexxus',1,'/images/templates/nexxus.jpg',1,NULL,'minimal.v1.nexxus','2026-04-25 05:35:35','2026-04-25 05:35:35'),(8,3,'pulse','Pulse',1,'/images/templates/pulse.jpg',1,NULL,'minimal.v1.pulse','2026-04-25 05:35:35','2026-04-25 05:35:35');
/*!40000 ALTER TABLE `invoice_templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoices`
--

LOCK TABLES `invoices` WRITE;
/*!40000 ALTER TABLE `invoices` DISABLE KEYS */;
INSERT INTO `invoices` VALUES (1,4,1,1,1,1,1,'INV-0001',NULL,NULL,NULL,NULL,NULL,NULL,'draft','test-company-llc',1,NULL,60100,'none',0,0.00,0,0,0.000,0,60100,0,'','',NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(2,7,4,3,6,3,20,'YK-00001',NULL,NULL,'2026-04-26','2026-04-26',NULL,NULL,'issued',NULL,1,NULL,428800,'none',0,0.00,0,0,0.000,0,428800,428800,NULL,NULL,NULL,NULL,NULL,'invoice_pdfs/2026/04/yk_00001_35371146.pdf','public',NULL,0,'ready','2026-04-26 18:20:30',NULL,NULL,'2026-04-26 18:00:35','2026-04-26 18:20:30');
/*!40000 ALTER TABLE `invoices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_plans_table',1),(2,'0001_01_01_000000_create_users_table',1),(3,'0001_01_01_000001_create_cache_table',1),(4,'0001_01_01_000001_user_subscriptions',1),(5,'0001_01_01_000002_create_jobs_table',1),(6,'2025_03_25_000806_create_workspace_table',1),(7,'2025_03_26_120000_backfill_invoice_workspaces',1),(8,'2025_10_09_163456_create_oauth_auth_codes_table',1),(9,'2025_10_09_163457_create_oauth_access_tokens_table',1),(10,'2025_10_09_163458_create_oauth_refresh_tokens_table',1),(11,'2025_10_09_163459_create_oauth_clients_table',1),(12,'2025_10_09_163500_create_oauth_device_codes_table',1),(13,'2025_10_19_172528_create_table_payment_information',1),(14,'2025_10_20_034007_create_business_profiles_table',1),(15,'2025_10_20_035753_create_clients_table',1),(16,'2025_10_20_040134_create_invoice_templates_table',1),(17,'2025_10_20_040222_create_invoice_template_versions_table',1),(18,'2025_10_20_040630_create_user_template_settings_table',1),(19,'2025_10_20_041124_create_currency_table',1),(20,'2025_10_20_041125_create_invoices_table',1),(21,'2025_10_20_041828_create_invoice_items_table',1),(22,'2025_10_24_175951_create_color_scheme_color_table',1),(23,'2025_12_10_031329_create_migration_to_seed_plans_table',1),(24,'2025_12_10_032438_create_migration_to_seed_tests_and_categories',1),(25,'2025_12_10_171714_create_table_plan_capabilities',1),(26,'2025_12_10_172917_seed_plan_capabilities',1),(27,'2025_12_15_070922_stripe_webhook_events',1),(28,'2026_03_30_000001_add_ai_invoice_assistant_plan_capability',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_access_tokens`
--

DROP TABLE IF EXISTS `oauth_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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

LOCK TABLES `oauth_access_tokens` WRITE;
/*!40000 ALTER TABLE `oauth_access_tokens` DISABLE KEYS */;
INSERT INTO `oauth_access_tokens` VALUES ('2213cade8837c4368f4dfbf774d917dd43bc73908aa00bee6b069e68d292d2cd549c0007a3899e77',12,'019dc322-f704-7283-895f-146d4feb9e1d','Billifty Web App','[]',1,'2026-04-26 20:00:08','2026-04-26 20:08:29','2026-10-26 20:00:08'),('2481639507f036ffe4e55ebc3bdd918b6f5a8a21383ae131ceaa9ab76ba60dc6a295d0db8bf975f7',13,'019dc322-f704-7283-895f-146d4feb9e1d','Billifty Web App','[]',1,'2026-04-26 20:13:31','2026-04-26 20:53:28','2026-10-26 20:13:31'),('2d33498ffb1400262810b67b7b34fe04a067541446b74ab7305ee029aee5459d585952c7a4ff7039',9,'019dc322-f704-7283-895f-146d4feb9e1d','Billifty Web App','[]',1,'2026-04-26 19:38:06','2026-04-26 19:45:18','2026-10-26 19:38:06'),('3cbea0d9c06d78c6680e3741fdbe0f0b2346a42bea8adfd78e3912c2441d736981fc2bffae0808b5',8,'019dc322-f704-7283-895f-146d4feb9e1d','Billifty Web App','[]',1,'2026-04-25 08:16:30','2026-04-26 19:33:14','2026-10-25 08:16:30'),('4522efdfc3f4ea76e05d6664942257f0e4a28de0c0b3f1ae08cbae24ad85ec01e86880dcfd4334a4',17,'019dc322-f704-7283-895f-146d4feb9e1d','Billifty Web App','[]',1,'2026-04-26 22:01:33','2026-04-26 22:04:31','2026-10-26 22:01:33'),('63824f521ab4f99f507f4dd684679d850f03189a8aaa61c8b2b9a1d8e835f9ef70cae42b2cc66787',16,'019dc322-f704-7283-895f-146d4feb9e1d','Billifty Web App','[]',1,'2026-04-26 21:15:53','2026-04-26 22:01:13','2026-10-26 21:15:53'),('77a266b04f599150d6b312ff7a06c416f88e5aa69208143b3644d7dd14d551f1d9a2015105d9d3aa',20,'019dc322-f704-7283-895f-146d4feb9e1d','Billifty Web App','[]',1,'2026-04-26 22:08:05','2026-04-27 00:49:37','2026-10-26 22:08:05'),('7e471ba93fb48a8d5f3f2ffa56dfcc450369c67040422d4d82e48a533dd6bc8c34816aab05366b3b',15,'019dc322-f704-7283-895f-146d4feb9e1d','Billifty Web App','[]',1,'2026-04-26 21:15:14','2026-04-26 21:15:25','2026-10-26 21:15:14'),('90e5c48c31c5017c4bb7311db7c5d6ca2bdf4eee24697e485fdd66bf1e6086a22ee6c5cdfdbb1367',18,'019dc322-f704-7283-895f-146d4feb9e1d','Billifty Web App','[]',1,'2026-04-26 22:04:52','2026-04-26 22:06:14','2026-10-26 22:04:52'),('a276c82c55c2d5f15d25df60a6a5431eecb0cdcf35d9192ff22d6b69e17578fefba09769afda0334',10,'019dc322-f704-7283-895f-146d4feb9e1d','Billifty Web App','[]',1,'2026-04-26 19:45:35','2026-04-26 19:53:06','2026-10-26 19:45:35'),('aae4ab5212367816b64118826fb7bbe52943f16ac866937a341309a7fa0ee73955479f8f571f418d',19,'019dc322-f704-7283-895f-146d4feb9e1d','Billifty Web App','[]',1,'2026-04-26 22:06:37','2026-04-26 22:07:51','2026-10-26 22:06:37'),('b87aa7c959718370801860d385812a92140b045410fc8291dd1fb44c452fd00c7e809bc0200efa05',11,'019dc322-f704-7283-895f-146d4feb9e1d','Billifty Web App','[]',1,'2026-04-26 19:53:23','2026-04-26 19:59:36','2026-10-26 19:53:23'),('c372be338a49891a36f258d4f43897a7b79ddfd51a44c163b0a706a0d73219a08977c8e8c9ef2efa',7,'019dc322-f704-7283-895f-146d4feb9e1d','Billifty Web App','[]',1,'2026-04-25 07:30:01','2026-04-25 08:16:09','2026-10-25 07:30:01'),('d79d47e4d321955120a9a48f17236cf363a227a67b98ae364544893474d732ef4ef9c131fc219d22',6,'019dc322-f704-7283-895f-146d4feb9e1d','Billifty Web App','[]',1,'2026-04-25 05:36:19','2026-04-25 07:29:34','2026-10-25 05:36:19'),('df01b75f32c005deae7c3e390acf325e05500f7b9e7b17897b8ec9a1be8960438779438b307ee096',14,'019dc322-f704-7283-895f-146d4feb9e1d','Billifty Web App','[]',1,'2026-04-26 20:53:51','2026-04-26 21:14:59','2026-10-26 20:53:51'),('fff0ca84cbce1ab5440c4032857ca0a25c0a6482663a759301d41be087eb29f37c416980d7bfd62f',21,'019dc322-f704-7283-895f-146d4feb9e1d','Billifty Web App','[]',0,'2026-04-27 00:50:01','2026-04-27 00:50:01','2026-10-27 00:50:01');
/*!40000 ALTER TABLE `oauth_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_auth_codes`
--

DROP TABLE IF EXISTS `oauth_auth_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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

LOCK TABLES `oauth_auth_codes` WRITE;
/*!40000 ALTER TABLE `oauth_auth_codes` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_auth_codes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_clients`
--

DROP TABLE IF EXISTS `oauth_clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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

LOCK TABLES `oauth_clients` WRITE;
/*!40000 ALTER TABLE `oauth_clients` DISABLE KEYS */;
INSERT INTO `oauth_clients` VALUES ('019dc322-f704-7283-895f-146d4feb9e1d',NULL,NULL,'billifty','$2y$12$UnFX8T6gERTnLJUzerwmdelIbnGFH4ULix5WhzIb8VzeiXWHDiLm.','users','[]','[\"personal_access\"]',0,'2026-04-25 05:35:41','2026-04-25 05:35:41');
/*!40000 ALTER TABLE `oauth_clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_device_codes`
--

DROP TABLE IF EXISTS `oauth_device_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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

LOCK TABLES `oauth_device_codes` WRITE;
/*!40000 ALTER TABLE `oauth_device_codes` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_device_codes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_refresh_tokens`
--

DROP TABLE IF EXISTS `oauth_refresh_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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

LOCK TABLES `oauth_refresh_tokens` WRITE;
/*!40000 ALTER TABLE `oauth_refresh_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_refresh_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment_information`
--

DROP TABLE IF EXISTS `payment_information`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment_information` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `payment_method` enum('bank_transfer','paypal','stripe','cash_app') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `routing_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `iban` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `swift_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paypal_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stripe_payment_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cash_app` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `is_test` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_information`
--

LOCK TABLES `payment_information` WRITE;
/*!40000 ALTER TABLE `payment_information` DISABLE KEYS */;
INSERT INTO `payment_information` VALUES (1,'bank_transfer','BoFa','John Doe','123456789','12345678914662',NULL,NULL,NULL,NULL,NULL,'Test',1,'2026-04-25 05:35:35','2026-04-25 05:35:35',NULL),(2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,'2026-04-26 17:57:51','2026-04-26 17:57:51',NULL),(3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,'2026-04-26 17:58:52','2026-04-26 17:58:52',NULL),(4,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,'2026-04-26 21:33:07','2026-04-26 21:33:07',NULL),(5,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,'2026-04-26 21:34:10','2026-04-26 21:34:10',NULL);
/*!40000 ALTER TABLE `payment_information` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plan_capabilities`
--

DROP TABLE IF EXISTS `plan_capabilities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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

LOCK TABLES `plan_capabilities` WRITE;
/*!40000 ALTER TABLE `plan_capabilities` DISABLE KEYS */;
INSERT INTO `plan_capabilities` VALUES (1,1,'max_business_profiles','Business Profiles','int','1',NULL,'businessProfiles',NULL,'limits',1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(2,1,'max_clients','Clients','int','5',NULL,'clients',NULL,'limits',1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(3,1,'max_invoices_per_month','Invoices per month','int','5','{\"usage\": \"monthly\"}','invoices',NULL,'limits',1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(4,1,'pdf_watermark','PDF Watermark','bool','true',NULL,NULL,'“Powered by Billifty” watermark','features',1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(5,1,'email_watermark','Email Watermark','bool','true',NULL,NULL,'Billifty branding in emails','features',1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(6,1,'custom_prefix','Custom Invoice Numbering','bool','false',NULL,NULL,'Basic invoice numbering','features',1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(7,1,'custom_branding','Custom Brand Colors','bool','false',NULL,NULL,'Basic invoice template','features',1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(8,1,'multi_templates','Templates','bool','false',NULL,NULL,'Basic invoice template','features',1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(9,1,'logo_upload','Logo Upload','bool','false',NULL,NULL,NULL,'features',1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(10,1,'automated_reminders','Automated Reminders','string','none',NULL,NULL,NULL,'features',1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(11,1,'online_payments','Online Payments','bool','false',NULL,NULL,NULL,'features',1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(12,1,'multi_currency','Multi-Currency','bool','false',NULL,NULL,NULL,'features',1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(13,1,'ai_invoice_assistant','AI Invoice Assistant','bool','false',NULL,NULL,NULL,'features',1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(14,1,'analytics_tier','Analytics','string','basic',NULL,NULL,NULL,'features',0,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(15,1,'email_branding','Email Branding','string','billifty_footer',NULL,NULL,NULL,'features',1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(16,1,'templates_tier','Templates','string','basic',NULL,NULL,NULL,'features',1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(17,1,'support_level','Support','string','email',NULL,NULL,NULL,'features',1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(18,1,'cta_text1',NULL,'string','Perfect for trying out Billifty.',NULL,NULL,NULL,'marketing',1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(19,1,'cta_btn',NULL,'string','Get started free',NULL,NULL,NULL,'marketing',1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(20,1,'cta_upper_text',NULL,'string','Start here',NULL,NULL,NULL,'marketing',1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(21,1,'cta_card_label',NULL,'string',NULL,NULL,NULL,NULL,'marketing',1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(22,2,'max_business_profiles','Business Profiles','int','3',NULL,'businessProfiles',NULL,'limits',1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(23,2,'max_clients','Clients','int','0','{\"unlimited\": true}','clients',NULL,'limits',1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(24,2,'max_invoices_per_month','Invoices per month','int','10','{\"usage\": \"monthly\"}','invoices',NULL,'limits',1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(25,2,'pdf_watermark','PDF Watermark','bool','false',NULL,NULL,'No PDF watermark','features',1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(26,2,'email_watermark','Email Watermark','bool','true',NULL,NULL,'Watermark on emails','features',1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(27,2,'custom_prefix','Custom Invoice Numbering','bool','true',NULL,NULL,'Custom invoice numbering','features',1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(28,2,'custom_branding','Custom Brand Colors','bool','true',NULL,NULL,'Custom brand colors','features',1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(29,2,'multi_templates','Templates','bool','true',NULL,NULL,'Multiple invoice templates','features',1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(30,2,'logo_upload','Logo Upload','bool','true',NULL,NULL,'Upload business logo','features',1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(31,2,'automated_reminders','Automated Reminders','string','manual',NULL,NULL,'Manual reminders','features',1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(32,2,'online_payments','Online Payments','bool','false',NULL,NULL,NULL,'features',1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(33,2,'multi_currency','Multi-Currency','bool','false',NULL,NULL,NULL,'features',1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(34,2,'ai_invoice_assistant','AI Invoice Assistant','bool','true',NULL,NULL,'AI invoice assistant chat','features',1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(35,2,'analytics_tier','Analytics','string','standard',NULL,NULL,NULL,'features',0,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(36,2,'email_branding','Email Branding','string','small_footer',NULL,NULL,NULL,'features',1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(37,2,'templates_tier','Templates','string','multiple',NULL,NULL,NULL,'features',1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(38,2,'support_level','Support','string','email',NULL,NULL,NULL,'features',1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(39,2,'cta_text1',NULL,'string','Everything you need to invoice clients professionally.',NULL,NULL,NULL,'marketing',1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(40,2,'cta_btn',NULL,'string','Upgrade to Pro',NULL,NULL,NULL,'marketing',1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(41,2,'cta_upper_text',NULL,'string',NULL,NULL,NULL,NULL,'marketing',1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(42,2,'cta_card_label',NULL,'string','BEST FOR FREELANCERS',NULL,NULL,NULL,'marketing',1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(43,3,'max_business_profiles','Business Profiles','int','0','{\"unlimited\": true}','businessProfiles',NULL,'limits',1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(44,3,'max_clients','Clients','int','0','{\"unlimited\": true}','clients',NULL,'limits',1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(45,3,'max_invoices_per_month','Invoices per month','int','0','{\"usage\": \"monthly\", \"unlimited\": true}','invoices',NULL,'limits',1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(46,3,'pdf_watermark','PDF Watermark','bool','false',NULL,NULL,'No branding on PDFs','features',1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(47,3,'email_watermark','Email Watermark','bool','false',NULL,NULL,'No branding on emails','features',1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(48,3,'custom_prefix','Custom Invoice Numbering','bool','true',NULL,NULL,'Custom invoice numbering','features',1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(49,3,'custom_branding','Custom Brand Colors','bool','true',NULL,NULL,'Custom brand colors','features',1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(50,3,'multi_templates','Templates','bool','true',NULL,NULL,'All advanced templates','features',1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(51,3,'logo_upload','Logo Upload','bool','true',NULL,NULL,'Upload business logo','features',1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(52,3,'automated_reminders','Automated Reminders','string','automatic',NULL,NULL,'Automated invoice reminders','features',1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(53,3,'online_payments','Online Payments','bool','true',NULL,NULL,'Online payment links','features',1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(54,3,'multi_currency','Multi-Currency','bool','true',NULL,NULL,'Multi-currency support','features',1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(55,3,'ai_invoice_assistant','AI Invoice Assistant','bool','true',NULL,NULL,'AI invoice assistant chat','features',1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(56,3,'analytics_tier','Analytics','string','advanced',NULL,NULL,'Advanced analytics dashboard','features',0,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(57,3,'email_branding','Email Branding','string','none',NULL,NULL,NULL,'features',1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(58,3,'templates_tier','Templates','string','all_advanced',NULL,NULL,NULL,'features',1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(59,3,'support_level','Support','string','priority',NULL,NULL,NULL,'features',1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(60,3,'cta_text1',NULL,'string','Unlimited invoicing with advanced automation.',NULL,NULL,NULL,'marketing',1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(61,3,'cta_btn',NULL,'string','Go Premium',NULL,NULL,NULL,'marketing',1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(62,3,'cta_upper_text',NULL,'string','For growing teams',NULL,NULL,NULL,'marketing',1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(63,3,'cta_card_label',NULL,'string',NULL,NULL,NULL,NULL,'marketing',1,'2026-04-25 05:35:35','2026-04-25 05:35:35');
/*!40000 ALTER TABLE `plan_capabilities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plans`
--

DROP TABLE IF EXISTS `plans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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

LOCK TABLES `plans` WRITE;
/*!40000 ALTER TABLE `plans` DISABLE KEYS */;
INSERT INTO `plans` VALUES (1,'free','Free','Try Billifty with limited clients and invoices.',0.00,NULL,1,1,'2026-04-25 05:35:34','2026-04-25 05:35:34'),(2,'pro','Pro','For freelancers and small teams.',4.99,49.99,0,2,'2026-04-25 05:35:34','2026-04-25 05:35:34'),(3,'premium','Premium','Unlimited invoicing and automation.',9.99,99.99,0,3,'2026-04-25 05:35:34','2026-04-25 05:35:34');
/*!40000 ALTER TABLE `plans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('SuGbV113x89KCB48FXHiQ6lefiNjAybgS6ch2YZP',NULL,'192.168.65.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVzhmcVVxdE9iREZxRE5uTmlnSU9BeDJHRUZSSTQ3bUV2VGloVkhBSSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDY6Imh0dHBzOi8vaW50LmJpbGxpZnR5LmNvbS9wcmV2aWV3L2ludm9pY2UvMi9wZGYiO3M6NToicm91dGUiO3M6MTk6InByZXZpZXcuaW52b2ljZS5wZGYiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1777227091);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stripe_webhook_events`
--

DROP TABLE IF EXISTS `stripe_webhook_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stripe_webhook_events`
--

LOCK TABLES `stripe_webhook_events` WRITE;
/*!40000 ALTER TABLE `stripe_webhook_events` DISABLE KEYS */;
/*!40000 ALTER TABLE `stripe_webhook_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_subscriptions`
--

DROP TABLE IF EXISTS `user_subscriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_subscriptions`
--

LOCK TABLES `user_subscriptions` WRITE;
/*!40000 ALTER TABLE `user_subscriptions` DISABLE KEYS */;
INSERT INTO `user_subscriptions` VALUES (1,1,1,'free','',NULL,NULL,'usd',NULL,'active',NULL,NULL,NULL,NULL,NULL,'2026-04-25 05:35:34','2026-04-25 05:35:34'),(2,2,2,'pro','',NULL,NULL,'usd',NULL,'active',NULL,NULL,NULL,NULL,NULL,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(3,3,3,'premium','',NULL,NULL,'usd',NULL,'active',NULL,NULL,NULL,NULL,NULL,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(4,4,3,'premium','',NULL,NULL,'usd',NULL,'active',NULL,NULL,NULL,NULL,NULL,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(5,6,1,'free','monthly',NULL,NULL,'usd',NULL,'active',NULL,NULL,NULL,NULL,NULL,'2026-04-25 05:36:19','2026-04-25 05:36:19'),(6,7,1,'free','monthly',NULL,NULL,'usd',NULL,'active',NULL,NULL,NULL,NULL,NULL,'2026-04-25 07:30:01','2026-04-25 07:30:01'),(9,8,2,'pro','monthly','cus_UPMwhKMOviPPt4','sub_1TQYDbEgxqQWB3tMZRskcuEZ','usd',499,'active',NULL,NULL,NULL,NULL,'{\"id\": \"sub_1TQYDbEgxqQWB3tMZRskcuEZ\", \"plan\": {\"id\": \"price_1Sd3R4EgxqQWB3tMvarRVEyG\", \"meter\": null, \"active\": true, \"amount\": 499, \"object\": \"plan\", \"created\": 1765434790, \"product\": \"prod_TaDsiLUgesMtJe\", \"currency\": \"usd\", \"interval\": \"month\", \"livemode\": false, \"metadata\": [], \"nickname\": null, \"tiers_mode\": null, \"usage_type\": \"licensed\", \"amount_decimal\": \"499\", \"billing_scheme\": \"per_unit\", \"interval_count\": 1, \"transform_usage\": null, \"trial_period_days\": null}, \"items\": {\"url\": \"/v1/subscription_items?subscription=sub_1TQYDbEgxqQWB3tMZRskcuEZ\", \"data\": [{\"id\": \"si_UPMxRE92FH3wAw\", \"plan\": {\"id\": \"price_1Sd3R4EgxqQWB3tMvarRVEyG\", \"meter\": null, \"active\": true, \"amount\": 499, \"object\": \"plan\", \"created\": 1765434790, \"product\": \"prod_TaDsiLUgesMtJe\", \"currency\": \"usd\", \"interval\": \"month\", \"livemode\": false, \"metadata\": [], \"nickname\": null, \"tiers_mode\": null, \"usage_type\": \"licensed\", \"amount_decimal\": \"499\", \"billing_scheme\": \"per_unit\", \"interval_count\": 1, \"transform_usage\": null, \"trial_period_days\": null}, \"price\": {\"id\": \"price_1Sd3R4EgxqQWB3tMvarRVEyG\", \"type\": \"recurring\", \"active\": true, \"object\": \"price\", \"created\": 1765434790, \"product\": \"prod_TaDsiLUgesMtJe\", \"currency\": \"usd\", \"livemode\": false, \"metadata\": [], \"nickname\": null, \"recurring\": {\"meter\": null, \"interval\": \"month\", \"usage_type\": \"licensed\", \"interval_count\": 1, \"trial_period_days\": null}, \"lookup_key\": null, \"tiers_mode\": null, \"unit_amount\": 499, \"tax_behavior\": \"unspecified\", \"billing_scheme\": \"per_unit\", \"custom_unit_amount\": null, \"transform_quantity\": null, \"unit_amount_decimal\": \"499\"}, \"object\": \"subscription_item\", \"created\": 1777231189, \"metadata\": [], \"quantity\": 1, \"discounts\": [], \"tax_rates\": [], \"subscription\": \"sub_1TQYDbEgxqQWB3tMZRskcuEZ\", \"billing_thresholds\": null, \"current_period_end\": 1779823189, \"current_period_start\": 1777231189}], \"object\": \"list\", \"has_more\": false, \"total_count\": 1}, \"object\": \"subscription\", \"status\": \"active\", \"created\": 1777231189, \"currency\": \"usd\", \"customer\": \"cus_UPMwhKMOviPPt4\", \"ended_at\": null, \"livemode\": false, \"metadata\": {\"plan_code\": \"pro\", \"billing_cycle\": \"monthly\", \"billifty_user_id\": \"8\"}, \"quantity\": 1, \"schedule\": null, \"cancel_at\": null, \"discounts\": [], \"trial_end\": null, \"start_date\": 1777231189, \"test_clock\": null, \"application\": null, \"canceled_at\": null, \"description\": null, \"trial_start\": null, \"billing_mode\": {\"type\": \"flexible\", \"flexible\": {\"proration_discounts\": \"included\"}, \"updated_at\": 1777231152}, \"on_behalf_of\": null, \"automatic_tax\": {\"enabled\": false, \"liability\": null, \"disabled_reason\": null}, \"transfer_data\": null, \"days_until_due\": null, \"default_source\": null, \"latest_invoice\": \"in_1TQYDZEgxqQWB3tMFdMdjNIS\", \"pending_update\": null, \"trial_settings\": {\"end_behavior\": {\"missing_payment_method\": \"create_invoice\"}}, \"customer_account\": null, \"invoice_settings\": {\"issuer\": {\"type\": \"self\"}, \"account_tax_ids\": null}, \"managed_payments\": {\"enabled\": false}, \"pause_collection\": null, \"payment_settings\": {\"payment_method_types\": null, \"payment_method_options\": {\"pix\": null, \"upi\": null, \"card\": {\"network\": null, \"request_three_d_secure\": \"automatic\"}, \"payto\": null, \"konbini\": null, \"acss_debit\": null, \"bancontact\": null, \"sepa_debit\": null, \"us_bank_account\": null, \"customer_balance\": null}, \"save_default_payment_method\": \"off\"}, \"collection_method\": \"charge_automatically\", \"default_tax_rates\": [], \"billing_thresholds\": null, \"billing_cycle_anchor\": 1777231189, \"cancel_at_period_end\": false, \"cancellation_details\": {\"reason\": null, \"comment\": null, \"feedback\": null}, \"pending_setup_intent\": null, \"default_payment_method\": \"pm_1TQYDYEgxqQWB3tMKWyhlBl3\", \"application_fee_percent\": null, \"billing_cycle_anchor_config\": null, \"pending_invoice_item_interval\": null, \"next_pending_invoice_item_invoice\": null}','2026-04-26 19:20:01','2026-04-26 19:33:02'),(10,9,3,'premium','yearly','cus_UPNHgtaHjCDehA',NULL,'usd',9999,'active',NULL,NULL,NULL,NULL,'{\"id\": \"sub_1TQYXUEgxqQWB3tMdP5Nd4v8\", \"plan\": {\"id\": \"price_1SfGNQEgxqQWB3tMWdDE1ho6\", \"meter\": null, \"active\": true, \"amount\": 9999, \"object\": \"plan\", \"created\": 1765961192, \"product\": \"prod_TcVOOXZAQC4yTM\", \"currency\": \"usd\", \"interval\": \"year\", \"livemode\": false, \"metadata\": [], \"nickname\": null, \"tiers_mode\": null, \"usage_type\": \"licensed\", \"amount_decimal\": \"9999\", \"billing_scheme\": \"per_unit\", \"interval_count\": 1, \"transform_usage\": null, \"trial_period_days\": null}, \"items\": {\"url\": \"/v1/subscription_items?subscription=sub_1TQYXUEgxqQWB3tMdP5Nd4v8\", \"data\": [{\"id\": \"si_UPNI6ygHuj71oT\", \"plan\": {\"id\": \"price_1SfGNQEgxqQWB3tMWdDE1ho6\", \"meter\": null, \"active\": true, \"amount\": 9999, \"object\": \"plan\", \"created\": 1765961192, \"product\": \"prod_TcVOOXZAQC4yTM\", \"currency\": \"usd\", \"interval\": \"year\", \"livemode\": false, \"metadata\": [], \"nickname\": null, \"tiers_mode\": null, \"usage_type\": \"licensed\", \"amount_decimal\": \"9999\", \"billing_scheme\": \"per_unit\", \"interval_count\": 1, \"transform_usage\": null, \"trial_period_days\": null}, \"price\": {\"id\": \"price_1SfGNQEgxqQWB3tMWdDE1ho6\", \"type\": \"recurring\", \"active\": true, \"object\": \"price\", \"created\": 1765961192, \"product\": \"prod_TcVOOXZAQC4yTM\", \"currency\": \"usd\", \"livemode\": false, \"metadata\": [], \"nickname\": null, \"recurring\": {\"meter\": null, \"interval\": \"year\", \"usage_type\": \"licensed\", \"interval_count\": 1, \"trial_period_days\": null}, \"lookup_key\": null, \"tiers_mode\": null, \"unit_amount\": 9999, \"tax_behavior\": \"unspecified\", \"billing_scheme\": \"per_unit\", \"custom_unit_amount\": null, \"transform_quantity\": null, \"unit_amount_decimal\": \"9999\"}, \"object\": \"subscription_item\", \"created\": 1777232421, \"metadata\": [], \"quantity\": 1, \"discounts\": [], \"tax_rates\": [], \"subscription\": \"sub_1TQYXUEgxqQWB3tMdP5Nd4v8\", \"billing_thresholds\": null, \"current_period_end\": 1808768421, \"current_period_start\": 1777232421}], \"object\": \"list\", \"has_more\": false, \"total_count\": 1}, \"object\": \"subscription\", \"status\": \"active\", \"created\": 1777232421, \"currency\": \"usd\", \"customer\": \"cus_UPNHgtaHjCDehA\", \"ended_at\": null, \"livemode\": false, \"metadata\": {\"plan_code\": \"premium\", \"billing_cycle\": \"yearly\", \"billifty_user_id\": \"9\"}, \"quantity\": 1, \"schedule\": null, \"cancel_at\": null, \"discounts\": [], \"trial_end\": null, \"start_date\": 1777232421, \"test_clock\": null, \"application\": null, \"canceled_at\": null, \"description\": null, \"trial_start\": null, \"billing_mode\": {\"type\": \"flexible\", \"flexible\": {\"proration_discounts\": \"included\"}, \"updated_at\": 1777232395}, \"on_behalf_of\": null, \"automatic_tax\": {\"enabled\": false, \"liability\": null, \"disabled_reason\": null}, \"transfer_data\": null, \"days_until_due\": null, \"default_source\": null, \"latest_invoice\": \"in_1TQYXREgxqQWB3tMYNryyZIM\", \"pending_update\": null, \"trial_settings\": {\"end_behavior\": {\"missing_payment_method\": \"create_invoice\"}}, \"customer_account\": null, \"invoice_settings\": {\"issuer\": {\"type\": \"self\"}, \"account_tax_ids\": null}, \"managed_payments\": {\"enabled\": false}, \"pause_collection\": null, \"payment_settings\": {\"payment_method_types\": null, \"payment_method_options\": {\"pix\": null, \"upi\": null, \"card\": {\"network\": null, \"request_three_d_secure\": \"automatic\"}, \"payto\": null, \"konbini\": null, \"acss_debit\": null, \"bancontact\": null, \"sepa_debit\": null, \"us_bank_account\": null, \"customer_balance\": null}, \"save_default_payment_method\": \"off\"}, \"collection_method\": \"charge_automatically\", \"default_tax_rates\": [], \"billing_thresholds\": null, \"billing_cycle_anchor\": 1777232421, \"cancel_at_period_end\": false, \"cancellation_details\": {\"reason\": null, \"comment\": null, \"feedback\": null}, \"pending_setup_intent\": null, \"default_payment_method\": \"pm_1TQYXQEgxqQWB3tMg2Qbj4ep\", \"application_fee_percent\": null, \"billing_cycle_anchor_config\": null, \"pending_invoice_item_interval\": null, \"next_pending_invoice_item_invoice\": null}','2026-04-26 19:38:06','2026-04-26 19:45:11'),(11,10,3,'premium','yearly','cus_UPNSqjs5gwFk94',NULL,'usd',9999,'active',NULL,NULL,NULL,NULL,'{\"id\": \"sub_1TQYi0EgxqQWB3tMq71lYyiZ\", \"plan\": {\"id\": \"price_1SfGNQEgxqQWB3tMWdDE1ho6\", \"meter\": null, \"active\": true, \"amount\": 9999, \"object\": \"plan\", \"created\": 1765961192, \"product\": \"prod_TcVOOXZAQC4yTM\", \"currency\": \"usd\", \"interval\": \"year\", \"livemode\": false, \"metadata\": [], \"nickname\": null, \"tiers_mode\": null, \"usage_type\": \"licensed\", \"amount_decimal\": \"9999\", \"billing_scheme\": \"per_unit\", \"interval_count\": 1, \"transform_usage\": null, \"trial_period_days\": null}, \"items\": {\"url\": \"/v1/subscription_items?subscription=sub_1TQYi0EgxqQWB3tMq71lYyiZ\", \"data\": [{\"id\": \"si_UPNS0zNwJiL3wb\", \"plan\": {\"id\": \"price_1SfGNQEgxqQWB3tMWdDE1ho6\", \"meter\": null, \"active\": true, \"amount\": 9999, \"object\": \"plan\", \"created\": 1765961192, \"product\": \"prod_TcVOOXZAQC4yTM\", \"currency\": \"usd\", \"interval\": \"year\", \"livemode\": false, \"metadata\": [], \"nickname\": null, \"tiers_mode\": null, \"usage_type\": \"licensed\", \"amount_decimal\": \"9999\", \"billing_scheme\": \"per_unit\", \"interval_count\": 1, \"transform_usage\": null, \"trial_period_days\": null}, \"price\": {\"id\": \"price_1SfGNQEgxqQWB3tMWdDE1ho6\", \"type\": \"recurring\", \"active\": true, \"object\": \"price\", \"created\": 1765961192, \"product\": \"prod_TcVOOXZAQC4yTM\", \"currency\": \"usd\", \"livemode\": false, \"metadata\": [], \"nickname\": null, \"recurring\": {\"meter\": null, \"interval\": \"year\", \"usage_type\": \"licensed\", \"interval_count\": 1, \"trial_period_days\": null}, \"lookup_key\": null, \"tiers_mode\": null, \"unit_amount\": 9999, \"tax_behavior\": \"unspecified\", \"billing_scheme\": \"per_unit\", \"custom_unit_amount\": null, \"transform_quantity\": null, \"unit_amount_decimal\": \"9999\"}, \"object\": \"subscription_item\", \"created\": 1777233074, \"metadata\": [], \"quantity\": 1, \"discounts\": [], \"tax_rates\": [], \"subscription\": \"sub_1TQYi0EgxqQWB3tMq71lYyiZ\", \"billing_thresholds\": null, \"current_period_end\": 1808769073, \"current_period_start\": 1777233073}], \"object\": \"list\", \"has_more\": false, \"total_count\": 1}, \"object\": \"subscription\", \"status\": \"active\", \"created\": 1777233073, \"currency\": \"usd\", \"customer\": \"cus_UPNSqjs5gwFk94\", \"ended_at\": null, \"livemode\": false, \"metadata\": {\"plan_code\": \"premium\", \"billing_cycle\": \"yearly\", \"billifty_user_id\": \"10\"}, \"quantity\": 1, \"schedule\": null, \"cancel_at\": null, \"discounts\": [], \"trial_end\": null, \"start_date\": 1777233073, \"test_clock\": null, \"application\": null, \"canceled_at\": null, \"description\": null, \"trial_start\": null, \"billing_mode\": {\"type\": \"flexible\", \"flexible\": {\"proration_discounts\": \"included\"}, \"updated_at\": 1777233046}, \"on_behalf_of\": null, \"automatic_tax\": {\"enabled\": false, \"liability\": null, \"disabled_reason\": null}, \"transfer_data\": null, \"days_until_due\": null, \"default_source\": null, \"latest_invoice\": \"in_1TQYhxEgxqQWB3tMFlbYFVUC\", \"pending_update\": null, \"trial_settings\": {\"end_behavior\": {\"missing_payment_method\": \"create_invoice\"}}, \"customer_account\": null, \"invoice_settings\": {\"issuer\": {\"type\": \"self\"}, \"account_tax_ids\": null}, \"managed_payments\": {\"enabled\": false}, \"pause_collection\": null, \"payment_settings\": {\"payment_method_types\": null, \"payment_method_options\": {\"pix\": null, \"upi\": null, \"card\": {\"network\": null, \"request_three_d_secure\": \"automatic\"}, \"payto\": null, \"konbini\": null, \"acss_debit\": null, \"bancontact\": null, \"sepa_debit\": null, \"us_bank_account\": null, \"customer_balance\": null}, \"save_default_payment_method\": \"off\"}, \"collection_method\": \"charge_automatically\", \"default_tax_rates\": [], \"billing_thresholds\": null, \"billing_cycle_anchor\": 1777233073, \"cancel_at_period_end\": false, \"cancellation_details\": {\"reason\": null, \"comment\": null, \"feedback\": null}, \"pending_setup_intent\": null, \"default_payment_method\": \"pm_1TQYhxEgxqQWB3tML1moJ2uH\", \"application_fee_percent\": null, \"billing_cycle_anchor_config\": null, \"pending_invoice_item_interval\": null, \"next_pending_invoice_item_invoice\": null}','2026-04-26 19:45:35','2026-04-26 19:53:02'),(12,11,2,'pro','yearly','cus_UPNV0mPQlavzbf',NULL,'usd',4999,'active',NULL,NULL,NULL,NULL,'{\"id\": \"sub_1TQYl5EgxqQWB3tMzlx6Zwhf\", \"plan\": {\"id\": \"price_1Sd3RsEgxqQWB3tM7ED1FYNn\", \"meter\": null, \"active\": true, \"amount\": 4999, \"object\": \"plan\", \"created\": 1765434840, \"product\": \"prod_TaDtjy585w9uXZ\", \"currency\": \"usd\", \"interval\": \"year\", \"livemode\": false, \"metadata\": [], \"nickname\": null, \"tiers_mode\": null, \"usage_type\": \"licensed\", \"amount_decimal\": \"4999\", \"billing_scheme\": \"per_unit\", \"interval_count\": 1, \"transform_usage\": null, \"trial_period_days\": null}, \"items\": {\"url\": \"/v1/subscription_items?subscription=sub_1TQYl5EgxqQWB3tMzlx6Zwhf\", \"data\": [{\"id\": \"si_UPNWAevlZ89bIM\", \"plan\": {\"id\": \"price_1Sd3RsEgxqQWB3tM7ED1FYNn\", \"meter\": null, \"active\": true, \"amount\": 4999, \"object\": \"plan\", \"created\": 1765434840, \"product\": \"prod_TaDtjy585w9uXZ\", \"currency\": \"usd\", \"interval\": \"year\", \"livemode\": false, \"metadata\": [], \"nickname\": null, \"tiers_mode\": null, \"usage_type\": \"licensed\", \"amount_decimal\": \"4999\", \"billing_scheme\": \"per_unit\", \"interval_count\": 1, \"transform_usage\": null, \"trial_period_days\": null}, \"price\": {\"id\": \"price_1Sd3RsEgxqQWB3tM7ED1FYNn\", \"type\": \"recurring\", \"active\": true, \"object\": \"price\", \"created\": 1765434840, \"product\": \"prod_TaDtjy585w9uXZ\", \"currency\": \"usd\", \"livemode\": false, \"metadata\": [], \"nickname\": null, \"recurring\": {\"meter\": null, \"interval\": \"year\", \"usage_type\": \"licensed\", \"interval_count\": 1, \"trial_period_days\": null}, \"lookup_key\": null, \"tiers_mode\": null, \"unit_amount\": 4999, \"tax_behavior\": \"unspecified\", \"billing_scheme\": \"per_unit\", \"custom_unit_amount\": null, \"transform_quantity\": null, \"unit_amount_decimal\": \"4999\"}, \"object\": \"subscription_item\", \"created\": 1777233265, \"metadata\": [], \"quantity\": 1, \"discounts\": [], \"tax_rates\": [], \"subscription\": \"sub_1TQYl5EgxqQWB3tMzlx6Zwhf\", \"billing_thresholds\": null, \"current_period_end\": 1808769265, \"current_period_start\": 1777233265}], \"object\": \"list\", \"has_more\": false, \"total_count\": 1}, \"object\": \"subscription\", \"status\": \"active\", \"created\": 1777233265, \"currency\": \"usd\", \"customer\": \"cus_UPNV0mPQlavzbf\", \"ended_at\": null, \"livemode\": false, \"metadata\": {\"plan_code\": \"pro\", \"billing_cycle\": \"yearly\", \"billifty_user_id\": \"11\"}, \"quantity\": 1, \"schedule\": null, \"cancel_at\": null, \"discounts\": [], \"trial_end\": null, \"start_date\": 1777233265, \"test_clock\": null, \"application\": null, \"canceled_at\": null, \"description\": null, \"trial_start\": null, \"billing_mode\": {\"type\": \"flexible\", \"flexible\": {\"proration_discounts\": \"included\"}, \"updated_at\": 1777233247}, \"on_behalf_of\": null, \"automatic_tax\": {\"enabled\": false, \"liability\": null, \"disabled_reason\": null}, \"transfer_data\": null, \"days_until_due\": null, \"default_source\": null, \"latest_invoice\": \"in_1TQYl3EgxqQWB3tMSFP8RlH9\", \"pending_update\": null, \"trial_settings\": {\"end_behavior\": {\"missing_payment_method\": \"create_invoice\"}}, \"customer_account\": null, \"invoice_settings\": {\"issuer\": {\"type\": \"self\"}, \"account_tax_ids\": null}, \"managed_payments\": {\"enabled\": false}, \"pause_collection\": null, \"payment_settings\": {\"payment_method_types\": null, \"payment_method_options\": {\"pix\": null, \"upi\": null, \"card\": {\"network\": null, \"request_three_d_secure\": \"automatic\"}, \"payto\": null, \"konbini\": null, \"acss_debit\": null, \"bancontact\": null, \"sepa_debit\": null, \"us_bank_account\": null, \"customer_balance\": null}, \"save_default_payment_method\": \"off\"}, \"collection_method\": \"charge_automatically\", \"default_tax_rates\": [], \"billing_thresholds\": null, \"billing_cycle_anchor\": 1777233265, \"cancel_at_period_end\": false, \"cancellation_details\": {\"reason\": null, \"comment\": null, \"feedback\": null}, \"pending_setup_intent\": null, \"default_payment_method\": \"pm_1TQYl2EgxqQWB3tMzoYoh0Fg\", \"application_fee_percent\": null, \"billing_cycle_anchor_config\": null, \"pending_invoice_item_interval\": null, \"next_pending_invoice_item_invoice\": null}','2026-04-26 19:53:23','2026-04-26 19:59:05'),(13,12,3,'premium','yearly','cus_UPNcQSxNmcfByd',NULL,'usd',9999,'active',NULL,NULL,NULL,NULL,'{\"id\": \"sub_1TQYrHEgxqQWB3tMy2fxu7Q0\", \"plan\": {\"id\": \"price_1SfGNQEgxqQWB3tMWdDE1ho6\", \"meter\": null, \"active\": true, \"amount\": 9999, \"object\": \"plan\", \"created\": 1765961192, \"product\": \"prod_TcVOOXZAQC4yTM\", \"currency\": \"usd\", \"interval\": \"year\", \"livemode\": false, \"metadata\": [], \"nickname\": null, \"tiers_mode\": null, \"usage_type\": \"licensed\", \"amount_decimal\": \"9999\", \"billing_scheme\": \"per_unit\", \"interval_count\": 1, \"transform_usage\": null, \"trial_period_days\": null}, \"items\": {\"url\": \"/v1/subscription_items?subscription=sub_1TQYrHEgxqQWB3tMy2fxu7Q0\", \"data\": [{\"id\": \"si_UPNcv3GLPRAICs\", \"plan\": {\"id\": \"price_1SfGNQEgxqQWB3tMWdDE1ho6\", \"meter\": null, \"active\": true, \"amount\": 9999, \"object\": \"plan\", \"created\": 1765961192, \"product\": \"prod_TcVOOXZAQC4yTM\", \"currency\": \"usd\", \"interval\": \"year\", \"livemode\": false, \"metadata\": [], \"nickname\": null, \"tiers_mode\": null, \"usage_type\": \"licensed\", \"amount_decimal\": \"9999\", \"billing_scheme\": \"per_unit\", \"interval_count\": 1, \"transform_usage\": null, \"trial_period_days\": null}, \"price\": {\"id\": \"price_1SfGNQEgxqQWB3tMWdDE1ho6\", \"type\": \"recurring\", \"active\": true, \"object\": \"price\", \"created\": 1765961192, \"product\": \"prod_TcVOOXZAQC4yTM\", \"currency\": \"usd\", \"livemode\": false, \"metadata\": [], \"nickname\": null, \"recurring\": {\"meter\": null, \"interval\": \"year\", \"usage_type\": \"licensed\", \"interval_count\": 1, \"trial_period_days\": null}, \"lookup_key\": null, \"tiers_mode\": null, \"unit_amount\": 9999, \"tax_behavior\": \"unspecified\", \"billing_scheme\": \"per_unit\", \"custom_unit_amount\": null, \"transform_quantity\": null, \"unit_amount_decimal\": \"9999\"}, \"object\": \"subscription_item\", \"created\": 1777233649, \"metadata\": [], \"quantity\": 1, \"discounts\": [], \"tax_rates\": [], \"subscription\": \"sub_1TQYrHEgxqQWB3tMy2fxu7Q0\", \"billing_thresholds\": null, \"current_period_end\": 1808769649, \"current_period_start\": 1777233649}], \"object\": \"list\", \"has_more\": false, \"total_count\": 1}, \"object\": \"subscription\", \"status\": \"active\", \"created\": 1777233649, \"currency\": \"usd\", \"customer\": \"cus_UPNcQSxNmcfByd\", \"ended_at\": null, \"livemode\": false, \"metadata\": {\"plan_code\": \"premium\", \"billing_cycle\": \"yearly\", \"billifty_user_id\": \"12\"}, \"quantity\": 1, \"schedule\": null, \"cancel_at\": null, \"discounts\": [], \"trial_end\": null, \"start_date\": 1777233649, \"test_clock\": null, \"application\": null, \"canceled_at\": null, \"description\": null, \"trial_start\": null, \"billing_mode\": {\"type\": \"flexible\", \"flexible\": {\"proration_discounts\": \"included\"}, \"updated_at\": 1777233628}, \"on_behalf_of\": null, \"automatic_tax\": {\"enabled\": false, \"liability\": null, \"disabled_reason\": null}, \"transfer_data\": null, \"days_until_due\": null, \"default_source\": null, \"latest_invoice\": \"in_1TQYrFEgxqQWB3tM1LdOYCQs\", \"pending_update\": null, \"trial_settings\": {\"end_behavior\": {\"missing_payment_method\": \"create_invoice\"}}, \"customer_account\": null, \"invoice_settings\": {\"issuer\": {\"type\": \"self\"}, \"account_tax_ids\": null}, \"managed_payments\": {\"enabled\": false}, \"pause_collection\": null, \"payment_settings\": {\"payment_method_types\": null, \"payment_method_options\": {\"pix\": null, \"upi\": null, \"card\": {\"network\": null, \"request_three_d_secure\": \"automatic\"}, \"payto\": null, \"konbini\": null, \"acss_debit\": null, \"bancontact\": null, \"sepa_debit\": null, \"us_bank_account\": null, \"customer_balance\": null}, \"save_default_payment_method\": \"off\"}, \"collection_method\": \"charge_automatically\", \"default_tax_rates\": [], \"billing_thresholds\": null, \"billing_cycle_anchor\": 1777233649, \"cancel_at_period_end\": false, \"cancellation_details\": {\"reason\": null, \"comment\": null, \"feedback\": null}, \"pending_setup_intent\": null, \"default_payment_method\": \"pm_1TQYrEEgxqQWB3tMhu0CvCCO\", \"application_fee_percent\": null, \"billing_cycle_anchor_config\": null, \"pending_invoice_item_interval\": null, \"next_pending_invoice_item_invoice\": null}','2026-04-26 20:00:08','2026-04-26 20:04:37'),(14,13,2,'pro','monthly','cus_UPNs5bv2r3DOqU',NULL,'usd',499,'active',NULL,NULL,NULL,NULL,'{\"id\": \"sub_1TQZ7HEgxqQWB3tM72TiN5sL\", \"plan\": {\"id\": \"price_1Sd3R4EgxqQWB3tMvarRVEyG\", \"meter\": null, \"active\": true, \"amount\": 499, \"object\": \"plan\", \"created\": 1765434790, \"product\": \"prod_TaDsiLUgesMtJe\", \"currency\": \"usd\", \"interval\": \"month\", \"livemode\": false, \"metadata\": [], \"nickname\": null, \"tiers_mode\": null, \"usage_type\": \"licensed\", \"amount_decimal\": \"499\", \"billing_scheme\": \"per_unit\", \"interval_count\": 1, \"transform_usage\": null, \"trial_period_days\": null}, \"items\": {\"url\": \"/v1/subscription_items?subscription=sub_1TQZ7HEgxqQWB3tM72TiN5sL\", \"data\": [{\"id\": \"si_UPNthNu4bMYaQD\", \"plan\": {\"id\": \"price_1Sd3R4EgxqQWB3tMvarRVEyG\", \"meter\": null, \"active\": true, \"amount\": 499, \"object\": \"plan\", \"created\": 1765434790, \"product\": \"prod_TaDsiLUgesMtJe\", \"currency\": \"usd\", \"interval\": \"month\", \"livemode\": false, \"metadata\": [], \"nickname\": null, \"tiers_mode\": null, \"usage_type\": \"licensed\", \"amount_decimal\": \"499\", \"billing_scheme\": \"per_unit\", \"interval_count\": 1, \"transform_usage\": null, \"trial_period_days\": null}, \"price\": {\"id\": \"price_1Sd3R4EgxqQWB3tMvarRVEyG\", \"type\": \"recurring\", \"active\": true, \"object\": \"price\", \"created\": 1765434790, \"product\": \"prod_TaDsiLUgesMtJe\", \"currency\": \"usd\", \"livemode\": false, \"metadata\": [], \"nickname\": null, \"recurring\": {\"meter\": null, \"interval\": \"month\", \"usage_type\": \"licensed\", \"interval_count\": 1, \"trial_period_days\": null}, \"lookup_key\": null, \"tiers_mode\": null, \"unit_amount\": 499, \"tax_behavior\": \"unspecified\", \"billing_scheme\": \"per_unit\", \"custom_unit_amount\": null, \"transform_quantity\": null, \"unit_amount_decimal\": \"499\"}, \"object\": \"subscription_item\", \"created\": 1777234641, \"metadata\": [], \"quantity\": 1, \"discounts\": [], \"tax_rates\": [], \"subscription\": \"sub_1TQZ7HEgxqQWB3tM72TiN5sL\", \"billing_thresholds\": null, \"current_period_end\": 1779826641, \"current_period_start\": 1777234641}], \"object\": \"list\", \"has_more\": false, \"total_count\": 1}, \"object\": \"subscription\", \"status\": \"active\", \"created\": 1777234641, \"currency\": \"usd\", \"customer\": \"cus_UPNs5bv2r3DOqU\", \"ended_at\": null, \"livemode\": false, \"metadata\": {\"plan_code\": \"pro\", \"billing_cycle\": \"monthly\", \"billifty_user_id\": \"13\"}, \"quantity\": 1, \"schedule\": null, \"cancel_at\": null, \"discounts\": [], \"trial_end\": null, \"start_date\": 1777234641, \"test_clock\": null, \"application\": null, \"canceled_at\": null, \"description\": null, \"trial_start\": null, \"billing_mode\": {\"type\": \"flexible\", \"flexible\": {\"proration_discounts\": \"included\"}, \"updated_at\": 1777234616}, \"on_behalf_of\": null, \"automatic_tax\": {\"enabled\": false, \"liability\": null, \"disabled_reason\": null}, \"transfer_data\": null, \"days_until_due\": null, \"default_source\": null, \"latest_invoice\": \"in_1TQZ7FEgxqQWB3tMGUmcxUuB\", \"pending_update\": null, \"trial_settings\": {\"end_behavior\": {\"missing_payment_method\": \"create_invoice\"}}, \"customer_account\": null, \"invoice_settings\": {\"issuer\": {\"type\": \"self\"}, \"account_tax_ids\": null}, \"managed_payments\": {\"enabled\": false}, \"pause_collection\": null, \"payment_settings\": {\"payment_method_types\": null, \"payment_method_options\": {\"pix\": null, \"upi\": null, \"card\": {\"network\": null, \"request_three_d_secure\": \"automatic\"}, \"payto\": null, \"konbini\": null, \"acss_debit\": null, \"bancontact\": null, \"sepa_debit\": null, \"us_bank_account\": null, \"customer_balance\": null}, \"save_default_payment_method\": \"off\"}, \"collection_method\": \"charge_automatically\", \"default_tax_rates\": [], \"billing_thresholds\": null, \"billing_cycle_anchor\": 1777234641, \"cancel_at_period_end\": false, \"cancellation_details\": {\"reason\": null, \"comment\": null, \"feedback\": null}, \"pending_setup_intent\": null, \"default_payment_method\": \"pm_1TQZ7EEgxqQWB3tM6fbq4FIq\", \"application_fee_percent\": null, \"billing_cycle_anchor_config\": null, \"pending_invoice_item_interval\": null, \"next_pending_invoice_item_invoice\": null}','2026-04-26 20:13:31','2026-04-26 20:20:27'),(15,14,1,'free','monthly',NULL,NULL,'usd',NULL,'active',NULL,NULL,NULL,NULL,NULL,'2026-04-26 20:53:51','2026-04-26 20:53:51'),(16,15,1,'free','monthly',NULL,NULL,'usd',NULL,'active',NULL,NULL,NULL,NULL,NULL,'2026-04-26 21:15:14','2026-04-26 21:15:14'),(17,16,3,'premium','monthly','cus_UPOpHybmbTgFK5',NULL,'usd',999,'active',NULL,NULL,NULL,NULL,'{\"id\": \"sub_1TQa2aEgxqQWB3tMhaAvqN1s\", \"plan\": {\"id\": \"price_1Sd3SQEgxqQWB3tMwm9eDbPZ\", \"meter\": null, \"active\": true, \"amount\": 999, \"object\": \"plan\", \"created\": 1765434874, \"product\": \"prod_TaDuix6Cq3Omo9\", \"currency\": \"usd\", \"interval\": \"month\", \"livemode\": false, \"metadata\": [], \"nickname\": null, \"tiers_mode\": null, \"usage_type\": \"licensed\", \"amount_decimal\": \"999\", \"billing_scheme\": \"per_unit\", \"interval_count\": 1, \"transform_usage\": null, \"trial_period_days\": null}, \"items\": {\"url\": \"/v1/subscription_items?subscription=sub_1TQa2aEgxqQWB3tMhaAvqN1s\", \"data\": [{\"id\": \"si_UPOqN4npYthOFF\", \"plan\": {\"id\": \"price_1Sd3SQEgxqQWB3tMwm9eDbPZ\", \"meter\": null, \"active\": true, \"amount\": 999, \"object\": \"plan\", \"created\": 1765434874, \"product\": \"prod_TaDuix6Cq3Omo9\", \"currency\": \"usd\", \"interval\": \"month\", \"livemode\": false, \"metadata\": [], \"nickname\": null, \"tiers_mode\": null, \"usage_type\": \"licensed\", \"amount_decimal\": \"999\", \"billing_scheme\": \"per_unit\", \"interval_count\": 1, \"transform_usage\": null, \"trial_period_days\": null}, \"price\": {\"id\": \"price_1Sd3SQEgxqQWB3tMwm9eDbPZ\", \"type\": \"recurring\", \"active\": true, \"object\": \"price\", \"created\": 1765434874, \"product\": \"prod_TaDuix6Cq3Omo9\", \"currency\": \"usd\", \"livemode\": false, \"metadata\": [], \"nickname\": null, \"recurring\": {\"meter\": null, \"interval\": \"month\", \"usage_type\": \"licensed\", \"interval_count\": 1, \"trial_period_days\": null}, \"lookup_key\": null, \"tiers_mode\": null, \"unit_amount\": 999, \"tax_behavior\": \"unspecified\", \"billing_scheme\": \"per_unit\", \"custom_unit_amount\": null, \"transform_quantity\": null, \"unit_amount_decimal\": \"999\"}, \"object\": \"subscription_item\", \"created\": 1777238194, \"metadata\": [], \"quantity\": 1, \"discounts\": [], \"tax_rates\": [], \"subscription\": \"sub_1TQa2aEgxqQWB3tMhaAvqN1s\", \"billing_thresholds\": null, \"current_period_end\": 1779830194, \"current_period_start\": 1777238194}], \"object\": \"list\", \"has_more\": false, \"total_count\": 1}, \"object\": \"subscription\", \"status\": \"active\", \"created\": 1777238194, \"currency\": \"usd\", \"customer\": \"cus_UPOpHybmbTgFK5\", \"ended_at\": null, \"livemode\": false, \"metadata\": {\"plan_code\": \"premium\", \"billing_cycle\": \"monthly\", \"billifty_user_id\": \"16\"}, \"quantity\": 1, \"schedule\": null, \"cancel_at\": null, \"discounts\": [], \"trial_end\": null, \"start_date\": 1777238194, \"test_clock\": null, \"application\": null, \"canceled_at\": null, \"description\": null, \"trial_start\": null, \"billing_mode\": {\"type\": \"flexible\", \"flexible\": {\"proration_discounts\": \"included\"}, \"updated_at\": 1777238154}, \"on_behalf_of\": null, \"automatic_tax\": {\"enabled\": false, \"liability\": null, \"disabled_reason\": null}, \"transfer_data\": null, \"days_until_due\": null, \"default_source\": null, \"latest_invoice\": \"in_1TQa2YEgxqQWB3tMPMm7Gcw2\", \"pending_update\": null, \"trial_settings\": {\"end_behavior\": {\"missing_payment_method\": \"create_invoice\"}}, \"customer_account\": null, \"invoice_settings\": {\"issuer\": {\"type\": \"self\"}, \"account_tax_ids\": null}, \"managed_payments\": {\"enabled\": false}, \"pause_collection\": null, \"payment_settings\": {\"payment_method_types\": null, \"payment_method_options\": {\"pix\": null, \"upi\": null, \"card\": {\"network\": null, \"request_three_d_secure\": \"automatic\"}, \"payto\": null, \"konbini\": null, \"acss_debit\": null, \"bancontact\": null, \"sepa_debit\": null, \"us_bank_account\": null, \"customer_balance\": null}, \"save_default_payment_method\": \"off\"}, \"collection_method\": \"charge_automatically\", \"default_tax_rates\": [], \"billing_thresholds\": null, \"billing_cycle_anchor\": 1777238194, \"cancel_at_period_end\": false, \"cancellation_details\": {\"reason\": null, \"comment\": null, \"feedback\": null}, \"pending_setup_intent\": null, \"default_payment_method\": \"pm_1TQa2XEgxqQWB3tM1Zot3roh\", \"application_fee_percent\": null, \"billing_cycle_anchor_config\": null, \"pending_invoice_item_interval\": null, \"next_pending_invoice_item_invoice\": null}','2026-04-26 21:16:41','2026-04-26 21:16:41'),(18,16,3,'premium','monthly','cus_UPOpHybmbTgFK5',NULL,'usd',999,'active',NULL,NULL,NULL,NULL,'{\"id\": \"sub_1TQa2aEgxqQWB3tMhaAvqN1s\", \"plan\": {\"id\": \"price_1Sd3SQEgxqQWB3tMwm9eDbPZ\", \"meter\": null, \"active\": true, \"amount\": 999, \"object\": \"plan\", \"created\": 1765434874, \"product\": \"prod_TaDuix6Cq3Omo9\", \"currency\": \"usd\", \"interval\": \"month\", \"livemode\": false, \"metadata\": [], \"nickname\": null, \"tiers_mode\": null, \"usage_type\": \"licensed\", \"amount_decimal\": \"999\", \"billing_scheme\": \"per_unit\", \"interval_count\": 1, \"transform_usage\": null, \"trial_period_days\": null}, \"items\": {\"url\": \"/v1/subscription_items?subscription=sub_1TQa2aEgxqQWB3tMhaAvqN1s\", \"data\": [{\"id\": \"si_UPOqN4npYthOFF\", \"plan\": {\"id\": \"price_1Sd3SQEgxqQWB3tMwm9eDbPZ\", \"meter\": null, \"active\": true, \"amount\": 999, \"object\": \"plan\", \"created\": 1765434874, \"product\": \"prod_TaDuix6Cq3Omo9\", \"currency\": \"usd\", \"interval\": \"month\", \"livemode\": false, \"metadata\": [], \"nickname\": null, \"tiers_mode\": null, \"usage_type\": \"licensed\", \"amount_decimal\": \"999\", \"billing_scheme\": \"per_unit\", \"interval_count\": 1, \"transform_usage\": null, \"trial_period_days\": null}, \"price\": {\"id\": \"price_1Sd3SQEgxqQWB3tMwm9eDbPZ\", \"type\": \"recurring\", \"active\": true, \"object\": \"price\", \"created\": 1765434874, \"product\": \"prod_TaDuix6Cq3Omo9\", \"currency\": \"usd\", \"livemode\": false, \"metadata\": [], \"nickname\": null, \"recurring\": {\"meter\": null, \"interval\": \"month\", \"usage_type\": \"licensed\", \"interval_count\": 1, \"trial_period_days\": null}, \"lookup_key\": null, \"tiers_mode\": null, \"unit_amount\": 999, \"tax_behavior\": \"unspecified\", \"billing_scheme\": \"per_unit\", \"custom_unit_amount\": null, \"transform_quantity\": null, \"unit_amount_decimal\": \"999\"}, \"object\": \"subscription_item\", \"created\": 1777238194, \"metadata\": [], \"quantity\": 1, \"discounts\": [], \"tax_rates\": [], \"subscription\": \"sub_1TQa2aEgxqQWB3tMhaAvqN1s\", \"billing_thresholds\": null, \"current_period_end\": 1779830194, \"current_period_start\": 1777238194}], \"object\": \"list\", \"has_more\": false, \"total_count\": 1}, \"object\": \"subscription\", \"status\": \"active\", \"created\": 1777238194, \"currency\": \"usd\", \"customer\": \"cus_UPOpHybmbTgFK5\", \"ended_at\": null, \"livemode\": false, \"metadata\": {\"plan_code\": \"premium\", \"billing_cycle\": \"monthly\", \"billifty_user_id\": \"16\"}, \"quantity\": 1, \"schedule\": null, \"cancel_at\": null, \"discounts\": [], \"trial_end\": null, \"start_date\": 1777238194, \"test_clock\": null, \"application\": null, \"canceled_at\": null, \"description\": null, \"trial_start\": null, \"billing_mode\": {\"type\": \"flexible\", \"flexible\": {\"proration_discounts\": \"included\"}, \"updated_at\": 1777238154}, \"on_behalf_of\": null, \"automatic_tax\": {\"enabled\": false, \"liability\": null, \"disabled_reason\": null}, \"transfer_data\": null, \"days_until_due\": null, \"default_source\": null, \"latest_invoice\": \"in_1TQa2YEgxqQWB3tMPMm7Gcw2\", \"pending_update\": null, \"trial_settings\": {\"end_behavior\": {\"missing_payment_method\": \"create_invoice\"}}, \"customer_account\": null, \"invoice_settings\": {\"issuer\": {\"type\": \"self\"}, \"account_tax_ids\": null}, \"managed_payments\": {\"enabled\": false}, \"pause_collection\": null, \"payment_settings\": {\"payment_method_types\": null, \"payment_method_options\": {\"pix\": null, \"upi\": null, \"card\": {\"network\": null, \"request_three_d_secure\": \"automatic\"}, \"payto\": null, \"konbini\": null, \"acss_debit\": null, \"bancontact\": null, \"sepa_debit\": null, \"us_bank_account\": null, \"customer_balance\": null}, \"save_default_payment_method\": \"off\"}, \"collection_method\": \"charge_automatically\", \"default_tax_rates\": [], \"billing_thresholds\": null, \"billing_cycle_anchor\": 1777238194, \"cancel_at_period_end\": false, \"cancellation_details\": {\"reason\": null, \"comment\": null, \"feedback\": null}, \"pending_setup_intent\": null, \"default_payment_method\": \"pm_1TQa2XEgxqQWB3tM1Zot3roh\", \"application_fee_percent\": null, \"billing_cycle_anchor_config\": null, \"pending_invoice_item_interval\": null, \"next_pending_invoice_item_invoice\": null}','2026-04-26 21:16:41','2026-04-26 21:16:41'),(19,17,1,'free','monthly',NULL,NULL,'usd',NULL,'active',NULL,NULL,NULL,NULL,NULL,'2026-04-26 22:01:33','2026-04-26 22:01:33'),(20,18,3,'premium','monthly','cus_UPPc2EZGLDhzv2',NULL,'usd',999,'active',NULL,NULL,NULL,NULL,'{\"id\": \"sub_1TQao5EgxqQWB3tMBPxNknDc\", \"plan\": {\"id\": \"price_1Sd3SQEgxqQWB3tMwm9eDbPZ\", \"meter\": null, \"active\": true, \"amount\": 999, \"object\": \"plan\", \"created\": 1765434874, \"product\": \"prod_TaDuix6Cq3Omo9\", \"currency\": \"usd\", \"interval\": \"month\", \"livemode\": false, \"metadata\": [], \"nickname\": null, \"tiers_mode\": null, \"usage_type\": \"licensed\", \"amount_decimal\": \"999\", \"billing_scheme\": \"per_unit\", \"interval_count\": 1, \"transform_usage\": null, \"trial_period_days\": null}, \"items\": {\"url\": \"/v1/subscription_items?subscription=sub_1TQao5EgxqQWB3tMBPxNknDc\", \"data\": [{\"id\": \"si_UPPddscXRuxzeW\", \"plan\": {\"id\": \"price_1Sd3SQEgxqQWB3tMwm9eDbPZ\", \"meter\": null, \"active\": true, \"amount\": 999, \"object\": \"plan\", \"created\": 1765434874, \"product\": \"prod_TaDuix6Cq3Omo9\", \"currency\": \"usd\", \"interval\": \"month\", \"livemode\": false, \"metadata\": [], \"nickname\": null, \"tiers_mode\": null, \"usage_type\": \"licensed\", \"amount_decimal\": \"999\", \"billing_scheme\": \"per_unit\", \"interval_count\": 1, \"transform_usage\": null, \"trial_period_days\": null}, \"price\": {\"id\": \"price_1Sd3SQEgxqQWB3tMwm9eDbPZ\", \"type\": \"recurring\", \"active\": true, \"object\": \"price\", \"created\": 1765434874, \"product\": \"prod_TaDuix6Cq3Omo9\", \"currency\": \"usd\", \"livemode\": false, \"metadata\": [], \"nickname\": null, \"recurring\": {\"meter\": null, \"interval\": \"month\", \"usage_type\": \"licensed\", \"interval_count\": 1, \"trial_period_days\": null}, \"lookup_key\": null, \"tiers_mode\": null, \"unit_amount\": 999, \"tax_behavior\": \"unspecified\", \"billing_scheme\": \"per_unit\", \"custom_unit_amount\": null, \"transform_quantity\": null, \"unit_amount_decimal\": \"999\"}, \"object\": \"subscription_item\", \"created\": 1777241139, \"metadata\": [], \"quantity\": 1, \"discounts\": [], \"tax_rates\": [], \"subscription\": \"sub_1TQao5EgxqQWB3tMBPxNknDc\", \"billing_thresholds\": null, \"current_period_end\": 1779833139, \"current_period_start\": 1777241139}], \"object\": \"list\", \"has_more\": false, \"total_count\": 1}, \"object\": \"subscription\", \"status\": \"active\", \"created\": 1777241139, \"currency\": \"usd\", \"customer\": \"cus_UPPc2EZGLDhzv2\", \"ended_at\": null, \"livemode\": false, \"metadata\": {\"plan_code\": \"premium\", \"billing_cycle\": \"monthly\", \"billifty_user_id\": \"18\"}, \"quantity\": 1, \"schedule\": null, \"cancel_at\": null, \"discounts\": [], \"trial_end\": null, \"start_date\": 1777241139, \"test_clock\": null, \"application\": null, \"canceled_at\": null, \"description\": null, \"trial_start\": null, \"billing_mode\": {\"type\": \"flexible\", \"flexible\": {\"proration_discounts\": \"included\"}, \"updated_at\": 1777241108}, \"on_behalf_of\": null, \"automatic_tax\": {\"enabled\": false, \"liability\": null, \"disabled_reason\": null}, \"transfer_data\": null, \"days_until_due\": null, \"default_source\": null, \"latest_invoice\": \"in_1TQao3EgxqQWB3tMZee7RUF7\", \"pending_update\": null, \"trial_settings\": {\"end_behavior\": {\"missing_payment_method\": \"create_invoice\"}}, \"customer_account\": null, \"invoice_settings\": {\"issuer\": {\"type\": \"self\"}, \"account_tax_ids\": null}, \"managed_payments\": {\"enabled\": false}, \"pause_collection\": null, \"payment_settings\": {\"payment_method_types\": null, \"payment_method_options\": {\"pix\": null, \"upi\": null, \"card\": {\"network\": null, \"request_three_d_secure\": \"automatic\"}, \"payto\": null, \"konbini\": null, \"acss_debit\": null, \"bancontact\": null, \"sepa_debit\": null, \"us_bank_account\": null, \"customer_balance\": null}, \"save_default_payment_method\": \"off\"}, \"collection_method\": \"charge_automatically\", \"default_tax_rates\": [], \"billing_thresholds\": null, \"billing_cycle_anchor\": 1777241139, \"cancel_at_period_end\": false, \"cancellation_details\": {\"reason\": null, \"comment\": null, \"feedback\": null}, \"pending_setup_intent\": null, \"default_payment_method\": \"pm_1TQao2EgxqQWB3tMTzufLdQB\", \"application_fee_percent\": null, \"billing_cycle_anchor_config\": null, \"pending_invoice_item_interval\": null, \"next_pending_invoice_item_invoice\": null}','2026-04-26 22:04:52','2026-04-26 22:06:06'),(21,19,2,'pro','yearly','cus_UPPeQGvt2AqNvg',NULL,'usd',4999,'active',NULL,NULL,NULL,NULL,'{\"id\": \"sub_1TQapXEgxqQWB3tMHW3lfg4d\", \"plan\": {\"id\": \"price_1Sd3RsEgxqQWB3tM7ED1FYNn\", \"meter\": null, \"active\": true, \"amount\": 4999, \"object\": \"plan\", \"created\": 1765434840, \"product\": \"prod_TaDtjy585w9uXZ\", \"currency\": \"usd\", \"interval\": \"year\", \"livemode\": false, \"metadata\": [], \"nickname\": null, \"tiers_mode\": null, \"usage_type\": \"licensed\", \"amount_decimal\": \"4999\", \"billing_scheme\": \"per_unit\", \"interval_count\": 1, \"transform_usage\": null, \"trial_period_days\": null}, \"items\": {\"url\": \"/v1/subscription_items?subscription=sub_1TQapXEgxqQWB3tMHW3lfg4d\", \"data\": [{\"id\": \"si_UPPezWrIcZsoyD\", \"plan\": {\"id\": \"price_1Sd3RsEgxqQWB3tM7ED1FYNn\", \"meter\": null, \"active\": true, \"amount\": 4999, \"object\": \"plan\", \"created\": 1765434840, \"product\": \"prod_TaDtjy585w9uXZ\", \"currency\": \"usd\", \"interval\": \"year\", \"livemode\": false, \"metadata\": [], \"nickname\": null, \"tiers_mode\": null, \"usage_type\": \"licensed\", \"amount_decimal\": \"4999\", \"billing_scheme\": \"per_unit\", \"interval_count\": 1, \"transform_usage\": null, \"trial_period_days\": null}, \"price\": {\"id\": \"price_1Sd3RsEgxqQWB3tM7ED1FYNn\", \"type\": \"recurring\", \"active\": true, \"object\": \"price\", \"created\": 1765434840, \"product\": \"prod_TaDtjy585w9uXZ\", \"currency\": \"usd\", \"livemode\": false, \"metadata\": [], \"nickname\": null, \"recurring\": {\"meter\": null, \"interval\": \"year\", \"usage_type\": \"licensed\", \"interval_count\": 1, \"trial_period_days\": null}, \"lookup_key\": null, \"tiers_mode\": null, \"unit_amount\": 4999, \"tax_behavior\": \"unspecified\", \"billing_scheme\": \"per_unit\", \"custom_unit_amount\": null, \"transform_quantity\": null, \"unit_amount_decimal\": \"4999\"}, \"object\": \"subscription_item\", \"created\": 1777241229, \"metadata\": [], \"quantity\": 1, \"discounts\": [], \"tax_rates\": [], \"subscription\": \"sub_1TQapXEgxqQWB3tMHW3lfg4d\", \"billing_thresholds\": null, \"current_period_end\": 1808777228, \"current_period_start\": 1777241228}], \"object\": \"list\", \"has_more\": false, \"total_count\": 1}, \"object\": \"subscription\", \"status\": \"active\", \"created\": 1777241228, \"currency\": \"usd\", \"customer\": \"cus_UPPeQGvt2AqNvg\", \"ended_at\": null, \"livemode\": false, \"metadata\": {\"plan_code\": \"pro\", \"billing_cycle\": \"yearly\", \"billifty_user_id\": \"19\"}, \"quantity\": 1, \"schedule\": null, \"cancel_at\": null, \"discounts\": [], \"trial_end\": null, \"start_date\": 1777241228, \"test_clock\": null, \"application\": null, \"canceled_at\": null, \"description\": null, \"trial_start\": null, \"billing_mode\": {\"type\": \"flexible\", \"flexible\": {\"proration_discounts\": \"included\"}, \"updated_at\": 1777241214}, \"on_behalf_of\": null, \"automatic_tax\": {\"enabled\": false, \"liability\": null, \"disabled_reason\": null}, \"transfer_data\": null, \"days_until_due\": null, \"default_source\": null, \"latest_invoice\": \"in_1TQapVEgxqQWB3tMiQq1pwqz\", \"pending_update\": null, \"trial_settings\": {\"end_behavior\": {\"missing_payment_method\": \"create_invoice\"}}, \"customer_account\": null, \"invoice_settings\": {\"issuer\": {\"type\": \"self\"}, \"account_tax_ids\": null}, \"managed_payments\": {\"enabled\": false}, \"pause_collection\": null, \"payment_settings\": {\"payment_method_types\": null, \"payment_method_options\": {\"pix\": null, \"upi\": null, \"card\": {\"network\": null, \"request_three_d_secure\": \"automatic\"}, \"payto\": null, \"konbini\": null, \"acss_debit\": null, \"bancontact\": null, \"sepa_debit\": null, \"us_bank_account\": null, \"customer_balance\": null}, \"save_default_payment_method\": \"off\"}, \"collection_method\": \"charge_automatically\", \"default_tax_rates\": [], \"billing_thresholds\": null, \"billing_cycle_anchor\": 1777241228, \"cancel_at_period_end\": false, \"cancellation_details\": {\"reason\": null, \"comment\": null, \"feedback\": null}, \"pending_setup_intent\": null, \"default_payment_method\": \"pm_1TQapUEgxqQWB3tMzryr6Xzh\", \"application_fee_percent\": null, \"billing_cycle_anchor_config\": null, \"pending_invoice_item_interval\": null, \"next_pending_invoice_item_invoice\": null}','2026-04-26 22:06:37','2026-04-26 22:07:15'),(22,20,3,'premium','monthly','cus_UPPgRukWtB5lL3',NULL,'usd',999,'active',NULL,NULL,NULL,NULL,'{\"id\": \"sub_1TQar1EgxqQWB3tMpumf8s2e\", \"plan\": {\"id\": \"price_1Sd3SQEgxqQWB3tMwm9eDbPZ\", \"meter\": null, \"active\": true, \"amount\": 999, \"object\": \"plan\", \"created\": 1765434874, \"product\": \"prod_TaDuix6Cq3Omo9\", \"currency\": \"usd\", \"interval\": \"month\", \"livemode\": false, \"metadata\": [], \"nickname\": null, \"tiers_mode\": null, \"usage_type\": \"licensed\", \"amount_decimal\": \"999\", \"billing_scheme\": \"per_unit\", \"interval_count\": 1, \"transform_usage\": null, \"trial_period_days\": null}, \"items\": {\"url\": \"/v1/subscription_items?subscription=sub_1TQar1EgxqQWB3tMpumf8s2e\", \"data\": [{\"id\": \"si_UPPgyeUK8iVDvI\", \"plan\": {\"id\": \"price_1Sd3SQEgxqQWB3tMwm9eDbPZ\", \"meter\": null, \"active\": true, \"amount\": 999, \"object\": \"plan\", \"created\": 1765434874, \"product\": \"prod_TaDuix6Cq3Omo9\", \"currency\": \"usd\", \"interval\": \"month\", \"livemode\": false, \"metadata\": [], \"nickname\": null, \"tiers_mode\": null, \"usage_type\": \"licensed\", \"amount_decimal\": \"999\", \"billing_scheme\": \"per_unit\", \"interval_count\": 1, \"transform_usage\": null, \"trial_period_days\": null}, \"price\": {\"id\": \"price_1Sd3SQEgxqQWB3tMwm9eDbPZ\", \"type\": \"recurring\", \"active\": true, \"object\": \"price\", \"created\": 1765434874, \"product\": \"prod_TaDuix6Cq3Omo9\", \"currency\": \"usd\", \"livemode\": false, \"metadata\": [], \"nickname\": null, \"recurring\": {\"meter\": null, \"interval\": \"month\", \"usage_type\": \"licensed\", \"interval_count\": 1, \"trial_period_days\": null}, \"lookup_key\": null, \"tiers_mode\": null, \"unit_amount\": 999, \"tax_behavior\": \"unspecified\", \"billing_scheme\": \"per_unit\", \"custom_unit_amount\": null, \"transform_quantity\": null, \"unit_amount_decimal\": \"999\"}, \"object\": \"subscription_item\", \"created\": 1777241321, \"metadata\": [], \"quantity\": 1, \"discounts\": [], \"tax_rates\": [], \"subscription\": \"sub_1TQar1EgxqQWB3tMpumf8s2e\", \"billing_thresholds\": null, \"current_period_end\": 1779833321, \"current_period_start\": 1777241321}], \"object\": \"list\", \"has_more\": false, \"total_count\": 1}, \"object\": \"subscription\", \"status\": \"active\", \"created\": 1777241321, \"currency\": \"usd\", \"customer\": \"cus_UPPgRukWtB5lL3\", \"ended_at\": null, \"livemode\": false, \"metadata\": {\"plan_code\": \"premium\", \"billing_cycle\": \"monthly\", \"billifty_user_id\": \"20\"}, \"quantity\": 1, \"schedule\": null, \"cancel_at\": null, \"discounts\": [], \"trial_end\": null, \"start_date\": 1777241321, \"test_clock\": null, \"application\": null, \"canceled_at\": null, \"description\": null, \"trial_start\": null, \"billing_mode\": {\"type\": \"flexible\", \"flexible\": {\"proration_discounts\": \"included\"}, \"updated_at\": 1777241302}, \"on_behalf_of\": null, \"automatic_tax\": {\"enabled\": false, \"liability\": null, \"disabled_reason\": null}, \"transfer_data\": null, \"days_until_due\": null, \"default_source\": null, \"latest_invoice\": \"in_1TQaqzEgxqQWB3tMnFR3rkoL\", \"pending_update\": null, \"trial_settings\": {\"end_behavior\": {\"missing_payment_method\": \"create_invoice\"}}, \"customer_account\": null, \"invoice_settings\": {\"issuer\": {\"type\": \"self\"}, \"account_tax_ids\": null}, \"managed_payments\": {\"enabled\": false}, \"pause_collection\": null, \"payment_settings\": {\"payment_method_types\": null, \"payment_method_options\": {\"pix\": null, \"upi\": null, \"card\": {\"network\": null, \"request_three_d_secure\": \"automatic\"}, \"payto\": null, \"konbini\": null, \"acss_debit\": null, \"bancontact\": null, \"sepa_debit\": null, \"us_bank_account\": null, \"customer_balance\": null}, \"save_default_payment_method\": \"off\"}, \"collection_method\": \"charge_automatically\", \"default_tax_rates\": [], \"billing_thresholds\": null, \"billing_cycle_anchor\": 1777241321, \"cancel_at_period_end\": false, \"cancellation_details\": {\"reason\": null, \"comment\": null, \"feedback\": null}, \"pending_setup_intent\": null, \"default_payment_method\": \"pm_1TQaqyEgxqQWB3tMnVcz1q82\", \"application_fee_percent\": null, \"billing_cycle_anchor_config\": null, \"pending_invoice_item_interval\": null, \"next_pending_invoice_item_invoice\": null}','2026-04-26 22:08:05','2026-04-26 22:08:47'),(23,21,1,'free','monthly',NULL,NULL,'usd',NULL,'active',NULL,NULL,NULL,NULL,NULL,'2026-04-27 00:50:01','2026-04-27 00:50:01');
/*!40000 ALTER TABLE `user_subscriptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_template_settings`
--

DROP TABLE IF EXISTS `user_template_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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

LOCK TABLES `user_template_settings` WRITE;
/*!40000 ALTER TABLE `user_template_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_template_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,1,NULL,NULL,'John Paine','john+free@billifty.czom',NULL,'$2y$12$OPdCIA4Q9nvM.oDIBPpNiOK.uhvrf7Whg6VEEYyeckj9/zOHU2Xxu',NULL,NULL,NULL,NULL,0,NULL,NULL,'2026-04-25 05:35:34','2026-04-25 05:35:34'),(2,2,NULL,NULL,'Kirk McDonald','kirk+pro@billifty.com',NULL,'$2y$12$tB7DEUmuusTd0dml72LPc.4n74JncD57Z7D64Ugj4MKQmj1Db2/em',NULL,NULL,NULL,NULL,0,NULL,NULL,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(3,3,NULL,NULL,'James Harris','james+premium@billifty.com',NULL,'$2y$12$yszEhb2acD8Lr5z2Z7ahSONzx2wNjXb4sRiUGewB1bry3OS8UwV0e',NULL,NULL,NULL,NULL,0,NULL,NULL,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(4,3,NULL,NULL,'Ed Bedia','fordbedia@billifty.com',NULL,'$2y$12$zFPKnmyqtGkdrSAssxMceOhLvwsI21jWtPh3JmYHtJk5weJhOO7V2',NULL,NULL,NULL,NULL,0,NULL,NULL,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(5,1,NULL,NULL,'Test User','test@example.com','2026-04-25 05:35:35','$2y$12$/Gzvb8BfZx1jRiICo75aNuMpvU.LHZTlqgK/zvUZYwabf0qV4bXDy',NULL,NULL,NULL,NULL,0,'J6gselOeYp',NULL,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(6,1,'John','Hampton','John Hampton','johnH@gmail.com',NULL,'$2y$12$lNa1WOnaXVcIdlPbCYjqaOgIBsnPKucWQqYDHnIdXMnRWL.ex966q',NULL,NULL,NULL,NULL,0,'osnzMWQG9N2yJaKZ8GUCCl7Jh2hp3bLfPb00xN9Z4rMRi3Ei9uNbVL94o9c1',NULL,'2026-04-25 05:36:19','2026-04-25 05:36:19'),(7,1,'Stacey','Harper','Stacey Harper','staceyharper@gmail.cxom','2026-04-25 07:30:51','$2y$12$PRy1crM5eTqNhVWY/BX2Aek7PhGRjLUYjsHVyapts3epRQxd6ipkG',NULL,NULL,NULL,NULL,0,'ZuKHpBMvY2jz6qmpnPC7NhPf5bR3J26KYGeLlghsAqBQBFH3wwOspicl0R8I',NULL,'2026-04-25 07:30:01','2026-04-25 07:30:51'),(8,2,'Veronica','Heggins','Veronica Heggins','verheggins@gmail.com','2026-04-26 18:44:28','$2y$12$l7q/JRHJGXp2RPpVs4exiOo9.dkAVibIsQsepyCDV6X44tsUmujQW',NULL,NULL,'cus_UPMwhKMOviPPt4',NULL,0,'RGXgebWXL08FEODUXiL2XHUIhBX11uF8a1eMSmbtJbLroqQBWQ4Pm6dawulf',NULL,'2026-04-25 08:16:30','2026-04-26 19:20:01'),(9,3,'Jenson','Martin','Jenson Martin','jensonmarting@gmail.com','2026-04-26 19:39:18','$2y$12$2A.mB36cSw/LYyDQXW1MCO/fun.OLhzgyvuUlrtNJl5x4pAGKr256',NULL,NULL,'cus_UPNHgtaHjCDehA',NULL,0,'knC1bvtrtnnWwEnCVYiE79iY6QBskwv4yZk6HAjxuLlViMcfisj8tu1SEWw8',NULL,'2026-04-26 19:38:06','2026-04-26 19:40:29'),(10,3,'John','Hopkins','John Hopkins','johnhopkins@gmail.com',NULL,'$2y$12$I/1aMSg.w3QS3P/1HxZ4xO71lpaqWJ6SnLv5MzCYKztQ0swVfctvq',NULL,NULL,'cus_UPNSqjs5gwFk94',NULL,0,'XOFmhYrrtkjzLyECW5f1uPl6ABSjM5Chc6tLmdKPjCoUE7KjcUb3CxUlrL3n',NULL,'2026-04-26 19:45:35','2026-04-26 19:51:29'),(11,2,'MIla','Konis','MIla Konis','milakonis@gmail.com','2026-04-26 19:53:38','$2y$12$RgpGjImFq1ncgzJn4Wo3WeSOj.2OtFN/qNfT2LnUCsbb5EnKxYxi2',NULL,NULL,'cus_UPNV0mPQlavzbf',NULL,0,'Mjn6Ghd749KAzIENSbqgKDlO2xbTGLVDoDHnvJRXZpkbGPzlVIbWQfqbQYea',NULL,'2026-04-26 19:53:23','2026-04-26 19:54:32'),(12,3,'Jona','Brosil','Jona Brosil','jonabrosol@gmail.com',NULL,'$2y$12$361Z5RHs2U124luWj7LJQOZ9FB0EMfS9YDzOV4CZFtJlLuVXWxRhO',NULL,NULL,'cus_UPNcQSxNmcfByd',NULL,0,'BMTJJN4uSEkBlXfUh8MSnawCjJTwYxI4FEaHvOpgiL47u2NuIB80A35tjCdr',NULL,'2026-04-26 20:00:08','2026-04-26 20:00:57'),(13,2,'Stacey','co','Stacey co','staceyco@gmail.com',NULL,'$2y$12$ACtYvoF8bKvanTI2kYsx7eYqbNcY8yCvtOFdWMtgIagkjTugXoeZ.',NULL,NULL,'cus_UPNs5bv2r3DOqU',NULL,0,'fX2d9Mjpmk8KlpfQpY9lLA6UYVwgXoPxuP9FJuaRTYpaPESrq2NESbfbaRIC',NULL,'2026-04-26 20:13:31','2026-04-26 20:17:29'),(14,1,'Devon','James','Devon James','djames@gmail.com',NULL,'$2y$12$gTURkMclXKdcZdT9ZJxJceduMIa1yGOQ4dcYeD8FMZTw9XOkUEyRu',NULL,NULL,NULL,NULL,0,'j1XwNWO3o49rAQNJPd6RABheQ2d5VBSnDKc8pQzOYEyPf18mM8zrW956H7BI',NULL,'2026-04-26 20:53:51','2026-04-26 20:53:51'),(15,1,'EER','EQW','EER EQW','EWW11@gmail.com',NULL,'$2y$12$W0fEjiBdSm688qb2wV9mc.68dppUTX5tuN0LoxWc.PjzZ0xDJvt26',NULL,NULL,NULL,NULL,0,'GBTsAaStMqsYBjOT04Cu8aU5a9drf1Wkv3ALJ9V1LmN7LnudNSlBxsHBFDQt',NULL,'2026-04-26 21:15:14','2026-04-26 21:15:14'),(16,3,'ARTES','Jason','ARTES Jason','arterj@gmail.com','2026-04-26 21:32:10','$2y$12$EPnm6bxTws/HCkloIS.NZeAO6v6Fz5UOP3iVaypIcW.T002g64DVi',NULL,NULL,'cus_UPOpHybmbTgFK5',NULL,0,'DUWy5UnRHu3UaWDU5qoQphi8xYhu2wXgNFQUdhfp6z9wgtXwnBs4z7qhXgKc',NULL,'2026-04-26 21:15:53','2026-04-26 21:32:10'),(17,1,'Bernard','Yusay','Bernard Yusay','bysay@gmail.com',NULL,'$2y$12$oaesx8mFM1krnSNE1qhsluJtICv9KCgZ78dWvT2gs0I5jn.NvOQwS',NULL,NULL,NULL,NULL,0,'ucWP8JVX5yidaBy8vWiCVrw57ForuOz9hO1bHAL6C0serqKeoG39OJ6oPyXS',NULL,'2026-04-26 22:01:33','2026-04-26 22:01:33'),(18,3,'Blase','Javier','Blase Javier','bjavier@mgai.com',NULL,'$2y$12$d2.d0ZEfe1LEEsIgxTHw4OHBgLnR053y/P5oPFljSVPj5xReBH.Ei',NULL,NULL,'cus_UPPc2EZGLDhzv2',NULL,0,'jeGjAj4al5y5BWaKttaGuSs9wNbPkg9hs0RHs510wgAmNmctWSBBLziLAoyL',NULL,'2026-04-26 22:04:52','2026-04-26 22:05:46'),(19,2,'Freddie','Espinosa','Freddie Espinosa','freddespinosa@gmail.com',NULL,'$2y$12$whE5RzxuMh8XKEWvnLb/vOP3hrTDGM6IptdbUDUBjMrdzAwy2euwW',NULL,NULL,'cus_UPPeQGvt2AqNvg',NULL,0,'IXlRJjrLQkVg3u63pUH7anstXuiwpC1hRMEFKxBfp9mqisskkZw3iak3toJ5',NULL,'2026-04-26 22:06:37','2026-04-26 22:07:15'),(20,3,'Alex','VELEZ','Alex VELEZ','alexvelez@gmail.com','2026-04-26 22:09:16','$2y$12$kOXVNUSVOkdXuVmAeZ.NVO0mlXE2HaoXu3oPUeW80NMunr3g8nqMy',NULL,NULL,'cus_UPPgRukWtB5lL3',NULL,0,'q3S5zAdRicyCBTVFCvm3gjVrLQhyi55uXJ3QLBBKoDywJQ3FfGc6vWuccQQP',NULL,'2026-04-26 22:08:05','2026-04-26 22:09:16'),(21,1,'John','Arthur','John Arthur','johnarthur@gmail.com','2026-04-27 00:51:05','$2y$12$vjnpA2gNYHAm3gfU9E.6OO.7PlQVdRa.ySOtw6SGeAj3XT8w2Mv9C',NULL,NULL,NULL,NULL,0,'CpFLWA6EWTC4MfhqVS6IbsIFnAQ6WRT0aEEquarmCEpvhiKFUeEGhOKp6q8T',NULL,'2026-04-27 00:50:01','2026-04-28 00:50:48');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `workspace`
--

DROP TABLE IF EXISTS `workspace`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `workspace`
--

LOCK TABLES `workspace` WRITE;
/*!40000 ALTER TABLE `workspace` DISABLE KEYS */;
INSERT INTO `workspace` VALUES (1,1,'default',1,1,'2026-04-25 05:35:34','2026-04-25 05:35:34'),(2,2,'default',1,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(3,3,'default',1,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(4,4,'default',1,1,'2026-04-25 05:35:35','2026-04-25 05:35:35'),(5,6,'default',1,1,'2026-04-25 05:36:19','2026-04-25 05:36:19'),(6,7,'default',1,1,'2026-04-25 07:30:01','2026-04-25 07:30:01'),(7,8,'default',1,1,'2026-04-25 08:16:30','2026-04-25 08:16:30'),(8,9,'default',1,1,'2026-04-26 19:38:06','2026-04-26 19:38:06'),(9,10,'default',1,1,'2026-04-26 19:45:35','2026-04-26 19:45:35'),(10,11,'default',1,1,'2026-04-26 19:53:23','2026-04-26 19:53:23'),(11,12,'default',1,1,'2026-04-26 20:00:08','2026-04-26 20:00:08'),(12,13,'default',1,1,'2026-04-26 20:13:31','2026-04-26 20:13:31'),(13,14,'default',1,1,'2026-04-26 20:53:51','2026-04-26 20:53:51'),(14,15,'default',1,1,'2026-04-26 21:15:14','2026-04-26 21:15:14'),(15,16,'default',1,1,'2026-04-26 21:15:53','2026-04-26 21:15:53'),(16,17,'default',1,1,'2026-04-26 22:01:33','2026-04-26 22:01:33'),(17,18,'default',1,1,'2026-04-26 22:04:52','2026-04-26 22:04:52'),(18,19,'default',1,1,'2026-04-26 22:06:37','2026-04-26 22:06:37'),(19,20,'default',1,1,'2026-04-26 22:08:05','2026-04-26 22:08:05'),(20,21,'default',1,1,'2026-04-27 00:50:01','2026-04-27 00:50:01');
/*!40000 ALTER TABLE `workspace` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'app_db'
--

--
-- Dumping routines for database 'app_db'
--
SET @@SESSION.SQL_LOG_BIN = @MYSQLDUMP_TEMP_LOG_BIN;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-04-28  2:36:25
