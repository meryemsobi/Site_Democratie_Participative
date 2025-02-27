<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des membres</title>
    <link rel="stylesheet" href="../css/styleGestionProposition.css">
</head>
<body>
    <h1>Gestion des membres</h1>

    <!-- Ajouter un membre -->
    <form action="../api/api.php?action=add_member" method="POST">
        <input type="hidden" name="group_id" value="<?php echo $group_id; ?>">
        <label for="user_id">ID de l'utilisateur :</label>
        <input type="number" name="user_id" id="user_id" required>
        <button type="submit">Ajouter membre</button>
    </form>

    <!-- Liste des membres -->
    <?php if ($members['status'] === 'success'): ?>
        <ul>
            <?php foreach ($members['data'] as $member): ?>
                <li>
                    <?php echo htmlspecialchars($member['nom']); ?> (<?php echo htmlspecialchars($member['role']); ?>)
                    <form action="../api/api.php?action=update_role" method="POST" style="display:inline;">
                        <input type="hidden" name="group_id" value="<?php echo $group_id; ?>">
                        <input type="hidden" name="user_id" value="<?php echo $member['id_internaute']; ?>">
                        <select name="role">
                            <option value="admin" <?php echo $member['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                            <option value="moderateur" <?php echo $member['role'] === 'moderateur' ? 'selected' : ''; ?>>Modérateur</option>
                            <option value="scrutateur" <?php echo $member['role'] === 'scrutateur' ? 'selected' : ''; ?>>Scrutateur</option>
                            <option value="membre" <?php echo $member['role'] === 'membre' ? 'selected' : ''; ?>>Membre</option>
                        </select>
                        <button type="submit">Modifier rôle</button>
                    </form>
                    <form action="../api/api.php?action=remove_member" method="POST" style="display:inline;">
                        <input type="hidden" name="group_id" value="<?php echo $group_id; ?>">
                        <input type="hidden" name="user_id" value="<?php echo $member['id_internaute']; ?>">
                        <button type="submit">Supprimer</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Erreur : <?php echo htmlspecialchars($members['message']); ?></p>
    <?php endif; ?>
</body>
</html>
