-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema manttdb
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema manttdb
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `manttdb` DEFAULT CHARACTER SET utf8 ;
USE `manttdb` ;

-- -----------------------------------------------------
-- Table `manttdb`.`admin`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `manttdb`.`admin` (
  `idAdmin` INT NOT NULL DEFAULT 1,
  `Email` VARCHAR(45) NOT NULL DEFAULT 'chris@gmail.com',
  `Password` VARCHAR(45) NOT NULL DEFAULT 'f7050fa5b63ca3f9c663f606edd93f15',
  PRIMARY KEY (`idAdmin`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `manttdb`.`timetable`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `manttdb`.`timetable` (
  `idTimetable` INT NOT NULL AUTO_INCREMENT,
  `Admin_idAdmin` INT NOT NULL DEFAULT 1,
  `Days` INT NOT NULL,
  `Hours` INT NOT NULL,
  `Name` VARCHAR(45) NOT NULL,
  `Viewable` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`idTimetable`),
  INDEX `fk_Timetable_Admin_idx` (`Admin_idAdmin` ASC),
  CONSTRAINT `fk_Timetable_Admin`
    FOREIGN KEY (`Admin_idAdmin`)
    REFERENCES `manttdb`.`admin` (`idAdmin`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `manttdb`.`dayname`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `manttdb`.`dayname` (
  `idDayname` INT NOT NULL,
  `Timetable_idTimetable` INT NOT NULL,
  `Name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idDayname`, `Timetable_idTimetable`),
  INDEX `fk_Dayname_Timetable1_idx` (`Timetable_idTimetable` ASC),
  CONSTRAINT `fk_Dayname_Timetable1`
    FOREIGN KEY (`Timetable_idTimetable`)
    REFERENCES `manttdb`.`timetable` (`idTimetable`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `manttdb`.`hourname`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `manttdb`.`hourname` (
  `idHourname` INT NOT NULL,
  `Timetable_idTimetable` INT NOT NULL,
  `Name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idHourname`, `Timetable_idTimetable`),
  INDEX `fk_Hourname_Timetable1_idx` (`Timetable_idTimetable` ASC),
  CONSTRAINT `fk_Hourname_Timetable1`
    FOREIGN KEY (`Timetable_idTimetable`)
    REFERENCES `manttdb`.`timetable` (`idTimetable`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `manttdb`.`colorgroup`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `manttdb`.`colorgroup` (
  `idColorgroup` INT NOT NULL,
  `Timetable_idTimetable` INT NOT NULL,
  `Name` VARCHAR(45) NOT NULL,
  `Color` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idColorgroup`, `Timetable_idTimetable`),
  INDEX `fk_Colorgroup_Timetable1_idx` (`Timetable_idTimetable` ASC),
  CONSTRAINT `fk_Colorgroup_Timetable1`
    FOREIGN KEY (`Timetable_idTimetable`)
    REFERENCES `manttdb`.`timetable` (`idTimetable`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `manttdb`.`printgroup`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `manttdb`.`printgroup` (
  `idPrintgroup` INT NOT NULL,
  `Timetable_idTimetable` INT NOT NULL,
  `Name` VARCHAR(45) NOT NULL,
  `Number` INT NOT NULL,
  PRIMARY KEY (`idPrintgroup`, `Timetable_idTimetable`),
  INDEX `fk_Printgroup_Timetable1_idx` (`Timetable_idTimetable` ASC),
  CONSTRAINT `fk_Printgroup_Timetable1`
    FOREIGN KEY (`Timetable_idTimetable`)
    REFERENCES `manttdb`.`timetable` (`idTimetable`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `manttdb`.`subject`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `manttdb`.`subject` (
  `idSubject` INT NOT NULL,
  `Timetable_idTimetable` INT NOT NULL,
  `Colorgroup_idColorgroup` INT NULL,
  `Printgroup_idPrintgroup` INT NULL,
  `Code` VARCHAR(45) NOT NULL,
  `Name` MEDIUMTEXT NOT NULL,
  `Students` INT NOT NULL DEFAULT 1,
  `Hours` VARCHAR(45) NOT NULL,
  `Availability` INT NOT NULL,
  `Semister` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idSubject`, `Timetable_idTimetable`),
  INDEX `fk_Subject_Timetable1_idx` (`Timetable_idTimetable` ASC),
  INDEX `fk_Subject_Colorgroup1_idx` (`Colorgroup_idColorgroup` ASC),
  INDEX `fk_Subject_Printgroup1_idx` (`Printgroup_idPrintgroup` ASC),
  CONSTRAINT `fk_Subject_Timetable1`
    FOREIGN KEY (`Timetable_idTimetable`)
    REFERENCES `manttdb`.`timetable` (`idTimetable`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Subject_Colorgroup1`
    FOREIGN KEY (`Colorgroup_idColorgroup`)
    REFERENCES `manttdb`.`colorgroup` (`idColorgroup`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Subject_Printgroup1`
    FOREIGN KEY (`Printgroup_idPrintgroup`)
    REFERENCES `manttdb`.`printgroup` (`idPrintgroup`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `manttdb`.`classroom`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `manttdb`.`classroom` (
  `idClassroom` INT NOT NULL,
  `Timetable_idTimetable` INT NOT NULL,
  `Name` VARCHAR(45) NOT NULL,
  `Capacity` INT NOT NULL DEFAULT 500,
  PRIMARY KEY (`idClassroom`, `Timetable_idTimetable`),
  INDEX `fk_Classroom_Timetable1_idx` (`Timetable_idTimetable` ASC),
  CONSTRAINT `fk_Classroom_Timetable1`
    FOREIGN KEY (`Timetable_idTimetable`)
    REFERENCES `manttdb`.`timetable` (`idTimetable`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `manttdb`.`teacher`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `manttdb`.`teacher` (
  `idTeacher` INT NOT NULL,
  `Timetable_idTimetable` INT NOT NULL,
  `Name` VARCHAR(45) NOT NULL,
  `ContinuingHours` INT NOT NULL DEFAULT 0,
  `HoursPerDay` INT NOT NULL DEFAULT 0,
  `DaysPerWeek` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`idTeacher`, `Timetable_idTimetable`),
  INDEX `fk_Teacher_Timetable1_idx` (`Timetable_idTimetable` ASC),
  CONSTRAINT `fk_Teacher_Timetable1`
    FOREIGN KEY (`Timetable_idTimetable`)
    REFERENCES `manttdb`.`timetable` (`idTimetable`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `manttdb`.`cgroup`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `manttdb`.`cgroup` (
  `idCgroup` INT NOT NULL,
  `Timetable_idTimetable` INT NOT NULL,
  `Name` VARCHAR(45) NOT NULL,
  `Multiplier` INT NOT NULL,
  PRIMARY KEY (`idCgroup`, `Timetable_idTimetable`),
  INDEX `fk_Cgroup_Timetable1_idx` (`Timetable_idTimetable` ASC),
  CONSTRAINT `fk_Cgroup_Timetable1`
    FOREIGN KEY (`Timetable_idTimetable`)
    REFERENCES `manttdb`.`timetable` (`idTimetable`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `manttdb`.`cgroup_has_subject`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `manttdb`.`cgroup_has_subject` (
  `Cgroup_idCgroup` INT NOT NULL,
  `Subject_idSubject` INT NOT NULL,
  `Subject_Timetable_idTimetable` INT NOT NULL,
  PRIMARY KEY (`Cgroup_idCgroup`, `Subject_idSubject`, `Subject_Timetable_idTimetable`),
  INDEX `fk_Cgroup_has_Subject_Subject1_idx` (`Subject_idSubject` ASC, `Subject_Timetable_idTimetable` ASC),
  INDEX `fk_Cgroup_has_Subject_Cgroup1_idx` (`Cgroup_idCgroup` ASC),
  CONSTRAINT `fk_Cgroup_has_Subject_Cgroup1`
    FOREIGN KEY (`Cgroup_idCgroup`)
    REFERENCES `manttdb`.`cgroup` (`idCgroup`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Cgroup_has_Subject_Subject1`
    FOREIGN KEY (`Subject_idSubject` , `Subject_Timetable_idTimetable`)
    REFERENCES `manttdb`.`subject` (`idSubject` , `Timetable_idTimetable`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `manttdb`.`dgroup`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `manttdb`.`dgroup` (
  `idDgroup` INT NOT NULL,
  `Timetable_idTimetable` INT NOT NULL,
  `Name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idDgroup`, `Timetable_idTimetable`),
  INDEX `fk_Dgroup_Timetable1_idx` (`Timetable_idTimetable` ASC),
  CONSTRAINT `fk_Dgroup_Timetable1`
    FOREIGN KEY (`Timetable_idTimetable`)
    REFERENCES `manttdb`.`timetable` (`idTimetable`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `manttdb`.`dgroup_has_subject`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `manttdb`.`dgroup_has_subject` (
  `Dgroup_idDgroup` INT NOT NULL,
  `Subject_idSubject` INT NOT NULL,
  `Subject_Timetable_idTimetable` INT NOT NULL,
  PRIMARY KEY (`Dgroup_idDgroup`, `Subject_idSubject`, `Subject_Timetable_idTimetable`),
  INDEX `fk_Dgroup_has_Subject_Subject1_idx` (`Subject_idSubject` ASC, `Subject_Timetable_idTimetable` ASC),
  INDEX `fk_Dgroup_has_Subject_Dgroup1_idx` (`Dgroup_idDgroup` ASC),
  CONSTRAINT `fk_Dgroup_has_Subject_Dgroup1`
    FOREIGN KEY (`Dgroup_idDgroup`)
    REFERENCES `manttdb`.`dgroup` (`idDgroup`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Dgroup_has_Subject_Subject1`
    FOREIGN KEY (`Subject_idSubject` , `Subject_Timetable_idTimetable`)
    REFERENCES `manttdb`.`subject` (`idSubject` , `Timetable_idTimetable`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `manttdb`.`equal`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `manttdb`.`equal` (
  `idEqual` INT NOT NULL AUTO_INCREMENT,
  `Subject_Timetable_idTimetable` INT NOT NULL,
  `Subject_idSubject1` INT NOT NULL,
  `idSubject2` INT NOT NULL,
  PRIMARY KEY (`idEqual`),
  INDEX `fk_Equal_Subject1_idx` (`Subject_idSubject1` ASC, `Subject_Timetable_idTimetable` ASC),
  CONSTRAINT `fk_Equal_Subject1`
    FOREIGN KEY (`Subject_idSubject1` , `Subject_Timetable_idTimetable`)
    REFERENCES `manttdb`.`subject` (`idSubject` , `Timetable_idTimetable`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `manttdb`.`subject_has_teacher`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `manttdb`.`subject_has_teacher` (
  `Subject_idSubject` INT NOT NULL,
  `Subject_Timetable_idTimetable` INT NOT NULL,
  `Teacher_idTeacher` INT NOT NULL,
  PRIMARY KEY (`Subject_idSubject`, `Subject_Timetable_idTimetable`, `Teacher_idTeacher`),
  INDEX `fk_Subject_has_Teacher_Teacher1_idx` (`Teacher_idTeacher` ASC),
  INDEX `fk_Subject_has_Teacher_Subject1_idx` (`Subject_idSubject` ASC, `Subject_Timetable_idTimetable` ASC),
  CONSTRAINT `fk_Subject_has_Teacher_Subject1`
    FOREIGN KEY (`Subject_idSubject` , `Subject_Timetable_idTimetable`)
    REFERENCES `manttdb`.`subject` (`idSubject` , `Timetable_idTimetable`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Subject_has_Teacher_Teacher1`
    FOREIGN KEY (`Teacher_idTeacher`)
    REFERENCES `manttdb`.`teacher` (`idTeacher`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `manttdb`.`classroomrestrictions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `manttdb`.`classroomrestrictions` (
  `idClassroomRestrictions` INT NOT NULL AUTO_INCREMENT,
  `Classroom_idClassroom` INT NOT NULL,
  `Classroom_Timetable_idTimetable` INT NOT NULL,
  `Day` INT NOT NULL,
  `Hour` INT NOT NULL,
  PRIMARY KEY (`idClassroomRestrictions`),
  INDEX `fk_ClassroomRestrictions_Classroom1_idx` (`Classroom_idClassroom` ASC, `Classroom_Timetable_idTimetable` ASC),
  CONSTRAINT `fk_ClassroomRestrictions_Classroom1`
    FOREIGN KEY (`Classroom_idClassroom` , `Classroom_Timetable_idTimetable`)
    REFERENCES `manttdb`.`classroom` (`idClassroom` , `Timetable_idTimetable`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `manttdb`.`teacherrestrictions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `manttdb`.`teacherrestrictions` (
  `idTeacherRestrictions` INT NOT NULL AUTO_INCREMENT,
  `Teacher_idTeacher` INT NOT NULL,
  `Teacher_Timetable_idTimetable` INT NOT NULL,
  `Day` INT NOT NULL,
  `Hour` INT NOT NULL,
  PRIMARY KEY (`idTeacherRestrictions`),
  INDEX `fk_TeacherRestrictions_Teacher1_idx` (`Teacher_idTeacher` ASC, `Teacher_Timetable_idTimetable` ASC),
  CONSTRAINT `fk_TeacherRestrictions_Teacher1`
    FOREIGN KEY (`Teacher_idTeacher` , `Teacher_Timetable_idTimetable`)
    REFERENCES `manttdb`.`teacher` (`idTeacher` , `Timetable_idTimetable`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `manttdb`.`subjectrestrictions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `manttdb`.`subjectrestrictions` (
  `idSubjectRestrictions` INT NOT NULL AUTO_INCREMENT,
  `Subject_idSubject` INT NOT NULL,
  `Subject_Timetable_idTimetable` INT NOT NULL,
  `Day` INT NOT NULL,
  `Hour` INT NOT NULL,
  PRIMARY KEY (`idSubjectRestrictions`),
  INDEX `fk_ClassroomRestrictions_copy1_Subject1_idx` (`Subject_idSubject` ASC, `Subject_Timetable_idTimetable` ASC),
  CONSTRAINT `fk_ClassroomRestrictions_copy1_Subject1`
    FOREIGN KEY (`Subject_idSubject` , `Subject_Timetable_idTimetable`)
    REFERENCES `manttdb`.`subject` (`idSubject` , `Timetable_idTimetable`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `manttdb`.`timeclassroom`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `manttdb`.`timeclassroom` (
  `idTimeClassroom` INT NOT NULL AUTO_INCREMENT,
  `Subject_idSubject` INT NOT NULL,
  `Subject_Timetable_idTimetable` INT NOT NULL,
  `Time` INT NOT NULL,
  `Classroom` INT NOT NULL,
  PRIMARY KEY (`idTimeClassroom`),
  INDEX `fk_TimeClassroom_Subject1_idx` (`Subject_idSubject` ASC, `Subject_Timetable_idTimetable` ASC),
  CONSTRAINT `fk_TimeClassroom_Subject1`
    FOREIGN KEY (`Subject_idSubject` , `Subject_Timetable_idTimetable`)
    REFERENCES `manttdb`.`subject` (`idSubject` , `Timetable_idTimetable`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `manttdb`.`subject_has_classroom_preference`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `manttdb`.`subject_has_classroom_preference` (
  `idSubject_has_Classroom_Preference` INT NOT NULL AUTO_INCREMENT,
  `Subject_idSubject` INT NOT NULL,
  `Subject_Timetable_idTimetable` INT NOT NULL,
  `idClassroom` INT NOT NULL,
  PRIMARY KEY (`idSubject_has_Classroom_Preference`),
  INDEX `fk_Subject_has_Classroom_Subject1_idx` (`Subject_idSubject` ASC, `Subject_Timetable_idTimetable` ASC),
  CONSTRAINT `fk_Subject_has_Classroom_Subject1`
    FOREIGN KEY (`Subject_idSubject` , `Subject_Timetable_idTimetable`)
    REFERENCES `manttdb`.`subject` (`idSubject` , `Timetable_idTimetable`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
