<?php
$racine = dirname(__DIR__, 1);
include_once $racine.'/connex.php';
require_once 'conditionnels.php';

/**
 * Fonction pour récupérer un joueur par son id
 * @param int $id
 * @return array 
 * @param bool $test
 * @return null si rien n'a été trouvé
 * @description Cette fonction récupère un joueur de la table G04_JOUEUR en fonction de son id.
 */
function getG04JoueurById(int $id, bool $test=false) {
    $ptrDB = connexion();
    $query = "SELECT * FROM G04_joueur WHERE id_joueur = $1";
    pg_prepare($ptrDB, 'reqPrepSelectById', $query);
    $ptrQuery = pg_execute($ptrDB, "reqPrepSelectById", array($id));

    if (isset($ptrQuery)) $resu = pg_fetch_assoc($ptrQuery);
    if (empty($resu)){
        if($test)
        echo("<div class='error'><b>Identifiant</b> de joueur <b>non valide</b> : $id </div>");
        $resu =  null;
    }
    else {
        if($test)
        echo("<div class='success'>Le joueur a été trouvé : <b>" . $resu['joueur_nom'] . "</b> <b>" . $resu['joueur_prenom'] . "</b> de nationalité <b/>" . $resu['joueur_nationalite'] . "</b></div>");
    }

    pg_free_result($ptrQuery);
    pg_close($ptrDB);

    return $resu;
}


/**
 * Fonction pour récupérer tous les joueurs 
 * @param void
 * @return array
 * @description Cette fonction récupère tous les joueurs de la table G04_JOUEUR.
 */
