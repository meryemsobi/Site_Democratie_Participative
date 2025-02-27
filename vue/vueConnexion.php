<?php
require_once '../config/Connexion.php'; // Connexion à la base de données
require_once '../controleur/ControleurInternaute.php'; // Inclusion du contrôleur de l'internaute

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $motdepasse = $_POST['motdepasse'];

    // Récupérer l'utilisateur correspondant à l'email
    $stmt = Connexion::pdo()->prepare('SELECT * FROM internaute WHERE mail_internaute = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $motdepasseStocke = $user['mot_de_passe_internaute'];

        // Vérifie si le mot de passe est haché
        $isValid = false;
        if (password_verify($motdepasse, $motdepasseStocke)) {
            $isValid = true; // Mot de passe haché valide
        } elseif ($motdepasse === $motdepasseStocke) {
            $isValid = true; // Mot de passe en clair valide
            // Migre le mot de passe en le hachant pour une meilleure sécurité
            $nouveauMotDePasse = password_hash($motdepasse, PASSWORD_BCRYPT);
            $updateStmt = Connexion::pdo()->prepare('UPDATE internaute SET mot_de_passe_internaute = ? WHERE id_internaute = ?');
            $updateStmt->execute([$nouveauMotDePasse, $user['id_internaute']]);
        }

        if ($isValid) {
            // Connexion réussie
            $_SESSION['user'] = $user;
            $_SESSION['user_id'] = $user['id_internaute'];

            echo "<div class='success'>Connexion réussie. Vous allez être redirigé vers la page d'accueil dans quelques secondes.</div>";
            echo "<script>
                    setTimeout(() => {
                        window.location.href = 'vueAccueilGroupe.php';
                    }, 3000);
                  </script>";
            exit;
        }
    }

    // Si les informations sont incorrectes
    $error_message = "Email ou mot de passe incorrect.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="../css/styleConnexion.css">
</head>
<body>
    <div class="container">
        <div class="logo-section">
            <img src="../images/logo.png" alt="We Decide Logo" class="logo">
        </div>

        <div class="form-section">
            <?php if (isset($error_message)) { echo "<p class='error-message'>$error_message</p>"; } ?>
            <button type="submit" class="signin-button">Connexion</button>
            <form action="" method="POST" class="signin-form">
                <label for="email">Identifiant (adresse mail)</label>
                <input type="email" id="email" name="email" required>

                <label for="motdepasse">Mot de passe</label>
                <input type="password" id="motdepasse" name="motdepasse" required>

                <button type="submit" class="submit-button">Valider</button>
            </form>
        </div>
    </div>
</body>
</html>