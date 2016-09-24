<?php
require_once("SQLAPI.php");

// Connect to server
$mysqli = new mysqli('127.0.0.1', 'root', 'password');
//$mysqli = new mysqli('127.0.0.1', 'ita', 'itaem2016');
if($mysqli->connect_error){
	var_dump($mysqli->connect_error);
}

// Select the created database
$mysqli->select_db("mydb") or die($mysqli->error);

// Get the JSON Data from OpenLigaDB
$line = fgets(fopen("http://www.openligadb.de/api/getmatchdata/em2016/2016/1", "r"));
$matchesArray1 = json_decode($line, true);
$line = fgets(fopen("http://www.openligadb.de/api/getmatchdata/em2016/2016/2", "r"));
$matchesArray2 = json_decode($line, true);
$line = fgets(fopen("http://www.openligadb.de/api/getmatchdata/em2016/2016/3", "r"));
$matchesArray3 = json_decode($line, true);
$line = fgets(fopen("http://www.openligadb.de/api/getmatchdata/em2016/2016/4", "r"));
$matchesArray4 = json_decode($line, true);
$line = fgets(fopen("http://www.openligadb.de/api/getmatchdata/em2016/2016/5", "r"));
$matchesArray5 = json_decode($line, true);
$line = fgets(fopen("http://www.openligadb.de/api/getmatchdata/em2016/2016/6", "r"));
$matchesArray6 = json_decode($line, true);
$line = fgets(fopen("http://www.openligadb.de/api/getmatchdata/em2016/2016/7", "r"));
$matchesArray7 = json_decode($line, true);
$line = fgets(fopen("http://www.openligadb.de/api/getmatchdata/em2016/2016/8", "r"));
$matchesArray8 = json_decode($line, true);
$line = fgets(fopen("http://www.openligadb.de/api/getmatchdata/em2016/2016/9", "r"));
$matchesArray9 = json_decode($line, true);
$line = fgets(fopen("http://www.openligadb.de/api/getmatchdata/em2016/2016/10", "r"));
$matchesArray10 = json_decode($line, true);


// Fülle Tabelle "Zeitzone" mit JSON Daten (nur ein Datensatz notwendig, da Zeitzone überall gleich)
foreach($matchesArray1 as $i){}
$sql = "INSERT INTO Zeitzone(zeitzone_name) VALUES ('$i[TimeZoneID]')";
$mysqli->query($sql);


// Fülle Tabelle "Gruppe" (Von Hand)
$sql = "INSERT IGNORE INTO Gruppe(gruppe_name) VALUES ('Gruppe A')";
$mysqli->query($sql);
$sql = "INSERT IGNORE INTO Gruppe(gruppe_name) VALUES ('Gruppe B')";
$mysqli->query($sql);
$sql = "INSERT IGNORE INTO Gruppe(gruppe_name) VALUES ('Gruppe C')";
$mysqli->query($sql);
$sql = "INSERT IGNORE INTO Gruppe(gruppe_name) VALUES ('Gruppe D')";
$mysqli->query($sql);
$sql = "INSERT IGNORE INTO Gruppe(gruppe_name) VALUES ('Gruppe E')";
$mysqli->query($sql);
$sql = "INSERT IGNORE INTO Gruppe(gruppe_name) VALUES ('Gruppe F')";
$mysqli->query($sql);
$sql = "INSERT IGNORE INTO Gruppe(gruppe_name) VALUES ('Achtelfinale')";
$mysqli->query($sql);
$sql = "INSERT IGNORE INTO Gruppe(gruppe_name) VALUES ('Viertelfinale')";
$mysqli->query($sql);
$sql = "INSERT IGNORE INTO Gruppe(gruppe_name) VALUES ('Halbfinale')";
$mysqli->query($sql);
$sql = "INSERT IGNORE INTO Gruppe(gruppe_name) VALUES ('Finale')";
$mysqli->query($sql);




// Fülle Tabelle "Mannschaft" (Hole Teams der ersten zwei Spiele ($key 0 und 1) aller 6 Gruppen)
function getTeams ($matchesArray)
{
	foreach($matchesArray as $key=>$i){
		if ($key == 0){
			$eins = $i[Team1][TeamName];
			$zwei = $i[Team2][TeamName];
			$bild_eins = $i[Team1][TeamIconUrl];	
			$bild_zwei = $i[Team2][TeamIconUrl];	
		} 
	
		if ($key == 1){
			$drei = $i[Team1][TeamName];
			$vier = $i[Team2][TeamName];
			$bild_drei = $i[Team1][TeamIconUrl];	
			$bild_vier = $i[Team2][TeamIconUrl];
		}
	}
	
	return array($eins, $zwei, $drei, $vier, $bild_eins, $bild_zwei, $bild_drei, $bild_vier);
}

