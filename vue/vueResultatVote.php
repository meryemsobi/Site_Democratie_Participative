<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats du vote</title>
    <link rel="stylesheet" href="../css/styleResultatVote.css">
</head>
<body>
    <h1>Résultats du vote</h1>
    <?php if ($results['status'] === 'success'): ?>
        <ul>
            <?php foreach ($results['data'] as $result): ?>
                <li><?php echo htmlspecialchars($result['choix']); ?> : <?php echo $result['total']; ?> votes</li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Erreur : <?php echo htmlspecialchars($results['message']); ?></p>
    <?php endif; ?>
</body>
</html>