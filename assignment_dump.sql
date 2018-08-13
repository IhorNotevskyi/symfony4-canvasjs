-- MySQL dump 10.13  Distrib 5.7.22, for Linux (x86_64)
--
-- Host: localhost    Database: assignment
-- ------------------------------------------------------
-- Server version	5.7.22-0ubuntu0.17.10.1

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
-- Table structure for table `interspace`
--

DROP TABLE IF EXISTS `interspace`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `interspace` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `start_date` date NOT NULL,
  `finish_date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_2F77B3F4584665A` (`product_id`),
  CONSTRAINT `FK_2F77B3F4584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `interspace`
--

LOCK TABLES `interspace` WRITE;
/*!40000 ALTER TABLE `interspace` DISABLE KEYS */;
INSERT INTO `interspace` VALUES (11,3,200.00,'2018-08-01','2018-08-09'),(21,1,12000.00,'2016-05-01','2017-01-01'),(22,3,54.00,'2018-08-02','2018-08-11'),(23,3,432.00,'2018-08-02','2018-08-13'),(24,1,15000.00,'2016-07-01','2016-09-10'),(25,1,20000.00,'2017-06-01','2017-10-20'),(27,1,8000.00,'2016-01-01','2050-12-31'),(28,2,1000.00,'2018-08-06','2018-08-11'),(29,3,1.00,'2018-08-15','2018-08-17'),(35,4,1500.00,'2018-08-02','2018-08-14'),(36,4,2000.00,'2018-08-04','2018-08-07'),(37,4,2500.00,'2018-08-06','2018-08-08'),(48,NULL,32131.00,'2015-01-01','2016-03-02');
/*!40000 ALTER TABLE `interspace` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `base_price` decimal(10,2) NOT NULL,
  `created_at` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product`
--

LOCK TABLES `product` WRITE;
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
INSERT INTO `product` VALUES (1,'Школьная форма','Равным образом консультация с широким активом позволяет выполнять важные задания по разработке существенных финансовых и административных условий. Не следует, однако забывать, что укрепление и развитие структуры обеспечивает широкому кругу (специалистов) участие в формировании систем массового участия.',10000.00,'2015-04-27'),(2,'Карнавальные костюмы','Задача организации, в особенности же новая модель организационной деятельности представляет собой интересный эксперимент проверки дальнейших направлений развития. Не следует, однако забывать, что начало повседневной работы по формированию позиции способствует подготовки и реализации модели развития.',777.00,'2018-08-03'),(3,'Обувь','Равным образом укрепление и развитие структуры обеспечивает широкому кругу (специалистов) участие в формировании системы обучения кадров, соответствует насущным потребностям. С другой стороны реализация намеченных плановых заданий играет важную роль в формировании соответствующий условий активизации.',555.00,'2018-07-28'),(4,'Кеды','Не следует, однако забывать, что новая модель организационной деятельности обеспечивает широкому кругу (специалистов) участие в формировании дальнейших направлений развития. Не следует, однако забывать, что постоянное информационно-пропагандистское обеспечение нашей деятельности позволяет выполнять важные задания по разработке форм развития.',1000.00,'2018-08-01');
/*!40000 ALTER TABLE `product` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-08-13  6:29:52
