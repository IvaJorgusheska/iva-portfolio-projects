<?php 
function audit_delete_form(){
	return '
	<div style="float: left; margin-left: 40px; margin-top:30px; padding: 10px; text-align: left;">
	<h2 style="color: white;">Task 6 - Keeping record when deleting Employee</h2>
	<p style="font-style: italic;">Enter the employee number of the employee you want to delete.</p>
	<form method = "POST">
	<label for="employee_number" style="color: white;">Employee Number</label>
	<input type="text" id="employee_number" name="employee_number" style="margin-bottom: 10px;"><br>
	<label for="your_number" style="color: white;">Your Employee Number</label>
	<input type="text" id="your_number" name="your_number" style="margin-bottom: 10px;"><br>
	<input type="submit" name="audit_emp" value="Delete Employee">
	</form>
	</div>
	'; }

function isValid6(){
	include("db_config.php");
	if ($_SERVER["REQUEST_METHOD"] != "POST"){
		return false;
	}
	
	if(empty($_POST['your_number'])){
		return false;
	}

	$yourNumber = $_POST['your_number'];

	include("db_config.php");

	$sql = "SELECT department_name FROM Employee WHERE employee_number = :employee_number";
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':employee_number', $yourNumber, PDO::PARAM_STR);
	$stmt->execute();

	$result = $stmt->fetchColumn();

	if(!empty($result)){
		if ($result == 'HR'){
			return true;
		}
	}
	return false;
}

function audit_employee(){
	include("db_config.php");
	try{
		$date = date("Y-m-d");  
		$time = date("H:i:s");
		$employee_nr = $_POST['employee_number'];
		$hr_employee = $_POST['your_number'];
		$sql1 = "CALL insert_audit_table(:employee_nr, :hr_employee, :date, :time)";
		$stmt1 = $pdo->prepare($sql1);
		$stmt1->bindParam(':employee_nr', $employee_nr, PDO::PARAM_STR);
        $stmt1->bindParam(':hr_employee', $hr_employee, PDO::PARAM_STR);
        $stmt1->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt1->bindParam(':time', $time, PDO::PARAM_STR);
        $stmt1->execute();
        $hr_employee = $_POST['your_number'];
        $_SESSION['hr_employee'] = $hr_employee;
		$sql = "DELETE FROM Employee WHERE employee_number = :employee_nr";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':employee_nr', $employee_nr, PDO::PARAM_STR);
		$stmt->execute();
		
	} catch (PDOException $e){
		echo "Error deleting employee: " . $e->getMessage();
	}
	echo audit_delete_form();
}


?>