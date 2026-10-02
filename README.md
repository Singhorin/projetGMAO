# Mini-GMAO - Gestion des bons de travail

Application PHP/MySQL réalisée avec des requêtes simples (`mysqli_connect`, `mysqli_query`) pour un projet de deuxième année.

## Installation

1. Copier le dossier `gmao-bons-travail` dans `htdocs` de XAMPP.
2. Démarrer Apache et MySQL.
3. Ouvrir phpMyAdmin, créer ou importer la base avec le fichier `database/gmao.sql`.
4. Vérifier les paramètres de MySQL dans `includes/connexion_bdd.php` (par défaut : `root`, sans mot de passe).
5. Ouvrir `http://localhost/gmao-bons-travail/`.

## Comptes de démonstration

| Rôle | Identifiant | Mot de passe |
| --- | --- | --- |
| Responsable | admin.maint@usine.com | Admin2026! |
| Technicien | tech.pierre@usine.com | Tech2026! |
| Opérateur | op.lucas@usine.com | Op2026! |

## Fonctions présentes

- Gestion des équipements par le responsable.
- Création et suivi des demandes d'intervention par l'opérateur.
- Validation d'une DI et création d'un BT par le responsable.
- Attribution, démarrage et clôture des BT par le technicien.
- Rapport de clôture obligatoire avec le temps, la cause et les tâches réalisées.
- Planification préventive et génération manuelle du BT correspondant.

Les mots de passe sont volontairement conservés en texte simple pour rester au niveau demandé par l'exercice. Dans un vrai projet, il faudrait utiliser `password_hash()`.
