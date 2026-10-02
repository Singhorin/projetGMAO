<?php
require_once "includes/connexion_bdd.php";
session_start();

$erreur = "";
if (isset($_POST['se_connecter'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $mot_de_passe = mysqli_real_escape_string($conn, $_POST['mot_de_passe']);

    // Requête volontairement simple pour rester facile à expliquer.
    $sql = "SELECT * FROM utilisateurs WHERE email='$email' AND mot_de_passe='$mot_de_passe'";
    $resultat = mysqli_query($conn, $sql);

    if (mysqli_num_rows($resultat) == 1) {
        $_SESSION['utilisateur'] = mysqli_fetch_assoc($resultat);
        header("Location: accueil.php");
        exit();
    } else {
        $erreur = "Identifiant ou mot de passe incorrect.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Mini-GMAO</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="page-connexion">
    <section class="boite-connexion">
        <h1>Mini-GMAO</h1>
        <p>Gestion des bons de travail</p>
        <?php if ($erreur != "") { ?><div class="message erreur"><?php echo $erreur; ?></div><?php } ?>
        <form method="post">
            <label>Adresse e-mail</label>
            <input type="email" name="email" required>
            <label>Mot de passe</label>
            <input type="password" name="mot_de_passe" required>
            <button type="submit" name="se_connecter">Se connecter</button>
        </form>
        <small>Utilisez les comptes fournis dans le fichier README.</small>
    </section>
</body>
</html>
