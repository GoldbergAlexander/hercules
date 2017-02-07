-- MySQL dump 10.13  Distrib 5.7.15, for Linux (x86_64)
--
-- Host: localhost    Database: Revenue
-- ------------------------------------------------------
-- Server version	5.7.15-0ubuntu2

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
-- Table structure for table `Bonus`
--

DROP TABLE IF EXISTS `Bonus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Bonus` (
  `idBonus` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Bon_idRevenueYearly` int(10) unsigned NOT NULL,
  `Date` date NOT NULL,
  `Make` double NOT NULL,
  `Staff` int(10) unsigned NOT NULL,
  `Percent` float NOT NULL,
  PRIMARY KEY (`idBonus`),
  UNIQUE KEY `idBonus_UNIQUE` (`idBonus`),
  KEY `Bon_RevYear_idx` (`Bon_idRevenueYearly`),
  CONSTRAINT `Bon_RevYear` FOREIGN KEY (`Bon_idRevenueYearly`) REFERENCES `RevenueYearly` (`idRevenueYearly`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Bonus`
--

LOCK TABLES `Bonus` WRITE;
/*!40000 ALTER TABLE `Bonus` DISABLE KEYS */;
/*!40000 ALTER TABLE `Bonus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Confirmation`
--

DROP TABLE IF EXISTS `Confirmation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Confirmation` (
  `idConfirmation` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Con_idDailyRevenueEntry` int(10) unsigned DEFAULT NULL,
  `Con_idDepartmentRevenueEntry` int(10) unsigned DEFAULT NULL,
  `Con_idUsers` int(11) DEFAULT NULL,
  `Datetime` datetime DEFAULT NULL,
  `IP` double DEFAULT NULL,
  PRIMARY KEY (`idConfirmation`),
  UNIQUE KEY `idConfirmation_UNIQUE` (`idConfirmation`),
  KEY `Con_DeptRevEntry_idx` (`Con_idDepartmentRevenueEntry`),
  KEY `Con_DailyRevEntry` (`Con_idDailyRevenueEntry`),
  CONSTRAINT `Con_DailyRevEntry` FOREIGN KEY (`Con_idDailyRevenueEntry`) REFERENCES `DailyRevenueEntry` (`idDailyRevenueEntry`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `Con_DeptRevEntry` FOREIGN KEY (`Con_idDepartmentRevenueEntry`) REFERENCES `DepartmentRevenueEntry` (`idDepartmentRevenueEntry`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Confirmation`
--

LOCK TABLES `Confirmation` WRITE;
/*!40000 ALTER TABLE `Confirmation` DISABLE KEYS */;
INSERT INTO `Confirmation` VALUES (1,6,NULL,2,'2016-10-29 10:07:17',2886762241),(2,7,NULL,2,'2016-10-29 10:23:12',2886762241),(3,8,NULL,2,'2016-10-29 10:26:41',2886762241),(6,9,NULL,2,'2016-10-29 11:16:42',2886762241),(8,10,NULL,2,'2016-11-02 07:11:27',2886762241),(9,11,NULL,2,'2016-11-02 07:16:51',2886762241),(10,12,NULL,2,'2016-11-03 10:17:35',2886762241);
/*!40000 ALTER TABLE `Confirmation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `DailyRevenueEntry`
--

DROP TABLE IF EXISTS `DailyRevenueEntry`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `DailyRevenueEntry` (
  `idDailyRevenueEntry` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `DailyRevEntry_idLocation` int(10) unsigned NOT NULL,
  `DailyRevEntry_idDepartment` int(10) unsigned DEFAULT NULL,
  `Date` date NOT NULL,
  `CashCount` int(11) DEFAULT NULL,
  `CheckCount` int(11) DEFAULT NULL,
  `PayoutReceipt` float DEFAULT NULL,
  `CardUnit` float DEFAULT NULL,
  `CashTape` float DEFAULT NULL,
  `CheckTape` float DEFAULT NULL,
  `CardTape` float DEFAULT NULL,
  `TaxTape` float DEFAULT NULL,
  `SalesVoid` float DEFAULT NULL,
  `TaxVoid` float DEFAULT NULL,
  PRIMARY KEY (`idDailyRevenueEntry`),
  UNIQUE KEY `idDailyRevenueEntry_UNIQUE` (`idDailyRevenueEntry`),
  KEY `DailyRevEntry_Loc_idx` (`DailyRevEntry_idLocation`),
  KEY `DailyRevEntry_Dept_idx` (`DailyRevEntry_idDepartment`),
  CONSTRAINT `DailyRevEntry_Dept` FOREIGN KEY (`DailyRevEntry_idDepartment`) REFERENCES `Department` (`idDepartment`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `DailyRevEntry_Loc` FOREIGN KEY (`DailyRevEntry_idLocation`) REFERENCES `Location` (`idLocation`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `DailyRevenueEntry`
--

LOCK TABLES `DailyRevenueEntry` WRITE;
/*!40000 ALTER TABLE `DailyRevenueEntry` DISABLE KEYS */;
INSERT INTO `DailyRevenueEntry` VALUES (1,1,NULL,'2016-11-03',1,NULL,1,1,1,1,1,1,1,1),(2,1,1,'2016-11-03',1,NULL,1,1,1,1,1,1,1,1),(3,1,1,'2016-11-03',1,NULL,1.1,1.1,1.1,1.1,1.1,1.1,1.1,1.1),(4,1,1,'2016-10-20',1,NULL,1,1,1,1,1,1,1,1),(5,1,1,'2016-10-11',1,NULL,1,1,1,1,1,1,1,1),(6,1,1,'2016-10-11',1,NULL,1,1,1,1,1,1,1,1),(7,1,1,'2016-10-29',1,NULL,3,4,4,5,6,6,7,8),(8,1,1,'2016-10-29',1,2,3,4,5,6,7,8,9,10),(9,1,1,'2016-10-29',1,2,3.3,4.4,5.5,6.6,7.7,8.8,9.9,10.1),(10,1,1,'2016-11-02',1,2,3,4,5,6,7,8,9,10),(11,1,1,'2016-11-09',1,2,3,4,5,6,7,8,9,10),(12,1,1,'2016-11-03',1,1,1.1,1.1,1.1,1.1,1.1,1.1,1.1,1.1);
/*!40000 ALTER TABLE `DailyRevenueEntry` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Department`
--

DROP TABLE IF EXISTS `Department`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Department` (
  `idDepartment` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` char(16) NOT NULL,
  PRIMARY KEY (`idDepartment`),
  UNIQUE KEY `idDepartment_UNIQUE` (`idDepartment`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Department`
--

LOCK TABLES `Department` WRITE;
/*!40000 ALTER TABLE `Department` DISABLE KEYS */;
INSERT INTO `Department` VALUES (1,'Test Department');
/*!40000 ALTER TABLE `Department` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `DepartmentRevenueEntry`
--

DROP TABLE IF EXISTS `DepartmentRevenueEntry`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `DepartmentRevenueEntry` (
  `idDepartmentRevenueEntry` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `DeptRevEntry_idLocation` int(10) unsigned NOT NULL,
  `DeptRevEntry_idDepartment` int(10) unsigned NOT NULL,
  `Date` date NOT NULL,
  `Revenue` double NOT NULL,
  PRIMARY KEY (`idDepartmentRevenueEntry`),
  UNIQUE KEY `idDepartmentRevenueEntry_UNIQUE` (`idDepartmentRevenueEntry`),
  KEY `DeptRevEntry_Loc_idx` (`DeptRevEntry_idLocation`),
  KEY `DeptRevEntry_Dept_idx` (`DeptRevEntry_idDepartment`),
  CONSTRAINT `DeptRevEntry_Dept` FOREIGN KEY (`DeptRevEntry_idDepartment`) REFERENCES `Department` (`idDepartment`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `DeptRevEntry_Loc` FOREIGN KEY (`DeptRevEntry_idLocation`) REFERENCES `Location` (`idLocation`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `DepartmentRevenueEntry`
--

LOCK TABLES `DepartmentRevenueEntry` WRITE;
/*!40000 ALTER TABLE `DepartmentRevenueEntry` DISABLE KEYS */;
/*!40000 ALTER TABLE `DepartmentRevenueEntry` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Location`
--

DROP TABLE IF EXISTS `Location`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Location` (
  `idLocation` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` char(16) NOT NULL,
  `Address` char(32) DEFAULT NULL,
  PRIMARY KEY (`idLocation`),
  UNIQUE KEY `idLocation_UNIQUE` (`idLocation`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Location`
--

LOCK TABLES `Location` WRITE;
/*!40000 ALTER TABLE `Location` DISABLE KEYS */;
INSERT INTO `Location` VALUES (1,'Test Location','Test');
/*!40000 ALTER TABLE `Location` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `RevenueMonthly`
--

DROP TABLE IF EXISTS `RevenueMonthly`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `RevenueMonthly` (
  `idRevenueMonthly` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `RevMonth_idLocation` int(10) unsigned DEFAULT NULL,
  `RevMonth_idDepartment` int(10) unsigned DEFAULT NULL,
  `Date` date NOT NULL,
  `Revenue` double NOT NULL,
  PRIMARY KEY (`idRevenueMonthly`),
  UNIQUE KEY `idRevenueMonthly_UNIQUE` (`idRevenueMonthly`),
  KEY `RevMonth_Loc_idx` (`RevMonth_idLocation`),
  KEY `RevMonth_Dept_idx` (`RevMonth_idDepartment`),
  CONSTRAINT `RevMonth_Dept` FOREIGN KEY (`RevMonth_idDepartment`) REFERENCES `Department` (`idDepartment`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `RevMonth_Loc` FOREIGN KEY (`RevMonth_idLocation`) REFERENCES `Location` (`idLocation`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `RevenueMonthly`
--

LOCK TABLES `RevenueMonthly` WRITE;
/*!40000 ALTER TABLE `RevenueMonthly` DISABLE KEYS */;
/*!40000 ALTER TABLE `RevenueMonthly` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `RevenueYearly`
--

DROP TABLE IF EXISTS `RevenueYearly`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `RevenueYearly` (
  `idRevenueYearly` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `RevYear_idLocation` int(10) unsigned DEFAULT NULL,
  `RevYear_idDepartment` int(10) unsigned DEFAULT NULL,
  `Date` date DEFAULT NULL,
  `Revenue Double` double NOT NULL,
  PRIMARY KEY (`idRevenueYearly`),
  UNIQUE KEY `idRevenueYearly_UNIQUE` (`idRevenueYearly`),
  KEY `RevYear_Loc_idx` (`RevYear_idLocation`),
  KEY `RevYear_Dept_idx` (`RevYear_idDepartment`),
  CONSTRAINT `RevYear_Dept` FOREIGN KEY (`RevYear_idDepartment`) REFERENCES `Department` (`idDepartment`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `RevYear_Loc` FOREIGN KEY (`RevYear_idLocation`) REFERENCES `Location` (`idLocation`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `RevenueYearly`
--

LOCK TABLES `RevenueYearly` WRITE;
/*!40000 ALTER TABLE `RevenueYearly` DISABLE KEYS */;
/*!40000 ALTER TABLE `RevenueYearly` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Signature`
--

DROP TABLE IF EXISTS `Signature`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Signature` (
  `idSignature` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Sig_idConfirmation` int(10) unsigned NOT NULL,
  `Data` blob NOT NULL,
  PRIMARY KEY (`idSignature`),
  UNIQUE KEY `idSignature_UNIQUE` (`idSignature`),
  KEY `Sig_Con_idx` (`Sig_idConfirmation`),
  CONSTRAINT `Sig_Con` FOREIGN KEY (`Sig_idConfirmation`) REFERENCES `Confirmation` (`idConfirmation`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Signature`
--

LOCK TABLES `Signature` WRITE;
/*!40000 ALTER TABLE `Signature` DISABLE KEYS */;
/*!40000 ALTER TABLE `Signature` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Size`
--

DROP TABLE IF EXISTS `Size`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Size` (
  `idSize` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Size_idDepartment` int(10) unsigned NOT NULL,
  `Size_idLocation` int(10) unsigned NOT NULL,
  `Size` int(10) unsigned NOT NULL,
  `Date` date NOT NULL,
  `Location_idLocation` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idSize`,`Location_idLocation`),
  UNIQUE KEY `idSize_UNIQUE` (`idSize`),
  KEY `Size_Dept_idx` (`Size_idDepartment`),
  KEY `Size_Loc_idx` (`Size_idLocation`),
  CONSTRAINT `Size_Dept` FOREIGN KEY (`Size_idDepartment`) REFERENCES `Department` (`idDepartment`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `Size_Loc` FOREIGN KEY (`Size_idLocation`) REFERENCES `Location` (`idLocation`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Size`
--

LOCK TABLES `Size` WRITE;
/*!40000 ALTER TABLE `Size` DISABLE KEYS */;
/*!40000 ALTER TABLE `Size` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `UserSignature`
--

DROP TABLE IF EXISTS `UserSignature`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `UserSignature` (
  `idUserSignature` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `UserSig_idUsers` int(10) unsigned NOT NULL,
  `Data` blob NOT NULL,
  PRIMARY KEY (`idUserSignature`),
  UNIQUE KEY `idUserSignature_UNIQUE` (`idUserSignature`),
  KEY `Sig_User_idx` (`UserSig_idUsers`),
  CONSTRAINT `Sig_User` FOREIGN KEY (`UserSig_idUsers`) REFERENCES `Users` (`idUsers`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `UserSignature`
--

LOCK TABLES `UserSignature` WRITE;
/*!40000 ALTER TABLE `UserSignature` DISABLE KEYS */;
/*!40000 ALTER TABLE `UserSignature` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Users`
--

DROP TABLE IF EXISTS `Users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Users` (
  `idUsers` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Username` char(16) NOT NULL,
  `Hash` char(64) DEFAULT NULL,
  `Level` int(11) DEFAULT NULL,
  PRIMARY KEY (`idUsers`),
  UNIQUE KEY `idUsers_UNIQUE` (`idUsers`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Users`
--

LOCK TABLES `Users` WRITE;
/*!40000 ALTER TABLE `Users` DISABLE KEYS */;
INSERT INTO `Users` VALUES (2,'Test','$2y$10$gTahaXfwZQPtWjUGmkReVezZdv7jE3LT/hhfK7jYaVqOwd6jUZMDW',0);
/*!40000 ALTER TABLE `Users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-11-03 11:47:02
