<?php

    session_start();


    try {
        $bdd = new PDO('mysql:host=localhost;dbname=ProjetPHP', 'root', '');

    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    //Récupère l'id de l'utilisateur si c'est un chef de team
    $idCompte = $_SESSION['idCompte'];

    $reqChefTeam = $bdd->prepare("SELECT idTeam FROM team WHERE chef = ?");
    $reqChefTeam->execute([$idCompte]);
    $team = $reqChefTeam->fetch();
    
    $idChef = $team ? $team['idTeam'] : null;
?>

<a href="./../Joueur/accueilConnecteJoueur.php">Retour</a>

<table>
    <tr>
        <th>Nom du tournoi</th>
        <th>Jeu</th>
        <th>Date de début</th>
        <th>Date de fin</th>
        <th>Nombre de team</th>
        <th>Description du tournoi</th>
        <th>Nom du créateur</th>
        <th>Action</th>
    </tr>

    <?php 
    $reqTournoi = "SELECT idTournoi, nomTournoi, dateDebut, dateFin, descTournoi, nbTeam, pseudo, nomJeu
                FROM tournoi T
                JOIN compte ON idCompte = createur
                JOIN jeu J ON J.idJeu = T.idJeu";
    $resultReqTournoi = $bdd->query($reqTournoi);

    if ($resultReqTournoi->rowCount() > 0) {
        while ($tournoi = $resultReqTournoi->fetch()) {

            // Vérifier si la team du chef est inscrite
            $reqVerif = $bdd->prepare("SELECT * FROM participe WHERE idTeam = ? AND idTournoi = ?");
            $reqVerif->execute([$idChef, $tournoi['idTournoi']]);
            $dejaRejoint = $reqVerif->rowCount() > 0;

            echo "<tr>";
            echo "<td><a href='tournoi.php?idTournoi=" . $tournoi['idTournoi'] . "'>" . $tournoi['nomTournoi'] . "</a></td>";
            echo "<td>" . $tournoi['nomJeu']  . "</td>";
            echo "<td>" . $tournoi['dateDebut'] . "</td>";
            echo "<td>" . $tournoi['dateFin'] . "</td>";
            echo "<td>" . $tournoi['nbTeam'] . "</td>";
            echo "<td>" . $tournoi['descTournoi'] . "</td>";
            echo "<td>" . $tournoi['pseudo'] . "</td>";

            echo "<td>";
            if ($idChef) {
                if ($dejaRejoint) {
                    echo "<span style='color: green; font-weight: bold;'>Rejoint !</span>";
                } else {
                    echo "<a href='rejoindreTournoi.php?idTournoi=" . $tournoi['idTournoi'] . "'>Rejoindre</a>";
                }   
            } else {
                echo "—"; // pas chef
            }
            echo "</td>";
            echo "</tr>";
        }
    }
    ?>

</table>