<?php
$racine = dirname(__DIR__, 1);
include_once $racine.'/connex.php';
require_once 'conditionnels.php';
/**
 * Fonction permettant de récupérer un tournois à partir de son identifiant
 * @param int $id (l'id du tournois à récupérer)
 * @return array le tableau qui contient l'enregistrement
 * @param bool $test (si on veut afficher les messages de test ou pas)
 * @return null si aucun tournois trouvé
 */
function getG04TournoisById(int $id, bool $test=false) {
    $ptrDB = connexion();
    $query = "SELECT * FROM G04_TOURNOIS WHERE id_tournois = $1";
    pg_prepare($ptrDB, 'reqPrepSelectById', $query);
    $ptrQuery = pg_execute($ptrDB, "reqPrepSelectById", array($id));

    if (isset($ptrQuery)) $resu = pg_fetch_assoc($ptrQuery);
    if (empty($resu)){
        if($test)
        echo("<div class='error'><b>Identifiant de tournois non valide</b> : $id </div>");
        $resu =  null;
    }
    else{
        if($test)
        echo("<div class='success'>Le <b>tournois a été trouvé</b> </div>");
    }

    pg_free_result($ptrQuery);
    pg_close($ptrDB);
    return $resu;
}

/**
 * Fonction qui permet de récupérer toutes les informations de la table Tournois
 * @param void
 * @return array (le tableau contenant les enregistrements)
 */
