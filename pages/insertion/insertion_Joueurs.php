<?php 
$racine = dirname(__DIR__, 2);
require_once $racine.'/connex.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../../balle.png" sizes="16x16" />
    <link rel="stylesheet" href="../../assets/css/form.css" type="text/css"/>
    <link rel="stylesheet" href="../../style.css" type="text/css"/>
    <title>Formulaire insertion joueur | Master 1000</title>
</head>
<body>
<?php
include $racine.'/includes/menuNav.php';
?>

    <h1>Formulaitre d'insertion joueur</h1>
    <form method="POST" action="../inserer/inserer_Joueurs.php" class="joueur">
        <label for="joueur_nom">Nom :</label>
        <input type="text" name="joueur_nom" placeholder="nom joueur" required><br>

        <label for="joueur_prenom">Prénom :</label>
        <input type="text" name="joueur_prenom" placeholder="prénom joueur" required><br>

        <label for="joueur_nationalite">Nationalité :</label>
        <input type="text" name="joueur_nationalite" placeholder="nationalité" required><br>

        <input type="hidden" name="id_joueur" value="{$joueur['id_joueur']}">
        <input type="submit" value="Ajouter le joueur">
    </form>

    <div style="text-align: right; margin-top: 50px;">
    <form action="../../index.php" method="post">
        <button type="submit" class="btnretour">Retour à l'accueil</button>
        <input type='hidden' name='table' value='Joueur'>
    </form>
</div>
</body>
</html>