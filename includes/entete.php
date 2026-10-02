<?php
if (!isset($titre_page)) { $titre_page = "Mini-GMAO"; }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titre_page; ?> - Mini-GMAO</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <header>
        <div class="titre-site">Mini-GMAO</div>
        <div class="utilisateur-connecte">
            <?php echo texte_securise($_SESSION['utilisateur']['nom']); ?> - <?php echo texte_securise($_SESSION['utilisateur']['role']); ?>
            <a href="deconnexion.php">Déconnexion</a>
        </div>
    </header>
    <nav>
        <a href="accueil.php">Tableau de bord</a>
        <?php if ($_SESSION['utilisateur']['role'] == 'Responsable') { ?>
            <a href="equipements.php">Équipements</a>
            <a href="demandes.php">Demandes</a>
            <a href="bons_travail.php">Bons de travail</a>
            <a href="planifications.php">Préventif</a>
        <?php } ?>
        <?php if ($_SESSION['utilisateur']['role'] == 'Opérateur') { ?>
            <a href="demandes.php">Mes demandes</a>
            <a href="etat_equipements.php">Équipements</a>
        <?php } ?>
        <?php if ($_SESSION['utilisateur']['role'] == 'Technicien') { ?>
            <a href="bons_travail.php">Mes bons de travail</a>
        <?php } ?>
    </nav>
    <main>
        <?php if (isset($_SESSION['message'])) { ?>
            <div class="message <?php echo $_SESSION['type_message']; ?>"><?php echo texte_securise($_SESSION['message']); ?></div>
            <?php unset($_SESSION['message'], $_SESSION['type_message']); ?>
        <?php } ?>
