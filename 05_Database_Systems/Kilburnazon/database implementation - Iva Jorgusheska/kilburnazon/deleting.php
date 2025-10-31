<?php 
function delete_employee_form(){
	return '
	<div style="float: left; margin-left: 40px; margin-top:30px; padding: 10px; text-align: left;">
	<h2 style="color: white;">Task 3 - Delete Employee</h2>
	<p style="font-style: italic;">Enter the employee number of the employee you want to delete.</p>
	<form method = "POST">
	<label for="employee_number" style="color: white;">Employee Number</label>
		<input type="text" id="employee_number" name="employee_number" style="margin-bottom: 10px;"><br>
	<input type="submit" name="del_emp" value="Delete Employee">
	</form>
	</div>
	'; }


function delete_employee(){
	include("db_config.php");
	include("stored_procedures.php");
	include("inserting_data.php");
	#include("trigger.php");
	try{

		$employee_nr = $_POST['employee_number'];
		$sql = "DELETE FROM Employee WHERE employee_number = :employee_nr";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':employee_nr', $employee_nr, PDO::PARAM_STR);
		$stmt->execute();
	} catch (PDOException $e){
		echo "Error deleting employee: " . $e->getMessage();
	}
	echo delete_employee_form();
}




?>