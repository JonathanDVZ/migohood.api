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
  `avatar` VARCHAR(250) NULL,
  `secondname` VARCHAR(45) NULL,
  `lastname` VARCHAR(45) NULL,
  `remember_token` VARCHAR(100) NULL,
  `confirm_token` VARCHAR(100) NULL,
  `address` VARCHAR(80) NULL,
  `city_id` INT NULL,
  `avatar_original` VARCHAR(250) NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_USER_CITY1_idx` (`city_id` ASC),
  CONSTRAINT `fk_USER_CITY1`
    FOREIGN KEY (`city_id`)
    REFERENCES `Migohood`.`city` (`id`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Migohood`.`service`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Migohood`.`service` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `date` DATE NULL,
  `zipcode` INT NULL,
  `city_id` INT NULL,
  `num_bathroom` INT NULL,
  `status` TINYINT(1) NOT NULL DEFAULT 0,
  `num_guest` INT NULL,
  `live` TINYINT(1) NULL,
  PRIMARY KEY (`id`),
  INDEX `cod_user_idx` (`user_id` ASC),
  INDEX `fk_service_city1_idx` (`city_id` ASC),
  CONSTRAINT `cod_user`
    FOREIGN KEY (`user_id`)
    REFERENCES `Migohood`.`user` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_service_city1`
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
  `code` INT NULL,
  `languaje` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Migohood`.`type_amenities`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Migohood`.`type_amenities` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `code` INT NULL,
  `languaje` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Migohood`.`amenities`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Migohood`.`amenities` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `category_id` INT NOT NULL,
  `type_amenities_id` INT NOT NULL,
  `languaje` VARCHAR(45) NOT NULL,
  `code` INT NULL,
  PRIMARY KEY (`id`),
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
-- Table `Migohood`.`image`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Migohood`.`image` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `ruta` VARCHAR(45) NOT NULL,
  `service_id` INT NOT NULL,
  `description` VARCHAR(300) NULL,
  PRIMARY KEY (`id`),
  INDEX `service_id` (`service_id` ASC),
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
  `code` INT NULL,
  `languaje` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id_type`),
  INDEX `fk_TYPE_CATEGORY1_idx` (`category_id` ASC),
  CONSTRAINT `fk_TYPE_CATEGORY1`
    FOREIGN KEY (`category_id`)
    REFERENCES `Migohood`.`category` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Migohood`.`accommodation`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Migohood`.`accommodation` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `code` INT NULL,
  `languaje` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
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
    REFERENCES `Migohood`.`amenities` (`id`)
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
  `code` INT NULL,
  `languaje` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Migohood`.`calendar`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Migohood`.`calendar` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `day` VARCHAR(45) NOT NULL,
  `code` INT NULL,
  `languaje` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Migohood`.`service_calendar`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Migohood`.`service_calendar` (
  `service_id` INT NOT NULL,
  `calendar_id` INT NOT NULL,
  `id` INT NOT NULL AUTO_INCREMENT,
  INDEX `fk_SERVICE_has_CALENDAR_CALENDAR1_idx` (`calendar_id` ASC),
  INDEX `fk_SERVICE_has_CALENDAR_SERVICE1_idx` (`service_id` ASC),
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_SERVICE_has_CALENDAR_SERVICE1`
    FOREIGN KEY (`service_id`)
    REFERENCES `Migohood`.`service` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_SERVICE_has_CALENDAR_CALENDAR1`
    FOREIGN KEY (`calendar_id`)
    REFERENCES `Migohood`.`calendar` (`id`)
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
  `service_id` INT NOT NULL,
  `endDate` DATETIME NULL,
  `price` FLOAT NOT NULL,
  `currency_id` INT NOT NULL,
  PRIMARY KEY (`starDate`, `service_id`),
  INDEX `fk_price_history_service1_idx` (`service_id` ASC),
  INDEX `fk_price_history_currency1_idx` (`currency_id` ASC),
  CONSTRAINT `fk_price_history_service1`
    FOREIGN KEY (`service_id`)
    REFERENCES `Migohood`.`service` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_price_history_currency1`
    FOREIGN KEY (`currency_id`)
    REFERENCES `Migohood`.`currency` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Migohood`.`type_number`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Migohood`.`type_number` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `type` VARCHAR(16) NOT NULL,
  `code` INT NULL,
  `languaje` VARCHAR(45) NOT NULL,
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
  `description` VARCHAR(100) NULL,
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
  `code` INT NULL,
  `languaje` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Migohood`.`bedroom_bed`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Migohood`.`bedroom_bed` (
  `bedroom_id` INT NOT NULL,
  `bed_id` INT NOT NULL,
  `quantity` INT NOT NULL,
  INDEX `fk_bedroom_has_bed_bed1_idx` (`bed_id` ASC),
  INDEX `fk_bedroom_has_bed_bedroom1_idx` (`bedroom_id` ASC),
  PRIMARY KEY (`bedroom_id`, `bed_id`),
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
  `code` INT NULL,
  `languaje` VARCHAR(45) NOT NULL,
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


-- -----------------------------------------------------
-- Table `Migohood`.`payment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Migohood`.`payment` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `type` VARCHAR(45) NOT NULL,
  `code` INT NULL,
  `languaje` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Migohood`.`service_accommodation`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Migohood`.`service_accommodation` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `service_id` INT NOT NULL,
  `accommodation_id` INT NOT NULL,
  INDEX `fk_service_has_accommodation_accommodation1_idx` (`accommodation_id` ASC),
  INDEX `fk_service_has_accommodation_service1_idx` (`service_id` ASC),
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_service_has_accommodation_service1`
    FOREIGN KEY (`service_id`)
    REFERENCES `Migohood`.`service` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_service_has_accommodation_accommodation1`
    FOREIGN KEY (`accommodation_id`)
    REFERENCES `Migohood`.`accommodation` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Migohood`.`service_payment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Migohood`.`service_payment` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `service_id` INT NOT NULL,
  `payment_id` INT NOT NULL,
  INDEX `fk_service_has_payment_payment1_idx` (`payment_id` ASC),
  INDEX `fk_service_has_payment_service1_idx` (`service_id` ASC),
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_service_has_payment_service1`
    FOREIGN KEY (`service_id`)
    REFERENCES `Migohood`.`service` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_service_has_payment_payment1`
    FOREIGN KEY (`payment_id`)
    REFERENCES `Migohood`.`payment` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Migohood`.`price_history_has_duration`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Migohood`.`price_history_has_duration` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `price_history_starDate` DATETIME NOT NULL,
  `price_history_service_id` INT NOT NULL,
  `duration_id` INT NOT NULL,
  INDEX `fk_price_history_has_duration_duration1_idx` (`duration_id` ASC),
  INDEX `fk_price_history_has_duration_price_history1_idx` (`price_history_starDate` ASC, `price_history_service_id` ASC),
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_price_history_has_duration_price_history1`
    FOREIGN KEY (`price_history_starDate` , `price_history_service_id`)
    REFERENCES `Migohood`.`price_history` (`starDate` , `service_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_price_history_has_duration_duration1`
    FOREIGN KEY (`duration_id`)
    REFERENCES `Migohood`.`duration` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Migohood`.`service_category`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Migohood`.`service_category` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `service_id` INT NOT NULL,
  `category_id` INT NOT NULL,
  INDEX `fk_service_has_category_category1_idx` (`category_id` ASC),
  INDEX `fk_service_has_category_service1_idx` (`service_id` ASC),
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_service_has_category_service1`
    FOREIGN KEY (`service_id`)
    REFERENCES `Migohood`.`service` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_service_has_category_category1`
    FOREIGN KEY (`category_id`)
    REFERENCES `Migohood`.`category` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
