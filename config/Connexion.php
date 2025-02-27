<?php
class Connexion {
    // Attributs statiques pour la configuration
    private static $hostname = 'localhost';
    private static $database = 'smouss6';
    private static $login = 'smouss6';
    private static $password = 'MyUPsACLAYPass@91';
    private static $tabUTF8 = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");

    // Attribut statique pour la connexion PDO
    private static $pdo = null;

    // Définition de la constante URL_API
    const URL_API = 'https://webdev.iut-orsay.fr/smouss6/partie-web-MVC/api/api.php/';

    // Méthode pour obtenir la connexion PDO
    public static function pdo() {
        if (self::$pdo === null) {
            self::connect();
        }
        return self::$pdo;
    }

    // Méthode pour initialiser la connexion PDO
    private static function connect() {
        $h = self::$hostname;
        $d = self::$database;
        $l = self::$login;
        $p = self::$password;
        $t = self::$tabUTF8;

        try {
            self::$pdo = new PDO("mysql:host=$h;dbname=$d;charset=utf8", $l, $p, $t);
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    }

    // Fonction pour effectuer un appel API
    public static function callAPI($action, $data = null, $method = 'GET') {
        $url = self::URL_API;
    
        // Si l'action est passée en paramètre, on l'ajoute aux données
        if ($data !== null) {
            $data['action'] = $action; // Assurez-vous que l'action est incluse dans les données
        } else {
            $data = ['action' => $action];
        }
    
        // Initialisation de cURL
        $ch = curl_init();
    
        // Configuration de la méthode POST
        if ($method == 'POST') {
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); // Données encodées en JSON
        } elseif ($method == 'GET') {
            curl_setopt($ch, CURLOPT_URL, $url . '?' . http_build_query($data)); // Ajout des paramètres GET
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        }
    
        // Exécution de la requête cURL
        $response = curl_exec($ch);
    
        // Gestion des erreurs cURL
        if (curl_errno($ch)) {
            return json_encode(['status' => 'erreur', 'message' => curl_error($ch)]);
        }
    
        curl_close($ch);
    
        // Décodage de la réponse JSON
        $rep = json_decode($response, true);
    
        return json_encode(['status' => 'success', 'reponse' => $rep]);
    }
    
}
?>
