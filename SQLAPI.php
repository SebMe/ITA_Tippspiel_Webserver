<?php
class SQLAPI {
	function connectDB(){
		$mysqli = new mysqli('localhost', 'root', 'password');
		if($mysqli->connect_error){throw new Exception($mysqli->connect_error);}
		$mysqli->select_db("mydb");
		if($mysqli->error){throw new Exception($mysqli->error);}
		return $mysqli;
	}

	function executeInsertSQL($sql){
		$mysqli = $this->connectDB();
		$stmt = $mysqli->prepare($sql);
		if($mysqli->error){throw new Exception($mysqli->error);}
		$stmt->execute();
		if($stmt->error){throw new Exception($stmt->error);};
		$id = $mysqli->insert_id;
		$mysqli->close();
		return $id;
	}
	
	function executeSQL($sql){
		$mysqli = $this->connectDB();
		$stmt = $mysqli->prepare($sql);
		if($mysqli->error){throw new Exception($mysqli->error);}
		$stmt->execute();
		if($stmt->error){throw new Exception($stmt->error);};
		$result = $stmt->get_result();
		return $result;
	}
	
	function executeSQLParam($sql, $paramTypes, $params){
		$mysqli = $this->connectDB();
		$stmt = $mysqli->prepare($sql);
		if($mysqli->error){throw new Exception($mysqli->error);};
		call_user_func_array(array($stmt, "bind_param"), array_merge(array($paramTypes), $params));
		$stmt->execute();// or die($stmt->error);
		if($stmt->error){throw new Exception($stmt->error);};
		$result = $stmt->get_result();
		$mysqli->close();
		return $result;
	}
	
	function getTableContent($tablename, $version){
		$sql = sprintf('SELECT * FROM %s WHERE version > %s', $tablename, $version);
		$result = $this->executeSQL($sql);
		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			$rows[] = $row;
		}
		return json_encode($rows);
	}
	
	// $values has to be an associated array
	function insertTableContent($tablename, $values){
		$commaSeparatedColumns = implode(',',array_keys($values));
		$commaSeparatedValues = implode('","',array_values($values));
		$sql = sprintf('INSERT INTO %s (%s) VALUES ("%s")',$tablename, $commaSeparatedColumns, $commaSeparatedValues);
		$insertAutoincrementID = null;
		try{
			$insertAutoincrementID = $this->executeInsertSQL($sql);
		} catch(Exception $e){
			$errorString = '[From Server] Insert to '.$tablename.' table failed with error: '.$e->getMessage();
			return $errorString;
		}
		return $insertAutoincrementID;
	}	
	
	/*
	function updateBenutzerTable($values){
		// values is a 2 dimensional array: values[0] = [0]user1, [1]pass1, .., values[1] = [0]user2, [1]pass2, .., 
		foreach($values as $row){
			$commaSeparatedColumns = implode(',',array_keys($row));
			$commaSeparatedValues = implode('","',array_values($row));
			$sql = sprintf(
			'INSERT INTO benutzer (%s) VALUES ("%s")',$commaSeparatedColumns, $commaSeparatedValues);
			try{
				$result = $this->executeSQL($sql);
			} catch(Exception $e){
				$resultString = '[From Server] Update to Benutzer table failed with error: '.$e->getMessage();
				return $resultString;
			}
		}
		return '[From Server] Update to Benutzer table successfull.';
	}	
	
	function updateTipprundeTable(){
		
	}
	
	function updateTippsTable(){
		
	}
	
	function getBenutzerTable($version){
		
	}
	
	function getTipprundenTable($version){
		
	}
	
	function getTippsTable($version){
		
	}
	
	function getTableVersions(){
		$sql = 'select * from table_versions';
		$result = $this->executeSQL($sql);
		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			$rows[] = $row;
		}
		return json_encode($rows);
	}*/
}
?>