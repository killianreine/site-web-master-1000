<?php
session_start();
$racine = dirname(__DIR__, 2);

require_once $racine.'/connex.php';
require_once $racine.'/includes/fonction_joueur.php';
require_once $racine.'/includes/fonction_tournois.php';
require_once $racine.'/includes/fonction_edition_tournois.php';
require_once $racine.'/includes/fonction_equipe.php';
require_once $racine.'/includes/afficherTable.php';
require_once 'detailsJoueur.php';
require_once 'detailsEquipe.php';
require_once 'detailsTournois.php';
require_once 'detailsEditionTournois.php';

if (isset($_POST['choixTable'])) {
    $_SESSION['choixTable'] = $_POST['choixTable'];
}

if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    $idPost = (int)$_POST['id'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'supprimer' && isset($_POST['idSupp'])) {
    $idSupp = (int)$_POST['idSupp'];

    if ($idSupp > 0) {
        suppIntermed($idSupp);
    } else {
        echo "<div class='error'>L'ID fourni est invalide pour la suppression.</div>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../../balle.png" sizes="16x16" />
    <link rel="stylesheet" href="../../style.css" type="text/css"/>
    <?php
    // Inclure le CSS spécifique à la page de détails
    if (isset($_SESSION['choixTable'])) {
        switch ($_SESSION['choixTable']) {
            case 'Joueur':
                echo '<link rel="stylesheet" href="../../assets/css/detailsJoueur.css" type="text/css"/>';
                break;
            case 'Equipe':
                echo '<link rel="stylesheet" href="../../assets/css/detailsEquipe.css" type="text/css"/>';
                break;
            case 'Tournois':
                echo '<link rel="stylesheet" href="../../assets/css/detailsTournois.css" type="text/css"/>';
                break;
            case 'ETournois':
                echo '<link rel="stylesheet" href="../../assets/css/detailsEditionTournois.css" type="text/css"/>';
                break;
        }
    }
    ?>
    <title>Détails <?php echo $_SESSION['choixTable'] ?> | Master 1000 Tennis</title>
</head>
<body>
<?php
include $racine.'/includes/menuNav.php';
?>

<h1>Détails d'un enregistrement de la table <?php echo($_SESSION['choixTable']) ?></h1>

<?php
if (isset($idPost)) {
    // Contenu de la page selon la table choisie
    switch ($_SESSION['choixTable']) {
        case 'Joueur':
            $tab = getG04JoueurById($idPost);
            echo afficherCarteJoueur($tab);
            break;
        case 'Equipe':
            $tab = getG04EquipeById($idPost);
            echo afficherEquipe($tab);
            break;
        case 'Tournois':
            $tab = getG04TournoisById($idPost);
            echo afficherCarteTournois($tab) ; echo afficherEditionTournois($idPost);
            break;
        case 'ETournois':
            $tab = getG04EditionTournoisById($idPost);
            echo afficherCarteETournois($tab);
            break;
        default:
            echo "Erreur : aucune donnée sélectionnée.";
            break;
    }
} else {
    echo "Erreur : aucune donnée sélectionnée.";
}
?>

<br/>
<div style="text-align: right; margin-top: 50px;">
    <form action="../../index.php" method="get">
        <button type="submit" class="btnretour">Retour à l'accueil</button>
        <input type='hidden' name='table' value='$table'>
    </form>
</div>

</body>
</html>