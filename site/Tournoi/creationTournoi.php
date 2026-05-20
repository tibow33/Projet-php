<?php
session_start();
$pageTitle = 'Création de tournoi';
$rootPath = '..';

try {
    $bdd = new PDO('mysql:host=localhost;dbname=ProjetPHP', 'root', '');
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

$message = '';
$createur = $_SESSION['pseudo'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idCreateur = $_SESSION['idCompte'];
    $nomTournoi = $_POST['nomTournoi'];
    $dateDebut = $_POST['dateDebut'];
    $dateFin = $_POST['dateFin'];
    $idJeu = $_POST['idJeu'];
    $nbTeam = $_POST['nbTeam'];
    $descTournoi = $_POST['descTournoi'];

    try {
        $sql = $bdd->prepare("INSERT INTO Tournoi (nomTournoi, dateDebut, dateFin, idJeu, nbTeam, descTournoi, createur) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $sql->execute([$nomTournoi, $dateDebut, $dateFin, $idJeu, $nbTeam, $descTournoi, $idCreateur]);
        header("Location: ./../Joueur/accueilConnecteJoueur.php");
        exit;
    } catch (PDOException $e) {
        $message = "Erreur : " . $e->getMessage();
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
    <h1>Créer un tournoi</h1>

    <?php if (!empty($message)) : ?>
        <p class="mb-6 p-4 bg-red-900/20 border border-red-500 rounded text-red-200"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="POST" class="max-w-2xl bg-panel/50 border border-slate-700 rounded-lg p-8 space-y-6">
        <div class="space-y-2">
            <label class="block text-sm font-medium text-slate-200">Nom du Tournoi</label>
            <input type="text" name="nomTournoi" required class="w-full px-4 py-2 bg-slate-900/50 border border-slate-600 rounded text-slate-100 focus:border-neon focus:outline-none transition">
        </div>

        <div class="grid grid-cols-2 gap-6">
            <div class="space-y-2">
                <label class="block text-sm font-medium text-slate-200">Date Début</label>
                <input type="date" name="dateDebut" required class="w-full px-4 py-2 bg-slate-900/50 border border-slate-600 rounded text-slate-100 focus:border-neon focus:outline-none transition">
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-medium text-slate-200">Date Fin</label>
                <input type="date" name="dateFin" required class="w-full px-4 py-2 bg-slate-900/50 border border-slate-600 rounded text-slate-100 focus:border-neon focus:outline-none transition">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-6">
            <div class="space-y-2">
                <label class="block text-sm font-medium text-slate-200">Jeu</label>
                <select name="idJeu" required class="w-full px-4 py-2 bg-slate-900/50 border border-slate-600 rounded text-slate-100 focus:border-neon focus:outline-none transition">
                    <option value="">Sélectionnez un jeu</option>
                    <?php 
                        $reqJeu = "SELECT idJeu, nomJeu FROM Jeu ORDER BY nomJeu ASC";
                        $resultReqJeu = $bdd->query($reqJeu);
                        if ($resultReqJeu->rowCount() > 0) {
                            while ($jeu = $resultReqJeu->fetch()) {
                                echo '<option value="' . $jeu['idJeu'] . '">' . htmlspecialchars($jeu['nomJeu']) . '</option>';
                            }
                        }
                    ?>
                </select>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-medium text-slate-200">Nombre de Teams</label>
                <input type="number" name="nbTeam" required class="w-full px-4 py-2 bg-slate-900/50 border border-slate-600 rounded text-slate-100 focus:border-neon focus:outline-none transition">
            </div>
        </div>

        <div class="space-y-2">
            <label class="block text-sm font-medium text-slate-200">Description</label>
            <input type="text" name="descTournoi" class="w-full px-4 py-2 bg-slate-900/50 border border-slate-600 rounded text-slate-100 focus:border-neon focus:outline-none transition">
        </div>

        <div class="pt-4 pb-2 border-t border-slate-600">
            <p class="text-sm text-slate-300">Créateur : <span class="text-neon font-semibold"><?= htmlspecialchars($createur) ?></span></p>
        </div>

        <button type="submit" class="w-full mt-4 px-6 py-3 bg-accent text-white font-semibold rounded-lg hover:bg-accent/80 transition">Créer le Tournoi</button>
    </form>
</main>
<footer class="border-t border-slate-800 bg-panel/75 px-5 py-6 text-center text-slate-500">
    <p>&copy; 2026 Gaming Hub. Tous droits réservés.</p>
</footer>
</body>
</html>


