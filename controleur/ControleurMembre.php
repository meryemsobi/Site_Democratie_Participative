<?php
require_once '../config/Connexion.php';
require_once '../modele/Membre.php';

class ControleurMembre {

    private $pdo;

    public function __construct() {
        $this->pdo = Connexion::pdo();
    }

    // Fonction pour ajouter un membre
    public function addMember($data) {
        $modeleMembre = new Membre($this->pdo);
        $result = $modeleMembre->insertMember($data);
        
        if ($result) {
            // Appel API pour notifier l'ajout du membre
            $apiResponse = Connexion::callAPI('add_member', $data, 'POST');
            echo json_encode(['status' => 'success', 'message' => 'Membre ajouté', 'api_response' => $apiResponse]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Échec de l\'ajout']);
        }
    }

    // Fonction pour supprimer un membre
    public function removeMember($data) {
        $modeleMembre = new Membre($this->pdo);
        $result = $modeleMembre->deleteMember($data);
        
        if ($result) {
            // Appel API pour notifier la suppression du membre
            $apiResponse = Connexion::callAPI('remove_member', $data, 'POST');
            echo json_encode(['status' => 'success', 'message' => 'Membre supprimé', 'api_response' => $apiResponse]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Échec de la suppression']);
        }
    }
}
?>
