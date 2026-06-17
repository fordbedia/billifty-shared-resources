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

--
-- Table structure for table `business_profiles`
--

DROP TABLE IF EXISTS `business_profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `business_profiles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `workspace_id` bigint unsigned NOT NULL,
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
  KEY `business_profiles_workspace_id_foreign` (`workspace_id`),
  CONSTRAINT `business_profiles_workspace_id_foreign` FOREIGN KEY (`workspace_id`) REFERENCES `workspace` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `business_profiles`
--

LOCK TABLES `business_profiles` WRITE;
/*!40000 ALTER TABLE `business_profiles` DISABLE KEYS */;
INSERT INTO `business_profiles` VALUES (1,4,'Test Company LLC','Test Company LLC','test_company_llc@gmail.com','87365245111','','','','129 Bernham street',NULL,'Houston','TX','1222','US','public',NULL,NULL,0,'2026-06-13 08:01:56','2026-06-13 08:01:56',NULL),(2,4,'ILLCity Clothing LLC','ILLCity Clothing LLC','illCityClothing@gmail.com','87365245311','','','','7099 Blair Stone Rd',NULL,'Tallahasse','FL','32301','US','public',NULL,NULL,0,'2026-06-13 08:01:56','2026-06-13 08:01:56',NULL),(3,5,'Iloilo Central Commercial High School','Iloilo Central Commercial High School','enterprise@icchstest.com',NULL,NULL,'EIN-09991117',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'public',NULL,NULL,0,'2026-06-13 08:18:45','2026-06-13 08:18:45',NULL);
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
  `workspace_id` bigint unsigned NOT NULL,
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
  KEY `clients_workspace_id_foreign` (`workspace_id`),
  CONSTRAINT `clients_workspace_id_foreign` FOREIGN KEY (`workspace_id`) REFERENCES `workspace` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clients`
--

LOCK TABLES `clients` WRITE;
/*!40000 ALTER TABLE `clients` DISABLE KEYS */;
INSERT INTO `clients` VALUES (1,4,'John Doe','EvaSoft LLC','johndoe@gmail.com','9876381234','','','7900 S Post Oak',NULL,'Houston','TX','77890','US',NULL,1,'2026-06-13 08:01:56','2026-06-13 08:01:56',NULL),(2,4,'Harry Doe','Wee LLC','harry@gmail.com','9876316234','','','1922 Pleasant Groove Rd',NULL,'Houston','TX','77840','US',NULL,1,'2026-06-13 08:01:56','2026-06-13 08:01:56',NULL),(3,5,'Gladys Chua','Gladys Chua','glads+test@gmail.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,'2026-06-13 08:19:10','2026-06-13 08:19:10',NULL);
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
INSERT INTO `color_scheme` VALUES (1,'Ocean Blue','ocean',NULL,'/images/invoice-selection/ocean-blue.png','2026-06-13 08:01:56','2026-06-13 08:01:56'),(2,'Forest Green','forest',NULL,'/images/invoice-selection/forest-green.png','2026-06-13 08:01:56','2026-06-13 08:01:56'),(3,'Royal Purple','royal',NULL,'/images/invoice-selection/royal-purple.png','2026-06-13 08:01:56','2026-06-13 08:01:56'),(4,'Crimson Red','crimson',NULL,'/images/invoice-selection/crimson-red.png','2026-06-13 08:01:56','2026-06-13 08:01:56'),(5,'Sunset Orange','sunset',NULL,'/images/invoice-selection/sunset-orange.png','2026-06-13 08:01:56','2026-06-13 08:01:56');
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
INSERT INTO `color_scheme_color` VALUES (1,3,'main','#8B5CF6','2026-06-13 08:01:56','2026-06-13 08:01:56'),(2,3,'light','#D8B4FE','2026-06-13 08:01:56','2026-06-13 08:01:56'),(3,3,'extra_light','rgba(253, 242, 248, 0.3)','2026-06-13 08:01:56','2026-06-13 08:01:56'),(4,3,'gradient_bg_1','90deg,rgba(147, 51, 234, 1) 0%, rgba(168, 85, 247, 0.67) 55%, rgba(236, 72, 153, 1) 100%','2026-06-13 08:01:56','2026-06-13 08:01:56'),(5,3,'table_tbody_color','#FDF2F8','2026-06-13 08:01:56','2026-06-13 08:01:56'),(6,3,'gradient_bg_1_light','142deg, rgba(255, 255, 255, 1) 0%, rgba(238, 242, 255, 1) 100%','2026-06-13 08:01:56','2026-06-13 08:01:56'),(7,1,'main','#3B82F6','2026-06-13 08:01:56','2026-06-13 08:01:56'),(8,1,'light','#93C5FD','2026-06-13 08:01:56','2026-06-13 08:01:56'),(9,1,'extra_light','rgba(255, 255, 255, 0.3)','2026-06-13 08:01:56','2026-06-13 08:01:56'),(10,1,'gradient_bg_1','90deg,#020024 0%, #090979 35%, #00D4FF 100%','2026-06-13 08:01:56','2026-06-13 08:01:56'),(11,1,'table_tbody_color','','2026-06-13 08:01:56','2026-06-13 08:01:56'),(12,1,'gradient_bg_1_light','142deg, rgba(255, 255, 255, 1) 0%, rgba(238, 242, 255, 1) 100%','2026-06-13 08:01:56','2026-06-13 08:01:56'),(13,2,'main','#22C55E','2026-06-13 08:01:56','2026-06-13 08:01:56'),(14,2,'light','#86EFAC','2026-06-13 08:01:56','2026-06-13 08:01:56'),(15,2,'extra_light','rgba(255, 255, 255, 0.3)','2026-06-13 08:01:56','2026-06-13 08:01:56'),(16,2,'gradient_bg_1','90deg,#2A7B9B 0%, #57C785 50%, #EDDD53 100%','2026-06-13 08:01:56','2026-06-13 08:01:56'),(17,2,'table_tbody_color','','2026-06-13 08:01:56','2026-06-13 08:01:56'),(18,2,'gradient_bg_1_light','142deg, rgba(255, 255, 255, 1) 0%, rgba(238, 242, 255, 1) 100%','2026-06-13 08:01:56','2026-06-13 08:01:56'),(19,4,'main','#EF4444','2026-06-13 08:01:56','2026-06-13 08:01:56'),(20,4,'light','#FCA5A5','2026-06-13 08:01:56','2026-06-13 08:01:56'),(21,4,'extra_light','rgba(255, 255, 255, 0.3)','2026-06-13 08:01:56','2026-06-13 08:01:56'),(22,4,'gradient_bg_1','90deg,rgba(253, 29, 29, 1) 0%, rgba(252, 176, 69, 0.67) 55%, rgba(235, 143, 143, 1) 79%','2026-06-13 08:01:56','2026-06-13 08:01:56'),(23,4,'table_tbody_color','','2026-06-13 08:01:56','2026-06-13 08:01:56'),(24,4,'gradient_bg_1_light','','2026-06-13 08:01:56','2026-06-13 08:01:56'),(25,5,'main','#F97316','2026-06-13 08:01:56','2026-06-13 08:01:56'),(26,5,'light','#FDBA74','2026-06-13 08:01:56','2026-06-13 08:01:56'),(27,5,'extra_light','rgba(255, 255, 255, 0.3)','2026-06-13 08:01:56','2026-06-13 08:01:56'),(28,5,'gradient_bg_1','142deg,rgba(249, 115, 22, 1) 1%, rgba(253, 186, 116, 1) 100%','2026-06-13 08:01:56','2026-06-13 08:01:56'),(29,5,'table_tbody_color','','2026-06-13 08:01:56','2026-06-13 08:01:56'),(30,5,'gradient_bg_1_light','142deg, rgba(255, 255, 255, 1) 0%, rgba(238, 242, 255, 1) 100%','2026-06-13 08:01:56','2026-06-13 08:01:56');
/*!40000 ALTER TABLE `color_scheme_color` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_messages`
--

