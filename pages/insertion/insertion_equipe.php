
<?php 
$racine = dirname(__DIR__, 2);
require_once $racine."/connex.php";
require_once $racine."/includes/fonction_joueur.php";
$ptrDB = connexion();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../../balle.png" sizes="16x16" />
    <link rel="stylesheet" href="../../assets/css/form.css" type="text/css"/>
    <link rel="stylesheet" href="../../style.css" type="text/css"/>
    <title>Formulaire insertion équipe | Master 1000</title>
</head>
<body>
<?php
include $racine.'/includes/menuNav.php';
?>

<h1>Forumulaire insertion équipe</h1>
<form method="POST" action="../inserer/inserer_equipe.php" class="equipe">
    <div class="select-container">
        <div class="select-wrapper">
            <label for="eq_joueur1">Sélectionnez le joueur 1</label>
            <select size="7" name="eq_joueur1" required>
                <?php 
                $liste = getAllG04Joueur();
                afficherOptionsJoueurs($liste); ?>
            </select>
        </div>

        <div class="select-wrapper">
            <label for="eq_joueur2">Sélectionnez le joueur 2</label>
            <select size="7" name="eq_joueur2" required>
                <?php afficherOptionsJoueurs($liste); ?>
            </select>
        </div>
    </div>
    <input type="submit" value="Ajouter équipe">
</form>

</body>
</html>

<?php

function afficherOptionsJoueurs(array $liste, $joueurSelectionneId = null) {
    foreach ($liste as $joueur) {
        $selected = ($joueur['id_joueur'] == $joueurSelectionneId) ? 'selected' : '';
        echo "<option value=\"{$joueur['id_joueur']}\" $selected>";
        echo htmlspecialchars($joueur['joueur_prenom']) . ' ' . htmlspecialchars($joueur['joueur_nom']);
        echo " ({$joueur['id_joueur']})</option>";
    }
}


?>

<div style="text-align: right; margin-top: 50px;">
    <form action="../../index.php" method="post">
        <button type="submit" class="btnretour">Retour à l'accueil</button>
        <input type='hidden' name='table' value='Equipe'>
    </form>
</div>