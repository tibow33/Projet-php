<?php

    session_start();


    try {
        $bdd = new PDO('mysql:host=localhost;dbname=ProjetPHP', 'root', 'root');

    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }
?>

<a href="./Joueur/accueilConnecteJoueur.php">Retour</a>

<table>
    <tr>
        <th>Pseudo du Joueur</th>
        <th>Team du Joueur</th>
        <th>Description</th>
    </tr>

    <?php 
    $reqCompte = "SELECT pseudo, nomTeam, descCompte FROM Compte ORDER BY nomJeu ASC";
    $resultReqCompte = $bdd->query($reqCompte);

    if ($resultReqCompte->rowCount() > 0) {
        while ($compte = $resultReqCompte->fetch()) {
            echo "<tr>";
            echo "<td>" . $compte['pseudo'] . "</td>";
            echo "<td>" . $compte['nomTeam']  . "</td>";
            echo "<td>" . $compte['descCompte'] . "</td>";
            echo "</tr>";
        }
    }
    ?>
</table>