

<?php 

/**
 * Formulaire de modification du joueur
 * @param int
 *      $joueur les infos du joueurs à modifier
 * @return void
 */
function modifJoueur(array $joueur) : void{
    $nom = htmlspecialchars($joueur['joueur_nom']);
    $prenom = htmlspecialchars($joueur['joueur_prenom']);
    $nationalite = htmlspecialchars($joueur['joueur_nationalite']);

    // Formulaire de modification les input texte sont automatiquement remplis avec les valeurs actuelles du joueur
    echo <<<HTML
        <form method="POST" action="modifierJoueur.php" class="joueur">
            <label for="joueur_nom">Nom :</label>
            <input type="text" name="joueur_nom" value="$nom" required><br>

            <label for="joueur_prenom">Prénom :</label>
            <input type="text" name="joueur_prenom" value="$prenom" required><br>

            <label for="joueur_nationalite">Nationalité :</label>
            <input type="text" name="joueur_nationalite" value="$nationalite" required><br>

            <input type="hidden" name="id_joueur" value="{$joueur['id_joueur']}">
            <input type="submit" value="Modifier joueur">
        </form>
    HTML;
}

/**
 * Formulaire de modification d'une équipe
 * @param int
 *      $equipe les infos de l'équipe à modifier
 * @return void
 */
