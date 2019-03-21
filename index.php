<?php
	@session_destroy(); // '@' stopper fejlbesked ved eksisterende session.
?>

<!DOCTYPE html>
<html>
<head>
	<title>Handelsprojekt</title>
	<link rel="stylesheet" type="text/css" href="tema.css">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/> 
	<link rel="icon" href="handelIkon.png">
</head>
<body>
	<center>
		<div class="forsideIndtastning">
			<h1>Handelsprojektet 2019</h1>
			<h3>Indtast dine brugeroplysning, for at oprette forbindelse</h3>
			<form action="underBruger.php" method="post">
				<div class="forsideForm">
		    		Brugernavn<br><input type="text" name="tasBrugernavn" required><br>
		   			Adgangskode (min. 8 tegn)<br><input type="password" name="tasAdgangskode" minlength="8" required><br>
		   		</div>
		    <input type="submit" value="Forbind">
		</div>
	</center>
</form>
</body>
</html>