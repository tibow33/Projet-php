<?php
session_start();

try {
    $bdd = new PDO('mysql:host=localhost;dbname=ProjetPHP', 'root', 'root');
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

$idTournoi = $_GET['idTournoi'];

//  Infos du tournoi
$reqTournoi = $bdd->prepare("
    SELECT T.nomTournoi, T.dateDebut, T.dateFin, T.descTournoi, J.nomJeu
    FROM tournoi T
    JOIN jeu J ON J.idJeu = T.idJeu
    WHERE T.idTournoi = ?
");
$reqTournoi->execute([$idTournoi]);
$tournoi = $reqTournoi->fetch();

// Équipes inscrites
$reqEquipes = $bdd->prepare("
    SELECT E.nomTeam, E.tag, E.idTeam
    FROM participe P
    JOIN team E ON E.idTeam = P.idTeam
    WHERE P.idTournoi = ?
");
$reqEquipes->execute([$idTournoi]);
?>

<a href="listeTournoi.php">Retour</a>

<h2>Tournoi : <?php echo $tournoi['nomTournoi']; ?></h2>

<p><strong>Jeu :</strong> <?php echo $tournoi['nomJeu']; ?></p>
<p><strong>Date début :</strong> <?php echo $tournoi['dateDebut']; ?></p>
<p><strong>Date fin :</strong> <?php echo $tournoi['dateFin']; ?></p>
<p><strong>Description :</strong> <?php echo $tournoi['descTournoi']; ?></p>

<hr>

<h3>Équipes participantes</h3>

<?php if ($reqEquipes->rowCount() > 0): ?>
<table border="1">
    <tr>
        <th>Nom de la team</th>
        <th>Tag</th>
        <th>Détails</th>
    </tr>

    <?php while ($e = $reqEquipes->fetch()): ?>
    <tr>
        <td><?php echo $e['nomTeam']; ?></td>
        <td><?php echo $e['tag']; ?></td>
        <td><a href="../Team/team.php?idTeam=<?php echo $e['idTeam']; ?>">Voir la team</a></td>
    </tr>
    <?php endwhile; ?>
</table>
<?php else: ?>
<p>Aucune équipe inscrite pour ce tournoi.</p>
<?php endif; ?>