<?php
require_once "includes/fonctions.php";
require_once "includes/connexion_bdd.php";
verifier_connexion();
$titre_page = "Demandes d'intervention";
$role = $_SESSION['utilisateur']['role'];

if (isset($_POST['creer_di']) && $role == 'Opérateur') {
    $equipement = (int)$_POST['id_equipement'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $urgence = $_POST['urgence'];
    $demandeur = $_SESSION['utilisateur']['id_utilisateur'];
    mysqli_query($conn, "INSERT INTO demandes_intervention (id_equipement, id_demandeur, description, urgence, date_demande) VALUES ($equipement, $demandeur, '$description', '$urgence', NOW())");
    message("Votre demande d'intervention a été envoyée.");
    header("Location: demandes.php"); exit();
}

// Le responsable valide une DI et crée un BT correctif à partir de celle-ci.
if (isset($_POST['valider_di']) && $role == 'Responsable') {
    $id_di = (int)$_POST['id_di'];
    $technicien = (int)$_POST['id_technicien'];
    $di = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM demandes_intervention WHERE id_di=$id_di"));
    $compte = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM bons_travail"));
    $numero = "BT-" . str_pad($compte['total'] + 1, 3, "0", STR_PAD_LEFT);
    $titre = mysqli_real_escape_string($conn, "Intervention corrective - " . $di['urgence']);
    mysqli_query($conn, "INSERT INTO bons_travail (numero_bt, id_equipement, id_di, id_technicien, type_bt, titre, statut, date_creation) VALUES ('$numero', " . $di['id_equipement'] . ", $id_di, $technicien, 'Correctif', '$titre', 'Assigné', NOW())");
    mysqli_query($conn, "UPDATE demandes_intervention SET statut='Validée' WHERE id_di=$id_di");
    mysqli_query($conn, "UPDATE equipements SET statut='En panne' WHERE id_equipement=" . $di['id_equipement']);
    message("DI validée. Le BT $numero a été créé et attribué.");
    header("Location: demandes.php"); exit();
}

$equipements = mysqli_query($conn, "SELECT * FROM equipements ORDER BY nom");
$techniciens = mysqli_query($conn, "SELECT * FROM utilisateurs WHERE role='Technicien'");
if ($role == 'Opérateur') { $demandes = mysqli_query($conn, "SELECT d.*, e.code, e.nom FROM demandes_intervention d JOIN equipements e ON d.id_equipement=e.id_equipement WHERE d.id_demandeur=" . $_SESSION['utilisateur']['id_utilisateur'] . " ORDER BY d.id_di DESC"); }
else { $demandes = mysqli_query($conn, "SELECT d.*, e.code, e.nom, u.nom AS demandeur FROM demandes_intervention d JOIN equipements e ON d.id_equipement=e.id_equipement JOIN utilisateurs u ON d.id_demandeur=u.id_utilisateur ORDER BY d.id_di DESC"); }
require "includes/entete.php";
?>
<h1>Demandes d'intervention</h1>
<?php if ($role == 'Opérateur') { ?>
<section class="formulaire"><h2>Déclarer une panne</h2><form method="post"><label>Équipement concerné</label><select name="id_equipement" required><?php while($e=mysqli_fetch_assoc($equipements)) { ?><option value="<?php echo $e['id_equipement']; ?>"><?php echo texte_securise($e['code'] . ' - ' . $e['nom']); ?></option><?php } ?></select><label>Description du problème</label><textarea name="description" required></textarea><label>Degré d'urgence</label><select name="urgence"><option>Faible</option><option>Moyen</option><option>Critique</option></select><br><br><button name="creer_di">Envoyer la demande</button></form></section>
<?php } ?>
<section class="tableau"><table><tr><th>Date</th><th>Équipement</th><?php if($role=='Responsable') { ?><th>Demandeur</th><?php } ?><th>Description</th><th>Urgence</th><th>Statut</th><?php if($role=='Responsable') { ?><th>Action</th><?php } ?></tr>
<?php while ($d=mysqli_fetch_assoc($demandes)) { ?><tr><td><?php echo date('d/m/Y H:i', strtotime($d['date_demande'])); ?></td><td><?php echo texte_securise($d['code']); ?></td><?php if($role=='Responsable') { ?><td><?php echo texte_securise($d['demandeur']); ?></td><?php } ?><td><?php echo texte_securise($d['description']); ?></td><td class="<?php echo $d['urgence']=='Critique'?'critique':''; ?>"><?php echo $d['urgence']; ?></td><td><span class="statut"><?php echo $d['statut']; ?></span></td><?php if($role=='Responsable') { ?><td><?php if($d['statut']=='En attente') { ?><form method="post"><input type="hidden" name="id_di" value="<?php echo $d['id_di']; ?>"><select name="id_technicien"><?php mysqli_data_seek($techniciens,0); while($t=mysqli_fetch_assoc($techniciens)) { ?><option value="<?php echo $t['id_utilisateur']; ?>"><?php echo texte_securise($t['nom']); ?></option><?php } ?></select><button name="valider_di">Valider et créer BT</button></form><?php } ?></td><?php } ?></tr><?php } ?>
</table></section>
<?php require "includes/pied.php"; ?>
