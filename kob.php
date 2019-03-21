<html>
<head>
	<title>Handelsprojekt</title>
	<link rel="stylesheet" type="text/css" href ="tema.css">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/> 
</head>
<body>
	<center>
		<div class="underBruger">
			<h1>Handelsprojektet 2019</h1>
			<div id="navigation"><a href="underBruger.php">Oversigt</a><a href="transaktioner.php">Transaktioner</a><a href="brugerProfil.php">Profil</a><a href="index.php">Log ud</a></div>
			<h3>Overførsel</h3>
			<?php
			    session_start();

			    include "forbindTilDatabase.php";
			    $forbind = start();
			    echo "<center>";

			    if ($GLOBALS['fejlfind']){
			    	echo "<br>#sesBrugerId#" . $_SESSION['sesBruger_id'] . "#";
				}

			    kobGods($forbind, $_SESSION['sesBruger_id'], $_GET['gods_id'], $_GET['gods_pris'], $_GET['handelAntal']);    

			    echo "<br>";
			    visGodsBil($_GET['gods_id']);
			?>
		</center>
	</body>
</html>