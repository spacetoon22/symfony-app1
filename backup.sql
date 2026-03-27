-- MySQL dump 10.13  Distrib 8.0.45, for Linux (x86_64)
--
-- Host: localhost    Database: symfony_app
-- ------------------------------------------------------
-- Server version	8.0.45-0ubuntu0.24.04.1

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
-- Table structure for table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctrine_migration_versions`
--

LOCK TABLES `doctrine_migration_versions` WRITE;
/*!40000 ALTER TABLE `doctrine_migration_versions` DISABLE KEYS */;
INSERT INTO `doctrine_migration_versions` VALUES ('DoctrineMigrations\\Version20260217170431','2026-02-24 14:43:02',25),('DoctrineMigrations\\Version20260218113017','2026-02-24 14:43:02',49),('DoctrineMigrations\\Version20260218122033',NULL,NULL),('DoctrineMigrations\\Version20260218141934',NULL,NULL),('DoctrineMigrations\\Version20260223145722',NULL,NULL),('DoctrineMigrations\\Version20260224090825',NULL,NULL),('DoctrineMigrations\\Version20260224102917',NULL,NULL),('DoctrineMigrations\\Version20260224144038',NULL,NULL),('DoctrineMigrations\\Version20260224144256',NULL,NULL),('DoctrineMigrations\\Version20260224144650','2026-02-24 14:46:51',502),('DoctrineMigrations\\Version20260224153732','2026-02-24 15:37:33',21),('DoctrineMigrations\\Version20260226133831','2026-02-26 13:38:32',163),('DoctrineMigrations\\Version20260227134708','2026-02-27 13:47:14',63),('DoctrineMigrations\\Version20260303153821','2026-03-03 15:38:22',28),('DoctrineMigrations\\Version20260304150447','2026-03-04 15:04:54',23),('DoctrineMigrations\\Version20260310142501','2026-03-24 13:40:57',38),('DoctrineMigrations\\Version20260310152622','2026-03-24 13:40:57',61);
/*!40000 ALTER TABLE `doctrine_migration_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `document`
--

