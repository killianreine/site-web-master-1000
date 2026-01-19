<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../../balle.png" sizes="16x16" />
    <link rel="stylesheet" href="../../assets/css/form.css" type="text/css"/>
    <link rel="stylesheet" href="../../style.css" type="text/css"/>
    <title>Formulaire d'insertion d'un tournois | Master 1000</title>
</head>
<body>
<?php
$racine = dirname(__DIR__, 2);
include $racine.'/includes/menuNav.php';
?>
    <form method="POST" action="../inserer/inserer_tournois.php" class="tournois">
        
        <label for="tour_nom">Nom du tournois :</label>
        <input type="text" name="tour_nom" required><br>

        <p>Format du tournois : <br/>
        Moquette <input type="radio" name="surface" 
                    value="Moquette" checked="checked"  />
        Dur <input type="radio" name="surface"     
                  value="Dur" />
        </p>

        <label for="tour_lieu">Lieu du tournois :</label>
        <input type="text" name="tour_lieu" required><br>

        <input type="submit" value="Ajouter tournois">
    </form>
<div style="text-align: right; margin-top: 50px;">
    <form action="../../index.php" method="post">
        <button type="submit" class="btnretour">Retour à l'accueil</button>
        <input type='hidden' name='table' value='Tournois'>
    </form>
</div>
</body>
</html>