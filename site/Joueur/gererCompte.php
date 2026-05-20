<?php
session_start();
$pageTitle = 'Mon compte';
$rootPath = '..';

try {
    $bdd = new PDO('mysql:host=localhost;dbname=ProjetPHP', 'root', '');
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

$idCompte = $_SESSION['idCompte'];
$req = $bdd->prepare("SELECT pseudo, email, dateCreation, descCompte FROM Compte WHERE idCompte = ?");
$req->execute([$idCompte]);
$compte = $req->fetch();
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
            <a href="<?= $rootPath ?>/listeJeux.php" class="rounded-full border border-slate-700 bg-slate-900/80 px-4 py-2 text-slate-200 transition hover:border-glow hover:text-white">Jeux</a>
            <?php if (!empty($_SESSION['pseudo'])): ?>
                <a href="<?= $rootPath ?>/deconnexion.php" class="rounded-full border border-slate-700 bg-slate-900/80 px-4 py-2 text-slate-200 transition hover:border-glow hover:text-white">Déconnexion</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<main class="mx-auto flex max-w-6xl flex-col gap-10 px-5 py-10 flex-1">
    <section class="rounded-[2rem] border border-slate-800 bg-panel/80 p-8 shadow-neon backdrop-blur-xl">
        <div class="mb-8">
            <p class="text-sm uppercase tracking-[0.5em] text-neon/70">Mon Compte</p>
            <h1 class="mt-4 text-4xl font-extrabold tracking-tight text-white sm:text-5xl">Bienvenue, <?= htmlspecialchars($compte['pseudo'], ENT_QUOTES, 'UTF-8') ?></h1>
        </div>
        
        <div class="grid gap-6 lg:grid-cols-2">
            <div class="rounded-3xl border border-slate-700 bg-slate-900/70 p-6">
                <h2 class="text-lg font-semibold text-neon">Informations générales</h2>
                <div class="mt-6 space-y-4">
                    <div class="rounded-xl border border-slate-600 bg-slate-900/50 p-4">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Pseudo</p>
                        <p class="mt-2 text-xl font-bold text-white"><?= htmlspecialchars($compte['pseudo'], ENT_QUOTES, 'UTF-8') ?></p>
                    </div>
                    <div class="rounded-xl border border-slate-600 bg-slate-900/50 p-4">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Email</p>
                        <p class="mt-2 text-lg text-slate-200"><?= htmlspecialchars($compte['email'], ENT_QUOTES, 'UTF-8') ?></p>
                    </div>
                    <div class="rounded-xl border border-slate-600 bg-slate-900/50 p-4">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Compte créé le</p>
                        <p class="mt-2 text-lg text-slate-200"><?= htmlspecialchars($compte['dateCreation'], ENT_QUOTES, 'UTF-8') ?></p>
                    </div>
                    <div class="rounded-xl border border-slate-600 bg-slate-900/50 p-4">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Description</p>
                        <p class="mt-2 text-sm text-slate-300"><?= htmlspecialchars($compte['descCompte'] ?? 'Aucune description', ENT_QUOTES, 'UTF-8') ?></p>
                    </div>
                </div>
            </div>
            
            <div class="rounded-3xl border border-slate-700 bg-slate-900/70 p-6">
                <h2 class="text-lg font-semibold text-neon">Actions</h2>
                <div class="mt-6 space-y-4">
                    <a href="modifierCompte.php" class="block rounded-xl border border-slate-600 bg-slate-900/50 p-4 transition hover:border-accent hover:bg-slate-900/80">
                        <h3 class="font-semibold text-white">Modifier mon compte</h3>
                        <p class="mt-2 text-sm text-slate-400">Mets à jour tes informations personnelles</p>
                    </a>
                    <a href="supprimerCompte.php" class="block rounded-xl border border-red-700/50 bg-red-950/30 p-4 transition hover:border-red-500 hover:bg-red-950/50">
                        <h3 class="font-semibold text-red-400">Supprimer mon compte</h3>
                        <p class="mt-2 text-sm text-red-300/70">Action irréversible - supprimer définitivement ton compte</p>
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>
<footer class="border-t border-slate-800 bg-panel/75 px-5 py-6 text-center text-slate-500">
    <p>&copy; 2026 Gaming Hub. Tous droits réservés.</p>
</footer>
</body>
</html>

