<?php
$racine = dirname(__DIR__, 1);
include_once $racine.'/connex.php';
require_once $racine.'/includes/fonction_joueur.php';

/**
 * Fonction pour récupérer une équipe par son id
 * @param int $id
 * @return array
 * @param bool $test
 * @return null si rien n'est trouvé
 * @description Cette fonction récupère une équipe de la table G04_EQUIPE en fonction de son id.
 */
function getG04EquipeById(int $id, bool $test=false) {
    $ptrDB = connexion();
    $query = "SELECT * FROM G04_equipe WHERE id_equipe = $1";
    pg_prepare($ptrDB, 'reqPrepSelectById', $query);
    $ptrQuery = pg_execute($ptrDB, "reqPrepSelectById", array($id));

    if (isset($ptrQuery)) $resu = pg_fetch_assoc($ptrQuery);
    if (empty($resu)){
        if($test)
        echo "<div class='error'>Identifiant d'<b>équipe non valide</b> : $id</div>";
        $resu=null;
    }
    else {
        if($test)
        echo "<div class='success'>L'<b>équipe a été trouvée</b></div>";
    }

    pg_free_result($ptrQuery);
    pg_close($ptrDB);
    return $resu;
}

/**
 * Fonction permettant de sélectionner tous les enregistrement des équipes
 * @param void
 * @return array le tableau contenant tous les enregistrements de la table
 */
function getAllG04Equipe() : array{
    $ptrDB = connexion();
    $query = "SELECT * FROM G04_equipe ORDER BY id_equipe";
    pg_prepare($ptrDB, "reqPrepSelectAll", $query);
    $ptrQuery = pg_execute($ptrDB, "reqPrepSelectAll", array());
    $resu = array();

    if (isset($ptrQuery)) {
        while ($row = pg_fetch_assoc($ptrQuery)) {
            $resu[] = $row;
        }
    }

    pg_free_result($ptrQuery);
    pg_close($ptrDB);
    return $resu;
}

/**
 * Fonction pour insérer une nouvelle équipe
 * @param int $id_joueur1
 * @param int $id_joueur2
 * @param bool $test
 * @return array
 * @description Cette fonction insère une nouvelle équipe dans la table G04_EQUIPE si :
 *      - l'équipe existe déjà
 *      - les deux joueur existent
 *      - les deux joueurs sont déjà dans la même équipe
 */
function insertG04Equipe(int $id_joueur1, int $id_joueur2, bool $test=false) :bool{
    // On récupère les deux joueurs et toutes les équipes
    $joueur1 = getG04JoueurById($id_joueur1);
    $joueur2 = getG04JoueurById($id_joueur2);
    $allEquipe = getAllG04Equipe();

    // On vérifie si les deux existe 
    if (empty($joueur1) || empty($joueur2)){
        if($test)
        echo("<div class='error'>Au moins <b>un des joueurs n'existe pas</b></div>");
        return FALSE;
    }

    // On vérifie si les deux joueurs sont différents
    if($id_joueur1 === $id_joueur2){
        if($test)
         echo("<div class='error'><b>Les deux joueurs doivent <u>être différents</u></b>...</div>");
         return FALSE;
    }

    // On vérifie si l'équipe n'est pas déjà dans la table
    foreach($allEquipe as $equipe){
        if(($equipe['eq_joueur1']==$id_joueur1 && $equipe['eq_joueur2']==$id_joueur2) || 
        ($equipe['eq_joueur2']==$id_joueur1 && $equipe['eq_joueur1']==$id_joueur2)){
            if($test)
            echo("<div class='error'>L'équipe <b>existe déjà</b>.</div>");
            return FALSE;
        }
    }

    $ptrDB = connexion();
    $query = "INSERT INTO G04_EQUIPE (eq_joueur1, eq_joueur2) VALUES ($1, $2);";
    pg_prepare($ptrDB, 'reqPrepInsert', $query);
    $ptrQuery = pg_execute($ptrDB, 'reqPrepInsert', array($id_joueur1, $id_joueur2));

    if (!isset($ptrQuery)) {
        if($test)
        echo("<div class='error'><b>Erreur</b> lors de l'insertion d'une équipe !</p>");
        return FALSE;
    }
    else {
        if($test)
        echo("<div class='success'>L'équipe a été insérée : <b>$id_joueur1</b> et <b>$id_joueur2</b></div>");
    }

    pg_free_result($ptrQuery);
    pg_close($ptrDB);
    return TRUE;
}

/**
 * Fonction pour réinitialiser la séquence de l'id_equipe
 * @param void
 * @return void
 * @description Cette fonction réinitialise la séquence de l'id_équipe dans la table G04_EQUIPE.
 */
