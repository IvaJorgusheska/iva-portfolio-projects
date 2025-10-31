<?php 
 function update_employee_form()
	{
	return '
	 <div style="float: left; margin-left: 40px; margin-top:30px; padding: 10px; text-align: left;">
	 <h2 style="color: white;">Task 2 - Update Employee</h2>
	  <p style="font-style: italic;">Enter the employee number and any additional fields you want to update.</p>
	 	<form method = "POST">
		<label for="employee_number" style="color: white;">Employee Number</label>
		<input type="text" id="employee_number" name="employee_number" style="margin-bottom: 10px;"><br>
		<label for="first_name" style="color: white;">First Name</label>
		<input type="text" id="first_name" name="first_name" style="margin-bottom: 10px;"><br>
		<label for="last_name" style="color: white;">Last Name</label>
		<input type="text" id="last_name" name="last_name" style="margin-bottom: 10px;"><br>
		<label for="home_address" style="color: white;">Home Address</label>
		<input type="text" id="home_address" name="home_address" style="margin-bottom: 10px;"><br>
		<label for="salary" style="color: white;">Salary</label>
		<input type="text" id="salary" name="salary" style="margin-bottom: 10px;"><br>
		<label for="day_of_birth" style="color: white;">Day of Birth</label>
		<input type="text" id="day_of_birth" name="day_of_birth" style="margin-bottom: 10px;"><br>
		<label for="month_of_birth" style="color: white;">Month of Birth</label>
		<input type="text" id="month_of_birth" name="month_of_birth" style="margin-bottom: 10px;"><br>
		<label for="year_of_birth" style="color: white;">Year of Birth</label>
		<input type="text" id="year_of_birth" name="year_of_birth" style="margin-bottom: 10px;"><br>
		<label for="national_insurance_number" style="color: white;">National Insurance Number</label>
		<input type="text" id="national_insurance_number" name="national_insurance_number" style="margin-bottom: 10px;"><br>
		<label for="department_name" style="color: white;">Department Name</label>
		<input type="text" id="department_name" name="department_name" style="margin-bottom: 10px;"><br>
		<label for="emergency_name" style="color: white;">Emergency Contact Name</label>
		<input type="text" id="emergency_name" name="emergency_name" style="margin-bottom: 10px;"><br>
		<label for="relationship" style="color: white;">Relationship</label>
		<input type="text" id="relationship" name="relationship" style="margin-bottom: 10px;"><br>
		<label for="number" style="color: white;">Phone Number</label>
		<input type="text" id="number" name="number" style="margin-bottom: 10px;"><br>

		<input type="submit" name="upd_emp" value="Update employee">

</form>

</div>
'; }

function isValid(){
	include("db_config.php");
	if ($_SERVER["REQUEST_METHOD"] != "POST"){
		return false;
	}
	
	if(empty($_POST['employee_number'])){
		return false;
	}

	$employeeNumber = $_POST['employee_number'];

	include("db_config.php");

	$sql = "SELECT first_name FROM Employee WHERE employee_number = :employee_number";
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':employee_number', $employeeNumber, PDO::PARAM_STR);
	$stmt->execute();

	$result = $stmt->fetchColumn();

	return (!empty($result));
}

