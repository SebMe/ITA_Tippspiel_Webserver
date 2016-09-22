<?php
require_once("SQLAPI.php");

// Connect to server
$mysqli = new mysqli('127.0.0.1', 'root', 'password');
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
$sql = "INSERT INTO Mannschaft(mannschaft_name, mannschaft_flagge) VALUES ('$eins', '$bild_eins') ON DUPLICATE KEY UPDATE mannschaft_name = '$eins', mannschaft_flagge = $bild_eins";
$mysqli->query($sql);
$sql = "INSERT INTO Mannschaft(mannschaft_name, mannschaft_flagge) VALUES ('$zwei', '$bild_zwei') ON DUPLICATE KEY UPDATE mannschaft_name = '$zwei', mannschaft_flagge = $bild_zwei";
$mysqli->query($sql);
$sql = "INSERT INTO Mannschaft(mannschaft_name, mannschaft_flagge) VALUES ('$drei', '$bild_drei') ON DUPLICATE KEY UPDATE mannschaft_name = '$drei', mannschaft_flagge = $bild_drei";
$mysqli->query($sql);
$sql = "INSERT INTO Mannschaft(mannschaft_name, mannschaft_flagge) VALUES ('$vier', '$bild_vier') ON DUPLICATE KEY UPDATE mannschaft_name = '$vier', mannschaft_flagge = $bild_vier";
$mysqli->query($sql);

list($eins, $zwei, $drei, $vier, $bild_eins, $bild_zwei, $bild_drei, $bild_vier) = getTeams($matchesArray2);
$sql = "INSERT INTO Mannschaft(mannschaft_name, mannschaft_flagge) VALUES ('$eins', '$bild_eins') ON DUPLICATE KEY UPDATE mannschaft_name = '$eins', mannschaft_flagge = $bild_eins";
$mysqli->query($sql);
$sql = "INSERT INTO Mannschaft(mannschaft_name, mannschaft_flagge) VALUES ('$zwei', '$bild_zwei') ON DUPLICATE KEY UPDATE mannschaft_name = '$zwei', mannschaft_flagge = $bild_zwei";
$mysqli->query($sql);
$sql = "INSERT INTO Mannschaft(mannschaft_name, mannschaft_flagge) VALUES ('$drei', '$bild_drei') ON DUPLICATE KEY UPDATE mannschaft_name = '$drei', mannschaft_flagge = $bild_drei";
$mysqli->query($sql);
$sql = "INSERT INTO Mannschaft(mannschaft_name, mannschaft_flagge) VALUES ('$vier', '$bild_vier') ON DUPLICATE KEY UPDATE mannschaft_name = '$vier', mannschaft_flagge = $bild_vier";
$mysqli->query($sql);

list($eins, $zwei, $drei, $vier, $bild_eins, $bild_zwei, $bild_drei, $bild_vier) = getTeams($matchesArray3);
$sql = "INSERT INTO Mannschaft(mannschaft_name, mannschaft_flagge) VALUES ('$eins', '$bild_eins') ON DUPLICATE KEY UPDATE mannschaft_name = '$eins', mannschaft_flagge = $bild_eins";
$mysqli->query($sql);
$sql = "INSERT INTO Mannschaft(mannschaft_name, mannschaft_flagge) VALUES ('$zwei', '$bild_zwei') ON DUPLICATE KEY UPDATE mannschaft_name = '$zwei', mannschaft_flagge = $bild_zwei";
$mysqli->query($sql);
$sql = "INSERT INTO Mannschaft(mannschaft_name, mannschaft_flagge) VALUES ('$drei', '$bild_drei') ON DUPLICATE KEY UPDATE mannschaft_name = '$drei', mannschaft_flagge = $bild_drei";
$mysqli->query($sql);
$sql = "INSERT INTO Mannschaft(mannschaft_name, mannschaft_flagge) VALUES ('$vier', '$bild_vier') ON DUPLICATE KEY UPDATE mannschaft_name = '$vier', mannschaft_flagge = $bild_vier";
$mysqli->query($sql);