list($eins, $zwei, $drei, $vier, $bild_eins, $bild_zwei, $bild_drei, $bild_vier) = getTeams($matchesArray1);
$sql = "INSERT INTO Mannschaft(mannschaft_name, mannschaft_flagge) VALUES ('$eins', '$bild_eins') ON DUPLICATE KEY UPDATE mannschaft_name = '$eins', mannschaft_flagge = '$bild_eins'";
$mysqli->query($sql);
$sql = "INSERT INTO Mannschaft(mannschaft_name, mannschaft_flagge) VALUES ('$zwei', '$bild_zwei') ON DUPLICATE KEY UPDATE mannschaft_name = '$zwei', mannschaft_flagge = '$bild_zwei'";
$mysqli->query($sql);
$sql = "INSERT INTO Mannschaft(mannschaft_name, mannschaft_flagge) VALUES ('$drei', '$bild_drei') ON DUPLICATE KEY UPDATE mannschaft_name = '$drei', mannschaft_flagge = '$bild_drei'";
$mysqli->query($sql);
$sql = "INSERT INTO Mannschaft(mannschaft_name, mannschaft_flagge) VALUES ('$vier', '$bild_vier') ON DUPLICATE KEY UPDATE mannschaft_name = '$vier', mannschaft_flagge = '$bild_vier'";
$mysqli->query($sql);

list($eins, $zwei, $drei, $vier, $bild_eins, $bild_zwei, $bild_drei, $bild_vier) = getTeams($matchesArray2);
$sql = "INSERT INTO Mannschaft(mannschaft_name, mannschaft_flagge) VALUES ('$eins', '$bild_eins') ON DUPLICATE KEY UPDATE mannschaft_name = '$eins', mannschaft_flagge = '$bild_eins'";
$mysqli->query($sql);
$sql = "INSERT INTO Mannschaft(mannschaft_name, mannschaft_flagge) VALUES ('$zwei', '$bild_zwei') ON DUPLICATE KEY UPDATE mannschaft_name = '$zwei', mannschaft_flagge = '$bild_zwei'";
$mysqli->query($sql);
$sql = "INSERT INTO Mannschaft(mannschaft_name, mannschaft_flagge) VALUES ('$drei', '$bild_drei') ON DUPLICATE KEY UPDATE mannschaft_name = '$drei', mannschaft_flagge = '$bild_drei'";
$mysqli->query($sql);
$sql = "INSERT INTO Mannschaft(mannschaft_name, mannschaft_flagge) VALUES ('$vier', '$bild_vier') ON DUPLICATE KEY UPDATE mannschaft_name = '$vier', mannschaft_flagge = '$bild_vier'";
$mysqli->query($sql);

list($eins, $zwei, $drei, $vier, $bild_eins, $bild_zwei, $bild_drei, $bild_vier) = getTeams($matchesArray3);
$sql = "INSERT INTO Mannschaft(mannschaft_name, mannschaft_flagge) VALUES ('$eins', '$bild_eins') ON DUPLICATE KEY UPDATE mannschaft_name = '$eins', mannschaft_flagge = '$bild_eins'";
$mysqli->query($sql);
$sql = "INSERT INTO Mannschaft(mannschaft_name, mannschaft_flagge) VALUES ('$zwei', '$bild_zwei') ON DUPLICATE KEY UPDATE mannschaft_name = '$zwei', mannschaft_flagge = '$bild_zwei'";
$mysqli->query($sql);
$sql = "INSERT INTO Mannschaft(mannschaft_name, mannschaft_flagge) VALUES ('$drei', '$bild_drei') ON DUPLICATE KEY UPDATE mannschaft_name = '$drei', mannschaft_flagge = '$bild_drei'";
$mysqli->query($sql);
$sql = "INSERT INTO Mannschaft(mannschaft_name, mannschaft_flagge) VALUES ('$vier', '$bild_vier') ON DUPLICATE KEY UPDATE mannschaft_name = '$vier', mannschaft_flagge = '$bild_vier'";
$mysqli->query($sql);

