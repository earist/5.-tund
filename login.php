<?php

	require_once("../config.php");
	$database = "if15_earis_3";
	$mysqli = new mysqli($servername, $username, $password, $database);
	
	//login.php
	
	$email1_error = "";
	$email2_error = "";
	$password1_error = "";
	$password2_error = "";
	$password3_error ="";
	$eesnimi_error ="";
	$perekonnanimi_error ="";
	//muutujad andmebaasi väärtuse jaoks
	$email1 ="";
	$email2 ="";
	$eesnimi ="";
	$perekonnanimi ="";
	$password1 ="";
	$password2 ="";
	
	//kontrollime, et keegi vajutas input nuppu
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		
		//echo "Keegi vajutas nuppu"; sedasi tehakse kontroll ainult siis, kui vajutatakse login nuppu
		//vajutas login nuppu
		if(isset($_POST['login'])){
			
			
			//kontrollin, et epost ei ole tühi
			if ( empty($_POST["email1"]) ) {
				$email1_error = "See väli on kohustuslik";
			}else{
				//kõik korras, test_input eemaldab pahatahtlikud osad
				$email1 = test_input($_POST["email1"]);
				}
				
			//kontrollin, et parool ei ole tühi
			if ( empty($_POST["password1"]) ) {
				$password1_error = "See väli on kohustuslik";	
			} else if(strlen($_POST["password1"]) < 8) {
					$password1_error ="Peab olema vähemalt 8 sümbolit pikk!";
			}
		//Võib kasutaja sisse logida
			if($password1_error == "" && $email1_error == ""){
				echo "Võib sisse logida! Kasutajanimi on ".$email1." ja parool on ".$password1;
				
				$hash = hash("sha512", $password1);
				$stmt = $mysqli->prepare("SELECT id, email FROM users WHERE email=? AND password=?");
				$stmt->bind_param("ss", $email1, $hash);
				
				//muutujad tulemustele
				$stmt->bind_result($id_from_db, $email_from_db);
				$stmt->execute();
				
				//Kontrollin kas tulemusi leiti
				if($stmt->fetch()){
					//andmebaasis oli midagi
					echo "Email ja parool õiged, kasutaja id=" .$id_from_db;
		
				}else{
					//ei leidnud
					echo "Wrong credentials";
				}
					
				$stmt->close();
			}
			
			
			//*****************************************
			//keegi vajutas registreeri nuppu
		}elseif(isset($_POST['registreeri'])) {
			
			//kontrollin, et nimi pole tühi
			if ( empty($_POST["eesnimi"]) ) {
				$eesnimi_error = "See väli on kohustuslik";
			}else{
				//kõik korras, test_input eemaldab pahatahtlikud osad
				$eesnimi = test_input($_POST["eesnimi"]);
				}
				
				
			// kontrollin et perekonnanimi pole tühi
			if ( empty($_POST["perekonnanimi"]) ) {
				$perekonnanimi_error = "See väli on kohustuslik";
			}else{
				//kõik korras, test_input eemaldab pahatahtlikud osad
				$perekonnanimi = test_input($_POST["perekonnanimi"]);
				}
			
			//kontrollin, et epost ei ole tühi
			if ( empty($_POST["email2"]) ) {
				$email2_error = "See väli on kohustuslik";
			}else{
				//kõik korras, test_input eemaldab pahatahtlikud osad
				$email2 = test_input($_POST["email2"]);
				}
				
			
			//kontrollin, et parool ei ole tühi
			if ( empty($_POST["password2"]) ) {
				$password2_error = "See väli on kohustuslik";	
			} else {
				
				
				if(strlen($_POST["password2"]) < 8) {
					$password2_error ="Peab olema vähemalt 8 sümbolit pikk!";
				}else{
					$password2 = test_input($_POST["password2"]);
				}
			}
			
			
			//kontrollin, et paroolid klapiksid
			if ($_POST["password2"] != $_POST["password3"]) {
				$password3_error = "Paroolid ei kattu. Proovi uuesti.";	

				}
				
				
			if(	$email2_error == "" && $password2_error == ""){
				
				//räsi paroolist, mille salvestame andmebaasi
				$hash = hash("sha512", $password2);
				
				echo "Võib kasutajat luua! Kasutajanimi on ".$email2." ja parool on ".$password2. "ja räsi on ".$hash;
				
				$stmt = $mysqli->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
				//echo $mysqli->error;
				//echo $stmt->error;
				
				// asendame ?;? ss - s on string email, s on string password
				$stmt->bind_param("ss", $email2, $hash);
				$stmt->execute();
				$stmt->close();
			}
			
				
			}
		} 
		
		
	function test_input($data) {	
		$data = trim($data);	//võtab ära tühikud,enterid,tabid
		$data = stripslashes($data);  //võtab ära tagurpidi kaldkriipsud
		$data = htmlspecialchars($data);	//teeb htmli tekstiks, nt < läheb &lt
		return $data;
}
	
	
	$mysqli->close();
?>
<html>

<head>
	<title>Login page</title>
	<metadata encoding="UTF8">
</head>
<body bgcolor="#BF9E7C"><br><br>
		<center><h2>Log in</h2></center>
		<center><form action="login.php" method="post"></center>
			<center><input name="email1" type="email" value="<?php echo $email1 ?>" placeholder="E-post"> <?php echo $email1_error; ?></center><br><br>
			<center><input name="password1" type="password" placeholder="Parool"> <?php echo $password1_error; ?> </center><br><br>
			<center><input type="submit" name="login" value="Log in"></center><br>
			<center><a href="http://www.tlu.ee/~earist/">Unustasid parooli?</a></center>
		</form>
	
	<center><h2>Create user</h2></center>
		<form action ="login.php" method="post">
			<center><input type="text" name="eesnimi" value ="<?php echo $eesnimi ?>" placeholder="Eesnimi"><?php echo $eesnimi_error;?></center><br>
			<center><input type="text" name="perekonnanimi" value ="<?php echo $perekonnanimi ?>" placeholder="Perekonnanimi"><?php echo $perekonnanimi_error;?></center><br>
			<center><input name="email2" type="email" placeholder="E-post" value ="<?php echo $email2 ?>"><?php echo $email2_error; ?></center><br>
			<center><input name="password2" type="password" placeholder="Parool"><br><?php echo $password2_error; ?></center><br>
			<center><input name="password3" type="password" placeholder="Korda parooli"><?php echo $password3_error; ?></center><br><br>
			<center>Sugu?<br><input type="radio" name="sugu" value="mees">Mees&nbsp;&nbsp;&nbsp;<input type="radio" name="sugu" value="naine">Naine</center><br><br>
			<center><input type="submit" name="registreeri" value="Registreeri"></center><br>
		</form>
</body>

</html>