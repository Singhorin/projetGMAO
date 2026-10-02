<?php
session_start();
if (isset($_SESSION['utilisateur'])) {
    header("Location: accueil.php");
} else {
    header("Location: connexion.php");
}
exit();
?>