function modifEquipe(array $equipe) {
    $liste = getAllG04Joueur();
    ?>
    <form method="POST" action="modifierEquipe.php" class="equipe">

    <div class="select-container">
        <div class="select-wrapper">
            <label for="eq_joueur1">Sélectionnez le joueur 1</label>
            <select size="7" name="eq_joueur1" required>
                <?php foreach ($liste as $joueur): 
                    $selected = ($joueur['id_joueur'] == $equipe['eq_joueur1']) ? 'selected' : '';
                    ?>
                    <option value="<?= $joueur['id_joueur'] ?>" <?= $selected ?>>
                        <?= htmlspecialchars($joueur['joueur_prenom']) ?>
                        <?= htmlspecialchars($joueur['joueur_nom']) ?>
                        (<?= $joueur['id_joueur'] ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="select-wrapper">
            <label for="eq_joueur2">Sélectionnez le joueur 2</label>
            <select size="7" name="eq_joueur2" required>
                <?php foreach ($liste as $joueur): 
                    $selected = ($joueur['id_joueur'] == $equipe['eq_joueur2']) ? 'selected' : '';
                    ?>
                    <option value="<?= $joueur['id_joueur'] ?>" <?= $selected ?>>
                        <?= htmlspecialchars($joueur['joueur_prenom']) ?>
                        <?= htmlspecialchars($joueur['joueur_nom']) ?>
                        (<?= $joueur['id_joueur'] ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <input type="hidden" name="id_equipe" value="<?= $equipe['id_equipe'] ?>">
    <input type="submit" value="Modifier équipe">
    </form>

    <?php
}

/**
 * Formulaire de modification du tournoi
 * @param int
 *      $tournois les infos du tornoi à modifier
 * @return void
 */
function modifTournois(array $tournois){
    $nom = htmlspecialchars($tournois['tour_nom']);
    $lieu = htmlspecialchars($tournois['tour_lieu']);
    $surface = htmlspecialchars($tournois['tour_surface']);
    $idTour = htmlspecialchars($tournois['id_tournois']);
    echo <<<HTML
    <form method="POST" action="modifTournois.php" class="tournois">
        
        <label for="tour_nom">Nom du tournois :</label>
        <input type="text" name="tour_nom" value="$nom" required><br>

        <p>Format du tournois : <br/>
        Moquette <input type="radio" name="surface" 
                    value="Moquette" checked="checked"  />
        Dur <input type="radio" name="surface"
                  value="Dur" />
        </p>

        <label for="tour_lieu">Lieu du tournois :</label>
        <input type="text" name="tour_lieu" value="$lieu" required><br>

        <input type='hidden' name='idTour' value='$idTour'>

        <input type="submit" value="Modifier le tournoi">
    </form>
    HTML;
}

/**
 * Formulaire de modification d'une édition
 * @param int
 *      $edition les infos de l'édition à modifier
 * @return void
 */
function modifEdition(array $edition) {
    $tournois = getAllG04Tournois();
    $joueurs = getAllG04Joueur();
    $equipes = getAllG04Equipe();

    echo '<form method="POST" action="modifierEdition.php" class="Etournois">';

    echo '<input type="hidden" name="format" value="' . htmlspecialchars($edition['format']) . '">';

    // Sélection du tournoi
    echo '<label for="edi_tournois">Tournoi :</label>';
    echo '<select id="edi_tournois" name="edi_tournois" required>';
    foreach ($tournois as $tournoi) {
        $selected = ($tournoi['id_tournois'] == $edition['id_tournois']) ? 'selected' : '';
        echo '<option value="' . htmlspecialchars($tournoi['id_tournois']) . '" ' . $selected . '>' . htmlspecialchars($tournoi['tour_nom']) . '</option>';
    }
    echo '</select><br>';

    // Date de l'édition
    echo '<label for="edi_date">Date de l\'édition :</label>';
    echo '<input type="date" id="edi_date" name="edi_date" value="' . htmlspecialchars($edition['edi_date']) . '" required><br>';

    // FORMAT SIMPLE
    if (isset($edition['format']) && $edition['format'] === 'Simple') {
        echo '<h3>Format simple</h3>';

        // Joueur vainqueur
        echo '<label for="edi_vainqueur">Vainqueur :</label>';
        echo '<select id="edi_vainqueur" name="edi_vainqueur" required>';
        foreach ($joueurs as $joueur) {
            $selected = ($joueur['id_joueur'] == $edition['edi_vainqueur']) ? 'selected' : '';
            echo '<option value="' . htmlspecialchars($joueur['id_joueur']) . '" ' . $selected . '>' . htmlspecialchars($joueur['joueur_nom'] . ' ' . $joueur['joueur_prenom']) . '</option>';
        }
        echo '</select><br/>';

        // Joueur finaliste
        echo '<label for="edi_finaliste">Finaliste :</label>';
        echo '<select id="edi_finaliste" name="edi_finaliste" required>';
        foreach ($joueurs as $joueur) {
            $selected = ($joueur['id_joueur'] == $edition['edi_finaliste']) ? 'selected' : '';
            echo '<option value="' . htmlspecialchars($joueur['id_joueur']) . '" ' . $selected . '>' . htmlspecialchars($joueur['joueur_nom'] . ' ' . $joueur['joueur_prenom']) . '</option>';
        }
        echo '</select><br/>';

    } else {
        //sinon FORMAT DOUBLE
        echo '<h3>Format Double</h3>';

        // Équipe vainqueur
        echo '<label for="edi_vainqueur">Équipe vainqueur :</label>';
        echo '<select id="edi_vainqueur" name="edi_vainqueur" required>';
        foreach ($equipes as $equipe) {
            $joueur1 = getG04JoueurById($equipe['eq_joueur1']);
            $joueur2 = getG04JoueurById($equipe['eq_joueur2']);
            $selected = ($equipe['id_equipe'] == $edition['edi_vainqueur']) ? 'selected' : '';
            echo '<option value="' . htmlspecialchars($equipe['id_equipe']) . '" ' . $selected . '>' . htmlspecialchars($joueur1['joueur_nom'] . ' ' . $joueur1['joueur_prenom'] . ' et ' . $joueur2['joueur_nom'] . ' ' . $joueur2['joueur_prenom']) . '</option>';
        }
        echo '</select><br/>';

        // Équipe finaliste
        echo '<label for="edi_finaliste">Équipe finaliste :</label>';
        echo '<select id="edi_finaliste" name="edi_finaliste" required>';
        foreach ($equipes as $equipe) {
            $joueur1 = getG04JoueurById($equipe['eq_joueur1']);
            $joueur2 = getG04JoueurById($equipe['eq_joueur2']);
            $selected = ($equipe['id_equipe'] == $edition['edi_finaliste']) ? 'selected' : '';
            echo '<option value="' . htmlspecialchars($equipe['id_equipe']) . '" ' . $selected . '>' . htmlspecialchars($joueur1['joueur_nom'] . ' ' . $joueur1['joueur_prenom'] . ' et ' . $joueur2['joueur_nom'] . ' ' . $joueur2['joueur_prenom']) . '</option>';
        }
        echo '</select><br/>';
    }

    echo '<input type="hidden" name="id_edition" value="' . htmlspecialchars($edition['id_edition']) . '">';
    echo '<input type="submit" value="Modifier édition">';
    echo '</form>';
}


?>