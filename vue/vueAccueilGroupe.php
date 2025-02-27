<?php
require_once '../controleur/ControleurGroupe.php';

session_start();

// Vérification de connexion
if (!isset($_SESSION['user'])) {
    header("Location: vueConnexion.php");
    exit;
}

// Récupérer l'ID utilisateur depuis la session
$userId = $_SESSION['user']['id_internaute'];

// Initialiser le contrôleur
$controleur = new ControleurGroupe();
$data = $controleur->showGroups($userId);

// Récupérer les groupes
$groupes = $data['groupes'] ?? [];  // Si pas de groupes, tableau vide
$notifications = $notifications ?? ['data' => []];
$errorMessage = $data['errorMessage'] ?? "";  // Si erreur, message approprié

// Tableau des correspondances des couleurs
$couleursNomToHex = [
    'vert' => '#28a745',
    'rouge' => '#dc3545',
    'bleu' => '#007bff',
    'jaune' => '#ffc107',
    'orange' => '#fd7e14',
    'violet' => '#6f42c1',
    'gris' => '#6c757d',
    'noir' => '#343a40',
    'blanc' => '#ffffff'
];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'accueil des groupes</title>
    <link rel="stylesheet" href="../css/styleAccueilGroupe.css">
</head>
<body>
    <div class="main-container">
        <!-- Logo -->
        <div class="logo-container">
            <img src="../images/logo.png" alt="Logo We Decide">
        </div>

        <!-- Créer un groupe - En haut à droite, sur la même ligne que le logo -->
        <div class="create-group-container">
            <a href="vueNouveauGroupe.php"><img src="../images/creer.png" alt="Icône créer un groupe"></a>
            <p><a href="vueNouveauGroupe.php">Créer un groupe</a></p>
        </div>

        <!-- Notifications -->
        <div class="notification-container">
            <div class="notification-icon">
                <img src="../images/notification.png" alt="Icône de notification">
                <span class="notification-badge"><?php echo count($notifications['data']); ?></span>
            </div>
            <p><a href="vueNotification.php">Notifications</a></p>
        </div>

        <!-- Liste des groupes -->
        <div class="group-container">
            <h2>Mes Groupes</h2>
            <div class="group-list">
                <?php
                $nbGroupes = count($groupes);
                echo "<p>Nombre de groupes : $nbGroupes</p>";

                if ($errorMessage) {
                    echo '<p class="error">' . htmlspecialchars($errorMessage) . '</p>';
                } else {
                    foreach ($groupes as $groupe) {
                        // Assurez-vous que les clés existent
                        $icone = htmlspecialchars($groupe['image_groupe'] ?? '../images/default-icon.png'); // Icône par défaut
                        $nom = htmlspecialchars($groupe['nom_groupe'] ?? 'Nom inconnu');
                        $couleur = htmlspecialchars($groupe['couleur_groupe'] ?? '#FFFFFF'); // Couleur par défaut
                        $role = htmlspecialchars($groupe['rôle'] ?? 'Rôle inconnu'); // Rôle de l'internaute

                        // Vérifie si la couleur est un nom et remplace par la valeur hexadécimale
                        if (array_key_exists($couleur, $couleursNomToHex)) {
                            $couleur = $couleursNomToHex[$couleur];
                        }

                        $id = htmlspecialchars($groupe['id_groupe']);

                        // Vérifier si l'icône existe avant de l'afficher
                        if (!file_exists($icone)) {
                            $icone = '../images/default-icon.png'; // Utiliser l'icône par défaut si le fichier n'existe pas
                        }

                        echo "
                            <div class=\"group-card\" style=\"background-color: $couleur;\">
                                <img src=\"$icone\" alt=\"Icône du groupe\">
                                <p style=\"color: #FFFFFF;\">$nom <span style=\"font-style: italic;\">($role)</span></p> <!-- Texte en blanc pour plus de contraste -->
                                <a href=\"vueGroupeDetails.php?id=$id\" class=\"btn-details\">Voir détails</a>
                            </div>";
                    }
                }
                ?>
            </div>
        </div>

        <!-- Compte utilisateur -->
        <div class="account-container">
            <img src="../images/compte.png" alt="Icône compte">
            <p><a href="vueCompte.php">Compte</a></p>
        </div>
    </div>
</body>
</html>
