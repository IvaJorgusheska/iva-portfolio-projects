<?php


	if (isset($_POST['database_action']))
	{
		include("db_config.php");	
		
		$database_action = $_POST['database_action'];

		if ($database_action == "create_database")
		{
			$sql = "CREATE DATABASE IF NOT EXISTS kilburnazon";
			
			try
			{
				$stmt = $pdo->prepare($sql);
				$stmt->execute();
				$message = "Database created successfully";

				$error = false;
			}
			catch(Exception $e)
			{
				$error = true;
				$message = $e->getMessage();
				echo $message;
			}


		}


		if ($database_action == "drop_database")
		{
			include("drop_database.php");
			$sql = "CALL drop_database()";
			
			try
			{
				$pdo->exec($sql);
				$error = false;
				$message = "Database dropped successfully";
			}
			catch(Exception $e)
			{
				$error = true;
				$message = $e->getMessage();
			}
		}

		if ($database_action == "create_tables")
		{	
			include("stored_procedures.php");
		
			$sql = " CALL create_employee_table();

					CALL create_manager_table();

					CALL create_area_table();

					CALL create_department_table();

					CALL create_emergency_contact_table();

					CALL create_route_table();

					CALL create_vehicle_table();

					CALL create_driver_table();

					CALL create_middle_stops_table();

					CALL create_packager_table();

					CALL create_warehouse_table();

					CALL create_customer_table();

					CALL create_complaints_table();

					CALL create_address_table();

					CALL create_product_table();
					
					CALL create_products_in_warehouse_table();

					CALL create_order_table();

					CALL create_ordered_products_table();

					CALL create_audit_table()";

				

			try
			{	
				$pdo->exec($sql);
				$error = false;
				$message = "Tables created successfully";
			}
			catch(Exception $e)
			{
				$error = true;
				$message = $e->getMessage();
			}
		}

		if ($database_action == "insert_data")
		{	
			include("stored_procedures.php");
			include("inserting_data.php");			
			try{
			$sql1 = "INSERT INTO `customer` (`ID`, `last_name`, `first_name`, `email_address`, `postal_code`) VALUES (1, 'Bedecs', 'Anna', NULL,'99999')";
			$stmt1 = $pdo->prepare($sql1);
			$stmt1->execute();

			$sql2 = "CALL insert_employee_info('22-3708071', 'Rob', 'Feast', '9503 Buell Drive', '30397.56', 04, 01, 1986,'rb499211c', 'HR', 'Jeremy Slayford', 'girlfriend', '07721065357')";

			$stmt2 = $pdo->prepare($sql2);
			$stmt2->execute();
			
			
			$sql3 = "CALL insert_employee_info('61-9391449', 'Lester', 'Carmo', '6 Buell Trail', '37061.26', 02, 02, 1997,'mt812263o', 'driver', 'Alis Plaster', 'father', '07547928939')";
			$stmt3 = $pdo->prepare($sql3);
			$stmt3->execute();
	
		

			$sql4 = "CALL insert_employee_info('66-7576883', 'Dur', 'Janodet', '91175 Northland Hill', '54589.37', 17, 04, 1950,'jd412382q', 'driver', 'Golda Preston', 'father', '07636377875')";
			$stmt4 = $pdo->prepare($sql4);
			$stmt4->execute();


			$sql5 = "CALL insert_employee_info('05-1128789', 'Paulita', 'Casewell', '18 Riverside Trail', '73099.76', 15, 11, 1995,'ds948250k', 'driver', 'Bambie Bennell', 'father', '07411921600')";
			$stmt5 = $pdo->prepare($sql5);
			$stmt5->execute();
			

			$sql6 = "CALL insert_employee_info('16-0283796', 'Delphina', 'Skelhorn', '115 Cody Point', '91026.23', 29, 12, 1969,'mx935884p', 'driver', 'Zita Greasty', 'father', '07672610977')";
			$stmt6 = $pdo->prepare($sql6);
			$stmt6->execute();
			

			$sql7 = "CALL insert_employee_info('68-5871521', 'Bearnard', 'Parysowna', '60052 Monument Alley', '85175.33', 08, 12, 1985,'vg613456a', 'driver', 'Ernestine Syne', 'father', '07699779427')";
			$stmt7 = $pdo->prepare($sql7);
			$stmt7->execute();

			$sqlsp = "CALL insert_employee_info('', 'Bearnard', 'Parysowna', '60052 Monument Alley', '85175.33', 08, 12, 1985,'vg613456a', 'driver', 'Ernestine Syne', 'father', '07699779427')";
			$stmtsp = $pdo->prepare($sqlsp);
			$stmtsp->execute();


			$sql8 = "CALL insert_employee_info('53-0431396', 'Demetrius', 'Lowmass', '8 Stone Corner Crossing', '99061.45', 24, 12, 1968,'wd175217c', 'driver', 'Emelen Spender', 'father', '07345326453')";
			$stmt8 = $pdo->prepare($sql8);
			$stmt8->execute();

			$sql9 = "CALL insert_employee_info('71-7374760', 'Alfie', 'Dean', '15 Mccormick Point', '21930.10', 10, 12, 1963,'lh444635g', 'packager', 'Godard Klimmek', 'wife', '07219099770')";
			$stmt9 = $pdo->prepare($sql9);
			$stmt9->execute();

			$sql10 = "CALL insert_employee_info('71-4783541', 'Brandise', 'Kondrachenko', '3 Kenwood Alley', '49471.98', 28, 12, 1961,'fm459704k', 'packager', 'Alica Adnam', 'civil partner', '07339364526')";
			$stmt10 = $pdo->prepare($sql10);
			$stmt10->execute();
			
			$sql11 = "CALL insert_employee_info('76-2985832', 'Desirae', 'Gooch', '6306 Reinke Circle', '42779.29', 01, 11, 1979,'uc493497v', 'manager', 'Phillie Eles', 'husband', '07925034405')";
			$stmt11 = $pdo->prepare($sql11);
			$stmt11->execute();

			$sqla = "CALL insert_area(334, 'London')";
			$stmta = $pdo->prepare($sqla);
			$stmta->execute();

			$sqlhr = "CALL insert_department('HR', 500, 'Sam Johns', 200, 'London', 334)";
			$stmthr = $pdo->prepare($sqlhr);
			$stmthr->execute();


			$sql12 = "CALL insert_employee_info('65-1738561', 'Say', 'Lodwig', '54399 Forest Dale Avenue', '17891.12', 27, 11, 1997,'tj978150h', 'manager', 'Donella Scullard', 'boyfriend', '07764317881')";
			$stmt12 = $pdo->prepare($sql12);
			$stmt12->execute();

			$sqldr = "CALL insert_department('driver', 400, 'Mila Smith', 500, 'London', 334)";
			$stmtdr = $pdo->prepare($sqldr);
			$stmtdr->execute();


			$sql13 = "CALL insert_employee_info('61-2138561', 'Say', 'Lodwig', '54399 Forest Dale Avenue', '17891.12', 27, 11, 1997,'tj978150h', 'manager', 'Donella Scullard', 'boyfriend', '07764317881')";
			$stmt13 = $pdo->prepare($sql13);
			$stmt13->execute();
			
			$sql14 = "CALL insert_employee_info('07-4517183', 'Rochell', 'ODoohaine', '1802 Hayes Court', '25471.21', 17, 04, 1950,'rz111360g', 'packager', 'Regan Yarn', 'mother', '07967 811 408')";
			$stmt14 = $pdo->prepare($sql14);
			$stmt14->execute();

		

				$message = "Data inserted successfully";
				$error = false;
			}
			catch(Exception $e)
			{
				$error = true;
				$message = $e->getMessage();
			}
		}

	}
