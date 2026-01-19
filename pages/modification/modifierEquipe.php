<?php
session_start();
$racine = dirname(__DIR__, 2);
require_once $racine.'/includes/fonction_joueur.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../../balle.png" sizes="16x16" />
    <link rel="stylesheet" href="../../style.css" type="text/css"/>
    <title>Résultat modification | Master 1000 Tennis</title>
</head>
<body>
<?php
include $racine.'/includes/menuNav.php';
?>

    <h1>Affichage complet de la Table <span class='code'>Equipe</span> modifiée</h1>

    <?php
    require_once $racine.'/includes/fonction_equipe.php';
    require_once $racine.'/includes/afficherTable.php';

    // Récupération des données du formulaire
    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
        $joueur1 = trim($_POST['eq_joueur1']);
        $joueur2 = trim($_POST['eq_joueur2']);
        $idEq = $_POST['id_equipe'];

        if (empty($joueur1) || empty($joueur2) ) {
            header("Location: modifier.php");
            exit(1);
        }
        $equipe = array(
            'id_equipe' => $idEq,
            'eq_joueur1' => $joueur1,
            'eq_joueur2' => $joueur2,
        );
        $result = updateG04Equipe($equipe, true);

    } else {
            echo "<div class='error'>Erreur dans REQUEST_METHOD</div>";
    }


    $nouvelleEquipe = getAllG04Equipe();
    foreach ($nouvelleEquipe as &$equipe) {
        $IDjoueur1 = $equipe['eq_joueur1'];
        $IDjoueur2 = $equipe['eq_joueur2'];
        $joueur1 = getG04JoueurById($IDjoueur1);
        $joueur2 = getG04JoueurById($IDjoueur2);
        $equipe['eq_joueur1'] = $joueur1['joueur_prenom'] . " " . $joueur1['joueur_nom'];
        $equipe['eq_joueur2'] = $joueur2['joueur_prenom'] . " " . $joueur2['joueur_nom'];
    }
    afficherTableSansBouton($nouvelleEquipe);

    ?>

<div style="text-align: right; margin-top: 50px;">
    <form action="../../index.php" method="get">
        <button type="submit" class="btnretour">Retour à l'accueil</button>
    </form>
</div>
</body>
</html>
