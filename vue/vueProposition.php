
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soumettre une proposition</title>
    <link rel="stylesheet" href="../css/styleProposition.css">
</head>
<body>
    <div class="main-container">
        <h1>Soumettre une proposition</h1>
        <form action="../api/api.php?action=submit_proposal" method="POST">
            <!-- Sélection du thème -->
            <label for="theme_id">Choisissez un thème :</label>
            <select name="theme_id" id="theme_id" required>
                <option value="" disabled selected>-- Sélectionnez un thème --</option>
                <option value="1">Environnement</option>
                <option value="2">Économie</option>
                <option value="3">Technologie</option>
                <option value="4">Santé</option>
                <option value="5">Éducation</option>
                <option value="6">Culture</option>
                <option value="7">Transport</option>
                <option value="8">Justice</option>
                <option value="9">Urbanisme</option>
                <option value="10">Loisirs</option>
                <option value="11">Sciences</option>
                <option value="12">Politique</option>
                <option value="13">Sport</option>
                <option value="14">Mode</option>
                <option value="15">Gastronomie</option>
                <option value="16">Voyages</option>
                <option value="17">Sciences sociales</option>
                <option value="18">Technologies vertes</option>
                <option value="19">Jeux vidéo</option>
                <option value="20">Art</option>
                <option value="21">Musique</option>
                <option value="22">Architecture</option>
                <option value="23">Cinéma</option>
                <option value="24">Astronomie</option>
                <option value="25">Histoire</option>
                <option value="26">Philosophie</option>
                <option value="27">Psychologie</option>
                <option value="28">Développement personnel</option>
                <option value="29">Animaux</option>
                <option value="30">Météo</option>
            </select>

            <!-- Titre -->
            <label for="title">Titre de votre proposition :</label>
            <input type="text" id="title" name="title" placeholder="Entrez un titre" maxlength="255" required>

            <!-- Description -->
            <label for="description">Description :</label>
            <textarea id="description" name="description" placeholder="Décrivez votre proposition ici..." required></textarea>

            <!-- Date de fin de discussion -->
            <label for="discussion_end_date">Date de fin de discussion :</label>
            <input type="date" id="discussion_end_date" name="discussion_end_date" required>

            <!-- Budget -->
            <label for="budget">Budget :</label>
            <input type="number" id="budget" name="budget" min="0" step="0.01" required>

            <!-- Bouton -->
            <button type="submit">Soumettre</button>
        </form>
    </div>
</body>
</html>
