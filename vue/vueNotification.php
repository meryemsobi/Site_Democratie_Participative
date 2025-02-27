<?php
session_start();
require_once '../config/Connexion.php';
require_once '../controleur/ControleurNotification.php'; // Inclure le contrôleur de notifications

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: vueInscriptionConnexion.html"); // Rediriger si non connecté
    exit;
}

$user_id = $_SESSION['user']['id_internaute'];

// Créer une instance du contrôleur de notifications
$controleurNotification = new ControleurNotification();

// Récupérer les notifications de l'utilisateur via le contrôleur
$notificationsResponse = $controleurNotification->getNotifications($user_id);
$notifications = json_decode($notificationsResponse, true); // Décoder la réponse JSON
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link rel="stylesheet" href="../css/styleNotification.css"> <!-- Lien vers le fichier CSS -->
</head>
<body>
    <div class="container">
        <h1>Mes Notifications</h1>

        <?php if (isset($notifications['status']) && $notifications['status'] === 'success'): ?>
            <div class="notifications-container">
                <div class="notification-list">
                    <?php foreach ($notifications['data'] as $notification): ?>
                        <div class="notification-item">
                            <p class="message"><?php echo htmlspecialchars($notification['message_notification']); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php else: ?>
            <p class="no-notifications">Aucune notification.</p>
        <?php endif; ?>
    </div>
</body>
</html>
