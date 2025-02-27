<?php
session_start();
require_once '../config/Connexion.php';

// Vérification de l'authentification
if (!isset($_SESSION['user_id'])) {
    header('Location: vueInscriptionConnexion.html');
    exit;
}

// Récupérer les informations de l'utilisateur
$userId = $_SESSION['user_id'];
$stmt = Connexion::pdo()->prepare('SELECT * FROM internaute WHERE id_internaute = ?');
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "Erreur : utilisateur introuvable.";
    exit;
}

// Gestion des actions via les requêtes POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        // Supprimer le compte
        if ($_POST['action'] === 'supprimer-compte') {
            try {
                $stmt = Connexion::pdo()->prepare('DELETE FROM internaute WHERE id_internaute = ?');
                $stmt->execute([$userId]);

                // Détruire la session après suppression
                session_destroy();

                echo "<div class='success'>Compte supprimé avec succès. Vous allez être redirigé vers la page d'inscription dans quelques secondes.</div>";
                echo "<script>
                        setTimeout(() => {
                            window.location.href = 'vueInscriptionConnexion.html';
                        }, 3000);
                      </script>";
                exit;
            } catch (Exception $e) {
                echo "<div class='error'>Erreur lors de la suppression du compte : " . htmlspecialchars($e->getMessage()) . "</div>";
            }
        }

        // Déconnexion
        if ($_POST['action'] === 'deconnexion') {
            session_destroy();
            echo "<div class='success'>Déconnexion réussie. Vous allez être redirigé vers la page d'accueil dans quelques secondes.</div>";
            echo "<script>
                    setTimeout(() => {
                        window.location.href = 'vueInscriptionConnexion.html';
                    }, 3000);
                  </script>";
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Compte</title>
    <link rel="stylesheet" href="../css/styleCompte.css">
</head>
<body>
    <div class="container">
        <!-- Section du logo -->
        <div class="logo-section">
            <img src="../images/logo.png" alt="We Decide Logo">
        </div>

        <!-- Section du formulaire -->
        <div class="form-section">
            <div class="form-title">Mon Compte</div>

            <form action="" method="post">
                <div class="form-group">
                    <label for="nom">Nom</label>
                    <input type="text" id="nom" name="nom" 
                        value="<?= isset($user['nom_internaute']) ? htmlspecialchars($user['nom_internaute']) : 'Nom indisponible'; ?>" 
                        readonly>
                </div>
                <div class="form-group">
                    <label for="prenom">Prénom</label>
                    <input type="text" id="prenom" name="prenom" 
                        value="<?= isset($user['prenom_internaute']) ? htmlspecialchars($user['prenom_internaute']) : 'Prénom indisponible'; ?>" 
                        readonly>
                </div>
                <div class="form-group">
                    <label for="adresse">Adresse</label>
                    <input type="text" id="adresse" name="adresse" 
                        value="<?= isset($user['adresse_internaute']) ? htmlspecialchars($user['adresse_internaute']) : 'Adresse indisponible'; ?>" 
                        readonly>
                </div>
                <div class="form-group">
                    <label for="email">Adresse e-mail</label>
                    <input type="email" id="email" name="email" 
                        value="<?= isset($user['mail_internaute']) ? htmlspecialchars($user['mail_internaute']) : 'E-mail indisponible'; ?>" 
                        readonly>
                </div>

                <div class="buttons">
                    <button type="button" onclick="window.location.href='vueModificationCompte.php'" class="submit-button">Modifier les informations</button>
                    
                    <button type="submit" name="action" value="deconnexion" class="logout-button">Se déconnecter</button>
                    
                    <button type="submit" name="action" value="supprimer-compte" class="delete-button">Supprimer le compte</button>
                    
                    <button type="button" onclick="window.location.href='vueAccueilGroupe.php'" class="back-button">Retour</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Script de confirmation pour la suppression de compte
        document.querySelector('.delete-button').addEventListener('click', function (event) {
            if (!confirm("Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible.")) {
                event.preventDefault();
            }
        });
    </script>
</body>
</html>

