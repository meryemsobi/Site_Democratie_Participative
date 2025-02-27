<?php
require_once '../config/Connexion.php';

class Vote {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = Connexion::pdo();
    }

    // Méthode pour créer un vote
    public static function insertVote($data) {
        $query = "INSERT INTO vote (type_vote, date_debut_vote, date_fin_vote, id_proposition)
                VALUES (:type, :start_date, :end_date, :proposal_id)";
       $stmt = $this->pdo->prepare($query);
       return $stmt->execute([
           ':type' => $data['type_vote'],
           ':start_date' => $data['date_debut_vote'],
           ':end_date' => $data['date_fin_vote'],
           ':proposal_id' => $data['id_proposition'],
       ]);
   }

    // Méthode pour récupérer les résultats du vote
    public function getVoteResults($voteId) {
        // On va chercher le résultat de la décision associée à ce vote
        $query = "SELECT résultat_décision, budget_décision FROM decision WHERE id_vote = :vote_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':vote_id' => $voteId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Ajouter une décision en fonction des résultats du vote
    public function insertDecision($data) {
        $query = "INSERT INTO decision (résultat_décision, budget_décision, id_vote) 
                  VALUES (:result, :budget, :vote_id)";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([
            ':result' => $data['result'],
            ':budget' => $data['budget'],
            ':vote_id' => $data['vote_id'],
        ]);
    }

    // Récupérer une décision à partir de l'ID du vote
    public function getDecisionByVote($voteId) {
        $query = "SELECT * FROM decision WHERE id_vote = :vote_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':vote_id' => $voteId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}
?>
