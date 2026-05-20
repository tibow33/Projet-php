<?php
session_start();
$pageTitle = 'Espace joueur';
$rootPath = '..';

try {
    $bdd = new PDO('mysql:host=localhost;dbname=ProjetPHP', 'root', '');
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
                <p class="text-sm uppercase tracking-[0.5em] text-neon/70">Espace joueur</p>
                <h1 class="mt-4 text-4xl font-extrabold tracking-tight text-white sm:text-5xl">Bienvenue, <?= htmlspecialchars($_SESSION['pseudo'] ?? 'joueur', ENT_QUOTES, 'UTF-8') ?></h1>
                <p class="mt-4 max-w-xl text-lg leading-8 text-slate-300">Gère ton compte, crée une team, rejoins des tournois et retrouve rapidement toutes les sections importantes de ta carrière gaming.</p>
                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="<?= $rootPath ?>/Joueur/gererCompte.php" class="inline-flex items-center rounded-full bg-accent px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-accent/20 transition hover:bg-accent/90">Gérer mon compte</a>
                    <a href="<?= $rootPath ?>/GestionTournoi/mesTournois.php" class="inline-flex items-center rounded-full border border-slate-700 bg-slate-900/80 px-6 py-3 text-sm font-semibold text-slate-200 transition hover:border-glow hover:text-white">Mes tournois</a>
                </div>
            </div>
            <div class="rounded-3xl bg-slate-950/80 p-6 text-slate-200 shadow-neon sm:p-8">
                <div class="space-y-4">
                    <div class="rounded-3xl border border-slate-700 bg-slate-900/70 p-5">
                        <p class="text-sm uppercase tracking-[0.3em] text-slate-400">Statut</p>
                        <p class="mt-2 text-2xl font-semibold text-white">Connecté et prêt à jouer</p>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-3">
                        <div class="rounded-3xl border border-slate-700 bg-slate-900/70 p-4 text-center">
                            <p class="text-sm text-slate-400">Tournois</p>
                            <p class="mt-2 text-xl font-bold text-white">Voir mes tournois</p>
                        </div>
                        <div class="rounded-3xl border border-slate-700 bg-slate-900/70 p-4 text-center">
                            <p class="text-sm text-slate-400">Teams</p>
                            <p class="mt-2 text-xl font-bold text-white">Créer ou rejoindre</p>
                        </div>
                        <div class="rounded-3xl border border-slate-700 bg-slate-900/70 p-4 text-center">
                            <p class="text-sm text-slate-400">Compte</p>
                            <p class="mt-2 text-xl font-bold text-white">Mise à jour rapide</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="grid gap-6 lg:grid-cols-2">
        <article class="rounded-[2rem] border border-slate-800 bg-panel/80 p-8 shadow-neon backdrop-blur-xl">
            <h2 class="text-2xl font-bold text-white">Actions rapides</h2>
            <div class="mt-6 space-y-4 text-slate-300">
                <div class="rounded-3xl border border-slate-700 bg-slate-900/70 p-5">
                    <h3 class="text-lg font-semibold text-neon">Créer une team</h3>
                    <p class="mt-2 text-sm text-slate-400">Lance ta team, invite des joueurs et commence à dominer les compétitions.</p>
                    <a href="<?= $rootPath ?>/Team/creationTeam.php" class="mt-4 inline-flex rounded-full bg-slate-800 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-700">Créer</a>
                </div>
                <div class="rounded-3xl border border-slate-700 bg-slate-900/70 p-5">
                    <h3 class="text-lg font-semibold text-neon">Créer un tournoi</h3>
                    <p class="mt-2 text-sm text-slate-400">Organise ton propre tournoi et gère les matchs avec efficacité.</p>
                    <a href="<?= $rootPath ?>/Tournoi/creationTournoi.php" class="mt-4 inline-flex rounded-full bg-slate-800 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-700">Créer</a>
                </div>
                <div class="rounded-3xl border border-slate-700 bg-slate-900/70 p-5">
                    <h3 class="text-lg font-semibold text-neon">Gérer mon compte</h3>
                    <p class="mt-2 text-sm text-slate-400">Modifie tes informations et personnalise tes préférences de jeu.</p>
                    <a href="<?= $rootPath ?>/Joueur/gererCompte.php" class="mt-4 inline-flex rounded-full bg-slate-800 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-700">Accéder</a>
                </div>
            </div>
        </article>

        <article class="rounded-[2rem] border border-slate-800 bg-panel/80 p-8 shadow-neon backdrop-blur-xl">
            <h2 class="text-2xl font-bold text-white">Navigation</h2>
            <ul class="mt-6 space-y-4 text-slate-200">
                <li class="rounded-3xl border border-slate-700 bg-slate-900/70 p-4 transition hover:border-glow">
                    <a href="<?= $rootPath ?>/Team/listeTeam.php" class="flex items-center justify-between text-sm font-medium">Liste des teams <span class="text-neon">→</span></a>
                </li>
                <li class="rounded-3xl border border-slate-700 bg-slate-900/70 p-4 transition hover:border-glow">
                    <a href="<?= $rootPath ?>/Tournoi/listeTournoi.php" class="flex items-center justify-between text-sm font-medium">Liste des tournois <span class="text-neon">→</span></a>
                </li>
                <li class="rounded-3xl border border-slate-700 bg-slate-900/70 p-4 transition hover:border-glow">
                    <a href="<?= $rootPath ?>/GestionTournoi/mesTournois.php" class="flex items-center justify-between text-sm font-medium">Mes tournois <span class="text-neon">→</span></a>
                </li>
                <li class="rounded-3xl border border-slate-700 bg-slate-900/70 p-4 transition hover:border-glow">
                    <a href="<?= $rootPath ?>/deconnexion.php" class="flex items-center justify-between text-sm font-medium">Déconnexion <span class="text-neon">→</span></a>
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


