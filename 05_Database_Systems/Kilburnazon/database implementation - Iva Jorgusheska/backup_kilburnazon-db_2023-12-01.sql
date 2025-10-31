-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 01, 2023 at 01:19 PM
-- Server version: 8.0.31
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kilburnazon`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `create_address_table` ()   BEGIN
		CREATE TABLE IF NOT EXISTS Address(
			postal_code VARCHAR(40) PRIMARY KEY,
			city VARCHAR(20),
			street VARCHAR(40),
			customer_id INT,
			warehouse_name VARCHAR(50),
			FOREIGN KEY (customer_id) REFERENCES Customer(ID),
			FOREIGN KEY (warehouse_name) REFERENCES Warehouse_Office(name)
		);
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `create_area_table` ()   BEGIN
		CREATE TABLE IF NOT EXISTS Area (
				`ID` INT PRIMARY KEY,
			    `name` VARCHAR(30)
				);
		CREATE INDEX idx_area_name
					ON Area (`name`);
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `create_audit_table` ()   BEGIN
		CREATE TABLE IF NOT EXISTS Audit(
			deleted_employee_number VARCHAR(20), 
			employee_who_deleted VARCHAR(20),
			date_when_deleted DATE,
			time_when_deleted TIME,
			PRIMARY KEY (deleted_employee_number)
		);
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `create_complaints_table` ()   BEGIN
		CREATE TABLE IF NOT EXISTS Complaints(
			complaint_number INT PRIMARY KEY,
			datum DATE,
			reason VARCHAR(100),
			employee_number VARCHAR(20), 
			customer_id INT,
			FOREIGN KEY (employee_number) REFERENCES Employee(employee_number),
			FOREIGN KEY (customer_id) REFERENCES Customer(ID)
		);
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `create_customer_table` ()   BEGIN
		CREATE TABLE IF NOT EXISTS Customer(
			ID INT PRIMARY KEY NOT NULL,
			first_name VARCHAR(20) NOT NULL,
			last_name VARCHAR(40) NOT NULL,
			postal_code VARCHAR(40),
			email_address VARCHAR(50),
			INDEX idx_postal_code (postal_code)
		);
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `create_department_table` ()   BEGIN
		CREATE TABLE IF NOT EXISTS `Department` (
				    `name` VARCHAR(50) PRIMARY KEY NOT NULL,
				    `number_` INT,
				    `manager_employee` VARCHAR(50),
				    `number_of_employees` INT,
				    `head_office_location` VARCHAR(70),
				    `area_id` INT,
				    FOREIGN KEY (`area_id`) REFERENCES Area(`ID`)
				);
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `create_driver_table` ()   BEGIN
		CREATE TABLE IF NOT EXISTS `Driver` (
		    `employee_number` VARCHAR(20) PRIMARY KEY NOT NULL,
		    `hours_per_week` INT,
		    `area_id` INT NOT NULL,
		    `vehicle_id` INT,
		    FOREIGN KEY (`employee_number`) REFERENCES Employee(`employee_number`),
		    FOREIGN KEY (`area_id`) REFERENCES Area(`ID`),
		    FOREIGN KEY (`vehicle_id`) REFERENCES Vehicle(`ID`)
		);
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `create_emergency_contact_table` ()   BEGIN
		CREATE TABLE IF NOT EXISTS `Emergency_Contact` (
		    `name` VARCHAR(70),
		    `relationship` VARCHAR(20),
		    `phone_number` VARCHAR(22) NOT NULL,
		    `employee` VARCHAR(20) PRIMARY KEY NOT NULL,
		    FOREIGN KEY (`employee`) REFERENCES Employee(`employee_number`)
		);
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `create_employee_table` ()   BEGIN
	CREATE TABLE IF NOT EXISTS `Employee`(
					`employee_number` VARCHAR(20) NOT NULL UNIQUE,
					`first_name` VARCHAR(20) NOT NULL,
					`last_name` VARCHAR(40) NOT NULL,
					`home_address` VARCHAR(70),
					`salary` DECIMAL(10,2),
					`day_of_birth` VARCHAR(3),
					`month_of_birth` VARCHAR(3),
					`year_of_birth` INT,
					`national_insurance_number` VARCHAR(20),
					`department_name` VARCHAR(50) NOT NULL,
					`manager_name` VARCHAR(40) DEFAULT NULL);
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `create_manager_table` ()   BEGIN
	CREATE TABLE IF NOT EXISTS `Manager` (
				 `manager_id` INT PRIMARY KEY UNIQUE,
				 `employee_number` VARCHAR(20) NOT NULL,
				  FOREIGN KEY (`employee_number`) REFERENCES Employee(`employee_number`)
					);
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `create_middle_stops_table` ()   BEGIN
		CREATE TABLE IF NOT EXISTS Middle_Stops(
		    `starting_location` VARCHAR(50) NOT NULL,
		    `ending_location` VARCHAR(50) NOT NULL,
		    `stop_location` VARCHAR(50),
		    `time_arrived_stop` TIME,
		    PRIMARY KEY (`starting_location`, `ending_location`),
		    FOREIGN KEY (`starting_location`) REFERENCES Route(`starting_location`),
		    FOREIGN KEY (`ending_location`) REFERENCES Route(`ending_location`)
		);
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `create_ordered_products_table` ()   BEGIN
		CREATE TABLE IF NOT EXISTS Ordered_products(
			order_id INT, 
			product_id INT,
			PRIMARY KEY (order_id, product_id),
			FOREIGN KEY (order_id) REFERENCES Orderr(ID),
			FOREIGN KEY (product_id) REFERENCES Product(ID)
		);
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `create_order_table` ()   BEGIN
		CREATE TABLE IF NOT EXISTS Orderr(
			ID INT PRIMARY KEY UNIQUE,
			purchase_date DATE,
			customer_id INT,
			FOREIGN KEY (customer_id) REFERENCES Customer(ID)
		);
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `create_packager_table` ()   BEGIN
		CREATE TABLE IF NOT EXISTS Packager(
			area_id INT(50) NOT NULL,
			employee_number VARCHAR(20) NOT NULL,
			PRIMARY KEY (employee_number),
			FOREIGN KEY (employee_number) REFERENCES Employee(employee_number),
			FOREIGN KEY (area_id) REFERENCES Area(ID)
		);
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `create_products_in_warehouse_table` ()   BEGIN
		CREATE TABLE IF NOT EXISTS Products_in_warehouse(
			product_id INT,
			warehouse_name VARCHAR(50),
			quantity_left INT,
			PRIMARY KEY (product_id, warehouse_name),
			FOREIGN KEY (product_id) REFERENCES Product(ID),
			FOREIGN KEY (warehouse_name) REFERENCES Warehouse_Office(name)
		);
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `create_product_table` ()   BEGIN
		CREATE TABLE IF NOT EXISTS Product(
			ID INT PRIMARY KEY NOT NULl,
			name VARCHAR(40),
			description VARCHAR(100),
			price INT,
			quantity_in_order INT
		);
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `create_route_table` ()   BEGIN
		CREATE TABLE IF NOT EXISTS `Route` (
		    `name` VARCHAR(20) NOT NULL UNIQUE,
		    `starting_location` VARCHAR(50) NOT NULL,
		    `ending_location` VARCHAR(50) NOT NULL,
		    `time_arrived` TIME,
		    PRIMARY KEY (`name`)
		);
		CREATE INDEX idx_starting_location 
					ON `Route`(`starting_location`);
					CREATE INDEX idx_ending_location
					ON `Route`(`ending_location`);
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `create_vehicle_table` ()   BEGIN
		CREATE TABLE IF NOT EXISTS `Vehicle` (
		    `ID` INT PRIMARY KEY NOT NULL,
		    `name` VARCHAR(50),
		    `area_id` INT NOT NULL,
		    `route_name` VARCHAR(30),
		    FOREIGN KEY (`area_id`) REFERENCES Area(`ID`),
		    FOREIGN KEY (`route_name`) REFERENCES Route(`name`)
		);
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `create_warehouse_table` ()   BEGIN
		CREATE TABLE IF NOT EXISTS Warehouse_Office(
			name VARCHAR(50) PRIMARY KEY NOT NULL,
			area_id INT,
			warehouse_id INT,
			size INT,
			purpose VARCHAR(50),
			postal_code VARCHAR(50),
			FOREIGN KEY (area_id) REFERENCES Area(ID)
		);
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_area` (IN `p_ID` INT, IN `p_name` VARCHAR(30))   BEGIN
		INSERT INTO Area(ID, name) VALUES (p_ID, p_name);
		END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_audit_table` (IN `p_deleted_employee_number` VARCHAR(20), IN `p_employee_who_deleted` VARCHAR(20), IN `p_date_when_deleted` DATE, IN `p_time_when_deleted` TIME)   BEGIN
		INSERT INTO Audit(deleted_employee_number, employee_who_deleted, date_when_deleted, time_when_deleted) VALUES (p_deleted_employee_number, p_employee_who_deleted, p_date_when_deleted, p_time_when_deleted);
		 END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_department` (IN `p_name` VARCHAR(50), IN `p_number_` INT, IN `p_manager_employee` VARCHAR(50), IN `p_number_of_employees` INT, IN `p_head_office_location` VARCHAR(70), IN `p_area_id` INT)   BEGIN
		INSERT INTO Department(name, number_, manager_employee, number_of_employees, head_office_location, area_id) VALUES (p_name, p_number_, p_manager_employee, p_number_of_employees, p_head_office_location, p_area_id);
		END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_employee_info` (IN `p_employee_number` VARCHAR(20), IN `p_first_name` VARCHAR(20), IN `p_last_name` VARCHAR(40), IN `p_home_address` VARCHAR(70), IN `p_salary` DECIMAL(10,2), IN `p_day_of_birth` VARCHAR(3), IN `p_month_of_birth` VARCHAR(3), IN `p_year_of_birth` INT, IN `p_national_insurance_number` VARCHAR(20), IN `p_department_name` VARCHAR(50), IN `p_name` VARCHAR(70), IN `p_relationship` VARCHAR(20), IN `p_phone_number` VARCHAR(22))   BEGIN
		INSERT INTO Employee(employee_number, first_name,last_name, home_address, salary,day_of_birth, month_of_birth, year_of_birth, national_insurance_number, department_name) VALUES (p_employee_number, p_first_name, p_last_name, p_home_address, p_salary, p_day_of_birth, p_month_of_birth, p_year_of_birth, p_national_insurance_number, p_department_name);
		 INSERT INTO Emergency_Contact(name, relationship, phone_number, employee) VALUES (p_name, p_relationship, p_phone_number, p_employee_number);
		 END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `select_birthdays` ()   BEGIN 
DECLARE currentMonth INT;
SET currentMonth = MONTH(CURDATE());
		SELECT 
				E.first_name,
				E.last_name
				FROM Employee E
				WHERE E.month_of_birth = currentMonth;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `postal_code` varchar(40) NOT NULL,
  `city` varchar(20) DEFAULT NULL,
  `street` varchar(40) DEFAULT NULL,
  `customer_id` int DEFAULT NULL,
  `warehouse_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `area`