list($eins, $zwei, $drei, $vier, $bild_eins, $bild_zwei, $bild_drei, $bild_vier) = getTeams($matchesArray4);
$sql = "INSERT INTO Mannschaft(mannschaft_name, mannschaft_flagge) VALUES ('$eins', '$bild_eins') ON DUPLICATE KEY UPDATE mannschaft_name = '$eins', mannschaft_flagge = '$bild_eins'";
$mysqli->query($sql);
$sql = "INSERT INTO Mannschaft(mannschaft_name, mannschaft_flagge) VALUES ('$zwei', '$bild_zwei') ON DUPLICATE KEY UPDATE mannschaft_name = '$zwei', mannschaft_flagge = '$bild_zwei'";
$mysqli->query($sql);
$sql = "INSERT INTO Mannschaft(mannschaft_name, mannschaft_flagge) VALUES ('$drei', '$bild_drei') ON DUPLICATE KEY UPDATE mannschaft_name = '$drei', mannschaft_flagge = '$bild_drei'";
$mysqli->query($sql);
$sql = "INSERT INTO Mannschaft(mannschaft_name, mannschaft_flagge) VALUES ('$vier', '$bild_vier') ON DUPLICATE KEY UPDATE mannschaft_name = '$vier', mannschaft_flagge = '$bild_vier'";
$mysqli->query($sql);

list($eins, $zwei, $drei, $vier, $bild_eins, $bild_zwei, $bild_drei, $bild_vier) = getTeams($matchesArray5);
$sql = "INSERT INTO Mannschaft(mannschaft_name, mannschaft_flagge) VALUES ('$eins', '$bild_eins') ON DUPLICATE KEY UPDATE mannschaft_name = '$eins', mannschaft_flagge = '$bild_eins'";
$mysqli->query($sql);
$sql = "INSERT INTO Mannschaft(mannschaft_name, mannschaft_flagge) VALUES ('$zwei', '$bild_zwei') ON DUPLICATE KEY UPDATE mannschaft_name = '$zwei', mannschaft_flagge = '$bild_zwei'";
$mysqli->query($sql);
$sql = "INSERT INTO Mannschaft(mannschaft_name, mannschaft_flagge) VALUES ('$drei', '$bild_drei') ON DUPLICATE KEY UPDATE mannschaft_name = '$drei', mannschaft_flagge = '$bild_drei'";
$mysqli->query($sql);
$sql = "INSERT INTO Mannschaft(mannschaft_name, mannschaft_flagge) VALUES ('$vier', '$bild_vier') ON DUPLICATE KEY UPDATE mannschaft_name = '$vier', mannschaft_flagge = '$bild_vier'";
$mysqli->query($sql);

list($eins, $zwei, $drei, $vier, $bild_eins, $bild_zwei, $bild_drei, $bild_vier) = getTeams($matchesArray6);
$sql = "INSERT INTO Mannschaft(mannschaft_name, mannschaft_flagge) VALUES ('$eins', '$bild_eins') ON DUPLICATE KEY UPDATE mannschaft_name = '$eins', mannschaft_flagge = '$bild_eins'";
$mysqli->query($sql);
$sql = "INSERT INTO Mannschaft(mannschaft_name, mannschaft_flagge) VALUES ('$zwei', '$bild_zwei') ON DUPLICATE KEY UPDATE mannschaft_name = '$zwei', mannschaft_flagge = '$bild_zwei'";
$mysqli->query($sql);
$sql = "INSERT INTO Mannschaft(mannschaft_name, mannschaft_flagge) VALUES ('$drei', '$bild_drei') ON DUPLICATE KEY UPDATE mannschaft_name = '$drei', mannschaft_flagge = '$bild_drei'";
$mysqli->query($sql);
$sql = "INSERT INTO Mannschaft(mannschaft_name, mannschaft_flagge) VALUES ('$vier', '$bild_vier') ON DUPLICATE KEY UPDATE mannschaft_name = '$vier', mannschaft_flagge = '$bild_vier'";
$mysqli->query($sql);




// Fülle Tabelle "Europameisterschaft" (Von Hand)
$sql = "INSERT INTO Europameisterschaft(europameisterschaft_jahr, europameisterschaft_ort) VALUES ('2016', 'Frankreich')";
$mysqli->query($sql);

