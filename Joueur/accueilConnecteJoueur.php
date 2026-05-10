<?php

    session_start();
    $bdd = new PDO('mysql:host=localhost;dbname=ProjetPHP', 'root', 'root') or die('Connexion Impossible');
?>

<a href="./../deconnexion.php" class="has-text-primary-light p-4">Déconnexion</a>
<a href="./../Team/creationTeam.php">Créer une team</a>
<a href="./../Tournoi/creationTournoi.php">Créer un tournoi</a>
<a href="./gererCompte.php">Gérer son compte</a>
<a href="./../Team/listeTeam.php">Afficher la liste des teams</a>
<a href="./../Tournoi/listeTournoi.php">Afficher la liste des tournois</a>