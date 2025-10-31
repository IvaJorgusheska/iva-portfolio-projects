<?php 
 function enter_employee_form()
	{
	return '
	 <div style="float: left; margin-left: 70px; margin-top:30px; padding: 10px; text-align: left;">
	 <h2 style="color: white;">Task 1 - Add Employee</h2>
	 <p style="font-style: italic;">Fill in all fields</p>
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

		<input type="submit" name="add_emp" value="Add employee">

</form>

</div>
'; }


function isInputValid(){
	if ($_SERVER["REQUEST_METHOD"] != "POST"){
		return false;
	}
	$requiredFields = ['employee_number', 'first_name', 'last_name', 'home_address', 'salary', 'day_of_birth', 'month_of_birth', 'year_of_birth', 'national_insurance_number', 'department_name'];
	foreach ($requiredFields as $field) {
		if(empty($_POST[$field])){
			return false;
		}
	}
	if (!preg_match('/^\d{2}-\d{7}$/', $_POST['employee_number'])) {
        return false;
    }
	return true;
}

function add_employee(){
	include("db_config.php");
	include("stored_procedures.php");
	include("inserting_data.php");
	try{
	$emp_nr = $_POST['employee_number'];
	$name = $_POST['first_name'];
	$surname = $_POST['last_name'];
	$address = $_POST['home_address'];
	$sage = $_POST['salary'];
	$birthday = $_POST['day_of_birth'];
	$birthmonth = $_POST['month_of_birth'];
	$birthyear = $_POST['year_of_birth'];
	$nin = $_POST['national_insurance_number'];
	$department = $_POST['department_name'];
	$nameE = $_POST['emergency_name'];
	$relation = $_POST['relationship'];
	$phone = $_POST['number'];


	$sql = "CALL insert_employee_info(:employee_number, :first_name, :last_name, :home_address, :salary, :day_of_birth, :month_of_birth, :year_of_birth, :national_insurance_number, :department_name, :emergency_name, :relationship, :number)";

	$stmt = $pdo->prepare($sql);
	$stmt->execute([
					'employee_number'=>$emp_nr, 
					'first_name'=>$name,
					'last_name'=>$surname, 
					'home_address'=>$address,
					'salary'=>$sage,
					'day_of_birth'=>$birthday,
					 'month_of_birth'=>$birthmonth,
					 'year_of_birth'=>$birthyear,
					 'national_insurance_number'=>$nin,
					 'department_name'=>$department,
					 'emergency_name'=>$nameE,
					 'relationship'=>$relation,
					 'number'=>$phone
					]); }
	catch(Exception $e){
		echo "Error adding employee: " , $e->getMessage();
	}
	echo enter_employee_form();
}


?>