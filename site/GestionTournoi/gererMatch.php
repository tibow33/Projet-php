<?php
session_start();
$rootPath = '..';

if (empty($_SESSION['idCompte'])) {
    header('Location: connexion.php');
    exit;
}

try {
    $bdd = new PDO('mysql:host=localhost;dbname=ProjetPHP', 'root', '');
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

$idMatch   = isset($_GET['idMatch'])   ? (int)$_GET['idMatch']   : 0;
$idTournoi = isset($_GET['idTournoi']) ? (int)$_GET['idTournoi'] : 0;
$idCompte  = $_SESSION['idCompte'];

if (!$idMatch || !$idTournoi) {
    die('Paramètres manquants.');
}

// Vérifie que l'utilisateur est bien le créateur du tournoi
$stmt = $bdd->prepare("SELECT createur FROM tournoi WHERE idTournoi = ?");
$stmt->execute([$idTournoi]);
$tournoi = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$tournoi || $tournoi['createur'] != $idCompte) {
    die('Accès refusé.');
}

// Infos du match
$stmt = $bdd->prepare("
    SELECT m.idMatch, m.dateMatch, p.nomPhase
    FROM matchs m
    JOIN phase p ON p.idPhase = m.idPhase
    WHERE m.idMatch = ?
");
$stmt->execute([$idMatch]);
$match = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$match) {
    die('Match introuvable.');
}

