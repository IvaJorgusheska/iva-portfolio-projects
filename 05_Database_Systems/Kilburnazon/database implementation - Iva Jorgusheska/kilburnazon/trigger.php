<?php
include("db_config.php");

$sqlt = " 
CREATE TRIGGER IF NOT EXISTS before_deleting_employee
	BEFORE DELETE ON Employee 
	FOR EACH ROW 
BEGIN
	SET @employeID = OLD.employee_number; 

	DELETE FROM Emergency_Contact WHERE employee = @employeID;

	SET @isManager = EXISTS( 
				SELECT 1 FROM Manager WHERE employee_number = @employeID	);
	IF @isManager THEN 
			DELETE FROM Manager WHERE employee_number = @employeID;
	END IF;

	SET @isDriver = EXISTS( 
				SELECT 1 FROM Driver WHERE employee_number = @employeID	);
	IF @isDriver THEN 
			DELETE FROM Driver WHERE employee_number = @employeID;
	END IF;

	SET @isPackager = EXISTS( 
				SELECT 1 FROM Packager WHERE employee_number = @employeID	);
	IF @isPackager THEN 
			DELETE FROM Packager WHERE employee_number = @employeID;
	END IF;


END;
  ";

 try {
    $pdo->exec($sqlt);
} catch (Exception $e) {
    echo "Error creating trigger: " . $e->getMessage();
}

?>