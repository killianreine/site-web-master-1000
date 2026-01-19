<?php 
function afficherCarteJoueur($tab) {
    // Vérification minimale des données (optionnel)
    if (!isset($tab['id_joueur'], $tab['joueur_nom'], $tab['joueur_prenom'], $tab['joueur_nationalite'])) {
        return "<div>Informations du joueur incomplètes.</div>";
    }

    $initiales = substr($tab['joueur_prenom'], 0, 1).substr($tab['joueur_nom'], 0, 1);
    $nomComplet = htmlspecialchars($tab['joueur_prenom'] . " " . $tab['joueur_nom']);
    $id = htmlspecialchars($tab['id_joueur']);
    $nom = htmlspecialchars($tab['joueur_nom']);
    $prenom = htmlspecialchars($tab['joueur_prenom']);
    $nationalite = htmlspecialchars($tab['joueur_nationalite']);

    return <<<HTML
        <div class="page-container">
            <div class="player-card">
                <div class="player-head">
                    <div class="player-avatar">$initiales</div>
                    <div class="player-info">
                        <h2 class="player-name">$nomComplet</h2>
                    </div>
                </div> 
                <div class="player-detail-principal">
                    <div class="player-detail">
                        <div class="detail-label">ID du joueur</div>
                        <div class="detail-value">$id</div>

                        <div class="detail-label">Nom</div>
                        <div class="detail-value">$nom</div>

                        <div class="detail-label">Prénom</div>
                        <div class="detail-value">$prenom</div>
                        
                        <div class="detail-label">Nationalité</div>
                        <div class="detail-value">$nationalite</div>
                    </div>
                </div>
            </div>
        </div>
    HTML;
}

?>
