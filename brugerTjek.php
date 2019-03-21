<?php 
    echo "<head><link rel='icon' href='handelIkon.png'></head>";
    // Her beskriver 'tas' i 'tasBrugernavn' og 'tasAdgangskode', at det er den indtastede værdi.
    $navn = isset($_POST['tasBrugernavn']) ? $_POST['tasBrugernavn'] : $_SESSION['sesNavn'];
    $adgangskode = isset($_POST['tasAdgangskode']) ? $_POST['tasAdgangskode'] : $_SESSION['sesAdgangskode'];

    $forbind = start(); 
    
    $adgang = false; 
  
    if (brugerTjek($forbind, $navn, $adgangskode) == 1) {
        echo "<br>Forkert adgangskode ".$navn;
        $adgang = false; 
    } if (brugerTjek($forbind, $navn, $adgangskode) == 2) {
        echo "<br>Du er nu forbundet, <b>" . $navn . "</b>, dato: <b>" . date("D d, F, Y")."</b>";
        $adgang = true;
    } if (brugerTjek($forbind, $navn, $adgangskode) == 0) {
        echo "<br>Bruger oprettet, du er nu forbundet for første gang ".$navn;
        $adgang = skabBruger($forbind, $navn, $adgangskode);
    }

    // Her beskriver 'ses' i eksempelvis 'sesNavn', at denne variabel laves med hensigt på sessionbrug.
    if ($adgang) {
        $_SESSION['sesNavn'] = $navn;
        $_SESSION['sesAdgangskode'] = $adgangskode; 
        $_SESSION['sesForbind'] = $forbind;
        $_SESSION['sesBruger_id'] = returnerBrugerId($forbind, $navn);
    }
?>