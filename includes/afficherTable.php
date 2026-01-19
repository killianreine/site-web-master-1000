<?php
require_once 'fonction_globale.php';
$uri = $_SERVER['REQUEST_URI'];
preg_match('#^/~([^/]+)/#', $uri, $matches);

if (isset($matches[1])) {
    $user = $matches[1];
    $baseUrl = "/~$user/projet";
} else {
    $user = "";
    $baseUrl = "/projet";
}

function afficherListeEnregistrement(array $tab, String $table) {
    if (empty($tab)) {
        echo "<p>Tableau vide.</p>";
        return;
    }
    echo "<table class='table'>";
    echo "<tr>";
    foreach (array_keys($tab[0]) as $cle) {
        echo "<th>" . $cle . "</th>";
    }
    echo "</tr>";
    // Affichage des valeurs
    foreach ($tab as $ligne) {
        echo "<tr>";
        foreach ($ligne as $information) {
            echo "<td>" . $information . "</td>";
        }
        if (isset($ligne['id_joueur'])) {
            colBouton($ligne['id_joueur'], $table);
        } elseif (isset($ligne['id_equipe'])) {
            colBouton($ligne['id_equipe'], $table);
        }elseif (isset($ligne['id_edition'])) {
            colBouton($ligne['id_edition'], $table);
        } elseif (isset($ligne['id_tournois'])) {
            colBouton($ligne['id_tournois'], $table);
        } 
         // Fin de la ligne
        echo "</tr>";
    }
    echo "</table>";
}

function afficherEnregistrement(array $enregistrement) {
    if (empty($enregistrement)) {
        return;
    }
    echo "<table class='table'>";
    // Affichage des clés en première ligne
    echo "<tr>";
    foreach ($enregistrement as $cle => $valeur) {
        echo "<th>" . $cle . "</th>";
    }
    echo "</tr>";
    // Affichage des valeurs en deuxième ligne
    echo "<tr>";
    foreach ($enregistrement as $valeur) {
        echo "<td>" . $valeur . "</td>";
    }
    echo "</tr>";

    echo "</table>";
}


function afficherTable($table, String $choixTable) {
    if ($table==null) return;
    if (empty($table)) {
        return;
    }
    // Vérifie si c'est un tableau associatif unique ou un tableau de tableaux
    if (isset($table[0]) && is_array($table[0])) {
        afficherListeEnregistrement($table, $choixTable);
    } else {
        afficherEnregistrement($table);
    }
}

function colBouton(int $id, String $table){
    global $baseUrl;
    // Détailler
    echo "<td class='buttons'>
    <form method='post' action='$baseUrl/pages/detailler/details.php' style='display:inline;'>
        <input type='hidden' name='id' value='$id'>
        <input type='hidden' name='action' value='detail'>
        <input type='hidden' name='choixTable' value='$table'>
        <button type='submit' class='detailButton actionButton'>Détailler</button>
    </form>
    </td>";

    // Modifier
    echo "<td class='buttons'>
    <form method='post' action='$baseUrl/pages/modification/modifier.php' style='display:inline;'>
        <input type='hidden' name='id' value='$id'>
        <input type='hidden' name='action' value='modifier'>
        <input type='hidden' name='choixTable' value='$table'>
        <button type='submit' class='modifButton actionButton'>Modifier</button>
    </form>
    </td>";

    // Supprimer
    echo "<td class='buttons'>
    <form method='post' action='' style='display:inline;' onsubmit=\"return confirm('Êtes-vous sûr de vouloir supprimer cet élément ?');\">
        <input type='hidden' name='idSupp' value='$id'>
        <input type='hidden' name='action' value='supprimer'>
        <input type='hidden' name='choixTable' value='$table'>
        <button type='submit' class='suppButton actionButton'>Supprimer</button>
    </form>
    </td>";
}

/**
 * Fonction pour afficher table sans boutons
 * @description Cette fonction affiche une table sans les boutons de détail, modifier et supprimer.
 * * @param array $table
 * * @return void
 */
function afficherTableSansBouton(array $tab) {
    if (empty($tab)) {
        echo "<p>Tableau vide.</p>";
        return;
    }
    echo "<table class='table'>";
    echo "<tr>";
    foreach (array_keys($tab[0]) as $cle) {
        echo "<th>" . $cle . "</th>";
    }
    echo "</tr>";
    // Affichage des valeurs
    foreach ($tab as $ligne) {
        echo "<tr>";
        foreach ($ligne as $information) {
            echo "<td>" . $information . "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
}

?>