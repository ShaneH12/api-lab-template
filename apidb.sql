CREATE DATABASE newapidb;
USE newapidb;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Player_Info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player` varchar(20) NOT NULL,
  `position` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Player_Info`
--

LOCK TABLES `Player_Info` WRITE;
/*!40000 ALTER TABLE `Player_Info` DISABLE KEYS */;
INSERT INTO `Player_Info` VALUES (1,'Carson Wentz', 'QB'),(2, 'Jay Ajayi', 'RB'),(3, 'Alshon Jeffery', 'WR'),(4, 'Fletcher Cox', 'DT'),(5, 'Nigel Bradham', 'LB'),(6, 'Zach Ertz', 'TE');
/*!40000 ALTER TABLE `Player_Info` ENABLE KEYS */;
UNLOCK TABLES;
