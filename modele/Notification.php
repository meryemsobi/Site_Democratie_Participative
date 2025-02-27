<?php
require_once '../config/Connexion.php';

class Notification {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = Connexion::pdo();
    }

    // Insère une notification dans la base de données
    public function insertNotification($data) {
        $date_send = date('Y-m-d H:i:s');
        $sql = "INSERT INTO notification (message_notification)
                VALUES (:message)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':message' => $data['message'],
        ]);
    }

    // Récupère les notifications d'un utilisateur
    public function getUserNotifications($userId) {
        $sql = "SELECT * FROM reception_notification rn
                JOIN notification n ON rn.id_notification = n.id_notification
                WHERE rn.id_internaute = :user_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}


?>
