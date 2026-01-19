<?php
// Configuration de la base de données
$_ENV['dbHost'] = 'localhost';
$_ENV['dbName'] = 'TA_BASE_DE_DONNEES';
$_ENV['dbUser'] = 'USER';
$_ENV['dbPasswd'] = 'TON_MOT_DE_PASSE';

/**
 * Fonction pour se connecter à PostgreSQL
 * @return resource|false : retourne le lien de connexion ou false si échec
 */
if (!function_exists('connexion')) {
    function connexion() {
        $strConnex = "host=" . $_ENV['dbHost'] .
                     " dbname=" . $_ENV['dbName'] .
                     " user=" . $_ENV['dbUser'] .
                     " password=" . $_ENV['dbPasswd'];

        // Connexion
        $ptrDB = pg_connect($strConnex);

        if (!$ptrDB) {
            // Gestion d'erreur simple
            die("Erreur : Impossible de se connecter à la base PostgreSQL !");
        }

        return $ptrDB;
    }
}
?>