// Équipes déjà enregistrées pour ce match
$stmt = $bdd->prepare("
    SELECT mt.idTeam, mt.vainqueur, t.nomTeam, t.tag
    FROM match_team mt
    JOIN team t ON t.idTeam = mt.idTeam
    WHERE mt.idMatch = ?
");
$stmt->execute([$idMatch]);
$teamsMatch = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Joueurs déjà enregistrés pour ce match
$stmt = $bdd->prepare("
    SELECT idCompte FROM match_compte WHERE idMatch = ?
");
$stmt->execute([$idMatch]);
$joueursMatch = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'idCompte');

// Toutes les équipes inscrites au tournoi avec leurs joueurs
$stmt = $bdd->prepare("
    SELECT t.idTeam, t.nomTeam, t.tag,
           c.idCompte, c.pseudo
    FROM participe p
    JOIN team t ON t.idTeam = p.idTeam
    LEFT JOIN compte c ON c.idTeam = t.idTeam
    WHERE p.idTournoi = ?
    ORDER BY t.nomTeam ASC, c.pseudo ASC
");
$stmt->execute([$idTournoi]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$teams = [];
foreach ($rows as $row) {
    $id = $row['idTeam'];
    if (!isset($teams[$id])) {
        $teams[$id] = [
            'idTeam'  => $id,
            'nomTeam' => $row['nomTeam'],
            'tag'     => $row['tag'],
            'joueurs' => [],
        ];
    }
    if ($row['idCompte']) {
        $teams[$id]['joueurs'][] = [
            'idCompte' => $row['idCompte'],
            'pseudo'   => $row['pseudo'],
        ];
    }
}

// Valeurs pré-remplies
$idTeam1Actuel = isset($_GET['idTeam1']) ? (int)$_GET['idTeam1']
               : ($teamsMatch[0]['idTeam'] ?? 0);
$idTeam2Actuel = isset($_GET['idTeam2']) ? (int)$_GET['idTeam2']
               : ($teamsMatch[1]['idTeam'] ?? 0);
$idVainqueurActuel= null;
foreach ($teamsMatch as $t) {
    if ($t['vainqueur']) { $idVainqueurActuel = $t['idTeam']; break; }
}

// -------------------------------------------------------
// TRAITEMENT POST
// -------------------------------------------------------
$msgSucces = '';
$msgErreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $idVainqueur = isset($_POST['idVainqueur']) ? (int)$_POST['idVainqueur'] : 0;
    $idTeam1     = isset($_POST['idTeam1'])     ? (int)$_POST['idTeam1']     : 0;
    $idTeam2     = isset($_POST['idTeam2'])     ? (int)$_POST['idTeam2']     : 0;
    $joueurs1    = isset($_POST['joueurs1'])    ? array_map('intval', (array)$_POST['joueurs1']) : [];
    $joueurs2    = isset($_POST['joueurs2'])    ? array_map('intval', (array)$_POST['joueurs2']) : [];

    if (!$idVainqueur || !$idTeam1 || !$idTeam2 || $idTeam1 === $idTeam2) {
        $msgErreur = 'Veuillez sélectionner deux équipes différentes et un vainqueur.';
    } else {
        try {
            $bdd->beginTransaction();

            $bdd->prepare("DELETE FROM match_compte WHERE idMatch = ?")->execute([$idMatch]);
            $bdd->prepare("DELETE FROM match_team   WHERE idMatch = ?")->execute([$idMatch]);

            $stmtTeam   = $bdd->prepare("INSERT INTO match_team (idMatch, idTeam, vainqueur) VALUES (?, ?, ?)");
            $stmtJoueur = $bdd->prepare("INSERT INTO match_compte (idMatch, idCompte) VALUES (?, ?)");

            $stmtTeam->execute([$idMatch, $idTeam1, ($idTeam1 === $idVainqueur) ? 1 : 0]);
            foreach ($joueurs1 as $idJ) {
                $stmtJoueur->execute([$idMatch, $idJ]);
            }

            $stmtTeam->execute([$idMatch, $idTeam2, ($idTeam2 === $idVainqueur) ? 1 : 0]);
            foreach ($joueurs2 as $idJ) {
                $stmtJoueur->execute([$idMatch, $idJ]);
            }

            $bdd->commit();

            // Recharge les données après sauvegarde
            header("Location: gererMatch.php?idMatch=$idMatch&idTournoi=$idTournoi&ok=1");
            exit;

        } catch (Exception $e) {
            $bdd->rollBack();
            $msgErreur = 'Erreur : ' . $e->getMessage();
        }
    }
}

if (isset($_GET['ok'])) {
    $msgSucces = 'Match enregistré avec succès.';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion du match — <?= htmlspecialchars($match['nomPhase']) ?></title>
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
<body>

<h1>Match — <?= htmlspecialchars($match['nomPhase']) ?></h1>

<?php if ($match['dateMatch']): ?>
    <p>Date : <?= date('d/m/Y', strtotime($match['dateMatch'])) ?></p>
<?php endif; ?>

<p><a href="gererTournoi.php?idTournoi=<?= $idTournoi ?>">← Retour au bracket</a></p>

<?php if ($msgSucces): ?>
    <p class="mb-6 p-4 bg-green-900/20 border border-green-500 rounded text-green-200"><?= htmlspecialchars($msgSucces) ?></p>
<?php endif; ?>
<?php if ($msgErreur): ?>
    <p class="mb-6 p-4 bg-red-900/20 border border-red-500 rounded text-red-200"><?= htmlspecialchars($msgErreur) ?></p>
<?php endif; ?>

<form method="POST" class="bg-panel/50 border border-slate-700 rounded-lg p-8 space-y-8 max-w-2xl">

    <div class="space-y-4">
        <h2 class="text-lg font-semibold text-neon">Équipe 1</h2>
        <select name="idTeam1" required onchange="window.location.href='gererMatch.php?idMatch=<?= $idMatch ?>&idTournoi=<?= $idTournoi ?>&idTeam1='+this.value+'&idTeam2=<?= $idTeam2Actuel ?>'" class="w-full px-4 py-2 bg-slate-900/50 border border-slate-600 rounded text-slate-100 focus:border-neon focus:outline-none transition">
            <option value="">-- Choisir une équipe --</option>
            <?php foreach ($teams as $team): ?>
                <option value="<?= $team['idTeam'] ?>"
                    <?= ($team['idTeam'] == $idTeam1Actuel) ? 'selected' : '' ?>>
                    [<?= htmlspecialchars($team['tag'] ?? '?') ?>] <?= htmlspecialchars($team['nomTeam']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <?php if ($idTeam1Actuel && isset($teams[$idTeam1Actuel])): ?>
            <div class="pt-4 border-t border-slate-600">
                <p class="text-sm font-medium text-slate-300 mb-3">Joueurs ayant participé :</p>
                <div class="space-y-2">
                    <?php foreach ($teams[$idTeam1Actuel]['joueurs'] as $joueur): ?>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="joueurs1[]" value="<?= $joueur['idCompte'] ?>"
                                <?= in_array($joueur['idCompte'], $joueursMatch) ? 'checked' : '' ?>
                                class="w-4 h-4 bg-slate-900/50 border border-slate-600 rounded cursor-pointer">
                            <span class="text-slate-200"><?= htmlspecialchars($joueur['pseudo']) ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="space-y-4">
        <h2 class="text-lg font-semibold text-neon">Équipe 2</h2>
        <select name="idTeam2" required onchange="window.location.href='gererMatch.php?idMatch=<?= $idMatch ?>&idTournoi=<?= $idTournoi ?>&idTeam1=<?= $idTeam1Actuel ?>&idTeam2='+this.value" class="w-full px-4 py-2 bg-slate-900/50 border border-slate-600 rounded text-slate-100 focus:border-neon focus:outline-none transition">
            <option value="">-- Choisir une équipe --</option>
            <?php foreach ($teams as $team): ?>
                <option value="<?= $team['idTeam'] ?>"
                    <?= ($team['idTeam'] == $idTeam2Actuel) ? 'selected' : '' ?>>
                    [<?= htmlspecialchars($team['tag'] ?? '?') ?>] <?= htmlspecialchars($team['nomTeam']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <?php if ($idTeam2Actuel && isset($teams[$idTeam2Actuel])): ?>
            <div class="pt-4 border-t border-slate-600">
                <p class="text-sm font-medium text-slate-300 mb-3">Joueurs ayant participé :</p>
                <div class="space-y-2">
                    <?php foreach ($teams[$idTeam2Actuel]['joueurs'] as $joueur): ?>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="joueurs2[]" value="<?= $joueur['idCompte'] ?>"
                                <?= in_array($joueur['idCompte'], $joueursMatch) ? 'checked' : '' ?>
                                class="w-4 h-4 bg-slate-900/50 border border-slate-600 rounded cursor-pointer">
                            <span class="text-slate-200"><?= htmlspecialchars($joueur['pseudo']) ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($idTeam1Actuel && $idTeam2Actuel): ?>
        <div class="space-y-4">
            <h2 class="text-lg font-semibold text-neon">Vainqueur</h2>
            <div class="space-y-3 pt-2">
                <?php foreach ([$idTeam1Actuel, $idTeam2Actuel] as $idT): ?>
                    <?php if (isset($teams[$idT])): ?>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="radio" name="idVainqueur" value="<?= $idT ?>"
                                <?= ($idT == $idVainqueurActuel) ? 'checked' : '' ?> required
                                class="w-4 h-4 bg-slate-900/50 border border-slate-600 rounded-full cursor-pointer">
                            <span class="text-slate-200">[<?= htmlspecialchars($teams[$idT]['tag'] ?? '?') ?>] <?= htmlspecialchars($teams[$idT]['nomTeam']) ?></span>
                        </label>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($idTeam1Actuel && $idTeam2Actuel): ?>
        <br>
        <button type="submit">Enregistrer</button>
    <?php endif; ?>

</form>
</section>
</main>
</body>
</html>
