SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `imgstock` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
USE `imgstock` ;

-- -----------------------------------------------------
-- Table `img_policities`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `img_policities` ;

CREATE  TABLE IF NOT EXISTS `img_policities` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  `acronym` VARCHAR(45) NOT NULL ,
  `status` TINYINT NOT NULL DEFAULT 1 ,
  `visible` TINYINT(1) NOT NULL DEFAULT 1 ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `img_image_status`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `img_image_status` ;

CREATE  TABLE IF NOT EXISTS `img_image_status` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  `status` TINYINT NOT NULL DEFAULT 1 ,
  `visible` TINYINT(1) NOT NULL DEFAULT 1 ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `img_users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `img_users` ;

CREATE  TABLE IF NOT EXISTS `img_users` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `email` VARCHAR(100) NOT NULL ,
  `password` VARCHAR(500) NOT NULL ,
  `cash` DECIMAL(10,2) NOT NULL DEFAULT 0 ,
  `socialreason_completename` VARCHAR(45) NULL ,
  `cpf_cnpj` VARCHAR(45) NULL ,
  `state_register` VARCHAR(45) NULL ,
  `city` VARCHAR(45) NULL ,
  `state` VARCHAR(45) NULL ,
  `country_id` INT NULL ,
  `landline` VARCHAR(45) NULL ,
  `mobile` VARCHAR(45) NULL ,
  `status` TINYINT NOT NULL DEFAULT 1 ,
  `visible` TINYINT(1) NOT NULL DEFAULT 1 ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `img_images`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `img_images` ;

CREATE  TABLE IF NOT EXISTS `img_images` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `policy_id` INT NOT NULL ,
  `status_id` INT NOT NULL ,
  `uploader_id` INT NOT NULL ,
  `file` VARCHAR(500) NOT NULL DEFAULT 'problem.jpg' ,
  `credits_base` DECIMAL(10,2) NULL ,
  `validity` DATE NOT NULL ,
  `title` VARCHAR(100) NULL ,
  `description` TEXT NULL ,
  `people_qtd` INT NOT NULL DEFAULT 0 ,
  `gender` SET('0','1','2','3') NOT NULL DEFAULT 0 ,
  `age_group_child` TINYINT(1) NOT NULL DEFAULT 0 ,
  `age_group_adult` TINYINT(1) NOT NULL DEFAULT 0 ,
  `age_group_middle` TINYINT(1) NOT NULL DEFAULT 0 ,
  `age_group_elder` TINYINT(1) NOT NULL DEFAULT 0 ,
  `uploaded_at` DATETIME NOT NULL ,
  `taken_at` DATETIME NOT NULL ,
  `status` TINYINT NOT NULL DEFAULT 1 ,
  `visible` TINYINT(1) NOT NULL DEFAULT 1 ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
  INDEX `fk_img_images_img_policities1_idx` (`policy_id` ASC) ,
  INDEX `fk_img_images_img_image_status1_idx` (`status_id` ASC) ,
  INDEX `fk_img_images_img_users1_idx` (`uploader_id` ASC) ,
  CONSTRAINT `fk_img_images_img_policities1`
    FOREIGN KEY (`policy_id` )
    REFERENCES `img_policities` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_img_images_img_image_status1`
    FOREIGN KEY (`status_id` )
    REFERENCES `img_image_status` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_img_images_img_users1`
    FOREIGN KEY (`uploader_id` )
    REFERENCES `img_users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `img_tags`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `img_tags` ;

CREATE  TABLE IF NOT EXISTS `img_tags` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `value` VARCHAR(45) NOT NULL ,
  `status` TINYINT NOT NULL DEFAULT 1 ,
  `visible` TINYINT(1) NOT NULL DEFAULT 1 ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `img_purchases`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `img_purchases` ;

