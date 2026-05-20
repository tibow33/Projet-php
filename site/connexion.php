<?php
session_start();
$pageTitle = 'Connexion';
$rootPath = '.';

try {
    $bdd = new PDO('mysql:host=localhost;dbname=ProjetPHP', 'root', '');
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pseudo = $_POST['pseudo'];
    $mdp = $_POST['mdp'];

    if (!empty($pseudo) && !empty($mdp)) {
        $req = $bdd->prepare('SELECT * FROM COMPTE WHERE pseudo = :pseudo');
        $req->execute(['pseudo' => $pseudo]);
        $compte = $req->fetch();

        if ($compte && password_verify($mdp, $compte['mdp'])) {
            if ($compte['statut'] === 'Banni') {
                $message = "Ce compte a été banni.";
            } else {
                $_SESSION['pseudo'] = $compte['pseudo'];
                $_SESSION['idCompte'] = $compte['idCompte'];
                $_SESSION['typeCompte'] = $compte['typeCompte'];

                if ($compte['typeCompte'] === 'Admin') {
                    header('Location: ./Admin/accueilAdmin.php');
                } else {
                    header('Location: ./Joueur/accueilConnecteJoueur.php');
                }
                exit;
            }
        } else {
            $message = "Nom d'utilisateur ou mot de passe incorrect.";
        }
    } else {
        $message = "Veuillez remplir tous les champs.";
    }
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
<header class="border-b border-slate-800 bg-panel/90 backdrop-blur-md shadow-neon"><nav class="mx-auto flex max-w-6xl flex-wrap items-center gap-3 px-5 py-4 text-sm">
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
    <h1 class="mb-8">Connexion</h1>

    <?php if (!empty($message)): ?>
        <p class="mb-6 p-4 bg-red-900/20 border border-red-500 rounded text-red-200"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="POST" class="max-w-md bg-panel/50 border border-slate-700 rounded-lg p-8 space-y-6">
        <div class="space-y-2">
            <label class="block text-sm font-medium text-slate-200">Pseudo</label>
            <input type="text" name="pseudo" required class="w-full px-4 py-2 bg-slate-900/50 border border-slate-600 rounded text-slate-100 focus:border-neon focus:outline-none transition">
        </div>

        <div class="space-y-2">
            <label class="block text-sm font-medium text-slate-200">Mot de passe</label>
            <input type="password" name="mdp" required class="w-full px-4 py-2 bg-slate-900/50 border border-slate-600 rounded text-slate-100 focus:border-neon focus:outline-none transition">
        </div>

        <button type="submit" class="w-full mt-4 px-6 py-3 bg-accent text-white font-semibold rounded-lg hover:bg-accent/80 transition">Se connecter</button>
    </form>
</main>
<footer class="border-t border-slate-800 bg-panel/75 px-5 py-6 text-center text-slate-500">
    <p>&copy; 2026 Gaming Hub. Tous droits réservés.</p>
</footer>
</body>
</html>
 
