<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des propositions</title>
</head>
<body>
    <h1>Gestion des propositions</h1>
    <?php if ($proposals['status'] === 'success'): ?>
        <ul>
            <?php foreach ($proposals['data'] as $proposal): ?>
                <li>
                    <?php echo htmlspecialchars($proposal['contenu']); ?>
                    <form action="../api/api.php?action=delete_proposal" method="POST" style="display:inline;">
                        <input type="hidden" name="proposal_id" value="<?php echo $proposal['id_proposition']; ?>">
                        <button type="submit">Supprimer</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Erreur : <?php echo htmlspecialchars($proposals['message']); ?></p>
    <?php endif; ?>
</body>
</html>