// Suche Mannschaftsname in Tabelle Mannschaft und gebe id aus
function getMannschaftID($name)
{
	$select = "SELECT mannschaft_id FROM Mannschaft WHERE mannschaft_name = '$name'";
	try{
		$sqlAPI = new SQLAPI();
		$result = $sqlAPI->executeSQL($select);	
		$row = $result->fetch_array(MYSQLI_NUM);
	} catch(Exception $e){
		echo($e->getMessage());
	}
	
	return $row[0];
}


// Fülle Tabelle "Begegnung" 
$sql = "ALTER TABLE Begegnung ADD UNIQUE unique_index(begegnung_spieltermin, heimmannschaft_fid, auswaertsmannschaft_fid)";
try{
	$sqlAPI = new SQLAPI();
	$sqlAPI->executeSQL($sql);	
} catch(Exception $e){
	echo($e->getMessage());
}

function fillBegegnung($matchesArray, $gruppeID){

	foreach($matchesArray as $i){
		$tore1 = $i[MatchResults][1][PointsTeam1];
		$tore2 = $i[MatchResults][1][PointsTeam2];
		
		// Gab es eine Verlaengerung, wenn ja aendere Ergebnis
		if ($i[MatchResults][2] != null){
			$tore1 = $i[MatchResults][2][PointsTeam1];
			$tore2 = $i[MatchResults][2][PointsTeam2];
		}
		
		// Gab es Elfmeterschiessen, wenn ja aendere Ergebnis nochmal
		if ($i[MatchResults][3] != null){
			$tore1 = $i[MatchResults][3][PointsTeam1];
			$tore2 = $i[MatchResults][3][PointsTeam2];
		}
		
		$team1 = $i[Team1][TeamName];
		$team2 = $i[Team2][TeamName];
		$teamID1 = getMannschaftID($team1);
		$teamID2 = getMannschaftID($team2);
				
		$sql = "INSERT INTO Begegnung(begegnung_spieltermin, begegnung_tore_heimmannschaft, begegnung_tore_auswaertsmannschaft, zeitzone_fid, gruppe_fid, europameisterschaft_fid, heimmannschaft_fid, auswaertsmannschaft_fid) VALUES ('$i[MatchDateTime]', '$tore1', '$tore2', 1, '$gruppeID', 1, '$teamID1', '$teamID2') ON DUPLICATE KEY UPDATE begegnung_spieltermin = '$i[MatchDateTime]', begegnung_tore_heimmannschaft = '$tore1', begegnung_tore_auswaertsmannschaft = '$tore2', heimmannschaft_fid = '$teamID1', auswaertsmannschaft_fid = '$teamID2'";
		try{
			$sqlAPI = new SQLAPI();
			$sqlAPI->executeSQL($sql);	
		} catch(Exception $e){
			echo($e->getMessage());
		}
	};
};


fillBegegnung($matchesArray1, 1);
fillBegegnung($matchesArray2, 2);
fillBegegnung($matchesArray3, 3);
fillBegegnung($matchesArray4, 4);
fillBegegnung($matchesArray5, 5);
fillBegegnung($matchesArray6, 6);
fillBegegnung($matchesArray7, 7);
fillBegegnung($matchesArray8, 8);
fillBegegnung($matchesArray9, 9);
fillBegegnung($matchesArray10, 10);

// Aktualisieren des fehlerhaften Spielergebnisses aus der openligaDb
$sql = "UPDATE Begegnung SET begegnung_tore_heimmannschaft= 5, begegnung_tore_auswaertsmannschaft = 6 WHERE begegnung_id = 37";
try{
	$sqlAPI = new SQLAPI();
	$sqlAPI->executeSQL($sql);	
} catch(Exception $e){
	echo($e->getMessage());
}


// Füllen der Zwischentabelle "Gruppe_enthaelt_Mannschaft"
list($team1, $team2, $team3, $team4, , , , ) = getTeams($matchesArray1);
$teamID1 = getMannschaftID($team1);
$teamID2 = getMannschaftID($team2);
$teamID3 = getMannschaftID($team3);
$teamID4 = getMannschaftID($team4);
$sql = "INSERT INTO Gruppe_enthaelt_Mannschaft(gruppe_fid, mannschaft_fid) VALUES (1, '$teamID1') ON DUPLICATE KEY UPDATE gruppe_fid = 1, mannschaft_fid = '$teamID1'";
$mysqli->query($sql);
$sql = "INSERT INTO Gruppe_enthaelt_Mannschaft(gruppe_fid, mannschaft_fid) VALUES (1, '$teamID2') ON DUPLICATE KEY UPDATE gruppe_fid = 1, mannschaft_fid = '$teamID2'";
$mysqli->query($sql);
$sql = "INSERT INTO Gruppe_enthaelt_Mannschaft(gruppe_fid, mannschaft_fid) VALUES (1, '$teamID3') ON DUPLICATE KEY UPDATE gruppe_fid = 1, mannschaft_fid = '$teamID3'";
$mysqli->query($sql);
$sql = "INSERT INTO Gruppe_enthaelt_Mannschaft(gruppe_fid, mannschaft_fid) VALUES (1, '$teamID4') ON DUPLICATE KEY UPDATE gruppe_fid = 1, mannschaft_fid = '$teamID4'";
$mysqli->query($sql);

