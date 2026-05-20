<?php
session_start();
$pageTitle = 'Liste des teams';
$rootPath = '..';

try {
    $bdd = new PDO('mysql:host=localhost;dbname=ProjetPHP', 'root', '');
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

if (!isset($_SESSION['idCompte'])) {
    header('Location: ../connexion.php');
    exit;
}

$stmt = $bdd->query("SELECT idTeam, nomTeam, tag, chef FROM team ORDER BY nomTeam ASC");
$teams = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    </nav>
</header>
<main class="mx-auto max-w-6xl px-5 py-10 flex-1">
    <h1>Liste des teams</h1>
    <p>Retrouvez ici toutes les équipes enregistrées sur la plateforme.</p>
    <p><a href="creationTeam.php">+ Créer une team</a></p>

    <?php if (empty($teams)): ?>
        <p>Aucune team disponible pour le moment.</p>
    <?php else: ?>
        <table class="w-full mt-6 text-left border-collapse">
            <thead>
                <tr class="text-sm text-slate-300 border-b border-slate-700">
                    <th class="py-3">Nom</th>
                    <th class="py-3">Tag</th>
                    <th class="py-3">Chef</th>
                    <th class="py-3">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($teams as $team): ?>
                <tr class="border-b border-slate-800">
                    <td class="py-3"><?= htmlspecialchars($team['nomTeam']) ?></td>
                    <td class="py-3"><?= htmlspecialchars($team['tag'] ?? '—') ?></td>
                    <td class="py-3"><?= htmlspecialchars($team['chef'] ?? '—') ?></td>
                    <td class="py-3"><a href="team.php?idTeam=<?= (int)$team['idTeam'] ?>" class="text-neon">Voir →</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>
<footer class="border-t border-slate-800 bg-panel/75 px-5 py-6 text-center text-slate-500">
    <p>&copy; 2026 Gaming Hub. Tous droits réservés.</p>
</footer>
</body>
</html>

