<?php 
$racine = dirname(__DIR__, 2);
require_once $racine.'/includes/fonction_edition_tournois.php';
require_once $racine.'/includes/fonction_tournois.php';
require_once 'detailsJoueur.php';
require_once 'detailsEquipe.php';
require_once $racine.'/includes/fonction_joueur.php';
require_once $racine.'/includes/afficherTable.php';

function afficherCarteETournois($Etournoi) {
    if (!isset($Etournoi['id_edition'], $Etournoi['id_tournois'], $Etournoi['edi_date'], $Etournoi['format'], $Etournoi['edi_vainqueur'], $Etournoi['edi_finaliste'])) {
        return "<div>Données de l'édition incomplètes.</div>";
    }

    $id = htmlspecialchars($Etournoi['id_edition']);
    $tournoi = htmlspecialchars($Etournoi['id_tournois']);
    $dateComplete = htmlspecialchars($Etournoi['edi_date']);
    $format = htmlspecialchars($Etournoi['format']);
    $vainqueur = htmlspecialchars($Etournoi['edi_vainqueur']);
    $finaliste = htmlspecialchars($Etournoi['edi_finaliste']);

    $infoTournoi = getG04TournoisById($tournoi);
    $nomTournoi = htmlspecialchars($infoTournoi['tour_nom']);

    $date = explode("-", $dateComplete);
    $date = $date[2] . " " . getMois($date[1]) . " " . $date[0];
    echo <<<HTML
        <div class="page-container-Edition">
            <div class="tournamentE-card">
                <div class="tournamentE-head">Édition n°$id</div>
                <div class="tournamentE-detail">
                    <div class="detail-label-Edition">Tournoi concerné</div>
                    <div class="detail-value-Edition">$nomTournoi - ID$tournoi</div>

                    <div class="detail-label-Edition">Date de l'édition</div>
                    <div class="detail-value-Edition">$date</div>

                    <div class="detail-label-Edition">format</div>
                    <div class="detail-value-Edition">$format</div>
                </div>
            </div>
        </div>
    HTML;
    if($format === "Simple") {
        $vainqueurJoueur = getG04JoueurById($vainqueur);
        $finalisteJoueur = getG04JoueurById($finaliste);
        echo "<h2 class='subtitle'>Joueurs vainqueur</h2>";
        echo afficherCarteJoueur($vainqueurJoueur);

        echo "<h2 class='subtitle'>Joueurs finaliste</h2>";
        echo afficherCarteJoueur($finalisteJoueur);
    } elseif ($format === "Double") {
        $vainqueurEquipe = getG04EquipeById($vainqueur);
        $finalisteEquipe = getG04EquipeById($finaliste);
        echo "<h2 class='subtitle'>Équipes vainqueur</h2>";
        echo afficherEquipe($vainqueurEquipe);

        echo "<h2 class='subtitle'>Équipes finaliste</h2>";
        echo afficherEquipe($finalisteEquipe);
    }
}

function getMois($mois) {
    $mois = (int)$mois;
    $moisNoms = [
        1 => 'Janvier',
        2 => 'Février',
        3 => 'Mars',
        4 => 'Avril',
        5 => 'Mai',
        6 => 'Juin',
        7 => 'Juillet',
        8 => 'Août',
        9 => 'Septembre',
        10 => 'Octobre',
        11 => 'Novembre',
        12 => 'Décembre'
    ];
    return $moisNoms[$mois] ?? "Inconnu";
}
?>