function update_employee(){
	include("db_config.php");
	include("stored_procedures.php");
	include("inserting_data.php");
	try{
	$emp_nr = $_POST['employee_number'];
	$name = $_POST['first_name'];
	if(!empty($name)){
		$sql = "UPDATE Employee SET first_name = :new_first_name WHERE employee_number = :employee_number";
    	$stmt = $pdo->prepare($sql);
    	$stmt->bindParam(':new_first_name', $name, PDO::PARAM_STR);
    	$stmt->bindParam(':employee_number', $emp_nr, PDO::PARAM_STR);

    	$stmt->execute();
	}
	$surname = $_POST['last_name'];
	if(!empty($surname)){
		$sql2 = "UPDATE Employee SET last_name = :new_last_name WHERE employee_number = :employee_number";
    	$stmt = $pdo->prepare($sql2);
    	$stmt->bindParam(':new_last_name', $nsurame, PDO::PARAM_STR);
    	$stmt->bindParam(':employee_number', $emp_nr, PDO::PARAM_STR);

    	$stmt->execute();
	}
	$address = $_POST['home_address'];
	if(!empty($address)){
		$sql3 = "UPDATE Employee SET home_address = :new_address WHERE employee_number = :employee_number";
    	$stmt = $pdo->prepare($sql3);
    	$stmt->bindParam(':new_address', $address, PDO::PARAM_STR);
    	$stmt->bindParam(':employee_number', $emp_nr, PDO::PARAM_STR);

    	$stmt->execute();
	}
	$sage = $_POST['salary'];
	if(!empty($sage)){
		$sql4 = "UPDATE Employee SET salary = :new_salary WHERE employee_number = :employee_number";
    	$stmt = $pdo->prepare($sql4);
    	$stmt->bindParam(':new_salary', $sage, PDO::PARAM_STR);
    	$stmt->bindParam(':employee_number', $emp_nr, PDO::PARAM_STR);

    	$stmt->execute();
	}
	$birthday = $_POST['day_of_birth'];
	if(!empty($birthday)){
		$sql5 = "UPDATE Employee SET day_of_birth = :new_day_of_birth WHERE employee_number = :employee_number";
    	$stmt = $pdo->prepare($sql5);
    	$stmt->bindParam(':new_day_of_birth', $birthday, PDO::PARAM_STR);
    	$stmt->bindParam(':employee_number', $emp_nr, PDO::PARAM_STR);

    	$stmt->execute();
	}
	$birthmonth = $_POST['month_of_birth'];
	if(!empty($birthmonth)){
		$sql6 = "UPDATE Employee SET month_of_birth = :new_month_of_birth WHERE employee_number = :employee_number";
    	$stmt = $pdo->prepare($sql6);
    	$stmt->bindParam(':new_month_of_birth', $birthmonth, PDO::PARAM_STR);
    	$stmt->bindParam(':employee_number', $emp_nr, PDO::PARAM_STR);

    	$stmt->execute();
	}
	$birthyear = $_POST['year_of_birth'];
	if(!empty($birthyear)){
		$sql7 = "UPDATE Employee SET year_of_birth = :new_year_of_birth WHERE employee_number = :employee_number";
    	$stmt = $pdo->prepare($sql7);
    	$stmt->bindParam(':new_year_of_birth', $name, PDO::PARAM_STR);
    	$stmt->bindParam(':employee_number', $emp_nr, PDO::PARAM_STR);

    	$stmt->execute();
	}
	$nin = $_POST['national_insurance_number'];
	if(!empty($nin)){
		$sql8 = "UPDATE Employee SET national_insurance_number = :new_nin WHERE employee_number = :employee_number";
    	$stmt = $pdo->prepare($sql8);
    	$stmt->bindParam(':new_nin', $nin, PDO::PARAM_STR);
    	$stmt->bindParam(':employee_number', $emp_nr, PDO::PARAM_STR);

    	$stmt->execute();
	}
	$department = $_POST['department_name'];
	if(!empty($department)){
		$sql9 = "UPDATE Employee SET department_name = :new_dp_name WHERE employee_number = :employee_number";
    	$stmt = $pdo->prepare($sql9);
    	$stmt->bindParam(':new_dp_name', $department, PDO::PARAM_STR);
    	$stmt->bindParam(':employee_number', $emp_nr, PDO::PARAM_STR);

    	$stmt->execute();
	}
	$nameE = $_POST['emergency_name'];
	if(!empty($namE)){
		$sql10 = "UPDATE Emergency_Contact SET emergency_name = :new_emergency_name WHERE employee = :employee_number";
    	$stmt = $pdo->prepare($sql10);
    	$stmt->bindParam(':new_emergency_name', $namE, PDO::PARAM_STR);
    	$stmt->bindParam(':employee_number', $emp_nr, PDO::PARAM_STR);

    	$stmt->execute();
	}
	$relation = $_POST['relationship'];
	if(!empty($relation)){
		$sql11 = "UPDATE Emergency_Contact SET relationship = :new_rel WHERE employee = :employee_number";
    	$stmt = $pdo->prepare($sql11);
    	$stmt->bindParam(':new_rel', $relation, PDO::PARAM_STR);
    	$stmt->bindParam(':employee_number', $emp_nr, PDO::PARAM_STR);

    	$stmt->execute();
	}
	$phone = $_POST['number'];
	if(!empty($phone)){
		$sql12 = "UPDATE Emergency_Contact SET phone_number = :new_nr WHERE employee = :employee_number";
    	$stmt = $pdo->prepare($sql12);
    	$stmt->bindParam(':new_nr', $phone, PDO::PARAM_STR);
    	$stmt->bindParam(':employee_number', $emp_nr, PDO::PARAM_STR);

    	$stmt->execute();
	}

	


 }
	catch(Exception $e){
		echo "Error adding employee: " , $e->getMessage();
	}
	echo update_employee_form();
}




?>