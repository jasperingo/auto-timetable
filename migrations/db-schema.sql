-- MySQL dump 10.13  Distrib 8.0.17, for Win64 (x86_64)
--
-- Host: localhost    Database: auto-timetable
-- ------------------------------------------------------
-- Server version	8.0.17

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `course_registrations`
--

DROP TABLE IF EXISTS `course_registrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `course_registrations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `courseId` int(11) NOT NULL,
  `studentId` int(11) NOT NULL,
  `session` year(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `courseId` (`courseId`),
  KEY `studentId` (`studentId`),
  CONSTRAINT `course_registrations_ibfk_1` FOREIGN KEY (`courseId`) REFERENCES `courses` (`id`),
  CONSTRAINT `course_registrations_ibfk_2` FOREIGN KEY (`studentId`) REFERENCES `students` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `course_registrations`
--

LOCK TABLES `course_registrations` WRITE;
/*!40000 ALTER TABLE `course_registrations` DISABLE KEYS */;
INSERT INTO `course_registrations` VALUES (1,1,1,2022),(2,2,1,2022),(3,5,1,2022),(5,1,3,2022),(6,2,3,2022),(7,2,4,2022);
/*!40000 ALTER TABLE `course_registrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `courses`
--

DROP TABLE IF EXISTS `courses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `courses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `departmentId` int(11) NOT NULL,
  `title` text NOT NULL,
  `code` text NOT NULL,
  `semester` enum('first','second') NOT NULL,
  `level` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `departmentId` (`departmentId`),
  CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`departmentId`) REFERENCES `departments` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `courses`
--

LOCK TABLES `courses` WRITE;
/*!40000 ALTER TABLE `courses` DISABLE KEYS */;
INSERT INTO `courses` VALUES (1,1,'Software planning and development','IFT211','first',2),(2,1,'System analysis','IFT203','first',2),(3,2,'Data processing','CSC203','first',2),(4,2,'Telecommunication','CSC204','second',2),(5,1,'Introduction to telecommunication','CSC104','first',1),(6,1,'Advanced telecommunication','CSC303','first',3),(7,3,'Software Analysis','SWE103','first',1);
/*!40000 ALTER TABLE `courses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `departments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `code` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departments`
--

LOCK TABLES `departments` WRITE;
/*!40000 ALTER TABLE `departments` DISABLE KEYS */;
INSERT INTO `departments` VALUES (1,'Information Technology','IFT'),(2,'Computer Science','CSC'),(3,'Software Engineering','SWE');
/*!40000 ALTER TABLE `departments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `examination_halls`
--

DROP TABLE IF EXISTS `examination_halls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `examination_halls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hallId` int(11) NOT NULL,
  `examinationId` int(11) NOT NULL,
  `capacity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `hallId` (`hallId`),
  KEY `examinationId` (`examinationId`),
  CONSTRAINT `examination_halls_ibfk_1` FOREIGN KEY (`hallId`) REFERENCES `halls` (`id`),
  CONSTRAINT `examination_halls_ibfk_2` FOREIGN KEY (`examinationId`) REFERENCES `examinations` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `examination_halls`
--

LOCK TABLES `examination_halls` WRITE;
/*!40000 ALTER TABLE `examination_halls` DISABLE KEYS */;
INSERT INTO `examination_halls` VALUES (1,6,2,3),(2,2,1,2),(3,4,3,1);
/*!40000 ALTER TABLE `examination_halls` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `examination_invigilators`
--

DROP TABLE IF EXISTS `examination_invigilators`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `examination_invigilators` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `staffId` int(11) NOT NULL,
  `examinationId` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `staffId` (`staffId`),
  KEY `examinationId` (`examinationId`),
  CONSTRAINT `examination_invigilators_ibfk_1` FOREIGN KEY (`staffId`) REFERENCES `staffs` (`id`),
  CONSTRAINT `examination_invigilators_ibfk_2` FOREIGN KEY (`examinationId`) REFERENCES `examinations` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `examination_invigilators`
--

LOCK TABLES `examination_invigilators` WRITE;
/*!40000 ALTER TABLE `examination_invigilators` DISABLE KEYS */;
INSERT INTO `examination_invigilators` VALUES (1,3,1),(2,3,2),(3,5,2),(4,5,3);
/*!40000 ALTER TABLE `examination_invigilators` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `examinations`
--

DROP TABLE IF EXISTS `examinations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `examinations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timetableId` int(11) NOT NULL,
  `courseId` int(11) NOT NULL,
  `duration` int(11) NOT NULL,
  `numberOfStudents` int(11) NOT NULL,
  `startAt` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `timetableId` (`timetableId`),
  KEY `courseId` (`courseId`),
  CONSTRAINT `examinations_ibfk_1` FOREIGN KEY (`timetableId`) REFERENCES `timetables` (`id`),
  CONSTRAINT `examinations_ibfk_3` FOREIGN KEY (`courseId`) REFERENCES `courses` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `examinations`
--

LOCK TABLES `examinations` WRITE;
/*!40000 ALTER TABLE `examinations` DISABLE KEYS */;
INSERT INTO `examinations` VALUES (1,1,1,3,2,'2022-11-21 09:00:00'),(2,1,2,3,3,'2022-11-21 14:00:00'),(3,1,5,3,1,'2022-11-21 09:00:00');
/*!40000 ALTER TABLE `examinations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `halls`
--

DROP TABLE IF EXISTS `halls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `halls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `departmentId` int(11) DEFAULT NULL,
  `name` text NOT NULL,
  `capacity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `departmentId` (`departmentId`),
  CONSTRAINT `halls_ibfk_1` FOREIGN KEY (`departmentId`) REFERENCES `departments` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `halls`
--

LOCK TABLES `halls` WRITE;
/*!40000 ALTER TABLE `halls` DISABLE KEYS */;
INSERT INTO `halls` VALUES (1,1,'IFT Audit',2),(2,1,'Hall of fame',2),(3,2,'Hall of flies',1),(4,2,'Hall of fire',1),(5,2,'Hall of flees',1),(6,NULL,'Hall of tests',3),(7,1,'Back side building',2),(8,NULL,'50 Cap',5);
/*!40000 ALTER TABLE `halls` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `staffs`
--

DROP TABLE IF EXISTS `staffs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `staffs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `departmentId` int(11) DEFAULT NULL,
  `title` text,
  `firstName` text NOT NULL,
  `lastName` text NOT NULL,
  `staffNumber` text NOT NULL,
  `password` text NOT NULL,
  `role` enum('admin','exam_officer','course_adviser','invigilator') NOT NULL,
  `createdAt` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `departmentId` (`departmentId`),
  CONSTRAINT `staffs_ibfk_1` FOREIGN KEY (`departmentId`) REFERENCES `departments` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `staffs`
--

LOCK TABLES `staffs` WRITE;
/*!40000 ALTER TABLE `staffs` DISABLE KEYS */;
INSERT INTO `staffs` VALUES (1,NULL,NULL,'John','Doe','01','$2y$13$rzuM7rQDFc6X/oZ2DXpUbOHgfu4ymc1ob2J/Z2PSR.DDQW2cyMDwS','admin','2022-07-15 00:14:23'),(2,1,NULL,'Jane','Doe','02','$2y$13$0z0KQvfWq5BjeJyoSrOuheqgUgYRgiuFfGjkjR0ixNGzzKGZtZrZS','exam_officer','2022-07-19 20:54:29'),(3,1,NULL,'Pater','Doe','03','$2y$13$Ckh6mwyWYKYM0Q6oCjVSWu0UAzchaUbZ.H1ApLJBvNXbdlVki9L9C','invigilator','2022-07-19 21:02:06'),(4,2,NULL,'Bread','Butter','023','$2y$13$/gMUr0Xk0ipD/Nzi4TEPb.z3SvVPPKySO/rVpdNO2gWa2qA8yxhFy','exam_officer','2022-08-23 23:11:16'),(5,2,'Professor','James','Bond','021','$2y$13$JWdxdKPUPRJlZ7c.0fRO1OKopRtcIs6YRokCGXj945L1OyjMWv/KO','invigilator','2022-08-23 23:17:01');
/*!40000 ALTER TABLE `staffs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `students` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `departmentId` int(11) DEFAULT NULL,
  `firstName` text NOT NULL,
  `lastName` text NOT NULL,
  `matriculationNumber` text NOT NULL,
  `password` text NOT NULL,
  `joinedAt` year(4) NOT NULL,
  `createdAt` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `departmentId` (`departmentId`),
  CONSTRAINT `students_ibfk_1` FOREIGN KEY (`departmentId`) REFERENCES `departments` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `students`
--

LOCK TABLES `students` WRITE;
/*!40000 ALTER TABLE `students` DISABLE KEYS */;
INSERT INTO `students` VALUES (1,1,'Rain','Bow','20161994946','$2y$13$4a0/VZdHmVbJwMdC6oUVU.wu4ASoDEwS6JUXKFOZcS.a/Z8rEFD2e',2016,'2022-08-11 20:39:03'),(2,1,'Rag','Day','20171994941','$2y$13$C75bq7V7vqNOXDnKn1P1guuj.v80p5jRIjDteCTvpVRDgs70RVRg2',2017,'2022-08-12 09:46:54'),(3,1,'Brown','Powder','20161924971','$2y$13$rjrfTZetAkB1IquqriTOvu7jyj8QldaptZPuNkIfqLGOfR/fGrxAi',2016,'2022-08-12 10:16:21'),(4,1,'Uber','Bolt','20161994990','$2y$13$GYLQiZPEsHdcp.PpBfC85e7taL2HMnFBogZMuV.P7r3vQUtEgDinC',2016,'2022-08-31 11:45:00'),(5,3,'Facebook','Twitter','20176782891','$2y$13$5fwKaX2LZeQMSiN54a/sU.4arHtbIud47OK/qkE0XseTeoNPbLOVC',2017,'2022-08-31 11:46:02');
/*!40000 ALTER TABLE `students` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `timetables`
--

DROP TABLE IF EXISTS `timetables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `timetables` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session` year(4) NOT NULL,
  `semester` enum('first','second') NOT NULL,
  `createdAt` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `timetables`
--

LOCK TABLES `timetables` WRITE;
/*!40000 ALTER TABLE `timetables` DISABLE KEYS */;
INSERT INTO `timetables` VALUES (1,2022,'first','2022-10-23 13:51:24');
/*!40000 ALTER TABLE `timetables` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-10-31 21:47:39
