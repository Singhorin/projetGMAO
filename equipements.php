<?php
require_once "includes/fonctions.php";
require_once "includes/connexion_bdd.php";
verifier_role("Responsable");
$titre_page = "Équipements";

if (isset($_POST['ajouter'])) {
    $code = mysqli_real_escape_string($conn, $_POST['code']);
    $nom = mysqli_real_escape_string($conn, $_POST['nom']);
    $categorie = mysqli_real_escape_string($conn, $_POST['categorie']);
    $localisation = mysqli_real_escape_string($conn, $_POST['localisation']);
    $date = $_POST['date_mise_service'];
    $statut = $_POST['statut'];
    $sql = "INSERT INTO equipements (code, nom, categorie, localisation, date_mise_service, statut) VALUES ('$code', '$nom', '$categorie', '$localisation', '$date', '$statut')";
    if (mysqli_query($conn, $sql)) { message("Équipement ajouté."); } else { message("Le code équipement existe déjà.", "erreur"); }
    header("Location: equipements.php"); exit();
}

if (isset($_GET['supprimer'])) {
    $id = (int)$_GET['supprimer'];
    // La suppression peut échouer si l'équipement est lié à des demandes, des
    // bons de travail ou des planifications (contrainte de clé étrangère).
    // mysqli étant configuré en mode "exception", on doit utiliser try/catch.
    try {
        mysqli_query($conn, "DELETE FROM equipements WHERE id_equipement=$id");
        message("Équipement supprimé.");
    } catch (mysqli_sql_exception $e) {
        message("Impossible de supprimer : cet équipement est lié à des demandes, bons de travail ou planifications existants.", "erreur");
    }
    header("Location: equipements.php"); exit();
}

$equipements = mysqli_query($conn, "SELECT * FROM equipements ORDER BY code");
require "includes/entete.php";
?>
<h1>Équipements</h1>
<section class="formulaire">
    <h2>Ajouter un équipement</h2>
    <form method="post">
        <div class="ligne-formulaire"><div><label>Code unique</label><input name="code" required></div><div><label>Nom</label><input name="nom" required></div></div>
        <div class="ligne-formulaire"><div><label>Catégorie</label><input name="categorie" required></div><div><label>Localisation</label><input name="localisation" required></div></div>
        <div class="ligne-formulaire"><div><label>Date de mise en service</label><input type="date" name="date_mise_service"></div><div><label>Statut</label><select name="statut"><option>En marche</option><option>En panne</option><option>En maintenance</option></select></div></div>
        <br><button name="ajouter">Ajouter</button>
    </form>
</section>
<section class="tableau"><table><tr><th>Code</th><th>Nom</th><th>Catégorie</th><th>Localisation</th><th>Statut</th><th>Action</th></tr>
<?php while ($e = mysqli_fetch_assoc($equipements)) { ?><tr><td><?php echo texte_securise($e['code']); ?></td><td><?php echo texte_securise($e['nom']); ?></td><td><?php echo texte_securise($e['categorie']); ?></td><td><?php echo texte_securise($e['localisation']); ?></td><td><span class="statut"><?php echo $e['statut']; ?></span></td><td><a data-confirmation="Supprimer cet équipement ?" href="equipements.php?supprimer=<?php echo $e['id_equipement']; ?>">Supprimer</a></td></tr><?php } ?>
</table></section>
<?php require "includes/pied.php"; ?>
