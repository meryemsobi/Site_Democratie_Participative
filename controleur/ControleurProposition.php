<?php
require_once '../config/Connexion.php';
require_once '../modele/Proposition.php';

class ControleurProposition {
    private $pdo;

    public function __construct() {
        $this->pdo = Connexion::pdo();
    }

    // Récupérer toutes les propositions
    public function getAllProposals() {
        $modeleProposition = new Proposition($this->pdo);
        $proposals = $modeleProposition->getAllProposals();
        echo json_encode(['status' => 'success', 'data' => $proposals]);
    }

    // Créer une nouvelle proposition
    public function createProposal($data) {
        // Appel à la méthode d'insertion dans le modèle Proposition
        $modeleProposition = new Proposition($this->pdo);
        $result = $modeleProposition->insertProposal($data);

        // Retourner un message de succès ou d'erreur
        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Proposition créée avec succès']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Échec de la création de la proposition']);
        }
    }

    // Supprimer une proposition
    public function deleteProposal($proposalId) {
        $modeleProposition = new Proposition($this->pdo);
        $result = $modeleProposition->removeProposal($proposalId);

        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Proposition supprimée']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Échec de la suppression']);
        }
    }
}
?>
