<?php
// Ce fichier contient uniquement la connexion à la base de données.
$serveur = "localhost";
$utilisateur = "root";
$mot_de_passe = "";
$base_de_donnees = "gmao_bons_travail";

$conn = mysqli_connect($serveur, $utilisateur, $mot_de_passe, $base_de_donnees);

if (!$conn) {
    die("La connexion à la base de données a échoué : " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");
?>
