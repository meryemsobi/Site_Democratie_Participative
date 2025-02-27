<?php
require_once '../config/Connexion.php';  // Inclure la connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données du formulaire
    $nom_groupe = $_POST['nom_groupe'];
    $description_groupe = $_POST['description_groupe'];
    $couleur_groupe = $_POST['couleur_groupe'];
    $image = "Icone du groupe vide"; // Valeur par défaut si aucune image n'est fournie

    // Traitement de l'image si présente
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $imageFileName = basename($_FILES['image']['name']);
        $target_dir = '../images/';
        $target_file = $target_dir . $imageFileName;

        // Vérifier si le dossier images existe
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // Déplacer l'image téléchargée
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image = $target_file; // Chemin de l'image téléchargée
        } else {
            $errorMessage = "Erreur lors du téléchargement de l'image.";
        }
    }

    try {
        // Connexion à la base de données
        $pdo = Connexion::pdo();

        // Démarrer une transaction
        $pdo->beginTransaction();

        // Insérer le nouveau groupe dans la table GROUPE
        $stmt = $pdo->prepare("
            INSERT INTO groupe (id_groupe, nom_groupe, description_groupe, couleur_groupe, image_groupe, date_création_groupe)
            VALUES (NULL, ?, ?, ?, ?, CURRENT_DATE)
        ");
        $stmt->execute([$nom_groupe, $description_groupe, $couleur_groupe, $image]);

        // Récupérer l'ID du groupe inséré
        $id_groupe = $pdo->lastInsertId();

        // Ajouter l'utilisateur (ici supposé être celui avec ID 1) comme administrateur du groupe dans la table MEMBRE
        // Remplacez l'ID par celui de l'utilisateur approprié
        $id_internaute = 1; // ID utilisateur à remplacer par la logique nécessaire pour récupérer l'utilisateur connecté

        $stmt = $pdo->prepare("
            INSERT INTO membre (id_internaute, id_groupe, rôle)
            VALUES (?, ?, 'Administrateur')
        ");
        $stmt->execute([$id_internaute, $id_groupe]);

        // Valider la transaction
        $pdo->commit();

        // Redirection vers la page des groupes
        header('Location: vueAccueilGroupe.php');
        exit;
    } catch (PDOException $e) {
        // Annuler la transaction en cas d'erreur
        $pdo->rollBack();
        $errorMessage = "Erreur de connexion à la base de données : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Groupe</title>
    <link rel="stylesheet" href="../css/styleNouveauGroupe.css">
</head>
<body>
    <div class="container">
        <!-- Logo -->
        <div class="logo-section" onclick="window.location.href='vueAccueilGroupe.php'">
            <img src="../images/logo.png" alt="We Decide Logo">
        </div>

        <!-- Formulaire -->
        <div class="form-section">
            <div class="form-title">Créer un Nouveau Groupe</div>

            <?php if (!empty($errorMessage)): ?>
                <div class="error-message"><?= htmlspecialchars($errorMessage) ?></div>
            <?php endif; ?>

            <form action="" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="nom_groupe">Nom du Groupe</label>
                    <input type="text" id="nom_groupe" name="nom_groupe" placeholder="Entrez le nom" required>
                </div>

                <div class="form-group">
                    <label for="description_groupe">Description</label>
                    <textarea id="description_groupe" name="description_groupe" placeholder="Entrez une description" required></textarea>
                </div>

                <div class="form-group">
                    <label for="couleur_groupe">Choisir une couleur</label>
                    <input type="color" id="couleur_groupe" name="couleur_groupe" value="#000000" required>
                </div>

                <div class="form-group">
                    <label for="image">Image du Groupe</label>
                    <input type="file" id="image" name="image" accept="image/png, image/jpeg">
                </div>

                <div class="buttons">
                    <button type="submit">Créer le Groupe</button>
                    <button type="button" onclick="window.location.href='vueAccueilGroupe.php'" class="back-button">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
