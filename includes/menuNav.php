<?php

$uri = $_SERVER['REQUEST_URI'];
preg_match('#^/~([^/]+)/#', $uri, $matches);

if (isset($matches[1])) {
    $user = $matches[1];
    $baseUrl = "/~$user/projet";
} else {
    $user = "";
    $baseUrl = "/projet";
}

$testJoueur = "$baseUrl/tests/test_joueur.php";
$testEquipe = "$baseUrl/tests/test_equipe.php";
$testTournoi = "$baseUrl/tests/test_tournois.php";
$testETournoi = "$baseUrl/tests/test_edition_tournois.php";

$lienIcone = "$baseUrl/tennis.png";
?>

<div class="navigation">
    <div class='navIcon'>
        <img class='iconeTennis' src=<?php echo $lienIcone ?> alt="Tennis Icon" />
        <p>Master 1000
            <br/>Tennis
        </p>
        <br/>
        <p id="groupe">
            Damien <span class='nom'>Bonnegent</span> - Killian <span class='nom'>Reine</span> - <span class='nom'>ThomTrooper</span>
        </p>
    </div>
    <div class="navLink">
        <nav class="navbar">
            <ul>
                <li><a href= <?php echo $testJoueur;?> >Tests des joueur</a></li>
                <li><a href=<?php echo $testEquipe;?> >Tests des équipe</a></li>
                <li><a href=<?php echo $testETournoi;?> >Tests des éditions</a></li>
                <li><a href=<?php echo $testTournoi;?> >Tests des tournois</a></li>
            </ul>
        </nav>
    </div>
</div>
