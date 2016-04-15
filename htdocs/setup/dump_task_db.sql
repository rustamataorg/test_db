-- MySQL dump 10.13  Distrib 5.7.9, for Win64 (x86_64)
--
-- Host: localhost    Database: task_db
-- ------------------------------------------------------
-- Server version	5.7.11-log

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
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clients` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `iin` varchar(12) NOT NULL,
  `lastname` varchar(45) NOT NULL,
  `name` varchar(45) NOT NULL,
  `surname` varchar(45) DEFAULT NULL COMMENT 'Отчество сделано необязательным для для случаев, когда фамилия даётся по отцу, а отчества нет',
  `mobile_phone` varchar(20) NOT NULL,
  `birthdate` date NOT NULL,
  `gender` tinyint(1) unsigned NOT NULL COMMENT 'М <> 0\nЖ = 0',
  `document_number` varchar(45) NOT NULL,
  `document_issued_by` varchar(64) NOT NULL,
  `document_issue_date` date NOT NULL,
  `document_valid_until` date NOT NULL,
  `salary` int(11) unsigned NOT NULL,
  `spendings` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `iin_UNIQUE` (`iin`),
  UNIQUE KEY `document_number_UNIQUE` (`document_number`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='Справочник клиентов';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clients`
--

LOCK TABLES `clients` WRITE;
/*!40000 ALTER TABLE `clients` DISABLE KEYS */;
INSERT INTO `clients` VALUES (1,'444444444444','Иван','Иванович','Иванович','+7 (701) 222-3355','1982-05-05',1,'docnum12345666','Министерство Юстиции Республики Казахстан','2005-04-04','2021-06-06',150000,100000),(6,'123456789011','Петров','Пётр','Петрович','+7 (705) 333-4455','1981-01-01',1,'ждлфоыва','ждолфыва','1991-01-01','2022-02-02',200000,100000),(8,'222222222222','Васечкин','Пётр','Иванович','+7 (705) 999-9999','2001-01-01',1,'докнум','МЮ РК','2002-01-01','2003-02-02',200000,100000),(9,'333333333333','Иванченко','Иван','Иваныч','+7 (747) 888-8888','1981-01-01',1,'докн123','МВД РК','2001-01-01','2022-02-02',350000,100000);
/*!40000 ALTER TABLE `clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `credit_application_status`
--

DROP TABLE IF EXISTS `credit_application_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `credit_application_status` (
  `id` tinyint(1) unsigned NOT NULL,
  `value` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `credit_application_status`
--

LOCK TABLES `credit_application_status` WRITE;
/*!40000 ALTER TABLE `credit_application_status` DISABLE KEYS */;
INSERT INTO `credit_application_status` VALUES (0,'Отказано'),(1,'Одобрено'),(2,'Отмена');
/*!40000 ALTER TABLE `credit_application_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `credit_applications`
--

DROP TABLE IF EXISTS `credit_applications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `credit_applications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int(10) unsigned NOT NULL COMMENT 'Клиент из справочника clients',
  `amount` float unsigned DEFAULT NULL COMMENT 'Сумма кредитования',
  `credit_period_id` smallint(5) unsigned DEFAULT NULL COMMENT 'Период кредитования',
  `rate` int(10) unsigned DEFAULT NULL COMMENT 'Процентная ставка',
  `status_id` tinyint(1) unsigned NOT NULL COMMENT 'Статус заявки',
  `status_reason` varchar(128) DEFAULT NULL COMMENT 'Причина или дополнительные комментарии, связанные со статусом',
  `application_date` date NOT NULL COMMENT 'Дата подачи заявки на получение кредита (устанавливается автоматически триггером)',
  PRIMARY KEY (`id`),
  KEY `fk_credit_applications_to_clients_idx` (`client_id`),
  KEY `fk_credit_applications_to_period_idx` (`credit_period_id`),
  KEY `fk_credit_applications_to_app_status_idx` (`status_id`),
  CONSTRAINT `fk_credit_applications_to_app_status` FOREIGN KEY (`status_id`) REFERENCES `credit_application_status` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_credit_applications_to_clients` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_credit_applications_to_period` FOREIGN KEY (`credit_period_id`) REFERENCES `credit_periods` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8 COMMENT='Таблица с заявками на получение кредита';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `credit_applications`
--

LOCK TABLES `credit_applications` WRITE;
/*!40000 ALTER TABLE `credit_applications` DISABLE KEYS */;
INSERT INTO `credit_applications` VALUES (1,1,900000,3,11,0,'Недостаточный доход для покрытия кредита в указанный период','2016-04-09'),(2,1,1000000,3,10,0,'Недостаточный доход для покрытия кредита в указанный период','2016-04-08'),(3,1,1000000,6,9,1,'','2016-04-13'),(5,6,1000000,6,11,0,'Ежемесячной остаточной суммы недостаточно для гашения кредита','2016-04-13'),(44,1,10000000,3,9,0,'Ежемесячных остаточных средств недостаточно для выплаты','2016-04-15'),(45,1,10000,3,9,1,'','2016-04-15'),(53,8,88884,6,10,1,'','2016-04-15'),(54,9,NULL,NULL,NULL,2,NULL,'2016-04-15'),(55,9,NULL,NULL,NULL,2,NULL,'2016-04-15'),(58,9,1000000,15,9,1,'','2016-04-15');
/*!40000 ALTER TABLE `credit_applications` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `task_db`.`credit_applications_BEFORE_INSERT` BEFORE INSERT ON `credit_applications` FOR EACH ROW
BEGIN
	SET NEW.application_date = IFNULL(NEW.application_date, NOW());
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `credit_periods`
--

DROP TABLE IF EXISTS `credit_periods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `credit_periods` (
  `id` smallint(5) unsigned NOT NULL,
  `value` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `description_UNIQUE` (`value`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Справочник сроков кредитования';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `credit_periods`
--

LOCK TABLES `credit_periods` WRITE;
/*!40000 ALTER TABLE `credit_periods` DISABLE KEYS */;
INSERT INTO `credit_periods` VALUES (12,'12 месяцев'),(15,'15 месяцев'),(18,'18 месяцев'),(21,'21 месяц'),(24,'24 месяца'),(3,'3 месяца'),(6,'6 месяцев'),(9,'9 месяцев');
/*!40000 ALTER TABLE `credit_periods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  `name` varchar(45) NOT NULL,
  `lastname` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login_UNIQUE` (`login`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='Справочник пользователей системы';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'rustam','202cb962ac59075b964b07152d234b70','Рустам','Хакимжанов'),(2,'ruslan','202cb962ac59075b964b07152d234b70','Руслан','Бурабаев');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'task_db'
--
/*!50003 DROP PROCEDURE IF EXISTS `addClient` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `addClient`(
in in_iin varchar(12)
,in in_lastname varchar(45)
,in in_name varchar(45)
,in in_surname varchar(45)
,in in_mobile_phone varchar(20)
,in in_birthdate date
,in in_gender tinyint(1) unsigned
,in in_document_number varchar(45)
,in in_document_issued_by varchar(64)
,in in_document_issue_date date
,in in_document_valid_until date
,in in_salary int(11) unsigned
,in in_spendings int(11) unsigned
,out out_id int(10) unsigned)
BEGIN
	INSERT INTO clients (
		iin
		,lastname
		,name
		,surname
		,mobile_phone
		,birthdate
		,gender
		,document_number
		,document_issued_by
		,document_issue_date
		,document_valid_until
		,salary
		,spendings
    ) VALUES (
    in_iin
	,in_lastname
	,in_name
	,in_surname
	,in_mobile_phone
	,in_birthdate
	,in_gender
	,in_document_number
	,in_document_issued_by
	,in_document_issue_date
	,in_document_valid_until
	,in_salary
	,in_spendings
    );
    SELECT LAST_INSERT_ID() INTO out_id;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `addCreditApplication` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `addCreditApplication`(
	in in_client_id int(10) unsigned
  , in in_amount int(10) unsigned
  , in in_credit_period_id smallint(5) unsigned
  , in in_rate int(10) unsigned
  , in in_status_id tinyint(1) unsigned
  , in in_status_reason varchar(128)
)
BEGIN
	insert into credit_applications (
		client_id,
        amount,
        credit_period_id,
        rate,
        status_id,
        status_reason)
	values (
		in_client_id,
        in_amount,
        in_credit_period_id,
        in_rate,
        in_status_id,
        in_status_reason
        );
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `cancelCreditApplication` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `cancelCreditApplication`(
	in in_client_id int(10) unsigned
)
BEGIN
	insert into credit_applications (
		client_id,
        status_id
)
	values (
		in_client_id,
        2
        );
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `getAuthorizationStatus` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `getAuthorizationStatus`(in in_login varchar(45), in in_md5 varchar(45),out outId int)
BEGIN
	select id INTO outId from users where login=in_login and password=in_md5;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `getClientById` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `getClientById`(in in_id int(10) unsigned)
BEGIN
SELECT 
id
,iin
,lastname
,name
,surname
,mobile_phone
,birthdate
,gender
,document_number
,document_issued_by
,document_issue_date
,document_valid_until
,salary salary
,spendings spendings
FROM clients
where id=in_id;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `getClientByIIN` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `getClientByIIN`(in in_iin varchar(12))
BEGIN
SELECT 
iin
,lastname
,name
,surname
,mobile_phone
,birthdate
,gender
,document_number
,document_issued_by
,document_issue_date
,document_valid_until
,salary salary
,spendings spendings
FROM clients
where iin=in_iin;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `getClientCreditApplications` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `getClientCreditApplications`(in in_iin varchar(12))
    READS SQL DATA
BEGIN
SET @rownum:=0;
SELECT
@rownum:=@rownum+1 as rank
,c.id
,c.iin
,c.lastname
,c.name
,c.surname
,c.mobile_phone
,c.birthdate
,c.gender
,c.document_number
,c.document_issued_by
,c.document_issue_date
,c.document_valid_until
,c.salary salary
,c.spendings spendings
,a.application_date
,a.amount application_amount
,cp.id application_period_id
,cp.value application_period_value
,cas.value application_status
FROM credit_applications a left join clients c on a.client_id=c.id
left join credit_periods cp on a.credit_period_id=cp.id
left join credit_application_status cas on a.status_id=cas.id
where c.iin=in_iin
order by a.application_date,rank;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `getCreditPeriods` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `getCreditPeriods`()
BEGIN
select 
	id
    ,value 
from task_db.credit_periods 
order by id;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `updateClientInfo` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `updateClientInfo`(
in in_id int(10) unsigned
,in in_iin varchar(12)
,in in_lastname varchar(45)
,in in_name varchar(45)
,in in_surname varchar(45)
,in in_mobile_phone varchar(20)
,in in_birthdate date
,in in_gender tinyint(1) unsigned
,in in_document_number varchar(45)
,in in_document_issued_by varchar(64)
,in in_document_issue_date date
,in in_document_valid_until date
,in in_salary int(11) unsigned
,in in_spendings int(11) unsigned)
BEGIN
	UPDATE clients SET
    iin=in_iin
	,lastname=in_lastname
	,name=in_name
	,surname=in_surname
	,mobile_phone=in_mobile_phone
	,birthdate=in_birthdate
	,gender=in_gender
	,document_number=in_document_number
	,document_issued_by=in_document_issued_by
	,document_issue_date=in_document_issue_date
	,document_valid_until=in_document_valid_until
	,salary=in_salary
	,spendings=in_spendings
	WHERE id=in_id;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-04-15  8:21:38
