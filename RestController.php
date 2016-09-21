<?php
require_once("SQLAPI.php");

$postdata = file_get_contents("php://input");
if(isset($postdata)){
	$request = json_decode($postdata);
	
	// To be changed to the correct open liga db fetch
	if(isset($request->serverFetchOpenLigaDB)){
		$handle = fopen("http://www.openligadb.de/api/getmatchdata/em2016/2016/1", "r");
		$line = fgets($handle);
		$matchesArray = json_decode($line, true);
		var_dump($matchesArray);
		$sqlAPI = new SQLAPI();
		
	}
	
	// Client sends {tablename: someTablename, version: someTimestamp}
	// All table entries in the server table with server.version > client.version are unknown for the client
	// Server will return all data of that table where server.version > client.version to bring the client to the up-to-date table content
	if(isset($request->tablename) && isset($request->version)){
		$sqlAPI = new SQLAPI();
		$data = $sqlAPI->getTableContent($request->tablename, $request->version);
		// If the Benutzer table was requested, cut off the email and passwort
		if($request->tablename == 'Benutzer'){
			$dataAsArray = json_decode($data, true);
			for($i=0;$i<count($dataAsArray);$i++){
				unset($dataAsArray[$i]["benutzer_passwort"]);
				unset($dataAsArray[$i]["benutzer_mailadresse"]);
			}
			$data = json_encode($dataAsArray);
		};
		echo($data);
	};
	
	// Client tries to create a user
	if(isset($request->createUser) && isset($request->user)){
		$sqlAPI = new SQLAPI();
		$tablename = "Benutzer";
		$user = $request->user;
		$userArray = (array) $user;
		unset($userArray['benutzer_id']); // This field is send by the client but has no value, have to remove it to use the insertTableContent method
		$data = $sqlAPI->insertTableContent($tablename, $userArray);
		echo($data);
	}
	
	// Client tries to create a Tipprunde
	if(isset($request->createTipprunde) && isset($request->tipprunde)){
		$sqlAPI = new SQLAPI();
		$tablename = "Tipprunde";
		$tipprunde = $request->tipprunde;
		$tipprundeArray = (array) $tipprunde;
		unset($tipprundeArray['tipprunde_id']); // This field is send by the client but has no value, have to remove it to use the insertTableContent method
		$data = $sqlAPI->insertTableContent($tablename, $tipprundeArray);
		echo($data);
	}
	
	// Client tries to create or update Tipps
	if(isset($request->createOrUpdateTipps) && isset($request->tipps)){
		$sqlAPI = new SQLAPI();
		$tipps = $request->tipps;
		$i = 0;
		for(;$i<count($tipps);$i++){
			$tippAsArray = (array)$tipps[$i];
			$data = $sqlAPI->insertOrUpdateTipp($tippAsArray);
		};
		echo($i);
	}
}
?>