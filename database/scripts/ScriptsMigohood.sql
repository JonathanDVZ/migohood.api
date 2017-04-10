<<<<<<< HEAD
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema Migohood
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema Migohood
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `Migohood` DEFAULT CHARACTER SET utf8 ;
USE `Migohood` ;

-- -----------------------------------------------------
-- Table `Migohood`.`country`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Migohood`.`country` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `iso` VARCHAR(2) NOT NULL,
  `name` VARCHAR(250) NOT NULL,
  `iso3` VARCHAR(3) NULL,
  `numcode` INT NULL,
  `phonecode` INT NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Migohood`.`state`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Migohood`.`state` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(250) NOT NULL,
  `country_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_STATE_COUNTRY1_idx` (`country_id` ASC),
  CONSTRAINT `fk_STATE_COUNTRY1`
    FOREIGN KEY (`country_id`)
    REFERENCES `Migohood`.`country` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Migohood`.`city`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Migohood`.`city` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `state_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_CITY_STATE1_idx` (`state_id` ASC),
  CONSTRAINT `fk_CITY_STATE1`
    FOREIGN KEY (`state_id`)
    REFERENCES `Migohood`.`state` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Migohood`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Migohood`.`user` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `password` VARCHAR(300) NULL,
  `thumbnail` VARCHAR(150) NULL,
  `secondname` VARCHAR(45) NULL,
  `lastname` VARCHAR(45) NULL,
  `remember_token` VARCHAR(100) NULL,
  `confirm_token` VARCHAR(100) NULL,
  `address` VARCHAR(80) NULL,
  `city_id` INT NULL,
  UNIQUE INDEX `password_UNIQUE` (`password` ASC),
  PRIMARY KEY (`id`),
  INDEX `fk_USER_CITY1_idx` (`city_id` ASC),
  CONSTRAINT `fk_USER_CITY1`
    FOREIGN KEY (`city_id`)
    REFERENCES `Migohood`.`city` (`id`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Migohood`.`category`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Migohood`.`category` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Migohood`.`accommodation`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Migohood`.`accommodation` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Migohood`.`payment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Migohood`.`payment` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `type` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Migohood`.`service`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Migohood`.`service` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `date` DATETIME NULL,
  `category_id` INT NOT NULL,
  `accommodation_id` INT NULL,
  `zipcode` INT NULL,
  `city_id` INT NULL,
  `num_bathroom` INT NULL,
  `status` TINYINT(1) NOT NULL DEFAULT 0,
  `num_guest` INT NULL,
  `live` TINYINT(1) NULL,
  `service_id` INT NULL,
  `payment_id` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `cod_user_idx` (`user_id` ASC),
  INDEX `fk_SERVICE_CATEGORY1_idx` (`category_id` ASC),
  INDEX `fk_SERVICE_Accommodation1_idx` (`accommodation_id` ASC),
  INDEX `fk_service_city1_idx` (`city_id` ASC),
  INDEX `fk_service_service1_idx` (`service_id` ASC),
  INDEX `fk_service_pague1_idx` (`payment_id` ASC),
  CONSTRAINT `cod_user`
    FOREIGN KEY (`user_id`)
    REFERENCES `Migohood`.`user` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_SERVICE_CATEGORY1`
    FOREIGN KEY (`category_id`)
    REFERENCES `Migohood`.`category` (`id`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE,
  CONSTRAINT `fk_SERVICE_Accommodation1`
    FOREIGN KEY (`accommodation_id`)
    REFERENCES `Migohood`.`accommodation` (`id`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE,
  CONSTRAINT `fk_service_city1`
    FOREIGN KEY (`city_id`)
    REFERENCES `Migohood`.`city` (`id`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE,
  CONSTRAINT `fk_service_service1`
    FOREIGN KEY (`service_id`)
    REFERENCES `Migohood`.`service` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_service_pague1`
    FOREIGN KEY (`payment_id`)
    REFERENCES `Migohood`.`payment` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Migohood`.`type_amenities`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Migohood`.`type_amenities` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Migohood`.`amenities`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Migohood`.`amenities` (
  `codigo` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `category_id` INT NOT NULL,
  `type_amenities_id` INT NULL,
  `languaje` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`codigo`),
  INDEX `fk_AMENITES_CATEGORY1_idx` (`category_id` ASC),
  INDEX `fk_amenities_type_amenities1_idx` (`type_amenities_id` ASC),
  CONSTRAINT `fk_AMENITES_CATEGORY1`
    FOREIGN KEY (`category_id`)
    REFERENCES `Migohood`.`category` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_amenities_type_amenities1`
    FOREIGN KEY (`type_amenities_id`)
    REFERENCES `Migohood`.`type_amenities` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Migohood`.`imagen`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Migohood`.`imagen` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `ruta` VARCHAR(45) NOT NULL,
  `service_id` INT NOT NULL,
  `description` VARCHAR(300) NULL,
  PRIMARY KEY (`id`),
  INDEX `cod_service_idx` (`service_id` ASC),
  CONSTRAINT `cod_service`
    FOREIGN KEY (`service_id`)
    REFERENCES `Migohood`.`service` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Migohood`.`type`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Migohood`.`type` (
  `id_type` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `category_id` INT NOT NULL,
  PRIMARY KEY (`id_type`),
  INDEX `fk_TYPE_CATEGORY1_idx` (`category_id` ASC),
  CONSTRAINT `fk_TYPE_CATEGORY1`
    FOREIGN KEY (`category_id`)
    REFERENCES `Migohood`.`category` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Migohood`.`service_amenites`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Migohood`.`service_amenites` (
  `service_id` INT NOT NULL,
  `amenite_id` INT NOT NULL,
  `id` INT NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  INDEX `fk_SERVICE_has_AMENITES_AMENITES1_idx` (`amenite_id` ASC),
  INDEX `fk_SERVICE_has_AMENITES_SERVICE1_idx` (`service_id` ASC),
  CONSTRAINT `fk_SERVICE_has_AMENITES_SERVICE1`
    FOREIGN KEY (`service_id`)
    REFERENCES `Migohood`.`service` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_SERVICE_has_AMENITES_AMENITES1`
    FOREIGN KEY (`amenite_id`)
    REFERENCES `Migohood`.`amenities` (`codigo`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Migohood`.`service_type`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Migohood`.`service_type` (
  `service_id` INT NOT NULL,
  `type_id` INT NOT NULL,
  `id` INT NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  INDEX `fk_SERVICE_has_TYPE_TYPE1_idx` (`type_id` ASC),
  INDEX `fk_SERVICE_has_TYPE_SERVICE1_idx` (`service_id` ASC),
  CONSTRAINT `service_id`
    FOREIGN KEY (`service_id`)
    REFERENCES `Migohood`.`service` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `type_id`
    FOREIGN KEY (`type_id`)
    REFERENCES `Migohood`.`type` (`id_type`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Migohood`.`inbox`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Migohood`.`inbox` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `receiver_id` INT NULL,
  `transmiter_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `id_receiver` (`receiver_id` ASC),
  INDEX `id_transmiter` (`transmiter_id` ASC),
  CONSTRAINT `receiver_id`
    FOREIGN KEY (`receiver_id`)
    REFERENCES `Migohood`.`user` (`id`)
    ON DELETE SET NULL
    ON UPDATE SET NULL,
  CONSTRAINT `transmiter_id`
    FOREIGN KEY (`transmiter_id`)
    REFERENCES `Migohood`.`user` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Migohood`.`message`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Migohood`.`message` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `content` LONGTEXT NOT NULL,
  `date` DATETIME NOT NULL,
  `inbox_id` INT NOT NULL,
  `user_id` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_MESSAGE_INBOX1_idx` (`inbox_id` ASC),
  INDEX `fk_MESSAGE_USER1_idx` (`user_id` ASC),
  CONSTRAINT `fk_MESSAGE_INBOX1`
    FOREIGN KEY (`inbox_id`)
    REFERENCES `Migohood`.`inbox` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_MESSAGE_USER1`
    FOREIGN KEY (`user_id`)
    REFERENCES `Migohood`.`user` (`id`)
    ON DELETE SET NULL
    ON UPDATE SET NULL)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Migohood`.`card`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Migohood`.`card` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `number` VARCHAR(300) NOT NULL,
  `exp_month` VARCHAR(300) NOT NULL,
  `exp_year` VARCHAR(300) NOT NULL,
  `cvc` VARCHAR(300) NOT NULL,
  `name` VARCHAR(300) NOT NULL,
  `user_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_CARD_USER1_idx` (`user_id` ASC),
  CONSTRAINT `fk_CARD_USER1`
    FOREIGN KEY (`user_id`)
    REFERENCES `Migohood`.`user` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Migohood`.`paypal`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Migohood`.`paypal` (
  `id_paypal` INT NOT NULL AUTO_INCREMENT,
  `account` VARCHAR(100) NOT NULL,
  `user_id` INT NOT NULL,
  PRIMARY KEY (`id_paypal`),
  INDEX `fk_PAYPAL_USER1_idx` (`user_id` ASC),
  CONSTRAINT `fk_PAYPAL_USER1`
    FOREIGN KEY (`user_id`)
    REFERENCES `Migohood`.`user` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Migohood`.`rent`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Migohood`.`rent` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `service_cod` INT NOT NULL,
  `end_date` DATETIME NOT NULL,
  `initial_date` DATETIME NOT NULL,
  INDEX `fk_USER_has_SERVICE_SERVICE1_idx` (`service_cod` ASC),
  INDEX `fk_USER_has_SERVICE_USER1_idx` (`user_id` ASC),
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_USER_has_SERVICE_USER1`
    FOREIGN KEY (`user_id`)
    REFERENCES `Migohood`.`user` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_USER_has_SERVICE_SERVICE1`
    FOREIGN KEY (`service_cod`)
    REFERENCES `Migohood`.`service` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Migohood`.`bill`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Migohood`.`bill` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `date` DATETIME NOT NULL,
  `total` FLOAT NOT NULL,
  `user_id` INT NOT NULL,
  `card_id` INT NULL,
  `paypal_id` INT NULL,
  `rent_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_BILL_USER1_idx` (`user_id` ASC),
  INDEX `fk_BILL_CARD1_idx` (`card_id` ASC),
  INDEX `fk_BILL_PAYPAL1_idx` (`paypal_id` ASC),
  INDEX `fk_BILL_RENT1_idx` (`rent_id` ASC),
  CONSTRAINT `fk_BILL_USER1`
    FOREIGN KEY (`user_id`)
    REFERENCES `Migohood`.`user` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_BILL_CARD1`
    FOREIGN KEY (`card_id`)
    REFERENCES `Migohood`.`card` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_BILL_PAYPAL1`
    FOREIGN KEY (`paypal_id`)
    REFERENCES `Migohood`.`paypal` (`id_paypal`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_BILL_RENT1`
    FOREIGN KEY (`rent_id`)
    REFERENCES `Migohood`.`rent` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Migohood`.`duration`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Migohood`.`duration` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `type` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Migohood`.`calendar`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Migohood`.`calendar` (
  `codigo_id` INT NOT NULL AUTO_INCREMENT,
  `day` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`codigo_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Migohood`.`service_calendar`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Migohood`.`service_calendar` (
  `service_id` INT NOT NULL,
  `calendar_id` INT NOT NULL,
  `codigo` INT NOT NULL AUTO_INCREMENT,
  INDEX `fk_SERVICE_has_CALENDAR_CALENDAR1_idx` (`calendar_id` ASC),
  INDEX `fk_SERVICE_has_CALENDAR_SERVICE1_idx` (`service_id` ASC),
  PRIMARY KEY (`codigo`),
  CONSTRAINT `fk_SERVICE_has_CALENDAR_SERVICE1`
    FOREIGN KEY (`service_id`)
    REFERENCES `Migohood`.`service` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_SERVICE_has_CALENDAR_CALENDAR1`
    FOREIGN KEY (`calendar_id`)
    REFERENCES `Migohood`.`calendar` (`codigo_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Migohood`.`currency`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Migohood`.`currency` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `currency_iso` VARCHAR(45) NOT NULL,
  `language` VARCHAR(45) NOT NULL,
  `currency_name` VARCHAR(45) NOT NULL,
  `money` VARCHAR(45) NOT NULL,
  `symbol` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Migohood`.`price_history`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Migohood`.`price_history` (
  `starDate` DATETIME NOT NULL,
  `endDate` DATETIME NULL,
  `price` FLOAT NOT NULL,
  `service_id` INT NOT NULL,
  `currency_id` INT NOT NULL,
  `duration_id` INT NOT NULL,
  PRIMARY KEY (`starDate`, `service_id`),
  INDEX `fk_PRICE_HISTORY_SERVICE1_idx` (`service_id` ASC),
  INDEX `fk_price_history_currency1_idx` (`currency_id` ASC),
  INDEX `fk_price_history_duration1_idx` (`duration_id` ASC),
  CONSTRAINT `fk_PRICE_HISTORY_SERVICE1`
    FOREIGN KEY (`service_id`)
    REFERENCES `Migohood`.`service` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_price_history_currency1`
    FOREIGN KEY (`currency_id`)
    REFERENCES `Migohood`.`currency` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_price_history_duration1`
    FOREIGN KEY (`duration_id`)
    REFERENCES `Migohood`.`duration` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Migohood`.`type_number`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Migohood`.`type_number` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `type` VARCHAR(16) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Migohood`.`phone_number`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Migohood`.`phone_number` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `number` INT NOT NULL,
  `user_id` INT NOT NULL,
  `type_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_PHONE_NUMBER_USER1_idx` (`user_id` ASC),
  INDEX `fk_phone_number_type_number1_idx` (`type_id` ASC),
  CONSTRAINT `fk_PHONE_NUMBER_USER1`
    FOREIGN KEY (`user_id`)
    REFERENCES `Migohood`.`user` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_phone_number_type_number1`
    FOREIGN KEY (`type_id`)
    REFERENCES `Migohood`.`type_number` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Migohood`.`comment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Migohood`.`comment` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `date` DATETIME NOT NULL,
  `content` MEDIUMTEXT NOT NULL,
  `cod_service` INT NOT NULL,
  `user_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_COMMENT_SERVICE1_idx` (`cod_service` ASC),
  INDEX `fk_COMMENT_USER1_idx` (`user_id` ASC),
  CONSTRAINT `fk_COMMENT_SERVICE1`
    FOREIGN KEY (`cod_service`)
    REFERENCES `Migohood`.`service` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_COMMENT_USER1`
    FOREIGN KEY (`user_id`)
    REFERENCES `Migohood`.`user` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Migohood`.`notification`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Migohood`.`notification` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `bill_number` INT NULL,
  `comment_id` INT NULL,
  `message_id` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_NOTIFICATION_USER1_idx` (`user_id` ASC),
  INDEX `fk_NOTIFICATION_BILL1_idx` (`bill_number` ASC),
  INDEX `fk_NOTIFICATION_COMMENT1_idx` (`comment_id` ASC),
  INDEX `fk_NOTIFICATION_MESSAGE1_idx` (`message_id` ASC),
  CONSTRAINT `fk_NOTIFICATION_USER1`
    FOREIGN KEY (`user_id`)
    REFERENCES `Migohood`.`user` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_NOTIFICATION_BILL1`
    FOREIGN KEY (`bill_number`)
    REFERENCES `Migohood`.`bill` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_NOTIFICATION_COMMENT1`
    FOREIGN KEY (`comment_id`)
    REFERENCES `Migohood`.`comment` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_NOTIFICATION_MESSAGE1`
    FOREIGN KEY (`message_id`)
    REFERENCES `Migohood`.`message` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Migohood`.`description`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Migohood`.`description` (
  `description_id` INT NOT NULL AUTO_INCREMENT,
  `type` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`description_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Migohood`.`service_description`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Migohood`.`service_description` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `description_id` INT NOT NULL,
  `service_id` INT NOT NULL,
  `content` VARCHAR(300) NULL,
  `check` TINYINT(1) NULL DEFAULT 0,
  INDEX `fk_description_has_service_service1_idx` (`service_id` ASC),
  INDEX `fk_description_has_service_description1_idx` (`description_id` ASC),
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_description_has_service_description1`
    FOREIGN KEY (`description_id`)
    REFERENCES `Migohood`.`description` (`description_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_description_has_service_service1`
    FOREIGN KEY (`service_id`)
    REFERENCES `Migohood`.`service` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Migohood`.`furniture`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Migohood`.`furniture` (
  `id_furniture` INT NOT NULL AUTO_INCREMENT,
  `type` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id_furniture`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Migohood`.`service_furniture`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Migohood`.`service_furniture` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `service_id` INT NOT NULL,
  `id_furniture` INT NOT NULL,
  `quantity` INT NOT NULL,
  INDEX `fk_service_has_furniture_furniture1_idx` (`id_furniture` ASC),
  INDEX `fk_service_has_furniture_service1_idx` (`service_id` ASC),
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_service_has_furniture_service1`
    FOREIGN KEY (`service_id`)
    REFERENCES `Migohood`.`service` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_service_has_furniture_furniture1`
    FOREIGN KEY (`id_furniture`)
    REFERENCES `Migohood`.`furniture` (`id_furniture`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Migohood`.`cancellation`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Migohood`.`cancellation` (
  `id_cancellation` INT NOT NULL AUTO_INCREMENT,
  `type` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id_cancellation`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Migohood`.`service_cancellation`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Migohood`.`service_cancellation` (
  `service_id` INT NOT NULL,
  `cancellation_id` INT NOT NULL,
  `content` TINYINT(1) NULL DEFAULT 0,
  INDEX `fk_service_has_cancellation_cancellation1_idx` (`cancellation_id` ASC),
  INDEX `fk_service_has_cancellation_service1_idx` (`service_id` ASC),
  CONSTRAINT `fk_service_has_cancellation_service1`
    FOREIGN KEY (`service_id`)
    REFERENCES `Migohood`.`service` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_service_has_cancellation_cancellation1`
    FOREIGN KEY (`cancellation_id`)
    REFERENCES `Migohood`.`cancellation` (`id_cancellation`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Migohood`.`bedroom`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Migohood`.`bedroom` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `service_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_bedroom_service1_idx` (`service_id` ASC),
  CONSTRAINT `fk_bedroom_service1`
    FOREIGN KEY (`service_id`)
    REFERENCES `Migohood`.`service` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Migohood`.`bed`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Migohood`.`bed` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `type` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Migohood`.`bedroom_bed`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Migohood`.`bedroom_bed` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `bedroom_id` INT NOT NULL,
  `bed_id` INT NOT NULL,
  `quantity` INT NOT NULL,
  INDEX `fk_bedroom_has_bed_bed1_idx` (`bed_id` ASC),
  INDEX `fk_bedroom_has_bed_bedroom1_idx` (`bedroom_id` ASC),
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_bedroom_has_bed_bedroom1`
    FOREIGN KEY (`bedroom_id`)
    REFERENCES `Migohood`.`bedroom` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_bedroom_has_bed_bed1`
    FOREIGN KEY (`bed_id`)
    REFERENCES `Migohood`.`bed` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Migohood`.`house_rules`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Migohood`.`house_rules` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `type` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Migohood`.`service_rules`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Migohood`.`service_rules` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `service_id` INT NOT NULL,
  `rules_id` INT NOT NULL,
  `check` TINYINT(1) NULL DEFAULT 0,
  `description` VARCHAR(300) NULL,
  INDEX `fk_service_has_house_rules_house_rules1_idx` (`rules_id` ASC),
  INDEX `fk_service_has_house_rules_service1_idx` (`service_id` ASC),
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_service_has_house_rules_service1`
    FOREIGN KEY (`service_id`)
    REFERENCES `Migohood`.`service` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_service_has_house_rules_house_rules1`
    FOREIGN KEY (`rules_id`)
    REFERENCES `Migohood`.`house_rules` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Migohood`.`check_in`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Migohood`.`check_in` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `time_entry` TIME NULL,
  `until` TIME NULL,
  `service_id` INT NOT NULL,
  INDEX `fk_check_in_service1_idx` (`service_id` ASC),
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_check_in_service1`
    FOREIGN KEY (`service_id`)
    REFERENCES `Migohood`.`service` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Migohood`.`check_out`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Migohood`.`check_out` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `departure_time` TIME NULL,
  `service_id` INT NOT NULL,
  INDEX `fk_check_out_service1_idx` (`service_id` ASC),
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_check_out_service1`
    FOREIGN KEY (`service_id`)
    REFERENCES `Migohood`.`service` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Migohood`.`reservation_preference`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Migohood`.`reservation_preference` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(300) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Migohood`.`service_reservation`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Migohood`.`service_reservation` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `service_id` INT NOT NULL,
  `preference_id` INT NOT NULL,
  `check` TINYINT(1) NOT NULL DEFAULT 0,
  INDEX `fk_service_has_reservation_preference_reservation_preferenc_idx` (`preference_id` ASC),
  INDEX `fk_service_has_reservation_preference_service1_idx` (`service_id` ASC),
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_service_has_reservation_preference_service1`
    FOREIGN KEY (`service_id`)
    REFERENCES `Migohood`.`service` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_service_has_reservation_preference_reservation_preference1`
    FOREIGN KEY (`preference_id`)
    REFERENCES `Migohood`.`reservation_preference` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Migohood`.`specialDate`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Migohood`.`specialDate` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `stardate` DATE NULL,
  `finishdate` DATE NULL,
  `price` FLOAT NULL,
  `service_id` INT NOT NULL,
  `check` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  INDEX `fk_optionalprice_service1_idx` (`service_id` ASC),
  CONSTRAINT `fk_optionalprice_service1`
    FOREIGN KEY (`service_id`)
    REFERENCES `Migohood`.`service` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
=======
use Migohood;
INSERT INTO `currency` (`currency_iso`, `Language`, `currency_name`, `money`, `symbol`) VALUES
        ('AFN', 'EN', 'Afghani', 'افغانۍ', '؋'),
        ('DZD', 'EN', 'Algerian Dinar', 'دينار', 'د'),
        ('ARS', 'EN', 'Argentine Peso', 'Peso', '$'),
        ('AMD', 'EN', 'Armenian Dram', 'Դրամ', 'դ'),
        ('AWG', 'EN', 'Aruban Guilder', 'Florijn', 'A'),
        ('AUD', 'EN', 'Australian Dollar', 'Dollar', '$'),
        ('AZN', 'EN', 'Azerbaijanian Manat', 'Manatı', 'м'),
        ('BSD', 'EN', 'Bahamian Dollar', 'Dollar', 'B'),
        ('BHD', 'EN', 'Bahraini Dinar', 'دينار', '.'),
        ('THB', 'EN', 'Baht', 'บาทไทย', '฿'),
        ('PAB', 'EN', 'Balboa', 'Balboa', 'B'),
        ('BBD', 'EN', 'Barbados Dollar', 'Dollar', 'B'),
        ('BYR', 'EN', 'Belarussian Ruble', 'Рубель', 'B'),
        ('BZD', 'EN', 'Belize Dollar', 'Dollar', 'B'),
        ('BMD', 'EN', 'Bermudian Dollar', 'Dollar', 'B'),
        ('VEF', 'EN', 'Bolivar Fuerte', 'Bolívar', 'B'),
        ('BOB', 'EN', 'Boliviano', 'Boliviano', 'B'),
        ('BRL', 'EN', 'Brazilian Real', 'Real', 'R'),
        ('BND', 'EN', 'Brunei Dollar', 'Ringgit', 'B'),
        ('BGN', 'EN', 'Bulgarian Lev', 'лев', 'л'),
        ('BIF', 'EN', 'Burundi Franc', 'Franc', 'F'),
        ('CAD', 'EN', 'Canadian Dollar', 'Dollar', '$'),
        ('CVE', 'EN', 'Cape Verde Escudo', 'Escudo', 'E'),
        ('KYD', 'EN', 'Cayman Islands Dollar', 'Dollar', '$'),
        ('GHS', 'EN', 'Cedi', 'Cedi', 'G'),
        ('XAF', 'EN', 'CFA Franc', 'Franc', 'C'),
        ('XOF', 'EN', 'CFA Franc', 'Franc', 'C'),
        ('XPF', 'EN', 'CFP Franc', 'Franc', 'F'),
        ('CLP', 'EN', 'Chilean Peso', 'Peso', '$'),
        ('CNY', 'EN', 'Chinese Yuan', '人民币', '¥'),
        ('COP', 'EN', 'Colombian Peso', 'Peso', 'C'),
        ('KMF', 'EN', 'Comoro Franc', 'Franc', 'F'),
        ('CDF', 'EN', 'Congolese Franc', 'Franc', 'F'),
        ('BAM', 'EN', 'Convertible Marks', 'Marka', 'K'),
        ('NIO', 'EN', 'Cordoba Oro', 'Córdoba', 'C'),
        ('CRC', 'EN', 'Costa Rican Colon', 'Colón', '₡'),
        ('HRK', 'EN', 'Croatian Kuna', 'Kuna', 'k'),
        ('CUP', 'EN', 'Cuban Peso', 'Peso', '$'),
        ('CZK', 'EN', 'Czech Koruna', 'Koruna', 'K'),
        ('GMD', 'EN', 'Dalasi', 'Dalasi', 'D'),
        ('DKK', 'EN', 'Danish Krone', 'Krone', 'K'),
        ('MKD', 'EN', 'Denar', 'Денар', 'д'),
        ('DJF', 'EN', 'Djibouti Franc', 'الفرنك', 'F'),
        ('STD', 'EN', 'Dobra', 'Dobra', 'D'),
        ('DOP', 'EN', 'Dominican Peso', 'Peso', 'R'),
        ('VND', 'EN', 'Dong', 'đồng', '₫'),
        ('XCD', 'EN', 'East Caribbean Dollar', 'Dollar', 'E'),
        ('EGP', 'EN', 'Egyptian Pound', 'الجنيه', '£'),
        ('ETB', 'EN', 'Ethiopian Birr', 'Birr', 'B'),
        ('EUR', 'EN', 'Euro', 'Euro', '€'),
        ('FKP', 'EN', 'Falkland Islands Pound', 'Pound', '£'),
        ('FJD', 'EN', 'Fiji Dollar', 'Dollar', 'F'),
        ('GIP', 'EN', 'Gibraltar Pound', 'Pound', '£'),
        ('HTG', 'EN', 'Gourde', 'Gourde', 'G'),
        ('PYG', 'EN', 'Guarani', 'Guaraní', '₲'),
        ('GNF', 'EN', 'Guinea Franc', 'Franc', 'F'),
        ('GWP', 'EN', 'Guinea-Bissau Peso', 'Peso', '$'),
        ('GYD', 'EN', 'Guyana Dollar', 'Dollar', 'G'),
        ('HKD', 'EN', 'Hong Kong Dollar', '香港圓', 'H'),
        ('UAH', 'EN', 'Hryvnia', 'Гривня', '₴'),
        ('HUF', 'EN', 'Hungary Forint', 'Forint', 'F'),
        ('ISK', 'EN', 'Iceland Krona', 'Króna', 'k'),
        ('INR', 'EN', 'Indian Rupee', 'Rupee', 'R'),
        ('IRR', 'EN', 'Iranian Rial', '﷼', '﷼'),
        ('IQD', 'EN', 'Iraqi Dinar', 'دينار', 'ع'),
        ('ILS', 'EN', 'Israeli Sheqel', 'שקל', '₪'),
        ('JMD', 'EN', 'Jamaican Dollar', 'Dollar', '$'),
        ('JPY', 'EN', 'Japan Yen', '日本円', '¥'),
        ('JOD', 'EN', 'Jordanian Dinar', 'دينار', 'د'),
        ('KES', 'EN', 'Kenyan Shilling', 'Shilling', 'K'),
        ('PGK', 'EN', 'Kina', 'Kina', 'K'),
        ('LAK', 'EN', 'Kip', 'ກີບ', '₭'),
        ('EEK', 'EN', 'Kroon', 'Kroon', 'K'),
        ('KWD', 'EN', 'Kuwaiti Dinar', 'دينار', 'د'),
        ('MWK', 'EN', 'Kwacha', 'Kwacha', 'M'),
        ('AOA', 'EN', 'Kwanza', 'Kwanza', 'K'),
        ('MMK', 'EN', 'Kyat', 'Kyat', 'K'),
        ('GEL', 'EN', 'Lari', 'ლარი', 'l'),
        ('LVL', 'EN', 'Latvian Lats', 'Lats', 'L'),
        ('LBP', 'EN', 'Lebanese Pound', 'ليرة,', 'ل'),
        ('ALL', 'EN', 'Lek', 'Leku', 'L'),
        ('HNL', 'EN', 'Lempira', 'Lempira', 'L'),
        ('SLL', 'EN', 'Leone', 'Leone', 'L'),
        ('LRD', 'EN', 'Liberian Dollar', 'Dollar', 'L'),
        ('LYD', 'EN', 'Libyan Dinar', 'دينار', 'ل'),
        ('SZL', 'EN', 'Lilangeni', 'Lilangeni', 'L'),
        ('LTL', 'EN', 'Lithuanian Litas', 'Litas', 'L'),
        ('LSL', 'EN', 'Loti', 'Loti', 'L'),
        ('MGA', 'EN', 'Malagasy Ariary', 'Ariary', 'F'),
        ('MYR', 'EN', 'Malaysian Ringgit', 'ريڠڬيت', 'R'),
        ('TMT', 'EN', 'Manat', 'Манат', 'm'),
        ('MUR', 'EN', 'Mauritius Rupee', 'Roupie', 'R'),
        ('MZN', 'EN', 'Metical', 'Metical', 'M'),
        ('MXN', 'EN', 'Mexican Peso', 'Peso', '$'),
        ('MDL', 'EN', 'Moldovan Leu', 'Leu', 'l'),
        ('MAD', 'EN', 'Moroccan Dirham', 'درهم', 'د'),
        ('NGN', 'EN', 'Naira', 'Naira', '₦'),
        ('ERN', 'EN', 'Nakfa', 'Nakfa', 'N'),
        ('NAD', 'EN', 'Namibia Dollar', 'Dollar', 'N'),
        ('NPR', 'EN', 'Nepalese Rupee', 'Rupee', 'N'),
        ('ANG', 'EN', 'Netherlands Antillian Guilder', 'Gulden', 'N'),
        ('RON', 'EN', 'New Leu', 'Leu', 'L'),
        ('NZD', 'EN', 'New Zealand Dollar', 'Dollar', '$'),
        ('BTN', 'EN', 'Ngultrum', 'དངུལ་ཀྲམ', 'N'),
        ('KPW', 'EN', 'North Korean Won', '원', '₩'),
        ('NOK', 'EN', 'Norwegian Krone', 'Krone', 'k'),
        ('PEN', 'EN', 'Nuevo Sol', 'Sol', 'S'),
        ('MRO', 'EN', 'Ouguiya', 'أوقية', 'U'),
        ('TOP', 'EN', 'Pa\'anga', 'Pa\'anga', 'T'),
        ('PKR', 'EN', 'Pakistan Rupee', 'Rupee', 'R'),
        ('MOP', 'EN', 'Pataca', '澳門圓', 'M'),
        ('UYU', 'EN', 'Peso Uruguayo', 'Peso', '$'),
        ('PHP', 'EN', 'Philippine Peso', 'Piso', '₱'),
        ('PLN', 'EN', 'Polish Zloty', 'Złoty', 'z'),
        ('GBP', 'EN', 'Pound Sterling', 'Pound', '£'),
        ('BWP', 'EN', 'Pula', 'Pula', 'P'),
        ('QAR', 'EN', 'Qatari Rial', 'ريال', 'ر'),
        ('GTQ', 'EN', 'Quetzal', 'Quetzal', 'Q'),
        ('ZAR', 'EN', 'Rand', 'Rand', 'R'),
        ('OMR', 'EN', 'Rial Omani', 'ريال', 'ر'),
        ('KHR', 'EN', 'Riel', 'Riel', 'r'),
        ('MVR', 'EN', 'Rufiyaa', 'Rufiyaa', 'R'),
        ('IDR', 'EN', 'Rupiah', 'Rupiah', 'R'),
        ('RUB', 'EN', 'Russian Ruble', 'Рубль', 'р'),
        ('RWF', 'EN', 'Rwanda Franc', 'Franc', 'R'),
        ('SHP', 'EN', 'Saint Helena Pound', 'Pound', '£'),
        ('SVC', 'EN', 'Salvadoran Colon', 'Colón', '₡'),
        ('SAR', 'EN', 'Saudi Riyal', 'ريال', 'ر'),
        ('RSD', 'EN', 'Serbian Dinar', 'Динар', 'д'),
        ('SCR', 'EN', 'Seychelles Rupee', 'Roupie', 'S'),
        ('SGD', 'EN', 'Singapore Dollar', '新加坡元', 'S'),
        ('SBD', 'EN', 'Solomon Islands Dollar', 'Dollar', 'S'),
        ('KGS', 'EN', 'Som', 'сом', 'с'),
        ('SOS', 'EN', 'Somali Shilling', 'Shilin', 'S'),
        ('TJS', 'EN', 'Somoni', 'Сомонӣ', 'С'),
        ('LKR', 'EN', 'Sri Lanka Rupee', 'Rupee', 'R'),
        ('SDG', 'EN', 'Sudanese Pound', 'جنيه', '£'),
        ('SRD', 'EN', 'Surinam Dollar', 'Dollar', '$'),
        ('SEK', 'EN', 'Swedish Krona', 'Krona', 'k'),
        ('CHF', 'EN', 'Swiss Franc', 'Franc', 'F'),
        ('SYP', 'EN', 'Syrian Pound', 'الليرة', 'S'),
        ('TWD', 'EN', 'Taiwan Dollar', '新臺幣', '$'),
        ('BDT', 'EN', 'Taka', 'টাকা', '৳'),
        ('WST', 'EN', 'Tala', 'Tālā', 'W'),
        ('TZS', 'EN', 'Tanzanian Shilling', 'Shilingi', '/'),
        ('KZT', 'EN', 'Tenge', 'Теңгесі', '〒'),
        ('TTD', 'EN', 'Trinidad and Tobago Dollar', 'Dollar', '$'),
        ('MNT', 'EN', 'Tugrik', 'Төгрөг', '₮'),
        ('TND', 'EN', 'Tunisian Dinar', 'دينار', 'د'),
        ('TRY', 'EN', 'Turkish Lira', 'Lirası', 'T'),
        ('AED', 'EN', 'UAE Dirham', 'درهم', 'د'),
        ('UGX', 'EN', 'Uganda Shilling', 'Shilling', 'U'),
        ('USD', 'EN', 'US Dollar', 'Dollar', '$'),
        ('UZS', 'EN', 'Uzbekistan Sum', 'Сўм', 'с'),
        ('VUV', 'EN', 'Vatu', 'Vatu', 'V'),
        ('KRW', 'EN', 'Won', '원', '₩'),
        ('YER', 'EN', 'Yemeni Rial', 'ريال', 'ر'),
        ('ZMK', 'EN', 'Zambian Kwacha', 'Kwacha', 'Z'),
        ('ZWL', 'EN', 'Zimbabwe Dollar', 'Dollar', '$'),
        ('AFN', 'ES', 'Afghani', 'افغانۍ', '؋'),
        ('THB', 'ES', 'Baht', 'บาทไทย', '฿'),
        ('PAB', 'ES', 'Balboa', 'Balboa', 'B'),
        ('ETB', 'ES', 'Birr Etiopía', 'Birr', 'B'),
        ('VEF', 'ES', 'Bolivar Fuerte', 'Bolívar', 'B'),
        ('BOB', 'ES', 'Boliviano', 'Boliviano', 'B'),
        ('BND', 'ES', 'Brunei Dólar', 'Ringgit', 'B'),
        ('GHS', 'ES', 'Cedi', 'Cedi', 'G'),
        ('CRC', 'ES', 'Colón Costa Rican', 'Colón', '₡'),
        ('SVC', 'ES', 'Colón Salvadoreño', 'Colón', '₡'),
        ('NIO', 'ES', 'Cordoba Oro', 'Córdoba', 'C'),
        ('DKK', 'ES', 'Corona Danesa', 'Corona', 'K'),
        ('ISK', 'ES', 'Corona Islandia', 'Corona', 'k'),
        ('NOK', 'ES', 'Corona Noruega', 'Corona', 'k'),
        ('SEK', 'ES', 'Corona Suiza', 'Corona', 'k'),
        ('GMD', 'ES', 'Dalasi', 'Dalasi', 'D'),
        ('MKD', 'ES', 'Dinar', 'Денар', 'д'),
        ('DZD', 'ES', 'Dinar Algeria', 'دينار', 'د'),
        ('BHD', 'ES', 'Dinar Bahrainí', 'دينار', '.'),
        ('LYD', 'ES', 'Dinar de Libia', 'دينار', 'ل'),
        ('RSD', 'ES', 'Dinar de Serbian', 'Динар', 'д'),
        ('TND', 'ES', 'Dinar de Tunisia', 'دينار', 'د'),
        ('IQD', 'ES', 'Dinar Iraqi', 'دينار', 'ع'),
        ('JOD', 'ES', 'Dinar Jordano', 'دينار', 'د'),
        ('KWD', 'ES', 'Dinar Kuwaití', 'دينار', 'د'),
        ('MAD', 'ES', 'Dirham de Moroco', 'درهم', 'د'),
        ('DJF', 'ES', 'Djibouti Franco', 'الفرنك', 'F'),
        ('STD', 'ES', 'Dobra', 'Dobra', 'D'),
        ('USD', 'ES', 'Dólar Americano', 'Dólar', '$'),
        ('AUD', 'ES', 'Dólar Australiano', 'Dólar', '$'),
        ('BSD', 'ES', 'Dólar Bahamas', 'Dólar', 'B'),
        ('BBD', 'ES', 'Dólar Barbados', 'Dólar', 'B'),
        ('BZD', 'ES', 'Dólar Belize', 'Dólar', 'B'),
        ('BMD', 'ES', 'Dólar Bremuda', 'Dólar', 'B'),
        ('CAD', 'ES', 'Dólar Canadiense', 'Dólar', '$'),
        ('GYD', 'ES', 'Dólar de Guyana', 'Dólar', 'G'),
        ('NAD', 'ES', 'Dólar de Namibia', 'Dólar', 'N'),
        ('ZWL', 'ES', 'Dólar de Zimbabwe', 'Dólar', '$'),
        ('XCD', 'ES', 'Dólar del Este Caribeño', 'Dólar', 'E'),
        ('FJD', 'ES', 'Dólar Fiji', 'Dólar', 'F'),
        ('HKD', 'ES', 'Dólar Hong Kong', '香港圓', 'H'),
        ('KYD', 'ES', 'Dólar Islas Caimán', 'Dólar', '$'),
        ('SBD', 'ES', 'Dólar Islas Salomón', 'Dólar', 'S'),
        ('JMD', 'ES', 'Dólar Jamaiquino', 'Dólar', '$'),
        ('LRD', 'ES', 'Dólar Liberiano', 'Dólar', 'L'),
        ('NZD', 'ES', 'Dólar Nueva Zelanda', 'Dólar', '$'),
        ('SGD', 'ES', 'Dólar Singapur', '新加坡元', 'S'),
        ('SRD', 'ES', 'Dólar Surinam', 'Dólar', '$'),
        ('TWD', 'ES', 'Dólar Taiwanés', '新臺幣', '$'),
        ('TTD', 'ES', 'Dólar Trinidad y Tobago', 'Dólar', '$'),
        ('VND', 'ES', 'Dong', 'đồng', '₫'),
        ('AMD', 'ES', 'Dram Armenio', 'Դրամ', 'դ'),
        ('CVE', 'ES', 'Escudo Cabo Verde', 'Escudo', 'E'),
        ('EUR', 'ES', 'Euro', 'Euro', '€'),
        ('HUF', 'ES', 'Florín Húgaro', 'Forín', 'F'),
        ('XAF', 'ES', 'Franco CFA', 'Franco', 'C'),
        ('XOF', 'ES', 'Franco CFA', 'Franco', 'C'),
        ('XPF', 'ES', 'Franco CFA', 'Franco', 'F'),
        ('CDF', 'ES', 'Franco Congolés', 'Franco', 'F'),
        ('KMF', 'ES', 'Franco de Comoro', 'Franco', 'F'),
        ('GNF', 'ES', 'Franco de Guinea', 'Franco', 'F'),
        ('RWF', 'ES', 'Franco Ruandés', 'Franco', 'R'),
        ('CHF', 'ES', 'Franco Suizo', 'Franco', 'F'),
        ('BIF', 'ES', 'Francoo de Burundi', 'Franco', 'F'),
        ('HTG', 'ES', 'Gourde', 'Gourde', 'G'),
        ('PYG', 'ES', 'Guarani', 'Guaraní', '₲'),
        ('ANG', 'ES', 'Guilder Antillas Nerlandesas', 'Gulden', 'N'),
        ('AWG', 'ES', 'Guilder de Aruba', 'Florijn', 'A'),
        ('UAH', 'ES', 'Hryvnia', 'Гривня', '₴'),
        ('PGK', 'ES', 'Kina', 'Kina', 'K'),
        ('LAK', 'ES', 'Kip', 'ກີບ', '₭'),
        ('CZK', 'ES', 'Koruna Checa', 'Koruna', 'K'),
        ('EEK', 'ES', 'Kroon', 'Kroon', 'K'),
        ('HRK', 'ES', 'Kuna Croatí', 'Kuna', 'k'),
        ('MWK', 'ES', 'Kwacha', 'Kwacha', 'M'),
        ('ZMK', 'ES', 'Kwacha de Zambia', 'Kwacha', 'Z'),
        ('AOA', 'ES', 'Kwanza', 'Kwanza', 'K'),
        ('MMK', 'ES', 'Kyat', 'Kyat', 'K'),
        ('GEL', 'ES', 'Lari', 'ლარი', 'l'),
        ('LVL', 'ES', 'Latvian Lats', 'Lats', 'L'),
        ('ALL', 'ES', 'Lek', 'Leku', 'L'),
        ('HNL', 'ES', 'Lempira', 'Lempira', 'L'),
        ('SLL', 'ES', 'Leone', 'Leone', 'L'),
        ('MDL', 'ES', 'Leu de Moldovia', 'Leu', 'l'),
        ('BGN', 'ES', 'Lev Búlgaro', 'лев', 'л'),
        ('SHP', 'ES', 'Libra de Santa Eelena', 'Libra', '£'),
        ('SDG', 'ES', 'Libra de Sudán', 'جنيه', '£'),
        ('EGP', 'ES', 'Libra Egipcia', 'الجنيه', '£'),
        ('GIP', 'ES', 'Libra Gibraltar', 'Libra', '£'),
        ('FKP', 'ES', 'Libra Islas Falkland', 'Libra', '£'),
        ('LBP', 'ES', 'Libra Libanesa', 'ليرة,', 'ل'),
        ('SYP', 'ES', 'Libra Siria', 'الليرة', 'S'),
        ('GBP', 'ES', 'Libra Sterling', 'Libra', '£'),
        ('SZL', 'ES', 'Lilangeni', 'Lilangeni', 'L'),
        ('TRY', 'ES', 'Lira Turca', 'Lira', 'T'),
        ('LTL', 'ES', 'Litas Lutianesa', 'Litas', 'L'),
        ('LSL', 'ES', 'Loti', 'Loti', 'L'),
        ('MGA', 'ES', 'Malagasy Ariary', 'Ariary', 'F'),
        ('TMT', 'ES', 'Manat', 'Манат', 'm'),
        ('AZN', 'ES', 'Manat Azerbaijanian', 'Manatı', 'м'),
        ('BAM', 'ES', 'Marcos Convertibles', 'Marka', 'K'),
        ('MZN', 'ES', 'Metical', 'Metical', 'M'),
        ('NGN', 'ES', 'Naira', 'Naira', '₦'),
        ('ERN', 'ES', 'Nakfa', 'Nakfa', 'N'),
        ('BTN', 'ES', 'Ngultrum', 'དངུལ་ཀྲམ', 'N'),
        ('RON', 'ES', 'Nuevo Leu', 'Leu', 'L'),
        ('PEN', 'ES', 'Nuevo Sol', 'Sol', 'S'),
        ('MRO', 'ES', 'Ouguiya', 'أوقية', 'U'),
        ('TOP', 'ES', 'Pa\'anga', 'Pa\'anga', 'T'),
        ('MOP', 'ES', 'Pataca', '澳門圓', 'M'),
        ('ARS', 'ES', 'Peso Argentino', 'Peso', '$'),
        ('CLP', 'ES', 'Peso Chileno', 'Peso', '$'),
        ('COP', 'ES', 'Peso Colombiano', 'Peso', 'C'),
        ('CUP', 'ES', 'Peso Cubano', 'Peso', '$'),
        ('DOP', 'ES', 'Peso Dominicano', 'Peso', 'R'),
        ('PHP', 'ES', 'Peso Filipino', 'Piso', '₱'),
        ('GWP', 'ES', 'Peso Guinea-Bissau', 'Peso', '$'),
        ('MXN', 'ES', 'Peso Mexicano', 'Peso', '$'),
        ('UYU', 'ES', 'Peso Uruguayo', 'Peso', '$'),
        ('BWP', 'ES', 'Pula', 'Pula', 'P'),
        ('QAR', 'ES', 'Qatari Rial', 'ريال', 'ر'),
        ('GTQ', 'ES', 'Quetzal', 'Quetzal', 'Q'),
        ('ZAR', 'ES', 'Rand', 'Rand', 'R'),
        ('BRL', 'ES', 'Real Brasileño', 'Real', 'R'),
        ('IRR', 'ES', 'Rial Iraní', '﷼', '﷼'),
        ('OMR', 'ES', 'Rial Omani', 'ريال', 'ر'),
        ('YER', 'ES', 'Rial Yemení', 'ريال', 'ر'),
        ('KHR', 'ES', 'Riel', 'Riel', 'r'),
        ('MYR', 'ES', 'Ringgit de Malasia', 'ريڠڬيت', 'R'),
        ('SAR', 'ES', 'Riyal Saudí', 'ريال', 'ر'),
        ('BYR', 'ES', 'Rublo Bieloruso', 'Рубель', 'B'),
        ('RUB', 'ES', 'Rublo Ruso', 'Рубль', 'р'),
        ('MVR', 'ES', 'Rufiyaa', 'Rufiyaa', 'R'),
        ('IDR', 'ES', 'Rupia', 'Rupia', 'R'),
        ('SCR', 'ES', 'Rupia de Seychelles', 'Rupia', 'S'),
        ('INR', 'ES', 'Rupia Indú', 'Rupia', 'R'),
        ('MUR', 'ES', 'Rupia Mauritius', 'Rupia', 'R'),
        ('NPR', 'ES', 'Rupia Nepalesa', 'Rupia', 'N'),
        ('PKR', 'ES', 'Rupia Pakistaní', 'Rupia', 'R'),
        ('LKR', 'ES', 'Rupia Sri Lanka', 'Rupia', 'R'),
        ('ILS', 'ES', 'Sheqel Israeli', 'שקל', '₪'),
        ('TZS', 'ES', 'Shilling de Tanzania', 'Shilingi', '/'),
        ('UGX', 'ES', 'Shilling de Uganda', 'Shilling', 'U'),
        ('KES', 'ES', 'Shilling Kenya', 'Shilling', 'K'),
        ('SOS', 'ES', 'Shilling Somalí', 'Shilin', 'S'),
        ('KGS', 'ES', 'Som', 'сом', 'с'),
        ('TJS', 'ES', 'Somoni', 'Сомонӣ', 'С'),
        ('UZS', 'ES', 'Sum Uzbekistán', 'Сўм', 'с'),
        ('BDT', 'ES', 'Taka', 'টাকা', '৳'),
        ('WST', 'ES', 'Tala', 'Tālā', 'W'),
        ('KZT', 'ES', 'Tenge', 'Теңгесі', '〒'),
        ('MNT', 'ES', 'Tugrik', 'Төгрөг', '₮'),
        ('AED', 'ES', 'UAE Dirham', 'درهم', 'د'),
        ('VUV', 'ES', 'Vatu', 'Vatu', 'V'),
        ('KRW', 'ES', 'Won', '원', '₩'),
        ('KPW', 'ES', 'Won Nor-Coreano', '원', '₩'),
        ('JPY', 'ES', 'Yen Japonés', '日本円', '¥'),
        ('CNY', 'ES', 'Yuan Chino', '人民币', '¥'),
        ('PLN', 'ES', 'Zloty Polaco', 'Złoty', 'z'),
        ('APN', 'FR', 'Afghani', 'افغانۍ', '؋'),
        ('AMD', 'FR', 'arménienne Dram', 'Դրամ', 'դ'),
        ('AWG', 'FR', 'Aruba Florin', 'Florijn', 'A'),
        ('THB', 'FR', 'Baht', 'บาท ไทย', '฿'),
        ('CAP', 'FR', 'Balboa', 'Balboa', 'B'),
        ('BBD', 'FR', 'Barbade Dollar', 'Dollar', 'B'),
        ('BYR', 'FR', 'biélorusse Rouble', 'Рубель', 'B'),
        ('ETB', 'FR', 'Birr éthiopien', 'Birr', 'B'),
        ('VEF', 'FR', 'Bolivar Fuerte', 'Bolívar', 'B'),
        ('BOB', 'FR', 'Boliviano', 'Boliviano', 'B'),
        ('BND', 'FR', 'Brunei Dollar', 'Ringgit', 'B'),
        ('SGH', 'FR', 'Cedi', 'Cedi', 'G'),
        ('SVC', 'FR', 'Colon Salvadorien', 'Colón', '₡'),
        ('NIO', 'FR', 'Cordoba Oro', 'Córdoba', 'C'),
        ('KPW', 'FR', 'Corée du Nord Won', '원', '₩'),
        ('CRC', 'FR', 'Costa Rica Colon', 'Colon', '₡'),
        ('EEK', 'FR', 'Couronne', 'Kroon', 'K'),
        ('DKK', 'FR', 'Couronne danoise', 'Krone', 'K'),
        ('ISK', 'FR', 'Couronne islandaise', 'couronne', 'k'),
        ('NOK', 'FR', 'Couronne Norvégienne', 'Krone', 'k'),
        ('SEK', 'FR', 'couronne suédoise', 'Couronne', 'k'),
        ('CZK', 'FR', 'Couronne tchèque', 'Couronne', 'K'),
        ('GMD', 'FR', 'Dalasi', 'Dalasi', 'D'),
        ('MKD', 'FR', 'Denar', 'Денар', 'д'),
        ('DZD', 'FR', 'Dinar Algérien', 'دينار', 'د'),
        ('BHD', 'FR', 'Dinar de Bahreïn', 'دينار', '.'),
        ('JOD', 'FR', 'Dinar jordanien', 'دينار', 'د'),
        ('KWD', 'FR', 'Dinar koweïtien', 'دينار', 'د'),
        ('LYD', 'FR', 'Dinar libyen', 'دينار', 'ل'),
        ('RSD', 'FR', 'Dinar serbe', 'Динар', 'д'),
        ('TND', 'FR', 'Dinar Tunisien', 'دينار', 'د'),
        ('MAD', 'FR', 'Dirham Marocain', 'درهم', 'د'),
        ('MST', 'FR', 'Dobra', 'Dobra', 'D'),
        ('AUD', 'FR', 'Dollar australien', 'Dollar', '$'),
        ('DMO', 'FR', 'Dollar bermudien', 'Dollar', 'B'),
        ('CAD', 'FR', 'Dollar canadien', 'Dollar', '$'),
        ('BZD', 'FR', 'Dollar de Belize', 'Dollar', 'B'),
        ('FJD', 'FR', 'Dollar de Fidji', 'Dollar', 'F'),
        ('HKD', 'FR', 'Dollar de Hong Kong', '香港 圆', 'H'),
        ('SGD', 'FR', 'Dollar de Singapour', '新加坡元', 'S'),
        ('TWD', 'FR', 'dollar de Taïwan', '新台币', '$'),
        ('BSD', 'FR', 'Dollar des Bahamas', 'Dollar', 'B'),
        ('XCD', 'FR', 'Dollar des Caraïbes orientales', 'Dollar', 'E'),
        ('SBD', 'FR', 'Dollar des Îles Salomon', 'Dollar', 'S'),
        ('ZWL', 'FR', 'Dollar du Zimbabwe', 'Dollar', '$'),
        ('GYD', 'FR', 'Dollar Guyana', 'Dollar', 'G'),
        ('JMD', 'FR', 'Dollar jamaïcain', 'Dollar', '$'),
        ('LRD', 'FR', 'Dollar libérien', 'Dollar', 'L'),
        ('NAD', 'FR', 'Dollar Namibie', 'Dollar', 'N'),
        ('NZD', 'FR', 'dollar néo-zélandais', 'Dollar', '$'),
        ('SRD', 'FR', 'Dollar Surinam', 'Dollar', '$'),
        ('VND', 'FR', 'Dong', 'Djong', '₫'),
        ('AED', 'FR', 'Emirats Arabes Unis Dirham', 'درهم', 'د'),
        ('CVE', 'FR', 'Escudo du Cap Vert', 'Escudo', 'E'),
        ('EUR', 'FR', 'Euro', 'Euro', '€'),
        ('FIF', 'FR', 'Franc Burundi', 'Franc', 'F'),
        ('XOF', 'FR', 'Franc CFA', 'Franc', 'C'),
        ('XAF', 'FR', 'Franc CFA', 'Franc', 'C'),
        ('XPF', 'FR', 'Franc CFP', 'Franc', 'F'),
        ('CDF', 'FR', 'Franc Congolais', 'Franc', 'F'),
        ('DJF', 'FR', 'Franc de Djibouti', 'الفرنك', 'F'),
        ('KMF', 'FR', 'Franc des Comores', 'Franc', 'F'),
        ('CHF', 'FR', 'franc suisse', 'Franc', 'F'),
        ('HTG', 'FR', 'Gourde', 'Gourde', 'G'),
        ('PYG', 'FR', 'Guarani', 'guarani', '₲'),
        ('GNF', 'FR', 'Guinée Franc', 'Franc', 'F'),
        ('PRG', 'FR', 'Guinée-Bissau Peso', 'Peso', '$'),
        ('HUF', 'FR', 'Hongrie Forint', 'Forint', 'F'),
        ('UAH', 'FR', 'Hryvnia', 'Гривня', '₴'),
        ('KYD', 'FR', 'Îles Caïmans Dollar', 'Dollar', '$'),
        ('FKP', 'FR', 'Îles Falkland Pound', 'Livre', '£'),
        ('IQD', 'FR', 'irakienne Dinar', 'دينار', 'ع'),
        ('ILS', 'FR', 'israëli sheqel', 'שקל', '₪'),
        ('JPY', 'FR', 'Japon Yen', '日本 円', '¥'),
        ('PGK', 'FR', 'Kina', 'Kina', 'K'),
        ('LAK', 'FR', 'Kip', 'ກີບ', '₭'),
        ('HRK', 'FR', 'Kuna Croate', 'Kuna', 'k'),
        ('MWK', 'FR', 'Kwacha', 'Kwacha', 'M'),
        ('ZMK', 'FR', 'Kwacha zambien', 'Kwacha', 'Z'),
        ('AOA', 'FR', 'Kwanza', 'Kwanza', 'K'),
        ('MMK', 'FR', 'Kyat', 'Kyat', 'K'),
        ('GEL', 'FR', 'Lari', 'ლარი', 'l'),
        ('LVL', 'FR', 'Lats letton', 'Lats', 'L'),
        ('ALL', 'FR', 'Lek', 'Leku', 'L'),
        ('HNL', 'FR', 'Lempira', 'Lempira', 'L'),
        ('SLL', 'FR', 'Leone', 'Leone', 'L'),
        ('MDL', 'FR', 'Leu moldave', 'Leu', 'l'),
        ('BGN', 'FR', 'lev bulgare', 'лев', 'л'),
        ('SZL', 'FR', 'Lilangeni', 'Lilangeni', 'L'),
        ('TRY', 'FR', 'lire turque', 'Lirasi', 'T'),
        ('LTL', 'FR', 'Litas lituanien', 'litas', 'L'),
        ('GIP', 'FR', 'Livre de Gibraltar', 'Livre', '£'),
        ('EGP', 'FR', 'Livre égyptienne', 'الجنيه', '£'),
        ('LBP', 'FR', 'Livre Libanaise', 'ليرة,', 'ل'),
        ('SDG', 'FR', 'Livre soudanaise', 'جنيه', '£'),
        ('GBP', 'FR', 'Livre Sterling', 'Livre', '£'),
        ('SYP', 'FR', 'Livre syrienne', 'الليرة', 'S'),
        ('LSL', 'FR', 'Loti', 'Loti', 'L'),
        ('MGA', 'FR', 'Malagasy Ariary', 'Ariary', 'F'),
        ('TMT', 'FR', 'Manat', 'Манат', 'm'),
        ('AZN', 'FR', 'manat azerbaïdjanais', 'Manati', 'м'),
        ('BAM', 'FR', 'Marks convertibles', 'Marka', 'K'),
        ('MZN', 'FR', 'Metical', 'Metical', 'M'),
        ('NGN', 'FR', 'Naira', 'Naira', '₦'),
        ('ERN', 'FR', 'Nakfa', 'Nakfa', 'N'),
        ('BTN', 'FR', 'Ngultrum', 'དངུལ་ཀྲམ', 'N'),
        ('RON', 'FR', 'Nouveau Leu', 'Leu', 'L'),
        ('PEN', 'FR', 'Nuevo Sol', 'Sol', 'S'),
        ('MRO', 'FR', 'Ouguiya', 'أوقية', 'U'),
        ('TOP', 'FR', 'Pa\'anga', 'Pa\'anga', 'T'),
        ('MOP', 'FR', 'Pataca', '澳门 圆', 'M'),
        ('ANG', 'FR', 'Pays-Bas Antilles Florin', 'Gulden', 'N'),
        ('ARS', 'FR', 'Peso argentin', 'Peso', '$'),
        ('CLP', 'FR', 'Peso Chilien', 'Peso', '$'),
        ('COP', 'FR', 'Peso colombien', 'Peso', 'C'),
        ('CUP', 'FR', 'Peso cubain', 'Peso', '$'),
        ('DOP', 'FR', 'Peso Dominican', 'Peso', 'R'),
        ('MXN', 'FR', 'Peso mexicain', 'Peso', '$'),
        ('PHP', 'FR', 'Peso philippin', 'Piso', '₱'),
        ('UYU', 'FR', 'Peso uruguayen', 'Peso', '$'),
        ('BWP', 'FR', 'Pula', 'Pula', 'P'),
        ('GTQ', 'FR', 'Quetzal', 'Quetzal', 'Q'),
        ('ZAR', 'FR', 'Rand', 'Rand', 'R'),
        ('BRL', 'FR', 'Real brésilien', 'Immobilier', 'R'),
        ('IRR', 'FR', 'Rial iranien', '﷼', '﷼'),
        ('OMR', 'FR', 'Rial omanais', 'ريال', 'ر'),
        ('KHR', 'FR', 'Riel', 'Riel', 'r'),
        ('MYR', 'FR', 'Ringgit Malaisien', 'ريڠڬيت', 'R'),
        ('QAR', 'FR', 'Riyal du Qatar', 'ريال', 'ر'),
        ('YER', 'FR', 'Riyal du Yémen', 'ريال', 'ر'),
        ('SAR', 'FR', 'Riyal saoudien', 'ريال', 'ر'),
        ('RUB', 'FR', 'rouble russe', 'Рубль', 'р'),
        ('MUR', 'FR', 'Roupie de Maurice', 'Roupie', 'R'),
        ('LKR', 'FR', 'Roupie de Sri Lanka', 'Roupie', 'R'),
        ('INR', 'FR', 'Roupie indienne', 'Roupie', 'R'),
        ('NPR', 'FR', 'Roupie Népalaise', 'Roupie', 'N'),
        ('PKR', 'FR', 'Roupie pakistanaise', 'Roupie', 'R'),
        ('RCS', 'FR', 'Roupie Seychelloise', 'Roupie', 'S'),
        ('MVR', 'FR', 'Rufiyaa', 'Rufiyaa', 'R'),
        ('IDR', 'FR', 'Rupiah', 'Rupiah', 'R'),
        ('RWF', 'FR', 'Rwanda Franc', 'Franc', 'R'),
        ('SHP', 'FR', 'Saint Helena Pound', 'Livre', '£'),
        ('SOS', 'FR', 'Shilling de Somalie', 'Shilin', 'S'),
        ('KES', 'FR', 'Shilling kenyan', 'Shilling', 'K'),
        ('UGX', 'FR', 'Shilling ougandais', 'Shilling', 'U'),
        ('TZS', 'FR', 'Shilling tanzanien', 'Shilingi', '/'),
        ('KGS', 'FR', 'Som', 'сом', 'с'),
        ('TJS', 'FR', 'Somoni', 'Сомонӣ', 'С'),
        ('UZS', 'FR', 'Sum ouzbek', 'Сўм', 'с'),
        ('BDT', 'FR', 'Taka', 'টাকা', '৳'),
        ('WST', 'FR', 'Tala', 'Tala', 'W'),
        ('KZT', 'FR', 'Tenge', 'Теңгесі', '〒'),
        ('TTD', 'FR', 'Trinité-et-Tobago Dollar', 'Dollar', '$'),
        ('MNT', 'FR', 'Tugrik', 'Төгрөг', '₮'),
        ('USD', 'FR', 'US Dollar', 'Dollar', '$'),
        ('VUV', 'FR', 'Vatu', 'Vatu', 'V'),
        ('KRW', 'FR', 'won', '원', '₩'),
        ('CNY', 'FR', 'yuan chinois', '人民币', '¥'),
        ('PLN', 'FR', 'Zloty polish', 'Złoty', 'z'),
        ('AFN', 'IT', 'Afghani afgano', 'افغانۍ', '؋'),
        ('MGA', 'IT', 'Ariary malgascio', 'Ariary', 'F'),
        ('THB', 'IT', 'Baht thailandese', 'บาทไทย', '฿'),
        ('PAB', 'IT', 'Balboa panamense', 'Balboa', 'B'),
        ('ETB', 'IT', 'Birr etiope', 'Birr', 'B'),
        ('VEF', 'IT', 'Bolivar venezuelano', 'Bolívar', 'B'),
        ('BOB', 'IT', 'Boliviano boliviano', 'Boliviano', 'B'),
        ('GHS', 'IT', 'Cedi ghanese', 'Cedi', 'G'),
        ('CRC', 'IT', 'Colón costaricano', 'Colón', '₡'),
        ('NIO', 'IT', 'Cordoba nicaraguense', 'Córdoba', 'C'),
        ('CZK', 'IT', 'Corona ceca', 'Koruna', 'K'),
        ('DKK', 'IT', 'Corona danese', 'Krone', 'K'),
        ('ISK', 'IT', 'Corona islandese', 'Króna', 'k'),
        ('NOK', 'IT', 'Corona norvegese', 'Krone', 'k'),
        ('SEK', 'IT', 'Corona svedese', 'Krona', 'k'),
        ('GMD', 'IT', 'Dalasi gambese', 'Dalasi', 'D'),
        ('DZD', 'IT', 'Dinaro algerino', 'دينار', 'د'),
        ('BHD', 'IT', 'Dinaro del Bahrain', 'دينار', '.'),
        ('JOD', 'IT', 'Dinaro giordano', 'دينار', 'د'),
        ('IQD', 'IT', 'Dinaro iracheno', 'دينار', 'ع'),
        ('KWD', 'IT', 'Dinaro kuwaitiano', 'دينار', 'د'),
        ('LYD', 'IT', 'Dinaro libico', 'دينار', 'ل'),
        ('MKD', 'IT', 'Dinaro macedone', 'Денар', 'д'),
        ('RSD', 'IT', 'Dinaro serbo', 'Динар', 'д'),
        ('TND', 'IT', 'Dinaro tunisino', 'دينار', 'د'),
        ('AED', 'IT', 'Dirham degli Emirati Arabi Uniti', 'درهم', 'د'),
        ('MAD', 'IT', 'Dirham marocchino', 'درهم', 'د'),
        ('STD', 'IT', 'Dobra di São Tomé e Príncipe', 'Dobra', 'D'),
        ('AUD', 'IT', 'Dollaro australiano', 'Dollar', '$'),
        ('CAD', 'IT', 'Dollaro canadese', 'Dollar', '$'),
        ('XCD', 'IT', 'Dollaro dei Caraibi Orientali', 'Dollar', 'E'),
        ('BZD', 'IT', 'Dollaro del Belize', 'Dollar', 'B'),
        ('BND', 'IT', 'Dollaro del Brunei', 'Ringgit', 'B'),
        ('BMD', 'IT', 'Dollaro della Bermuda', 'Dollar', 'B'),
        ('GYD', 'IT', 'Dollaro della Guyana', 'Dollar', 'G'),
        ('BSD', 'IT', 'Dollaro delle Bahamas', 'Dollar', 'B'),
        ('KYD', 'IT', 'Dollaro delle Cayman', 'Dollar', '$'),
        ('FJD', 'IT', 'Dollaro delle Figi', 'Dollar', 'F'),
        ('SBD', 'IT', 'Dollaro delle Salomone', 'Dollar', 'S'),
        ('BBD', 'IT', 'Dollaro di Barbados', 'Dollar', 'B'),
        ('HKD', 'IT', 'Dollaro di Hong Kong', '香港圓', 'H'),
        ('SGD', 'IT', 'Dollaro di Singapore', '新加坡元', 'S'),
        ('TTD', 'IT', 'Dollaro di Trinidad e Tobago', 'Dollar', '$'),
        ('JMD', 'IT', 'Dollaro giamaicano', 'Dollar', '$'),
        ('LRD', 'IT', 'Dollaro liberiano', 'Dollar', 'L'),
        ('NAD', 'IT', 'Dollaro namibiano', 'Dollar', 'N'),
        ('NZD', 'IT', 'Dollaro neozelandese', 'Dollar', '$'),
        ('USD', 'IT', 'Dollaro statunitense', 'Dollar', '$'),
        ('SRD', 'IT', 'Dollaro surinamese', 'Dollar', '$'),
        ('ZWL', 'IT', 'Dollaro zimbabwiano', 'Dollar', '$'),
        ('VND', 'IT', 'Đồng vietnamita', 'đồng', '₫'),
        ('AMD', 'IT', 'Dram armeno', 'Դրամ', 'դ'),
        ('CVE', 'IT', 'Escudo di Capo Verde', 'Escudo', 'E'),
        ('EUR', 'IT', 'Euro', 'Euro', '€'),
        ('AWG', 'IT', 'Fiorino arubano', 'Florijn', 'A'),
        ('ANG', 'IT', 'Fiorino delle Antille olandesi', 'Gulden', 'N'),
        ('HUF', 'IT', 'Fiorino ungherese', 'Forint', 'F'),
        ('XOF', 'IT', 'Franco CFA BCEAO', 'Franc', 'C'),
        ('XAF', 'IT', 'Franco CFA BEAC', 'Franc', 'C'),
        ('XPF', 'IT', 'Franco CFP', 'Franc', 'F'),
        ('CDF', 'IT', 'Franco congolese', 'Franc', 'F'),
        ('BIF', 'IT', 'Franco del Burundi', 'Franc', 'F'),
        ('KMF', 'IT', 'Franco delle Comore', 'Franc', 'F'),
        ('DJF', 'IT', 'Franco gibutiano', 'الفرنك', 'F'),
        ('GNF', 'IT', 'Franco guineano', 'Franc', 'F'),
        ('RWF', 'IT', 'Franco ruandese', 'Franc', 'R'),
        ('CHF', 'IT', 'Franco svizzero', 'Franc', 'F'),
        ('HTG', 'IT', 'Gourde haitiano', 'Gourde', 'G'),
        ('UAH', 'IT', 'Grivnia ucraina', 'Гривня', '₴'),
        ('PYG', 'IT', 'Guarani paraguaiano', 'Guaraní', '₲'),
        ('GWP', 'IT', 'Guinea-Bissau Peso', 'Peso', '$'),
        ('PGK', 'IT', 'Kina papuana', 'Kina', 'K'),
        ('LAK', 'IT', 'Kip laotiano', 'ກີບ', '₭'),
        ('EEK', 'IT', 'Kroon', 'Kroon', 'K'),
        ('HRK', 'IT', 'Kuna croata', 'Kuna', 'k'),
        ('MWK', 'IT', 'Kwacha malawiano', 'Kwacha', 'M'),
        ('ZMK', 'IT', 'Kwacha zambiano', 'Kwacha', 'Z'),
        ('AOA', 'IT', 'Kwanza angolano', 'Kwanza', 'K'),
        ('MMK', 'IT', 'Kyat birmano', 'Kyat', 'K'),
        ('GEL', 'IT', 'Lari georgiano', 'ლარი', 'l'),
        ('LVL', 'IT', 'Lats lettone', 'Lats', 'L'),
        ('ALL', 'IT', 'Lek albanese', 'Leku', 'L'),
        ('HNL', 'IT', 'Lempira honduregna', 'Lempira', 'L'),
        ('SLL', 'IT', 'Leone sierraleonese', 'Leone', 'L'),
        ('MDL', 'IT', 'Leu moldavo', 'Leu', 'l'),
        ('SZL', 'IT', 'Lilangeni dello Swaziland', 'Lilangeni', 'L'),
        ('EGP', 'IT', 'Lira egiziana', 'الجنيه', '£'),
        ('LBP', 'IT', 'Lira libanese (o sterlina)', 'ليرة,', 'ل'),
        ('SYP', 'IT', 'Lira siriana (o sterlina)', 'الليرة', 'S'),
        ('LTL', 'IT', 'Lita lituano', 'Litas', 'L'),
        ('LSL', 'IT', 'Loti lesothiano', 'Loti', 'L'),
        ('AZN', 'IT', 'Manat azero', 'Manatı', 'м'),
        ('TMT', 'IT', 'Manat turkmeno', 'Манат', 'm'),
        ('BAM', 'IT', 'Marco bosniaco', 'Marka', 'K'),
        ('MZN', 'IT', 'Metical mozambicano', 'Metical', 'M'),
        ('NGN', 'IT', 'Naira nigeriana', 'Naira', '₦'),
        ('ERN', 'IT', 'Nakfa eritreo', 'Nakfa', 'N'),
        ('BTN', 'IT', 'Ngultrum del Bhutan', 'དངུལ་ཀྲམ', 'N'),
        ('PEN', 'IT', 'Nuevo sol peruviano', 'Sol', 'S'),
        ('TRY', 'IT', 'Nuova lira turca', 'Lirası', 'T'),
        ('TWD', 'IT', 'Nuovo dollaro taiwanese', '新臺幣', '$'),
        ('RON', 'IT', 'Nuovo leu rumeno', 'Leu', 'L'),
        ('BGN', 'IT', 'Nuovo lev bulgaro', 'лев', 'л'),
        ('ILS', 'IT', 'Nuovo siclo israeliano', 'שקל', '₪'),
        ('MRO', 'IT', 'Ouguiya mauritana', 'أوقية', 'U'),
        ('TOP', 'IT', 'Paʻanga tongano', 'Pa\'anga', 'T'),
        ('MOP', 'IT', 'Pataca di Macao', '澳門圓', 'M'),
        ('ARS', 'IT', 'Peso argentino', 'Peso', '$'),
        ('CLP', 'IT', 'Peso cileno', 'Peso', '$'),
        ('COP', 'IT', 'Peso colombiano', 'Peso', 'C'),
        ('CUP', 'IT', 'Peso cubano', 'Peso', '$'),
        ('DOP', 'IT', 'Peso dominicano', 'Peso', 'R'),
        ('PHP', 'IT', 'Peso filippino', 'Piso', '₱'),
        ('MXN', 'IT', 'Peso messicano', 'Peso', '$'),
        ('UYU', 'IT', 'Peso uruguaiano', 'Peso', '$'),
        ('BWP', 'IT', 'Pula del Botswana', 'Pula', 'P'),
        ('GTQ', 'IT', 'Quetzal guatemalteco', 'Quetzal', 'Q'),
        ('ZAR', 'IT', 'Rand sudafricano', 'Rand', 'R'),
        ('BRL', 'IT', 'Real brasiliano', 'Real', 'R'),
        ('CNY', 'IT', 'Renminbi cinese (Yuan)', '人民币', '¥'),
        ('QAR', 'IT', 'Rial del Qatar', 'ريال', 'ر'),
        ('OMR', 'IT', 'Rial dell\'Oman', 'ريال', 'ر'),
        ('IRR', 'IT', 'Rial iraniano', '﷼', '﷼'),
        ('SAR', 'IT', 'Rial saudita', 'ريال', 'ر'),
        ('YER', 'IT', 'Rial yemenita', 'ريال', 'ر'),
        ('KHR', 'IT', 'Riel cambogiano', 'Riel', 'r'),
        ('MYR', 'IT', 'Ringgit malese', 'ريڠڬيت', 'R'),
        ('BYR', 'IT', 'Rublo bielorusso', 'Рубель', 'B'),
        ('RUB', 'IT', 'Rublo russo', 'Рубль', 'р'),
        ('MVR', 'IT', 'Rufiyaa delle Maldive', 'Rufiyaa', 'R'),
        ('SCR', 'IT', 'Rupia delle Seychelles', 'Roupie', 'S'),
        ('INR', 'IT', 'Rupia indiana', 'Rupee', 'R'),
        ('IDR', 'IT', 'Rupia indonesiana', 'Rupiah', 'R'),
        ('MUR', 'IT', 'Rupia mauriziana', 'Roupie', 'R'),
        ('NPR', 'IT', 'Rupia nepalese', 'Rupee', 'N'),
        ('PKR', 'IT', 'Rupia pakistana', 'Rupee', 'R'),
        ('LKR', 'IT', 'Rupia singalese', 'Rupee', 'R'),
        ('SVC', 'IT', 'Salvadoran Colon', 'Colón', '₡'),
        ('KES', 'IT', 'Scellino keniota', 'Shilling', 'K'),
        ('SOS', 'IT', 'Scellino somalo', 'Shilin', 'S'),
        ('TZS', 'IT', 'Scellino tanzaniano', 'Shilingi', '/'),
        ('UGX', 'IT', 'Scellino ugandese', 'Shilling', 'U'),
        ('KGS', 'IT', 'Som kirghizo', 'сом', 'с'),
        ('UZS', 'IT', 'Som uzbeco', 'Сўм', 'с'),
        ('TJS', 'IT', 'Somoni tagico', 'Сомонӣ', 'С'),
        ('GBP', 'IT', 'Sterlina britannica', 'Pound', '£'),
        ('FKP', 'IT', 'Sterlina delle Falkland', 'Pound', '£'),
        ('GIP', 'IT', 'Sterlina di Gibilterra', 'Pound', '£'),
        ('SHP', 'IT', 'Sterlina di Sant\'Elena', 'Pound', '£'),
        ('SDG', 'IT', 'Sterlina sudanese', 'جنيه', '£'),
        ('BDT', 'IT', 'Taka bengalese', 'টাকা', '৳'),
        ('WST', 'IT', 'Tala samoano', 'Tālā', 'W'),
        ('KZT', 'IT', 'Tenge kazako', 'Теңгесі', '〒'),
        ('MNT', 'IT', 'Tugrik mongolo', 'Төгрөг', '₮'),
        ('VUV', 'IT', 'Vatu di Vanuatu', 'Vatu', 'V'),
        ('KPW', 'IT', 'Won nordcoreano', '원', '₩'),
        ('KRW', 'IT', 'Won sudcoreano', '원', '₩'),
        ('JPY', 'IT', 'Yen giapponese', '日本円', '¥'),
        ('PLN', 'IT', 'Złoty polacco', 'Złoty', 'z');
>>>>>>> 6954e78362385e3a9cd10d2767fde9cef6869544
