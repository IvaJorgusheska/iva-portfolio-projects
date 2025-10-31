<?php 

include("db_config.php");
include("trigger.php");


$sql = "CREATE PROCEDURE IF NOT EXISTS insert_employee_info(
		IN p_employee_number VARCHAR(20),
		IN p_first_name VARCHAR(20),
		IN p_last_name VARCHAR(40) ,
		IN p_home_address VARCHAR(70),
		IN p_salary DECIMAL(10,2),
		IN p_day_of_birth VARCHAR(3),
		IN p_month_of_birth VARCHAR(3),
		IN p_year_of_birth INT,
		IN p_national_insurance_number VARCHAR(20),
		IN p_department_name VARCHAR(50),
		IN p_name VARCHAR(70),
	    IN p_relationship VARCHAR(20),
	    IN p_phone_number VARCHAR(22))
		BEGIN
		INSERT INTO Employee(employee_number, first_name,last_name, home_address, salary,day_of_birth, month_of_birth, year_of_birth, national_insurance_number, department_name) VALUES (p_employee_number, p_first_name, p_last_name, p_home_address, p_salary, p_day_of_birth, p_month_of_birth, p_year_of_birth, p_national_insurance_number, p_department_name);
		 INSERT INTO Emergency_Contact(name, relationship, phone_number, employee) VALUES (p_name, p_relationship, p_phone_number, p_employee_number);
		 END
";

try
		{
		 $result = $pdo->exec($sql);
		 $error = false; 
		}
		catch(Exception $e)
		{
		 $error = true;
		 echo($e->getMessage());
		}



$sqla = "CREATE PROCEDURE IF NOT EXISTS insert_area(
			IN p_ID INT,
		    IN p_name VARCHAR(30))
		BEGIN
		INSERT INTO Area(ID, name) VALUES (p_ID, p_name);
		END
";

try
		{
		 $result = $pdo->exec($sqla);
		 $error = false; 
		}
		catch(Exception $e)
		{
		 $error = true;
		 echo($e->getMessage());
		}



$sqld = "CREATE PROCEDURE IF NOT EXISTS insert_department(
			IN p_name VARCHAR(50),
		    IN p_number_ INT,
		    IN p_manager_employee VARCHAR(50),
		    IN p_number_of_employees INT,
		    IN p_head_office_location VARCHAR(70),
		    IN p_area_id INT)
		BEGIN
		INSERT INTO Department(name, number_, manager_employee, number_of_employees, head_office_location, area_id) VALUES (p_name, p_number_, p_manager_employee, p_number_of_employees, p_head_office_location, p_area_id);
		END
";

try
		{
		 $result = $pdo->exec($sqld);
		 $error = false; 
		}
		catch(Exception $e)
		{
		 $error = true;
		 echo($e->getMessage());
		}



$sql2 = "CREATE PROCEDURE IF NOT EXISTS insert_audit_table(
		IN p_deleted_employee_number VARCHAR(20), 
		IN p_employee_who_deleted VARCHAR(20),
		IN p_date_when_deleted DATE,
		IN p_time_when_deleted TIME)
		BEGIN
		INSERT INTO Audit(deleted_employee_number, employee_who_deleted, date_when_deleted, time_when_deleted) VALUES (p_deleted_employee_number, p_employee_who_deleted, p_date_when_deleted, p_time_when_deleted);
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





?>