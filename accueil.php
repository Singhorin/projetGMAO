<?php
require_once "includes/fonctions.php";
require_once "includes/connexion_bdd.php";
verifier_connexion();
$titre_page = "Tableau de bord";

// Les compteurs sont simples pour être facilement compris et présentés.
$nb_equipements = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM equipements"));
$nb_di = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM demandes_intervention WHERE statut='En attente'"));
$nb_bt = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM bons_travail WHERE statut!='Clôturé'"));
$nb_pannes = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM equipements WHERE statut='En panne'"));
require "includes/entete.php";
?>
<h1>Tableau de bord</h1>
<p class="sous-titre">Vue d'ensemble de l'activité de maintenance.</p>
<section class="cartes">
    <article class="carte"><span>Équipements</span><strong><?php echo $nb_equipements['total']; ?></strong></article>
    <article class="carte"><span>Demandes en attente</span><strong><?php echo $nb_di['total']; ?></strong></article>
    <article class="carte"><span>BT non clôturés</span><strong><?php echo $nb_bt['total']; ?></strong></article>
    <article class="carte"><span>Équipements en panne</span><strong><?php echo $nb_pannes['total']; ?></strong></article>
</section>
<?php if ($_SESSION['utilisateur']['role'] == 'Responsable') { ?>
<section class="bloc">
    <h2>Actions du responsable</h2>
    <p>Validez les demandes reçues, créez les bons de travail et planifiez les maintenances préventives.</p>
    <a class="bouton" href="demandes.php">Voir les demandes</a>
</section>
<?php } ?>
<?php if ($_SESSION['utilisateur']['role'] == 'Technicien') { ?>
<section class="bloc"><h2>Votre espace</h2><p>Consultez les bons qui vous sont attribués et complétez le rapport à la fin de l'intervention.</p><a class="bouton" href="bons_travail.php">Voir mes BT</a></section>
<?php } ?>
<?php if ($_SESSION['utilisateur']['role'] == 'Opérateur') { ?>
<section class="bloc"><h2>Votre espace</h2><p>Créez une demande d'intervention en cas de panne et suivez son traitement.</p><a class="bouton" href="demandes.php">Créer une demande</a></section>
<?php } ?>
<?php require "includes/pied.php"; ?>
