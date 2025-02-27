<?php
// vueGroupeDetails.php
require_once '../controleur/ControleurGroupe.php';

echo "<!DOCTYPE html><html lang='fr'><head><meta charset='UTF-8'><meta name='viewport' content='width=device-width, initial-scale=1.0'><title>Page d'inscription et connexion</title><link rel='stylesheet' href='../css/styleGroupeDetails.css'></head>";

$controleur = new ControleurGroupe();
$groupe = $controleur->getGroupDetails($_GET['id']);
$groupDetails = $groupe['groupDetails'];
$discussions = $groupe['discussions'];

// Vérification si les détails du groupe ont été passés
if (isset($groupDetails)) {
    // Récupération des informations du groupe
    $groupName = htmlspecialchars($groupDetails['nom_groupe']);
    $groupDescription = htmlspecialchars($groupDetails['description_groupe']);
    $groupColor = htmlspecialchars($groupDetails['couleur_groupe']);
    $groupImage = htmlspecialchars($groupDetails['image_groupe']);
    $groupDate = $groupDetails['date_création_groupe'];

    // Affichage des détails du groupe
    echo "<h1>Bienvenue dans le groupe : $groupName</h1>";
    echo "<p><strong>Description :</strong> $groupDescription</p>";
    echo "<p><strong>Couleur du groupe :</strong> $groupColor</p>";
    echo "<p><strong>Date de création :</strong> $groupDate</p>";
    echo "<img src='$groupImage' alt='Image du groupe' style='max-width: 300px;'>";

    // Vérification si des discussions (propositions) existent
    if (!empty($discussions)) {
        echo "<h2>Discussions :</h2>";

        // Parcours des discussions (propositions)
        foreach ($discussions as $discussion) {
            $discussionTitle = htmlspecialchars($discussion['titre_proposition']);
            $discussionDescription = htmlspecialchars($discussion['description_proposition']);
            $discussionDate = $discussion['date_creation'];
            $discussionAuthor = htmlspecialchars($discussion['nom_internaute']) . " " . htmlspecialchars($discussion['prenom_internaute']);

            // Affichage de la discussion
            echo "<div class='discussion'>";
            echo "<h3>$discussionTitle</h3>";
            echo "<p><strong>Description :</strong> $discussionDescription</p>";
            echo "<p><strong>Créée par :</strong> $discussionAuthor</p>";
            echo "<p><strong>Date de création :</strong> $discussionDate</p>";

            // Formulaire de vote pour les discussions
            echo "<form action='soumettre_vote.php' method='POST'>";
            echo "<input type='hidden' name='proposition_id' value='{$discussion['id_proposition']}'>";
            echo "<button type='submit' name='vote_type' value='upvote'>&#128077; </button>"; // Emoji pouce en l'air
            echo "<button type='submit' name='vote_type' value='downvote'>&#128078; </button>"; // Emoji pouce en bas
            echo "</form>";

            // Formulaire de signalement pour les discussions
            echo "<form action='signaler.php' method='POST'>";
            echo "<input type='hidden' name='type_signalement' value='discussion'>";
            echo "<input type='hidden' name='id_signalement' value='{$discussion['id_proposition']}'>";
            echo "<button type='submit' name='signalement' value='signaler'>Signaler cette discussion</button>";
            echo "</form>";

            // Vérification si des décisions sont associées à la discussion
            if (!empty($discussion['decisions'])) {
                echo "<h4>Décisions associées :</h4>";

                // Parcours des décisions
                foreach ($discussion['decisions'] as $decision) {
                    $decisionTitle = htmlspecialchars($decision['titre_decision']);
                    $decisionDate = $decision['date_decision'];
                    echo "<p><strong>Décision :</strong> $decisionTitle (Prise le : $decisionDate)</p>";
                }
            } else {
                echo "<p>Aucune décision n'a encore été prise pour cette discussion.</p>";
            }

            // Vérification si des commentaires sont associés à la discussion
            if (!empty($discussion['commentaires'])) {
                echo "<h4>Commentaires :</h4>";

                // Parcours des commentaires
                foreach ($discussion['commentaires'] as $commentaire) {
                    $commentaireAuthor = htmlspecialchars($commentaire['nom_internaute']) . " " . htmlspecialchars($commentaire['prenom_internaute']);
                    $commentaireContent = htmlspecialchars($commentaire['contenu_commentaire']);
                    $commentaireDate = $commentaire['date_commentaire'];

                    // Affichage du commentaire
                    echo "<div class='commentaire'>";
                    echo "<p><strong>$commentaireAuthor :</strong> $commentaireContent</p>";
                    echo "<p><em>Publié le : $commentaireDate</em></p>";

                    // Formulaire de réaction pour les commentaires
                    echo "<form action='ajouter_reaction.php' method='POST'>";
                    echo "<input type='hidden' name='commentaire_id' value='{$commentaire['id_commentaire']}'>";
                    echo "<button type='submit' name='reaction_type' value='like'>&#128077; </button>"; // Emoji pouce en l'air
                    echo "<button type='submit' name='reaction_type' value='dislike'>&#128078; </button>"; // Emoji pouce en bas
                    echo "</form>";

                    // Formulaire de signalement pour les commentaires
                    echo "<form action='signaler.php' method='POST'>";
                    echo "<input type='hidden' name='type_signalement' value='commentaire'>";
                    echo "<input type='hidden' name='id_signalement' value='{$commentaire['id_commentaire']}'>";
                    echo "<button type='submit' name='signalement' value='signaler'>Signaler ce commentaire</button>";
                    echo "</form>";

                    // Vérification si des réactions sont associées au commentaire
                    if (!empty($commentaire['reactions'])) {
                        echo "<p><strong>Réactions :</strong> ";
                        foreach ($commentaire['reactions'] as $reaction) {
                            $reactionType = htmlspecialchars($reaction['type_reaction']);
                            echo "<span class='reaction'>$reactionType</span> ";
                        }
                        echo "</p>";
                    } else {
                        echo "<p>Aucune réaction pour ce commentaire.</p>";
                    }

                    echo "</div>";
                }
            } else {
                echo "<p>Aucun commentaire pour cette discussion.</p>";
            }

            // Formulaire pour ajouter un commentaire
            echo "<form action='ajouter_commentaire.php' method='POST'>";
            echo "<input type='hidden' name='proposition_id' value='{$discussion['id_proposition']}'>";
            echo "<textarea name='contenu_commentaire' placeholder='Ajouter un commentaire' required></textarea><br>";
            echo "<button type='submit'>Ajouter un commentaire</button>";
            echo "</form>";

            echo "</div><hr>";
        }
    } else {
        // Si aucune discussion n'existe
        echo "<p>Il n'y a pas encore de discussions dans ce groupe.</p>";
    }
} else {
    // Si les détails du groupe ne sont pas définis
    echo "<p>Détails du groupe non disponibles.</p>";
}

?>

