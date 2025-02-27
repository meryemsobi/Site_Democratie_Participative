<?php
require_once '../config/Connexion.php';
require_once '../modele/Vote.php';

class ControleurVote {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = Connexion::pdo();
    }

    public function getVoteResults($voteId) {
        // Exemple d'utilisation de callAPI pour récupérer les résultats du vote
        $data = ['id_vote' => $voteId];
        $results = Connexion::callAPI('get_vote_results', $data);
        
        $resultsArray = json_decode($results, true);
        if ($resultsArray['status'] === 'success') {
            echo json_encode(['status' => 'success', 'data' => $resultsArray['reponse']]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Échec de la récupération des résultats']);
        }
    }



}
?>
