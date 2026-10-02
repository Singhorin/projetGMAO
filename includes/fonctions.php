<?php
// Fonctions simples utilisées dans plusieurs pages.
session_start();

function est_connecte() {
    return isset($_SESSION['utilisateur']);
}

function verifier_connexion() {
    if (!est_connecte()) {
        header("Location: connexion.php");
        exit();
    }
}

function verifier_role($role) {
    verifier_connexion();
    if ($_SESSION['utilisateur']['role'] != $role) {
        header("Location: accueil.php");
        exit();
    }
}

function texte_securise($texte) {
    return htmlspecialchars($texte, ENT_QUOTES, 'UTF-8');
}

function message($texte, $type = "succes") {
    $_SESSION['message'] = $texte;
    $_SESSION['type_message'] = $type;
}
?>
