SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

DROP SCHEMA IF EXISTS `FBT` ;
CREATE SCHEMA IF NOT EXISTS `FBT` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `FBT` ;

-- -----------------------------------------------------
-- Table `FBT`.`FBTCountries`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FBT`.`FBTCountries` ;

CREATE  TABLE IF NOT EXISTS `FBT`.`FBTCountries` (
  `idCountries` SMALLINT(5) NOT NULL AUTO_INCREMENT ,
  `iso_num` INT(3) NOT NULL ,
  `iso_alpha2` VARCHAR(2) NOT NULL ,
  `iso_alpha3` VARCHAR(3) NOT NULL ,
  `name_fr` VARCHAR(45) NOT NULL ,
  `name_en` VARCHAR(45) NOT NULL ,
  `continent` ENUM('AFRIQUE','AMERIQUE','ASIE','EUROPE','OCEANIE') NULL DEFAULT NULL ,
  PRIMARY KEY (`idCountries`) ,
  UNIQUE INDEX `idCountries_UNIQUE` (`idCountries` ASC) ,
  UNIQUE INDEX `iso_num_UNIQUE` (`iso_num` ASC) ,
  UNIQUE INDEX `iso_alpha2_UNIQUE` (`iso_alpha2` ASC) ,
  UNIQUE INDEX `iso_alpha3_UNIQUE` (`iso_alpha3` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `FBT`.`FBTTeams`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FBT`.`FBTTeams` ;

