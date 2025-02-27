<?php
require_once '../config/Connexion.php';
require_once '../modele/Groupe.php';
require_once '../modele/Notification.php';
require_once '../modele/Discussion.php'; 

class ControleurGroupe {
    private $pdo;

    public function __construct() {
        $this->pdo = Connexion::pdo();
    }

    // Fonction pour créer un groupe
    public function createGroup($data) {
        $modeleGroupe = new Groupe($this->pdo);
        $result = $modeleGroupe->insertGroup($data);

        if ($result) {
            // Appeler l'API pour notifier la création du groupe
            $apiResponse = Connexion::callAPI('create_group', $data, 'POST');
            echo json_encode(['status' => 'success', 'message' => 'Groupe créé avec succès', 'api_response' => $apiResponse]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Échec de la création du groupe']);
        }
    }

    // Fonction pour afficher les groupes
    public function showGroups($userId) {
        $modeleGroupe = new Groupe($this->pdo);
        $groups = $modeleGroupe->getGroupsByUser($userId);

        $modeleNotification = new Notification($this->pdo);
        $notifications = $modeleNotification->getUserNotifications($userId);

        // Appeler l'API pour récupérer des informations supplémentaires (par exemple les détails des groupes)
        $apiResponse = Connexion::callAPI('get_groups', ['user_id' => $userId], 'GET');
        $apiResponseData = json_decode($apiResponse, true);

        return [
            'groupes' => $groups,
            'notifications' => $notifications
        ];
    }

    // Nouvelle méthode pour récupérer les détails d'un groupe et ses discussions
    public function getGroupDetails($groupId) {
        // Récupérer les informations du groupe
        $modeleGroupe = new Groupe($this->pdo);
        $groupDetails = $modeleGroupe->getGroupDetails($groupId);

        // Récupérer les discussions associées au groupe
        $modeleDiscussion = new Discussion($this->pdo);
        $discussions = $modeleDiscussion->getDiscussionsByGroup($groupId);

        $result = [
            'groupDetails' => $groupDetails,
            'discussions' => $discussions,
        ];

        return $result;

        // Passer les données à la vue pour afficher les détails du groupe et les discussions
        //require_once 'vue/vueGroupeDetails.php';
    }
}
?>
