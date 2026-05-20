<?php
session_start();
$pageTitle = 'Inscription';
$rootPath = '.';
$message = "";

// Vérification AJAX pseudo
if (isset($_GET['check_pseudo'])) {
    header('Content-Type: application/json');
    $bdd = new PDO("mysql:host=localhost;dbname=ProjetPHP;charset=utf8", "root", "");
    $stmt = $bdd->prepare("SELECT COUNT(*) FROM Compte WHERE pseudo = ?");
    $stmt->execute([trim($_GET['check_pseudo'])]);
    echo json_encode(['taken' => (int)$stmt->fetchColumn() > 0]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $pseudo   = trim($_POST["pseudo"]);
    $eMail    = trim($_POST["eMail"]);
    $password = $_POST["password"];
    $confirm  = $_POST["confirm"];
    $descCompte = trim($_POST["descCompte"] ?? '');

    if (!filter_var($eMail, FILTER_VALIDATE_EMAIL)) {
        $message = "L'adresse e-mail n'est pas valide.";
    } elseif ($password !== $confirm) {
        $message = "Les mots de passe ne correspondent pas.";
    } else {
        try {
            $bdd = new PDO("mysql:host=localhost;dbname=ProjetPHP;charset=utf8", "root", "");
            $check = $bdd->prepare("SELECT COUNT(*) FROM Compte WHERE pseudo = ?");
            $check->execute([$pseudo]);
            if ((int)$check->fetchColumn() > 0) {
                $message = "Ce pseudo est déjà utilisé.";
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $sql  = $bdd->prepare("INSERT INTO Compte (pseudo, eMail, mdp, dateCreation, descCompte) VALUES (?, ?, ?, ?, ?)");
                $sql->execute([$pseudo, $eMail, $hash, date('Y-m-d'), $descCompte]);

                $_SESSION['pseudo'] = $pseudo;
                $_SESSION['id']     = $bdd->lastInsertId();

                header("Location: ./Joueur/accueilConnecteJoueur.php");
                exit;
            }
        } catch (PDOException $e) {
            $message = "Erreur : " . $e->getMessage();
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
    <h1 class="mb-8">Inscription</h1>

    <?php if ($message): ?>
        <p class="mb-6 p-4 bg-red-900/20 border border-red-500 rounded text-red-200"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="POST" onsubmit="return valider()" class="max-w-md bg-panel/50 border border-slate-700 rounded-lg p-8 space-y-6">
        <div class="space-y-2">
            <label class="block text-sm font-medium text-slate-200">Pseudo</label>
            <input type="text" id="pseudo" name="pseudo" required class="w-full px-4 py-2 bg-slate-900/50 border border-slate-600 rounded text-slate-100 focus:border-neon focus:outline-none transition">
            <span id="hint-pseudo" class="text-xs"></span>
        </div>

        <div class="space-y-2">
            <label class="block text-sm font-medium text-slate-200">e-Mail</label>
            <input type="email" name="eMail" required class="w-full px-4 py-2 bg-slate-900/50 border border-slate-600 rounded text-slate-100 focus:border-neon focus:outline-none transition">
        </div>

        <div class="space-y-2">
            <label class="block text-sm font-medium text-slate-200">Mot de passe</label>
            <input type="password" id="password" name="password" required class="w-full px-4 py-2 bg-slate-900/50 border border-slate-600 rounded text-slate-100 focus:border-neon focus:outline-none transition">
        </div>

        <div class="space-y-2">
            <label class="block text-sm font-medium text-slate-200">Confirmer le mot de passe</label>
            <input type="password" id="confirm" name="confirm" required class="w-full px-4 py-2 bg-slate-900/50 border border-slate-600 rounded text-slate-100 focus:border-neon focus:outline-none transition">
        </div>

        <div class="space-y-2">
            <label class="block text-sm font-medium text-slate-200">Description (optionnel)</label>
            <textarea name="descCompte" maxlength="500" rows="4" class="w-full px-4 py-2 bg-slate-900/50 border border-slate-600 rounded text-slate-100 focus:border-neon focus:outline-none transition resize-none" placeholder="Parle-moi de toi et de ton style de jeu..."></textarea>
            <p class="text-xs text-slate-400">Maximum 500 caractères</p>
        </div>

        <button type="submit" class="w-full mt-4 px-6 py-3 bg-accent text-white font-semibold rounded-lg hover:bg-accent/80 transition">S'inscrire</button>
    </form>
</main>
<footer class="border-t border-slate-800 bg-panel/75 px-5 py-6 text-center text-slate-500">
    <p>&copy; 2026 Gaming Hub. Tous droits réservés.</p>
</footer>

<script>
// Vérification pseudo en temps réel
let timer;
document.getElementById('pseudo').addEventListener('input', function() {
    clearTimeout(timer);
    const hint = document.getElementById('hint-pseudo');
    if (this.value.trim().length < 1) { hint.textContent = ''; return; }
    timer = setTimeout(async () => {
        const res  = await fetch('?check_pseudo=' + encodeURIComponent(this.value.trim()));
        const data = await res.json();
        hint.textContent = data.taken ? '✗ Pseudo déjà utilisé.' : '✓ Pseudo disponible.';
        hint.style.color = data.taken ? 'red' : 'green';
    }, 500);
});

// Validation avant envoi
function valider() {
    const pwd = document.getElementById('password').value;
    const cfm = document.getElementById('confirm').value;
    if (pwd !== cfm) {
        alert('Les mots de passe ne correspondent pas.');
        return false;
    }
    return true;
}
</script>
</body>
</html>

