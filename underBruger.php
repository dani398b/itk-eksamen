<?php
    session_start(); 

    include "forbindTilDatabase.php"; 
    include "brugerTjek.php";  

    if ($adgang){ // Tjekker om adgang er blevet sat til "true" under brugerTjek.php
    	include "oversigt.php";
    } else {
    	include "index.php";
    }
?>