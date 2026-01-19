<?php 

$racine = dirname(__DIR__, 1);
require_once $racine.'/connex.php';
require_once $racine.'/includes/fonction_joueur.php';
require_once $racine.'/includes/fonction_tournois.php';
require_once $racine.'/includes/fonction_edition_tournois.php';
require_once $racine.'/includes/fonction_equipe.php';

/**
 * Fonction pour récupérer une équipe par son id
 * @param int $id
 * @return array
 * @param bool $test
 * @return null si rien n'est trouvé
 * @description Cette fonction récupère une équipe selon l'id du joueur
 */
function joueurDansEquipe($idJoueur) : bool {
    $equipe = getEquipeByJoueurID($idJoueur);
    if (isset($equipe)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Fonction permettant de récupérer les éditions où le joeur est finaliste ou vainqueur
 * @param int 
 *      $idJoueur l'identifiant du joueur sur lequel on fait la requête
 * @return bool
 *      Le joueur est-il dans une édition ?
 */
function joueurDansEdition($idJoueur) : bool{
    $eSimple = getEditionTournoisSimpleByIdJoueur($idJoueur);
    $eDouble = getEditionTournoisDoubleByIdJoueur($idJoueur);
    if (isset($eSimple) || isset($eDouble)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Vérifier si le tournoi possède une édition.
 * @param int 
 *      $idTournois l'identifiant du tournoi
 */
function tournoisPossedeEdition(int $idTournois){
    $edition = getEditionTournoisByIdTournois($idTournois);
    if(!empty($edition)){
        return true;
    }
    return false;
}

?>