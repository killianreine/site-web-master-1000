<?php
$racine = dirname(__DIR__, 2);
require_once $racine.'/includes/fonction_joueur.php';
require_once 'detailsJoueur.php';


function afficherEquipe($tab) {
    $html = '<div class="team-avatar">Équipe n°' . htmlspecialchars($tab['id_equipe']) . '</div>';
    $html .= '<div class="team-info">';

    $joueur1 = getG04JoueurById($tab['eq_joueur1']);
    $joueur2 = getG04JoueurById($tab['eq_joueur2']);

    $html .= '<div class="team-player">';
    $html .= afficherCarteJoueur($joueur1);
    $html .= afficherCarteJoueur($joueur2);
    $html .= '</div>'; 
    $html .= '</div>';

    return $html; 
}


?>