<?php
	session_start(); 
	include "forbindTilDatabase.php";
	include "brugerTjek.php";
?>

<html>
<head>
	<title>Handelsprojekt</title>
	<link rel="stylesheet" type="text/css" href="tema.css">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/> 
</head>
<body>
	<center>
		<div class="underBruger">
			<h1>Handelsprojektet 2019</h1>
			<div id="navigation"><a href="underBruger.php">Oversigt</a><a href="transaktioner.php">Transaktioner</a><a href="brugerProfil.php">Profil</a><a href="index.php">Log ud</a></div>
			<h3>Profil</h3>
				<?php
					$billede = "indsendt/" . $_SESSION['sesNavn'] . ".jpg";
					echo "<img src='".$billede."' height='300px' width='300px'>";
			    ?>
			<table border='1px'>
			    <form action="indsend.php" method="post" enctype="multipart/form-data">
				    <tr>
					    <th>Vælg profilbillede</th>
					    <th><input type="file" name="fileToUpload" id="fileToUpload"></th>
					    <th><input type="submit" value="Indsend" name="submit"></th>
				    </tr>
				</form>
			</table>
		</div>
	</center>
</body>
</html>