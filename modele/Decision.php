<?php

require_once '../config/Connexion.php';

class Decision {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = Connexion::pdo();
    }

    public function getDecisionByVote($voteId) {
        $sql = "SELECT * FROM decision WHERE id_vote = :id_vote";  // Assure-toi que la colonne correspond bien à l'ID
        $stmt = $this->pdo->prepare($sql);
    
        // Bind du paramètre
        $stmt->bindParam(':id_vote', $voteId, PDO::PARAM_INT);
    
        // Exécution de la requête
        $stmt->execute();
    
        // Retourne la première ligne de la décision si elle existe
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
