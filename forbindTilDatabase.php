<?php
    // Fejlfinding.
	$GLOBALS['fejlfind'] = false;

    // Anviser at filen er inkluderet ved tilslået fejlfinding.
	if ($GLOBALS['fejlfind'] == true) {
		echo "<br>#Funktionsside inkluderet#";
	}

    // Skaber databade, tabeller og indsætter startgoder i goder-tabellen. Returnerer forbindelses-objektet.
	function start(){
		$forbind = new mysqli("localhost", "root","");

		skabDatabase($forbind);
		skabTabelBruger($forbind);
		skabTabelGoder($forbind);
		skabTabelTrans($forbind);
		indsaetStartGoder($forbind);

		return $forbind;
	}

    // Kaldes fra brugerTjek, og returnerer positiv værdi hvis den givne adgangskode passer til det givne brugernavn.
	function adgangskodeTjek($forbind, $brugernavn, $adgangskode){
		$adgangskodeKorrekt = FALSE;

		$sql = "SELECT brugere.id FROM goder.brugere WHERE adgangskode='".$adgangskode."' AND navn='".$brugernavn."' LIMIT 1";

        $resultat = $forbind->query($sql) or die($forbind->error);

        $row = $resultat->fetch_assoc();

        if ($GLOBALS['fejlfind']) {
            echo "<br>#Brugerid til adgangskodetjek: ".$row['id']."#";
            echo "<br>#SQL til adgangskode og brugertjek:" . $sql . " " . $forbind->error."#";
        }

        if ($row != null){
        	$adgangskodeKorrekt = TRUE;
        } 

        return $adgangskodeKorrekt;
	}

    // Returnerer et godsnavn ved et givent gods_id.
    function returnerGodsNavn($forbind, $id){
        $sql = "SELECT goder.navn FROM goder.goder WHERE id='".$id."' LIMIT 1";

        $resultat = $forbind->query($sql);

        $row = $resultat->fetch_assoc();

        return @$row[navn];
    }

    // Returnerer en værdi afhængende af om en given bruger eksisterer ved en given adgangskode.
	function brugerTjek($forbind, $brugernavn, $adgangskode){
		$brugerOgEllerAdgang = 0;

		$sql = "SELECT navn FROM goder.brugere WHERE navn='$brugernavn' LIMIT 1";

        $resultat = $forbind->query($sql);

        $row = $resultat->fetch_assoc();

        if($GLOBALS['fejlfind']){
            echo "<br>#Brugertjek SQL: " . $sql . " : " . $forbind->error."#";
        }

        if ($row != null) {
        	if (adgangskodeTjek($forbind, $brugernavn, $adgangskode)) {
        		$brugerOgEllerAdgang = 2;
        	} else {
        		$brugerOgEllerAdgang = 1;
        	}
        }

        return $brugerOgEllerAdgang;
	}

    // Skaber databasen; her vil alle tabeller blive oprettet i.
	function skabDatabase($forbind){
		$sql = "CREATE DATABASE goder";
        $databaseSkabt = $forbind->query($sql);

        if ($GLOBALS['fejlfind'] == true){
            if ($databaseSkabt) {
                echo "<br>#Database, goder, oprettet#";
            } else {
                echo "<br>#Database, goder, fejl: " . $forbind->error."#";
            }
        }
	}

    // Skaber bruger ved givent navn og adgangskode.
	function skabBruger($forbind, $brugernavn, $adgangskode){
		$startSaldo = 6000;

		$sql =  "INSERT INTO goder.brugere (navn, adgangskode, saldo) VALUES ('$brugernavn', '$adgangskode', '$startSaldo')";

        $brugerSkabt = $forbind->query($sql);

        if($GLOBALS['fejlfind']){
            if ($brugerSkabt) {
                echo "<br>#Bruger oprettet#";
            } else {
                echo "<br>#Brugeroprettelse, fejl: " . $sql . "<br>" . $forbind->error;
            } 
        }
        return $brugerSkabt;
	}

    // Skaber brugertabellen.
	function skabTabelBruger($forbind){
		$sql = "CREATE TABLE goder.brugere (
			id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
			navn VARCHAR(30) NOT NULL, 
			adgangskode VARCHAR(30) NOT NULL, 
			saldo DECIMAL(15,5))";

        $tabelSkabt = $forbind->query($sql);

        if($GLOBALS['fejlfind']){
            if ($tabelSkabt) {
                echo "<br>#Tabel, bruger, skabt#";
            } else {
                echo "<br>#Tabel, bruger, fejl: ".$forbind->error."#";
            }
        }
	}

    // Skaber godstabellen.
	function skabTabelGoder($forbind){
		$sql = "CREATE TABLE goder.goder (
			id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
			navn VARCHAR(30) NOT NULL, 
			pris DECIMAL(15,5) NOT NULL)";

        $tabelSkabt = $forbind->query($sql);

        if($GLOBALS['fejlfind']){
            if ($tabelSkabt) {
                echo "<br>#Tabel, goder, skabt#";
            } else {
                echo "<br>#Tabel, goder, fejl: " . $forbind->error."#";
            }
        }
	}

    // Modtager brugernavn og returnerer bruger_id til det givne brugernavn.
    function returnerBrugerId($forbind, $brugernavn){
        $sql = "SELECT id FROM goder.brugere WHERE navn='".$brugernavn."' LIMIT 1";

        $resultat = $forbind->query($sql);

        $row = $resultat->fetch_assoc();

        if($GLOBALS['fejlfind']){
            echo "<br>#Returnerbruger ved brugeride:" . @$row[id] . " SQL " . $sql . " : " . $forbind->error."#";
        }

        return @$row[id];
    }

    // Skaber transaktionstabellen.
	function skabTabelTrans($forbind){
		$sql = "CREATE TABLE goder.transaktioner (
			id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
			bruger_id INT(6) UNSIGNED, 
			gods_id INT(6) UNSIGNED, 
			antal INT(10), 
			bekostning DECIMAL(15,5) NOT NULL, 
            FOREIGN KEY (bruger_id) REFERENCES goder.brugere(id), 
            FOREIGN KEY (gods_id) REFERENCES goder.goder(id))";
        $tabelSkabt = $forbind->query($sql);

        if($GLOBALS['fejlfind']){
            if ($tabelSkabt) {
                echo "<br>#Tabel, transaktioner, skabt#";
            } else {
                echo "<br>#Tabel, transaktioner, fejl: " . $forbind->error."#";
            }
        }
	}

    // Fremtidsfunktion til indsættelse af nyt gods.
	function indsaetGoder($forbind, $godsNavn, $godsPris){
		$sql = "INSERT INTO goder.goder ('id', 'navn', 'pris') 
		VALUES ('', '$godsNavn', '$godsPris')";
	}

    // Køber en given mængde af et givent gods fra en bruger ved en given mængdepris.
	function kobGods($forbind, $brugernavn, $godsId, $godsPris, $godsVaegt) {
		$omkostning = $godsPris * $godsVaegt;

        // Har spilleren flere eller lig den mængde penge som det vil kræve at udføre købet.
        if ($_SESSION['saldo'] >= $omkostning){
            // Prisregulering
            $nedtal = $godsVaegt;
            $prisOmkost = $godsPris;
            $totalOmkost = 0;

            // Beregner total bekostning ved prisregulering.
            while($nedtal>0){
                $prisOmkost = $prisOmkost + 0.01;                
                $totalOmkost = $totalOmkost + $prisOmkost;
                $nedtal--;
            }

            $sql ="INSERT INTO goder.transaktioner (bruger_id, gods_id, antal, bekostning) 
            VALUES ('$brugernavn' , '$godsId', '$godsVaegt', '$totalOmkost');";
            $kobSkabt = $forbind->query($sql);

            $pris = floatval($_GET['gods_pris']);
            $navn = returnerGodsNavn($forbind, $_GET['gods_id']);

            $sql2 = "UPDATE goder.goder SET pris = $prisOmkost WHERE id = $godsId";
            $prisReguleret = $forbind->query($sql2);

            // Brugerbesked
            echo "Du har købt ".$_GET['handelAntal']." af ".$navn." for ".$pris." kr per styk ved startpris, til en stigning på 0.01 kr per styk, til en samlet pris af ".$totalOmkost." kr<br>"; 

            if($GLOBALS['fejlfind']){
                echo "<br>#Køb SQL: ".$sql."#";
                echo "<br>#Prisregulering, køb, SQL: ".$sql2."#";
                if ($kobSkabt) {
                    echo "<br>#Køb skabt ved godsId".$godsId."#";
                } else {
                    echo "<br>#Køb, fejl: " . $forbind->error."#";
                }
            }          
        } else {
            echo "Ikke nok penge. Varenmængden koster: ".($godsPris*$godsVaegt)." kr. Du har: ".$_SESSION['saldo']." kr";
        }
	}

    // Sælger en given mængde af et givents gods fra en given bruger til en given mængdepris.
	function saelgGods($forbind, $brugernavn, $godsId, $godsPris, $godsVaegt) {
        $sql = "SELECT SUM(antal) AS antal FROM goder.transaktioner WHERE bruger_id = $brugernavn AND gods_id = $godsId";

        $resultat = $forbind->query($sql);
        
        $row = $resultat->fetch_assoc();

        // Tjekker om brugeren ejer flere eller lig den mængde som de prøver at sælge.
        if ($godsVaegt <= @$row[antal]){
            // Prisregulering
            $nedtal = $godsVaegt;
            $prisOmkost = $godsPris;
            $totalOmkost = 0;

            // Beregner total bekostning ved prisregulering.
            while($nedtal>0){
                if ($GLOBALS['fejlfind']){
                    echo "######################<br>";
                    echo "#prisOmkost".$prisOmkost."<br>";
                    echo "#totalOmkost".$totalOmkost."<br>";
                    echo "#totalOmkost = ".$totalOmkost." + ".$prisOmkost;
                }

                $totalOmkost = $totalOmkost + $prisOmkost;
                $prisOmkost = $prisOmkost - 0.01;
                $nedtal--;
                if ($GLOBALS['fejlfind']){
                    echo " := ".$totalOmkost."<br>";
                    echo "#prisOmkost er nu ".$prisOmkost."<br>";
                    echo "#totalOmkost er nu ".$totalOmkost."<br>"; 
                }
            }

            $sql2 = "UPDATE goder.goder SET pris = $prisOmkost WHERE id = $godsId";
            $prisReguleret = $forbind->query($sql2);

            // Salg foretages
            $sql ="INSERT INTO goder.transaktioner (bruger_id, gods_id, antal, bekostning) 
            VALUES ('$brugernavn', '$godsId', '-$godsVaegt', '-$totalOmkost');";
            $salgSkabt = $forbind->query($sql);

            $pris = floatval($godsPris); // Fjerner 0'er.
            $navn = returnerGodsNavn($forbind, $_GET['gods_id']);

            echo "Du har solgt ".$_GET['handelAntal']." af ".$navn." for ".$pris." kr per styk i startpris, ved et fald på 0.01 kr per styk, til en samlet fortjeneste af ".floatval($totalOmkost)." kr<br>";

            if($GLOBALS['fejlfind']){
                echo "<br>#Salg SQL: ".$sql."#";
                echo "<br>#Prisregulering, salg, SQL: ".$sql2."#";
                if ($salgSkabt) {
                    echo "<br>#Salg skabt ved godsId".$godsId."#";
                } else {
                    echo "<br>#Salg, fejl: " . $forbind->error."#";
                }
            } 
        } else {
            echo "Fejl ved køb. Du ejer ".@$row[antal]." g af ".returnerGodsNavn($forbind, $godsId).", men du prøver at sælge ".$godsVaegt." g af det.";
        }
	}

    // Indsætter startgoder i tabellen over goder. Disse er hardcoded i navn og id, men deres pris varierer som følge af markedets aktivitet.
	function indsaetStartGoder($forbind){
		$sql = "INSERT INTO goder.goder (`id`, `navn`, `pris`) VALUES 
			(1, 'Guld', '258.02000'), 
			(2, 'Soelv', '2.70000'), 
			(3, 'Platin', '121.00000'),
			(4, 'Kviksoelv', '15.95000'),
			(5, 'Tobak', '0.14111'),
			(6, 'Sukker', '0.00849'),
            (7, 'Hvedemel', '0.00350'),
            (8, 'Gule ærter', '0.03344'),
            (9, 'Safran', '120.00000'),
			(10, 'Rhodium', '509.52193');";

        $tabelSkabt = $forbind->query($sql);

        if($GLOBALS['fejlfind']){
            if ($tabelSkabt) {
                echo "<br>#Startgoder, indsat#";
            } else {
                echo "<br>#Startgoder, fejl: " . $forbind->error."#";
            }
        } 
	}

	function brugerGodsOversigt($forbind, $brugernavn) {
		$samletTran = array();

        $sql = "SELECT trans_id, gods_id, gods_navn, gods_pris, 
			SUM(antal) AS 'antal', 
			SUM(bekostning) AS 'bekostning' FROM (SELECT
					0 AS 'trans_id',
        			id AS 'gods_id',
        			goder.navn AS 'gods_navn',
        			pris AS 'gods_pris',
        			0 AS 'antal',
        			0 AS 'bekostning'
        			FROM goder.goder
					   UNION
        		SELECT
                	transaktioner.id AS 'trans_id', 
                	transaktioner.gods_id, 
					goder.navn AS 'gods_navn', 
                	goder.pris AS 'gods_pris', 
                	transaktioner.antal,
                	transaktioner.bekostning
        		FROM goder.transaktioner, goder.goder
        		WHERE
       				transaktioner.gods_id = goder.id AND transaktioner.bruger_id =".$brugernavn." 
       		) t GROUP BY gods_id";

        $resultat = $forbind->query($sql);
        
        $i = 0;
        if ($resultat->num_rows > 0) {
            while($row = $resultat->fetch_assoc()) {
                $samletTran[$i] = $row;
                $i++;
            }
        }
        
        if($GLOBALS['fejlfind']){            
            foreach($samletTran as $tranRow){
                echo "<br>#Række id:".$tranRow['trans_id'];                    
            }
        }
        return $samletTran;
	}

    function visTrans($forbind){

        $sql = "SELECT goder.transaktioner.id as trans_id, bruger_id, navn, antal, bekostning, pris FROM goder.transaktioner, goder.goder WHERE goder.goder.id = goder.transaktioner.gods_id GROUP BY goder.transaktioner.id";
        $resultat = $forbind->query($sql);

        $i = 0;
        if ($resultat->num_rows > 0) {
            while($row = $resultat->fetch_assoc()) {
                $samletTran[$i] = $row;
                $i++;
            }
        }
        
        if($GLOBALS['fejlfind']){            
            foreach($samletTran as $tranRow){
                echo "<br>#Række id:".$tranRow['trans_id'];                    
            }
        }

        return $samletTran;
    }

    function visGodsBil($gods_id){
        $billede = "godsBil/".$gods_id.".jpg";
        echo "<img src='".$billede."'>";
    }
?>