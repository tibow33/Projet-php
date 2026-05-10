<?php

    $bdd = new PDO('mysql:host=localhost;dbname=ProjetPHP', 'root', '') or die('Connexion Impossible');
?>

<!DOCTYPE HTML>
<html lang="fr">

    <head>
        <meta charset="UTF_8">
    </head> 

    <div class="is-flex">
        <a href="./connexion.php" class="has-text-primary-light p-4">Connexion</a>
        <a href="./inscription.php" class="has-text-primary-light p-4">Inscription</a>
        <a href="./listeJeux.php">Acceder à la liste de jeux</a>
    </div> 
