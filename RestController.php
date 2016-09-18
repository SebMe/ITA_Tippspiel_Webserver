<?php
require_once("SQLAPI.php");

if(isset($_GET["gettableversions"])){
	$sqlAPI = new SQLAPI();
	$data = $sqlAPI->getTableVersions();
	var_dump($data);
	error_log('get success');
}

$postdata = file_get_contents("php://input");
if(isset($postdata)){
	$request = json_decode($postdata);
	
	// Client sends {tablename: someTablename, version: someTimestamp}
	// All table entries in the server table with server.version > client.version are unknown for the client
	// Server will return all data of that table where server.version > client.version to bring the client to the up-to-date table content
	if(isset($request->tablename) && isset($request->version)){
		$sqlAPI = new SQLAPI();
		$data = $sqlAPI->getTableContent($request->tablename, 0);
		var_dump($data);
	}
	
	// Client tries to create a user
	if(isset($request->createUser) && isset($request->user)){
		$sqlAPI = new SQLAPI();
		$tablename = "Benutzer";
		$user = $request->user;
		$userArray = (array) $user;
		$data = $sqlAPI->insertTableContent($tablename, $userArray);
		echo($data);
		//var_dump($data);
	}
}
?>