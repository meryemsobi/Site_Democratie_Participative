<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assigner des rôles</title>
    <link rel="stylesheet" href="../css/styleRole.css">
</head>
<body>
    <h1>Assigner des rôles</h1>
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Rôle actuel</th>
                <th>Attribuer un nouveau rôle</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($membres as $membre): ?>
                <tr>
                    <td><?php echo htmlspecialchars($membre['nom_internaute']); ?></td>
                    <td><?php echo htmlspecialchars($membre['rôle']); ?></td>
                    <td>
                        <form action="../api/api.php?action=assign_role" method="POST">
                            <input type="hidden" name="user_id" value="<?php echo $membre['id_internaute']; ?>">
                            <input type="hidden" name="group_id" value="<?php echo $group_id; ?>">
                            <select name="role">
                                <option value="membre">Membre</option>
                                <option value="admin">Admin</option>
                                <option value="modérateur">Modérateur</option>
                                <option value="scrutateur">Scrutateur</option>
                            </select>
                            <button type="submit">Attribuer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>