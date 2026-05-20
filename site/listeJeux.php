<?php
session_start();
$pageTitle = 'Liste des jeux';
$rootPath = '.';

try {
    $bdd = new PDO('mysql:host=localhost;dbname=ProjetPHP', 'root', '');
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
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
            <h1 class="text-2xl font-bold text-white">Liste des jeux</h1>
            <p class="text-sm text-slate-400">Retrouvez tous les jeux disponibles</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-sm text-slate-300 border-b border-slate-700">
                        <th class="py-3">Nom du Jeu</th>
                        <th class="py-3">Catégorie</th>
                        <th class="py-3">Description</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $reqJeu = "SELECT nomJeu, typeJeu, descJeu FROM Jeu ORDER BY nomJeu ASC";
                $resultReqJeu = $bdd->query($reqJeu);

                if ($resultReqJeu->rowCount() > 0) {
                    while ($jeu = $resultReqJeu->fetch()) {
                        ?>
                        <tr class="border-b border-slate-800">
                            <td class="py-3"><?= htmlspecialchars($jeu['nomJeu']) ?></td>
                            <td class="py-3"><?= htmlspecialchars($jeu['typeJeu']) ?></td>
                            <td class="py-3 text-slate-300"><?= htmlspecialchars($jeu['descJeu']) ?></td>
                        </tr>
                        <?php
                    }
                }
                ?>
                </tbody>
            </table>
        </div>
    </section>
</main>
<footer class="border-t border-slate-800 bg-panel/75 px-5 py-6 text-center text-slate-500">
    <p>&copy; 2026 Gaming Hub. Tous droits réservés.</p>
</footer>
</body>
</html>

