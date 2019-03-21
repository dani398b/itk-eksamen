<?php
	include "brugerProfil.php";

	// Mappe til opbevaring af profilbilleder.
	$target_dir = "indsendt/"; 

	// Total stifinderposition i forhold til indsend.php-filen.
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]); 

	// Startvariabel til brug ved senere tjek.
	$uploadOk = 1; 

	// Finder filtype, eksploderer til stifinderpositions-dele, sætter til lower-case.
	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION)); 

	// Kører tjek om hvorvidt den indsendte fil er et sandt eller falsk billede ud fra dets størrelse.
	if(isset($_POST["submit"])) {
	    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
	    if($check !== false) {
	        echo "Fil er et billede - " . $check["mime"] . ".";
	        $uploadOk = 1;
	    } else {
	        echo "Fil er ikke et billede.";
	        $uploadOk = 0;
	    }
	}
	// Tjekker om filen allerede eksisterer.
	if (file_exists($target_file)) {
	    echo "Filen eksisterer allerede.";
	    $uploadOk = 0;
	}
	// Tjekker filstørrelse
	if ($_FILES["fileToUpload"]["size"] > 1000000) { // højst 1 Mb
	    echo "Fil er for stor.";
	    $uploadOk = 0;
	}
	// Tjekker for jpg, png, jpeg og gif-format.
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
	&& $imageFileType != "gif" ) {
	    echo "Kun JPG, JPEG, PNG & GIF er tilladt.";
	    $uploadOk = 0;
	}
	// Tjekker om sikkerhedsvariabel er lig 1.
	if ($uploadOk == 0) {
	    echo "Fil var ikke indsendt!";
	} else {
		// Navngiver alle indsendte filer til session-brugernavnet, af typen jpg.
		$newfilename = "indsendt/" . $_SESSION['sesNavn'] . '.jpg';

		// Udfører indsendelse.
	    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $newfilename)) {
	        echo "Filen ". basename( $_FILES["fileToUpload"]["name"]). " er blevet indsendt.";
	    } else {
	        echo "Fejl ved indsending.";
	    }
	}
?>