DROP TABLE IF EXISTS `contact_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact_messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `contact_messages_user_id_foreign` (`user_id`),
  CONSTRAINT `contact_messages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_messages`
--

LOCK TABLES `contact_messages` WRITE;
/*!40000 ALTER TABLE `contact_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `contact_messages` ENABLE KEYS */;
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
INSERT INTO `currency` VALUES (1,'USD','United States Dollar','$',2,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(2,'EUR','Euro','€',2,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(3,'GBP','British Pound Sterling','£',2,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(4,'JPY','Japanese Yen','¥',0,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(5,'AUD','Australian Dollar','A$',2,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(6,'CAD','Canadian Dollar','C$',2,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(7,'CHF','Swiss Franc','CHF',2,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(8,'CNY','Chinese Yuan Renminbi','¥',2,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(9,'HKD','Hong Kong Dollar','HK$',2,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(10,'NZD','New Zealand Dollar','NZ$',2,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(11,'SGD','Singapore Dollar','S$',2,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(12,'SEK','Swedish Krona','kr',2,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(13,'NOK','Norwegian Krone','kr',2,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(14,'DKK','Danish Krone','kr',2,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(15,'INR','Indian Rupee','₹',2,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(16,'KRW','South Korean Won','₩',0,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(17,'ZAR','South African Rand','R',2,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(18,'BRL','Brazilian Real','R$',2,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(19,'MXN','Mexican Peso','$',2,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(20,'PHP','Philippine Peso','₱',2,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(21,'THB','Thai Baht','฿',2,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(22,'AED','UAE Dirham','د.إ',2,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(23,'SAR','Saudi Riyal','﷼',2,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(24,'TRY','Turkish Lira','₺',2,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(25,'RUB','Russian Ruble','₽',2,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(26,'PLN','Polish Zloty','zł',2,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(27,'HUF','Hungarian Forint','Ft',0,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(28,'CZK','Czech Koruna','Kč',2,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(29,'ILS','Israeli Shekel','₪',2,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(30,'MYR','Malaysian Ringgit','RM',2,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(31,'IDR','Indonesian Rupiah','Rp',0,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(32,'VND','Vietnamese Dong','₫',0,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(33,'PKR','Pakistani Rupee','₨',2,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(34,'BDT','Bangladeshi Taka','৳',2,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(35,'NGN','Nigerian Naira','₦',2,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(36,'EGP','Egyptian Pound','£',2,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(37,'KES','Kenyan Shilling','KSh',2,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(38,'GHS','Ghanaian Cedi','₵',2,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(39,'CLP','Chilean Peso','$',0,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(40,'ARS','Argentine Peso','$',2,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(41,'COP','Colombian Peso','$',2,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(42,'PEN','Peruvian Sol','S/',2,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(43,'UYU','Uruguayan Peso','$U',2,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(44,'TWD','New Taiwan Dollar','NT$',2,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(45,'QAR','Qatari Riyal','﷼',2,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(46,'BHD','Bahraini Dinar','.د.ب',3,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(47,'OMR','Omani Rial','﷼',3,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(48,'KWD','Kuwaiti Dinar','د.ك',3,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(49,'LKR','Sri Lankan Rupee','Rs',2,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(50,'MMK','Myanmar Kyat','K',0,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(51,'NPR','Nepalese Rupee','₨',2,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(52,'BND','Brunei Dollar','B$',2,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(53,'LAK','Lao Kip','₭',2,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(54,'KHR','Cambodian Riel','៛',2,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(55,'MOP','Macanese Pataca','MOP$',2,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(56,'BMD','Bermudian Dollar','$',2,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(57,'JMD','Jamaican Dollar','J$',2,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(58,'TTD','Trinidad and Tobago Dollar','TT$',2,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(59,'BBD','Barbadian Dollar','Bds$',2,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(60,'XOF','West African CFA Franc','CFA',0,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(61,'XAF','Central African CFA Franc','FCFA',0,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(62,'MUR','Mauritian Rupee','₨',2,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(63,'SCR','Seychellois Rupee','₨',2,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(64,'TZS','Tanzanian Shilling','TSh',2,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(65,'UGX','Ugandan Shilling','USh',0,1,'2026-06-13 08:01:56','2026-06-13 08:01:56');
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoice_items`
--

LOCK TABLES `invoice_items` WRITE;
/*!40000 ALTER TABLE `invoice_items` DISABLE KEYS */;
INSERT INTO `invoice_items` VALUES (1,1,1,'User Login Authentication','Create a functinality for the user where they all be needed for verification before they proceed.',1.0000,'',20000,0,0.0000,0.0000,0,20000,NULL,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(2,1,1,'Landing Page Design','Home Page Design',2.0000,'',15050,0,0.0000,0.0000,0,30100,NULL,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(3,1,1,'Logo Design','Logo Design',2.0000,'',5000,0,0.0000,0.0000,0,10000,NULL,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(4,2,1,NULL,'School Hymn Book',500.0000,NULL,4500,0,30.0000,5.0000,78750,1653750,NULL,'2026-06-13 08:20:45','2026-06-13 08:20:45'),(5,3,1,NULL,'School Papers',500.0000,NULL,1240,0,0.0000,5.0000,31000,651000,NULL,'2026-06-13 09:55:02','2026-06-13 09:55:02'),(6,4,1,NULL,'Electric Supply for EE class',2.0000,NULL,21000,0,0.0000,0.0000,0,42000,NULL,'2026-06-14 02:05:29','2026-06-14 02:05:29'),(7,5,1,NULL,'Chalk',100.0000,NULL,1500,0,0.0000,0.0000,0,150000,NULL,'2026-06-14 03:20:08','2026-06-14 03:20:08'),(8,5,2,NULL,'Eraser',30.0000,NULL,2500,0,0.0000,0.0000,0,75000,NULL,'2026-06-14 03:20:08','2026-06-14 03:20:08');
/*!40000 ALTER TABLE `invoice_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoice_payment_reminders`
--

DROP TABLE IF EXISTS `invoice_payment_reminders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `invoice_payment_reminders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint unsigned NOT NULL,
  `invoice_reminder_schedule_id` bigint unsigned NOT NULL,
  `offset_days` int NOT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `scheduled_at` timestamp NULL DEFAULT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `attempts` int unsigned NOT NULL DEFAULT '0',
  `last_error` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoice_payment_reminders_invoice_id_offset_days_unique` (`invoice_id`,`offset_days`),
  KEY `ipr_rules_schedule_fk` (`invoice_reminder_schedule_id`),
  CONSTRAINT `invoice_payment_reminders_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ipr_rules_schedule_fk` FOREIGN KEY (`invoice_reminder_schedule_id`) REFERENCES `invoice_reminder_schedules` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoice_payment_reminders`
--

LOCK TABLES `invoice_payment_reminders` WRITE;
/*!40000 ALTER TABLE `invoice_payment_reminders` DISABLE KEYS */;
INSERT INTO `invoice_payment_reminders` VALUES (1,3,1,-3,'3 days before due date','2026-06-11 09:00:00',NULL,'skipped',0,'Skipped because the scheduled reminder time was already in the past when reminders were generated.','2026-06-13 10:02:27','2026-06-13 10:02:27'),(2,3,1,0,'On due date','2026-06-13 09:00:00','2026-06-13 10:30:25','sent',1,NULL,'2026-06-13 10:02:27','2026-06-13 10:30:25'),(3,3,1,3,'3 days after due date','2026-06-17 09:00:00',NULL,'pending',0,NULL,'2026-06-13 10:02:27','2026-06-13 10:02:27'),(4,3,1,7,'7 days after due date','2026-06-21 09:00:00',NULL,'pending',0,NULL,'2026-06-13 10:02:27','2026-06-13 10:02:27'),(5,5,1,-3,'3 days before due date','2026-06-13 09:00:00','2026-06-14 03:30:16','sent',1,NULL,'2026-06-14 03:21:31','2026-06-14 03:30:16'),(6,5,1,0,'On due date','2026-06-13 09:00:00','2026-06-14 03:45:20','sent',1,NULL,'2026-06-14 03:21:31','2026-06-14 03:45:20'),(7,5,1,3,'3 days after due date','2026-06-13 09:00:00','2026-06-14 06:00:51','sent',2,NULL,'2026-06-14 03:21:31','2026-06-14 06:00:51'),(8,5,1,7,'7 days after due date','2026-06-20 09:00:00','2026-06-14 06:30:59','sent',2,NULL,'2026-06-14 03:21:31','2026-06-14 06:30:59');
/*!40000 ALTER TABLE `invoice_payment_reminders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoice_reminder_schedule_rules`
--

DROP TABLE IF EXISTS `invoice_reminder_schedule_rules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `invoice_reminder_schedule_rules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `invoice_reminder_schedule_id` bigint unsigned NOT NULL,
  `offset_days` int NOT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `channel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'email',
  `sort_order` int unsigned NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `irs_rules_schedule_fk` (`invoice_reminder_schedule_id`),
  CONSTRAINT `irs_rules_schedule_fk` FOREIGN KEY (`invoice_reminder_schedule_id`) REFERENCES `invoice_reminder_schedules` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoice_reminder_schedule_rules`
--

LOCK TABLES `invoice_reminder_schedule_rules` WRITE;
/*!40000 ALTER TABLE `invoice_reminder_schedule_rules` DISABLE KEYS */;
INSERT INTO `invoice_reminder_schedule_rules` VALUES (1,1,-3,'3 days before due date','email',10,1,'2026-06-13 10:02:27','2026-06-13 10:02:27'),(2,1,0,'On due date','email',20,1,'2026-06-13 10:02:27','2026-06-13 10:02:27'),(3,1,3,'3 days after due date','email',30,1,'2026-06-13 10:02:27','2026-06-13 10:02:27'),(4,1,7,'7 days after due date','email',40,1,'2026-06-13 10:02:27','2026-06-13 10:02:27');
/*!40000 ALTER TABLE `invoice_reminder_schedule_rules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoice_reminder_schedules`
--

DROP TABLE IF EXISTS `invoice_reminder_schedules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `invoice_reminder_schedules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `workspace_id` bigint unsigned DEFAULT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'system',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoice_reminder_schedules_user_id_foreign` (`user_id`),
  KEY `invoice_reminder_schedules_workspace_id_foreign` (`workspace_id`),
  CONSTRAINT `invoice_reminder_schedules_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `invoice_reminder_schedules_workspace_id_foreign` FOREIGN KEY (`workspace_id`) REFERENCES `workspace` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoice_reminder_schedules`
--

LOCK TABLES `invoice_reminder_schedules` WRITE;
/*!40000 ALTER TABLE `invoice_reminder_schedules` DISABLE KEYS */;
INSERT INTO `invoice_reminder_schedules` VALUES (1,NULL,NULL,'standard','Standard','system',1,'2026-06-13 10:02:27','2026-06-13 10:02:27');
/*!40000 ALTER TABLE `invoice_reminder_schedules` ENABLE KEYS */;
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
INSERT INTO `invoice_template_categories` VALUES (1,'modern','Modern','/images/invoice-selection/modern.png',1,1,'[]','2026-06-13 08:01:56','2026-06-13 08:01:56'),(2,'classic','Classic','/images/invoice-selection/classic.png',2,1,'[]','2026-06-13 08:01:56','2026-06-13 08:01:56'),(3,'minimal','Minimal','/images/invoice-selection/minimal.png',3,1,'[]','2026-06-13 08:01:56','2026-06-13 08:01:56');
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
INSERT INTO `invoice_templates` VALUES (1,1,'moderno','Moderno',1,'/images/templates/moderno.jpg',1,NULL,'modern.v1.moderno','2026-06-13 08:01:56','2026-06-13 08:01:56'),(2,1,'neo','Neo',1,'/images/templates/neo.jpg',1,NULL,'modern.v1.neo-columns','2026-06-13 08:01:56','2026-06-13 08:01:56'),(3,1,'mono','Mono',1,'/images/templates/mono.jpg',1,NULL,'modern.v1.mono','2026-06-13 08:01:56','2026-06-13 08:01:56'),(4,2,'aurora','Aurora',1,'/images/templates/aurora.jpg',1,NULL,'classic.v1.aurora','2026-06-13 08:01:56','2026-06-13 08:01:56'),(5,2,'ledger','Ledger',1,'/images/templates/ledger.jpg',1,NULL,'classic.v1.ledger','2026-06-13 08:01:56','2026-06-13 08:01:56'),(6,2,'simplifi','Simplifi',1,'/images/templates/simplifi.jpg',1,NULL,'classic.v1.simplifi','2026-06-13 08:01:56','2026-06-13 08:01:56'),(7,3,'nexxus','Nexxus',1,'/images/templates/nexxus.jpg',1,NULL,'minimal.v1.nexxus','2026-06-13 08:01:56','2026-06-13 08:01:56'),(8,3,'pulse','Pulse',1,'/images/templates/pulse.jpg',1,NULL,'minimal.v1.pulse','2026-06-13 08:01:56','2026-06-13 08:01:56');
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
  `client_snapshot` json DEFAULT NULL,
  `business_profile_snapshot` json DEFAULT NULL,
  `payment_information_snapshot` json DEFAULT NULL,
  `payment_reminders_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `invoice_reminder_schedule_id` bigint unsigned DEFAULT NULL,
  `payment_reminders_completed_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoices_invoice_reminder_schedule_id_foreign` (`invoice_reminder_schedule_id`),
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
  CONSTRAINT `invoices_invoice_reminder_schedule_id_foreign` FOREIGN KEY (`invoice_reminder_schedule_id`) REFERENCES `invoice_reminder_schedules` (`id`) ON DELETE SET NULL,
  CONSTRAINT `invoices_invoice_template_id_foreign` FOREIGN KEY (`invoice_template_id`) REFERENCES `invoice_templates` (`id`) ON DELETE CASCADE,
  CONSTRAINT `invoices_workspace_id_foreign` FOREIGN KEY (`workspace_id`) REFERENCES `workspace` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoices`
--

LOCK TABLES `invoices` WRITE;
/*!40000 ALTER TABLE `invoices` DISABLE KEYS */;
INSERT INTO `invoices` VALUES (1,4,1,1,1,1,1,'INV-0001',NULL,NULL,NULL,NULL,NULL,NULL,'draft','test-company-llc',1,NULL,60100,'none',0,0.00,0,0,0.000,0,60100,0,'','',NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(2,5,3,3,7,5,20,'ICCHS-00001',NULL,NULL,'2026-06-13','2026-06-13','2026-06-13',NULL,'issued',NULL,1,NULL,1575000,'per-line',0,0.00,78750,0,0.000,0,1653750,1653750,NULL,NULL,NULL,NULL,'{\"pdf_total_cents\": 1653750, \"pdf_amount_due_cents\": 1653750, \"pdf_payment_link_token\": \"pay_idmxEHakOzHbI2BM2pRI_01KV013BXY5C0K2CP5RXE7JZ2Z\"}','invoice_pdfs/2026/06/icchs_00001_3a30f090.pdf','public',NULL,0,'ready','2026-06-13 09:50:06',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'2026-06-13 08:20:45','2026-06-13 09:50:06'),(3,5,3,3,1,2,20,'ICCHS-00002',NULL,NULL,'2026-06-13','2026-06-13','2026-06-13',NULL,'issued',NULL,1,NULL,620000,'none',0,0.00,31000,0,0.000,0,651000,651000,NULL,NULL,NULL,NULL,'{\"pdf_total_cents\": 651000, \"pdf_amount_due_cents\": 651000, \"pdf_payment_link_token\": \"pay_t6JpCBXZ9b7mfgNXV4JY_01KV06G0QZVWYS9K67NAMXFRWV\"}','invoice_pdfs/2026/06/icchs_00002_878e88ae.pdf','public',NULL,0,'ready','2026-06-13 10:03:07',NULL,NULL,NULL,NULL,1,1,NULL,NULL,'2026-06-13 09:55:02','2026-06-14 02:04:31'),(4,5,3,3,3,2,20,'ICCHS-00003',NULL,NULL,'2026-06-13','2026-06-14',NULL,NULL,'issued',NULL,1,NULL,42000,'none',0,0.00,0,0,0.000,0,42000,42000,NULL,NULL,NULL,NULL,'{\"pdf_total_cents\": 42000, \"pdf_amount_due_cents\": 42000, \"pdf_payment_link_token\": \"pay_DEt9kz33D9AAowghyN8h_01KV1Y0YN5F2WZN8SJQRM2JAV0\"}','invoice_pdfs/2026/06/icchs_00003_7626941c.pdf','public',NULL,0,'ready','2026-06-14 02:32:09',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,'2026-06-14 02:05:29','2026-06-14 02:32:09'),(5,5,3,3,8,2,20,'ICCHS-00004',NULL,NULL,'2026-06-13','2026-06-14','2026-06-20',NULL,'void',NULL,1,NULL,225000,'none',0,0.00,0,0,0.000,0,225000,225000,NULL,NULL,NULL,NULL,'{\"pdf_total_cents\": 225000, \"pdf_amount_due_cents\": 225000, \"pdf_payment_link_token\": \"pay_Yykl5CJ6IatLmEg5YR1q_01KV229MPV95GKHAVMB97NVN7Y\"}','invoice_pdfs/2026/06/icchs_00004_cdb191da.pdf','public','csv-invoices/invoices/2026/6/6/invoice-ICCHS-00004-20260615-090759-a1fb8f1b.csv',0,'ready','2026-06-15 09:07:59',NULL,NULL,NULL,NULL,1,1,'2026-06-14 22:55:11',NULL,'2026-06-14 03:20:08','2026-06-15 09:07:59');
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
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_plans_table',1),(2,'0001_01_01_000000_create_users_table',1),(3,'0001_01_01_000001_create_cache_table',1),(4,'0001_01_01_000001_user_subscriptions',1),(5,'0001_01_01_000002_create_jobs_table',1),(6,'2025_03_25_000806_create_workspace_table',1),(7,'2025_03_26_120000_backfill_invoice_workspaces',1),(8,'2025_10_09_163456_create_oauth_auth_codes_table',1),(9,'2025_10_09_163457_create_oauth_access_tokens_table',1),(10,'2025_10_09_163458_create_oauth_refresh_tokens_table',1),(11,'2025_10_09_163459_create_oauth_clients_table',1),(12,'2025_10_09_163500_create_oauth_device_codes_table',1),(13,'2025_10_19_172528_create_table_payment_information',1),(14,'2025_10_20_034007_create_business_profiles_table',1),(15,'2025_10_20_035753_create_clients_table',1),(16,'2025_10_20_040134_create_invoice_templates_table',1),(17,'2025_10_20_040222_create_invoice_template_versions_table',1),(18,'2025_10_20_040630_create_user_template_settings_table',1),(19,'2025_10_20_041124_create_currency_table',1),(20,'2025_10_20_041125_create_invoice_reminder_schedules',1),(21,'2025_10_20_041125_create_invoices_table',1),(22,'2025_10_20_041828_create_invoice_items_table',1),(23,'2025_10_24_175951_create_color_scheme_color_table',1),(24,'2025_12_10_031329_create_migration_to_seed_plans_table',1),(25,'2025_12_10_032438_create_migration_to_seed_tests_and_categories',1),(26,'2025_12_10_171714_create_table_plan_capabilities',1),(27,'2025_12_10_172917_seed_plan_capabilities',1),(28,'2025_12_15_070922_stripe_webhook_events',1),(29,'2026_03_30_000001_add_ai_invoice_assistant_plan_capability',1),(30,'2026_05_07_182120_create_invoice_payment_link',1),(31,'2026_05_11_063524_create_payment_records',1),(32,'2026_05_13_000001_add_business_profile_id_to_payment_information_table',1),(33,'2026_05_27_000001_create_paypal_webhook_events',1),(34,'2026_06_12_184726_create_contact_messages',1);
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
INSERT INTO `oauth_access_tokens` VALUES ('157fc98433c1550d36e3191198d0b12bad875a7fd4d9032d05ac071bba26a0dbf428fdc94d87487a',6,'019ec00a-194b-708f-95ec-e2bae3b5db68','Billifty Web App','[]',0,'2026-06-13 08:13:24','2026-06-13 08:13:24','2026-12-13 08:13:24');
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
INSERT INTO `oauth_clients` VALUES ('019ec00a-194b-708f-95ec-e2bae3b5db68',NULL,NULL,'Billifty','$2y$12$i86mZ9jYwRHDMjSByY/SiexP0NxtI.JsVitNLkTkjmElUGC42zCHa','users','[]','[\"personal_access\"]',0,'2026-06-13 08:12:27','2026-06-13 08:12:27');
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_information`
--

LOCK TABLES `payment_information` WRITE;
/*!40000 ALTER TABLE `payment_information` DISABLE KEYS */;
INSERT INTO `payment_information` VALUES (1,NULL,'bank_transfer','BoFa','John Doe','123456789','12345678914662',NULL,NULL,NULL,NULL,NULL,0,0,0,NULL,NULL,NULL,NULL,NULL,'Test',1,'2026-06-13 08:01:56','2026-06-13 08:01:56',NULL),(2,3,'bank_transfer','Banco de Oro','Iloilo Central Commercial High School','0988990221','02242111','DE89771YHJ','CHAUI77701',NULL,NULL,NULL,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,'2026-06-13 08:18:45','2026-06-13 08:18:45',NULL),(3,3,'paypal',NULL,NULL,NULL,NULL,NULL,NULL,'6Q2PZJ3KUUJPW','6Q2PZJ3KUUJPW','enterprise@icchstest.com',0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,0,'2026-06-13 08:18:45','2026-06-13 08:18:45',NULL),(4,3,'stripe',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,0,NULL,NULL,'acct_1TUyXrEgxqsR1VDL',NULL,NULL,NULL,0,'2026-06-13 08:18:45','2026-06-13 08:18:45',NULL);
/*!40000 ALTER TABLE `payment_information` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment_link`
--

DROP TABLE IF EXISTS `payment_link`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment_link` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint unsigned NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `paypal_order_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paypal_capture_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `public_token_expires_at` timestamp NULL DEFAULT NULL,
  `public_token_revoked_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payment_link_token_unique` (`token`),
  KEY `payment_link_invoice_id_foreign` (`invoice_id`),
  KEY `payment_link_paypal_order_id_index` (`paypal_order_id`),
  KEY `payment_link_paypal_capture_id_index` (`paypal_capture_id`),
  CONSTRAINT `payment_link_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_link`
--

LOCK TABLES `payment_link` WRITE;
/*!40000 ALTER TABLE `payment_link` DISABLE KEYS */;
INSERT INTO `payment_link` VALUES (1,2,'pay_idmxEHakOzHbI2BM2pRI_01KV013BXY5C0K2CP5RXE7JZ2Z',NULL,NULL,NULL,NULL,'2026-06-20 08:20:45',NULL,'2026-06-13 08:20:45','2026-06-13 08:20:45'),(2,3,'pay_t6JpCBXZ9b7mfgNXV4JY_01KV06G0QZVWYS9K67NAMXFRWV',NULL,NULL,NULL,NULL,'2026-06-20 09:55:02',NULL,'2026-06-13 09:55:02','2026-06-13 09:55:02'),(3,4,'pay_DEt9kz33D9AAowghyN8h_01KV1Y0YN5F2WZN8SJQRM2JAV0',NULL,NULL,NULL,NULL,'2026-06-21 02:05:29',NULL,'2026-06-14 02:05:29','2026-06-14 02:05:29'),(4,5,'pay_Yykl5CJ6IatLmEg5YR1q_01KV229MPV95GKHAVMB97NVN7Y',NULL,NULL,NULL,NULL,'2026-06-21 03:20:08',NULL,'2026-06-14 03:20:08','2026-06-14 03:20:08');
/*!40000 ALTER TABLE `payment_link` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment_records`
--

DROP TABLE IF EXISTS `payment_records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_records`
--

LOCK TABLES `payment_records` WRITE;
/*!40000 ALTER TABLE `payment_records` DISABLE KEYS */;
/*!40000 ALTER TABLE `payment_records` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `paypal_webhook_events`
--

DROP TABLE IF EXISTS `paypal_webhook_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `paypal_webhook_events` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `event_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `resource_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payload` json NOT NULL,
  `received_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `paypal_webhook_events_event_id_unique` (`event_id`),
  KEY `paypal_webhook_events_type_index` (`type`),
  KEY `paypal_webhook_events_resource_id_index` (`resource_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `paypal_webhook_events`
--

LOCK TABLES `paypal_webhook_events` WRITE;
/*!40000 ALTER TABLE `paypal_webhook_events` DISABLE KEYS */;
/*!40000 ALTER TABLE `paypal_webhook_events` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plan_capabilities`
--

LOCK TABLES `plan_capabilities` WRITE;
/*!40000 ALTER TABLE `plan_capabilities` DISABLE KEYS */;
INSERT INTO `plan_capabilities` VALUES (1,1,'max_business_profiles','Business Profiles','int','1',NULL,'businessProfiles',NULL,'limits',1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(2,1,'max_clients','Clients','int','5',NULL,'clients',NULL,'limits',1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(3,1,'max_invoices_per_month','Invoices per month','int','5','{\"usage\": \"monthly\"}','invoices',NULL,'limits',1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(4,1,'pdf_watermark','PDF Watermark','bool','true',NULL,NULL,'“Powered by Billifty” watermark','features',1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(5,1,'email_watermark','Email Watermark','bool','true',NULL,NULL,'Billifty branding in emails','features',1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(6,1,'custom_prefix','Custom Invoice Numbering','bool','false',NULL,NULL,'Basic invoice numbering','features',1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(7,1,'custom_branding','Custom Brand Colors','bool','false',NULL,NULL,'Basic invoice template','features',1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(8,1,'multi_templates','Templates','bool','false',NULL,NULL,'Basic invoice template','features',1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(9,1,'logo_upload','Logo Upload','bool','false',NULL,NULL,NULL,'features',1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(10,1,'automated_reminders','Automated Reminders','string','none',NULL,NULL,NULL,'features',1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(11,1,'online_payments','Online Payments','bool','false',NULL,NULL,NULL,'features',1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(12,1,'multi_currency','Multi-Currency','bool','true',NULL,NULL,'Multi-currency support','features',1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(13,1,'ai_invoice_assistant','AI Invoice Assistant','bool','false',NULL,NULL,NULL,'features',1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(14,1,'analytics_tier','Analytics','string','basic',NULL,NULL,NULL,'features',0,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(15,1,'email_branding','Email Branding','string','billifty_footer',NULL,NULL,NULL,'features',1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(16,1,'templates_tier','Templates','string','basic',NULL,NULL,NULL,'features',1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(17,1,'support_level','Support','string','email',NULL,NULL,NULL,'features',1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(18,1,'cta_text1',NULL,'string','Perfect for trying out Billifty.',NULL,NULL,NULL,'marketing',1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(19,1,'cta_btn',NULL,'string','Get started free',NULL,NULL,NULL,'marketing',1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(20,1,'cta_upper_text',NULL,'string','Start here',NULL,NULL,NULL,'marketing',1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(21,1,'cta_card_label',NULL,'string',NULL,NULL,NULL,NULL,'marketing',1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(22,2,'max_business_profiles','Business Profiles','int','3',NULL,'businessProfiles',NULL,'limits',1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(23,2,'max_clients','Clients','int','0','{\"unlimited\": true}','clients',NULL,'limits',1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(24,2,'max_invoices_per_month','Invoices per month','int','10','{\"usage\": \"monthly\"}','invoices',NULL,'limits',1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(25,2,'pdf_watermark','PDF Watermark','bool','false',NULL,NULL,'No PDF watermark','features',1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(26,2,'email_watermark','Email Watermark','bool','true',NULL,NULL,'Watermark on emails','features',1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(27,2,'custom_prefix','Custom Invoice Numbering','bool','true',NULL,NULL,'Custom invoice numbering','features',1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(28,2,'custom_branding','Custom Brand Colors','bool','true',NULL,NULL,'Custom brand colors','features',1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(29,2,'multi_templates','Templates','bool','true',NULL,NULL,'Multiple invoice templates','features',1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(30,2,'logo_upload','Logo Upload','bool','true',NULL,NULL,'Upload business logo','features',1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(31,2,'automated_reminders','Automated Reminders','string','manual',NULL,NULL,'Manual reminders','features',1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(32,2,'online_payments','Online Payments','bool','false',NULL,NULL,NULL,'features',1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(33,2,'multi_currency','Multi-Currency','bool','true',NULL,NULL,'Multi-currency support','features',1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(34,2,'ai_invoice_assistant','AI Invoice Assistant','bool','true',NULL,NULL,'AI invoice assistant chat','features',1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(35,2,'analytics_tier','Analytics','string','standard',NULL,NULL,NULL,'features',0,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(36,2,'email_branding','Email Branding','string','small_footer',NULL,NULL,NULL,'features',1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(37,2,'templates_tier','Templates','string','multiple',NULL,NULL,NULL,'features',1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(38,2,'support_level','Support','string','email',NULL,NULL,NULL,'features',1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(39,2,'cta_text1',NULL,'string','Everything you need to invoice clients professionally.',NULL,NULL,NULL,'marketing',1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(40,2,'cta_btn',NULL,'string','Upgrade to Pro',NULL,NULL,NULL,'marketing',1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(41,2,'cta_upper_text',NULL,'string',NULL,NULL,NULL,NULL,'marketing',1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(42,2,'cta_card_label',NULL,'string','BEST FOR FREELANCERS',NULL,NULL,NULL,'marketing',1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(43,3,'max_business_profiles','Business Profiles','int','0','{\"unlimited\": true}','businessProfiles',NULL,'limits',1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(44,3,'max_clients','Clients','int','0','{\"unlimited\": true}','clients',NULL,'limits',1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(45,3,'max_invoices_per_month','Invoices per month','int','0','{\"usage\": \"monthly\", \"unlimited\": true}','invoices',NULL,'limits',1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(46,3,'pdf_watermark','PDF Watermark','bool','false',NULL,NULL,'No branding on PDFs','features',1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(47,3,'email_watermark','Email Watermark','bool','false',NULL,NULL,'No branding on emails','features',1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(48,3,'custom_prefix','Custom Invoice Numbering','bool','true',NULL,NULL,'Custom invoice numbering','features',1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(49,3,'custom_branding','Custom Brand Colors','bool','true',NULL,NULL,'Custom brand colors','features',1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(50,3,'multi_templates','Templates','bool','true',NULL,NULL,'All advanced templates','features',1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(51,3,'logo_upload','Logo Upload','bool','true',NULL,NULL,'Upload business logo','features',1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(52,3,'automated_reminders','Automated Reminders','string','automatic',NULL,NULL,'Automated invoice reminders','features',1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(53,3,'online_payments','Online Payments','bool','true',NULL,NULL,'Online payment links','features',1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(54,3,'multi_currency','Multi-Currency','bool','true',NULL,NULL,'Multi-currency support','features',1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(55,3,'ai_invoice_assistant','AI Invoice Assistant','bool','true',NULL,NULL,'AI invoice assistant chat','features',1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(56,3,'analytics_tier','Analytics','string','advanced',NULL,NULL,'Advanced analytics dashboard','features',0,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(57,3,'email_branding','Email Branding','string','none',NULL,NULL,NULL,'features',1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(58,3,'templates_tier','Templates','string','all_advanced',NULL,NULL,NULL,'features',1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(59,3,'support_level','Support','string','priority',NULL,NULL,NULL,'features',1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(60,3,'cta_text1',NULL,'string','Unlimited invoicing with advanced automation.',NULL,NULL,NULL,'marketing',1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(61,3,'cta_btn',NULL,'string','Go Premium',NULL,NULL,NULL,'marketing',1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(62,3,'cta_upper_text',NULL,'string','For growing teams',NULL,NULL,NULL,'marketing',1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(63,3,'cta_card_label',NULL,'string',NULL,NULL,NULL,NULL,'marketing',1,'2026-06-13 08:01:56','2026-06-13 08:01:56');
/*!40000 ALTER TABLE `plan_capabilities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `onboarding`
--

DROP TABLE IF EXISTS `onboarding`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `onboarding` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `is_completed` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `onboarding_user_id_foreign` (`user_id`),
  CONSTRAINT `onboarding_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `onboarding`
--

LOCK TABLES `onboarding` WRITE;
/*!40000 ALTER TABLE `onboarding` DISABLE KEYS */;
/*!40000 ALTER TABLE `onboarding` ENABLE KEYS */;
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
INSERT INTO `plans` VALUES (1,'free','Free','Try Billifty with limited clients and invoices.',0.00,NULL,1,1,'2026-06-13 08:01:54','2026-06-13 08:01:54'),(2,'pro','Pro','For freelancers and small teams.',4.99,49.99,0,2,'2026-06-13 08:01:54','2026-06-13 08:01:54'),(3,'premium','Premium','Unlimited invoicing and automation.',9.99,99.99,0,3,'2026-06-13 08:01:54','2026-06-13 08:01:54');
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_subscriptions`
--

LOCK TABLES `user_subscriptions` WRITE;
/*!40000 ALTER TABLE `user_subscriptions` DISABLE KEYS */;
INSERT INTO `user_subscriptions` VALUES (1,1,1,'free','',NULL,NULL,'usd',NULL,'active',NULL,NULL,NULL,NULL,NULL,'2026-06-13 08:01:55','2026-06-13 08:01:55'),(2,2,2,'pro','',NULL,NULL,'usd',NULL,'active',NULL,NULL,NULL,NULL,NULL,'2026-06-13 08:01:55','2026-06-13 08:01:55'),(3,3,3,'premium','',NULL,NULL,'usd',NULL,'active',NULL,NULL,NULL,NULL,NULL,'2026-06-13 08:01:55','2026-06-13 08:01:55'),(4,4,3,'premium','',NULL,NULL,'usd',NULL,'active',NULL,NULL,NULL,NULL,NULL,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(5,6,1,'free','monthly',NULL,NULL,'usd',NULL,'active',NULL,NULL,NULL,NULL,NULL,'2026-06-13 08:13:24','2026-06-13 08:13:24');
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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,1,NULL,NULL,'John Paine','john+free@billifty.czom',NULL,'$2y$12$7PT7VYN6hMsf2HOAHLA/y.BlM5v5Ino95xUK8FonaSrO3YI3M1D4a',NULL,NULL,NULL,NULL,0,NULL,NULL,'2026-06-13 08:01:55','2026-06-13 08:01:55'),(2,2,NULL,NULL,'Kirk McDonald','kirk+pro@billifty.com',NULL,'$2y$12$daOGbFpq5ovY8z0Z6kIL8u9VQJeeNXcuPKIg8jpbdqUdSHQwczfu2',NULL,NULL,NULL,NULL,0,NULL,NULL,'2026-06-13 08:01:55','2026-06-13 08:01:55'),(3,3,NULL,NULL,'James Harris','james+premium@billifty.com',NULL,'$2y$12$Hi8pjh3e.Tdo/1wMpEsBhunSB3LF65fCi8jfRewwbSXLo8yavaQQ2',NULL,NULL,NULL,NULL,0,NULL,NULL,'2026-06-13 08:01:55','2026-06-13 08:01:55'),(4,3,NULL,NULL,'Ed Bedia','fordbedia@billifty.com',NULL,'$2y$12$IcpkEa8VzjjM7UbKvCO8..VOYoefbTT9mP5HF5pCiClwI55ab2Vwa',NULL,NULL,NULL,NULL,0,NULL,NULL,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(5,1,NULL,NULL,'Test User','test@example.com','2026-06-13 08:01:56','$2y$12$A8M6pH.Na/1pBF30Qr.rCugVHtfrRmSfm3vEfKORI6g3QsUP0byUu',NULL,NULL,NULL,NULL,0,'dIpkeaL0VL',NULL,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(6,1,'Jane','Estrella','Jane Estrella','janeestrella+test1@gmail.com','2026-06-13 08:19:24','$2y$12$AYAgoGFj49m8stbVwond4.9PikHbjso1qv.ZSkTLWBWcmR6R4TQ0e',NULL,NULL,NULL,NULL,0,'k7y1JFeyAUaz0asru3TugmuQcXD1DinWL7akUjFhyL3NphtVSqCFJrRuuESU',NULL,'2026-06-13 08:13:24','2026-06-13 08:19:24');
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `workspace`
--

LOCK TABLES `workspace` WRITE;
/*!40000 ALTER TABLE `workspace` DISABLE KEYS */;
INSERT INTO `workspace` VALUES (1,1,'default',1,1,'2026-06-13 08:01:55','2026-06-13 08:01:55'),(2,2,'default',1,1,'2026-06-13 08:01:55','2026-06-13 08:01:55'),(3,3,'default',1,1,'2026-06-13 08:01:55','2026-06-13 08:01:55'),(4,4,'default',1,1,'2026-06-13 08:01:56','2026-06-13 08:01:56'),(5,6,'default',1,1,'2026-06-13 08:13:24','2026-06-13 08:13:24');
/*!40000 ALTER TABLE `workspace` ENABLE KEYS */;
UNLOCK TABLES;

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
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-06-15  9:16:47
