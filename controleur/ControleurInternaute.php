<?php
require_once '../config/Connexion.php';
require_once '../modele/Internaute.php';

class ControleurInternaute {
    private $pdo;

    public function __construct() {
        $this->pdo = Connexion::pdo();
    }

    public function createUser($data) {
        // Créer une instance de la classe Internaute pour gérer l'insertion dans la base de données
        $modeleInternaute = new Internaute($this->pdo);
    
        // Utiliser la méthode insertUser pour insérer les données de l'utilisateur dans la base de données
        $result = $modeleInternaute->insertUser($data);
    
        // Vérification du résultat d'insertion
        if ($result) {
            // Si l'insertion a réussi, retourner un message de succès
            return [
                'status' => 'success',
                'message' => 'Utilisateur créé avec succès',
            ];
        } else {
            // Si l'insertion échoue, retourner un message d'erreur
            return [
                'status' => 'error',
                'message' => 'Erreur lors de la création de l\'utilisateur',
            ];
        }
    }
    
    
    
    

    // Méthode pour traiter la connexion d'un utilisateur via l'API
    // Méthode pour traiter la connexion d'un utilisateur via l'API et la base de données
public function loginUser($email, $motdepasse) {
    // Prépare les données pour l'appel API
    $data = [
        'email' => $email,
        'motdepasse' => $motdepasse
    ];

    // Appelle l'API avec la méthode POST pour l'action 'login'
    $response = Connexion::callAPI('login', $data, 'POST');

    // Décodage de la réponse
    $responseArray = json_decode($response, true);

    // Vérifie si l'appel API a réussi
    if ($responseArray['status'] === 'success' && isset($responseArray['user'])) {
        return $responseArray['user']; // Retourne les données de l'utilisateur
    }

    // Si l'API échoue, on vérifie dans la base de données en local
    $modeleInternaute = new Internaute($this->pdo);
    $user = $modeleInternaute->getUserByEmail($email); // Récupère l'utilisateur par email depuis la base de données

    if ($user) {
        // Vérifie le mot de passe stocké localement
        $motdepasseStocke = $user['mot_de_passe_internaute'];
        if (password_verify($motdepasse, $motdepasseStocke)) {
            return $user; // Retourne l'utilisateur si la vérification réussit
        }
    }

    // Retourne false si la connexion échoue
    return false;
}


    

    // Fonction pour afficher les informations de l'utilisateur
    public function showAccountPage($userId) {
        $modeleInternaute = new Internaute($this->pdo);
        $user = $modeleInternaute->getUserByEmail($userId);
        
        if ($user) {
            // Appeler l'API pour récupérer des informations supplémentaires (si nécessaire)
            $apiResponse = Connexion::callAPI('get_user', ['user_id' => $userId], 'GET');
            $apiResponseData = json_decode($apiResponse, true);
            
            // Passer les données à la vue
            require 'vue/vueCompte.php';  // Affiche la vue avec les informations de l'utilisateur
        } else {
            echo "Utilisateur non trouvé.";
        }
    }

    // Fonction pour gérer les actions de déconnexion, de suppression et de modification du compte
    public function handleAccountAction($action, $userId, $data = null) {
        $modeleInternaute = new Internaute($this->pdo);

        if ($action === 'logout') {
            // Appeler l'API pour notifier la déconnexion (optionnel)
            Connexion::callAPI('logout', ['user_id' => $userId], 'POST');
            
            // Déconnexion : destruction de la session
            session_unset();
            session_destroy();
            header('Location: vueInscriptionConnexion.php');  // Redirection après déconnexion
            exit;
        }

        if ($action === 'delete_account') {
            // Appeler l'API pour notifier la suppression de l'utilisateur
            $apiResponse = Connexion::callAPI('delete_user', ['user_id' => $userId], 'POST');
            $apiResponseData = json_decode($apiResponse, true);
            
            // Suppression du compte
            $result = $modeleInternaute->deleteUser($userId);
            if ($result) {
                session_unset();
                session_destroy();
                header('Location: vueInscriptionConnexion.php');  // Redirection après suppression
                exit;
            } else {
                echo "Erreur lors de la suppression du compte.";
            }
        }

        if ($action === 'update_account') {
            // Appeler l'API pour notifier la mise à jour du compte
            $apiResponse = Connexion::callAPI('update_user', ['user_id' => $userId, 'data' => $data], 'POST');
            $apiResponseData = json_decode($apiResponse, true);

            // Appel de la méthode pour mettre à jour le compte dans la base de données
            $result = $modeleInternaute->updateUser($userId, $data);
            if ($result) {
                echo json_encode(['status' => 'success', 'message' => 'Compte mis à jour', 'api_response' => $apiResponseData]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Erreur lors de la mise à jour du compte']);
            }
        }
    }
}
?>
