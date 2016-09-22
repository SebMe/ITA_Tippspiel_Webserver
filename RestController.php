<?php
require_once("SQLAPI.php");

$postdata = file_get_contents("php://input");
if(isset($postdata)){
	$request = json_decode($postdata);
	
	// Client tries to trigger and openLigaDB server reload
	// To be changed to the correct open liga db fetch
	if(isset($request->serverFetchOpenLigaDB)){
		echo('success');
	};
	
	// Client sends {tablename: someTablename, version: someTimestamp, benutzer_id: IDOfLoggedInBenutzer}
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
		
		// If the Tipp table was requested, cut off the Tipps that belong to the user (he already has them, we dont want to override them)
		if($request->tablename == 'Tipp' && isset($request->benutzer_id)){
			$dataAsArray = json_decode($data, true);
			$cuttedDataAsArray = array();
			for($i=0;$i<count($dataAsArray);$i++){
				if($dataAsArray[$i]["benutzer_fid"] != $request->benutzer_id){
					$dataAsArray[$i]["status"] = 'committed'; // Client has this row to know what data is already present on the server (offline created tipps on the client have status = not_committed)
					$cuttedDataAsArray[] = $dataAsArray[$i];
				}
			}
			$data = json_encode($cuttedDataAsArray);
		};
		
		// If the benutzer_spielt_tipprunde table was requested, calculate the punkte for all users before data is returned to the client
		if($request->tablename == 'benutzer_spielt_tipprunde'){
			$data = $sqlAPI->getTableContent($request->tablename, 0);
			$dataAsArray = json_decode($data, true);
			
			
			$cuttedDataAsArray = array();
			for($i=0;$i<count($dataAsArray);$i++){
				if($dataAsArray[$i]["benutzer_fid"] != $request->benutzer_id){
					$dataAsArray[$i]["status"] = 'committed'; // Client has this row to know what data is already present on the server (offline created tipps on the client have status = not_committed)
					$cuttedDataAsArray[] = $dataAsArray[$i];
				}
			}
			$data = json_encode($cuttedDataAsArray);
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