CREATE  TABLE IF NOT EXISTS `img_purchases` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `user_id` INT NOT NULL ,
  `total_credits` DECIMAL(10,2) NOT NULL ,
  `date` DATETIME NOT NULL ,
  `status` TINYINT NOT NULL DEFAULT 1 ,
  `visible` TINYINT(1) NOT NULL DEFAULT 1 ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_img_purchases_img_users1_idx` (`user_id` ASC) ,
  CONSTRAINT `fk_img_purchases_img_users1`
    FOREIGN KEY (`user_id` )
    REFERENCES `img_users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `img_regions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `img_regions` ;

CREATE  TABLE IF NOT EXISTS `img_regions` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  `percentage` INT NOT NULL ,
  `status` TINYINT NOT NULL DEFAULT 1 ,
  `visible` TINYINT(1) NOT NULL DEFAULT 1 ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `img_periods`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `img_periods` ;

CREATE  TABLE IF NOT EXISTS `img_periods` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  `percentage` INT NOT NULL ,
  `status` TINYINT NOT NULL DEFAULT 1 ,
  `visible` TINYINT(1) NOT NULL DEFAULT 1 ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `img_sectors`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `img_sectors` ;

CREATE  TABLE IF NOT EXISTS `img_sectors` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  `percentage` INT NOT NULL ,
  `status` TINYINT NOT NULL DEFAULT 1 ,
  `visible` TINYINT(1) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `img_application`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `img_application` ;

CREATE  TABLE IF NOT EXISTS `img_application` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  `status` TINYINT NOT NULL DEFAULT 1 ,
  `visible` TINYINT(1) NOT NULL DEFAULT 1 ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `img_subapplication`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `img_subapplication` ;

CREATE  TABLE IF NOT EXISTS `img_subapplication` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `application_id` INT NOT NULL ,
  `name` VARCHAR(45) NOT NULL ,
  `percentage` INT NOT NULL DEFAULT 0 ,
  `status` TINYINT NOT NULL DEFAULT 1 ,
  `visible` TINYINT(1) NOT NULL DEFAULT 1 ,
  PRIMARY KEY (`id`, `application_id`) ,
  INDEX `fk_img_sector_img_application1_idx` (`application_id` ASC) ,
  CONSTRAINT `fk_img_sector_img_application1`
    FOREIGN KEY (`application_id` )
    REFERENCES `img_application` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `img_sizes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `img_sizes` ;

