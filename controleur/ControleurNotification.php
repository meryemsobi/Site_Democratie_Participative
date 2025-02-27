<?php
require_once '../config/Connexion.php';
require_once '../modele/Notification.php';

class ControleurNotification {

    private $pdo;

    public function __construct() {
        $this->pdo = Connexion::pdo();
    }

    // Fonction pour envoyer une notification
    public function sendNotification($data) {
        $modeleNotification = new Notification($this->pdo);
        $result = $modeleNotification->insertNotification($data);

        if ($result) {
            // Appel API pour notifier l'envoi de la notification
            $apiResponse = Connexion::callAPI('send_notification', $data, 'POST');
            echo json_encode(['status' => 'success', 'message' => 'Notification envoyée', 'api_response' => $apiResponse]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erreur lors de l\'envoi de la notification']);
        }
    }

    // Fonction pour récupérer les notifications d'un utilisateur
    public function getNotifications($userId) {
        $modeleNotification = new Notification($this->pdo);
        $notifications = $modeleNotification->getUserNotifications($userId);

        if ($notifications) {
            // Appel API pour récupérer des données supplémentaires (facultatif selon vos besoins)
            $apiResponse = Connexion::callAPI('get_notifications', ['user_id' => $userId], 'GET');
            return json_encode([
                'status' => 'success',
                'data' => $notifications,
                'api_response' => $apiResponse // Facultatif si vous n'avez pas besoin de cette partie
            ]);
        } else {
            return json_encode([
                'status' => 'error',
                'message' => 'Aucune notification trouvée'
            ]);
        }
    }
}

?>
