<?php
require_once '../config/Connexion.php';

class Role {
   
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = Connexion::pdo();
    }

    public function insertRole($data) {
        $query = "INSERT INTO membre (id_internaute, id_groupe, rôle) 
                  VALUES (:id_internaute, :id_groupe, :rôle)";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([
            ':id_internaute' => $data['id_internaute'],
            ':id_groupe' => $data['id_groupe'],
            ':rôle' => $data['rôle'],
        ]);
    }
    

    public function updateRole($data) {
        $query = "UPDATE membre 
                  SET rôle = :rôle 
                  WHERE id_internaute = :id_internaute AND id_groupe = :id_groupe";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([
            ':rôle' => $data['rôle'],
            ':id_internaute' => $data['id_internaute'],
            ':id_groupe' => $data['id_groupe'],
        ]);
    }
    
}
?>