list($team1, $team2, $team3, $team4, , , , ) = getTeams($matchesArray2);
$teamID1 = getMannschaftID($team1);
$teamID2 = getMannschaftID($team2);
$teamID3 = getMannschaftID($team3);
$teamID4 = getMannschaftID($team4);
$sql = "INSERT INTO Gruppe_enthaelt_Mannschaft(gruppe_fid, mannschaft_fid) VALUES (2, '$teamID1') ON DUPLICATE KEY UPDATE gruppe_fid = 2, mannschaft_fid = '$teamID1'";
$mysqli->query($sql);
$sql = "INSERT INTO Gruppe_enthaelt_Mannschaft(gruppe_fid, mannschaft_fid) VALUES (2, '$teamID2') ON DUPLICATE KEY UPDATE gruppe_fid = 2, mannschaft_fid = '$teamID2'";
$mysqli->query($sql);
$sql = "INSERT INTO Gruppe_enthaelt_Mannschaft(gruppe_fid, mannschaft_fid) VALUES (2, '$teamID3') ON DUPLICATE KEY UPDATE gruppe_fid = 2, mannschaft_fid = '$teamID3'";
$mysqli->query($sql);
$sql = "INSERT INTO Gruppe_enthaelt_Mannschaft(gruppe_fid, mannschaft_fid) VALUES (2, '$teamID4') ON DUPLICATE KEY UPDATE gruppe_fid = 2, mannschaft_fid = '$teamID4'";
$mysqli->query($sql);

list($team1, $team2, $team3, $team4, , , , ) = getTeams($matchesArray3);
$teamID1 = getMannschaftID($team1);
$teamID2 = getMannschaftID($team2);
$teamID3 = getMannschaftID($team3);
$teamID4 = getMannschaftID($team4);
$sql = "INSERT INTO Gruppe_enthaelt_Mannschaft(gruppe_fid, mannschaft_fid) VALUES (3, '$teamID1') ON DUPLICATE KEY UPDATE gruppe_fid = 3, mannschaft_fid = '$teamID1'";
$mysqli->query($sql);
$sql = "INSERT INTO Gruppe_enthaelt_Mannschaft(gruppe_fid, mannschaft_fid) VALUES (3, '$teamID2') ON DUPLICATE KEY UPDATE gruppe_fid = 3, mannschaft_fid = '$teamID2'";
$mysqli->query($sql);
$sql = "INSERT INTO Gruppe_enthaelt_Mannschaft(gruppe_fid, mannschaft_fid) VALUES (3, '$teamID3') ON DUPLICATE KEY UPDATE gruppe_fid = 3, mannschaft_fid = '$teamID3'";
$mysqli->query($sql);
$sql = "INSERT INTO Gruppe_enthaelt_Mannschaft(gruppe_fid, mannschaft_fid) VALUES (3, '$teamID4') ON DUPLICATE KEY UPDATE gruppe_fid = 3, mannschaft_fid = '$teamID4'";
$mysqli->query($sql);

list($team1, $team2, $team3, $team4, , , , ) = getTeams($matchesArray4);
$teamID1 = getMannschaftID($team1);
$teamID2 = getMannschaftID($team2);
$teamID3 = getMannschaftID($team3);
$teamID4 = getMannschaftID($team4);
$sql = "INSERT INTO Gruppe_enthaelt_Mannschaft(gruppe_fid, mannschaft_fid) VALUES (4, '$teamID1') ON DUPLICATE KEY UPDATE gruppe_fid = 4, mannschaft_fid = '$teamID1'";
$mysqli->query($sql);
$sql = "INSERT INTO Gruppe_enthaelt_Mannschaft(gruppe_fid, mannschaft_fid) VALUES (4, '$teamID2') ON DUPLICATE KEY UPDATE gruppe_fid = 4, mannschaft_fid = '$teamID2'";
$mysqli->query($sql);
$sql = "INSERT INTO Gruppe_enthaelt_Mannschaft(gruppe_fid, mannschaft_fid) VALUES (4, '$teamID3') ON DUPLICATE KEY UPDATE gruppe_fid = 4, mannschaft_fid = '$teamID3'";
$mysqli->query($sql);
$sql = "INSERT INTO Gruppe_enthaelt_Mannschaft(gruppe_fid, mannschaft_fid) VALUES (4, '$teamID4') ON DUPLICATE KEY UPDATE gruppe_fid = 4, mannschaft_fid = '$teamID4'";
$mysqli->query($sql);

