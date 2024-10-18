CREATE SCHEMA IF NOT EXISTS `pizza_Haseeb` DEFAULT CHARACTER SET utf8;

CREATE TABLE IF NOT EXISTS `pizza_Haseeb`.`customer` (
  `customer_id` INT NOT NULL,
  `customer_name` VARCHAR(45) NULL,
  `phone` VARCHAR(45) NULL,
  `address` VARCHAR(45) NULL,
  `city` VARCHAR(45) NULL,
  `state` VARCHAR(45) NULL,
  `zipcode` VARCHAR(45) NULL,
  PRIMARY KEY (`customer_id`))
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `pizza_Haseeb`.`employee` (
  `employee_id` INT NOT NULL,
  `employee_name` VARCHAR(45) NULL,
  PRIMARY KEY (`employee_id`))
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `pizza_Haseeb`.`order` (
  `order_id` INT NOT NULL,
  `order_date` DATE NULL,
  `order_time` TIME NULL,
  `employee_id` INT NULL,
  `customer_id` INT NULL,
  PRIMARY KEY (`order_id`),
  INDEX `employee_id_idx` (`employee_id` ASC) VISIBLE,
  INDEX `customer_id_idx` (`customer_id` ASC) VISIBLE,
  CONSTRAINT `employee_id`
    FOREIGN KEY (`employee_id`)
    REFERENCES `pizza_Haseeb`.`employee` (`employee_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `customer_id`
    FOREIGN KEY (`customer_id`)
    REFERENCES `pizza_Haseeb`.`customer` (`customer_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `pizza_Haseeb`.`size` (
  `size_id` INT NOT NULL,
  `size_description` VARCHAR(45) NULL,
  `size_price` DECIMAL(5,2) NULL,
  PRIMARY KEY (`size_id`))
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `pizza_Haseeb`.`pizza` (
  `pizza _id` INT NOT NULL,
  `size_id` INT NULL,
  PRIMARY KEY (`pizza _id`),
  INDEX `size_id_idx` (`size_id` ASC) VISIBLE,
  CONSTRAINT `size_id`
    FOREIGN KEY (`size_id`)
    REFERENCES `pizza_Haseeb`.`size` (`size_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `pizza_Haseeb`.` pizza_order` (
  `p_id` INT NOT NULL,
  `order_id` INT NULL,
  `quantity` INT NULL,
  INDEX `p_id_idx` (`p_id` ASC) VISIBLE,
  INDEX `order_id_idx` (`order_id` ASC) VISIBLE,
  CONSTRAINT `p_id`
    FOREIGN KEY (`p_id`)
    REFERENCES `pizza_Haseeb`.`pizza` (`pizza _id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `order_id`
    FOREIGN KEY (`order_id`)
    REFERENCES `pizza_Haseeb`.`order` (`order_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `pizza_Haseeb`.`toppings` (
  `topping_id` INT NOT NULL,
  `topping_description` VARCHAR(100) NULL,
  `topping_price` DECIMAL(5,2) NULL,
  PRIMARY KEY (`topping_id`))
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `pizza_Haseeb`.`pizza_toppings` (
  `pizza_id` INT NOT NULL,
  `topping_id` INT NOT NULL,
  PRIMARY KEY (`pizza_id`, `topping_id`),
  INDEX `topping_id_idx` (`topping_id` ASC) VISIBLE,
  CONSTRAINT `pizza_id`
    FOREIGN KEY (`pizza_id`)
    REFERENCES `pizza_Haseeb`.`pizza` (`pizza _id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `topping_id`
    FOREIGN KEY (`topping_id`)
    REFERENCES `pizza_Haseeb`.`toppings` (`topping_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;
