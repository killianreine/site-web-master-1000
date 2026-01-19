<?php

session_start();
$racine = dirname(__DIR__, 2);

require_once $racine.'/connex.php';
require_once $racine.'/includes/fonction_joueur.php';
require_once $racine.'/includes/fonction_tournois.php';
require_once $racine.'/includes/fonction_edition_tournois.php';
require_once $racine.'/includes/fonction_equipe.php';
require_once $racine.'/includes/afficherTable.php';
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

    <h1>Affichage complet de la Table <span class='code'>Edition</span> modifiée</h1>

    <?php

    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
        // Récupération des données du formulaire 
        $tournois = trim($_POST['edi_tournois']);
        $date = trim($_POST['edi_date']);
        $vainqueur = trim($_POST['edi_vainqueur']);
        $finaliste = trim($_POST['edi_finaliste']);
        $format = trim($_POST['format']);
        $date = trim($_POST['edi_date']);
        $idEdit = $_POST['id_edition'];

        if (empty($date) || empty($vainqueur) || empty($finaliste) || empty($format)) {
            header("Location: modifier.php");
            exit(1);
        }
        $edition = array(
            'id_edition' => $idEdit,
            'id_tournois' => $tournois,
            'edi_date' => $date,
            'edi_vainqueur' => $vainqueur,
            'edi_finaliste' => $finaliste,
            'format' => $format
        );
        $result = updateG04EditionTournois($edition, true);
    } else {
            echo "<div class='error'>Erreur dans REQUEST_METHOD</div>";
    }


    $nouvelleEdition = getAllG04EditionTournois();
    afficherTableSansBouton($nouvelleEdition);

    ?>

<div style="text-align: right; margin-top: 50px;">
    <form action="../../index.php" method="get">
        <button type="submit" class="btnretour">Retour à l'accueil</button>
    </form>
</div>
</body>
</html>
