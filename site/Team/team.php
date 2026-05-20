<?php
session_start();
$pageTitle = 'Détails de la team';
$rootPath = '..';

try {
    $bdd = new PDO('mysql:host=localhost;dbname=ProjetPHP', 'root', 'root');
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

$idTeam = $_GET['idTeam'];
$idCompte = $_SESSION['idCompte'];

$reqTeam = $bdd->prepare("SELECT nomTeam, tag, chef FROM team WHERE idTeam = ?");
$reqTeam->execute([$idTeam]);
$team = $reqTeam->fetch();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        bg: '#050816',
                        panel: '#111827',
                        neon: '#00F6FF',
                        accent: '#7C3AED'
                    },
                    boxShadow: {
                        neon: '0 0 40px rgba(0,246,255,0.25)'
                    }
                }
            }
        };
    </script>
    <link rel="stylesheet" href="<?= $rootPath ?>/css/style.css">
</head>
<body class="min-h-screen bg-bg text-slate-100 antialiased flex flex-col">
<?php include $rootPath . '/inc/header_connected.php'; ?>
</header>
<main class="mx-auto max-w-6xl px-5 py-10 flex-1">
    <?php
    $reqTournois = $bdd->prepare("SELECT T.nomTournoi, T.dateDebut, T.dateFin, J.nomJeu
        FROM participe P
        JOIN tournoi T ON T.idTournoi = P.idTournoi
        JOIN jeu J ON J.idJeu = T.idJeu
        WHERE P.idTeam = ?");
    $reqTournois->execute([$idTeam]);

    $reqMembres = $bdd->prepare("SELECT pseudo FROM compte WHERE idTeam = ?");
    $reqMembres->execute([$idTeam]);

    $reqUser = $bdd->prepare("SELECT idTeam FROM compte WHERE idCompte = ?");
    $reqUser->execute([$idCompte]);
    $user = $reqUser->fetch();
    $idTeamUser = $user ? $user['idTeam'] : null;
    ?>

    <p><a href="listeTeam.php">Retour</a></p>

    <h2>Team : <?= htmlspecialchars($team['nomTeam']) ?></h2>
    <p>Tag : <?= htmlspecialchars($team['tag']) ?></p>
    <p>Chef : <?= htmlspecialchars($team['chef']) ?></p>

<?php if ($idTeamUser == $idTeam): ?>
    <p><a href="quitterTeam.php">Quitter cette team</a></p>
<?php endif; ?>

<?php if ($idTeamUser === null): ?>
    <a href="rejoindreTeam.php?idTeam=<?= $idTeam ?>">Rejoindre cette team</a>
<?php endif; ?>

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
        <td><?= htmlspecialchars($t['nomTournoi']) ?></td>
        <td><?= htmlspecialchars($t['nomJeu']) ?></td>
        <td><?= htmlspecialchars($t['dateDebut']) ?></td>
        <td><?= htmlspecialchars($t['dateFin']) ?></td>
    </tr>
    <?php endwhile; ?>
</table>
<?php else: ?>
<p>Aucun tournoi pour cette team.</p>
<?php endif; ?>

<h3>Membres de la team</h3>

<?php if ($reqMembres->rowCount() > 0): ?>
<ul>
    <?php while ($m = $reqMembres->fetch()): ?>
        <li><?= htmlspecialchars($m['pseudo']) ?></li>
    <?php endwhile; ?>
</ul>
<?php else: ?>
<p>Aucun membre dans cette team.</p>
<?php endif; ?>
</main>
<footer class="border-t border-slate-800 bg-panel/75 px-5 py-6 text-center text-slate-500">
    <p>&copy; 2026 Gaming Hub. Tous droits réservés.</p>
</footer>
</body>
</html>
