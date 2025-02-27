<?php
require_once '../config/Connexion.php'; // Connexion à la base de données
require_once '../controleur/ControleurInternaute.php'; // Inclusion du contrôleur

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Vérification de la présence des champs dans le formulaire
  

    // Récupération des données depuis le formulaire
    $data = [
        'nom_internaute' => $_POST['nom_internaute'],
        'prenom_internaute' => $_POST['prenom_internaute'],
        'mail_internaute' => $_POST['mail_internaute'],
        'adresse_internaute' => $_POST['adresse_internaute'],
        'mot_de_passe_internaute' => password_hash($_POST['mot_de_passe_internaute'], PASSWORD_BCRYPT) // Hash du mot de passe
    ];

    // Utilisation du contrôleur pour créer l'utilisateur
    $controleur = new ControleurInternaute();
    $result = $controleur->createUser($data);

    // Vérification du résultat et affichage d'un message
    if ($result['status'] === 'success') {
        echo "<div class='success'>Inscription réussie. Vous allez être redirigé vers la page de connexion dans quelques secondes.</div>";
        echo "<script>
                setTimeout(() => {
                    window.location.href = 'vueInscriptionConnexion.html';
                }, 2000);
              </script>";
        exit;
    } else {
        echo "<div class='error'>Erreur lors de l'inscription : {$result['message']}</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="../css/styleInscription.css">
</head>
<body>
    <div class="container">
        <!-- Section du logo -->
        <div class="logo-section">
            <img src="../images/logo.png" alt="We Decide Logo" class="logo">
        </div>

        <!-- Section du formulaire -->
        <div class="form-section">
            
        <button type="submit" class="signup-button">Inscription</button>
            <form action="" method="POST" class="signup-form">
                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom_internaute" required>

                <label for="prenom">Prénom</label>
                <input type="text" id="prenom" name="prenom_internaute" required>

                <label for="adresse">Adresse</label>
                <input type="text" id="adresse" name="adresse_internaute" required>

                <label for="email">Adresse e-mail</label>
                <input type="email" id="email" name="mail_internaute" required>

                <label for="mot_de_passe">Mot de passe</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe_internaute" required>

                <button type="submit" name="submit" class="submit-button">Valider</button>
            </form>
        </div>
    </div>
</body>
</html>
