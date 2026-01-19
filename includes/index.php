<?php 
session_start();

require_once 'connex.php';
require_once 'includes/fonction_joueur.php';
require_once 'includes/fonction_equipe.php';
require_once 'includes/fonction_edition_tournois.php';
require_once 'includes/fonction_tournois.php';
require_once 'includes/afficherTable.php';


if (!isset($_SESSION['choixTable'])) {
    $_SESSION['choixTable'] = 'Joueur';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['table']) && !empty($_POST['table'])) {
        $_SESSION['choixTable'] = htmlspecialchars($_POST['table']);
    }

    if (isset($_POST['supprimer']) && isset($_SESSION['idSupp'])) {
        $idSupp = (int) $_SESSION['idSupp'];

        if ($idSupp > 0) {
            suppIntermed($idSupp, $_SESSION['choixTable']);
        } else {
            echo "<div class='error'>L'ID fourni est invalide.</div>";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="balle.png" sizes="16x16" />
    <link rel="stylesheet" href="style.css" type="text/css"/>
    <title>Accueil | Master 1000 Tennis</title>
</head>
<body>
<?php
include 'includes/menuNav.php';
?>

<h1>Affichage complet de la Table <span class='code'><?php echo $_SESSION['choixTable'] ?></span></h1>

<form method="POST" action="">
    <label for="table" class="labelForm">Sur quelle table voulez-vous travailler ?</label>
    <select class="index" id="table" name="table" required onchange="this.form.submit()">
        <option value="Joueur" <?php if ($_SESSION['choixTable'] == 'Joueur') echo 'selected'; ?>>Joueur</option>
        <option value="Equipe" <?php if ($_SESSION['choixTable'] == 'Equipe') echo 'selected'; ?>>Équipe</option>
        <option value="ETournois" <?php if ($_SESSION['choixTable'] == 'ETournois') echo 'selected'; ?>>Édition tournois</option>
        <option value="Tournois" <?php if ($_SESSION['choixTable'] == 'Tournois') echo 'selected'; ?>>Tournois</option>
    </select>
    <br/>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'supprimer' && isset($_POST['idSupp'])) {
        $idSupp = (int)$_POST['idSupp'];
        if ($idSupp > 0) {
            suppIntermed($idSupp, $_SESSION['choixTable']);
            $idSupp = -1;
        }
    }
}

$pageInsert = insertionTable($_SESSION['choixTable']); 
?>

<form method="POST" action="<?php echo $pageInsert; ?>">
    <button type="submit" class="modern">Insérer une donnée dans <b style="text-transform: uppercase;"><?php echo($_SESSION['choixTable']."<br/>")?></b></button>
    <input type='hidden' name='table' value='<?php echo $_SESSION['choixTable']; ?>'>
</form>

<?php
// Afficher la valeur de la variable $table
switch($_SESSION['choixTable']) {
    case 'Joueur':
        $table = getAllG04Joueur();
        break;
    case 'Equipe':
        $table = getAllG04Equipe();
        break;
    case 'Tournois':
        $table = getAllG04Tournois();
        break;
    case 'ETournois':
        $table = getAllG04EditionTournois();
        break;
    default:
        $table = NULL;
        echo "<div class='error'>Table non trouvée.</div>";
        break;
}

afficherTable($table, $_SESSION['choixTable']);

?>

</body>
</html>