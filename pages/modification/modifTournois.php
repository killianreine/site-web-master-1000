<?php
session_start();
$racine = dirname(__DIR__, 2);
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
<?php include $racine.'/includes/menuNav.php'; ?>

    <h1>Affichage complet de la Table <span class='code'>Tournois</span> modifiée</h1>

    <?php
    require_once $racine.'/includes/fonction_tournois.php';
    require_once $racine.'/includes/afficherTable.php';

    // Récupération des données du formulaire
    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
        $nom = trim($_POST['tour_nom']);
        $lieu = trim($_POST['tour_lieu']);
        $surface = trim($_POST['surface']);
        $idTour = $_POST['idTour'];

        if (empty($nom) || empty($lieu) || empty($surface)) {
            header("Location: modifier.php");
            exit(1);
        }
        $tournois = array(
            'id_tournois' => $idTour,
            'tour_nom' => $nom,
            'tour_lieu' => $lieu,
            'tour_surface' => $surface
        );
        $result = updateG04Tournois($tournois, true);

    } else {
            echo "<div class='error'>Erreur dans REQUEST_METHOD</div>";
    }



    afficherTableSansBouton(getAllG04Tournois());
    ?>

<div style="text-align: right; margin-top: 50px;">
    <form action="../../index.php" method="get">
        <button type="submit" class="btnretour">Retour à l'accueil</button>
    </form>
</div>
</body>
</html>
