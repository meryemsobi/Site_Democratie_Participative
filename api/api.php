<?php
require_once '../config/Connexion.php';
require_once '../controleur/ControleurGroupe.php';
require_once '../controleur/ControleurInternaute.php';
require_once '../controleur/ControleurDecision.php';
require_once '../controleur/ControleurRole.php';
require_once '../controleur/ControleurVote.php';
require_once '../controleur/ControleurNotification.php';

header('Content-Type: application/json'); // Les réponses seront au format JSON

$action = $_GET['action'] ?? null;

try {
    if ($action) {
        switch ($action) {
            /** Obtenir la liste des groupes d'un utilisateur */
            case 'get_groups':
                $userId = $_GET['user_id'] ?? null;
                if ($userId) {
                    $controleurGroupe = new ControleurGroupe(Connexion::pdo());
                    $controleurGroupe->showGroups($userId);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'ID utilisateur manquant']);
                }
                break;

            /** Créer un utilisateur */
            case 'create_user':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Lire les données provenant de la requête POST
                    $data = $_POST;
            
                    // Vérifier si les données sont valides
                    if (!empty($data) && is_array($data)) {
                        $controleurInternaute = new ControleurInternaute($pdo); // Passer la connexion PDO
                        $result = $controleurInternaute->createUser($data);
            
                        echo json_encode([
                            'status' => $result['status'],
                            'message' => $result['message'],
                        ]);
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'Données utilisateur manquantes']);
                    }
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Méthode incorrecte']);
                }
                break;
            
            case 'login':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Lecture des données
                    $data = $_POST;
                        
                    // Vérification des données
                    if (!empty($data['email']) && !empty($data['motdepasse'])) {
                        // Appel à la méthode loginUser
                        $controleurInternaute = new ControleurInternaute();
                        $user = $controleurInternaute->loginUser($data['email'], $data['motdepasse']);
                            
                        if ($user) {
                            echo json_encode([
                                'status' => 'success',
                                'message' => 'Connexion réussie',
                                'user' => $user
                            ]);
                        } else {
                            echo json_encode([
                                'status' => 'error',
                                'message' => 'Email ou mot de passe incorrect.'
                            ]);
                        }
                    } else {
                        echo json_encode([
                            'status' => 'error',
                            'message' => 'Données utilisateur manquantes.'
                        ]);
                    }
                } else {
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Méthode incorrecte. Utilisez POST.'
                    ]);
                }
                break;
            

            /** Créer un groupe */
            case 'create_group':
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['group_data'])) {
                    $data = json_decode($_POST['group_data'], true);
                    $controleurGroupe = new ControleurGroupe(Connexion::pdo());
                    $controleurGroupe->createGroup($data);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Paramètres ou méthode incorrects']);
                }
                break;

            /** Assigner un rôle */
            case 'assign_role':
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'], $_POST['group_id'], $_POST['role'])) {
                    $params = [
                        'user_id' => intval($_POST['user_id']),
                        'group_id' => intval($_POST['group_id']),
                        'role' => htmlspecialchars($_POST['role']),
                    ];
                    echo Connexion::callAPI('assign_role', $params, 'POST');
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Paramètres manquants']);
                }
                break;

            /** Soumettre une proposition */
            case 'submit_proposal':
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'], $_POST['description'], $_POST['theme_id'], $_POST['user_id'], $_POST['discussion_end_date'], $_POST['budget'])) {
                    
                    // Récupération des données depuis le formulaire
                    $data = [
                        'titre_proposition' => htmlspecialchars($_POST['title']),
                        'description_proposition' => htmlspecialchars($_POST['description']),
                        'id_thème' => intval($_POST['theme_id']),
                        'id_internaute' => intval($_POST['user_id']),
                        'date_fin_discussion' => $_POST['discussion_end_date'],
                        'budget' => floatval($_POST['budget']),
                    ];
            
                    // Appel à la méthode pour créer la proposition
                    try {
                        $controleurProposition = new ControleurProposition(Connexion::pdo());
                        $controleurProposition->createProposal($data);
                        echo json_encode(['status' => 'success', 'message' => 'Proposition créée avec succès']);
                    } catch (Exception $e) {
                        echo json_encode(['status' => 'error', 'message' => 'Échec de la création de la proposition: ' . $e->getMessage()]);
                    }
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Paramètres manquants ou invalides']);
                }
                break;
            
            
            

            /** Supprimer une proposition */
            case 'delete_proposal':
                if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_GET['proposal_id'])) {
                    $proposalId = intval($_GET['proposal_id']);
                    $controleurProposition = new ControleurProposition(Connexion::pdo());
                    $controleurProposition->deleteProposal($proposalId);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'ID de la proposition manquant']);
                }
                break;


            case 'get_decision':
                    if (isset($_GET['id_vote'])) {
                        $id_vote = $_GET['id_vote'];
                            
                        // Instancier le contrôleur et appeler la méthode
                        $controleurDecision = new ControleurDecision(Connexion::pdo());
                        $decision = $controleurDecision->getDecision($id_vote);
                            
                        echo json_encode($decision);
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'ID de vote manquant']);
                    }
                    break;
                                



            /** Ajouter un membre à un groupe */
            case 'add_member':
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'], $_POST['group_id'])) {
                    $controleurGroupe = new ControleurGroupe(Connexion::pdo());
                    $data = [
                        'user_id' => intval($_POST['user_id']),
                        'group_id' => intval($_POST['group_id']),
                    ];
                    $controleurGroupe->addMember($data);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Paramètres manquants']);
                }
                break;

            /** Supprimer un membre d'un groupe */
            case 'remove_member':
                if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_GET['user_id'], $_GET['group_id'])) {
                    $controleurGroupe = new ControleurGroupe(Connexion::pdo());
                    $data = [
                        'user_id' => intval($_GET['user_id']),
                        'group_id' => intval($_GET['group_id']),
                    ];
                    $controleurGroupe->removeMember($data);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Paramètres manquants']);
                }
                break;

            /** Mettre à jour un rôle */
            case 'update_role':
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'], $_POST['group_id'], $_POST['new_role'])) {
                    $controleurRole = new ControleurRole(Connexion::pdo());
                    $data = [
                        'user_id' => intval($_POST['user_id']),
                        'group_id' => intval($_POST['group_id']),
                        'new_role' => htmlspecialchars($_POST['new_role']),
                    ];
                    $controleurRole->updateRole($data);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Paramètres manquants']);
                }
                break;

            /** Créer un vote */
            case 'create_vote':
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vote_data'])) {
                    $data = json_decode($_POST['vote_data'], true);
                    $controleurVote = new ControleurVote(Connexion::pdo());
                    $controleurVote->createVote($data);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Paramètres manquants']);
                }
                break;

            /** Obtenir les résultats d'un vote */
            case 'get_vote_results':
                if (isset($_GET['vote_id'])) {
                    $voteId = intval($_GET['vote_id']);
                    $controleurVote = new ControleurVote(Connexion::pdo());
                    $controleurVote->getVoteResults($voteId);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'ID du vote manquant']);
                }
                break;

            /** Signaler une proposition */
            case 'report_proposal':
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['proposal_id'], $_POST['reason'])) {
                    $controleurProposition = new ControleurProposition(Connexion::pdo());
                    $data = [
                        'proposal_id' => intval($_POST['proposal_id']),
                        'reason' => htmlspecialchars($_POST['reason']),
                    ];
                    $controleurProposition->reportProposal($data);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Paramètres manquants']);
                }
                break;

            /** Notifications */
            case 'send_notification':
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'], $_POST['message'])) {
                    $controleurNotification = new ControleurNotification();
                    $data = [
                        'user_id' => intval($_POST['user_id']),
                        'message' => htmlspecialchars($_POST['message']),
                    ];
                    $controleurNotification->sendNotification($data);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Paramètres manquants']);
                }
                break;
        
            case 'get_notifications':
                if (isset($_GET['user_id'])) {
                    $userId = intval($_GET['user_id']);
                    $controleurNotification = new ControleurNotification();
                    echo $controleurNotification->getNotifications($userId);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'ID utilisateur manquant']);
                }
                break;

            /** Obtenir les propositions */
            case 'get_proposals':
                $controleurProposition = new ControleurProposition(Connexion::pdo());
                $controleurProposition->getAllProposals();
                break;
            

            /** Déconnexion */
            case 'logout':
                if (isset($_GET['user_id'])) {
                    $userId = intval($_GET['user_id']);
                    $controleurInternaute = new ControleurInternaute();
                    $controleurInternaute->logout($userId);
                    echo json_encode(['status' => 'success', 'message' => 'Déconnexion réussie']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'ID utilisateur manquant']);
                }
                break;

            /** Modifier un compte utilisateur */
            case 'modify_account':
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'], $_POST['new_data'])) {
                    $data = json_decode($_POST['new_data'], true);
                    $userId = intval($_POST['user_id']);
                    $controleurInternaute = new ControleurInternaute();
                    $controleurInternaute->modifyAccount($userId, $data);
                    echo json_encode(['status' => 'success', 'message' => 'Compte modifié avec succès']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Paramètres manquants']);
                }

                break;

            /** Supprimer un compte utilisateur */
            case 'delete_account':
                if (isset($_GET['user_id'])) {
                    $userId = intval($_GET['user_id']);
                    $controleurInternaute = new ControleurInternaute();
                    $controleurInternaute->deleteAccount($userId);
                    echo json_encode(['status' => 'success', 'message' => 'Compte supprimé avec succès']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'ID utilisateur manquant']);
                }
                break;

            /** Action non reconnue */
            default:
                echo json_encode(['status' => 'error', 'message' => 'Action non reconnue']);
                break;
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Action manquante']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
