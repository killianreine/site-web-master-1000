<?php
session_start();
$racine = dirname(__DIR__, 2);

// Récupère le choix de la table depuis la session contenu dans $choixTableIndex de index.php
if (isset($_POST['choixTable'])) {
    $choixTableIndex = $_POST['choixTable'];
} else {
    $choixTableIndex = 'joueur';
}

require_once $racine.'/connex.php';
require_once $racine.'/includes/fonction_joueur.php';
require_once $racine.'/includes/fonction_tournois.php';
require_once $racine.'/includes/fonction_edition_tournois.php';
require_once $racine.'/includes/fonction_equipe.php';
require_once $racine.'/includes/afficherTable.php';
require_once 'formModification.php';

if (isset($_POST['id'])) {
    $identifiant = $_POST['id'];
 } 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../../balle.png" sizes="16x16" />
    <link rel="stylesheet" href="../../style.css" type="text/css"/>
    <link rel="stylesheet" href="../../assets/css/form.css" type="text/css"/>
    <title>Modification de données | Master 1000 Tennis</title>
</head>
<body>
<?php
include $racine.'/includes/menuNav.php';
?>
    <h1>Modifier un enregistrement de la table <?php echo($choixTableIndex) ?></h1>
<?php
/**
 * Affiche les détails d'une table en fonction de l'ID sélectionné dans la session et de la table choisie.
 */

if (isset($identifiant)) {
    // Contenu de la page selon la table choisie

    // JOUEUR
    if($choixTableIndex === 'Joueur') {
        $tab = getG04JoueurById($identifiant);
        afficherTable($tab, $choixTableIndex);
        $joueur = getG04JoueurById($identifiant);
        echo modifJoueur($joueur);
    } 
    
    // EQUIPE
    elseif ($choixTableIndex === 'Equipe') {
        $tab = getG04EquipeById($identifiant);
        afficherTable($tab, $choixTableIndex);

        $equipe = getG04EquipeById($identifiant);
        echo modifEquipe($equipe);
    } 
    
    // EDITION TOURNOIS
    elseif ($choixTableIndex === 'ETournois') {
        $tab = getG04EditionTournoisById($identifiant);
        afficherTable($tab, $choixTableIndex);

        $editionTournois = getG04EditionTournoisById($identifiant);
        echo modifEdition($editionTournois);

    } 
    
    // TOURNOIS
    elseif ($choixTableIndex === 'Tournois') {
        $tab = getG04TournoisById($identifiant);
        afficherTable($tab, $choixTableIndex);

        $tournois = getG04TournoisById($identifiant);
        echo modifTournois($tournois);
    } 
    
    else {
        echo $tab= NULL;
    }
} else {
    echo "<div class='error'>Erreur : aucune donnée sélectionné.</div>";
}

?>
<div style="text-align: right; margin-top: 50px;">
    <form action="../../index.php" method="get">
        <button type="submit" class="btnretour">Retour à l'accueil</button>
    </form>
</div>
</body>
</html>