-- MySQL Script generated by MySQL Workbench
-- 05/25/16 18:40:33
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema FBT
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `FBT` ;

-- -----------------------------------------------------
-- Schema FBT
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `FBT` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `FBT` ;

-- -----------------------------------------------------
-- Table `FBT`.`FBT_Continent`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FBT`.`FBT_Continent` ;

CREATE TABLE IF NOT EXISTS `FBT`.`FBT_Continent` (
  `idContinent` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `label` VARCHAR(45) NULL COMMENT '',
  PRIMARY KEY (`idContinent`)  COMMENT '')
ENGINE = InnoDB;

CREATE UNIQUE INDEX `idContinent_UNIQUE` ON `FBT`.`FBT_Continent` (`idContinent` ASC)  COMMENT '';


-- -----------------------------------------------------
-- Table `FBT`.`FBT_Countries`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FBT`.`FBT_Countries` ;

CREATE TABLE IF NOT EXISTS `FBT`.`FBT_Countries` (
  `idCountries` SMALLINT(5) NOT NULL AUTO_INCREMENT COMMENT '',
  `iso_num` INT(3) NOT NULL COMMENT '',
  `iso_alpha2` VARCHAR(2) NOT NULL COMMENT '',
  `iso_alpha3` VARCHAR(3) NOT NULL COMMENT '',
  `name_fr` VARCHAR(45) NOT NULL COMMENT '',
  `name_en` VARCHAR(45) NOT NULL COMMENT '',
  `idContinent` INT NOT NULL COMMENT '',
  PRIMARY KEY (`idCountries`)  COMMENT '',
  CONSTRAINT `fk_FBT_Countries_FBT_Continent1`
    FOREIGN KEY (`idContinent`)
    REFERENCES `FBT`.`FBT_Continent` (`idContinent`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

CREATE UNIQUE INDEX `idCountries_UNIQUE` ON `FBT`.`FBT_Countries` (`idCountries` ASC)  COMMENT '';

CREATE UNIQUE INDEX `iso_num_UNIQUE` ON `FBT`.`FBT_Countries` (`iso_num` ASC)  COMMENT '';

CREATE UNIQUE INDEX `iso_alpha2_UNIQUE` ON `FBT`.`FBT_Countries` (`iso_alpha2` ASC)  COMMENT '';

CREATE UNIQUE INDEX `iso_alpha3_UNIQUE` ON `FBT`.`FBT_Countries` (`iso_alpha3` ASC)  COMMENT '';

CREATE INDEX `fk_FBT_Countries_FBT_Continent1` ON `FBT`.`FBT_Countries` (`idContinent` ASC)  COMMENT '';


-- -----------------------------------------------------
-- Table `FBT`.`FBT_LeagueType`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FBT`.`FBT_LeagueType` ;

CREATE TABLE IF NOT EXISTS `FBT`.`FBT_LeagueType` (
  `idLeagueType` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `leagueType` VARCHAR(45) NULL COMMENT '',
  PRIMARY KEY (`idLeagueType`)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `FBT`.`FBT_League`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FBT`.`FBT_League` ;

CREATE TABLE IF NOT EXISTS `FBT`.`FBT_League` (
  `idLeague` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `LeagueType_id` INT NOT NULL COMMENT '',
  `leagueName` VARCHAR(45) NULL COMMENT '',
  PRIMARY KEY (`idLeague`)  COMMENT '',
  CONSTRAINT `fk_LeagueType`
    FOREIGN KEY (`LeagueType_id`)
    REFERENCES `FBT`.`FBT_LeagueType` (`idLeagueType`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_LeagueType_idx` ON `FBT`.`FBT_League` (`LeagueType_id` ASC)  COMMENT '';


-- -----------------------------------------------------
-- Table `FBT`.`FBT_Teams`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FBT`.`FBT_Teams` ;

CREATE TABLE IF NOT EXISTS `FBT`.`FBT_Teams` (
  `idTeams` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `League_id` INT NOT NULL COMMENT '',
  `pool` INT NULL COMMENT '',
  `ranking` INT NULL COMMENT '',
  `played` INT NULL DEFAULT 0 COMMENT '',
  `win` INT NULL DEFAULT 0 COMMENT '',
  `draw` INT NULL DEFAULT 0 COMMENT '',
  `lost` INT NULL DEFAULT 0 COMMENT '',
  `gf` INT NULL DEFAULT 0 COMMENT '',
  `ga` INT NULL DEFAULT 0 COMMENT '',
  `gav` INT NULL DEFAULT 0 COMMENT '',
  `pts` INT NULL DEFAULT 0 COMMENT '',
  `Countries_id` SMALLINT(5) NOT NULL COMMENT '',
  PRIMARY KEY (`idTeams`, `Countries_id`)  COMMENT '',
  CONSTRAINT `fk_Teams_Countries`
    FOREIGN KEY (`Countries_id`)
    REFERENCES `FBT`.`FBT_Countries` (`idCountries`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_League`
    FOREIGN KEY (`League_id`)
    REFERENCES `FBT`.`FBT_League` (`idLeague`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

CREATE INDEX `fk_Teams_Countries` ON `FBT`.`FBT_Teams` (`Countries_id` ASC)  COMMENT '';

CREATE INDEX `fk_League_idx` ON `FBT`.`FBT_Teams` (`League_id` ASC)  COMMENT '';


-- -----------------------------------------------------
-- Table `FBT`.`FBT_Matchs`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FBT`.`FBT_Matchs` ;

CREATE TABLE IF NOT EXISTS `FBT`.`FBT_Matchs` (
  `idMatchs` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `date` DATETIME NULL COMMENT '',
  `stadium` VARCHAR(255) NULL COMMENT '',
  `type` VARCHAR(45) NULL COMMENT '',
  PRIMARY KEY (`idMatchs`)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `FBT`.`FBT_Players`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FBT`.`FBT_Players` ;

CREATE TABLE IF NOT EXISTS `FBT`.`FBT_Players` (
  `idPlayers` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `username` VARCHAR(255) NOT NULL COMMENT '',
  `firstname` VARCHAR(45) NOT NULL COMMENT '',
  `lastname` VARCHAR(45) NOT NULL COMMENT '',
  `mail` VARCHAR(45) NOT NULL COMMENT '',
  `salt` VARCHAR(255) NOT NULL COMMENT '',
  `password` VARCHAR(255) NOT NULL COMMENT '',
  `rdate` DATETIME NULL COMMENT '',
  `role` VARCHAR(45) NULL COMMENT '',
  `active` TINYINT NULL COMMENT '',
  PRIMARY KEY (`idPlayers`)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

CREATE UNIQUE INDEX `idPlayers_UNIQUE` ON `FBT`.`FBT_Players` (`idPlayers` ASC)  COMMENT '';

CREATE UNIQUE INDEX `username_UNIQUE` ON `FBT`.`FBT_Players` (`username` ASC)  COMMENT '';

CREATE UNIQUE INDEX `mail_UNIQUE` ON `FBT`.`FBT_Players` (`mail` ASC)  COMMENT '';


-- -----------------------------------------------------
-- Table `FBT`.`FBT_MatchTeam`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FBT`.`FBT_MatchTeam` ;

CREATE TABLE IF NOT EXISTS `FBT`.`FBT_MatchTeam` (
  `idMatchTeam` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `idMatchs` INT NOT NULL COMMENT '',
  `idTeams` INT NOT NULL COMMENT '',
  `score` INT NULL COMMENT '',
  `pen` INT NULL COMMENT '',
  PRIMARY KEY (`idMatchTeam`)  COMMENT '',
  CONSTRAINT `fk_FBT_MatchTeam_FBTTeams1`
    FOREIGN KEY (`idTeams`)
    REFERENCES `FBT`.`FBT_Teams` (`idTeams`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_FBT_MatchTeam_FBTMatchs1`
    FOREIGN KEY (`idMatchs`)
    REFERENCES `FBT`.`FBT_Matchs` (`idMatchs`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

CREATE INDEX `fk_FBT_MatchTeam_FBTTeams1` ON `FBT`.`FBT_MatchTeam` (`idTeams` ASC)  COMMENT '';

CREATE UNIQUE INDEX `id_UNIQUE` ON `FBT`.`FBT_MatchTeam` (`idMatchTeam` ASC)  COMMENT '';

CREATE INDEX `fk_FBT_MatchTeam_FBTMatchs1` ON `FBT`.`FBT_MatchTeam` (`idMatchs` ASC)  COMMENT '';


-- -----------------------------------------------------
-- Table `FBT`.`FBT_BetMatchs`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FBT`.`FBT_BetMatchs` ;

CREATE TABLE IF NOT EXISTS `FBT`.`FBT_BetMatchs` (
  `idBetMatchs` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `idPlayers` INT NOT NULL COMMENT '',
  `idMatchTeam` INT NOT NULL COMMENT '',
  `score` INT NULL COMMENT '',
  PRIMARY KEY (`idBetMatchs`, `idPlayers`, `idMatchTeam`)  COMMENT '',
  CONSTRAINT `fk_BetMatchs_Players1`
    FOREIGN KEY (`idPlayers`)
    REFERENCES `FBT`.`FBT_Players` (`idPlayers`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_FBTBetMatchs_FBT_MatchTeam1`
    FOREIGN KEY (`idMatchTeam`)
    REFERENCES `FBT`.`FBT_MatchTeam` (`idMatchTeam`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

CREATE INDEX `fk_BetMatchs_Players1` ON `FBT`.`FBT_BetMatchs` (`idPlayers` ASC)  COMMENT '';

CREATE INDEX `fk_FBTBetMatchs_FBT_MatchTeam1` ON `FBT`.`FBT_BetMatchs` (`idMatchTeam` ASC)  COMMENT '';


-- -----------------------------------------------------
-- Table `FBT`.`FBT_Tournament`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FBT`.`FBT_Tournament` ;

CREATE TABLE IF NOT EXISTS `FBT`.`FBT_Tournament` (
  `idTournament` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `name` VARCHAR(255) NULL COMMENT '',
  `year` DATETIME NULL COMMENT '',
  PRIMARY KEY (`idTournament`)  COMMENT '')
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `FBT`.`FBT_News`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FBT`.`FBT_News` ;

CREATE TABLE IF NOT EXISTS `FBT`.`FBT_News` (
  `idNews` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `date` TIMESTAMP NULL COMMENT '',
  `comment` MEDIUMTEXT NULL COMMENT '',
  PRIMARY KEY (`idNews`)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

CREATE UNIQUE INDEX `idNews_UNIQUE` ON `FBT`.`FBT_News` (`idNews` ASC)  COMMENT '';


-- -----------------------------------------------------
-- Table `FBT`.`FBT_Football`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FBT`.`FBT_Football` ;

CREATE TABLE IF NOT EXISTS `FBT`.`FBT_Football` (
  `worldrank` INT NOT NULL COMMENT '',
  `idCountries` SMALLINT(5) NOT NULL COMMENT '',
  PRIMARY KEY (`worldrank`, `idCountries`)  COMMENT '',
  CONSTRAINT `fk_FBTFootball_FBTCountries1`
    FOREIGN KEY (`idCountries`)
    REFERENCES `FBT`.`FBT_Countries` (`idCountries`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

CREATE UNIQUE INDEX `worldrank_UNIQUE` ON `FBT`.`FBT_Football` (`worldrank` ASC)  COMMENT '';


-- -----------------------------------------------------
-- Table `FBT`.`FBT_TournPlayers`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FBT`.`FBT_TournPlayers` ;

CREATE TABLE IF NOT EXISTS `FBT`.`FBT_TournPlayers` (
  `idTournPlayers` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `idTournament` INT NOT NULL COMMENT '',
  `idPlayers` INT NOT NULL COMMENT '',
  `isAdmin` TINYINT NULL COMMENT '',
  `isAccepted` TINYINT NULL COMMENT '',
  PRIMARY KEY (`idTournPlayers`)  COMMENT '',
  CONSTRAINT `fk_FBTTournPlayers_FBTTournament1`
    FOREIGN KEY (`idTournament`)
    REFERENCES `FBT`.`FBT_Tournament` (`idTournament`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_FBTTournPlayers_FBTPlayers1`
    FOREIGN KEY (`idPlayers`)
    REFERENCES `FBT`.`FBT_Players` (`idPlayers`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

CREATE INDEX `fk_FBTTournPlayers_FBTPlayers1` ON `FBT`.`FBT_TournPlayers` (`idPlayers` ASC)  COMMENT '';

CREATE UNIQUE INDEX `idToPlayers_UNIQUE` ON `FBT`.`FBT_TournPlayers` (`idTournPlayers` ASC)  COMMENT '';


-- -----------------------------------------------------
-- Table `FBT`.`FBT_BetScore`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FBT`.`FBT_BetScore` ;

CREATE TABLE IF NOT EXISTS `FBT`.`FBT_BetScore` (
  `idScore` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `idLeague` INT NOT NULL COMMENT '',
  `idMatchs` INT NOT NULL COMMENT '',
  `idPlayers` INT NOT NULL COMMENT '',
  `score` INT NULL COMMENT '',
  PRIMARY KEY (`idScore`)  COMMENT '',
  CONSTRAINT `fk_FBT_BetScore_FBT_Matchs1`
    FOREIGN KEY (`idMatchs`)
    REFERENCES `FBT`.`FBT_Matchs` (`idMatchs`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_FBT_BetScore_FBT_Players1`
    FOREIGN KEY (`idPlayers`)
    REFERENCES `FBT`.`FBT_Players` (`idPlayers`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_FBT_BetScore_FBT_League1`
    FOREIGN KEY (`idLeague`)
    REFERENCES `FBT`.`FBT_League` (`idLeague`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

CREATE UNIQUE INDEX `idScore_UNIQUE` ON `FBT`.`FBT_BetScore` (`idScore` ASC)  COMMENT '';

CREATE INDEX `fk_FBT_BetScore_FBT_Matchs1` ON `FBT`.`FBT_BetScore` (`idMatchs` ASC)  COMMENT '';

CREATE INDEX `fk_FBT_BetScore_FBT_Players1` ON `FBT`.`FBT_BetScore` (`idPlayers` ASC)  COMMENT '';

CREATE INDEX `fk_FBT_BetScore_FBT_League1` ON `FBT`.`FBT_BetScore` (`idLeague` ASC)  COMMENT '';


-- -----------------------------------------------------
-- Table `FBT`.`FBT_MatchPrerequisite`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FBT`.`FBT_MatchPrerequisite` ;

CREATE TABLE IF NOT EXISTS `FBT`.`FBT_MatchPrerequisite` (
  `idMatchPrerequisite` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `idPoolWinner` INT NULL COMMENT '',
  `idPoolSecond` INT NULL COMMENT '',
  `idMatchs` INT NOT NULL COMMENT '',
  `idMatchs1` INT NULL COMMENT '',
  `idMatchs2` INT NULL COMMENT '',
  PRIMARY KEY (`idMatchPrerequisite`)  COMMENT '',
  CONSTRAINT `fk_FBT_MatchPrerequisite_FBT_Matchs1`
    FOREIGN KEY (`idMatchs1`)
    REFERENCES `FBT`.`FBT_Matchs` (`idMatchs`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_FBT_MatchPrerequisite_FBT_Matchs2`
    FOREIGN KEY (`idMatchs2`)
    REFERENCES `FBT`.`FBT_Matchs` (`idMatchs`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_FBT_MatchPrerequisite_FBT_Matchs3`
    FOREIGN KEY (`idMatchs`)
    REFERENCES `FBT`.`FBT_Matchs` (`idMatchs`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

CREATE INDEX `fk_FBT_MatchPrerequisite_FBT_Matchs1` ON `FBT`.`FBT_MatchPrerequisite` (`idMatchs1` ASC)  COMMENT '';

CREATE INDEX `fk_FBT_MatchPrerequisite_FBT_Matchs2` ON `FBT`.`FBT_MatchPrerequisite` (`idMatchs2` ASC)  COMMENT '';

CREATE INDEX `fk_FBT_MatchPrerequisite_FBT_Matchs3` ON `FBT`.`FBT_MatchPrerequisite` (`idMatchs` ASC)  COMMENT '';


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
