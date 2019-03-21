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
			<h3>Oversigt over goder</h3>

			<?php
				$_SESSION['saldo'] = 10000;
				$i = 1;

		        $bekostning = brugerGodsOversigt($_SESSION['sesForbind'], $_SESSION['sesBruger_id']);

		        echo "Tid for sidste opdatering: <div id='tid'>".date("H:i:s")."</div>";

		        echo "<br><table border='1px'>
		        	<tr>
		        		<th>Godsnavn</th>
		        		<th>Vægt ejet</th>
		        		<th>Aktuel pris</th>
		        		<th>Værdi ejet</th>
		        		<th>Handlinger</th>
		        		
		        	</tr>
		        		";
		        foreach($bekostning as $row){
		            if($row['antal']>=0){
		                echo "<tr>"
	                	."<td>".$row['gods_navn']."</td>"
	                    ."<td>".$row['antal']." g</td>"
	                    ."<td>".floatval($row['gods_pris'])." kr/g</td>"
	                    ."<td>".floatval($row['antal']*$row['gods_pris'])." kr</td>"
	                    ."<td>
	                    <form action='kob.php' method='get'>
					    	<input type='number' name='handelAntal' min='1' max='1000'>
					    	<input type='hidden' name='gods_id' value='".$row['gods_id']."'>
					    	<input type='hidden' name='gods_pris' value='".$row['gods_pris']."'>
					    	<button>Køb</button></a> 
						</form>";

						if($row['antal']>0){

						echo "<form action='saelg.php' method='get'>
					    	<input type='number' name='handelAntal' min='1' max='1000'>
					    	<input type='hidden' name='gods_id' value='".$row['gods_id']."'>
					    	<input type='hidden' name='gods_pris' value='".$row['gods_pris']."'>
					    	<button>Sælg</button></a> 
						</form>";

						}

	                    echo "</tr>";
		            }

		            $_SESSION['saldo'] = $_SESSION['saldo'] - $row['bekostning'];
		        }

		        echo "</table>";
		        	
		        echo "<br><b>Saldo:</b> ".$_SESSION['saldo']."<b> DKK</b>";
		    ?>
		</div>
	</center>
</body>
</html>