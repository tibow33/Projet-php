<?php
session_start();
$pageTitle = 'Accueil';
$rootPath = '.';

try {
    $bdd = new PDO('mysql:host=localhost;dbname=ProjetPHP', 'root', '');
    $tournoisCount = (int) $bdd->query("SELECT COUNT(*) FROM tournoi")->fetchColumn();
    $teamsCount = (int) $bdd->query("SELECT COUNT(*) FROM team")->fetchColumn();
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}
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
<main class="mx-auto flex max-w-6xl flex-col gap-10 px-5 py-10 flex-1">
    <section class="rounded-[2rem] border border-slate-800 bg-panel/80 p-8 shadow-neon backdrop-blur-xl">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div class="max-w-2xl">
                <p class="text-sm uppercase tracking-[0.5em] text-neon/70">Bienvenue</p>
                <h1 class="mt-4 text-4xl font-extrabold tracking-tight text-white sm:text-5xl">Bienvenue sur Gaming Hub</h1>
                <p class="mt-4 max-w-xl text-lg leading-8 text-slate-300">Rejoins une communauté de joueurs passionnés, crée ta team, participe aux tournois et rivalise dans un univers cybernétique.</p>
                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="<?= $rootPath ?>/inscription.php" class="inline-flex items-center rounded-full bg-accent px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-accent/20 transition hover:bg-accent/90">Créer un compte</a>
                    <a href="<?= $rootPath ?>/listeJeux.php" class="inline-flex items-center rounded-full border border-slate-700 bg-slate-900/80 px-6 py-3 text-sm font-semibold text-slate-200 transition hover:border-glow hover:text-white">Voir les jeux</a>
                </div>
            </div>
            <div class="rounded-[2rem] border border-slate-800 bg-panel/90 p-6 text-slate-200 shadow-neon sm:p-8">
                <div class="space-y-4">
                    <div class="rounded-2xl border border-slate-700 bg-slate-900/70 p-5">
                        <p class="text-sm uppercase tracking-[0.3em] text-slate-400">Statut du site</p>
                        <p class="mt-2 text-2xl font-semibold text-white">En ligne</p>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="rounded-2xl border border-slate-700 bg-slate-900/70 p-5 text-center">
                            <p class="text-sm text-slate-400">Tournois</p>
                            <p class="mt-3 text-4xl font-bold text-white"><?= htmlspecialchars($tournoisCount, ENT_QUOTES, 'UTF-8') ?></p>
                        </div>
                        <div class="rounded-2xl border border-slate-700 bg-slate-900/70 p-5 text-center">
                            <p class="text-sm text-slate-400">Équipes</p>
                            <p class="mt-3 text-4xl font-bold text-white"><?= htmlspecialchars($teamsCount, ENT_QUOTES, 'UTF-8') ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="grid gap-6 lg:grid-cols-2">
        <article class="rounded-[2rem] border border-slate-800 bg-panel/80 p-8 shadow-neon backdrop-blur-xl">
            <h2 class="text-2xl font-bold text-white">Fonctionnalités</h2>
            <div class="mt-6 space-y-4 text-slate-300">
                <div class="rounded-3xl border border-slate-700 bg-slate-900/70 p-5">
                    <h3 class="text-lg font-semibold text-neon">Tournois</h3>
                    <p class="mt-2 text-sm text-slate-400">Organise ou rejoins des tournois compétitifs et gagne des récompenses.</p>
                </div>
                <div class="rounded-3xl border border-slate-700 bg-slate-900/70 p-5">
                    <h3 class="text-lg font-semibold text-neon">Équipes</h3>
                    <p class="mt-2 text-sm text-slate-400">Crée des teams, invite des amis et construis la meilleure synergie.</p>
                </div>
                <div class="rounded-3xl border border-slate-700 bg-slate-900/70 p-5">
                    <h3 class="text-lg font-semibold text-neon">Compétition</h3>
                    <p class="mt-2 text-sm text-slate-400">Affronte d'autres joueurs et grimpe dans les classements.</p>
                </div>
            </div>
        </article>

        <article class="rounded-[2rem] border border-slate-800 bg-panel/80 p-8 shadow-neon backdrop-blur-xl">
            <h2 class="text-2xl font-bold text-white">Accès rapide</h2>
            <ul class="mt-6 space-y-4 text-slate-200">
                <li class="rounded-3xl border border-slate-700 bg-slate-900/70 p-4 transition hover:border-glow">
                    <a href="<?= $rootPath ?>/connexion.php" class="flex items-center justify-between text-sm font-medium">Connexion <span class="text-neon">→</span></a>
                </li>
                <li class="rounded-3xl border border-slate-700 bg-slate-900/70 p-4 transition hover:border-glow">
                    <a href="<?= $rootPath ?>/inscription.php" class="flex items-center justify-between text-sm font-medium">Inscription <span class="text-neon">→</span></a>
                </li>
                <li class="rounded-3xl border border-slate-700 bg-slate-900/70 p-4 transition hover:border-glow">
                    <a href="<?= $rootPath ?>/listeJeux.php" class="flex items-center justify-between text-sm font-medium">Accéder à la liste des jeux <span class="text-neon">→</span></a>
                </li>
            </ul>
        </article>
    </section>
</main>
<footer class="border-t border-slate-800 bg-panel/75 px-5 py-6 text-center text-slate-500">
    <p>&copy; 2026 Gaming Hub. Tous droits réservés.</p>
</footer>
</body>
</html>


