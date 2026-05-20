<?php
session_start();
$pageTitle = 'Rejoindre une team';
$rootPath = '..';

try {
    $bdd = new PDO('mysql:host=localhost;dbname=ProjetPHP', 'root', 'root');
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

if (!isset($_SESSION['idCompte'])) {
    header('Location: ../connexion.php');
    exit;
}

$idCompte = $_SESSION['idCompte'];
$idTeam   = isset($_GET['idTeam']) ? (int)$_GET['idTeam'] : 0;

if ($idTeam === 0) {
    header('Location: listeTeam.php');
    exit;
}

// Récupérer les infos de la team ciblée
$reqTeam = $bdd->prepare("SELECT idTeam, nomTeam, tag FROM team WHERE idTeam = ?");
$reqTeam->execute([$idTeam]);
$team = $reqTeam->fetch(PDO::FETCH_ASSOC);

if (!$team) {
    header('Location: listeTeam.php');
    exit;
}

// Récupérer la team actuelle de l'utilisateur
$reqUser = $bdd->prepare("SELECT idTeam FROM compte WHERE idCompte = ?");
$reqUser->execute([$idCompte]);
$user = $reqUser->fetch(PDO::FETCH_ASSOC);
$idTeamActuelle = $user['idTeam'];

$message = '';
$messageType = 'error'; // 'error' ou 'success'

// L'utilisateur est déjà dans cette team
if ($idTeamActuelle == $idTeam) {
    header('Location: team.php?idTeam=' . $idTeam);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifier que l'utilisateur n'est pas déjà dans une team (double vérification côté POST)
    if ($idTeamActuelle !== null) {
        $message = 'Vous appartenez déjà à une team. Quittez-la avant d\'en rejoindre une autre.';
    } else {
        try {
            $stmt = $bdd->prepare("UPDATE compte SET idTeam = ? WHERE idCompte = ?");
            $stmt->execute([$idTeam, $idCompte]);

            header('Location: team.php?idTeam=' . $idTeam . '&succes=team_rejointe');
            exit;
        } catch (PDOException $e) {
            $message = 'Erreur : ' . $e->getMessage();
        }
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
<?php include $rootPath . '/inc/header_connected.php'; ?>

<main class="mx-auto max-w-6xl px-5 py-10 flex-1">
    <section class="rounded-[1.5rem] border border-slate-800 bg-panel/80 p-6 shadow-neon backdrop-blur-xl">

        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-white">Rejoindre une team</h1>
                <p class="text-sm text-slate-400">Confirmez votre inscription dans l'équipe.</p>
            </div>
            <a href="team.php?idTeam=<?= $idTeam ?>"
               class="rounded-full border border-slate-700 bg-slate-900/80 px-4 py-2 text-slate-200 transition hover:border-accent hover:text-white">
                Retour
            </a>
        </div>

        <?php if (!empty($message)): ?>
            <div class="mb-6 rounded-xl border border-red-500/30 bg-red-950/50 px-4 py-3 text-sm text-red-200">
                <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <?php if ($idTeamActuelle !== null && $idTeamActuelle != $idTeam): ?>
            <!-- Utilisateur déjà dans une autre team -->
            <div class="rounded-xl border border-yellow-500/30 bg-yellow-950/50 px-4 py-3 text-sm text-yellow-200">
                Vous appartenez déjà à une team. Quittez-la avant d'en rejoindre une autre.
            </div>
        <?php else: ?>
            <!-- Récapitulatif de la team -->
            <div class="mb-6 rounded-xl border border-slate-700 bg-slate-900/60 p-5 space-y-2">
                <p class="text-slate-300">
                    <span class="text-slate-500 text-sm uppercase tracking-wide">Nom</span><br>
                    <span class="text-white font-semibold text-lg">
                        <?= htmlspecialchars($team['nomTeam'], ENT_QUOTES, 'UTF-8') ?>
                    </span>
                </p>
                <?php if (!empty($team['tag'])): ?>
                <p class="text-slate-300">
                    <span class="text-slate-500 text-sm uppercase tracking-wide">Tag</span><br>
                    <span class="font-mono text-neon">[<?= htmlspecialchars($team['tag'], ENT_QUOTES, 'UTF-8') ?>]</span>
                </p>
                <?php endif; ?>
            </div>

            <p class="text-slate-300 mb-6">
                Voulez-vous vraiment rejoindre la team
                <strong class="text-white"><?= htmlspecialchars($team['nomTeam'], ENT_QUOTES, 'UTF-8') ?></strong> ?
            </p>

            <form method="POST" class="flex gap-4">
                <button type="submit"
                        class="inline-flex items-center justify-center rounded-full bg-accent px-6 py-3 text-sm font-semibold text-white transition hover:bg-accent/80">
                    Confirmer
                </button>
                <a href="team.php?idTeam=<?= $idTeam ?>"
                   class="inline-flex items-center justify-center rounded-full border border-slate-700 bg-slate-900/80 px-6 py-3 text-sm text-slate-200 transition hover:border-slate-500 hover:text-white">
                    Annuler
                </a>
            </form>
        <?php endif; ?>

    </section>
</main>

<footer class="border-t border-slate-800 bg-panel/75 px-5 py-6 text-center text-slate-500">
    <p>&copy; 2026 Gaming Hub. Tous droits réservés.</p>
</footer>
</body>
</html>
