
<!DOCTYPE html>
<html lang='fr'>
<head>
      <meta charset='UTF-8'>
      <link rel="icon" type="image/png" href="../balle.png" sizes="16x16" />
      <meta name='viewport' content='width=device-width, initial-scale=1.0'>
      <link rel='stylesheet' href='../style.css' type='text/css'/>
      <title>Test des fonctions tournois | Master 1000</title>
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

    <h1>Test sur les fonctions du fichier <span class='code'>fonction_tournois.php</span></h1><?php
require_once $racine.'/includes/fonction_joueur.php';
require_once $racine.'/includes/fonction_tournois.php';
require_once $racine.'/includes/fonction_edition_tournois.php';
require_once $racine.'/includes/fonction_equipe.php';
require_once $racine.'/connex.php';
require_once $racine.'/includes/afficherTable.php';

echo("<p>Sélectionner un tournois selon un id</p>");
afficherTable(getG04TournoisById(2, true), $choixTableIndex);

echo("<p>Liste des tournois </p>");
$LTournois = getAllG04Tournois();
afficherTableSansBouton($LTournois);


echo("<p>Insertion d'un tournois existant</p>");
insertG04Tournois("Open de Paris-Bercy", "Moquette", "Paris", true);
$LTournois = getAllG04Tournois();
afficherTableSansBouton($LTournois);



echo("<p>Insertion d'un nouveau tournois</p>");
insertG04Tournois("Master de Rome", "Dur", "Rome", true);
$LTournois = getAllG04Tournois();
afficherTableSansBouton($LTournois);


echo("<p>Update du nouveau tournois</p>");
$noubelleINFOs = array(
    'id_tournois' => 3,
    'tour_nom' => "Grands master d'Italie",
    'tour_surface' => "Dur",
    'tour_lieu' => 'Rome'
);
updateG04Tournois($noubelleINFOs, true);
$LTournois = getAllG04Tournois();
afficherTableSansBouton($LTournois);


echo("<p>Suppression d'un tournois non existant</p>");
deleteG04Tournois(5, true);
$LTournois = getAllG04Tournois();
afficherTableSansBouton($LTournois);



echo("<p>Suppression d'un tournois existant</p>");
 deleteG04Tournois(3, true);
$LTournois = getAllG04Tournois();
afficherTableSansBouton($LTournois);


?>
<form action="../index.php" method="post">
        <button type="submit" class="btnretour">Retour à l'accueil</button>
    </form>
</body>
</html>