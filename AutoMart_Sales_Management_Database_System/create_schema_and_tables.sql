CREATE database Kelvin_AutoMart;
use Kelvin_AutoMart;

CREATE TABLE car (
  `car_id` INT NOT NULL,
  `make` VARCHAR(45) NULL,
  `model` VARCHAR(45) NULL,
  `year` YEAR NULL,
  `price` DECIMAL NULL,
  `status` VARCHAR(45) NULL,
  PRIMARY KEY (`car_id`))
ENGINE = InnoDB;

-- creating table customers
CREATE TABLE  customers (
  `customer_id` INT NOT NULL,
  `first_name` VARCHAR(45) NULL,
  `last_name` VARCHAR(45) NULL,
  `phone_number` VARCHAR(45) NULL,
  `email` VARCHAR(45) NULL,
  `address` VARCHAR(100) NULL,
  PRIMARY KEY (`customer_id`))
ENGINE = InnoDB;

-- creating table employees
CREATE TABLE employees (
  `employee_id` INT NOT NULL,
  `employee_name` VARCHAR(45) NULL,
  `position` VARCHAR(45) NULL,
  `phone_number` VARCHAR(45) NULL,
  `email` VARCHAR(45) NULL,
  PRIMARY KEY (`employee_id`))
ENGINE = InnoDB;

-- creating table suppliers 

CREATE TABLE suppliers (
  `supplier_id` INT NOT NULL,
  `supplier_name` VARCHAR(45) NULL,
  `contact_number` VARCHAR(45) NULL,
  `email` VARCHAR(45) NULL,
  `address` VARCHAR(45) NULL,
  PRIMARY KEY (`supplier_id`))
ENGINE = InnoDB;

-- creating table spare_parts

CREATE TABLE  spares (
  `part_id` INT NOT NULL,
  `part_name` VARCHAR(45) NULL,
  `part_description` VARCHAR(100) NULL,
  `quantity_in_stock` INT NULL,
  `price` DECIMAL NULL,
  `supplier_id` INT NULL,
  PRIMARY KEY (`part_id`),
  CONSTRAINT `supplier_Id`
    FOREIGN KEY (`supplier_id`)
    REFERENCES suppliers (`supplier_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

-- creatting table part sales

CREATE TABLE partsales (
  `sale_id` INT NOT NULL,
  `part_id` INT NULL,
  `customer_id` INT NULL,
  `employee_id` INT NULL,
  `quantity` INT NULL,
  `sale_date` DATE NULL,
  `total_price` DECIMAL NULL,
  PRIMARY KEY (`sale_id`),
  CONSTRAINT `partId`
    FOREIGN KEY (`part_id`)
    REFERENCES  spares (`part_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `customerId`
    FOREIGN KEY (`customer_id`)
    REFERENCES customers (`customer_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `employeeId`
    FOREIGN KEY (`employee_id`)
    REFERENCES employees (`employee_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

-- creating table car_sales
CREATE TABLE carsales (
  `sale_id` INT NOT NULL,
  `car_id` INT NULL,
  `customer_id` INT NULL,
  `employee_id` INT NULL,
  `sale_date` DATE NULL,
  `sale_price` DECIMAL NULL,
  PRIMARY KEY (`sale_id`),
  CONSTRAINT `carId`
    FOREIGN KEY (`car_id`)
    REFERENCES car (`car_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `customer_Id`
    FOREIGN KEY (`customer_id`)
    REFERENCES  customers (`customer_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `employee_Id`
    FOREIGN KEY (`employee_id`)
    REFERENCES employees (`employee_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

