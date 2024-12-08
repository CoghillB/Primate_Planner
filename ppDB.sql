-- MySQL dump 10.13  Distrib 8.0.40, for Linux (x86_64)
--
-- Host: localhost    Database: Primate_Planner
-- ------------------------------------------------------
-- Server version	8.0.40-0ubuntu0.22.04.1

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
-- Table structure for table `Goals`
--

DROP TABLE IF EXISTS `Goals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Goals` (
  `mid` int NOT NULL,
  `weight_goal` int DEFAULT NULL,
  `weekly_calories` int DEFAULT NULL,
  `weekly_duration` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`mid`),
  CONSTRAINT `fk_mid` FOREIGN KEY (`mid`) REFERENCES `Members` (`id`),
  CONSTRAINT `Goals_ibfk_1` FOREIGN KEY (`mid`) REFERENCES `Members` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Goals`
--

LOCK TABLES `Goals` WRITE;
/*!40000 ALTER TABLE `Goals` DISABLE KEYS */;
INSERT INTO `Goals` VALUES (14,190,15000,500,'2024-12-07 17:48:24'),(16,190,30000,360,'2024-12-07 20:56:09');
/*!40000 ALTER TABLE `Goals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Members`
--

DROP TABLE IF EXISTS `Members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Members` (
  `id` int NOT NULL AUTO_INCREMENT,
  `fname` varchar(20) NOT NULL,
  `lname` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `weight` int DEFAULT NULL,
  `age` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Members`
--

LOCK TABLES `Members` WRITE;
/*!40000 ALTER TABLE `Members` DISABLE KEYS */;
INSERT INTO `Members` VALUES (3,'John','Doe','jdoe@hotmail.com','$2y$10$CMgsQZb1IvHtYFwuBAdlEOPsSpJf7d8b6mn3DDik62PCHPPDPYHlG',NULL,NULL),(11,'Jane','Doe','jdoe@yahoo.com','$2y$10$FO2IGIob8wpyothHy7GID.afOfeagBvnFmaths/gScj/MuakQ5VJO',NULL,NULL),(12,'Madison','Coghill','madison.coghill@gmail.com','$2y$10$4i75az03gdtosuh76G55I.SVep2Br0AqY/hXwRUjaM8YYBPh8z6s.',NULL,NULL),(14,'Ashton','Irwin','ai@gmail.com','$2y$10$JJZHAoIhNSEs4XcpMSyMcObk5GYqOd7q.FbcrOHsjVKMiypJZnPAC',NULL,NULL),(15,'hello','world','helloworld@gmail.com','$2y$10$zZ7Lkw6aUr4zXeYzfiUR3O4aJ6T6eHKiU29V5D4I2myM.UyUz6r16',NULL,NULL),(16,'Brayden','Coghill','coghill30@gmail.com','$2y$10$wH6ZDtS/5o3jLTMDG9G1Me7FxgH4g6daLN5rL5G1alwdxNh1N.cvq',NULL,NULL);
/*!40000 ALTER TABLE `Members` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `daily_exercises`
--

DROP TABLE IF EXISTS `daily_exercises`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `daily_exercises` (
  `exercise_id` int NOT NULL AUTO_INCREMENT,
  `mid` int DEFAULT NULL,
  `exercise_date` date DEFAULT NULL,
  `weight` int DEFAULT NULL,
  `exercise_type` varchar(50) DEFAULT NULL,
  `duration_minutes` int DEFAULT NULL,
  `calories_burned` int DEFAULT NULL,
  PRIMARY KEY (`exercise_id`),
  KEY `mid` (`mid`),
  CONSTRAINT `daily_exercises_ibfk_1` FOREIGN KEY (`mid`) REFERENCES `Members` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `daily_exercises`
--

LOCK TABLES `daily_exercises` WRITE;
/*!40000 ALTER TABLE `daily_exercises` DISABLE KEYS */;
INSERT INTO `daily_exercises` VALUES (7,16,'2024-12-09',222,'Weightlifting',60,422),(8,16,'2024-12-10',221,'Weightlifting',60,422),(9,16,'2024-12-12',220,'Weightlifting',60,422),(10,16,'2024-12-11',221,'Weightlifting',60,422);
/*!40000 ALTER TABLE `daily_exercises` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-12-07 21:38:22
