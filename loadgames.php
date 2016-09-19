<?php
// Connect to server
$mysqli = new mysqli('127.0.0.1', 'ita', 'itaem2016');
if($mysqli->connect_error){
	var_dump($mysqli->connect_error);
}
// Create database if not exist
$mysqli->query("CREATE DATABASE IF NOT EXISTS itaTestDB") or die($mysqli->error);

// Select the created database
$mysqli->select_db("itaTestDB") or die($mysqli->error);

// Drop the old openligadb table if it exists
$mysqli->query("DROP TABLE IF EXISTS matches") or die($mysqli->error);

// Create the table for openligadb information
$sql = "CREATE TABLE matches (id int PRIMARY KEY AUTO_INCREMENT, matchid int, matchdatetime varchar(30), timezoneid varchar(30), matchdatetimeutc varchar(30), team1id int, team2id int, lastupdatedatetime varchar(30), matchisfinished int, matchresults varchar(30), goals varchar(30), location varchar(30), numberofviewers varchar(30))";
$mysqli->query($sql) or die($mysqli->error);

// Get the JSON Data from OpenLigaDB
$handle = fopen("http://www.openligadb.de/api/getmatchdata/em2016/2016/1", "r");
$line = fgets($handle);
$matchesArray = json_decode($line, true);
var_dump($matchesArray);

// Write the JSON Data into tables
$sql = "INSERT INTO matches (matchid, matchdatetime, timezoneid, matchdatetimeutc, team1id, team2id, lastupdatedatetime, location, numberofviewers) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
foreach($matchesArray as $i){
	$stmt = $mysqli->prepare($sql) or die($mysqli->error);
	$stmt->bind_param('isssiisss', $i["MatchID"], $i["MatchDateTime"], $i["TimeZoneID"], $i["MatchDateTimeUTC"], $i["Team1"]["TeamId"], $i["Team2"]["TeamId"], $i["LastUpdateDateTime"], $i["Location"], $i["NumberOfViewers"]);
	$stmt->execute();
}
fclose($handle)	 
?>
