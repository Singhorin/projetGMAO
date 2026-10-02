<?php
require_once "includes/fonctions.php";
require_once "includes/connexion_bdd.php";
verifier_connexion();
$titre_page = "État des équipements";

$equipements = mysqli_query($conn, "SELECT * FROM equipements ORDER BY code");
require "includes/entete.php";
?>
<h1>État des équipements</h1>
<p class="sous-titre">Consultez le statut de chaque équipement (en marche, en panne, en maintenance).</p>
<section class="tableau"><table><tr><th>Code</th><th>Nom</th><th>Catégorie</th><th>Localisation</th><th>Statut</th></tr>
<?php while ($e = mysqli_fetch_assoc($equipements)) { ?><tr><td><?php echo texte_securise($e['code']); ?></td><td><?php echo texte_securise($e['nom']); ?></td><td><?php echo texte_securise($e['categorie']); ?></td><td><?php echo texte_securise($e['localisation']); ?></td><td><span class="statut"><?php echo $e['statut']; ?></span></td></tr><?php } ?>
</table></section>
<?php require "includes/pied.php"; ?>
