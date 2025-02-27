<?php
require_once '../config/Connexion.php';

class Discussion {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = Connexion::pdo();
    }

    // Récupérer toutes les propositions d'un groupe
    public function getDiscussionsByGroup($groupId) {
        // On récupère les propositions pour un groupe donné
        $query = "SELECT p.id_proposition, p.titre_proposition, p.description_proposition, p.date_creation, i.nom_internaute, i.prenom_internaute
                  FROM proposition p
                  JOIN internaute i ON p.id_internaute = i.id_internaute
                  JOIN membre m ON p.id_groupe = m.id_groupe
                  WHERE m.id_groupe = :group_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':group_id' => $groupId]);
        $discussions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Pour chaque discussion, on récupère les commentaires associés
        foreach ($discussions as &$discussion) {
            $discussion['commentaires'] = $this->getCommentsForDiscussion($discussion['id_proposition']);
        }

        return $discussions;
    }

    // Récupérer les commentaires associés à une proposition
    private function getCommentsForDiscussion($propositionId) {
        $query = "SELECT c.id_commentaire, c.contenu_commentaire, c.date_commentaire, i.nom_internaute, i.prenom_internaute
                  FROM commentaire c
                  INNER JOIN proposition p
                  ON p.id_proposition = c.id_proposition
                  INNER JOIN internaute i
                  ON i.id_internaute = p.id_internaute
                  WHERE c.id_proposition = :proposition_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':proposition_id' => $propositionId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>