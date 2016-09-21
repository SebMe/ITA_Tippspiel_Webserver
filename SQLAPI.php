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
		$sql = sprintf('SELECT * FROM %s WHERE version > \'%s\'', $tablename, $version);
		$result = $this->executeSQL($sql);
		$rows = [];
		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			$rows[] = $row;
		}
		return json_encode($rows);
	}
	
	// $values has to be an associative array
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

	// $values has to be an associative array
	function insertOrUpdateTipp($values){
		$commaSeparatedColumns = implode(',',array_keys($values));
		$commaSeparatedValues = implode('","',array_values($values));
		$updateString = "";
		for($i = 0;$i < count($values);$i++){
			$column = array_keys($values)[$i];
			$value = array_values($values)[$i];
			$updateString = $updateString . $column . '=' . $value;
			if($i < count($values) - 1){
				$updateString = $updateString . ',';
			};
		}
		$sql = sprintf('INSERT INTO Tipp (%s) VALUES ("%s") ON DUPLICATE KEY UPDATE %s',$commaSeparatedColumns, $commaSeparatedValues, $updateString);
		$insertAutoincrementID = null;
		try{
			$insertAutoincrementID = $this->executeInsertSQL($sql);
		} catch(Exception $e){
			$errorString = '[From Server] Insert or Update to Tipp table failed with error: '.$e->getMessage();
			return $errorString;
		}
		return $insertAutoincrementID;
	}		
}
?>