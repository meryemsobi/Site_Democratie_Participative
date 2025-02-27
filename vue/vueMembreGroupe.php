<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membres du groupe</title>
    <link rel="stylesheet" href="../css/styleMembreGroupe.css">
</head>
<body>
    <h1>Membres du groupe</h1>

    <!-- Formulaire pour ajouter un membre -->
    <form action="../api/api.php?action=add_member" method="POST">
        <input type="hidden" name="group_id" value="<?php echo $group_id; ?>">
        <input type="hidden" name="user_id_creator" value="<?php echo $user_id; ?>">
        <input type="text" name="user_id_to_add" placeholder="ID utilisateur à ajouter" required>
        <button type="submit">Ajouter un membre</button>
    </form>

    <!-- Liste des membres avec possibilité de suppression et d'attribution de rôles -->
    <?php if ($members['status'] === 'success'): ?>
        <ul>
            <?php foreach ($members['data'] as $member): ?>
                <li>
                    <?php echo htmlspecialchars($member['username']); ?> 
                    - Rôle : <?php echo htmlspecialchars($member['role']); ?>
                    <!-- Attribution de rôle -->
                    <form action="../api/api.php?action=assign_role" method="POST">
                        <input type="hidden" name="group_id" value="<?php echo $group_id; ?>">
                        <input type="hidden" name="user_id" value="<?php echo $member['id_internaute']; ?>">
                        <input type="hidden" name="user_id_creator" value="<?php echo $user_id; ?>">
                        <select name="role" required>
                            <option value="admin">Administrateur</option>
                            <option value="moderator">Modérateur</option>
                            <option value="scrutineer">Scrutateur</option>
                        </select>
                        <button type="submit">Attribuer rôle</button>
                    </form>

                    <!-- Suppression d'un membre -->
                    <form action="../api/api.php?action=remove_member" method="POST">
                        <input type="hidden" name="group_id" value="<?php echo $group_id; ?>">
                        <input type="hidden" name="user_id_to_remove" value="<?php echo $member['id_internaute']; ?>">
                        <input type="hidden" name="user_id_creator" value="<?php echo $user_id; ?>">
                        <button type="submit">Supprimer membre</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Aucun membre dans ce groupe ou erreur : <?php echo htmlspecialchars($members['message']); ?></p>
    <?php endif; ?>
</body>
</html>