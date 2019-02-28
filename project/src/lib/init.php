<?php

$servername = "mysql.info.unicaen.fr";
$username = "21600538";
$password = "Iegh3xeePaeJeuQu";

function htmlesc($str) {
	return htmlspecialchars($str,
		/* on échappe guillemets _et_ apostrophes : */
		ENT_QUOTES
		/* les séquences UTF-8 invalides sont
		* remplacées par le caractère �
		* au lieu de renvoyer la chaîne vide…) */
		| ENT_SUBSTITUTE
		/* on utilise les entités HTML5 (en particulier &apos;) */
		| ENT_HTML5,
		'UTF-8');
}

$pdo = new PDO("mysql:host=$servername;dbname=21600538_dev", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


?>
