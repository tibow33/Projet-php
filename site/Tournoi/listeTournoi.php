<?php
session_start();
$pageTitle = 'Liste des tournois';
$rootPath = '..';

try {
    $bdd = new PDO('mysql:host=localhost;dbname=ProjetPHP', 'root', '');
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

$idCompte = $_SESSION['idCompte'];
$reqChefTeam = $bdd->prepare("SELECT idTeam FROM team WHERE chef = ?");
$reqChefTeam->execute([$idCompte]);
$team = $reqChefTeam->fetch();
$idChef = $team ? $team['idTeam'] : null;
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
            <h1 class="text-2xl font-bold text-white">Liste des tournois</h1>
            <a href="<?= $rootPath ?>/Joueur/accueilConnecteJoueur.php" class="text-sm text-neon">← Retour</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-sm text-slate-300 border-b border-slate-700">
                        <th class="py-3">Nom</th>
                        <th class="py-3">Jeu</th>
                        <th class="py-3">Début</th>
                        <th class="py-3">Fin</th>
                        <th class="py-3">Teams</th>
                        <th class="py-3">Créateur</th>
                        <th class="py-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $reqTournoi = "SELECT idTournoi, nomTournoi, dateDebut, dateFin, descTournoi, nbTeam, pseudo, nomJeu
                            FROM tournoi T
                            JOIN compte ON idCompte = createur
                            JOIN jeu J ON J.idJeu = T.idJeu";
                $resultReqTournoi = $bdd->query($reqTournoi);

                if ($resultReqTournoi->rowCount() > 0) {
                    while ($tournoi = $resultReqTournoi->fetch()) {
                        $reqVerif = $bdd->prepare("SELECT * FROM participe WHERE idTeam = ? AND idTournoi = ?");
                        $reqVerif->execute([$idChef, $tournoi['idTournoi']]);
                        $dejaRejoint = $reqVerif->rowCount() > 0;
                        ?>
                        <tr class="border-b border-slate-800">
                            <td class="py-3"><a class="text-neon" href="tournoi.php?idTournoi=<?= $tournoi['idTournoi'] ?>"><?= htmlspecialchars($tournoi['nomTournoi']) ?></a></td>
                            <td class="py-3"><?= htmlspecialchars($tournoi['nomJeu']) ?></td>
                            <td class="py-3"><?= htmlspecialchars($tournoi['dateDebut']) ?></td>
                            <td class="py-3"><?= htmlspecialchars($tournoi['dateFin']) ?></td>
                            <td class="py-3"><?= htmlspecialchars($tournoi['nbTeam']) ?></td>
                            <td class="py-3"><?= htmlspecialchars($tournoi['pseudo']) ?></td>
                            <td class="py-3">
                                <?php if ($idChef): ?>
                                    <?php if ($dejaRejoint): ?>
                                        <span class="text-slate-400">Rejoint</span>
                                    <?php else: ?>
                                        <a href="rejoindreTournoi.php?idTournoi=<?= $tournoi['idTournoi'] ?>" class="text-accent">Rejoindre</a>
                                    <?php endif; ?>
                                <?php else: ?>
                                    —
                                <?php endif; ?>
                            </td>
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

