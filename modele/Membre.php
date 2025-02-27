<?php
require_once '../config/Connexion.php';

class Membre {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = Connexion::pdo();
    }

    // Insérer un membre dans un groupe avec un rôle
    public function insertMember($data) {
        $query = "INSERT INTO membre (id_groupe, id_internaute, rôle) 
                  VALUES (:group_id, :user_id, :role)";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([
            ':group_id' => $data['group_id'],
            ':user_id' => $data['user_id'],
            ':role' => $data['role'],
        ]);
    }


    // Supprimer un membre d'un groupe
    public function deleteMember($data) {
        $query = "DELETE FROM membre 
                  WHERE id_groupe = :group_id AND id_internaute = :user_id";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([
            ':group_id' => $data['group_id'],
            ':user_id' => $data['user_id'],
        ]);
    }

    // Récupérer tous les membres d'un groupe
    public function getMembersByGroup($groupId) {
        $query = "SELECT * FROM membre 
                  WHERE id_groupe = :group_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':group_id' => $groupId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer les groupes d'un utilisateur
    public function getGroupsByUser($userId) {
        $query = "SELECT * FROM membre 
                  WHERE id_internaute = :user_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

