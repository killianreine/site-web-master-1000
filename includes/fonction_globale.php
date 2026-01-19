<?php 
$racine = dirname(__DIR__, 1);
include_once $racine.'/connex.php';
require_once $racine.'/includes/fonction_joueur.php';
require_once $racine.'/includes/fonction_equipe.php';
require_once $racine.'/includes/fonction_edition_tournois.php';
require_once $racine.'/includes/fonction_tournois.php';

/**
 * On vas vers quel page pour l'insertion ?
 * @param String ChoixTable : Le nom de la table choisie
 */
function insertionTable(String $choixTable) : String{
    switch($choixTable) {
        case 'Joueur':
            return "pages/insertion/insertion_Joueurs.php";
        case 'Equipe':
            return "pages/insertion/insertion_equipe.php";
        case 'Tournois':
            return "pages/insertion/insertion_tournois.php";
        case 'ETournois':
            return "pages/insertion/insertion_editionTournois.php";
        default:
            return "";
    }
}

/**
 * Permet de détminer quelle fonction j'utilise selon la table.
 */
function suppIntermed(int $id, String $choixTable) : string {
    switch($choixTable) {
        case 'Joueur':
            deleteG04Joueur($id, true);
            break;
        case 'Equipe':
            deleteG04Equipe($id, true);
            break;
        case 'Tournois':
            deleteG04Tournois($id, true);
            break;
        case 'ETournois':
            deleteG04EditionTournois($id, true);
            break;
        default:
            return "";
    }
    return "OK";
}

?>