CREATE  TABLE IF NOT EXISTS `img_sizes` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  `axis_x` FLOAT NOT NULL ,
  `axis_y` FLOAT NOT NULL ,
  `credits` DECIMAL(10,2) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `img_images_sizes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `img_images_sizes` ;

CREATE  TABLE IF NOT EXISTS `img_images_sizes` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `image_id` INT NOT NULL ,
  `size_id` INT NOT NULL ,
  `credits` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`, `image_id`, `size_id`) ,
  INDEX `fk_img_images_has_img_image_sizes_img_images1_idx` (`image_id` ASC) ,
  INDEX `fk_img_images_sizes_img_sizes1_idx` (`size_id` ASC) ,
  CONSTRAINT `fk_img_images_has_img_image_sizes_img_images1`
    FOREIGN KEY (`image_id` )
    REFERENCES `img_images` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_img_images_sizes_img_sizes1`
    FOREIGN KEY (`size_id` )
    REFERENCES `img_sizes` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `img_purchase_image`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `img_purchase_image` ;

CREATE  TABLE IF NOT EXISTS `img_purchase_image` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `purchase_id` INT NOT NULL ,
  `credits` DECIMAL(10,2) NOT NULL DEFAULT 0 ,
  `validity` DATE NOT NULL ,
  `region_id` INT NULL ,
  `period_id` INT NULL ,
  `sector_id` INT NULL ,
  `category_use_id` INT NULL ,
  `subapplication_id` INT NULL ,
  `application_id` INT NULL ,
  `images_sizes_id` INT NOT NULL ,
  `image_id` INT NOT NULL ,
  `size_id` INT NOT NULL ,
  PRIMARY KEY (`id`, `purchase_id`) ,
  INDEX `fk_img_images_has_img_purchase_img_purchase1_idx` (`purchase_id` ASC) ,
  INDEX `fk_img_purchase_image_img_regions1_idx` (`region_id` ASC) ,
  INDEX `fk_img_purchase_image_img_periods1_idx` (`period_id` ASC) ,
  INDEX `fk_img_purchase_image_img_sectors1_idx` (`sector_id` ASC) ,
  INDEX `fk_img_purchase_image_img_subapplication1_idx` (`subapplication_id` ASC, `application_id` ASC) ,
  INDEX `fk_img_purchase_image_img_images_sizes1_idx` (`images_sizes_id` ASC, `image_id` ASC, `size_id` ASC) ,
  CONSTRAINT `fk_img_images_has_img_purchase_img_purchase1`
    FOREIGN KEY (`purchase_id` )
    REFERENCES `img_purchases` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_img_purchase_image_img_regions1`
    FOREIGN KEY (`region_id` )
    REFERENCES `img_regions` (`id` )
    ON DELETE SET NULL
    ON UPDATE SET NULL,
  CONSTRAINT `fk_img_purchase_image_img_periods1`
    FOREIGN KEY (`period_id` )
    REFERENCES `img_periods` (`id` )
    ON DELETE SET NULL
    ON UPDATE SET NULL,
  CONSTRAINT `fk_img_purchase_image_img_sectors1`
    FOREIGN KEY (`sector_id` )
    REFERENCES `img_sectors` (`id` )
    ON DELETE SET NULL
    ON UPDATE SET NULL,
  CONSTRAINT `fk_img_purchase_image_img_subapplication1`
    FOREIGN KEY (`subapplication_id` , `application_id` )
    REFERENCES `img_subapplication` (`id` , `application_id` )
    ON DELETE SET NULL
    ON UPDATE SET NULL,
  CONSTRAINT `fk_img_purchase_image_img_images_sizes1`
    FOREIGN KEY (`images_sizes_id` , `image_id` )
    REFERENCES `img_images_sizes` (`id` , `image_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `img_tag_categorys`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `img_tag_categorys` ;

CREATE  TABLE IF NOT EXISTS `img_tag_categorys` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  `status` TINYINT NOT NULL ,
  `visible` TINYINT(1) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `img_roles`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `img_roles` ;

CREATE  TABLE IF NOT EXISTS `img_roles` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  `status` TINYINT NOT NULL DEFAULT 1 ,
  `visible` TINYINT(1) NOT NULL DEFAULT 1 ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `img_images_tags`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `img_images_tags` ;

CREATE  TABLE IF NOT EXISTS `img_images_tags` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `image_id` INT NOT NULL ,
  `tag_id` INT NOT NULL ,
  PRIMARY KEY (`id`, `image_id`, `tag_id`) ,
  INDEX `fk_img_images_has_img_tags_img_tags1_idx` (`tag_id` ASC) ,
  INDEX `fk_img_images_has_img_tags_img_images1_idx` (`image_id` ASC) ,
  CONSTRAINT `fk_img_images_has_img_tags_img_images1`
    FOREIGN KEY (`image_id` )
    REFERENCES `img_images` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_img_images_has_img_tags_img_tags1`
    FOREIGN KEY (`tag_id` )
    REFERENCES `img_tags` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `img_tags_categorys`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `img_tags_categorys` ;

CREATE  TABLE IF NOT EXISTS `img_tags_categorys` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `tag_id` INT NOT NULL ,
  `category_id` INT NOT NULL ,
  PRIMARY KEY (`id`, `tag_id`, `category_id`) ,
  INDEX `fk_img_tags_has_img_tag_categorys_img_tag_categorys1_idx` (`category_id` ASC) ,
  INDEX `fk_img_tags_has_img_tag_categorys_img_tags1_idx` (`tag_id` ASC) ,
  CONSTRAINT `fk_img_tags_has_img_tag_categorys_img_tags1`
    FOREIGN KEY (`tag_id` )
    REFERENCES `img_tags` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_img_tags_has_img_tag_categorys_img_tag_categorys1`
    FOREIGN KEY (`category_id` )
    REFERENCES `img_tag_categorys` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `img_users_roles`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `img_users_roles` ;

CREATE  TABLE IF NOT EXISTS `img_users_roles` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `user_id` INT NOT NULL ,
  `role_id` INT NOT NULL ,
  PRIMARY KEY (`id`, `user_id`, `role_id`) ,
  INDEX `fk_img_roles_has_img_users_img_users1_idx` (`user_id` ASC) ,
  INDEX `fk_img_roles_has_img_users_img_roles1_idx` (`role_id` ASC) ,
  CONSTRAINT `fk_img_roles_has_img_users_img_roles1`
    FOREIGN KEY (`role_id` )
    REFERENCES `img_roles` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_img_roles_has_img_users_img_users1`
    FOREIGN KEY (`user_id` )
    REFERENCES `img_users` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `img_credits_history`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `img_credits_history` ;

CREATE  TABLE IF NOT EXISTS `img_credits_history` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `credits` DECIMAL(10,2) NOT NULL ,
  `price` DECIMAL(10,2) NOT NULL ,
  `paypal_id` INT NOT NULL ,
  `date` DATETIME NOT NULL ,
  `img_users_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_img_credits_history_img_users1_idx` (`img_users_id` ASC) ,
  CONSTRAINT `fk_img_credits_history_img_users1`
    FOREIGN KEY (`img_users_id` )
    REFERENCES `img_users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `img_currency`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `img_currency` ;

CREATE  TABLE IF NOT EXISTS `img_currency` (
  `id` INT NOT NULL ,
  `name` VARCHAR(45) NOT NULL ,
  `dollar_dif` DECIMAL(10,2) NOT NULL ,
  `status` TINYINT NOT NULL DEFAULT 1 ,
  `visible` TINYINT(1) NOT NULL DEFAULT 1 ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `img_image_logs`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `img_image_logs` ;

CREATE  TABLE IF NOT EXISTS `img_image_logs` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `image_id` INT NOT NULL ,
  `description` TEXT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_img_image_logs_img_images1_idx` (`image_id` ASC) ,
  CONSTRAINT `fk_img_image_logs_img_images1`
    FOREIGN KEY (`image_id` )
    REFERENCES `img_images` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `img_problem_reports`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `img_problem_reports` ;

CREATE  TABLE IF NOT EXISTS `img_problem_reports` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `image_id` INT NOT NULL ,
  `email` VARCHAR(45) NOT NULL ,
  `message` TEXT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_img_problem_reports_img_images1_idx` (`image_id` ASC) ,
  CONSTRAINT `fk_img_problem_reports_img_images1`
    FOREIGN KEY (`image_id` )
    REFERENCES `img_images` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `img_credit_plans`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `img_credit_plans` ;

CREATE  TABLE IF NOT EXISTS `img_credit_plans` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `price_unit` DECIMAL(10,2) NOT NULL DEFAULT 0 ,
  `credits_unit` DECIMAL(10,2) NOT NULL DEFAULT 0 ,
  `status` TINYINT NOT NULL DEFAULT 1 ,
  `visible` TINYINT(1) NOT NULL DEFAULT 1 ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `img_lightbox`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `img_lightbox` ;

CREATE  TABLE IF NOT EXISTS `img_lightbox` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `image_id` INT NOT NULL ,
  `user_id` INT NOT NULL ,
  `date` VARCHAR(45) NOT NULL ,
  `status` TINYINT NOT NULL DEFAULT 1 ,
  `visible` TINYINT(1) NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_img_lightbox_img_images1_idx` (`image_id` ASC) ,
  INDEX `fk_img_lightbox_img_users1_idx` (`user_id` ASC) ,
  CONSTRAINT `fk_img_lightbox_img_images1`
    FOREIGN KEY (`image_id` )
    REFERENCES `img_images` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_img_lightbox_img_users1`
    FOREIGN KEY (`user_id` )
    REFERENCES `img_users` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `img_users_hierarchy`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `img_users_hierarchy` ;

CREATE  TABLE IF NOT EXISTS `img_users_hierarchy` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `master_id` INT NOT NULL ,
  `slave_id` INT NOT NULL ,
  `status` TINYINT(1) NOT NULL DEFAULT 1 ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_img_users_hierarchy_img_users1_idx` (`master_id` ASC) ,
  INDEX `fk_img_users_hierarchy_img_users2_idx` (`slave_id` ASC) ,
  CONSTRAINT `fk_img_users_hierarchy_img_users1`
    FOREIGN KEY (`master_id` )
    REFERENCES `img_users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_img_users_hierarchy_img_users2`
    FOREIGN KEY (`slave_id` )
    REFERENCES `img_users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `img_statistics`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `img_statistics` ;

CREATE  TABLE IF NOT EXISTS `img_statistics` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `image_id` INT NOT NULL ,
  `date` DATETIME NOT NULL ,
  `clicked` INT NOT NULL DEFAULT 0 ,
  `bought` INT NOT NULL DEFAULT 0 ,
  `searched` INT NOT NULL DEFAULT 0 ,
  `status` TINYINT NOT NULL DEFAULT 1 ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_img_statistics_img_images1_idx` (`image_id` ASC) ,
  CONSTRAINT `fk_img_statistics_img_images1`
    FOREIGN KEY (`image_id` )
    REFERENCES `img_images` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
