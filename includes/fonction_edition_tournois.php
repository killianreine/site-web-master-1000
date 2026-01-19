<?php
$racine = dirname(__DIR__, 1);
include_once $racine.'/connex.php';
require_once $racine.'/includes/fonction_joueur.php';
require_once $racine.'/includes/fonction_tournois.php';
require_once $racine.'/includes/fonction_equipe.php';

/**
 * Fonction permettant de récupérer l'identifiant d'une édition de tournois
 * @param int $id (l'id du tournois a rechercher)
 * @return array si on a trouvé
 * @param bool $test (si on veut afficher les messages de test ou pas)
 * @return null sinon
 */
function getG04EditionTournoisById(int $id, bool $test=false) {
    $ptrDB = connexion();
    $query = "SELECT * FROM G04_edition_tournois WHERE id_edition = $1";
    pg_prepare($ptrDB, 'reqPrepSelectById', $query);
    $ptrQuery = pg_execute($ptrDB, "reqPrepSelectById", array($id));

    if (isset($ptrQuery)) $resu = pg_fetch_assoc($ptrQuery);
    if (empty($resu)){
        if($test)
        echo "<div class='error'><b>Identifiant d'édition de tournois <u>non valide</u> : $id</b></div>";
        return null;
    }
    else {
        if($test)
        echo "<div class='success'>L'<b>édition de tournois a été trouvée</b></div>";
    }

    pg_free_result($ptrQuery);
    pg_close($ptrDB);
    return $resu;
}

/**
 * Fonction permettant de récupérer toutes les données de la table
 * @param void
 * @return array, le tableau des enregistrements de la table
 */
