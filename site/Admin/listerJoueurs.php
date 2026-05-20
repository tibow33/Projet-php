<?php

    session_start();
    $pageTitle = 'Gestion des Joueurs';
    $rootPath = '..';

    try {
        $bdd = new PDO('mysql:host=localhost;dbname=ProjetPHP', 'root', '');
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    // Vérifier que l'utilisateur est admin
    $idCompte = $_SESSION['idCompte'] ?? null;

    if (!$idCompte) {
        header("Location: ../connexion.php");
        exit;
    }

    $reqAdmin = $bdd->prepare("SELECT typeCompte FROM compte WHERE idCompte = ?");
    $reqAdmin->execute([$idCompte]);
    $admin = $reqAdmin->fetch();

    if (!$admin || $admin['typeCompte'] !== 'Admin') {
        die("Accès refusé. Vous n'êtes pas administrateur.");
    }

    // Traiter les actions de ban/débanning
    if (isset($_GET['idJoueur']) && isset($_GET['action'])) {
        try {
            $newStatus = ($_GET['action'] === 'ban') ? 'Banni' : 'Actif';
            $reqUpdate = $bdd->prepare("UPDATE compte SET statut = ? WHERE idCompte = ?");
            $reqUpdate->execute([$newStatus, $_GET['idJoueur']]);
        } catch (PDOException $e) {
            die("Erreur : " . $e->getMessage());
        }
    }

    // Récupérer tous les joueurs (sauf les admins)
    $reqJoueurs = $bdd->prepare("SELECT idCompte, pseudo, eMail, statut, dateCreation FROM compte WHERE typeCompte IS NULL OR typeCompte != 'Admin' ORDER BY pseudo ASC");
    $reqJoueurs->execute();
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

    <div class="mx-auto max-w-6xl px-5 py-10">
    <div class="flex items-center justify-between mb-6">
        <div>
            <a href="../deconnexion.php" class="text-sm text-slate-400">Se déconnecter</a>
            <a href="accueilAdmin.php" class="ml-4 text-sm text-neon">Accueil Admin</a>
        </div>
        <h2 class="text-xl font-bold text-white">Gestion des Joueurs</h2>
    </div>

    <div class="rounded-[1.25rem] border border-slate-800 bg-panel/80 p-6 shadow-neon backdrop-blur-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-sm text-slate-300 border-b border-slate-700">
                        <th class="py-3">Pseudo</th>
                        <th class="py-3">Email</th>
                        <th class="py-3">Date</th>
                        <th class="py-3">Statut</th>
                        <th class="py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($joueur = $reqJoueurs->fetch()): ?>
                    <tr class="border-b border-slate-800">
                        <td class="py-3"><?php echo htmlspecialchars($joueur['pseudo']); ?></td>
                        <td class="py-3"><?php echo htmlspecialchars($joueur['eMail']); ?></td>
                        <td class="py-3"><?php echo htmlspecialchars($joueur['dateCreation']); ?></td>
                        <td class="py-3"><?php echo htmlspecialchars($joueur['statut']); ?></td>
                        <td class="py-3">
                            <?php if ($joueur['statut'] === 'Actif'): ?>
                                <a href="?idJoueur=<?php echo $joueur['idCompte']; ?>&action=ban" class="text-red-400">Bannir</a>
                            <?php else: ?>
                                <a href="?idJoueur=<?php echo $joueur['idCompte']; ?>&action=unban" class="text-green-400">Débannir</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
    </div>
</main>
<footer class="border-t border-slate-800 bg-panel/75 px-5 py-6 text-center text-slate-500">
    <p>&copy; 2026 Gaming Hub. Tous droits réservés.</p>
</footer>
</body>
</html>

