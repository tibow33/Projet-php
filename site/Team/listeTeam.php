<?php

    session_start();


    try {
        $bdd = new PDO('mysql:host=localhost;dbname=ProjetPHP', 'root', '');

    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    //Récupère l'id de la team de l'utilisateur si il en a une
    $idCompte = $_SESSION['idCompte'];

    $reqUserTeam = $bdd->prepare("SELECT idTeam FROM compte WHERE idCompte = ?");
    $reqUserTeam->execute([$idCompte]);
    $userTeam = $reqUserTeam->fetch();

    $idTeamUser = $userTeam ? $userTeam['idTeam'] : null;
?>

<a href="./../Joueur/accueilConnecteJoueur.php">Retour</a>

<table>
    <tr>
        <th>Nom de la team</th>
        <th>Leader de la Team</th>
        <th>Tag</th>
        <th>Action</th>
    </tr>

    <?php 
    $reqTeam = "SELECT idTeam, nomTeam, chef, tag FROM Team ORDER BY nomTeam ASC";
    $resultReqTeam = $bdd->query($reqTeam);

    if ($resultReqTeam->rowCount() > 0) {
        while ($team = $resultReqTeam->fetch()) {
            echo "<tr>";
            echo "<td><a href='team.php?idTeam=" . $team['idTeam'] . "'>" . $team['nomTeam'] . "</a></td>";
            echo "<td>" . $team['chef']  . "</td>";
            echo "<td>" . $team['tag'] . "</td>";

            echo "<td>";
            if ($idTeamUser == null) {
                // Le joueur n'a pas de team
                echo "<a href='rejoindreTeam.php?idTeam=" . $team['idTeam'] . "'>Rejoindre</a>";
            } else {
                // Le joueur a déjà une team
                if ($idTeamUser == $team['idTeam']) {
                    echo "<span style='color: green; font-weight: bold;'>Rejoint !</span>";
                } else {
                    echo "—";
                }
            }
            echo "</td>";

            echo "</tr>";
        }
    }
    ?>

</table>