list($team1, $team2, $team3, $team4, , , , ) = getTeams($matchesArray5);
$teamID1 = getMannschaftID($team1);
$teamID2 = getMannschaftID($team2);
$teamID3 = getMannschaftID($team3);
$teamID4 = getMannschaftID($team4);
$sql = "INSERT INTO Gruppe_enthaelt_Mannschaft(gruppe_fid, mannschaft_fid) VALUES (5, '$teamID1') ON DUPLICATE KEY UPDATE gruppe_fid = 5, mannschaft_fid = '$teamID1'";
$mysqli->query($sql);
$sql = "INSERT INTO Gruppe_enthaelt_Mannschaft(gruppe_fid, mannschaft_fid) VALUES (5, '$teamID2') ON DUPLICATE KEY UPDATE gruppe_fid = 5, mannschaft_fid = '$teamID2'";
$mysqli->query($sql);
$sql = "INSERT INTO Gruppe_enthaelt_Mannschaft(gruppe_fid, mannschaft_fid) VALUES (5, '$teamID3') ON DUPLICATE KEY UPDATE gruppe_fid = 5, mannschaft_fid = '$teamID3'";
$mysqli->query($sql);
$sql = "INSERT INTO Gruppe_enthaelt_Mannschaft(gruppe_fid, mannschaft_fid) VALUES (5, '$teamID4') ON DUPLICATE KEY UPDATE gruppe_fid = 5, mannschaft_fid = '$teamID4'";
$mysqli->query($sql);

list($team1, $team2, $team3, $team4, , , , ) = getTeams($matchesArray6);
$teamID1 = getMannschaftID($team1);
$teamID2 = getMannschaftID($team2);
$teamID3 = getMannschaftID($team3);
$teamID4 = getMannschaftID($team4);
$sql = "INSERT INTO Gruppe_enthaelt_Mannschaft(gruppe_fid, mannschaft_fid) VALUES (6, '$teamID1') ON DUPLICATE KEY UPDATE gruppe_fid = 6, mannschaft_fid = '$teamID1'";
$mysqli->query($sql);
$sql = "INSERT INTO Gruppe_enthaelt_Mannschaft(gruppe_fid, mannschaft_fid) VALUES (6, '$teamID2') ON DUPLICATE KEY UPDATE gruppe_fid = 6, mannschaft_fid = '$teamID2'";
$mysqli->query($sql);
$sql = "INSERT INTO Gruppe_enthaelt_Mannschaft(gruppe_fid, mannschaft_fid) VALUES (6, '$teamID3') ON DUPLICATE KEY UPDATE gruppe_fid = 6, mannschaft_fid = '$teamID3'";
$mysqli->query($sql);
$sql = "INSERT INTO Gruppe_enthaelt_Mannschaft(gruppe_fid, mannschaft_fid) VALUES (6, '$teamID4') ON DUPLICATE KEY UPDATE gruppe_fid = 6, mannschaft_fid = '$teamID4'";
$mysqli->query($sql);