--

CREATE TABLE `area` (
  `ID` int NOT NULL,
  `name` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `area`
--

INSERT INTO `area` (`ID`, `name`) VALUES
(334, 'London');

-- --------------------------------------------------------

--
-- Table structure for table `audit`
--

CREATE TABLE `audit` (
  `deleted_employee_number` varchar(20) NOT NULL,
  `employee_who_deleted` varchar(20) DEFAULT NULL,
  `date_when_deleted` date DEFAULT NULL,
  `time_when_deleted` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `complaint_number` int NOT NULL,
  `datum` date DEFAULT NULL,
  `reason` varchar(100) DEFAULT NULL,
  `employee_number` varchar(20) DEFAULT NULL,
  `customer_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `ID` int NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(40) NOT NULL,
  `postal_code` varchar(40) DEFAULT NULL,
  `email_address` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`ID`, `first_name`, `last_name`, `postal_code`, `email_address`) VALUES
(1, 'Anna', 'Bedecs', '99999', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `name` varchar(50) NOT NULL,
  `number_` int DEFAULT NULL,
  `manager_employee` varchar(50) DEFAULT NULL,
  `number_of_employees` int DEFAULT NULL,
  `head_office_location` varchar(70) DEFAULT NULL,
  `area_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`name`, `number_`, `manager_employee`, `number_of_employees`, `head_office_location`, `area_id`) VALUES
('driver', 400, 'Mila Smith', 500, 'London', 334),
('HR', 500, 'Sam Johns', 200, 'London', 334);

-- --------------------------------------------------------

--
-- Table structure for table `driver`
--

CREATE TABLE `driver` (
  `employee_number` varchar(20) NOT NULL,
  `hours_per_week` int DEFAULT NULL,
  `area_id` int NOT NULL,
  `vehicle_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `emergency_contact`
--

CREATE TABLE `emergency_contact` (
  `name` varchar(70) DEFAULT NULL,
  `relationship` varchar(20) DEFAULT NULL,
  `phone_number` varchar(22) NOT NULL,
  `employee` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `emergency_contact`
--

INSERT INTO `emergency_contact` (`name`, `relationship`, `phone_number`, `employee`) VALUES
('Ernestine Syne', 'father', '07699779427', ''),
('Bambie Bennell', 'father', '07411921600', '05-1128789'),
('Regan Yarn', 'mother', '07967 811 408', '07-4517183'),
('Zita Greasty', 'father', '07672610977', '16-0283796'),
('Jeremy Slayford', 'girlfriend', '07721065357', '22-3708071'),
('Emelen Spender', 'father', '07345326453', '53-0431396'),
('Donella Scullard', 'boyfriend', '07764317881', '61-2138561'),
('Alis Plaster', 'father', '07547928939', '61-9391449'),
('Donella Scullard', 'boyfriend', '07764317881', '65-1738561'),
('Golda Preston', 'father', '07636377875', '66-7576883'),
('Ernestine Syne', 'father', '07699779427', '68-5871521'),
('Alica Adnam', 'civil partner', '07339364526', '71-4783541'),
('Godard Klimmek', 'wife', '07219099770', '71-7374760'),
('Phillie Eles', 'husband', '07925034405', '76-2985832');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `employee_number` varchar(20) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(40) NOT NULL,
  `home_address` varchar(70) DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `day_of_birth` varchar(3) DEFAULT NULL,
  `month_of_birth` varchar(3) DEFAULT NULL,
  `year_of_birth` int DEFAULT NULL,
  `national_insurance_number` varchar(20) DEFAULT NULL,
  `department_name` varchar(50) NOT NULL,
  `manager_name` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`employee_number`, `first_name`, `last_name`, `home_address`, `salary`, `day_of_birth`, `month_of_birth`, `year_of_birth`, `national_insurance_number`, `department_name`, `manager_name`) VALUES
('', 'Bearnard', 'Parysowna', '60052 Monument Alley', '85175.33', '8', '12', 1985, 'vg613456a', 'driver', NULL),
('05-1128789', 'Paulita', 'Casewell', '18 Riverside Trail', '73099.76', '15', '11', 1995, 'ds948250k', 'driver', NULL),
('07-4517183', 'Rochell', 'ODoohaine', '1802 Hayes Court', '25471.21', '17', '4', 1950, 'rz111360g', 'packager', NULL),
('16-0283796', 'Delphina', 'Skelhorn', '115 Cody Point', '91026.23', '29', '12', 1969, 'mx935884p', 'driver', NULL),
('22-3708071', 'Rob', 'Feast', '9503 Buell Drive', '30397.56', '4', '1', 1986, 'rb499211c', 'HR', NULL),
('53-0431396', 'Demetrius', 'Lowmass', '8 Stone Corner Crossing', '99061.45', '24', '12', 1968, 'wd175217c', 'driver', NULL),
('61-2138561', 'Say', 'Lodwig', '54399 Forest Dale Avenue', '17891.12', '27', '11', 1997, 'tj978150h', 'manager', NULL),
('61-9391449', 'Lester', 'Carmo', '6 Buell Trail', '37061.26', '2', '2', 1997, 'mt812263o', 'driver', NULL),
('65-1738561', 'Say', 'Lodwig', '54399 Forest Dale Avenue', '17891.12', '27', '11', 1997, 'tj978150h', 'manager', NULL),
('66-7576883', 'Dur', 'Janodet', '91175 Northland Hill', '54589.37', '17', '4', 1950, 'jd412382q', 'driver', NULL),
('68-5871521', 'Bearnard', 'Parysowna', '60052 Monument Alley', '85175.33', '8', '12', 1985, 'vg613456a', 'driver', NULL),
('71-4783541', 'Brandise', 'Kondrachenko', '3 Kenwood Alley', '49471.98', '28', '12', 1961, 'fm459704k', 'packager', NULL),
('71-7374760', 'Alfie', 'Dean', '15 Mccormick Point', '21930.10', '10', '12', 1963, 'lh444635g', 'packager', NULL),
('76-2985832', 'Desirae', 'Gooch', '6306 Reinke Circle', '42779.29', '1', '11', 1979, 'uc493497v', 'manager', NULL);

--
-- Triggers `employee`
--
DELIMITER $$
CREATE TRIGGER `before_deleting_employee` BEFORE DELETE ON `employee` FOR EACH ROW BEGIN
	SET @employeID = OLD.employee_number; 

	DELETE FROM Emergency_Contact WHERE employee = @employeID;

	SET @isManager = EXISTS( 
				SELECT 1 FROM Manager WHERE employee_number = @employeID	);
	IF @isManager THEN 
			DELETE FROM Manager WHERE employee_number = @employeID;
	END IF;

	SET @isDriver = EXISTS( 
				SELECT 1 FROM Driver WHERE employee_number = @employeID	);
	IF @isDriver THEN 
			DELETE FROM Driver WHERE employee_number = @employeID;
	END IF;

	SET @isPackager = EXISTS( 
				SELECT 1 FROM Packager WHERE employee_number = @employeID	);
	IF @isPackager THEN 
			DELETE FROM Packager WHERE employee_number = @employeID;
	END IF;


END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `manager`
--

CREATE TABLE `manager` (
  `manager_id` int NOT NULL,
  `employee_number` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `middle_stops`
--

CREATE TABLE `middle_stops` (
  `starting_location` varchar(50) NOT NULL,
  `ending_location` varchar(50) NOT NULL,
  `stop_location` varchar(50) DEFAULT NULL,
  `time_arrived_stop` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ordered_products`
--

CREATE TABLE `ordered_products` (
  `order_id` int NOT NULL,
  `product_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orderr`
--

CREATE TABLE `orderr` (
  `ID` int NOT NULL,
  `purchase_date` date DEFAULT NULL,
  `customer_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `packager`
--

CREATE TABLE `packager` (
  `area_id` int NOT NULL,
  `employee_number` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `ID` int NOT NULL,
  `name` varchar(40) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `price` int DEFAULT NULL,
  `quantity_in_order` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products_in_warehouse`
--

CREATE TABLE `products_in_warehouse` (
  `product_id` int NOT NULL,
  `warehouse_name` varchar(50) NOT NULL,
  `quantity_left` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `route`
--

CREATE TABLE `route` (
  `name` varchar(20) NOT NULL,
  `starting_location` varchar(50) NOT NULL,
  `ending_location` varchar(50) NOT NULL,
  `time_arrived` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vehicle`
--

CREATE TABLE `vehicle` (
  `ID` int NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `area_id` int NOT NULL,
  `route_name` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `warehouse_office`
--

CREATE TABLE `warehouse_office` (
  `name` varchar(50) NOT NULL,
  `area_id` int DEFAULT NULL,
  `warehouse_id` int DEFAULT NULL,
  `size` int DEFAULT NULL,
  `purpose` varchar(50) DEFAULT NULL,
  `postal_code` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`postal_code`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `warehouse_name` (`warehouse_name`);

--
-- Indexes for table `area`
--
ALTER TABLE `area`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `idx_area_name` (`name`);

--
-- Indexes for table `audit`
--
ALTER TABLE `audit`
  ADD PRIMARY KEY (`deleted_employee_number`);

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`complaint_number`),
  ADD KEY `employee_number` (`employee_number`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `idx_postal_code` (`postal_code`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`name`),
  ADD KEY `area_id` (`area_id`);

--
-- Indexes for table `driver`
--
ALTER TABLE `driver`
  ADD PRIMARY KEY (`employee_number`),
  ADD KEY `area_id` (`area_id`),
  ADD KEY `vehicle_id` (`vehicle_id`);

--
-- Indexes for table `emergency_contact`
--
ALTER TABLE `emergency_contact`
  ADD PRIMARY KEY (`employee`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD UNIQUE KEY `employee_number` (`employee_number`);

--
-- Indexes for table `manager`
--
ALTER TABLE `manager`
  ADD PRIMARY KEY (`manager_id`),
  ADD UNIQUE KEY `manager_id` (`manager_id`),
  ADD KEY `employee_number` (`employee_number`);

--
-- Indexes for table `middle_stops`
--
ALTER TABLE `middle_stops`
  ADD PRIMARY KEY (`starting_location`,`ending_location`),
  ADD KEY `ending_location` (`ending_location`);

--
-- Indexes for table `ordered_products`
--
ALTER TABLE `ordered_products`
  ADD PRIMARY KEY (`order_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `orderr`
--
ALTER TABLE `orderr`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `ID` (`ID`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `packager`
--
ALTER TABLE `packager`
  ADD PRIMARY KEY (`employee_number`),
  ADD KEY `area_id` (`area_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `products_in_warehouse`
--
ALTER TABLE `products_in_warehouse`
  ADD PRIMARY KEY (`product_id`,`warehouse_name`),
  ADD KEY `warehouse_name` (`warehouse_name`);

--
-- Indexes for table `route`
--
ALTER TABLE `route`
  ADD PRIMARY KEY (`name`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `idx_starting_location` (`starting_location`),
  ADD KEY `idx_ending_location` (`ending_location`);

--
-- Indexes for table `vehicle`
--
ALTER TABLE `vehicle`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `area_id` (`area_id`),
  ADD KEY `route_name` (`route_name`);

--
-- Indexes for table `warehouse_office`
--
ALTER TABLE `warehouse_office`
  ADD PRIMARY KEY (`name`),
  ADD KEY `area_id` (`area_id`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `address`
--
ALTER TABLE `address`
  ADD CONSTRAINT `address_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`ID`),
  ADD CONSTRAINT `address_ibfk_2` FOREIGN KEY (`warehouse_name`) REFERENCES `warehouse_office` (`name`);

--
-- Constraints for table `complaints`
--
ALTER TABLE `complaints`
  ADD CONSTRAINT `complaints_ibfk_1` FOREIGN KEY (`employee_number`) REFERENCES `employee` (`employee_number`),
  ADD CONSTRAINT `complaints_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`ID`);

--
-- Constraints for table `department`
--
ALTER TABLE `department`
  ADD CONSTRAINT `department_ibfk_1` FOREIGN KEY (`area_id`) REFERENCES `area` (`ID`);

--
-- Constraints for table `driver`
--
ALTER TABLE `driver`
  ADD CONSTRAINT `driver_ibfk_1` FOREIGN KEY (`employee_number`) REFERENCES `employee` (`employee_number`),
  ADD CONSTRAINT `driver_ibfk_2` FOREIGN KEY (`area_id`) REFERENCES `area` (`ID`),
  ADD CONSTRAINT `driver_ibfk_3` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicle` (`ID`);

--
-- Constraints for table `emergency_contact`
--
ALTER TABLE `emergency_contact`
  ADD CONSTRAINT `emergency_contact_ibfk_1` FOREIGN KEY (`employee`) REFERENCES `employee` (`employee_number`);

--
-- Constraints for table `manager`
--
ALTER TABLE `manager`
  ADD CONSTRAINT `manager_ibfk_1` FOREIGN KEY (`employee_number`) REFERENCES `employee` (`employee_number`);

--
-- Constraints for table `middle_stops`
--
ALTER TABLE `middle_stops`
  ADD CONSTRAINT `middle_stops_ibfk_1` FOREIGN KEY (`starting_location`) REFERENCES `route` (`starting_location`),
  ADD CONSTRAINT `middle_stops_ibfk_2` FOREIGN KEY (`ending_location`) REFERENCES `route` (`ending_location`);

--
-- Constraints for table `ordered_products`
--
ALTER TABLE `ordered_products`
  ADD CONSTRAINT `ordered_products_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orderr` (`ID`),
  ADD CONSTRAINT `ordered_products_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`ID`);

--
-- Constraints for table `orderr`
--
ALTER TABLE `orderr`
  ADD CONSTRAINT `orderr_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`ID`);

--
-- Constraints for table `packager`
--
ALTER TABLE `packager`
  ADD CONSTRAINT `packager_ibfk_1` FOREIGN KEY (`employee_number`) REFERENCES `employee` (`employee_number`),
  ADD CONSTRAINT `packager_ibfk_2` FOREIGN KEY (`area_id`) REFERENCES `area` (`ID`);

--
-- Constraints for table `products_in_warehouse`
--
ALTER TABLE `products_in_warehouse`
  ADD CONSTRAINT `products_in_warehouse_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`ID`),
  ADD CONSTRAINT `products_in_warehouse_ibfk_2` FOREIGN KEY (`warehouse_name`) REFERENCES `warehouse_office` (`name`);

--
-- Constraints for table `vehicle`
--
ALTER TABLE `vehicle`
  ADD CONSTRAINT `vehicle_ibfk_1` FOREIGN KEY (`area_id`) REFERENCES `area` (`ID`),
  ADD CONSTRAINT `vehicle_ibfk_2` FOREIGN KEY (`route_name`) REFERENCES `route` (`name`);

--
-- Constraints for table `warehouse_office`
--
ALTER TABLE `warehouse_office`
  ADD CONSTRAINT `warehouse_office_ibfk_1` FOREIGN KEY (`area_id`) REFERENCES `area` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
