<?php

    session_start();
    $pageTitle = 'Gestion des Teams';
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
    if (isset($_GET['idTeam']) && isset($_GET['action'])) {
        try {
            $newStatus = ($_GET['action'] === 'ban') ? 'Banni' : 'Actif';
            $reqUpdate = $bdd->prepare("UPDATE team SET statut = ? WHERE idTeam = ?");
            $reqUpdate->execute([$newStatus, $_GET['idTeam']]);
        } catch (PDOException $e) {
            die("Erreur : " . $e->getMessage());
        }
    }

    // Récupérer toutes les teams
    $reqTeams = $bdd->prepare("
        SELECT T.idTeam, T.nomTeam, T.tag, T.statut, C.pseudo
        FROM team T
        LEFT JOIN compte C ON C.idCompte = T.chef
        ORDER BY T.nomTeam ASC
    ");
    $reqTeams->execute();
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
        <h2 class="text-xl font-bold text-white">Gestion des Teams</h2>
    </div>

    <div class="rounded-[1.25rem] border border-slate-800 bg-panel/80 p-6 shadow-neon backdrop-blur-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-sm text-slate-300 border-b border-slate-700">
                        <th class="py-3">Nom Team</th>
                        <th class="py-3">Tag</th>
                        <th class="py-3">Chef</th>
                        <th class="py-3">Statut</th>
                        <th class="py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($team = $reqTeams->fetch()): ?>
                    <tr class="border-b border-slate-800">
                        <td class="py-3"><?php echo htmlspecialchars($team['nomTeam']); ?></td>
                        <td class="py-3"><?php echo htmlspecialchars($team['tag']); ?></td>
                        <td class="py-3"><?php echo htmlspecialchars($team['pseudo']); ?></td>
                        <td class="py-3"><?php echo htmlspecialchars($team['statut']); ?></td>
                        <td class="py-3">
                            <?php if ($team['statut'] === 'Actif'): ?>
                                <a href="?idTeam=<?php echo $team['idTeam']; ?>&action=ban" class="text-red-400">Bannir</a>
                            <?php else: ?>
                                <a href="?idTeam=<?php echo $team['idTeam']; ?>&action=unban" class="text-green-400">Débannir</a>
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

