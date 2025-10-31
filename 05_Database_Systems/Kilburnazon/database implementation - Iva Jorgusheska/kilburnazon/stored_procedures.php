<?php



$sql2 = "CREATE PROCEDURE IF NOT EXISTS create_employee_table()
	BEGIN
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
	END
";
try
{
 $result = $pdo->exec($sql2);
 $error = false;
}
catch(Exception $e)
{
 $error = true;
 echo($e->getMessage());
} 


$sql3 = "CREATE PROCEDURE IF NOT EXISTS create_manager_table()
	BEGIN
	CREATE TABLE IF NOT EXISTS `Manager` (
				 `manager_id` INT PRIMARY KEY UNIQUE,
				 `employee_number` VARCHAR(20) NOT NULL,
				  FOREIGN KEY (`employee_number`) REFERENCES Employee(`employee_number`)
					);
	END
";
try
{
 $result = $pdo->exec($sql3);
 $error = false;
}
catch(Exception $e)
{
 $error = true;
 echo($e->getMessage());
} 


$sql4 = "CREATE PROCEDURE IF NOT EXISTS create_area_table()
	BEGIN
		CREATE TABLE IF NOT EXISTS Area (
				`ID` INT PRIMARY KEY,
			    `name` VARCHAR(30)
				);
		CREATE INDEX idx_area_name
					ON Area (`name`);
	END
";
try
{
 $result = $pdo->exec($sql4);
 $error = false;
}
catch(Exception $e)
{
 $error = true;
 echo($e->getMessage());
} 



$sql5 = "CREATE PROCEDURE IF NOT EXISTS create_department_table()
	BEGIN
		CREATE TABLE IF NOT EXISTS `Department` (
				    `name` VARCHAR(50) PRIMARY KEY NOT NULL,
				    `number_` INT,
				    `manager_employee` VARCHAR(50),
				    `number_of_employees` INT,
				    `head_office_location` VARCHAR(70),
				    `area_id` INT,
				    FOREIGN KEY (`area_id`) REFERENCES Area(`ID`)
				);
	END
";
try
{
 $result = $pdo->exec($sql5);
 $error = false;
}
catch(Exception $e)
{
 $error = true;
 echo($e->getMessage());
} 


$sql6 = "CREATE PROCEDURE IF NOT EXISTS create_emergency_contact_table()
	BEGIN
		CREATE TABLE IF NOT EXISTS `Emergency_Contact` (
		    `name` VARCHAR(70),
		    `relationship` VARCHAR(20),
		    `phone_number` VARCHAR(22) NOT NULL,
		    `employee` VARCHAR(20) PRIMARY KEY NOT NULL,
		    FOREIGN KEY (`employee`) REFERENCES Employee(`employee_number`)
		);
	END
";
try
{
 $result = $pdo->exec($sql6);
 $error = false;
}
catch(Exception $e)
{
 $error = true;
 echo($e->getMessage());
} 





$sql7 = "CREATE PROCEDURE IF NOT EXISTS create_route_table()
	BEGIN
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
	END
";
try
{
 $result = $pdo->exec($sql7);
 $error = false;
}
catch(Exception $e)
{
 $error = true;
 echo($e->getMessage());
} 


$sql8 = "CREATE PROCEDURE IF NOT EXISTS create_vehicle_table()
	BEGIN
		CREATE TABLE IF NOT EXISTS `Vehicle` (
		    `ID` INT PRIMARY KEY NOT NULL,
		    `name` VARCHAR(50),
		    `area_id` INT NOT NULL,
		    `route_name` VARCHAR(30),
		    FOREIGN KEY (`area_id`) REFERENCES Area(`ID`),
		    FOREIGN KEY (`route_name`) REFERENCES Route(`name`)
		);
	END
";
try
{
 $result = $pdo->exec($sql8);
 $error = false;
}
catch(Exception $e)
{
 $error = true;
 echo($e->getMessage());
} 



