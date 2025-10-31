<?php 
    
	include ("../../credentials.php");
	$host = $databaseConfig['host'];
	$dbname = $databaseConfig['dbname'];
	$username = $databaseConfig['username'];
	$password = $databaseConfig['password'];

	try
	{
		$pdo = new PDO("mysql:host=$host;", $username, $password);

		try
		{
			$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				}
		catch (PDOException $e)
		{
		}

	}
	catch (PDOException $e)
	{
		echo ("<p>Could not connect to $host database server</p>" . $e->getMessage());
	}
?>