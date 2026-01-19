<?php
$racine = dirname(__DIR__, 2);
require_once $racine.'/includes/afficherTable.php';

session_start();
$choixFormat = 'Simple'; // Default value

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['format'])) {
    $choixFormat  = $_POST['format'];
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../../balle.png" sizes="16x16" />
    <link rel="stylesheet" href="../../assets/css/form.css" type="text/css"/>
    <link rel="stylesheet" href="../../style.css" type="text/css"/>
    <title>Formulaire d'insertion d'une édition | Master 1000</title>
</head>
<body>
<?php
include $racine.'/includes/menuNav.php';
?>

    <h1>Formulaire insertion Edition Tournoi</h1>
    <form method="POST" action="" class="Etournois">
        <!-- Format -->
        <label for="format">Format :</label>
        <select id="format" name="format" required>
            <option <?php if ($choixFormat == 'Simple') {echo 'selected';} ?> value="Simple">Simple</option>
            <option <?php if ($choixFormat == 'Double') {echo 'selected';}?> value="Double">Double</option>
        </select><br/>

        <input type="submit" value="Choisir Format">
    </form>

    <?php
    
    require_once $racine.'/connex.php';
    require_once $racine.'/includes/fonction_joueur.php';
    require_once $racine.'/includes/fonction_equipe.php';

    echo '<form method="POST" action="../inserer/inserer_editionTournois.php" class="Etournois">';

    echo '<input type="hidden" name="format" value="'.htmlspecialchars($choixFormat).'">';
    
    echo '<label for="edi_date">Date de l\'édition :</label>';
    echo '<input type="date" id="edi_date" name="edi_date" required><br>';

    echo '<label for="id_tournois">Tournoi :</label>';
    echo '<select id="id_tournois" name="id_tournois" required>';
    
    $db = connexion();
    $requete_recup_tournois = "SELECT id_tournois, tour_nom FROM G04_Tournois;";
    $result_tournois = pg_query($db, $requete_recup_tournois);

    if ($result_tournois) {
        while ($tournoi = pg_fetch_assoc($result_tournois)) {
            echo '<option value="' . htmlspecialchars($tournoi['id_tournois']) . '">' . htmlspecialchars($tournoi['tour_nom']) . '</option>';
        }
    } else {
        echo '<option value="">Aucun tournoi trouvé</option>';
    }
    
    pg_close($db);
    echo '</select><br/>';

    if (isset($choixFormat) && $choixFormat === 'Simple') {
        // FORMAT SIMPLE
        echo '<h3>Format simple</h3>';
        
        echo '<label for="edi_vainqueur">Vainqueur : </label>';
        echo '<select id="edi_vainqueur" name="edi_vainqueur" required>';

        $joueurs = getAllG04Joueur(); 
        if (!empty($joueurs)) {
            foreach ($joueurs as $joueur) {
                echo '<option value="' . htmlspecialchars($joueur['id_joueur']) . '">' . htmlspecialchars($joueur['joueur_nom']) . ' ' . htmlspecialchars($joueur['joueur_prenom']) . '</option>';
            }
        } else {
            echo '<option value="">aucun joueur trouvé</option>';
        }
        echo '</select><br/>';

        echo '<label for="edi_finaliste">Finaliste :</label>';
        echo '<select id="edi_finaliste" name="edi_finaliste" required>';
        if (!empty($joueurs)) {
            foreach ($joueurs as $joueur) {
                echo '<option value="' . htmlspecialchars($joueur['id_joueur']) . '">' . htmlspecialchars($joueur['joueur_nom']) . ' ' . htmlspecialchars($joueur['joueur_prenom']) . '</option>';
            }
        } else {
            echo '<option value="">Aucun joueur trouvé</option>';
        }
        echo '</select><br/>';

    } elseif (isset($choixFormat) && $choixFormat === 'Double') {
        // FORMAT DOUBLE
        echo '<h3>Format Double</h3>';

        // équipe vainqueur
        echo '<label for="edi_vainqueur">équipe vainqueur:</label>';
        echo '<select id="edi_vainqueur" name="edi_vainqueur" required>';
    
        $equipes = getAllG04Equipe();

        if (!empty($equipes)) {
            foreach ($equipes as $equipe) {
                $joueur1 = getG04JoueurById($equipe['eq_joueur1']);
                $joueur2 = getG04JoueurById($equipe['eq_joueur2']);
                echo '<option value="' . htmlspecialchars($equipe['id_equipe']) . '">'. htmlspecialchars($joueur1['joueur_nom'] . ' ' . $joueur1['joueur_prenom'])
                    . ' et '
                    . htmlspecialchars($joueur2['joueur_nom'] . ' ' . $joueur2['joueur_prenom']) . '</option>';
            }
        } else {
            echo '<option value="">Aucune équipe trouvée</option>';
        }
        echo '</select><br/>';
    
        // équipe finaliste
        echo '<label for="edi_finaliste">Équipe finaliste :</label>';
        echo '<select id="edi_finaliste" name="edi_finaliste" required>';
        if (!empty($equipes)) {
            foreach ($equipes as $equipe) {
                $joueur1 = getG04JoueurById($equipe['eq_joueur1']);
                $joueur2 = getG04JoueurById($equipe['eq_joueur2']);
                echo '<option value="' . htmlspecialchars($equipe['id_equipe']) . '">' . htmlspecialchars($joueur1['joueur_nom'] . ' ' . $joueur1['joueur_prenom'])
                    . ' et '
                    . htmlspecialchars($joueur2['joueur_nom'] . ' ' . $joueur2['joueur_prenom']) . '</option>';
            }
        } else {
            echo '<option value="">Aucune équipe trouvée</option>';
        }
        echo '</select><br/>';
    }

    echo '<input type="submit" value="Ajouter une édition">';
    echo '</form>';
?> 
<div style="text-align: right; margin-top: 50px;">
    <form action="../../index.php" method="post">
        <button type="submit" class="btnretour">Retour à l'accueil</button>
        <input type='hidden' name='table' value='ETournois'>
    </form>
</div>
</body>
</html>