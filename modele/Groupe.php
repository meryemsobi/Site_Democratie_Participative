<?php
require_once '../config/Connexion.php';

class Groupe {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = Connexion::pdo();
    }

    // Fonction pour insérer un groupe
    public function insertGroup($data) {
        $query = "INSERT INTO groupe (nom_groupe, description_groupe, couleur_groupe, date_création_groupe, image_groupe) 
                  VALUES (:nom_groupe, :description_groupe, :couleur_groupe,  CURRENT_DATE, :image_groupe)";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([
            ':nom_groupe' => $data['nom_groupe'],
            ':description_groupe' => $data['description_groupe'],
            ':couleur_groupe' => $data['couleur_groupe'],  
            ':image_groupe' => $data['image_groupe'],
        ]);
    }

    // Fonction pour récupérer les groupes d'un utilisateur
    public function getGroupsByUser($userId) {
        $query = "SELECT g.id_groupe, g.nom_groupe, g.description_groupe, g.couleur_groupe, g.date_création_groupe, g.image_groupe
                  FROM groupe g
                  WHERE g.id_groupe IN (
                      SELECT id_groupe FROM membre WHERE id_internaute = :user_id
                  )";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Nouvelle fonction pour récupérer les détails d'un groupe
    public function getGroupDetails($groupId) {
        $query = "SELECT g.id_groupe, g.nom_groupe, g.description_groupe, g.couleur_groupe, g.date_création_groupe, g.image_groupe
                  FROM groupe g
                  WHERE g.id_groupe = :group_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':group_id' => $groupId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