function getAllG04Tournois() : array{
    $ptrDB = connexion();
    $query = "SELECT * FROM G04_tournois ORDER BY id_tournois"; 
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
 * Fonction permettant de savoir si un tournois existe déjà dans la table
 * @param string $nomTournois
 * @param string $surfaceTournois (la table n'accepte que Dur ou Moquette)
 * @param string $lieuTournois 
 * @return bool le tournois existe ?
 */
function tournoisExistant(string $nomTournois, string $surfaceTournois, string $lieuTournois) : bool{
    $LTournois = getAllG04Tournois();
    foreach($LTournois as $tournois){
        if($tournois['tour_nom']==$nomTournois && $tournois['tour_surface']==$surfaceTournois && $tournois['tour_lieu']==$lieuTournois)
            return true;
    }
    return false;
}

/**
 * Fonction permettant d'ajouter un nouveau tournois dans la table
 * @param string $nomTournois
 * @param string $surfaceTournois (la table n'accepte que Dur ou Moquette)
 * @param string $lieuTournois 
 * @param bool $test (si on veut afficher les messages de test ou pas)
 * @return bool pour savoir si l'insertion a eu lieue
 */
function insertG04Tournois(string $nomTournois, string $surfaceTournois, string $lieuTournois, bool $test=false) : bool {
    if (!tournoisExistant($nomTournois, $surfaceTournois, $lieuTournois)) {
        $ptrDB = connexion(); 
        $query = "INSERT INTO G04_TOURNOIS (tour_nom, tour_surface, tour_lieu) VALUES ($1, $2, $3);";
        pg_prepare($ptrDB, 'prepInsert', $query);
        $ptrQuery = pg_execute($ptrDB, 'prepInsert', array($nomTournois, $surfaceTournois, $lieuTournois));

        if ($ptrQuery === false) {
            if($test)
            echo "<div class='error'><b>Erreur</b> lors de l'insertion du tournoi.</div>";
            pg_close($ptrDB);
            return false; 
        }
        if($test)
        echo "<div class='success'><b>Tournoi ajouté</b> à la table !</div>";
        pg_free_result($ptrQuery); 
        pg_close($ptrDB); 
        return true; 
    } else {
        if($test)
        echo "<div class='error'>Le <b>tournoi existe déjà</b>.</div>";
        return false;
    }
}

/**
 * Fonction pour réinitialiser la séquence de l'id_tournois
 * @param void
 * @return void
 * @description Cette fonction réinitialise la séquence de l'id_équipe dans la table G04_TOURNOIS.
 */
function resetSequenceTournois() : void {
    $ptrDB = connexion();
    // Récupérer le nom de la séquence
    $querySeq = "SELECT pg_get_serial_sequence('G04_TOURNOIS', 'id_tournois') AS seq_name;";
    $result = pg_query($ptrDB, $querySeq);
    $row = pg_fetch_assoc($result);
    $sequenceName = $row['seq_name'];

    if ($sequenceName) {
        // On met à jour seulement si la séquence existe
        $queryReset = "SELECT setval('$sequenceName', (SELECT COALESCE(MAX(id_tournois), 0) FROM G04_TOURNOIS) + 1, false);";
        pg_query($ptrDB, $queryReset);
    }

    pg_close($ptrDB);
}

/**
 * Fonction permettant de supprimer un enregistrement 
 * @param int $idTournois
 * @param bool $test (si on veut afficher les messages de test ou pas)
 * @return void 
 * @description on affiche des messages en fonction de comment ça c'est passé
 */
function deleteG04Tournois(int $idTournois, bool $test=false) : void{
    $tournois = getG04TournoisById($idTournois);
    if(!empty($tournois)){
        if(tournoisPossedeEdition($idTournois)){
            if($test)
            echo "<div class='error'><b>Suppression impossible</b>, le tournois possède des éditions...</div>";
            return;
        }
        $ptrDB = connexion();
        $query = "DELETE FROM G04_TOURNOIS WHERE id_tournois=$1;";
        pg_prepare($ptrDB, 'prepDelete', $query);
        $ptrQuery = pg_execute($ptrDB, 'prepDelete', array($idTournois));
        if($ptrQuery === false) {
            if($test)
            echo "<div class='error'><b>Erreur</b> lors de la suppression du tournois.</div>";
        }
        else if($test)
        echo "<div class='success'>Le tournois n°$idTournois a bien été supprimé ! </div>";
        resetSequenceTournois();
    }
    else if($test) 
    echo "<div class='error'><b>Tournois inexistant</b>, la table n'a pas été modifiée</div>";
}

/**
 * Fonction permettant de mettre à jour / modifier un enregistrement de la table tournois
 * @param array $tournois
 *      Il contient les informations du tournois (lieu, surface, identifiant, ...)
 * * @param bool $test (si on veut afficher les messages de test ou pas)
 * @return void 
 */
function updateG04Tournois(array $tournois, bool $test=false) : void{
    $tournoisExistant = getG04TournoisById($tournois['id_tournois']);
    
    if ($tournoisExistant === null) {
        if($test)
        echo("<div class='error'><u>Impossible de mettre à jour :</u> le <b>tournois avec l'ID " . $tournois['id_tournois'] . " n'existe pas</b>.</div>");
        return;
    }
    if($tournoisExistant['tour_nom'] == $tournois['tour_nom'] && $tournoisExistant['tour_surface'] == $tournois['tour_surface'] && $tournoisExistant['tour_lieu'] == $tournois['tour_lieu']) {
        if($test)
        echo "<div class='error'>Le <b>tournois n°" . $tournois['id_tournois'] . " : " . $tournois['tour_nom'] . " (surface : " . $tournois['tour_surface'] . ") qui a lieu à ". $tournois['tour_lieu']. " n'a pas été modifié</b>.</div>";
        return;
    }

    $ptrDB = connexion();
    $query = "UPDATE G04_tournois SET tour_nom=$2, tour_surface=$3, tour_lieu=$4 WHERE id_tournois = $1";
    pg_prepare($ptrDB, "reqPrepUpdate", $query);
    $ptrQuery = pg_execute($ptrDB, "reqPrepUpdate", array($tournois['id_tournois'], $tournois['tour_nom'], $tournois['tour_surface'], $tournois['tour_lieu']));
    
    // Si la requête n'a pas aboutie
    if ($ptrQuery === false) {
        if($test)
        echo "<div class='error'><b>MAJ de la table Tournois <u>non-effectuée</u></b></div>";
    }
    // Si la requête s'est déroulée avec succès
    else{
        if($test)
        echo "<div class='success'>Le <b>tournois n°" . $tournois['id_tournois'] . " : " . $tournois['tour_nom'] . " (surface : " . $tournois['tour_surface'] . ") qui a lieu à ". $tournois['tour_lieu']. " a été mis à jour</b>.</div>";
    }

    pg_free_result($ptrQuery);
    pg_close($ptrDB);
}

?>