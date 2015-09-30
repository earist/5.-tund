<?php
	//functions.php
	//funktsioonid, mis on seotud andmebaasiga

	//Loome ühenduse andmebaasiga
	require_once("../config_global.php");
	$database = "if15_earis_3";
	$mysqli = new mysqli($servername, $server_username, $server_password, $database);
	
	//võtab andmed ja sisestab andmebaasi
	//võtame vastu kaks muutujat
	function createUser($email2, $hash){
		$mysqli = new mysqli($GLOBALS["servername"], $GLOBALS["server_username"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
		$stmt->bind_param("ss", $email2, $hash);
		$stmt->execute();
		$stmt->close();
		$mysqli->close();
	}
	
	function loginUser($email1, $hash){
		$mysqli = new mysqli($GLOBALS["servername"], $GLOBALS["server_username"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT id, email FROM users WHERE email=? AND password=?");
		$stmt->bind_param("ss", $email1, $hash);
		$stmt->bind_result($id_from_db, $email_from_db);
		$stmt->execute();
		if($stmt->fetch()){
			echo "Email ja parool õiged, kasutaja id=" .$id_from_db;
		}else{
			echo "Wrong credentials";
		}
		$stmt->close();
		$mysqli->close();
		
	}

?>