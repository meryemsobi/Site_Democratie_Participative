<?php
require_once '../config/Connexion.php';

require_once '../modele/Decision.php'; // Inclure le modèle Decision

class ControleurDecision {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = Connexion::pdo();
    }

    public function getDecision($voteId) {
        $data = ['id_vote' => $voteId];  // Passe bien l'ID du vote
        $decision = Connexion::callAPI('get_decision', $data , 'GET');
    
        $decisionArray = json_decode($decision, true);
        if ($decisionArray['status'] === 'success') {
            echo json_encode(['status' => 'success', 'data' => $decisionArray['reponse']]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Aucune décision trouvée']);
        }
    }
    

}
?>