?>



<style>
	body
	{
		background-color: #2F1847;
		color: F9B3D1;
		padding: 3em;
	}


	div
	{
		text-align: left;
		padding: 2em;
	}

	.name{
		position: absolute;
		top:3em;
		right: 3em;
		padding: 10px;
		color: white;
		font-weight: bold;
		font-size: 16px;
	}

	.student-id{
	position: absolute;
		top:5em;
		right: 3em;
		padding: 10px;
		color: white;
		font-weight: bold;
		font-size: 16px;	
	}

	input[type=button]
	{
		display: block;
		margin-bottom: 1em;
		background-color: #F9B3D1;
		color: #2F1847;
		padding: 1em 2em;
		text-decoration: none;

		cursor: pointer;
		width: 15em;
		border-radius: 2em;
		font-weight: bold;
	}

	input[type=button]:hover
	{
		background-color: #C62E65;
	}

	a
	{
		color: white;
	}

	.error
	{
		color: red;
	}

	.success
	{
		color: green;
	}

button.redirect-button {
        display: block;
        margin-bottom: 1em;
        background-color: #F9B3D1;
        color: #2F1847;
        padding: 1em 2em;
        text-decoration: none;
        cursor: pointer;
        width: 15em;
        border-radius: 0em;
        font-weight: bold;
    }

    button.redirect-button:hover {
        background-color: #C62E65;
    }
</style>
<h1><a href="">KILBURNAZON - Database Implementation </a></h1>
<div>
	<form name="frm_database_action" method="POST">
		<input type="button" name="create_database" value="CREATE DATABASE" onclick="submit_action('create_database');">
		<input type="button" name="drop_database" value="DROP DATABASE" onclick="submit_action('drop_database');">
		<input type="button" name="create_tables" value="CREATE TABLES" onclick="submit_action('create_tables');">		
		<input type="button" name="insert_data" value="INSERT DATA" onclick="submit_action('insert_data');">	

		<?php
			if (isset($error))
			{
				$style = $error ? "error" : "success";
				echo ("<p class='$style'>$message</p>");				
			}
		?>

		<input type="hidden" name="database_action" id="database_action">
	</form>
</div>
<top_right>
	<div class="name">Iva Jorgusheska</div>
    <div class="student-id">Student ID: 11114620</div>
	</top_right>
<button class="redirect-button" onclick="redirectToTask1()" style="position: absolute; top: 450px; left: 350px;">Task1</button>
<button class="redirect-button" onclick="redirectToTask2()" style="position: absolute; top: 450px; left: 600px;">Task2</button>
<button class="redirect-button" onclick="redirectToTask3()" style="position: absolute; top: 450px; left: 850px;">Task3</button>
<button class="redirect-button" onclick="redirectToTask4()" style="position: absolute; top: 520px; left: 450px;">Task4</button>
<button class="redirect-button" onclick="redirectToTask5()" style="position: absolute; top: 520px; left: 700px;">Task5</button>
<button class="redirect-button" onclick="redirectToTask6()" style="position: absolute; top: 520px; left: 950px;">Task6</button>
<script>
	
	function submit_action(action)
	{
		document.getElementById("database_action").value = action;
		frm_database_action.submit();
	}

	function redirectToTask1() {
         window.location.href = 'task1_page.php';
    }
    function redirectToTask2() {
         window.location.href = 'task2_page.php';
    }
    function redirectToTask3() {
         window.location.href = 'task3_page.php';
    }
    function redirectToTask4() {
         window.location.href = 'task4_page.php';
    }
    function redirectToTask5() {
         window.location.href = 'task5_page.php';
    }
    function redirectToTask6() {
         window.location.href = 'task6_page.php';
    }
</script>



