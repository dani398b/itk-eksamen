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
			<h3>Transaktioner</h3>
				<?php
					$bekostning = visTrans($_SESSION['sesForbind']);

					echo "Tid for sidste opdatering: <div id='tid'>".date("H:i:s")."</div>";

					echo "<br><table border='1px'>
		        	<tr>
		        		<th>Transaktions ID</th>
		        		<th>Bruger ID</th>
		        		<th>Vare</th>
		        		<th>Antal købt</th>
		        		<th>Pris per styk</th>
		        		<th>Omkostning</th>
		        	</tr>";
		        	foreach($bekostning as $row){
		                echo "<tr>"
	                	."<td>".$row['trans_id']."</td>"
	                	."<td>".$row['bruger_id']."</td>"
	                	."<td>".$row['navn']."</td>"
	                    ."<td>".$row['antal']."</td>"
	                    ."<td>".$row['pris']."</td>"
	                    ."<td>".$row['bekostning']."</td>";
	                    echo "</tr>";
			        }
			    ?>
		</div>
	</center>
</body>
</html>