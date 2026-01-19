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

    <h1>Affichage complet de la Table <span class='code'>Joueur</span> modifiée</h1>

    <?php
    require_once $racine.'/includes/fonction_joueur.php';
    require_once $racine.'/includes/afficherTable.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
        $nom = trim($_POST['joueur_nom']);
        $prenom = trim($_POST['joueur_prenom']);
        $nationalite = trim($_POST['joueur_nationalite']);
        $idJo = $_POST['id_joueur'];

        if (empty($nom) || empty($prenom) || empty($nationalite)) {
            header("Location: modifier.php");
            exit(1);
        }
        $joueur = array(
            'id_joueur' => $idJo,
            'joueur_nom' => $nom,
            'joueur_prenom' => $prenom,
            'joueur_nationalite' => $nationalite
        );
        $result = updateAllG04Joueur($joueur, true);

    } else {
            echo "<div class='error'>Erreur dans REQUEST_METHOD</div>";
    }



    afficherTableSansBouton(getAllG04Joueur());
    ?>

<div style="text-align: right; margin-top: 50px;">
    <form action="../../index.php" method="get">
        <button type="submit" class="btnretour">Retour à l'accueil</button>
    </form>
</div>
</body>
</html>
