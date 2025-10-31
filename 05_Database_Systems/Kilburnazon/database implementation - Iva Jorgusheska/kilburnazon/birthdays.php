<?php 
function birthdays_form(){
	return '
	 <div style="float: left; margin-left: 40px; margin-top: 30px; padding: 10px; text-align: left;">
	 <h2 style="color: white;">Task 5 - Birthdays</h2>
	  <p style="font-style: italic;">Displays the birthdays of employees in the current month.</p>
	 	<form method = "POST">
	 	<input type="submit" name="disp_brth" value="Show Birthdays">
</form>
</div>
'; }


function display_birthdays(){
	include("db_config.php");
	include("stored_procedures.php");
	include("inserting_data.php");
	try{
		$stmt = $pdo->prepare("CALL select_birthdays()");
		$result = $stmt->execute();

		if ($result) {
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($rows)) {
               echo '<div class="birthdays-list-container">';
               echo '<ul style="list-style-type: disc; margin-left: 50px; position: absolute; top: 300px; left: 90px;">';

               foreach ($rows as $row) {
               	echo '<li>';
               	echo $row["first_name"] . " " . $row["last_name"];
               	echo '</li>';
               }
               echo '</ul>';
               echo '</div>';

            } else {
                echo "No employee has brithday this month.";
            }
        } else {
            echo "Error executing the query";
        }
		
} catch(Exception $e){
		echo "Error displaying employee: " , $e->getMessage();
	}
	echo birthdays_form();
}





?>