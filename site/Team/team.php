<?php
session_start();

try {
    $bdd = new PDO('mysql:host=localhost;dbname=ProjetPHP', 'root', '');
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

$idTeam = $_GET['idTeam'];
$idCompte = $_SESSION['idCompte'];

// Infos de la team
$reqTeam = $bdd->prepare("SELECT nomTeam, tag, chef FROM team WHERE idTeam = ?");
$reqTeam->execute([$idTeam]);
$team = $reqTeam->fetch();

// Tournois auxquels la team a participé
$reqTournois = $bdd->prepare("
    SELECT T.nomTournoi, T.dateDebut, T.dateFin, J.nomJeu
    FROM participer P
    JOIN tournoi T ON T.idTournoi = P.idTournoi
    JOIN jeu J ON J.idJeu = T.idJeu
    WHERE P.idTeam = ?
");
$reqTournois->execute([$idTeam]);

// Membres de la team
$reqMembres = $bdd->prepare("SELECT pseudo FROM compte WHERE idTeam = ?");
$reqMembres->execute([$idTeam]);

// Team actuelle du joueur
$reqUser = $bdd->prepare("SELECT idTeam FROM compte WHERE idCompte = ?");
$reqUser->execute([$idCompte]);
$user = $reqUser->fetch();
$idTeamUser = $user ? $user['idTeam'] : null;
?>

<a href="listeTeam.php">Retour</a>

<h2>Team : <?php echo $team['nomTeam']; ?></h2>
<p>Tag : <?php echo $team['tag']; ?></p>
<p>Chef : <?php echo $team['chef']; ?></p>

<?php if ($idTeamUser == $idTeam): ?>
    <p><a href="quitterTeam.php">Quitter cette team</a></p>
<?php endif; ?>

<hr>

<h3>Tournois auxquels la team a participé</h3>

<?php if ($reqTournois->rowCount() > 0): ?>
<table>
    <tr>
        <th>Nom du tournoi</th>
        <th>Jeu</th>
        <th>Date début</th>
        <th>Date fin</th>
    </tr>

    <?php while ($t = $reqTournois->fetch()): ?>
    <tr>
        <td><?php echo $t['nomTournoi']; ?></td>
        <td><?php echo $t['nomJeu']; ?></td>
        <td><?php echo $t['dateDebut']; ?></td>
        <td><?php echo $t['dateFin']; ?></td>
    </tr>
    <?php endwhile; ?>
</table>
<?php else: ?>
<p>Aucun tournoi pour cette team.</p>
<?php endif; ?>

<hr>

<h3>Membres de la team</h3>

<?php if ($reqMembres->rowCount() > 0): ?>
<ul>
    <?php while ($m = $reqMembres->fetch()): ?>
        <li><?php echo $m['pseudo']; ?></li>
    <?php endwhile; ?>
</ul>
<?php else: ?>
<p>Aucun membre dans cette team.</p>
<?php endif; ?>