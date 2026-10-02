# Modèle de données - Mini-GMAO

## MCD simplifié

```mermaid
erDiagram
    UTILISATEURS ||--o{ DEMANDES_INTERVENTION : "crée"
    EQUIPEMENTS ||--o{ DEMANDES_INTERVENTION : "concerne"
    EQUIPEMENTS ||--o{ BONS_TRAVAIL : "fait l'objet de"
    UTILISATEURS ||--o{ BONS_TRAVAIL : "est affecté à"
    DEMANDES_INTERVENTION o|--o| BONS_TRAVAIL : "devient"
    EQUIPEMENTS ||--o{ PLANIFICATIONS : "possède"

    UTILISATEURS {
        int id_utilisateur PK
        string nom
        string email
        string mot_de_passe
        string role
    }
    EQUIPEMENTS {
        int id_equipement PK
        string code
        string nom
        string categorie
        string localisation
        date date_mise_service
        string statut
    }
    DEMANDES_INTERVENTION {
        int id_di PK
        int id_equipement FK
        int id_demandeur FK
        text description
        string urgence
        string statut
        datetime date_demande
    }
    BONS_TRAVAIL {
        int id_bt PK
        string numero_bt
        int id_equipement FK
        int id_di FK
        int id_technicien FK
        string type_bt
        string statut
        decimal temps_passe
    }
    PLANIFICATIONS {
        int id_planification PK
        int id_equipement FK
        string titre
        string periodicite
        date prochaine_date
        string statut
    }
```

## Règles de gestion principales

1. Un opérateur peut créer une ou plusieurs demandes d'intervention.
2. Une demande concerne un seul équipement.
3. Le responsable valide une demande et crée un bon de travail correctif.
4. Un BT est affecté à un seul technicien, mais un technicien peut avoir plusieurs BT.
5. Le statut d'un BT suit l'ordre : Créé, Assigné, En cours, Clôturé.
6. Pour clôturer, le technicien indique le temps passé, la cause et les tâches réalisées.
7. Un équipement possède zéro ou plusieurs planifications préventives.