// Füllen der Zwischentabelle "Europameisterschaft_beinhaltet_Mannschaft"
list($team1, $team2, $team3, $team4, , , , ) = getTeams($matchesArray1);
$teamID1 = getMannschaftID($team1);
$teamID2 = getMannschaftID($team2);
$teamID3 = getMannschaftID($team3);
$teamID4 = getMannschaftID($team4);
$sql = "INSERT INTO Europameisterschaft_beinhaltet_Mannschaft(europameisterschaft_fid, mannschaft_fid) VALUES (1, '$teamID1') ON DUPLICATE KEY UPDATE europameisterschaft_fid = 1, mannschaft_fid = '$teamID1'";
$mysqli->query($sql);
$sql = "INSERT INTO Europameisterschaft_beinhaltet_Mannschaft(europameisterschaft_fid, mannschaft_fid) VALUES (1, '$teamID2') ON DUPLICATE KEY UPDATE europameisterschaft_fid = 1, mannschaft_fid = '$teamID2'";
$mysqli->query($sql);
$sql = "INSERT INTO Europameisterschaft_beinhaltet_Mannschaft(europameisterschaft_fid, mannschaft_fid) VALUES (1, '$teamID3') ON DUPLICATE KEY UPDATE europameisterschaft_fid = 1, mannschaft_fid = '$teamID3'";
$mysqli->query($sql);
$sql = "INSERT INTO Europameisterschaft_beinhaltet_Mannschaft(europameisterschaft_fid, mannschaft_fid) VALUES (1, '$teamID4') ON DUPLICATE KEY UPDATE europameisterschaft_fid = 1, mannschaft_fid = '$teamID4'";
$mysqli->query($sql);

list($team1, $team2, $team3, $team4, , , , ) = getTeams($matchesArray2);
$teamID1 = getMannschaftID($team1);
$teamID2 = getMannschaftID($team2);
$teamID3 = getMannschaftID($team3);
$teamID4 = getMannschaftID($team4);
$sql = "INSERT INTO Europameisterschaft_beinhaltet_Mannschaft(europameisterschaft_fid, mannschaft_fid) VALUES (1, '$teamID1') ON DUPLICATE KEY UPDATE europameisterschaft_fid = 1, mannschaft_fid = '$teamID1'";
$mysqli->query($sql);
$sql = "INSERT INTO Europameisterschaft_beinhaltet_Mannschaft(europameisterschaft_fid, mannschaft_fid) VALUES (1, '$teamID2') ON DUPLICATE KEY UPDATE europameisterschaft_fid = 1, mannschaft_fid = '$teamID2'";
$mysqli->query($sql);
$sql = "INSERT INTO Europameisterschaft_beinhaltet_Mannschaft(europameisterschaft_fid, mannschaft_fid) VALUES (1, '$teamID3') ON DUPLICATE KEY UPDATE europameisterschaft_fid = 1, mannschaft_fid = '$teamID3'";
$mysqli->query($sql);
$sql = "INSERT INTO Europameisterschaft_beinhaltet_Mannschaft(europameisterschaft_fid, mannschaft_fid) VALUES (1, '$teamID4') ON DUPLICATE KEY UPDATE europameisterschaft_fid = 1, mannschaft_fid = '$teamID4'";
$mysqli->query($sql);

list($team1, $team2, $team3, $team4, , , , ) = getTeams($matchesArray3);
$teamID1 = getMannschaftID($team1);
$teamID2 = getMannschaftID($team2);
$teamID3 = getMannschaftID($team3);
$teamID4 = getMannschaftID($team4);
$sql = "INSERT INTO Europameisterschaft_beinhaltet_Mannschaft(europameisterschaft_fid, mannschaft_fid) VALUES (1, '$teamID1') ON DUPLICATE KEY UPDATE europameisterschaft_fid = 1, mannschaft_fid = '$teamID1'";
$mysqli->query($sql);
$sql = "INSERT INTO Europameisterschaft_beinhaltet_Mannschaft(europameisterschaft_fid, mannschaft_fid) VALUES (1, '$teamID2') ON DUPLICATE KEY UPDATE europameisterschaft_fid = 1, mannschaft_fid = '$teamID2'";
$mysqli->query($sql);
$sql = "INSERT INTO Europameisterschaft_beinhaltet_Mannschaft(europameisterschaft_fid, mannschaft_fid) VALUES (1, '$teamID3') ON DUPLICATE KEY UPDATE europameisterschaft_fid = 1, mannschaft_fid = '$teamID3'";
$mysqli->query($sql);
$sql = "INSERT INTO Europameisterschaft_beinhaltet_Mannschaft(europameisterschaft_fid, mannschaft_fid) VALUES (1, '$teamID4') ON DUPLICATE KEY UPDATE europameisterschaft_fid = 1, mannschaft_fid = '$teamID4'";
$mysqli->query($sql);

