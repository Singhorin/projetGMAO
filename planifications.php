<?php
require_once "includes/fonctions.php";
require_once "includes/connexion_bdd.php";
verifier_role("Responsable");
$titre_page = "Maintenance préventive";

if (isset($_POST['ajouter_plan'])) {
    $equipement=(int)$_POST['id_equipement']; $titre=mysqli_real_escape_string($conn,$_POST['titre']); $periodicite=mysqli_real_escape_string($conn,$_POST['periodicite']); $date=$_POST['prochaine_date'];
    mysqli_query($conn,"INSERT INTO planifications (id_equipement,titre,periodicite,prochaine_date) VALUES ($equipement,'$titre','$periodicite','$date')");
    message("Planification ajoutée."); header("Location: planifications.php"); exit();
}

if (isset($_GET['generer'])) {
    $id=(int)$_GET['generer'];
    $plan=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM planifications WHERE id_planification=$id"));
    $compte=mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS total FROM bons_travail"));
    $numero="BT-".str_pad($compte['total']+1,3,"0",STR_PAD_LEFT);
    mysqli_query($conn,"INSERT INTO bons_travail (numero_bt,id_equipement,type_bt,titre,statut,date_creation) VALUES ('$numero',".$plan['id_equipement'].",'Préventif','".mysqli_real_escape_string($conn,$plan['titre'])."','Créé',NOW())");
    mysqli_query($conn,"UPDATE planifications SET statut='BT généré' WHERE id_planification=$id");
    message("Le BT préventif $numero a été généré."); header("Location: planifications.php"); exit();
}

$equipements=mysqli_query($conn,"SELECT * FROM equipements ORDER BY code");
$plans=mysqli_query($conn,"SELECT p.*,e.code,e.nom FROM planifications p JOIN equipements e ON p.id_equipement=e.id_equipement ORDER BY p.prochaine_date");
require "includes/entete.php";
?>
<h1>Planification préventive</h1>
<p class="sous-titre">Créez une planification puis générez le BT à la date prévue.</p>
<section class="formulaire"><h2>Nouvelle planification</h2><form method="post"><div class="ligne-formulaire"><div><label>Équipement</label><select name="id_equipement"><?php while($e=mysqli_fetch_assoc($equipements)){ ?><option value="<?php echo $e['id_equipement']; ?>"><?php echo texte_securise($e['code'].' - '.$e['nom']); ?></option><?php } ?></select></div><div><label>Titre</label><input name="titre" required></div></div><div class="ligne-formulaire"><div><label>Périodicité</label><input name="periodicite" placeholder="Ex. Tous les mois" required></div><div><label>Prochaine date</label><input type="date" name="prochaine_date" required></div></div><br><button name="ajouter_plan">Enregistrer</button></form></section>
<section class="tableau"><table><tr><th>Équipement</th><th>Intervention</th><th>Périodicité</th><th>Échéance</th><th>Statut</th><th>Action</th></tr><?php while($p=mysqli_fetch_assoc($plans)){ ?><tr><td><?php echo texte_securise($p['code'].' - '.$p['nom']); ?></td><td><?php echo texte_securise($p['titre']); ?></td><td><?php echo texte_securise($p['periodicite']); ?></td><td><?php echo date('d/m/Y',strtotime($p['prochaine_date'])); ?></td><td><span class="statut"><?php echo $p['statut']; ?></span></td><td><?php if($p['statut']=='Planifié'){ ?><a class="bouton" data-confirmation="Générer le BT préventif ?" href="planifications.php?generer=<?php echo $p['id_planification']; ?>">Générer le BT</a><?php } ?></td></tr><?php } ?></table></section>
<?php require "includes/pied.php"; ?>
