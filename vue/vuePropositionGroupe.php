<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Propositions du groupe</title>
    <link rel="stylesheet" href="../css/stylePropositionGroupe.css">
</head>
<body>
    <h1>Propositions</h1>

    <!-- Formulaire pour soumettre une proposition -->
    <form action="../api/api.php?action=submit_proposal" method="POST">
        <input type="hidden" name="group_id" value="<?php echo $group_id; ?>">
        <input type="hidden" name="user_id" value="<?php echo $_SESSION['user']['id_internaute']; ?>">
        <textarea name="content" placeholder="Écrivez votre proposition..." required></textarea>
        <button type="submit">Soumettre</button>
    </form>

    <!-- Liste des propositions -->
    <?php if ($proposals['status'] === 'success'): ?>
        <?php foreach ($proposals['data'] as $proposal): ?>
            <div class="proposal">
                <p><strong><?php echo htmlspecialchars($proposal['auteur']); ?> :</strong></p>
                <p><?php echo htmlspecialchars($proposal['contenu']); ?></p>
                <p><small>Posté le : <?php echo htmlspecialchars($proposal['date_creation']); ?></small></p>

                <!-- Bouton pour signaler -->
                <form action="../api/api.php?action=report_proposal" method="POST" style="display:inline;">
                    <input type="hidden" name="proposal_id" value="<?php echo $proposal['id_proposition']; ?>">
                    <input type="text" name="reason" placeholder="Raison du signalement" required>
                    <button type="submit">Signaler</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Erreur : <?php echo htmlspecialchars($proposals['message']); ?></p>
    <?php endif; ?>
</body>
</html>