function getAllG04Joueur() : array {
    $ptrDB = connexion();
    $query = "SELECT * FROM G04_joueur ORDER BY id_joueur";
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
 * Fonction pour insérer un joueur
 * @param string $nom
 * @param string $prenom
 * @param string $nationalite
 * @param bool $test 
 * @return bool si l'insertion s'est faite avec succès
 * @description Cette fonction insère un joueur dans la table G04_JOUEUR en vérifiant d'abord s'il existe déjà.
 */
function insertG04Joueur(string $nom, string $prenom, string $nationalite, bool $test=false) : bool{
    $allInfo = getAllG04Joueur();
    // Vérifier si le joueur existe déjà
    foreach ($allInfo as $info) {
        if ($info['joueur_nom'] == $nom && $info['joueur_prenom'] == $prenom && $info['joueur_nationalite'] == $nationalite) {
            if($test)
            echo("<div class='error'>Le joueur <u><i>existe déjà</i></u> : <b>$nom</b> <b>$prenom</b> de nationalité <b/>$nationalite</b></div>");
            return FALSE;
        }
    }

    $ptrDB = connexion();
    $query = "INSERT INTO G04_JOUEUR (joueur_nom, joueur_prenom, joueur_nationalite) VALUES ($1, $2, $3);";
    pg_prepare($ptrDB, 'reqPrepInsert', $query);
    $ptrQuery = pg_execute($ptrDB, 'reqPrepInsert', array($nom, $prenom, $nationalite));

    if (isset($ptrQuery)) {
        $resu = pg_fetch_assoc($ptrQuery);
    } else {
        if($test)
        echo("<div class='error'>Erreur lors de l'insertion du joueur : <b>$nom</b> <b>$prenom</b> de nationalité <b/>$nationalite</b></div>");
        return FALSE;
    }

    pg_free_result($ptrQuery);
    pg_close($ptrDB);
    if($test)
    echo("<div class='success'>Le joueur a été inséré : <b>$nom</b> <b>$prenom</b> de nationalité <b/>$nationalite</b></div>");
    return TRUE;
}


/**
 * Fonction pour réinitialiser la séquence de l'id_joueur
 * @param void
 * @return void
 * @description Cette fonction réinitialise la séquence de l'id_joueur dans la table G04_JOUEUR.
 * TO DO :
 *  Gérer le cas ou un identifiant ne possède pas de données (ex. supprimée)
 */
function resetSequenceJoueur() :void {
    $ptrDB = connexion();
    // Récupérer le nom de la séquence associée à l'id_joueur
    $querySeq = "SELECT pg_get_serial_sequence('G04_JOUEUR', 'id_joueur') AS seq_name;";
    $result = pg_query($ptrDB, $querySeq);
    $row = pg_fetch_assoc($result);
    $sequenceName = $row['seq_name'];

    // Vérifier si elle existe
    if ($sequenceName) {
        // On la met à jour pour qu'elle prenne en compte le maximum de l'id_joueur en cas de suppression
        $queryReset = "SELECT setval('$sequenceName', (SELECT COALESCE(MAX(id_joueur), 0) FROM G04_JOUEUR) + 1, false);";
        pg_query($ptrDB, $queryReset);
    }

    pg_close($ptrDB);
}

/**
 * Fonction pour supprimer un joueur
 * @param string $nom
 * @param string $prenom
 * @param string $nationalite
 * @param bool $test
 * @return array
 * @description Cette fonction supprime un joueur de la table G04_JOUEUR en fonction de son nom, prénom et nationalité.
 */
function deleteG04Joueur(int $id_joueur, bool $test=false) : void{
    if(getEquipeByJoueurID($id_joueur)){
        if($test)
        echo("<div class='error'>Impossible de supprimer le joueur car il est dans une équipe</div>");
        return;
    }
    if(getEditionTournoisSimpleByIdJoueur($id_joueur) || getEditionTournoisDoubleByIdJoueur($id_joueur)){
        if($test)
        echo("<div class='error'>Impossible de supprimer le joueur car il est dans une édition de tournois</div>");
        return;
    }
    $ptrDB = connexion();
    $query = "DELETE FROM G04_JOUEUR WHERE id_joueur=$1;";
    pg_prepare($ptrDB, 'reqPrepDelete', $query);
    $ptrQuery = pg_execute($ptrDB, 'reqPrepDelete', array($id_joueur));

    if (isset($ptrQuery)) {
        resetSequenceJoueur();
        if($test)
        echo("<div class='success'>Le joueur <b>a bien été <u>supprimé</u></b></div>");
        return; // La connexion est fermée par resetSequenceJoueur
    } else {
        if($test)
        echo("<div class='error'>Erreur lors de la suppression du joueur</div>");
    }

    pg_free_result($ptrQuery);
    pg_close($ptrDB);
}

/**
 * Fonction pour mettre à jour un enregistrement de la table joueur
 * @param array $jour
 *      Un tableau associatif qui contient l'identifiant du joueur à mettre à jour et les infos le concernant,
 *      qu'elles soient mise à jour ou pas.
 * @return void 
 */
function updateAllG04Joueur(array $joueur, bool $test=false) : void{
    $joueurExistant = getG04JoueurById($joueur['id_joueur']);
    
    if ($joueurExistant === null) {
        if($test)
        echo("<div class='success'>Impossible de mettre à jour : le joueur avec l'ID " . $joueur['id_joueur'] . " n'existe pas.</div>");
        return;
    }
    $tousLesJoueurs = getAllG04Joueur();
    foreach($tousLesJoueurs as $joueurCourant){
        if($joueurCourant['joueur_nom'] == $joueur['joueur_nom'] && $joueurCourant['joueur_prenom'] == $joueur['joueur_prenom'] && $joueurCourant['joueur_nationalite'] == $joueur['joueur_nationalite']){
            if($test)
            echo("<div class='error'>Le joueur n'a pas été modifié <b>car il existe déjà</b></div>");
            return;
        }
    }

    $ptrDB = connexion();
    $query = "UPDATE G04_joueur SET joueur_nom=$2, joueur_prenom=$3, joueur_nationalite=$4 WHERE id_joueur = $1";
    pg_prepare($ptrDB, "reqPrepUpdate", $query);
    $ptrQuery = pg_execute($ptrDB, "reqPrepUpdate", array($joueur['id_joueur'], $joueur['joueur_nom'], $joueur['joueur_prenom'], $joueur['joueur_nationalite']));
    
    if ($ptrQuery === false) {
        if($test)
        echo("<div class='error'> MAJ de la table Joueur non-effectuée</div>");
    }
    else{
        if($test)
        echo("<div class='success'>Le joueur a été mis à jour : <b>" . $joueur['joueur_nom'] . "</b> <b>" . $joueur['joueur_prenom'] . "</b> de nationalité <b/>" . $joueur['joueur_nationalite'] . "</b></div>");
    }
    pg_free_result($ptrQuery);
    pg_close($ptrDB);
}

?>