$sql9 = "CREATE PROCEDURE IF NOT EXISTS create_driver_table()
	BEGIN
		CREATE TABLE IF NOT EXISTS `Driver` (
		    `employee_number` VARCHAR(20) PRIMARY KEY NOT NULL,
		    `hours_per_week` INT,
		    `area_id` INT NOT NULL,
		    `vehicle_id` INT,
		    FOREIGN KEY (`employee_number`) REFERENCES Employee(`employee_number`),
		    FOREIGN KEY (`area_id`) REFERENCES Area(`ID`),
		    FOREIGN KEY (`vehicle_id`) REFERENCES Vehicle(`ID`)
		);
	END
";
try
{
 $result = $pdo->exec($sql9);
 $error = false;
}
catch(Exception $e)
{
 $error = true;
 echo($e->getMessage());
} 



$sql10 = "CREATE PROCEDURE IF NOT EXISTS create_middle_stops_table()
	BEGIN
		CREATE TABLE IF NOT EXISTS Middle_Stops(
		    `starting_location` VARCHAR(50) NOT NULL,
		    `ending_location` VARCHAR(50) NOT NULL,
		    `stop_location` VARCHAR(50),
		    `time_arrived_stop` TIME,
		    PRIMARY KEY (`starting_location`, `ending_location`),
		    FOREIGN KEY (`starting_location`) REFERENCES Route(`starting_location`),
		    FOREIGN KEY (`ending_location`) REFERENCES Route(`ending_location`)
		);
	END
";
try
{
 $result = $pdo->exec($sql10);
 $error = false;
}
catch(Exception $e)
{
 $error = true;
 echo($e->getMessage());
} 



$sql11 = "CREATE PROCEDURE IF NOT EXISTS create_packager_table()
	BEGIN
		CREATE TABLE IF NOT EXISTS Packager(
			area_id INT(50) NOT NULL,
			employee_number VARCHAR(20) NOT NULL,
			PRIMARY KEY (employee_number),
			FOREIGN KEY (employee_number) REFERENCES Employee(employee_number),
			FOREIGN KEY (area_id) REFERENCES Area(ID)
		);
	END
";
try
{
 $result = $pdo->exec($sql11);
 $error = false;
}
catch(Exception $e)
{
 $error = true;
 echo($e->getMessage());
} 


$sql12 = "CREATE PROCEDURE IF NOT EXISTS create_warehouse_table()
	BEGIN
		CREATE TABLE IF NOT EXISTS Warehouse_Office(
			name VARCHAR(50) PRIMARY KEY NOT NULL,
			area_id INT,
			warehouse_id INT,
			size INT,
			purpose VARCHAR(50),
			postal_code VARCHAR(50),
			FOREIGN KEY (area_id) REFERENCES Area(ID)
		);
	END
";
try
{
 $result = $pdo->exec($sql12);
 $error = false;
}
catch(Exception $e)
{
 $error = true;
 echo($e->getMessage());
} 


$sql13 = "CREATE PROCEDURE IF NOT EXISTS create_customer_table()
	BEGIN
		CREATE TABLE IF NOT EXISTS Customer(
			ID INT PRIMARY KEY NOT NULL,
			first_name VARCHAR(20) NOT NULL,
			last_name VARCHAR(40) NOT NULL,
			postal_code VARCHAR(40),
			email_address VARCHAR(50),
			INDEX idx_postal_code (postal_code)
		);
	END
";
try
{
 $result = $pdo->exec($sql13);
 $error = false;
}
catch(Exception $e)
{
 $error = true;
 echo($e->getMessage());
} 


$sql14 = "CREATE PROCEDURE IF NOT EXISTS create_complaints_table()
	BEGIN
		CREATE TABLE IF NOT EXISTS Complaints(
			complaint_number INT PRIMARY KEY,
			datum DATE,
			reason VARCHAR(100),
			employee_number VARCHAR(20), 
			customer_id INT,
			FOREIGN KEY (employee_number) REFERENCES Employee(employee_number),
			FOREIGN KEY (customer_id) REFERENCES Customer(ID)
		);
	END
";
try
{
 $result = $pdo->exec($sql14);
 $error = false;
}
catch(Exception $e)
{
 $error = true;
 echo($e->getMessage());
} 