DROP TABLE IF EXISTS `document`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `document` (
  `id` int NOT NULL AUTO_INCREMENT,
  `file_name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `uploaded_at` datetime NOT NULL,
  `prospect_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D8698A76D182060A` (`prospect_id`),
  CONSTRAINT `FK_D8698A76D182060A` FOREIGN KEY (`prospect_id`) REFERENCES `prospect` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `document`
--

LOCK TABLES `document` WRITE;
/*!40000 ALTER TABLE `document` DISABLE KEYS */;
/*!40000 ALTER TABLE `document` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dossier`
--

DROP TABLE IF EXISTS `dossier`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dossier` (
  `id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cin` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `email` varchar(180) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `plan` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `beneficiaries` int DEFAULT NULL,
  `conditions` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `premium` double DEFAULT NULL,
  `medical_notes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `commercial_notes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `documents` json DEFAULT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rejection_note` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` datetime NOT NULL,
  `claimed_by_id` int DEFAULT NULL,
  `uploaded_files` json DEFAULT NULL,
  `assigned_to_id` int DEFAULT NULL,
  `type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `payment_method` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duration_months` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_3D48E037F67E7A38` (`claimed_by_id`),
  KEY `IDX_3D48E037F4BD7827` (`assigned_to_id`),
  CONSTRAINT `FK_3D48E037F4BD7827` FOREIGN KEY (`assigned_to_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_3D48E037F67E7A38` FOREIGN KEY (`claimed_by_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dossier`
--

LOCK TABLES `dossier` WRITE;
/*!40000 ALTER TABLE `dossier` DISABLE KEYS */;
INSERT INTO `dossier` VALUES (11,'way2','way2','ae456598','male','2026-02-07','alex@gmail.ma','0600000000','way2','','basic','2026-01-31',1,'diabetes',6,'','','[\"cin\", \"contract\"]','approved',NULL,'2026-02-26 13:45:32',11,'[\"1772113532_726f7cb8_NumPy_Python_DS_2026.pdf\", \"1772113532_cab46071_DEVIS_AKSAM_CASQUE_IPPHONE_.pdf\"]',11,NULL,NULL,NULL,NULL),(12,'karim','li','ae456598','female','6999-05-15','karim@gmai.ma','0600000000','casa','','premium','1899-08-19',3,'hypertension',320,'','','[\"photo\", \"contract\"]','approved',NULL,'2026-02-26 15:51:22',9,'[\"1772121082_215cf502_PW1_-_Class_diagrams.pdf\", \"69a06c2c7cd13_signature.PNG\", \"69a06c2c7ce27_The-Standard-CBIC-Medicaid-Card.png\", \"69a06c3cecbe5_FICHIER DE PASSATIO.pdf\", \"69a06cdf74975_key programme.PNG\"]',9,NULL,NULL,NULL,NULL),(13,'Nada','kira','ab1123','female','1994-03-02','Nada@gmail.ma','0600000055','fes','','family','2026-04-01',1,'other',1500,'Cancer','','[]','approved',NULL,'2026-03-02 11:01:58',11,'[\"69a56ec24f618_The-Standard-CBIC-Medicaid-Card.png\"]',11,NULL,NULL,NULL,NULL),(14,'Mane','kalulu','D6925','male','1980-03-19','mane@gmail.ma','0600000055','casa','','basic','2026-03-19',1,'none',450,'','','[]','rejected','no \r\n','2026-03-02 11:49:00',9,'[\"69a57995e8b0e_signature.PNG\"]',9,NULL,NULL,NULL,NULL),(15,'mer','cedi','CF4598','male','2026-03-27','mer@gmail.ma','0600000000','casa','','standard','2026-03-19',1,'none',260,'',NULL,'[]','review',NULL,'2026-03-02 14:10:13',9,'[]',9,'taxi',NULL,NULL,NULL),(16,'alex','kira','ab1123','male','2026-03-06','alex44@gmail.ma','0600000000','casa','','standard','2026-03-06',1,'diabetes',200,'','','[]','approved',NULL,'2026-03-04 10:40:33',9,'[\"69a80c5609b61_The-Standard-CBIC-Medicaid-Card.png\"]',9,'health',NULL,NULL,NULL),(17,'Ahmed','Test',NULL,NULL,NULL,'ahmed@test.com','+212658398172','Rabat',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'[]','pending',NULL,'2026-03-06 15:33:48',NULL,'[]',NULL,'health',NULL,NULL,NULL),(20,'ahmed','laghzel','ae292777','male','2026-03-01','aminefoot100@gmail.com','+212658398172','sale','','standard','2026-04-01',NULL,NULL,91,NULL,NULL,'[]','approved',NULL,'2026-03-24 13:56:59',9,'[]',9,'taxi','2026-09-30','card',6),(21,'ahmed','laghzel','ae292777','male','2026-03-01','alex@gmail.ma','+212658398172','','','standard','2026-04-01',NULL,NULL,91,NULL,NULL,'[]','approved',NULL,'2026-03-24 14:43:47',9,'[]',9,'taxi','2026-09-30','card',6),(22,'ahmed','laghzel','ae292777','male','2004-01-06','way2@example.ma','+212658398172','casa','','basic','2026-04-01',NULL,NULL,100,NULL,NULL,'[]','approved',NULL,'2026-03-24 15:04:20',9,'[]',9,'taxi','2026-06-30','card',3),(23,'d','d','ae456598','','2026-03-01','alex@gmail.ma','+212658398172','','','basic','2026-04-01',NULL,NULL,100,NULL,NULL,'[]','approved',NULL,'2026-03-24 15:05:39',9,'[\"69c2a8615e107_contract_taxi_000022_laghzel.pdf\"]',9,'taxi','2026-06-30','card',3),(24,'dina','chabil','ae1111','female','2000-01-01','dina.chabil@aksam-assurances.ma','+212695704124','rabat','rabat','basic','2026-04-01',NULL,NULL,100,NULL,NULL,'[]','approved',NULL,'2026-03-25 14:45:04',9,'[\"69c3f53c11ecd_contract_taxi_000024_chabil.pdf\"]',9,'taxi','2026-06-30','card',3),(25,'dina','way2','ae456598','female','2026-03-01','s@gmail.ma','+212658398172','','','basic','2026-04-02',NULL,NULL,100,NULL,NULL,'[]','approved',NULL,'2026-03-27 09:30:29',9,'[]',9,'taxi','2026-07-01','card',3),(26,'dina','canada','ae456598','female','2026-03-01','f@gmail.ma','+212658398172','','','standard','2026-04-01',NULL,NULL,91,NULL,NULL,'[]','approved',NULL,'2026-03-27 09:34:25',9,'[]',9,'taxi','2026-09-30','card',6);
/*!40000 ALTER TABLE `dossier` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prospect`
--

DROP TABLE IF EXISTS `prospect`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `prospect` (
  `id` int NOT NULL AUTO_INCREMENT,
  `firstname` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` int NOT NULL,
  `email` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cin` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prospect`
--

LOCK TABLES `prospect` WRITE;
/*!40000 ALTER TABLE `prospect` DISABLE KEYS */;
/*!40000 ALTER TABLE `prospect` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prospect_user`
--

DROP TABLE IF EXISTS `prospect_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `prospect_user` (
  `prospect_id` int NOT NULL,
  `user_id` int NOT NULL,
  PRIMARY KEY (`prospect_id`,`user_id`),
  KEY `IDX_F7725CE2D182060A` (`prospect_id`),
  KEY `IDX_F7725CE2A76ED395` (`user_id`),
  CONSTRAINT `FK_F7725CE2A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_F7725CE2D182060A` FOREIGN KEY (`prospect_id`) REFERENCES `prospect` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prospect_user`
--

LOCK TABLES `prospect_user` WRITE;
/*!40000 ALTER TABLE `prospect_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `prospect_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `setting`
--

DROP TABLE IF EXISTS `setting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `setting` (
  `id` int NOT NULL AUTO_INCREMENT,
  `company_name` varchar(255) DEFAULT NULL,
  `reg_number` varchar(255) DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `contact_phone` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `address` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `setting`
--

LOCK TABLES `setting` WRITE;
/*!40000 ALTER TABLE `setting` DISABLE KEYS */;
INSERT INTO `setting` VALUES (1,'AssureX',NULL,NULL,NULL,'','','〒163-8001 東京都新宿区西新宿2丁目8番1号');
/*!40000 ALTER TABLE `setting` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(180) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_IDENTIFIER_EMAIL` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'Admin_OSS@gmail.ma','[\"ROLE_ADMIN\"]','$2y$13$ammus8p5bfJmGUfkbKACu.donoreoc5I/cCWVnU.r6N2G456jKrrC','ADMIN',NULL),(4,'user3_OSS@gmail.ma','[\"ROLE_COMMERCIAL\"]','$2y$13$AXZjzTxH3a96MNCkxrF6luq4CmgpYuYyhhWkkVMux/QaTU1LnpKNC','user3',NULL),(9,'Hana@gmail.ma','[\"ROLE_COMMERCIAL\"]','$2y$13$zUZ8TCcsTOKIzZfzcvIfnO9to35DwcBe11Vn2OafxrNK7hQU4NBY2','Hana',NULL),(11,'way2@example.ma','[\"ROLE_COMMERCIAL\"]','$2y$13$Gy2Lrq7EnFHKz/TvBMQDMOnai/.Ct/gchkGbFrTqyXmCJEz29kNVS','way',NULL),(17,'ahmed@gg.gg','[\"ROLE_ADMIN\"]','Ahmed123',NULL,NULL),(18,'cloud@gmail.com','[\"ROLE_COMMERCIAL\"]','$2y$13$Ma7V5OtLZWLmGNsNn2AkjOScmvYBXPzWjKQIEgjuL6PeYFh199HPi','Cloud',NULL);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-03-27 10:47:48