list($team1, $team2, $team3, $team4, , , , ) = getTeams($matchesArray4);
$teamID1 = getMannschaftID($team1);
$teamID2 = getMannschaftID($team2);
$teamID3 = getMannschaftID($team3);
$teamID4 = getMannschaftID($team4);
$sql = "INSERT INTO Europameisterschaft_beinhaltet_Mannschaft(europameisterschaft_fid, mannschaft_fid) VALUES (1, '$teamID1') ON DUPLICATE KEY UPDATE europameisterschaft_fid = 1, mannschaft_fid = '$teamID1'";
$mysqli->query($sql);
$sql = "INSERT INTO Europameisterschaft_beinhaltet_Mannschaft(europameisterschaft_fid, mannschaft_fid) VALUES (1, '$teamID2') ON DUPLICATE KEY UPDATE europameisterschaft_fid = 1, mannschaft_fid = '$teamID2'";
$mysqli->query($sql);
$sql = "INSERT INTO Europameisterschaft_beinhaltet_Mannschaft(europameisterschaft_fid, mannschaft_fid) VALUES (1, '$teamID3') ON DUPLICATE KEY UPDATE europameisterschaft_fid = 1, mannschaft_fid = '$teamID3'";
$mysqli->query($sql);
$sql = "INSERT INTO Europameisterschaft_beinhaltet_Mannschaft(europameisterschaft_fid, mannschaft_fid) VALUES (1, '$teamID4') ON DUPLICATE KEY UPDATE europameisterschaft_fid = 1, mannschaft_fid = '$teamID4'";
$mysqli->query($sql);

list($team1, $team2, $team3, $team4, , , , ) = getTeams($matchesArray5);
$teamID1 = getMannschaftID($team1);
$teamID2 = getMannschaftID($team2);
$teamID3 = getMannschaftID($team3);
$teamID4 = getMannschaftID($team4);
$sql = "INSERT INTO Europameisterschaft_beinhaltet_Mannschaft(europameisterschaft_fid, mannschaft_fid) VALUES (1, '$teamID1') ON DUPLICATE KEY UPDATE europameisterschaft_fid = 1, mannschaft_fid = '$teamID1'";
$mysqli->query($sql);
$sql = "INSERT INTO Europameisterschaft_beinhaltet_Mannschaft(europameisterschaft_fid, mannschaft_fid) VALUES (1, '$teamID2') ON DUPLICATE KEY UPDATE europameisterschaft_fid = 1, mannschaft_fid = '$teamID2'";
$mysqli->query($sql);
$sql = "INSERT INTO Europameisterschaft_beinhaltet_Mannschaft(europameisterschaft_fid, mannschaft_fid) VALUES (1, '$teamID3') ON DUPLICATE KEY UPDATE europameisterschaft_fid = 1, mannschaft_fid = '$teamID3'";
$mysqli->query($sql);
$sql = "INSERT INTO Europameisterschaft_beinhaltet_Mannschaft(europameisterschaft_fid, mannschaft_fid) VALUES (1, '$teamID4') ON DUPLICATE KEY UPDATE europameisterschaft_fid = 1, mannschaft_fid = '$teamID4'";
$mysqli->query($sql);

list($team1, $team2, $team3, $team4, , , , ) = getTeams($matchesArray6);
$teamID1 = getMannschaftID($team1);
$teamID2 = getMannschaftID($team2);
$teamID3 = getMannschaftID($team3);
$teamID4 = getMannschaftID($team4);
$sql = "INSERT INTO Europameisterschaft_beinhaltet_Mannschaft(europameisterschaft_fid, mannschaft_fid) VALUES (1, '$teamID1') ON DUPLICATE KEY UPDATE europameisterschaft_fid = 1, mannschaft_fid = '$teamID1'";
$mysqli->query($sql);
$sql = "INSERT INTO Europameisterschaft_beinhaltet_Mannschaft(europameisterschaft_fid, mannschaft_fid) VALUES (1, '$teamID2') ON DUPLICATE KEY UPDATE europameisterschaft_fid = 1, mannschaft_fid = '$teamID2'";
$mysqli->query($sql);
$sql = "INSERT INTO Europameisterschaft_beinhaltet_Mannschaft(europameisterschaft_fid, mannschaft_fid) VALUES (1, '$teamID3') ON DUPLICATE KEY UPDATE europameisterschaft_fid = 1, mannschaft_fid = '$teamID3'";
$mysqli->query($sql);
$sql = "INSERT INTO Europameisterschaft_beinhaltet_Mannschaft(europameisterschaft_fid, mannschaft_fid) VALUES (1, '$teamID4') ON DUPLICATE KEY UPDATE europameisterschaft_fid = 1, mannschaft_fid = '$teamID4'";
$mysqli->query($sql);

?>
