<!DOCTYPE html>
<html lang='fr'>
<head>
      <meta charset='UTF-8'>
      <link rel="icon" type="image/png" href="../balle.png" sizes="16x16" />
      <meta name='viewport' content='width=device-width, initial-scale=1.0'>
      <link rel='stylesheet' href='../style.css' type='text/css'/>
      <title>Test des fonctions joueurs | Master 1000</title>
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

    <h1>Test sur les fonctions du fichier <span class='code'>fonction_joueur.php</span></h1>
<?php
require_once $racine.'/includes/fonction_joueur.php';
require_once $racine.'/includes/fonction_tournois.php';
require_once $racine.'/includes/fonction_edition_tournois.php';
require_once $racine.'/includes/fonction_equipe.php';
require_once $racine.'/connex.php';
require_once $racine.'/includes/afficherTable.php';

/* Test sur les joueurs */
echo "<p>Sélectionner un joueur selon un id</p>";
afficherTable(getG04JoueurById(1, true), $choixTableIndex);

echo("<p>Liste des joueurs</p>");
$Ljoueur = getAllG04Joueur();
afficherTableSansBouton($Ljoueur);


echo("<p>Insertion d'un joueur déjà existant de Tenis</p>");
 insertG04Joueur("Sock", "Jack","USA", true);
 $insertion = getAllG04Joueur();
 afficherTableSansBouton($Ljoueur);


echo("<p>Insertion d'un nouveau joueur de Tenis</p>");
 insertG04Joueur("Roger", "Federer", "Suisse", true);
 $insertion = getAllG04Joueur();
 afficherTableSansBouton($Ljoueur);


echo("<p>Update d'un joueur</p>");
$nouvellesINFOs = array(
    'id_joueur' => 22,
    'joueur_nom' => 'Roger', 
    'joueur_prenom' => 'Federer', 
    'joueur_nationalite' => 'Allemand'
);
updateAllG04Joueur($nouvellesINFOs, true);
$update = getAllG04Joueur();
afficherTableSansBouton($Ljoueur);


echo("<p>Suppression d'un joueur de Tenis</p>");
deleteG04Joueur(22, true);
$suppression = getAllG04Joueur();
afficherTableSansBouton($Ljoueur);


?>

<form action="../index.php" method="post">
        <button type="submit" class="btnretour">Retour à l'accueil</button>
    </form>
</body>
</html>