<?php
session_start();
$pageTitle = 'Mes tournois';
$rootPath = '..';

try {
    $bdd = new PDO('mysql:host=localhost;dbname=ProjetPHP', 'root', '');
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

$idCompte = $_SESSION['idCompte'];

$stmt = $bdd->prepare("SELECT t.idTournoi, t.nomTournoi, t.dateDebut, t.dateFin,
        t.statut, t.nbTeam, j.nomJeu,
        COUNT(p.idTeam) AS nbInscrits
    FROM tournoi t
    LEFT JOIN jeu j ON j.idJeu = t.idJeu
    LEFT JOIN participe p ON p.idTournoi = t.idTournoi
    WHERE t.createur = ?
    GROUP BY t.idTournoi
    ORDER BY t.dateDebut DESC");
$stmt->execute([$idCompte]);
$tournois = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <section class="rounded-[1.5rem] border border-slate-800 bg-panel/80 p-6 shadow-neon backdrop-blur-xl">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-white">Mes tournois</h1>
                <p class="text-sm text-slate-400">Retrouvez ici tous les tournois que vous avez créés.</p>
            </div>
            <a href="creationTournoi.php" class="rounded-full border border-slate-700 bg-slate-900/80 px-4 py-2 text-slate-200 transition hover:border-accent hover:text-white">+ Créer un tournoi</a>
        </div>

        <?php if (empty($tournois)): ?>
            <div class="py-8 text-center text-slate-400">Vous n'avez pas encore créé de tournoi.</div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-sm text-slate-300 border-b border-slate-700">
                            <th class="py-3">Nom</th>
                            <th class="py-3">Jeu</th>
                            <th class="py-3">Date début</th>
                            <th class="py-3">Date fin</th>
                            <th class="py-3">Équipes</th>
                            <th class="py-3">Statut</th>
                            <th class="py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tournois as $t): ?>
                        <tr class="border-b border-slate-800 hover:bg-slate-900/40">
                            <td class="py-3"><?= htmlspecialchars($t['nomTournoi']) ?></td>
                            <td class="py-3"><?= htmlspecialchars($t['nomJeu'] ?? '—') ?></td>
                            <td class="py-3"><?= $t['dateDebut'] ? date('d/m/Y', strtotime($t['dateDebut'])) : '—' ?></td>
                            <td class="py-3"><?= $t['dateFin'] ? date('d/m/Y', strtotime($t['dateFin'])) : '—' ?></td>
                            <td class="py-3"><?= (int)$t['nbInscrits'] ?> / <?= (int)$t['nbTeam'] ?></td>
                            <td class="py-3"><?= htmlspecialchars($t['statut'] ?? 'Actif') ?></td>
                            <td class="py-3"><a href="./gererTournoi.php?idTournoi=<?= (int)$t['idTournoi'] ?>" class="text-neon">Gérer →</a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>
</main>
<footer class="border-t border-slate-800 bg-panel/75 px-5 py-6 text-center text-slate-500">
    <p>&copy; 2026 Gaming Hub. Tous droits réservés.</p>
</footer>
</body>
</html>