$sql15 = "CREATE PROCEDURE IF NOT EXISTS create_address_table()
	BEGIN
		CREATE TABLE IF NOT EXISTS Address(
			postal_code VARCHAR(40) PRIMARY KEY,
			city VARCHAR(20),
			street VARCHAR(40),
			customer_id INT,
			warehouse_name VARCHAR(50),
			FOREIGN KEY (customer_id) REFERENCES Customer(ID),
			FOREIGN KEY (warehouse_name) REFERENCES Warehouse_Office(name)
		);
	END
";
try
{
 $result = $pdo->exec($sql15);
 $error = false;
}
catch(Exception $e)
{
 $error = true;
 echo($e->getMessage());
} 


$sql16 = "CREATE PROCEDURE IF NOT EXISTS create_product_table()
	BEGIN
		CREATE TABLE IF NOT EXISTS Product(
			ID INT PRIMARY KEY NOT NULl,
			name VARCHAR(40),
			description VARCHAR(100),
			price INT,
			quantity_in_order INT
		);
	END
";
try
{
 $result = $pdo->exec($sql16);
 $error = false;
}
catch(Exception $e)
{
 $error = true;
 echo($e->getMessage());
} 


$sql17 = "CREATE PROCEDURE IF NOT EXISTS create_products_in_warehouse_table()
	BEGIN
		CREATE TABLE IF NOT EXISTS Products_in_warehouse(
			product_id INT,
			warehouse_name VARCHAR(50),
			quantity_left INT,
			PRIMARY KEY (product_id, warehouse_name),
			FOREIGN KEY (product_id) REFERENCES Product(ID),
			FOREIGN KEY (warehouse_name) REFERENCES Warehouse_Office(name)
		);
	END
";
try
{
 $result = $pdo->exec($sql17);
 $error = false;
}
catch(Exception $e)
{
 $error = true;
 echo($e->getMessage());
} 


$sql18 = "CREATE PROCEDURE IF NOT EXISTS create_order_table()
	BEGIN
		CREATE TABLE IF NOT EXISTS Orderr(
			ID INT PRIMARY KEY UNIQUE,
			purchase_date DATE,
			customer_id INT,
			FOREIGN KEY (customer_id) REFERENCES Customer(ID)
		);
	END
";
try
{
 $result = $pdo->exec($sql18);
 $error = false;
}
catch(Exception $e)
{
 $error = true;
 echo($e->getMessage());
} 


$sql19 = "CREATE PROCEDURE IF NOT EXISTS create_ordered_products_table()
	BEGIN
		CREATE TABLE IF NOT EXISTS Ordered_products(
			order_id INT, 
			product_id INT,
			PRIMARY KEY (order_id, product_id),
			FOREIGN KEY (order_id) REFERENCES Orderr(ID),
			FOREIGN KEY (product_id) REFERENCES Product(ID)
		);
	END
";
try
{
 $result = $pdo->exec($sql19);
 $error = false;
}
catch(Exception $e)
{
 $error = true;
 echo($e->getMessage());
} 


$sql20 = "CREATE PROCEDURE IF NOT EXISTS create_audit_table()
	BEGIN
		CREATE TABLE IF NOT EXISTS Audit(
			deleted_employee_number VARCHAR(20), 
			employee_who_deleted VARCHAR(20),
			date_when_deleted DATE,
			time_when_deleted TIME,
			PRIMARY KEY (deleted_employee_number)
		);
	END
";
try
{
 $result = $pdo->exec($sql20);
 $error = false;
}
catch(Exception $e)
{
 $error = true;
 echo($e->getMessage());
} 




$sql21 = "CREATE PROCEDURE IF NOT EXISTS select_birthdays()
BEGIN 
DECLARE currentMonth INT;
SET currentMonth = MONTH(CURDATE());
		SELECT 
				E.first_name,
				E.last_name
				FROM Employee E
				WHERE E.month_of_birth = currentMonth;
END  ";

try
{
 $result = $pdo->exec($sql21);
 $error = false;
}
catch(Exception $e)
{
 $error = true;
 echo($e->getMessage());
} 








?>