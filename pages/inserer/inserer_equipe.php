<?php 
$racine = dirname(__DIR__, 2);

require_once $racine."/connex.php";
require_once $racine."/includes/fonction_equipe.php";
require_once $racine.'/includes/fonction_joueur.php';
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
    <title>Insertion d'une équipe | Master 1000</title>
</head>
<body>
<?php include $racine.'/includes/menuNav.php';?>

    <h1>Affichage complet de la Table <span class='code'>Equipe</span> modifiée</h1>
<!--Pour insérer équipe-->
    <?php
        if (isset($_POST['eq_joueur1'], $_POST['eq_joueur2'])){
            //si le formulaire a bien donné des résultats
            if(insertG04Equipe($_POST['eq_joueur1'], $_POST['eq_joueur2'], true)){
                $player1 = getG04JoueurById($_POST['eq_joueur1']);
                $player2 = getG04JoueurById($_POST['eq_joueur2']);
                echo "<div class='success'> Nouvelle équipe ajoutée : ".$player1['joueur_prenom']." ".$player1['joueur_nom']." et ".$player2['joueur_prenom']." ".$player2['joueur_nom']."</div>";
            }
        }else{
            echo "<div class='error'>Zut, il semble qu'il y ait un problème, vous n'êtes probablement pas passés par la page de formulaire, veuillez revenir à la page précédente.</div>";
        }
        // Affichage de la table modifiée ou non
        $nouvelleEquipe = getAllG04Equipe();
        foreach ($nouvelleEquipe as &$equipe) {
            $IDjoueur1 = $equipe['eq_joueur1'];
            $IDjoueur2 = $equipe['eq_joueur2'];
            $joueur1 = getG04JoueurById($IDjoueur1);
            $joueur2 = getG04JoueurById($IDjoueur2);
            $equipe['eq_joueur1'] = "ID".$IDjoueur1 . " - " . $joueur1['joueur_prenom'] . " " . $joueur1['joueur_nom'];
            $equipe['eq_joueur2'] = "ID". $IDjoueur2 . " - " . $joueur2['joueur_prenom'] . " " . $joueur2['joueur_nom'];
        }
        afficherTableSansBouton($nouvelleEquipe);
    ?>

<div style="text-align: right; margin-top: 50px;">
    <form action="../../index.php" method="post">
        <button type="submit" class="btnretour">Retour à l'accueil</button>
        <input type='hidden' name='table' value='Equipe'>
    </form>
</div>
</body>
</html>