function getAllG04EditionTournois() : array{
    $ptrDB = connexion();
    $query = "SELECT * FROM G04_edition_tournois ORDER BY id_edition";
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
 * Fonction permettant de vérifier si un tournois existe déjà
 * @param int $id_tournois
 * @param string $date (au format supposé valide)
 * @param string $format (uniquement Simple ou Double)
 * @param int $idVainqueur
 * @param int $idFinalise
 * @return bool l'édition existe ?
 */
function EditiontournoisExistant(int $id_tournois, string $date, string $format, int $idVainqueur, int $idFinaliste) : bool{
    $LETournois = getAllG04EditionTournois();
    foreach($LETournois as $Etournois){
        if($Etournois['id_tournois']==$id_tournois && $Etournois['edi_date']==$date && $Etournois['format']==$format && 
        $Etournois['edi_vainqueur']==$idVainqueur && $Etournois['edi_finaliste']==$idFinaliste)
            return TRUE;
    }
    return FALSE;
}

/**
 * Fonction permettant d'insérer un nouveau tournois
 * @param int $idtournois
 * @param string $date (au format supposé valide)
 * @param string $format (uniquement Simple ou Double)
 * @param int $idVainqueur
 * @param int $idFinalise
 * @param bool $test (si on veut afficher les messages de test ou pas)
 * @return bool l'insertion s'est bien passée ?
 */
function insertG04EditionTournois(int $idTournois, string $date, string $format, int $idVainqueur, int $idFinaliste, bool $test=false) : bool{
    // Si les identifiants sont les mêmes...
    if($idVainqueur===$idFinaliste){
        if($test)
        echo("<div class='error'>Les <b>identifiants de <u>vainqueur et de finaliste</u> ne peuvent pas être les mêmes</b></div>");
        return FALSE;
    }

    // Le format donné n'est pas valide
    if ($format!=="Double" && $format!=="Simple"){
        if($test)
        echo("<div class='error'>Le <b>format</b> de votre l'édition à insérer <b>n'est pas valide</b></div>");
        return FALSE;
    }

    // Si le tournois existe déjà
    if(EditiontournoisExistant($idTournois, $date, $format, $idVainqueur, $idFinaliste)) { 
        if($test)
        echo("<div class='error'><b>Édition déjà existante</b></div>");
        return FALSE;
    }

    // Si le tournois existe pas
    $tournois = getG04TournoisById($idTournois);
    if(!$tournois){
        if($test)
        echo "<div class='error'>Le <b>tournois existe pas</b>...</div>";
        return FALSE;
    }
    
    // Action si le format est Simple
    if($format==="Simple"){

        // Si un des joueur n'existe pas
        if(getG04JoueurById($idVainqueur)==null || getG04JoueurById($idFinaliste)==null){
            if($test)
            echo("<div class='error'>Le <b>joueur <u>vainqueur et/ou finaliste</u> n'existe pas</b>...</div>");
            return FALSE;
        }
    }

    // Action si le format est Double
    if($format==="Double"){

        // Si une des équipes n'existe pas
        if(getG04EquipeById($idVainqueur)==null || getG04EquipeById($idFinaliste)==null){
            if($test)
            echo("<div class='error'>L'<b>équipe <u>vainqueur et/ou finaliste</u> n'existe pas</b>...</div>");
            return FALSE;
        }
        if(joueurEnCommun($idVainqueur, $idFinaliste)){
            if($test)
            echo("<div class='error'><b>Édition non modifiable car un joueur est dans les 2 équipes</b></div>");
            return FALSE;
        }
    }

    $ptrDB = connexion();
    $query = "INSERT INTO G04_edition_tournois (id_tournois, edi_date, format, edi_vainqueur, edi_finaliste) VALUES ($1, $2, $3, $4, $5);";
    pg_prepare($ptrDB, "reqPrepInsert", $query);
    $ptrQuery = pg_execute($ptrDB, "reqPrepInsert", array($idTournois, $date, $format, $idVainqueur, $idFinaliste));

    if (!$ptrQuery) {
        if($test)
        echo "<div class='error'><b>Erreur</b> lors de l'insertion de l'édition d'un tournoi.</div>";
        return FALSE;
    }
    else{
        if($test)
        echo "<div class='success'>L'<b>édition de tournoi a été insérée</b></div>";
    }

    pg_free_result($ptrQuery);
    pg_close($ptrDB);
    return TRUE;
}

/**
 * Fonction permettant de réinitialiser la séquence id_edition_tournois
 * En gros on récupère la valeur max de l'id
 * @param void
 * @return void
 */
function resetSequenceEditionTournois() : void {
    $ptrDB = connexion();
    // Récupérer le nom de la séquence
    $querySeq = "SELECT pg_get_serial_sequence('G04_edition_tournois', 'id_edition') AS seq_name;";
    $result = pg_query($ptrDB, $querySeq);
    $row = pg_fetch_assoc($result);
    $sequenceName = $row['seq_name'];

    if ($sequenceName) {
        // On met à jour la séquence
        $queryReset = "SELECT setval('$sequenceName', (SELECT COALESCE(MAX(id_edition), 0) FROM G04_edition_tournois) + 1, false);";
        pg_query($ptrDB, $queryReset);
    }

    pg_close($ptrDB);
}

/**
 * Fonction permettant de supprimer un enregistrement de la table edition_tournois
 * @param int 
 *      $idEdition (l'id de l'édition a supprimer)
 * @param bool 
 *      $test (si on veut afficher les messages de test ou pas)
 * @return void
 */
function deleteG04EditionTournois(int $idEdition, bool $test=false) : void {
    $edition = getG04EditionTournoisById($idEdition);
    if (!empty($edition)) {
        $ptrDB = connexion();
        $query = "DELETE FROM G04_edition_tournois WHERE id_edition = $1;";
        pg_prepare($ptrDB, 'prepDeleteEdition', $query);
        pg_execute($ptrDB, 'prepDeleteEdition', array($idEdition));
        if($test) echo "<div class='success'>L'<b>édition</b> de tournoi n°$idEdition <b>a bien été supprimée</b> ! </div>";
        resetSequenceEditionTournois();
    } else {
        if ($test) echo "<div class='error'><b>Édition de tournoi inexistante</b>, la table n'a pas été modifiée.</div>";
    }
}

/**
 * Fonction permettant de mettre à jour un enregistrement contenu dans la table edition_tournois
 * @param array $ETournois
 *      Les informations de l'édition du tournois modifié (on indique l'id)
 * @param bool $test
 *     Si on veut afficher les messages de test ou pas
 * @return void
 */
function updateG04EditionTournois(array $ETournois, bool $test=false) : void {
    $editionTournoisExistant = getG04EditionTournoisById($ETournois['id_edition']);
    
    if ($editionTournoisExistant === null) {
        echo("<div class='error'>Impossible de mettre à jour : l'édition avec l'ID " . $ETournois['id_edition'] . " n'existe pas.</div>");
        return;
    }

    if($ETournois['edi_vainqueur'] === $ETournois['edi_finaliste']) {
        if($test)
        echo("<div class='error'>Les <b>identifiants de <u>vainqueur et de finaliste</u> ne peuvent pas être les mêmes</b></div>");
        return;
    }

    if(EditiontournoisExistant($ETournois['id_tournois'], $ETournois['edi_date'], $ETournois['format'], $ETournois['edi_vainqueur'], $ETournois['edi_finaliste'])) { 
        if($test)
        echo("<div class='error'><b>Édition déjà existante</b></div>");
        return;
    }

    if(joueurEnCommun($ETournois['edi_vainqueur'], $ETournois['edi_finaliste'])){
        if($test)
        echo("<div class='error'><b>Édition non modifiable car un joueur est dans les 2 équipes</b></div>");
        return;
    }
    
    $ptrDB = connexion();
    $query = "UPDATE G04_edition_tournois SET id_tournois=$2, edi_date=$3, format=$4, edi_vainqueur=$5, edi_finaliste=$6 WHERE id_edition = $1";
    pg_prepare($ptrDB, "reqPrepUpdate", $query);
    $ptrQuery = pg_execute($ptrDB, "reqPrepUpdate", array($ETournois['id_edition'], $ETournois['id_tournois'], $ETournois['edi_date'], $ETournois['format'], $ETournois['edi_vainqueur'], $ETournois['edi_finaliste']));
    
    if ($ptrQuery === false && $test) {
        echo("<div class='error'>MAJ de la table Joueur non-effectuée</div>");
    }
    
    else{
        if ($test) echo("<div class='success'>L'<b>édition de tournois a été mise à jour</b></div>");
    }

    pg_free_result($ptrQuery);
    pg_close($ptrDB);
}


/**
 * Fonction supplémentaires pour affichage des détails et pour les conditionnels
 */

/**
 * Fonction permettant de récupérer les éditions en fonction de l'id d'un joueur
 * @param int 
 *      $idJoueur l'identifiant du joueur
 * @return array
 *      Le tableau contenant les éditions simples dont un joueur a été finaliste / vainqueur
 */
function getEditionTournoisByIdTournois(int $id_tournois) : array {
    $ptrDB = connexion();
    $query = "SELECT * FROM G04_edition_tournois WHERE id_tournois = $1";
    pg_prepare($ptrDB, 'reqPrepSelectByIdTournois', $query);
    $ptrQuery = pg_execute($ptrDB, 'reqPrepSelectByIdTournois', array($id_tournois));
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
 * Fonction permettant de récupérer les éditions en fonction de l'id d'un joueur
 * @param int 
 *      $idJoueur l'identifiant du joueur
 * @return array
 *      Le tableau contenant les éditions doubles dont un joueur a été finaliste / vainqueur
 */
function getEditionTournoisSimpleByIdJoueur(int $idJoueur) : array {
    $ptrDB = connexion();
    $query = "SELECT * FROM G04_edition_tournois WHERE (edi_vainqueur = $1 OR edi_finaliste = $1) AND format = 'Simple'";
    pg_prepare($ptrDB, 'reqPrepSelectByIdJoueur', $query);
    $ptrQuery = pg_execute($ptrDB, 'reqPrepSelectByIdJoueur', array($idJoueur));
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

function getEditionTournoisDoubleByIdJoueur(int $idJoueur): array {
    $edition = getAllG04EditionTournois();
    $resu = [];

    foreach ($edition as $edi) {
        if ($edi['format'] == 'Double') {
            $vainqueur = getG04EquipeById($edi['edi_vainqueur']);
            $finaliste = getG04EquipeById($edi['edi_finaliste']);

            if (
                $vainqueur['eq_joueur1'] == $idJoueur || $vainqueur['eq_joueur2'] == $idJoueur ||
                $finaliste['eq_joueur1'] == $idJoueur || $finaliste['eq_joueur2'] == $idJoueur
            ) {
                $resu[] = $edi;
            }
        }
    }

    return $resu;
}

function joueurEnCommun(int $eq1, int $eq2) : bool{
    $equipe1 = getG04EquipeById($eq1);
    $equipe2 = getG04EquipeById($eq2);
    if(
        $equipe1['eq_joueur1']==$equipe2['eq_joueur2'] ||
        $equipe1['eq_joueur2']==$equipe2['eq_joueur1'] ||
        $equipe1['eq_joueur1']==$equipe2['eq_joueur1'] ||
        $equipe1['eq_joueur2']==$equipe2['eq_joueur2']
    ){
        return true;
    }
    return false;
}


?>