function resetSequenceEquipe() : void {
    $ptrDB = connexion();
    // Récupérer le nom de la séquence
    $querySeq = "SELECT pg_get_serial_sequence('G04_EQUIPE', 'id_equipe') AS seq_name;";
    $result = pg_query($ptrDB, $querySeq);
    $row = pg_fetch_assoc($result);
    $sequenceName = $row['seq_name'];

    if ($sequenceName) {
        // On met à jour
        $queryReset = "SELECT setval('$sequenceName', (SELECT COALESCE(MAX(id_equipe), 0) FROM G04_EQUIPE) + 1, false);";
        pg_query($ptrDB, $queryReset);
    }

    pg_close($ptrDB);
}

/**
 * Fonction permettant de supprimer un enregistrement d'une équipe en fonction de son identifiant
 * @param int $idEquipe (l'id de l'équipe que l'on souhaite supprimer)
 * @param bool $test (pour afficher les messages d'erreurs)
 * @return void
 */
function deleteG04Equipe(int $idEquipe, bool $test=false) : void{
    $equipeExistante = getG04EquipeById($idEquipe);
    
    if ($equipeExistante === null) {
        if($test)
        echo("<div class='error'><b>Impossible de mettre à jour</b> : l'équipe avec l'ID " . $idEquipe . " n'existe pas.</div>");
        return;
    }

    $ptrDB = connexion();
    $query = "DELETE FROM G04_EQUIPE WHERE id_equipe=$1";
    pg_prepare($ptrDB, 'deleteEq', $query);
    $ptrQuery = pg_execute($ptrDB, 'deleteEq', array($idEquipe));

    if (isset($ptrQuery)) {
        resetSequenceEquipe(); // Connexion fermée par cette fonction
        if($test)
        echo("<div class='success'>L'<b>équipe a été supprimé</b> : Équipe <b>$idEquipe</b></div>");
        return;
    } else {
        if($test)
        echo "<div class='error'><b>Erreur</b> lors de la suppression d'une équipe</div>";
    }

    pg_free_result($ptrQuery);
    pg_close($ptrDB);
}

/**
 * Fonction permettant de mettre à jour une équipe
 * @param array $equipe
 *      Le tableau qui contient tous les infos d'une équipe
 * @param bool $test
 *     Pour afficher les messages d'erreurs
 * @return void
 */
function updateG04Equipe(array $equipe, bool $test=false) : void{
    $equipeExistante = getG04EquipeById($equipe['id_equipe']);
    
    if ($equipeExistante === null) {
        if($test)
        echo("<div class='error'><b>Impossible de mettre à jour</b> : l'équipe avec l'ID " . $equipe['id_equipe'] . " n'existe pas.</div>");
        return;
    }
    if($equipe['eq_joueur1'] === $equipe['eq_joueur2']){
        if($test)
        echo("<div class='error'><b>Les deux joueurs doivent <u>être différents</u></b>...</div>");
        return;
    }
    // On vérifie si l'équipe n'est pas déjà dans la table
    $allEquipe = getAllG04Equipe();
    foreach($allEquipe as $equipeExistante){
        if(($equipeExistante['eq_joueur1']==$equipe['eq_joueur1'] && $equipeExistante['eq_joueur2']==$equipe['eq_joueur2']) || 
        ($equipeExistante['eq_joueur2']==$equipe['eq_joueur1'] && $equipeExistante['eq_joueur1']==$equipe['eq_joueur2'])){
            if($test)
            echo("<div class='error'>L'équipe <b>existe déjà</b>.</div>");
            return;
        }
    }

    $ptrDB = connexion();
    $query = "UPDATE G04_equipe SET eq_joueur1=$2, eq_joueur2=$3 WHERE id_equipe = $1";
    pg_prepare($ptrDB, "reqPrepUpdate", $query);
    $ptrQuery = pg_execute($ptrDB, "reqPrepUpdate", array($equipe['id_equipe'], $equipe['eq_joueur1'], $equipe['eq_joueur2']));
    
    if ($ptrQuery === false) {
        if($test)
        echo "<div class='error'>MAJ de la table équipe non-effectuée</div>";
    }
    else {
        if($test)
        echo "<div class='success'><b>Mise a jour</b> de la table équipe <b>effectuée</b>.</div>";
    }
    pg_free_result($ptrQuery);
    pg_close($ptrDB);
}

// Les fonctions utilisées pour les conditionnels

/**
 * Sélection d'une équipe en fonction de l'identifiant d'un joueur
 * @param int $idJoueur 
 *      l'identifiant du joueur 
 * @return array
 *      un tableau contenant les résultats de la requête
 */
function getEquipeByJoueurID(int $idJoueur) : array {
    $ptrDB = connexion();
    $query = "SELECT * FROM G04_EQUIPE WHERE eq_joueur1 = $1 OR eq_joueur2 = $1";
    pg_prepare($ptrDB, 'reqPrepSelectById', $query);
    $ptrQuery = pg_execute($ptrDB, "reqPrepSelectById", array($idJoueur));
    $resu = array();

    if (isset($ptrQuery)) {
        while ($row = pg_fetch_assoc($ptrQuery)) {
            $resu[] = $row;
        }
    }

    pg_free_result($ptrQuery);
    pg_close($ptrDB);
    return $resu;
}

?>