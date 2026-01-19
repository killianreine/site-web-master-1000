<?php 
$racine = dirname(__DIR__, 2);

require_once $racine."/connex.php";
require_once $racine."/includes/fonction_tournois.php";
require_once $racine.'/includes/afficherTable.php';
$ptrDB = connexion();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../../balle.png" sizes="16x16" />
    <link rel="stylesheet" href="../../style.css" type="text/css"/>
    <title>Insertion d'un tournoi | Master 1000</title>
</head>
<body>
<?php include $racine.'/includes/menuNav.php'; ?>

    <h1>Affichage complet de la Table <span class='code'>Tournois</span> modifiée</h1>
<!--Pour insérer équipe-->
    <?php
        $nomTournois = trim($_POST['tour_nom']);
        $lieuTournois = trim($_POST['tour_lieu']);
        if(empty($nomTournois) || empty($lieuTournois)){
            header("Location: ../insertion/insertion_tournois.php");
            exit(1);
        }
        if (isset($_POST['tour_nom'], $_POST['surface'], $_POST['tour_lieu'])){
            if(insertG04Tournois($_POST['tour_nom'], $_POST['surface'], $_POST['tour_lieu'], true)){
                echo "<div class='success'>Préparez votre argent, le tournois ".$_POST['tour_nom']." de ".$_POST['tour_lieu']." sur ".$_POST['surface']." arrive dans la compétition!</div>";
                
            };

        }
        else{
            echo "<div class='error>Zut, il semble qu'il y ait un problème, vous n'êtes probablement pas passés par la page de formulaire, veuillez revenir à la page précédente.</div>";
        }
        $LTournois = getAllG04Tournois();
        afficherTableSansBouton($LTournois);
    ?>

<div style="text-align: right; margin-top: 50px;">
    <form action="../../index.php" method="post">
        <button type="submit" class="btnretour">Retour à l'accueil</button>
        <input type='hidden' name='table' value='Tournois'>
    </form>
</div>
</body>
</html>


