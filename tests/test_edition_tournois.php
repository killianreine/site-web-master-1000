<!DOCTYPE html>
<html lang='fr'>
<head>
      <meta charset='UTF-8'>
      <link rel="icon" type="image/png" href="../balle.png" sizes="16x16" />
      <meta name='viewport' content='width=device-width, initial-scale=1.0'>
      <link rel='stylesheet' href='../style.css' type='text/css'/>
      <title>Test des fonctions édition | Master 1000</title>
</head>

<body>
<?php
session_start();
// Récupère choixTableIndex dans le fichier index.php
if (isset($_POST['choixTableIndex'])) {
    $choixTableIndex = $_POST['choixTableIndex'];
} else {
    $choixTableIndex = 'joueur';
}

$racine = dirname(__DIR__, 1);
include $racine.'/includes/menuNav.php';
?>

    <h1>Test sur les fonctions du fichier <span class='code'>fonction_edition_tournois.php</span></h1>
<?php
require_once $racine.'/includes/fonction_joueur.php';
require_once $racine.'/includes/fonction_tournois.php';
require_once $racine.'/includes/fonction_edition_tournois.php';
require_once $racine.'/includes/fonction_equipe.php';
require_once $racine.'/connex.php';
require_once $racine.'/includes/afficherTable.php';

/* Test sur les tournois */

echo "<p>Sélectionner un tournois selon un id</p>";
afficherTable(getG04EditionTournoisById(1, true), $choixTableIndex);

echo("<p>Liste des éditions tournois </p>");
$LETournois = getAllG04EditionTournois();
afficherTableSansBouton($LETournois);


echo("<p>Inserer une edition</p>");
insertG04EditionTournois(1, '2025-01-01', 'Simple', 20, 18, true);
$LETournois = getAllG04EditionTournois();
afficherTableSansBouton($LETournois);


echo("<p>Inserer une edition avec joueur invalide</p>");
insertG04EditionTournois(1, '2025-01-01', 'Simple', 1, 784, true);
$LETournois = getAllG04EditionTournois();
afficherTableSansBouton($LETournois);


echo("<p>Inserer une edition avec un même joueur</p>");
insertG04EditionTournois(1, '2025-01-01', 'Simple', 17, 17, true);
$LETournois = getAllG04EditionTournois();
afficherTableSansBouton($LETournois);


echo("<p>Inserer une edition avec une même équipe</p>");
insertG04EditionTournois(1, '2025-01-01', 'Simple', 1, 1, true);
$LETournois = getAllG04EditionTournois();
afficherTableSansBouton($LETournois);


echo("<p>Inserer une edition avec une équipe erronée</p>");
insertG04EditionTournois(1, '2025-01-01', 'Double', 94, 1, true);
$LETournois = getAllG04EditionTournois();
afficherTableSansBouton($LETournois);


echo("<p>Inserer une edition qui existe déjà</p>");
insertG04EditionTournois(1, '2024-10-28', 'Simple', 2, 1, true);
$LETournois = getAllG04EditionTournois();
afficherTableSansBouton($LETournois);

echo("<p>Inserer une edition avec un tournois qui existe pas</p>");
insertG04EditionTournois(100, '2024-12-28', 'Simple', 2, 1, true);
$LETournois = getAllG04EditionTournois();
afficherTableSansBouton($LETournois);

echo("<p>Update de la nouvelle edition</p>");
$nouvellesINFOs = array(
    'id_edition' => 12,
    'id_tournois' => 2,
    'edi_date' => '2021-02-05',
    'format' => 'Double',
    'edi_vainqueur' => 1, 
    'edi_finaliste' => 2
);
updateG04EditionTournois($nouvellesINFOs, true);
$LETournois = getAllG04EditionTournois();
afficherTableSansBouton($LETournois);


echo("<p>Suppression d'une edition qui existe</p>");
deleteG04EditionTournois(12, true);
$LETournois = getAllG04EditionTournois();
afficherTableSansBouton($LETournois);


?>

<form action="../index.php" method="post">
        <button type="submit" class="btnretour">Retour à l'accueil</button>
        
    </form>
</body>
</html>
