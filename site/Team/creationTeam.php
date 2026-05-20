<?php
session_start();
$pageTitle = 'Création de team';
$rootPath = '..';

try {
    $bdd = new PDO('mysql:host=localhost;dbname=ProjetPHP', 'root', '');
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

if (!isset($_SESSION['idCompte'])) {
    header('Location: ../connexion.php');
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomTeam = trim($_POST['nomTeam'] ?? '');
    $tag = trim($_POST['tag'] ?? '');
    $idCompte = $_SESSION['idCompte'];

    if ($nomTeam === '') {
        $message = 'Veuillez saisir un nom de team.';
    } else {
        try {
            $stmt = $bdd->prepare("INSERT INTO team (nomTeam, tag, chef) VALUES (?, ?, ?)");
            $stmt->execute([$nomTeam, $tag, $idCompte]);
            $idTeam = $bdd->lastInsertId();

            $updateCompte = $bdd->prepare("UPDATE compte SET idTeam = ? WHERE idCompte = ?");
            $updateCompte->execute([$idTeam, $idCompte]);

            header('Location: listeTeam.php?succes=team_creee');
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
                <h1 class="text-2xl font-bold text-white">Créer une team</h1>
                <p class="text-sm text-slate-400">Formulaire de création d’équipe.</p>
            </div>
            <a href="listeTeam.php" class="rounded-full border border-slate-700 bg-slate-900/80 px-4 py-2 text-slate-200 transition hover:border-accent hover:text-white">Retour</a>
        </div>

        <?php if (!empty($message)): ?>
            <div class="mb-6 rounded-xl border border-red-500/30 bg-red-950/50 px-4 py-3 text-sm text-red-200">
                <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-6 max-w-2xl">
            <div class="space-y-2">
                <label class="block text-sm font-medium text-slate-200" for="nomTeam">Nom de la team</label>
                <input id="nomTeam" name="nomTeam" type="text" required value="<?= isset($_POST['nomTeam']) ? htmlspecialchars($_POST['nomTeam'], ENT_QUOTES, 'UTF-8') : '' ?>" class="w-full rounded-xl border border-slate-700 bg-slate-900/80 px-4 py-3 text-slate-100 focus:border-neon focus:outline-none" />
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-medium text-slate-200" for="tag">Tag</label>
                <input id="tag" name="tag" type="text" value="<?= isset($_POST['tag']) ? htmlspecialchars($_POST['tag'], ENT_QUOTES, 'UTF-8') : '' ?>" class="w-full rounded-xl border border-slate-700 bg-slate-900/80 px-4 py-3 text-slate-100 focus:border-neon focus:outline-none" />
            </div>

            <button type="submit" class="inline-flex items-center justify-center rounded-full bg-accent px-6 py-3 text-sm font-semibold text-white transition hover:bg-accent/80">
                Créer la team
            </button>
        </form>
    </section>
</main>
<footer class="border-t border-slate-800 bg-panel/75 px-5 py-6 text-center text-slate-500">
    <p>&copy; 2026 Gaming Hub. Tous droits réservés.</p>
</footer>
</body>
</html>