list($eins, $zwei, $drei, $vier, $bild_eins, $bild_zwei, $bild_drei, $bild_vier) = getTeams($matchesArray4);
$sql = "INSERT INTO Mannschaft(mannschaft_name, mannschaft_flagge) VALUES ('$eins', '$bild_eins') ON DUPLICATE KEY UPDATE mannschaft_name = '$eins', mannschaft_flagge = $bild_eins";
$mysqli->query($sql);
$sql = "INSERT INTO Mannschaft(mannschaft_name, mannschaft_flagge) VALUES ('$zwei', '$bild_zwei') ON DUPLICATE KEY UPDATE mannschaft_name = '$zwei', mannschaft_flagge = $bild_zwei";
$mysqli->query($sql);
$sql = "INSERT INTO Mannschaft(mannschaft_name, mannschaft_flagge) VALUES ('$drei', '$bild_drei') ON DUPLICATE KEY UPDATE mannschaft_name = '$drei', mannschaft_flagge = $bild_drei";
$mysqli->query($sql);
$sql = "INSERT INTO Mannschaft(mannschaft_name, mannschaft_flagge) VALUES ('$vier', '$bild_vier') ON DUPLICATE KEY UPDATE mannschaft_name = '$vier', mannschaft_flagge = $bild_vier";
$mysqli->query($sql);

list($eins, $zwei, $drei, $vier, $bild_eins, $bild_zwei, $bild_drei, $bild_vier) = getTeams($matchesArray5);
$sql = "INSERT INTO Mannschaft(mannschaft_name, mannschaft_flagge) VALUES ('$eins', '$bild_eins') ON DUPLICATE KEY UPDATE mannschaft_name = '$eins', mannschaft_flagge = $bild_eins";
$mysqli->query($sql);
$sql = "INSERT INTO Mannschaft(mannschaft_name, mannschaft_flagge) VALUES ('$zwei', '$bild_zwei') ON DUPLICATE KEY UPDATE mannschaft_name = '$zwei', mannschaft_flagge = $bild_zwei";
$mysqli->query($sql);
$sql = "INSERT INTO Mannschaft(mannschaft_name, mannschaft_flagge) VALUES ('$drei', '$bild_drei') ON DUPLICATE KEY UPDATE mannschaft_name = '$drei', mannschaft_flagge = $bild_drei";
$mysqli->query($sql);
$sql = "INSERT INTO Mannschaft(mannschaft_name, mannschaft_flagge) VALUES ('$vier', '$bild_vier') ON DUPLICATE KEY UPDATE mannschaft_name = '$vier', mannschaft_flagge = $bild_vier";
$mysqli->query($sql);

list($eins, $zwei, $drei, $vier, $bild_eins, $bild_zwei, $bild_drei, $bild_vier) = getTeams($matchesArray6);
$sql = "INSERT INTO Mannschaft(mannschaft_name, mannschaft_flagge) VALUES ('$eins', '$bild_eins') ON DUPLICATE KEY UPDATE mannschaft_name = '$eins', mannschaft_flagge = $bild_eins";
$mysqli->query($sql);
$sql = "INSERT INTO Mannschaft(mannschaft_name, mannschaft_flagge) VALUES ('$zwei', '$bild_zwei') ON DUPLICATE KEY UPDATE mannschaft_name = '$zwei', mannschaft_flagge = $bild_zwei";
$mysqli->query($sql);
$sql = "INSERT INTO Mannschaft(mannschaft_name, mannschaft_flagge) VALUES ('$drei', '$bild_drei') ON DUPLICATE KEY UPDATE mannschaft_name = '$drei', mannschaft_flagge = $bild_drei";
$mysqli->query($sql);
$sql = "INSERT INTO Mannschaft(mannschaft_name, mannschaft_flagge) VALUES ('$vier', '$bild_vier') ON DUPLICATE KEY UPDATE mannschaft_name = '$vier', mannschaft_flagge = $bild_vier";
$mysqli->query($sql);




// Fülle Tabelle "Europameisterschaft" (Von Hand)
$sql = "INSERT INTO Europameisterschaft(europameisterschaft_jahr, europameisterschaft_ort) VALUES ('2016', 'Frankreich')";
$mysqli->query($sql);


function fillBegegnung($matchesArray, $gruppeID){
	// Fülle Tabelle "Begegnung" (Fehlen der FIDs)
	foreach($matchesArray as $i){
			echo($i[MatchResults][1][PointsTeam2]);
		$sql = "INSERT INTO Begegnung(begegnung_spieltermin, begegnung_tore_heimmannschaft, begegnung_tore_auswaertsmannschaft, zeitzone_fid, gruppe_fid, europameisterschaft_fid) VALUES ('$i[MatchDateTime]', '$i[MatchResults][1][PointsTeam1]', '$i[MatchResults][1][PointsTeam2]', 1, '$gruppeID', 1)";
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
 
?>
