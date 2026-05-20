<?php
session_start();
$pageTitle = 'Modifier mon compte';
$rootPath = '..';

try {
    $bdd = new PDO('mysql:host=localhost;dbname=ProjetPHP', 'root', '');
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

$idCompte = $_SESSION['idCompte'];
$req = $bdd->prepare("SELECT pseudo, email, descCompte FROM Compte WHERE idCompte = ?");
$req->execute([$idCompte]);
$compte = $req->fetch();

if (!$compte) {
    die("Erreur : compte introuvable.");
}

$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nouveauPseudo = $_POST['pseudo'];
    $nouvelEmail = $_POST['email'];
    $nouvelleDesc = $_POST['descCompte'] ?? '';
    $update = $bdd->prepare("UPDATE Compte SET pseudo = ?, email = ?, descCompte = ? WHERE idCompte = ?");
    $update->execute([$nouveauPseudo, $nouvelEmail, $nouvelleDesc, $idCompte]);
    $_SESSION['pseudo'] = $nouveauPseudo;
    $message = "Informations mises à jour avec succès !";
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
    </nav>
</header>
<main class="mx-auto max-w-6xl px-5 py-10 flex-1">
    <h1 class="mb-8">Modifier mon compte</h1>

    <?php if (!empty($message)) : ?>
        <p class="mb-6 p-4 bg-green-900/20 border border-green-500 rounded text-green-200"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="POST" class="max-w-md bg-panel/50 border border-slate-700 rounded-lg p-8 space-y-6">
        <div class="space-y-2">
            <label class="block text-sm font-medium text-slate-200">Pseudo</label>
            <input type="text" name="pseudo" value="<?= htmlspecialchars($compte['pseudo']) ?>" required class="w-full px-4 py-2 bg-slate-900/50 border border-slate-600 rounded text-slate-100 focus:border-neon focus:outline-none transition">
        </div>

        <div class="space-y-2">
            <label class="block text-sm font-medium text-slate-200">Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($compte['email']) ?>" required class="w-full px-4 py-2 bg-slate-900/50 border border-slate-600 rounded text-slate-100 focus:border-neon focus:outline-none transition">
        </div>

        <div class="space-y-2">
            <label class="block text-sm font-medium text-slate-200">Description</label>
            <textarea name="descCompte" maxlength="500" rows="4" class="w-full px-4 py-2 bg-slate-900/50 border border-slate-600 rounded text-slate-100 focus:border-neon focus:outline-none transition resize-none"><?= htmlspecialchars($compte['descCompte'] ?? '') ?></textarea>
            <p class="text-xs text-slate-400">Maximum 500 caractères</p>
        </div>

        <button type="submit" class="w-full mt-4 px-6 py-3 bg-accent text-white font-semibold rounded-lg hover:bg-accent/80 transition">Enregistrer les modifications</button>
    </form>

    <p class="mt-6"><a href="gererCompte.php" class="text-neon hover:text-white transition">← Retour à mon compte</a></p>
</main>
<footer class="border-t border-slate-800 bg-panel/75 px-5 py-6 text-center text-slate-500">
    <p>&copy; 2026 Gaming Hub. Tous droits réservés.</p>
</footer>
</body>
</html>

