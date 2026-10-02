<?php
require_once "includes/fonctions.php";
require_once "includes/connexion_bdd.php";
verifier_connexion();
$titre_page = "Bons de travail";
$role = $_SESSION['utilisateur']['role'];

// Le responsable peut affecter un BT préventif après sa génération.
if (isset($_POST['assigner_bt']) && $role == 'Responsable') {
    $id = (int)$_POST['id_bt'];
    $technicien = (int)$_POST['id_technicien'];
    mysqli_query($conn, "UPDATE bons_travail SET id_technicien=$technicien, statut='Assigné' WHERE id_bt=$id AND statut='Créé'");
    message("Le bon de travail a été attribué au technicien."); header("Location: bons_travail.php"); exit();
}

if (isset($_GET['demarrer']) && $role == 'Technicien') {
    $id = (int)$_GET['demarrer'];
    $tech = $_SESSION['utilisateur']['id_utilisateur'];
    mysqli_query($conn, "UPDATE bons_travail SET statut='En cours' WHERE id_bt=$id AND id_technicien=$tech AND statut='Assigné'");
    message("Le bon de travail est maintenant en cours."); header("Location: bons_travail.php"); exit();
}

if (isset($_POST['cloturer']) && $role == 'Technicien') {
    $id = (int)$_POST['id_bt'];
    $tech = $_SESSION['utilisateur']['id_utilisateur'];
    $temps = (float)$_POST['temps_passe'];
    $cause = mysqli_real_escape_string($conn, $_POST['cause_panne']);
    $taches = mysqli_real_escape_string($conn, $_POST['taches_realisees']);
    // Le rapport est demandé avant de passer le BT à l'état clôturé.
    mysqli_query($conn, "UPDATE bons_travail SET statut='Clôturé', temps_passe=$temps, cause_panne='$cause', taches_realisees='$taches' WHERE id_bt=$id AND id_technicien=$tech");
    $bt = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id_equipement FROM bons_travail WHERE id_bt=$id"));
    mysqli_query($conn, "UPDATE equipements SET statut='En marche' WHERE id_equipement=" . $bt['id_equipement']);
    message("BT clôturé et équipement remis en marche."); header("Location: bons_travail.php"); exit();
}

if ($role == 'Technicien') {
    $id_tech = $_SESSION['utilisateur']['id_utilisateur'];
    $bons = mysqli_query($conn, "SELECT b.*, e.code, e.nom, u.nom AS technicien FROM bons_travail b JOIN equipements e ON b.id_equipement=e.id_equipement LEFT JOIN utilisateurs u ON b.id_technicien=u.id_utilisateur WHERE b.id_technicien=$id_tech ORDER BY b.id_bt DESC");
} else if ($role == 'Responsable') {
    $bons = mysqli_query($conn, "SELECT b.*, e.code, e.nom, u.nom AS technicien FROM bons_travail b JOIN equipements e ON b.id_equipement=e.id_equipement LEFT JOIN utilisateurs u ON b.id_technicien=u.id_utilisateur ORDER BY b.id_bt DESC");
} else { header("Location: accueil.php"); exit(); }
require "includes/entete.php";
?>
<h1><?php echo $role == 'Technicien' ? 'Mes bons de travail' : 'Bons de travail'; ?></h1>
<section class="tableau"><table><tr><th>N° BT</th><th>Équipement</th><th>Type</th><th>Titre</th><th>Technicien</th><th>Statut</th><th>Rapport / action</th></tr>
<?php while ($b = mysqli_fetch_assoc($bons)) { ?><tr><td><?php echo texte_securise($b['numero_bt']); ?></td><td><?php echo texte_securise($b['code'].' - '.$b['nom']); ?></td><td><?php echo $b['type_bt']; ?></td><td><?php echo texte_securise($b['titre']); ?></td><td><?php echo texte_securise($b['technicien']); ?></td><td><span class="statut"><?php echo $b['statut']; ?></span></td><td>
<?php if ($role == 'Responsable' && $b['statut'] == 'Créé') { $techniciens = mysqli_query($conn, "SELECT * FROM utilisateurs WHERE role='Technicien'"); ?><form method="post"><input type="hidden" name="id_bt" value="<?php echo $b['id_bt']; ?>"><select name="id_technicien"><?php while($t=mysqli_fetch_assoc($techniciens)) { ?><option value="<?php echo $t['id_utilisateur']; ?>"><?php echo texte_securise($t['nom']); ?></option><?php } ?></select><button name="assigner_bt">Attribuer</button></form><?php } ?>
<?php if ($role == 'Technicien' && $b['statut'] == 'Assigné') { ?><a class="bouton" data-confirmation="Démarrer cette intervention ?" href="bons_travail.php?demarrer=<?php echo $b['id_bt']; ?>">Démarrer</a><?php } ?>
<?php if ($role == 'Technicien' && $b['statut'] == 'En cours') { ?><details><summary>Clôturer avec rapport</summary><form method="post"><input type="hidden" name="id_bt" value="<?php echo $b['id_bt']; ?>"><label>Temps passé (heures)</label><input type="number" name="temps_passe" step="0.5" min="0.5" required><label>Cause de la panne<?php echo $b['type_bt']=='Préventif'?' (si nécessaire)':''; ?></label><textarea name="cause_panne" required></textarea><label>Tâches réalisées</label><textarea name="taches_realisees" required></textarea><br><button name="cloturer">Clôturer le BT</button></form></details><?php } ?>
<?php if ($b['statut'] == 'Clôturé') { ?><?php echo $b['temps_passe']; ?> h<br><?php echo texte_securise($b['taches_realisees']); ?><?php } ?>
</td></tr><?php } ?>
</table></section>
<?php require "includes/pied.php"; ?>
