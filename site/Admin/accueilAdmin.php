<?php

session_start();

$pageTitle = 'Accueil Admin';
$rootPath = '..';

try {
    $bdd = new PDO('mysql:host=localhost;dbname=ProjetPHP', 'root', '');
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

$idCompte = $_SESSION['idCompte'] ?? null;

if (!$idCompte) {
    header('Location: ../connexion.php');
    exit;
}

$reqAdmin = $bdd->prepare('SELECT typeCompte FROM compte WHERE idCompte = ?');
$reqAdmin->execute([$idCompte]);
$admin = $reqAdmin->fetch();

if (!$admin || $admin['typeCompte'] !== 'Admin') {
    die('Accès refusé. Vous n\'êtes pas administrateur.');
}

$pseudo = $_SESSION['pseudo'] ?? 'Admin';
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
<header class="border-b border-slate-800 bg-panel/90 backdrop-blur-md shadow-neon">
    <nav class="mx-auto flex max-w-6xl flex-wrap items-center gap-3 px-5 py-4 text-sm">
        <a href="<?= $rootPath ?>/accueil.php">Accueil</a>
        <a href="<?= $rootPath ?>/connexion.php">Connexion</a>
        <a href="<?= $rootPath ?>/inscription.php">Inscription</a>
        <a href="<?= $rootPath ?>/listeJeux.php">Jeux</a>
        <?php if (!empty($_SESSION['pseudo'])): ?>
            <a href="<?= $rootPath ?>/deconnexion.php">Déconnexion</a>
            <a href="<?= $rootPath ?>/Joueur/accueilConnecteJoueur.php">Mon Espace</a>
        <?php endif; ?>
    </nav>
</header>
<main class="mx-auto max-w-6xl px-5 py-10 flex-1">
    <section class="rounded-[2rem] border border-slate-800 bg-panel/80 p-8 shadow-neon backdrop-blur-xl">
        <p class="text-sm uppercase tracking-[0.35em] text-neon/80">Espace Admin</p>
        <h1 class="mt-4 text-4xl font-extrabold tracking-tight text-white">Tableau de bord Admin</h1>
        <p class="mt-4 text-slate-300">Bienvenue <?= htmlspecialchars($pseudo, ENT_QUOTES, 'UTF-8') ?>.</p>
        <div class="mt-8 space-y-3">
            <a class="inline-block rounded-full bg-accent px-5 py-3 text-sm font-semibold text-white shadow-neon" href="creerAdmin.php">Créer un compte admin</a>
            <a class="inline-block rounded-full bg-accent px-5 py-3 text-sm font-semibold text-white shadow-neon" href="listerJoueurs.php">Gérer les joueurs</a>
            <a class="inline-block rounded-full bg-accent px-5 py-3 text-sm font-semibold text-white shadow-neon" href="listerTournois.php">Gérer les tournois</a>
            <a class="inline-block rounded-full bg-accent px-5 py-3 text-sm font-semibold text-white shadow-neon" href="listerTeams.php">Gérer les équipes</a>
            <a class="inline-block rounded-full bg-slate-700 px-5 py-3 text-sm font-semibold text-white shadow-neon" href="../deconnexion.php">Se déconnecter</a>
        </div>
    </section>
</main>
<footer class="border-t border-slate-800 bg-panel/75 px-5 py-6 text-center text-slate-500">
    <p>&copy; 2026 Gaming Hub. Tous droits réservés.</p>
</footer>
</body>
</html>