CREATE  TABLE IF NOT EXISTS `FBT`.`FBTTeams` (
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
    REFERENCES `FBT`.`FBTCountries` (`idCountries` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `FBT`.`FBTMatchs`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FBT`.`FBTMatchs` ;

CREATE  TABLE IF NOT EXISTS `FBT`.`FBTMatchs` (
  `idMatchs` INT NOT NULL AUTO_INCREMENT ,
  `date` DATETIME NULL ,
  `Teams_id1` INT NOT NULL ,
  `Teams_id2` INT NOT NULL ,
  `score1` INT NULL ,
  `score2` INT NULL ,
  `pen1` INT NULL ,
  `pen2` INT NULL ,
  `Teams_Countries_id1` SMALLINT(5) NOT NULL ,
  `Teams_Countries_id2` SMALLINT(5) NOT NULL ,
  PRIMARY KEY (`idMatchs`, `Teams_id1`, `Teams_id2`, `Teams_Countries_id1`, `Teams_Countries_id2`) ,
  INDEX `fk_Matchs_Teams1` (`Teams_id1` ASC, `Teams_Countries_id1` ASC) ,
  INDEX `fk_Matchs_Teams2` (`Teams_id2` ASC, `Teams_Countries_id2` ASC) ,
  CONSTRAINT `fk_Matchs_Teams1`
    FOREIGN KEY (`Teams_id1` , `Teams_Countries_id1` )
    REFERENCES `FBT`.`FBTTeams` (`idTeams` , `Countries_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Matchs_Teams2`
    FOREIGN KEY (`Teams_id2` , `Teams_Countries_id2` )
    REFERENCES `FBT`.`FBTTeams` (`idTeams` , `Countries_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `FBT`.`FBTPlayers`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FBT`.`FBTPlayers` ;

CREATE  TABLE IF NOT EXISTS `FBT`.`FBTPlayers` (
  `idPlayers` INT NOT NULL AUTO_INCREMENT ,
  `first` VARCHAR(45) NULL ,
  `last` VARCHAR(45) NULL ,
  `mail` VARCHAR(45) NULL ,
  `password` VARCHAR(45) NULL ,
  `rdate` DATETIME NULL ,
  `active` ENUM('0','1') NULL ,
  PRIMARY KEY (`idPlayers`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `FBT`.`FBTBetMatchs`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FBT`.`FBTBetMatchs` ;

CREATE  TABLE IF NOT EXISTS `FBT`.`FBTBetMatchs` (
  `idBetMatchs` INT NOT NULL AUTO_INCREMENT ,
  `Players_id` INT NOT NULL ,
  `Matchs_id` INT NOT NULL ,
  `Matchs_Teams_id1` INT NOT NULL ,
  `Matchs_Teams_id2` INT NOT NULL ,
  `score1` INT NULL ,
  `score2` INT NULL ,
  `Matchs_Teams_Countries_id1` SMALLINT(5) NOT NULL ,
  `Matchs_Teams_Countries_id2` SMALLINT(5) NOT NULL ,
  PRIMARY KEY (`idBetMatchs`, `Players_id`, `Matchs_id`, `Matchs_Teams_id1`, `Matchs_Teams_id2`, `Matchs_Teams_Countries_id1`, `Matchs_Teams_Countries_id2`) ,
  INDEX `fk_BetMatchs_Matchs1` (`Matchs_id` ASC, `Matchs_Teams_id1` ASC, `Matchs_Teams_id2` ASC, `Matchs_Teams_Countries_id1` ASC, `Matchs_Teams_Countries_id2` ASC) ,
  INDEX `fk_BetMatchs_Players1` (`Players_id` ASC) ,
  CONSTRAINT `fk_BetMatchs_Matchs1`
    FOREIGN KEY (`Matchs_id` , `Matchs_Teams_id1` , `Matchs_Teams_id2` , `Matchs_Teams_Countries_id1` , `Matchs_Teams_Countries_id2` )
    REFERENCES `FBT`.`FBTMatchs` (`idMatchs` , `Teams_id1` , `Teams_id2` , `Teams_Countries_id1` , `Teams_Countries_id2` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_BetMatchs_Players1`
    FOREIGN KEY (`Players_id` )
    REFERENCES `FBT`.`FBTPlayers` (`idPlayers` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `FBT`.`FBTTournament`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FBT`.`FBTTournament` ;

CREATE  TABLE IF NOT EXISTS `FBT`.`FBTTournament` (
  `idTournament` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NULL ,
  `type` ENUM('euro','mondial') NULL DEFAULT NULL ,
  `year` YEAR NULL ,
  `sport` VARCHAR(45) NULL DEFAULT 'Football' ,
  PRIMARY KEY (`idTournament`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `FBT`.`FBTNews`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FBT`.`FBTNews` ;

CREATE  TABLE IF NOT EXISTS `FBT`.`FBTNews` (
  `idNews` INT NOT NULL ,
  `date` TIMESTAMP NULL ,
  `comment` MEDIUMTEXT NULL ,
  PRIMARY KEY (`idNews`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `FBT`.`FBTFootball`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FBT`.`FBTFootball` ;

CREATE  TABLE IF NOT EXISTS `FBT`.`FBTFootball` (
  `worldrank` INT NOT NULL ,
  `Countries_id` SMALLINT(5) NOT NULL ,
  PRIMARY KEY (`worldrank`, `Countries_id`) ,
  UNIQUE INDEX `worldrank_UNIQUE` (`worldrank` ASC) ,
  CONSTRAINT `fk_FBTFootball_FBTCountries1`
    FOREIGN KEY (`Countries_id` )
    REFERENCES `FBT`.`FBTCountries` (`idCountries` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `FBT`.`FBTToPlayers`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FBT`.`FBTToPlayers` ;

CREATE  TABLE IF NOT EXISTS `FBT`.`FBTToPlayers` (
  `Tournament_id` INT NOT NULL ,
  `Players_id` INT NOT NULL ,
  PRIMARY KEY (`Tournament_id`, `Players_id`) ,
  INDEX `fk_FBTToPlayers_FBTPlayers1` (`Players_id` ASC) ,
  CONSTRAINT `fk_FBTToPlayers_FBTTournament1`
    FOREIGN KEY (`Tournament_id` )
    REFERENCES `FBT`.`FBTTournament` (`idTournament` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_FBTToPlayers_FBTPlayers1`
    FOREIGN KEY (`Players_id` )
    REFERENCES `FBT`.`FBTPlayers` (`idPlayers` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
