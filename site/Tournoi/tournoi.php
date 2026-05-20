<?php
session_start();
$pageTitle = 'Détails du tournoi';
$rootPath = '..';

try {
    $bdd = new PDO('mysql:host=localhost;dbname=ProjetPHP', 'root', '');
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

$idTournoi = $_GET['idTournoi'];
$reqTournoi = $bdd->prepare("SELECT T.nomTournoi, T.dateDebut, T.dateFin, T.descTournoi, J.nomJeu
    FROM tournoi T
    JOIN jeu J ON J.idJeu = T.idJeu
    WHERE T.idTournoi = ?");
$reqTournoi->execute([$idTournoi]);
$tournoi = $reqTournoi->fetch();

$reqEquipes = $bdd->prepare("SELECT E.nomTeam, E.tag, E.idTeam
    FROM participe P
    JOIN team E ON E.idTeam = P.idTeam
    WHERE P.idTournoi = ?");
$reqEquipes->execute([$idTournoi]);
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
<main class="mx-auto max-w-6xl px-5 py-10 flex-1">
    <p><a href="listeTournoi.php">Retour</a></p>

    <h2>Tournoi : <?= htmlspecialchars($tournoi['nomTournoi']) ?></h2>

    <p><strong>Jeu :</strong> <?= htmlspecialchars($tournoi['nomJeu']) ?></p>
    <p><strong>Date début :</strong> <?= htmlspecialchars($tournoi['dateDebut']) ?></p>
    <p><strong>Date fin :</strong> <?= htmlspecialchars($tournoi['dateFin']) ?></p>
    <p><strong>Description :</strong> <?= htmlspecialchars($tournoi['descTournoi']) ?></p>

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
            <td><?= htmlspecialchars($e['nomTeam']) ?></td>
            <td><?= htmlspecialchars($e['tag']) ?></td>
            <td><a href="../Team/team.php?idTeam=<?= (int)$e['idTeam'] ?>">Voir la team</a></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <?php else: ?>
        <p>Aucune équipe inscrite pour ce tournoi.</p>
    <?php endif; ?>
</main>
<footer class="border-t border-slate-800 bg-panel/75 px-5 py-6 text-center text-slate-500">
    <p>&copy; 2026 Gaming Hub. Tous droits réservés.</p>
</footer>
</body>
</html>

