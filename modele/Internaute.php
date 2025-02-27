<?php
require_once '../config/Connexion.php';

class Internaute {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = Connexion::pdo();
    }

    // Insérer un utilisateur dans la base de données
    public function insertUser($data) {
        $query = "INSERT INTO internaute (nom_internaute, prenom_internaute, mail_internaute, adresse_internaute, mot_de_passe_internaute) 
                  VALUES (:nom_internaute, :prenom_internaute, :mail_internaute, :adresse_internaute, :mot_de_passe_internaute)";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([
            ':nom_internaute' => $data['nom_internaute'],
            ':prenom_internaute' => $data['prenom_internaute'],
            ':mail_internaute' => $data['mail_internaute'],
            ':adresse_internaute' => $data['adresse_internaute'],
            ':mot_de_passe_internaute' => $data['mot_de_passe_internaute'],
        ]);
    }

    // Récupérer un utilisateur par son adresse email
    public function getUserByEmail($email) {
        $query = "SELECT * FROM internaute WHERE mail_internaute = :email";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC); // Retourne un tableau avec les informations de l'utilisateur
    }
    

    // Supprimer un utilisateur de la base de données
    public function deleteUser($userId) {
        $query = "DELETE FROM internaute WHERE id_internaute = :id";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([':id' => $userId]);
    }

    // Mettre à jour les informations d'un utilisateur
    public function updateUser($userId, $data) {
        $query = "UPDATE internaute SET nom_internaute = :nom_internaute, prenom_internaute = :prenom_internaute, 
                  mail_internaute = :mail_internaute, adresse_internaute = :adresse_internaute, mot_de_passe_internaute = :mot_de_passe_internaute 
                  WHERE id_internaute = :id";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([
            ':id' => $userId,
            ':nom_internaute' => $data['nom_internaute'],
            ':prenom_internaute' => $data['prenom_internaute'],
            ':mail_internaute' => $data['mail_internaute'],
            ':adresse_internaute' => $data['adresse_internaute'],
            ':mot_de_passe_internaute' => password_hash($data['mot_de_passe_internaute'], PASSWORD_DEFAULT),
        ]);
    }
}
?>
