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
		
		// If the Tipp table was requested
		if($request->tablename == 'Tipp'){
			$dataAsArray = json_decode($data, true);
			for($i=0;$i<count($dataAsArray);$i++){
				$dataAsArray[$i]["status"] = 'committed'; // Client has this row to know what data is already present on the server (offline created tipps on the client have status = not_committed)
			}
			// Cut off the Tipps that belong to the user (he already has them, we dont want to override them
			if(isset($request->benutzer_id)){
				$cuttedDataAsArray = array();
				for($i=0;$i<count($dataAsArray);$i++){
					if($dataAsArray[$i]["benutzer_fid"] != $request->benutzer_id){
						$dataAsArray[$i]["status"] = 'committed'; // Client has this row to know what data is already present on the server (offline created tipps on the client have status = not_committed)
						$cuttedDataAsArray[] = $dataAsArray[$i];
					}
				}
				$dataAsArray = $cuttedDataAsArray;
			}

			$data = json_encode($dataAsArray);
		};
		
		// If the benutzer_spielt_tipprunde table was requested, calculate the punkte for all users before data is returned to the client
		if($request->tablename == 'Benutzer_spielt_Tipprunde'){		
			// Get all Tipps with results
			$tipps = $sqlAPI->getTippWithBegegnungResult();
			$tippsAsArray = json_decode($tipps, true);
			
			// Calc all Benutzer_spielt_Tipprunde punkte
			$benutzerSpieltTipprundeAsArray = array();
			for($i=0;$i<count($tippsAsArray);$i++){
				$punkte = 0;
				$goalDifferenceTipp = $tippsAsArray[$i]["tipp_tore_heimmannschaft"] - $tippsAsArray[$i]["tipp_tore_auswaertsmannschaft"];
				$goalDifferenceResult = $tippsAsArray[$i]["begegnung_tore_heimmannschaft"] - $tippsAsArray[$i]["begegnung_tore_auswaertsmannschaft"];
				// Correct goal difference, 1 Point
				($goalDifferenceTipp == $goalDifferenceResult) ? $punkte++ : 0;
				// Correct winner, 1 Point
				($goalDifferenceTipp > 0 && $goalDifferenceResult > 0 || $goalDifferenceTipp < 0 && $goalDifferenceResult < 0) ? $punkte++ : 0;
				// Correct Draw, 1 Point
				($goalDifferenceTipp == 0 && $goalDifferenceResult == 0) ? $punkte++ : 0;				
				// Correct Result, 1 Point
				($tippsAsArray[$i]["tipp_tore_heimmannschaft"] == $tippsAsArray[$i]["begegnung_tore_heimmannschaft"] && $tippsAsArray[$i]["tipp_tore_auswaertsmannschaft"] == $tippsAsArray[$i]["begegnung_tore_auswaertsmannschaft"]) ? $punkte++ : 0;

				// Add the points in the result array
				$pointsAdded = false;
				for($x=0;$i<count($benutzerSpieltTipprundeAsArray);$x++){
					if($benutzerSpieltTipprundeAsArray[x]['benutzer_fid'] == $tippAsArray[i]['benutzer_fid'] && 
						$benutzerSpieltTipprundeAsArray[x]['tipprunde_fid'] == $tippAsArray[i]['tipprunde_fid']){
							$benutzerSpieltTipprundeAsArray[x]['punkte'] += $punkte;
							$pointsAdded = true;
						}
				}
				
				if($pointsAdded == false){
					$tableEntry = array();
					$tableEntry['benutzer_fid'] = $tippsAsArray[$i]['benutzer_fid'];
					$tableEntry['tipprunde_fid'] = $tippsAsArray[$i]['tipprunde_fid'];
					$tableEntry['punkte'] = $punkte;
					$benutzerSpieltTipprundeAsArray[] = $tableEntry;
				}
			}
			// Insert the new calculated punkte
			for($x=0;$x<count($benutzerSpieltTipprundeAsArray);$x++){
				$response = $sqlAPI->insertOrUpdateTable('Benutzer_spielt_Tipprunde', $benutzerSpieltTipprundeAsArray[$x]);
			}
			$data = $sqlAPI->getTableContent($request->tablename, $request->version);
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
		$successfullInsertOrUpdates = 0;
		for($i=0;$i<count($tipps);$i++){
			$tippAsArray = (array)$tipps[$i];
			$insertUpdateReturn = $sqlAPI->insertOrUpdateTable('Tipp', $tippAsArray);
			if($insertUpdateReturn == 0){
				$successfullInsertOrUpdates++;
			}
		};
		echo($successfullInsertOrUpdates);
	}	
	
	// Client tries to create a BenutzerSpieltTipprunde entry
	if(isset($request->createBenutzerSpieltTipprunde) && isset($request->dataToInsert)){
		$sqlAPI = new SQLAPI();
		$tablename = "benutzer_spielt_tipprunde";
		$data = $request->dataToInsert;
		$dataArray = (array) $data;
		$returnedData = $sqlAPI->insertTableContent($tablename, $dataArray);
		if(is_numeric($returnedData)){
			echo('success');
		}else{
			echo($returnedData);
		}
	}
}
?>