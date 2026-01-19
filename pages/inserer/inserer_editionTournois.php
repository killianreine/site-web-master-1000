<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../../balle.png" sizes="16x16" />
    <link rel="stylesheet" href="../../style.css" type="text/css"/>
    <title>Insertion d'une édition | Master 1000</title>
</head>
<body>
<?php 
$racine = dirname(__DIR__, 2);
include $racine.'/includes/menuNav.php'; 
?>

    <h1>Affichage complet de la Table <span class='code'>ETournois</span> modifiée</h1>


<?php
require_once $racine.'/connex.php';
require_once $racine.'/includes/fonction_joueur.php';
require_once $racine.'/includes/fonction_edition_tournois.php';
require_once $racine.'/includes/fonction_equipe.php';
require_once $racine.'/includes/afficherTable.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // traitement des informatiosn communes au format simple et double
    $edi_date = trim($_POST['edi_date']);
    $id_tournois = trim($_POST['id_tournois']);
    $format = trim($_POST['format']);

    // verification champs commun aux deux formulaire
    if (empty($edi_date) || empty($id_tournois) || empty($format)) {
        header("Location: ../insertion/insertion_editionTournois.php");
        exit(1);
    }
    if ($format === 'Simple') {
        // traitement format simplee
        $edi_vainqueur = trim($_POST['edi_vainqueur']);
        $edi_finaliste = trim($_POST['edi_finaliste']);

        if (empty($edi_vainqueur) || empty($edi_finaliste)) {
            die("<div class='error'>Les champs vainqueur et finaliste doivent être remplis pour le format Simple</div>");
        }

        if (getG04JoueurById($edi_vainqueur) === null || getG04JoueurById($edi_finaliste) === null) {
            die("<div class='error'>Le joueur vainqueur et/ou finaliste n'existe pas</div>");
        }

        $result = insertG04EditionTournois($id_tournois, $edi_date, $format, $edi_vainqueur, $edi_finaliste);
        if (!$result) {
            echo "<div class='error'>Erreur lors de l'insertion de l'édition au format Simple</div>";
        }
    } elseif ($format === 'Double') {
        // traitement format double
        $edi_vainqueur = trim($_POST['edi_vainqueur']);
        $edi_finaliste = trim($_POST['edi_finaliste']);

        if (empty($edi_vainqueur) || empty($edi_finaliste)) {
            die("<div class='error>Les champs équipe vainqueur et équipe finaliste doivent être remplis pour le format Double</div>");
        }

        if (getG04EquipeById($edi_vainqueur) === null || getG04EquipeById($edi_finaliste) === null) {
            die("<div class='error'>L'équipe vainqueur et/ou finaliste n'existe pas</div>");
        }

        $result = insertG04EditionTournois($id_tournois, $edi_date, $format, $edi_vainqueur, $edi_finaliste, true);
        if (!$result){
            echo "<div class='error'>Erreur lors de l'insertion de l'édition au format Double</div>";
        }
    } else {
        echo "<div class='error'>pas de format</div>";
    }
} else {
    echo "<div class='error'>méthode de requête non autorisé</div>";
}
$nouvETournois = getAllG04EditionTournois();
afficherTableSansBouton($nouvETournois);
?>
<div style="text-align: right; margin-top: 50px;">
    <form action="../../index.php" method="post">
        <button type="submit" class="btnretour">Retour à l'accueil</button>
        <input type='hidden' name='table' value='ETournois'>
    </form>
</div>
</body>
</html>


