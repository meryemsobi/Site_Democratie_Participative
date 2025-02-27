<?php
require_once '../config/Connexion.php';
require_once '../modele/Role.php';

class ControleurRole {
    private $pdo;

    public function __construct() {
        $this->pdo = Connexion::pdo();
    }

    // Fonction pour assigner un rôle
    public function assignRole($data) {
        $modeleRole = new Role($this->pdo);
        $result = $modeleRole->insertRole($data);
        
        if ($result) {
            // Appel API pour notifier l'assignation du rôle
            $apiResponse = Connexion::callAPI('assign_role', $data, 'POST');
            echo json_encode(['status' => 'success', 'message' => 'Rôle assigné avec succès', 'api_response' => $apiResponse]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Échec de l\'assignation du rôle']);
        }
    }

    // Fonction pour mettre à jour un rôle
    public function updateRole($data) {
        $modeleRole = new Role($this->pdo);
        $result = $modeleRole->updateRole($data);
        
        if ($result) {
            // Appel API pour notifier la mise à jour du rôle
            $apiResponse = Connexion::callAPI('update_role', $data, 'POST');
            echo json_encode(['status' => 'success', 'message' => 'Rôle mis à jour', 'api_response' => $apiResponse]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Échec de la mise à jour']);
        }
    }
}
?>
