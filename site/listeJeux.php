<?php

    session_start();


    try {
        $bdd = new PDO('mysql:host=localhost;dbname=ProjetPHP', 'root', '');

    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }
?>

<a href="./Joueur/accueilConnecteJoueur.php">Retour</a>

<table>
    <tr>
        <th>Nom du Jeu</th>
        <th>Catégorie</th>
        <th>Description</th>
    </tr>

    <?php 
    $reqJeu = "SELECT nomJeu, typeJeu, descJeu FROM Jeu ORDER BY nomJeu ASC";
    $resultReqJeu = $bdd->query($reqJeu);

    if ($resultReqJeu->rowCount() > 0) {
        while ($jeu = $resultReqJeu->fetch()) {
            echo "<tr>";
            echo "<td>" . $jeu['nomJeu'] . "</td>";
            echo "<td>" . $jeu['typeJeu']  . "</td>";
            echo "<td>" . $jeu['descJeu'] . "</td>";
            echo "</tr>";
        }
    }
    ?>

</table>