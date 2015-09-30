<?php
	//functions.php
	//funktsioonid, mis on seotud andmebaasiga

	//Loome ühenduse andmebaasiga
	require_once("../config_global.php");
	$database = "if15_earis_3";

	//tekitatakse sessioon, mida hoitakse serveris
	//kõik session muutujad on kättesaadavad kuni viimase brauseriakna sulgemiseni
	session_start();
	
	//võtab andmed ja sisestab andmebaasi
	//võtame vastu kaks muutujat
	function createUser($firstname, $lastname, $email2, $hash){
		$mysqli = new mysqli($GLOBALS["servername"], $GLOBALS["server_username"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("INSERT INTO users (email, password, firstname, lastname) VALUES (?, ?, ?, ?)");
		$stmt->bind_param("ss", $email2, $firstname, $lastname, $hash);
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
			
			//tekitan sessiooni muutujad
			$_SESSION["logged_in_user_id"] = $id_from_db;
			$_SESSION["logged_in_user_email"] = $email_from_db;
			
			//suunan data.php lehele
			header("Location: data.php");
			
		}else{
			echo "Wrong credentials";
		}
		$stmt->close();
		$mysqli->close();
		
	}

?>