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

// Mise à jour des informations si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $adresse = $_POST['adresse'];
    $email = $_POST['email'];

    $updateStmt = Connexion::pdo()->prepare(
        'UPDATE INTERNAUTE SET 
            nom_internaute = ?, 
            prenom_internaute = ?, 
            adresse_internaute = ?, 
            mail_internaute = ? 
         WHERE id_internaute = ?'
    );
    $updateStmt->execute([$nom, $prenom, $adresse, $email, $userId]);

    echo "<div class='success'>Informations mises à jour avec succès. Redirection en cours...</div>";
    echo "<script>setTimeout(() => { window.location.href = 'vueCompte.php'; }, 2000);</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modification Compte</title>
    <link rel="stylesheet" href="../css/styleCompte.css">
</head>
<body>
    <div class="container">
        <!-- Section du logo -->
        <div class="logo-section">
            <img src="../images/logo.png" alt="We Decide Logo" onclick="window.location.href='vueAccueilGroupe.php'">
        </div>

        <!-- Section du formulaire -->
        <div class="form-section">
            <div class="form-title">Modifier Mon Compte</div>

            <form action="" method="post">
                <div class="form-group">
                    <label for="nom">Nom</label>
                    <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($user['nom_internaute']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="prenom">Prénom</label>
                    <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($user['prenom_internaute']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="adresse">Adresse</label>
                    <input type="text" id="adresse" name="adresse" value="<?= htmlspecialchars($user['adresse_internaute']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Adresse e-mail</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['mail_internaute']) ?>" required>
                </div>

                <div class="buttons">
                    <button type="submit" onclick="window.location.href='vueCompte.php'" class="submit-button">Enregistrer les modifications</button>
                    <button type="button" onclick="window.location.href='vueCompte.php'" class="back-button">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
