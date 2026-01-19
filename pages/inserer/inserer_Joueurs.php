

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../../balle.png" sizes="16x16" />
    <link rel="stylesheet" href="../../style.css" type="text/css"/>
    <title>Insertion d'un joueur | Master 1000</title>
</head>
<body>
<?php
$racine = dirname(__DIR__, 2);
require_once $racine.'/connex.php';
?>
<?php
include $racine.'/includes/menuNav.php';
?>
    <h1>Affichage complet de la Table <span class='code'>Joueur</span> modifiée</h1>

    <?php
    
    require_once $racine.'/includes/fonction_joueur.php';
    require_once $racine.'/includes/afficherTable.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
        $nom = trim($_POST['joueur_nom']);
        $prenom = trim($_POST['joueur_prenom']);
        $nationalite = trim($_POST['joueur_nationalite']);

        if (empty($nom) || empty($prenom) || empty($nationalite)) {
            header("Location: ../insertion/insertion_Joueurs.php");
            exit(1);
        }
        $result = insertG04Joueur($nom, $prenom, $nationalite, true);

    } else {
            echo "<div class='error'>Erreur dans REQUEST_METHOD</div>";
    }



    afficherTableSansBouton(getAllG04Joueur());
    ?>

<div style="text-align: right; margin-top: 50px;">
    <form action="../../index.php" method="post">
        <button type="submit" class="btnretour">Retour à l'accueil</button>
        <input type='hidden' name='table' value='Joueur'>
    </form>
</div>
</body>
</html>
