<?php 
function selecting_form(){
	return '
	 <div style="float: left; margin-left: 40px; margin-top: 30px; padding: 10px; text-align: left;">
	 <h2 style="color: white;">Task 4 - Selecting</h2>
	  <p style="font-style: italic;">Write the emergency contact of the employees you want to select and  HR/driver/packager/manager.</p>
	 	<form method = "POST">
	<label for="relationship" style="color: white;">Emergency Contact Relationship</label>
	<input type="text" id="relationship" name="relationship" style="margin-bottom: 10px;"><br>
	<label for="department_name" style="color: white;">Employee Department</label>
	<input type="text" id="department_name" name="department_name" style="margin-bottom: 10px;"><br>
	<input type="submit" name="disp_emp" value="Select">

</form>

</div>
'; }


function isValid4(){
	include("db_config.php");
	if ($_SERVER["REQUEST_METHOD"] != "POST"){
		return false;
	}
	
	if(empty($_POST['relationship']) ||empty($_POST['department_name'])){
		return false;
	}

	return true;
}






function display_employee(){
	include("db_config.php");
	include("stored_procedures.php");
	include("inserting_data.php");
	try{
		$relationship = $_POST['relationship'];
		$department = $_POST['department_name'];
		$sql = "SELECT 
				E.first_name,
				E.last_name,
				E.department_name,
				EC.relationship,
				COALESCE(E.manager_name, D.manager_employee) AS manager_name
				FROM Employee E
				LEFT JOIN 
				Emergency_Contact EC ON E.employee_number = EC.employee
				LEFT JOIN Department D ON E.department_name = D.name
				WHERE EC.relationship = :relationship AND E.department_name = :department;
		";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':relationship', $relationship, PDO::PARAM_STR);
		$stmt->bindParam(':department', $department, PDO::PARAM_STR);
		$result = $stmt->execute();

		if ($result) {
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (!empty($rows)) {
            	echo '<div class="employee-table-container">';
                echo '<table style="position: absolute; top: 425px; left: 270px; border-collapse: collapse; width: 70%;">';
                      echo' <tr>
                        <th style="border: 1px solid #ddd; padding: 5px;">First Name</th>
                        <th style="border: 1px solid #ddd; padding: 5px;">Last Name</th>
                        <th style="border: 1px solid #ddd; padding: 5px;">Department</th>
                        <th style="border: 1px solid #ddd; padding: 5px;">Relationship</th>
                        <th style="border: 1px solid #ddd; padding: 5px;">Manager Name</th>
                        </tr>';

                foreach ($rows as $row) {
                    echo '<tr>
                             <td style="border: 1px solid #ddd; padding: 5px;">'.$row["first_name"].'</td>
                            <td style="border: 1px solid #ddd; padding: 5px;">'.$row["last_name"].'</td>
                            <td style="border: 1px solid #ddd; padding: 5px;">'. $row["department_name"].'</td>
                            <td style="border: 1px solid #ddd; padding: 5px;">'.$row["relationship"].'</td>
                            <td style="border: 1px solid #ddd; padding: 5px;">'.($row["manager_name"] !== null ? $row["manager_name"] : 'Unknown').'</td>
                        </tr>';
                }

                echo "</table>";
                echo '</div>';
            } else {
                 echo '<div style="position: absolute; top: 350px; left: 70px;">0 results</div>';
            }
        } else {
            echo "Error executing the query";
        }
		
} catch(Exception $e){
		echo "Error displaying employee: " , $e->getMessage();
	}
	echo selecting_form();
}




?>