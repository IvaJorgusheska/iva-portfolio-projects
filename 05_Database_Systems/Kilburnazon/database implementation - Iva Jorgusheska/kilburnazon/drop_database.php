<?php
include("db_config.php");



$sql = "CREATE PROCEDURE IF NOT EXISTS drop_database()
 BEGIN
 DROP DATABASE IF EXISTS kilburnazon;
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
?>