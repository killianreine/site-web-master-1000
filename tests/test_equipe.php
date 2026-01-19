<!DOCTYPE html>
<html lang='fr'>
<head>
      <meta charset='UTF-8'>
      <link rel="icon" type="image/png" href="../balle.png" sizes="16x16" />
      <meta name='viewport' content='width=device-width, initial-scale=1.0'>
      <link rel='stylesheet' href='../style.css' type='text/css'/>
      <title>Test des fonctions équipes | Master 1000</title>
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

    <h1>Test sur les fonctions du fichier <span class='code'>fonction_equipe.php</span></h1><?php
require_once $racine.'/includes/fonction_joueur.php';
require_once $racine.'/includes/fonction_tournois.php';
require_once $racine.'/includes/fonction_edition_tournois.php';
require_once $racine.'/includes/fonction_equipe.php';
require_once $racine.'/connex.php';
require_once $racine.'/includes/afficherTable.php';


echo "<p>Sélectionner une équipe selon un id</p>";
afficherTable(getG04EquipeById(4, true), $choixTableIndex);

echo("<p>Liste des équipes </p>");
$LEquipe = getAllG04Equipe();
afficherTableSansBouton($LEquipe);


echo("<p>Ajout d'une nouvelle équipe avec un joueur innexistant</p>");
insertG04Equipe(11, 42, true);
$LEquipe = getAllG04Equipe();
afficherTableSansBouton($LEquipe);

echo("<p>Ajout d'une nouvelle équipe déjà existante</p>");
insertG04Equipe(1, 2, true);
$LEquipe = getAllG04Equipe();
afficherTableSansBouton($LEquipe);

 
echo("<p>Ajout d'une équipe composé du même joueur</p>");
insertG04Equipe(1, 1, true);
$LEquipe = getAllG04Equipe();
afficherTableSansBouton($LEquipe);


echo("<p>Ajout d'une nouvelle équipe non existante</p>");
insertG04Equipe(7, 2, true);
$LEquipe = getAllG04Equipe();
afficherTableSansBouton($LEquipe);


echo("<p>Update de l'équipe ajoutée</p>");
$noubellesINFOs=array(
    'id_equipe' => 10,
    'eq_joueur1' => 8,
    'eq_joueur2' => 18
);
updateG04Equipe($noubellesINFOs, true);
$LEquipe = getAllG04Equipe();
afficherTableSansBouton($LEquipe);


echo("<p>Suppression d'une équipe</p>");
 deleteG04Equipe(10, true);
$LEquipe = getAllG04Equipe();
afficherTableSansBouton($LEquipe);

echo("<p>Suppression d'une équipe inexistante</p>");
deleteG04Equipe(785, true);
$LEquipe = getAllG04Equipe();
afficherTableSansBouton($LEquipe);
?>
<form action="../index.php" method="post">
        <button type="submit" class="btnretour">Retour à l'accueil</button>
    </form>
</body>
</html>