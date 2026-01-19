<?php
$racine = dirname(__DIR__, 2);
require_once $racine.'/includes/fonction_tournois.php';
require_once $racine.'/includes/fonction_joueur.php';
require_once $racine.'/includes/fonction_equipe.php';
require_once $racine.'/includes/afficherTable.php'; 

function afficherCarteTournois($tournoi) {
    if (!isset($tournoi['id_tournois'], $tournoi['tour_nom'], $tournoi['tour_lieu'], $tournoi['tour_surface'])) {
        return "<div>Données du tournoi incomplètes.</div>";
    }

    $id = htmlspecialchars($tournoi['id_tournois']);
    $nom = htmlspecialchars($tournoi['tour_nom']);
    $lieu = htmlspecialchars($tournoi['tour_lieu']);
    $surface = htmlspecialchars($tournoi['tour_surface']);

    return <<<HTML
        <div class="page-container">
            <div class="tournament-card">
                <div class="tournament-head">$nom</div>
                <div class="tournament-detail">
                    <div class="detail-label">ID du tournoi</div>
                    <div class="detail-value">$id</div>

                    <div class="detail-label">Lieu</div>
                    <div class="detail-value">$lieu</div>

                    <div class="detail-label">Surface</div>
                    <div class="detail-value">$surface</div>
                </div>
            </div>
        </div>
    HTML;
}

/** 
 * Fonction pour afficher les détails d'un tournoi
 * @description Cette fonction affiche les détails d'un tournoi sous forme de carte.
 * @param array $tournoi
 * @return string HTML
 */

 function afficherEditionTournois($id_tournois) {
    $html = "<h2 class='subtitle'>Éditions du Tournoi</h2>";

    if ($id_tournois) {
        $html .= afficherDetailsTournois($id_tournois);
    } else {
        $html .= "<div class='error'>Aucune édition trouvée pour ce tournoi.</div>";
    }

    return $html;
}

function afficherDetailsTournois($id_tournoi){
    $tournois = getEditionTournoisByIdTournois($id_tournoi);
    $html = "<table class='table'>
        <tr class='table-header'>
            <th>Identifiant édition</th>
            <th>Date</th>
            <th>Format</th>
            <th>Vainqueur.s</th>
            <th>Finaliste.s</th>
        </tr>";

    foreach ($tournois as $enregistrement) {
        $html .= "<tr> 
            <td>". $enregistrement['id_edition'] ."</td>
            <td>". $enregistrement['edi_date'] ."</td>
            <td>". $enregistrement['format'] ."</td>";

        if($enregistrement['format'] === 'Simple'){
            $joueurVainq = getG04JoueurById($enregistrement['edi_vainqueur']);
            $joueurFinal = getG04JoueurById($enregistrement['edi_finaliste']);
            $html .= "<td>". $joueurVainq['joueur_nom'] . " " . $joueurVainq['joueur_prenom'] ."</td>";
            $html .= "<td>". $joueurFinal['joueur_nom'] . " " . $joueurFinal['joueur_prenom'] ."</td>";
        } elseif($enregistrement['format'] === 'Double'){
            $equipeVainq = getG04EquipeById($enregistrement['edi_vainqueur']);
            $equipeFinal = getG04EquipeById($enregistrement['edi_finaliste']);
            $joueurVainq1 = getG04JoueurById($equipeVainq['eq_joueur1']);
            $joueurVainq2 = getG04JoueurById($equipeVainq['eq_joueur2']);
            $joueurFinal1 = getG04JoueurById($equipeFinal['eq_joueur1']);
            $joueurFinal2 = getG04JoueurById($equipeFinal['eq_joueur2']);
            $html .= "<td>". $joueurVainq1['joueur_nom'] . " " . $joueurVainq1['joueur_prenom'] . " / " . $joueurVainq2['joueur_nom'] . " " . $joueurVainq2['joueur_prenom'] ."</td>";
            $html .= "<td>". $joueurFinal1['joueur_nom'] . " " . $joueurFinal1['joueur_prenom'] . " / " . $joueurFinal2['joueur_nom'] . " " . $joueurFinal2['joueur_prenom'] ."</td>";
        }

        $html .= "</tr>";
    }

    $html .= "</table>";
    return $html;
}

?>
