SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

DROP SCHEMA IF EXISTS `FBT` ;
CREATE SCHEMA IF NOT EXISTS `FBT` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `FBT` ;

-- -----------------------------------------------------
-- Table `FBT`.`FBT_Continent`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FBT`.`FBT_Continent` ;

CREATE  TABLE IF NOT EXISTS `FBT`.`FBT_Continent` (
  `idContinent` INT NOT NULL AUTO_INCREMENT ,
  `label` VARCHAR(45) NULL ,
  PRIMARY KEY (`idContinent`) ,
  UNIQUE INDEX `idContinent_UNIQUE` (`idContinent` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `FBT`.`FBT_Countries`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FBT`.`FBT_Countries` ;

CREATE  TABLE IF NOT EXISTS `FBT`.`FBT_Countries` (
  `idCountries` SMALLINT(5) NOT NULL AUTO_INCREMENT ,
  `iso_num` INT(3) NOT NULL ,
  `iso_alpha2` VARCHAR(2) NOT NULL ,
  `iso_alpha3` VARCHAR(3) NOT NULL ,
  `name_fr` VARCHAR(45) NOT NULL ,
  `name_en` VARCHAR(45) NOT NULL ,
  `idContinent` INT NOT NULL ,
  PRIMARY KEY (`idCountries`) ,
  UNIQUE INDEX `idCountries_UNIQUE` (`idCountries` ASC) ,
  UNIQUE INDEX `iso_num_UNIQUE` (`iso_num` ASC) ,
  UNIQUE INDEX `iso_alpha2_UNIQUE` (`iso_alpha2` ASC) ,
  UNIQUE INDEX `iso_alpha3_UNIQUE` (`iso_alpha3` ASC) ,
  INDEX `fk_FBT_Countries_FBT_Continent1` (`idContinent` ASC) ,
  CONSTRAINT `fk_FBT_Countries_FBT_Continent1`
    FOREIGN KEY (`idContinent` )
    REFERENCES `FBT`.`FBT_Continent` (`idContinent` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `FBT`.`FBT_Teams`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FBT`.`FBT_Teams` ;

CREATE  TABLE IF NOT EXISTS `FBT`.`FBT_Teams` (
  `idTeams` INT NOT NULL AUTO_INCREMENT ,
  `pool` INT NULL ,
  `ranking` INT NULL ,
  `played` INT NULL ,
  `win` INT NULL ,
  `draw` INT NULL ,
  `lost` INT NULL ,
  `gf` INT NULL ,
  `ga` INT NULL ,
  `gav` INT NULL ,
  `pts` INT NULL ,
  `Countries_id` SMALLINT(5) NOT NULL ,
  PRIMARY KEY (`idTeams`, `Countries_id`) ,
  INDEX `fk_Teams_Countries` (`Countries_id` ASC) ,
  CONSTRAINT `fk_Teams_Countries`
    FOREIGN KEY (`Countries_id` )
    REFERENCES `FBT`.`FBT_Countries` (`idCountries` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `FBT`.`FBT_Matchs`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FBT`.`FBT_Matchs` ;

CREATE  TABLE IF NOT EXISTS `FBT`.`FBT_Matchs` (
  `idMatchs` INT NOT NULL AUTO_INCREMENT ,
  `date` DATETIME NULL ,
  `stadium` VARCHAR(255) NULL ,
  PRIMARY KEY (`idMatchs`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `FBT`.`FBT_Players`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FBT`.`FBT_Players` ;

CREATE  TABLE IF NOT EXISTS `FBT`.`FBT_Players` (
  `idPlayers` INT NOT NULL AUTO_INCREMENT ,
  `firstname` VARCHAR(45) NULL ,
  `lastname` VARCHAR(45) NULL ,
  `mail` VARCHAR(45) NULL ,
  `password` VARCHAR(255) NULL ,
  `rdate` DATETIME NULL ,
  `role` VARCHAR(45) NULL ,
  `active` TINYINT NULL ,
  PRIMARY KEY (`idPlayers`) ,
  UNIQUE INDEX `idPlayers_UNIQUE` (`idPlayers` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `FBT`.`FBT_MatchTeam`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FBT`.`FBT_MatchTeam` ;

CREATE  TABLE IF NOT EXISTS `FBT`.`FBT_MatchTeam` (
  `idMatchTeam` INT NOT NULL AUTO_INCREMENT ,
  `idMatchs` INT NOT NULL ,
  `idTeams` INT NOT NULL ,
  `score` INT NULL ,
  `pen` INT NULL ,
  PRIMARY KEY (`idMatchTeam`) ,
  INDEX `fk_FBT_MatchTeam_FBTTeams1` (`idTeams` ASC) ,
  UNIQUE INDEX `id_UNIQUE` (`idMatchTeam` ASC) ,
  INDEX `fk_FBT_MatchTeam_FBTMatchs1` (`idMatchs` ASC) ,
  CONSTRAINT `fk_FBT_MatchTeam_FBTTeams1`
    FOREIGN KEY (`idTeams` )
    REFERENCES `FBT`.`FBT_Teams` (`idTeams` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_FBT_MatchTeam_FBTMatchs1`
    FOREIGN KEY (`idMatchs` )
    REFERENCES `FBT`.`FBT_Matchs` (`idMatchs` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `FBT`.`FBT_BetMatchs`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FBT`.`FBT_BetMatchs` ;

CREATE  TABLE IF NOT EXISTS `FBT`.`FBT_BetMatchs` (
  `idBetMatchs` INT NOT NULL AUTO_INCREMENT ,
  `idPlayers` INT NOT NULL ,
  `idMatchTeam` INT NOT NULL ,
  `score` INT NULL ,
  PRIMARY KEY (`idBetMatchs`, `idPlayers`, `idMatchTeam`) ,
  INDEX `fk_BetMatchs_Players1` (`idPlayers` ASC) ,
  INDEX `fk_FBTBetMatchs_FBT_MatchTeam1` (`idMatchTeam` ASC) ,
  CONSTRAINT `fk_BetMatchs_Players1`
    FOREIGN KEY (`idPlayers` )
    REFERENCES `FBT`.`FBT_Players` (`idPlayers` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_FBTBetMatchs_FBT_MatchTeam1`
    FOREIGN KEY (`idMatchTeam` )
    REFERENCES `FBT`.`FBT_MatchTeam` (`idMatchTeam` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `FBT`.`FBT_Tournament`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FBT`.`FBT_Tournament` ;

CREATE  TABLE IF NOT EXISTS `FBT`.`FBT_Tournament` (
  `idTournament` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NULL ,
  `year` YEAR NULL ,
  PRIMARY KEY (`idTournament`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `FBT`.`FBT_News`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FBT`.`FBT_News` ;

CREATE  TABLE IF NOT EXISTS `FBT`.`FBT_News` (
  `idNews` INT NOT NULL ,
  `date` TIMESTAMP NULL ,
  `comment` MEDIUMTEXT NULL ,
  PRIMARY KEY (`idNews`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `FBT`.`FBT_Football`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FBT`.`FBT_Football` ;

CREATE  TABLE IF NOT EXISTS `FBT`.`FBT_Football` (
  `worldrank` INT NOT NULL ,
  `Countries_id` SMALLINT(5) NOT NULL ,
  PRIMARY KEY (`worldrank`, `Countries_id`) ,
  UNIQUE INDEX `worldrank_UNIQUE` (`worldrank` ASC) ,
  CONSTRAINT `fk_FBTFootball_FBTCountries1`
    FOREIGN KEY (`Countries_id` )
    REFERENCES `FBT`.`FBT_Countries` (`idCountries` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `FBT`.`FBT_ToPlayers`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FBT`.`FBT_ToPlayers` ;

CREATE  TABLE IF NOT EXISTS `FBT`.`FBT_ToPlayers` (
  `idToPlayers` INT NOT NULL AUTO_INCREMENT ,
  `idTournament` INT NOT NULL ,
  `idPlayers` INT NOT NULL ,
  `isAdmin` TINYINT NULL ,
  PRIMARY KEY (`idToPlayers`) ,
  INDEX `fk_FBTToPlayers_FBTPlayers1` (`idPlayers` ASC) ,
  UNIQUE INDEX `idToPlayers_UNIQUE` (`idToPlayers` ASC) ,
  CONSTRAINT `fk_FBTToPlayers_FBTTournament1`
    FOREIGN KEY (`idTournament` )
    REFERENCES `FBT`.`FBT_Tournament` (`idTournament` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_FBTToPlayers_FBTPlayers1`
    FOREIGN KEY (`idPlayers` )
    REFERENCES `FBT`.`FBT_Players` (`idPlayers` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `FBT`.`FBT_Continent`
-- -----------------------------------------------------
START TRANSACTION;
USE `FBT`;
INSERT INTO `FBT`.`FBT_Continent` (`idContinent`, `label`) VALUES (1, 'AFRIQE');
INSERT INTO `FBT`.`FBT_Continent` (`idContinent`, `label`) VALUES (2, 'AMERIQUE');
INSERT INTO `FBT`.`FBT_Continent` (`idContinent`, `label`) VALUES (3, 'ASIE');
INSERT INTO `FBT`.`FBT_Continent` (`idContinent`, `label`) VALUES (4, 'EUROPE');
INSERT INTO `FBT`.`FBT_Continent` (`idContinent`, `label`) VALUES (5, 'OCEANIE');

COMMIT;
