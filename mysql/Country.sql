CREATE DATABASE  IF NOT EXISTS `FBT` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `FBT`;
-- MySQL dump 10.13  Distrib 5.5.37, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: FBT
-- ------------------------------------------------------
-- Server version	5.5.37-0ubuntu0.12.04.1

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
-- Table structure for table `FBT_Continent`
--

DROP TABLE IF EXISTS `FBT_Continent`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `FBT_Continent` (
  `idContinent` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idContinent`),
  UNIQUE KEY `idContinent_UNIQUE` (`idContinent`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `FBT_Continent`
--

LOCK TABLES `FBT_Continent` WRITE;
/*!40000 ALTER TABLE `FBT_Continent` DISABLE KEYS */;
INSERT INTO `FBT_Continent` VALUES (1,'AFRIQUE'),(2,'AMERIQUE'),(3,'ASIE'),(4,'EUROPE'),(5,'ASIE');
/*!40000 ALTER TABLE `FBT_Continent` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `FBT_Countries`
--

DROP TABLE IF EXISTS `FBT_Countries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `FBT_Countries` (
  `idCountries` smallint(5) NOT NULL AUTO_INCREMENT,
  `iso_num` int(3) NOT NULL,
  `iso_alpha2` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `iso_alpha3` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `name_fr` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `name_en` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `idContinent` int(11) NOT NULL,
  PRIMARY KEY (`idCountries`),
  UNIQUE KEY `idCountries_UNIQUE` (`idCountries`),
  UNIQUE KEY `iso_num_UNIQUE` (`iso_num`),
  UNIQUE KEY `iso_alpha2_UNIQUE` (`iso_alpha2`),
  UNIQUE KEY `iso_alpha3_UNIQUE` (`iso_alpha3`),
  KEY `fk_FBT_Countries_FBT_Continent1` (`idContinent`),
  CONSTRAINT `fk_FBT_Countries_FBT_Continent1` FOREIGN KEY (`idContinent`) REFERENCES `FBT_Continent` (`idContinent`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=218 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `FBT_Countries`
--

LOCK TABLES `FBT_Countries` WRITE;
/*!40000 ALTER TABLE `FBT_Countries` DISABLE KEYS */;
INSERT INTO `FBT_Countries` VALUES (1,4,'AF','AFG','Afghanistan','Afghanistan',3),(2,8,'AL','ALB','Albanie','Albania',4),(3,12,'DZ','DZA','Algérie','Algeria',1),(4,16,'AS','ASM','Samoa Américaines','American Samoa',2),(5,20,'AD','AND','Andorre','Andorra',4),(6,24,'AO','AGO','Angola','Angola',1),(7,28,'AG','ATG','Antigua-et-Barbuda','Antigua and Barbuda',2),(8,31,'AZ','AZE','Azerbaïdjan','Azerbaijan',3),(9,32,'AR','ARG','Argentine','Argentina',2),(10,36,'AU','AUS','Australie','Australia',5),(11,40,'AT','AUT','Autriche','Austria',4),(12,44,'BS','BHS','Bahamas','Bahamas',2),(13,48,'BH','BHR','Bahreïn','Bahrain',3),(14,50,'BD','BGD','Bangladesh','Bangladesh',3),(15,51,'AM','ARM','Arménie','Armenia',3),(16,52,'BB','BRB','Barbade','Barbados',2),(17,56,'BE','BEL','Belgique','Belgium',4),(18,60,'BM','BMU','Bermudes','Bermuda',2),(19,64,'BT','BTN','Bhoutan','Bhutan',3),(20,68,'BO','BOL','Bolivie','Bolivia',2),(21,70,'BA','BIH','Bosnie-Herzégovine','Bosnia and Herzegovina',4),(22,72,'BW','BWA','Botswana','Botswana',1),(23,76,'BR','BRA','Brésil','Brazil',2),(24,84,'BZ','BLZ','Belize','Belize',2),(25,90,'SB','SLB','Îles Salomon','Solomon Islands',5),(26,92,'VG','VGB','Îles Vierges Britanniques','British Virgin Islands',2),(27,96,'BN','BRN','Brunéi Darussalam','Brunei Darussalam',3),(28,100,'BG','BGR','Bulgarie','Bulgaria',4),(29,108,'BI','BDI','Burundi','Burundi',1),(30,112,'BY','BLR','Bélarus','Belarus',4),(31,116,'KH','KHM','Cambodge','Cambodia',3),(32,120,'CM','CMR','Cameroun','Cameroon',1),(33,124,'CA','CAN','Canada','Canada',2),(34,132,'CV','CPV','Cap-vert','Cape Verde',1),(35,136,'KY','CYM','Îles Caïmanes','Cayman Islands',2),(36,140,'CF','CAF','République Centrafricaine','Central African',1),(37,144,'LK','LKA','Sri Lanka','Sri Lanka',3),(38,148,'TD','TCD','Tchad','Chad',1),(39,152,'CL','CHL','Chili','Chile',2),(40,156,'CN','CHN','Chine','China',3),(41,158,'TW','TWN','Taïwan','Taiwan',3),(42,170,'CO','COL','Colombie','Colombia',2),(43,174,'KM','COM','Comores','Comoros',1),(44,175,'YT','MYT','Mayotte','Mayotte',1),(45,178,'CG','COG','République du Congo','Republic of the Congo',1),(46,180,'CD','COD','République Démocratique du Congo','The Democratic Republic Of The Congo',1),(47,184,'CK','COK','Îles Cook','Cook Islands',5),(48,188,'CR','CRI','Costa Rica','Costa Rica',2),(49,191,'HR','HRV','Croatie','Croatia',4),(50,192,'CU','CUB','Cuba','Cuba',2),(51,196,'CY','CYP','Chypre','Cyprus',4),(52,203,'CZ','CZE','République Tchèque','Czech Republic',4),(53,204,'BJ','BEN','Bénin','Benin',1),(54,208,'DK','DNK','Danemark','Denmark',4),(55,212,'DM','DMA','Dominique','Dominica',2),(56,214,'DO','DOM','République Dominicaine','Dominican Republic',2),(57,218,'EC','ECU','Équateur','Ecuador',2),(58,222,'SV','SLV','El Salvador','El Salvador',2),(59,226,'GQ','GNQ','Guinée Équatoriale','Equatorial Guinea',1),(60,231,'ET','ETH','Éthiopie','Ethiopia',1),(61,232,'ER','ERI','Érythrée','Eritrea',1),(62,233,'EE','EST','Estonie','Estonia',4),(63,239,'GS','SGS','Géorgie du Sud et les Îles Sandwich du Sud','South Georgia and the South Sandwich Islands',2),(64,242,'FJ','FJI','Fidji','Fiji',5),(65,246,'FI','FIN','Finlande','Finland',4),(66,250,'FR','FRA','France','France',4),(67,254,'GF','GUF','Guyane Française','French Guiana',2),(68,258,'PF','PYF','Polynésie Française','French Polynesia',5),(69,262,'DJ','DJI','Djibouti','Djibouti',1),(70,266,'GA','GAB','Gabon','Gabon',1),(71,268,'GE','GEO','Géorgie','Georgia',3),(72,270,'GM','GMB','Gambie','Gambia',1),(73,276,'DE','DEU','Allemagne','Germany',4),(74,288,'GH','GHA','Ghana','Ghana',1),(75,296,'KI','KIR','Kiribati','Kiribati',5),(76,300,'GR','GRC','Grèce','Greece',4),(77,308,'GD','GRD','Grenade','Grenada',2),(78,312,'GP','GLP','Guadeloupe','Guadeloupe',2),(79,316,'GU','GUM','Guam','Guam',5),(80,320,'GT','GTM','Guatemala','Guatemala',2),(81,324,'GN','GIN','Guinée','Guinea',1),(82,328,'GY','GUY','Guyana','Guyana',2),(83,332,'HT','HTI','Haïti','Haiti',2),(84,336,'VA','VAT','Saint-Siège (état de la Cité du Vatican)','Vatican City State',4),(85,340,'HN','HND','Honduras','Honduras',2),(86,344,'HK','HKG','Hong-Kong','Hong Kong',3),(87,348,'HU','HUN','Hongrie','Hungary',4),(88,352,'IS','ISL','Islande','Iceland',4),(89,356,'IN','IND','Inde','India',3),(90,360,'ID','IDN','Indonésie','Indonesia',3),(91,364,'IR','IRN','République Islamique d\'Iran','Islamic Republic of Iran',3),(92,368,'IQ','IRQ','Iraq','Iraq',3),(93,372,'IE','IRL','Irlande','Ireland',4),(94,376,'IL','ISR','Israël','Israel',3),(95,380,'IT','ITA','Italie','Italy',4),(96,384,'CI','CIV','Côte d\'Ivoire','Côte d\'Ivoire',1),(97,388,'JM','JAM','Jamaïque','Jamaica',2),(98,392,'JP','JPN','Japon','Japan',3),(99,398,'KZ','KAZ','Kazakhstan','Kazakhstan',3),(100,400,'JO','JOR','Jordanie','Jordan',3),(101,404,'KE','KEN','Kenya','Kenya',1),(102,408,'KP','PRK','République Populaire Démocratique de Corée','Democratic People\'s Republic of Korea',3),(103,410,'KR','KOR','République de Corée','Republic of Korea',3),(104,414,'KW','KWT','Koweït','Kuwait',3),(105,417,'KG','KGZ','Kirghizistan','Kyrgyzstan',3),(106,418,'LA','LAO','République Démocratique Populaire Lao','Lao People\'s Democratic Republic',3),(107,422,'LB','LBN','Liban','Lebanon',3),(108,426,'LS','LSO','Lesotho','Lesotho',1),(109,428,'LV','LVA','Lettonie','Latvia',4),(110,430,'LR','LBR','Libéria','Liberia',1),(111,434,'LY','LBY','Jamahiriya Arabe Libyenne','Libyan Arab Jamahiriya',1),(112,438,'LI','LIE','Liechtenstein','Liechtenstein',4),(113,440,'LT','LTU','Lituanie','Lithuania',4),(114,442,'LU','LUX','Luxembourg','Luxembourg',4),(115,450,'MG','MDG','Madagascar','Madagascar',1),(116,454,'MW','MWI','Malawi','Malawi',1),(117,458,'MY','MYS','Malaisie','Malaysia',3),(118,462,'MV','MDV','Maldives','Maldives',3),(119,466,'ML','MLI','Mali','Mali',1),(120,470,'MT','MLT','Malte','Malta',4),(121,474,'MQ','MTQ','Martinique','Martinique',2),(122,478,'MR','MRT','Mauritanie','Mauritania',1),(123,480,'MU','MUS','Maurice','Mauritius',1),(124,484,'MX','MEX','Mexique','Mexico',2),(125,492,'MC','MCO','Monaco','Monaco',4),(126,496,'MN','MNG','Mongolie','Mongolia',3),(127,498,'MD','MDA','République de Moldova','Republic of Moldova',4),(128,500,'MS','MSR','Montserrat','Montserrat',2),(129,504,'MA','MAR','Maroc','Morocco',1),(130,508,'MZ','MOZ','Mozambique','Mozambique',1),(131,512,'OM','OMN','Oman','Oman',3),(132,516,'NA','NAM','Namibie','Namibia',1),(133,520,'NR','NRU','Nauru','Nauru',5),(134,524,'NP','NPL','Népal','Nepal',3),(135,528,'NL','NLD','Pays-Bas','Netherlands',4),(136,533,'AW','ABW','Aruba','Aruba',2),(137,540,'NC','NCL','Nouvelle-Calédonie','New Caledonia',5),(138,548,'VU','VUT','Vanuatu','Vanuatu',5),(139,554,'NZ','NZL','Nouvelle-Zélande','New Zealand',5),(140,558,'NI','NIC','Nicaragua','Nicaragua',2),(141,562,'NE','NER','Niger','Niger',1),(142,566,'NG','NGA','Nigéria','Nigeria',1),(143,570,'NU','NIU','Niué','Niue',5),(144,578,'NO','NOR','Norvège','Norway',4),(145,580,'MP','MNP','Îles Mariannes du Nord','Northern Mariana Islands',5),(146,583,'FM','FSM','États Fédérés de Micronésie','Federated States of Micronesia',5),(147,584,'MH','MHL','Îles Marshall','Marshall Islands',5),(148,585,'PW','PLW','Palaos','Palau',5),(149,586,'PK','PAK','Pakistan','Pa	kistan',3),(150,591,'PA','PAN','Panama','Panama',2),(151,598,'PG','PNG','Papouasie-Nouvelle-Guinée','Papua New Guinea',5),(152,600,'PY','PRY','Paraguay','Paraguay',2),(153,604,'PE','PER','Pérou','Peru',2),(154,608,'PH','PHL','Philippines','Philippines',3),(155,612,'PN','PCN','Pitcairn','Pitcairn',5),(156,616,'PL','POL','Pologne','Poland',4),(157,620,'PT','PRT','Portugal','Portugal',4),(158,624,'GW','GNB','Guinée-Bissau','Guinea-Bissau',1),(159,626,'TL','TLS','Timor-Leste','Timor-Leste',3),(160,630,'PR','PRI','Porto Rico','Puerto Rico',2),(161,634,'QA','QAT','Qatar','Qatar',3),(162,642,'RO','ROU','Roumanie','Romania',4),(163,643,'RU','RUS','Fédération de Russie','Russian Federation',4),(164,646,'RW','RWA','Rwanda','Rwanda',1),(165,654,'SH','SHN','Sainte-Hélène','Saint Helena',1),(166,660,'AI','AIA','Anguilla','Anguilla',2),(167,662,'LC','LCA','Sainte-Lucie','Saint Lucia',2),(168,666,'PM','SPM','Saint-Pierre-et-Miquelon','Saint-Pierre and Miquelon',2),(169,670,'VC','VCT','Saint-Vincent-et-les Grenadines','Saint Vincent and the Grenadines',2),(170,674,'SM','SMR','Saint-Marin','San Marino',4),(171,678,'ST','STP','Sao Tomé-et-Principe','Sao Tome and Principe',1),(172,682,'SA','SAU','Arabie Saoudite','Saudi Arabia',3),(173,686,'SN','SEN','Sénégal','Senegal',1),(174,690,'SC','SYC','Seychelles','Seychelles',1),(175,694,'SL','SLE','Sierra Leone','Sierra Leone',1),(176,702,'SG','SGP','Singapour','Singapore',3),(177,703,'SK','SVK','Slovaquie','Slovakia',4),(178,704,'VN','VNM','Viet Nam','Vietnam',3),(179,705,'SI','SVN','Slovénie','Slovenia',4),(180,706,'SO','SOM','Somalie','Somalia',1),(181,710,'ZA','ZAF','Afrique du Sud','South Africa',1),(182,716,'ZW','ZWE','Zimbabwe','Zimbabwe',1),(183,724,'ES','ESP','Espagne','Spain',4),(184,736,'SD','SDN','Soudan','Sudan',1),(185,740,'SR','SUR','Suriname','Suriname',2),(186,748,'SZ','SWZ','Swaziland','Swaziland',1),(187,752,'SE','SWE','Suède','Sweden',4),(188,756,'CH','CHE','Suisse','Switzerland',4),(189,760,'SY','SYR','République Arabe Syrienne','Syrian Arab Republic',3),(190,762,'TJ','TJK','Tadjikistan','Tajikistan',3),(191,764,'TH','THA','Thaïlande','Thailand',3),(192,768,'TG','TGO','Togo','Togo',1),(193,776,'TO','TON','Tonga','Tonga',5),(194,780,'TT','TTO','Trinité-et-Tobago','Trinidad and Tobago',2),(195,784,'AE','ARE','Émirats Arabes Unis','United Arab Emirates',3),(196,788,'TN','TUN','Tunisie','Tunisia',1),(197,792,'TR','TUR','Turquie','Turkey',3),(198,795,'TM','TKM','Turkménistan','Turkmenistan',3),(199,798,'TV','TUV','Tuvalu','Tuvalu',5),(200,800,'UG','UGA','Ouganda','Uganda',1),(201,804,'UA','UKR','Ukraine','Ukraine',4),(202,807,'MK','MKD','L\'ex-République Yougoslave de Macédoine','The Former Yugoslav Republic of Macedonia',4),(203,818,'EG','EGY','Égypte','Egypt',1),(204,826,'GB','GBR','Royaume-Uni','United Kingdom',4),(205,833,'IM','IMN','Île de Man','Isle of Man',4),(206,834,'TZ','TZA','République-Unie de Tanzanie','United Republic Of Tanzania',1),(207,840,'US','USA','États-Unis','United States',2),(208,850,'VI','VIR','Îles Vierges des États-Unis','U.S. Virgin Islands',2),(209,854,'BF','BFA','Burkina Faso','Burkina Faso',1),(210,858,'UY','URY','Uruguay','Uruguay',2),(211,860,'UZ','UZB','Ouzbékistan','Uzbekistan',3),(212,862,'VE','VEN','Venezuela','Venezuela',2),(213,876,'WF','WLF','Wallis et Futuna','Wallis and Futuna',5),(214,882,'WS','WSM','Samoa','Samoa',5),(215,887,'YE','YEM','Yémen','Yemen',3),(216,891,'CS','SCG','Serbie-et-Monténégro','Serbia and Montenegro',4),(217,894,'ZM','ZMB','Zambie','Zambia',1);
/*!40000 ALTER TABLE `FBT_Countries` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-04-28 16:06:48
