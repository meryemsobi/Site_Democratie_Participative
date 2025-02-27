<?php
require_once '../config/Connexion.php';

class Proposition {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = Connexion::pdo();
    }

    // Récupérer une proposition par son ID
    public function getPropositionById($proposalId) {
        $sql = "SELECT * FROM proposition WHERE id_proposition = :id_proposition";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id_proposition', $proposalId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Récupérer toutes les propositions
    public function getAllProposals() {
        $sql = "SELECT * FROM proposition";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Insérer une nouvelle proposition
    public function insertProposal($data) {
        // Requête SQL pour insérer une nouvelle proposition
        $query = "INSERT INTO proposition (
                      titre_proposition, 
                      description_proposition, 
                      id_thème, 
                      id_internaute, 
                      date_creation, 
                      etat_proposition, 
                      date_fin_discussion, 
                      budget
                  ) VALUES (
                      :titre_proposition, 
                      :description_proposition, 
                      :id_thème, 
                      :id_internaute, 
                      CURRENT_DATE, 
                      :etat_proposition, 
                      :date_fin_discussion, 
                      :budget
                  )";
    
        // Préparation et exécution de la requête
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([
            ':titre_proposition' => $data['titre_proposition'],
            ':description_proposition' => $data['description_proposition'],
            ':id_thème' => $data['id_thème'],
            ':id_internaute' => $data['id_internaute'],
            ':etat_proposition' => $data['etat_proposition'] ?? 'En discussion',
            ':date_fin_discussion' => $data['date_fin_discussion'],
            ':budget' => $data['budget']
        ]);
    }

    // Supprimer une proposition
    public function removeProposal($proposalId) {
        $query = "DELETE FROM proposition WHERE id_proposition = :proposal_id";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([':proposal_id' => $proposalId]);
    }
}
?>
