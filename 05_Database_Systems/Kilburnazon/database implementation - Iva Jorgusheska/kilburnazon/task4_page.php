<?php 
include("db_config.php");
include("selecting.php");
if (isset($_POST['disp_emp'])){
	if(isValid4()){
		display_employee();
	}
	else{
		echo selecting_form();
	}
}else{
	echo selecting_form();
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

</style>
<button onclick="redirectToHome()" style="position: absolute; top: 50px; left: 1320px;">Home Page</button>
<script>


	function redirectToHome() {
         window.location.href = 'main_page.php';
    }
</script>