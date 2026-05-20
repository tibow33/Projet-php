<?php
session_start();

if (empty($_SESSION['idCompte'])) {
    header('Location: connexion.php');
    exit;
}

try {
    $bdd = new PDO('mysql:host=localhost;dbname=ProjetPHP', 'root', '');
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

$rootPath = '..';

$idTournoi = isset($_GET['idTournoi']) ? (int)$_GET['idTournoi'] : 0;
$idCompte  = $_SESSION['idCompte'];

if (!$idTournoi) {
    die('Tournoi introuvable.');
}

// Infos du tournoi + nombre d'équipes inscrites
$stmt = $bdd->prepare("
    SELECT t.idTournoi, t.nomTournoi, t.createur, t.nbTeam,
           COUNT(p.idTeam) AS nbInscrits
    FROM tournoi t
    LEFT JOIN participe p ON p.idTournoi = t.idTournoi
    WHERE t.idTournoi = ?
    GROUP BY t.idTournoi
");
$stmt->execute([$idTournoi]);
$tournoi = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$tournoi) {
    die('Tournoi introuvable.');
}

$isAdmin  = ($tournoi['createur'] == $idCompte);
$estPlein = ($tournoi['nbInscrits'] >= $tournoi['nbTeam']);

// -------------------------------------------------------
// TRAITEMENT POST : génération du bracket
// -------------------------------------------------------
$msgSucces = '';
$msgErreur = '';

if ($isAdmin && isset($_POST['action']) && $_POST['action'] === 'generer_bracket') {

    $stmt = $bdd->prepare("SELECT COUNT(*) FROM matchs WHERE idTournoi = ?");
    $stmt->execute([$idTournoi]);
    $dejaGenere = (int)$stmt->fetchColumn();

    if ($dejaGenere > 0) {
        $msgErreur = 'Le bracket a déjà été généré.';
    } elseif (!$estPlein) {
        $msgErreur = 'Le tournoi n\'est pas encore complet.';
    } else {
        $nbEquipes = (int)$tournoi['nbInscrits'];
        $nbTours   = (int)ceil(log($nbEquipes, 2));

        $stmt = $bdd->prepare("SELECT idPhase FROM phase ORDER BY idPhase ASC");
        $stmt->execute();
        $toutesPhases = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'idPhase');

        if (count($toutesPhases) < $nbTours) {
            $msgErreur = 'Pas assez de phases définies pour ce nombre d\'équipes.';
        } else {
            try {
                $bdd->beginTransaction();

                $phasesUtilisees = array_slice($toutesPhases, 0, $nbTours);
                $stmtMatch = $bdd->prepare("INSERT INTO matchs (idTournoi, idPhase) VALUES (?, ?)");

                $nbMatchsTour = (int)($nbEquipes / 2);
                foreach ($phasesUtilisees as $idPhase) {
                    for ($i = 0; $i < $nbMatchsTour; $i++) {
                        $stmtMatch->execute([$idTournoi, $idPhase]);
                    }
                    $nbMatchsTour = (int)ceil($nbMatchsTour / 2);
                }

                $bdd->commit();
                header("Location: gererTournoi.php?idTournoi=$idTournoi&ok=1");
                exit;

            } catch (Exception $e) {
                $bdd->rollBack();
                $msgErreur = 'Erreur lors de la génération : ' . $e->getMessage();
            }
        }
    }
}

if (isset($_GET['ok'])) {
    $msgSucces = 'Bracket généré avec succès.';
}

// -------------------------------------------------------
// LECTURE : phases + matchs + équipes + vainqueur
// -------------------------------------------------------
$stmt = $bdd->prepare("
    SELECT DISTINCT p.idPhase, p.nomPhase
    FROM phase p
    JOIN matchs m ON m.idPhase = p.idPhase
    WHERE m.idTournoi = ?
    ORDER BY p.idPhase ASC
");
$stmt->execute([$idTournoi]);
$phases = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $bdd->prepare("
    SELECT m.idMatch, m.idPhase, m.dateMatch,
           t.idTeam, t.nomTeam, t.tag,
           mt.vainqueur
    FROM matchs m
    LEFT JOIN match_team mt ON mt.idMatch = m.idMatch
    LEFT JOIN team t ON t.idTeam = mt.idTeam
    WHERE m.idTournoi = ?
    ORDER BY m.idPhase ASC, m.idMatch ASC
");
$stmt->execute([$idTournoi]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$matchsByPhase = [];
foreach ($rows as $row) {
    $id = $row['idMatch'];
    if (!isset($matchsByPhase[$row['idPhase']][$id])) {
        $matchsByPhase[$row['idPhase']][$id] = [
            'idMatch'   => $id,
            'dateMatch' => $row['dateMatch'],
            'teams'     => [],
        ];
    }
    if ($row['idTeam']) {
        $matchsByPhase[$row['idPhase']][$id]['teams'][] = [
            'idTeam'    => $row['idTeam'],
            'nomTeam'   => $row['nomTeam'],
            'tag'       => $row['tag'],
            'vainqueur' => (bool)$row['vainqueur'],
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($tournoi['nomTournoi'], ENT_QUOTES, 'UTF-8') ?></title>
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
    <style>
        .bracket {
            display: flex;
            gap: 24px;
            align-items: flex-start;
            overflow-x: auto;
            padding: 16px 0;
        }
        .round {
            display: flex;
            flex-direction: column;
            min-width: 180px;
        }
        .round h3 {
            font-size: 13px;
            text-align: center;
            margin-bottom: 12px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .matches {
            display: flex;
            flex-direction: column;
            justify-content: space-around;
            flex: 1;
            gap: 12px;
        }
        .match-card {
            border: 1px solid #1e293b;
            border-radius: 6px;
            overflow: hidden;
            font-size: 13px;
            background: #111827;
        }
        .match-card.done {
            border-color: #16a34a;
        }
        .match-card.pending {
            opacity: 0.5;
        }
        .team-row {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 6px 8px;
            border-bottom: 1px solid #1e293b;
            color: #cbd5e1;
        }
        .team-row:last-of-type {
            border-bottom: none;
        }
        .team-row.winner {
            font-weight: bold;
            color: #fff;
            background: rgba(0, 246, 255, 0.05);
        }
        .team-tag {
            font-size: 10px;
            color: #64748b;
            min-width: 28px;
        }
        .match-date {
            font-size: 10px;
            color: #64748b;
            text-align: center;
            padding: 3px;
            border-bottom: 1px solid #1e293b;
            background: #0f172a;
        }
        .match-link {
            display: block;
            text-align: center;
            font-size: 11px;
            padding: 4px;
            background: #0f172a;
            border-top: 1px solid #1e293b;
            text-decoration: none;
            color: #00F6FF;
        }
        .match-link:hover {
            background: #1e293b;
        }
        .winner-tournoi {
            margin-top: 12px;
            padding: 10px;
            border: 1px solid #16a34a;
            border-radius: 6px;
            text-align: center;
            background: rgba(22, 163, 74, 0.1);
            font-size: 14px;
            color: #fff;
        }
    </style>
</head>
<body class="min-h-screen bg-bg text-slate-100 antialiased flex flex-col">
<?php include $rootPath . '/inc/header_connected.php'; ?>
<main class="mx-auto max-w-6xl px-5 py-10 flex-1">
    <section class="rounded-[1.5rem] border border-slate-800 bg-panel/80 p-6 shadow-neon backdrop-blur-xl">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-white"><?= htmlspecialchars($tournoi['nomTournoi']) ?></h1>
                <p class="text-sm text-slate-400">Gestion du tournoi et generation du bracket.</p>
            </div>
            <a href="mesTournois.php" class="rounded-full border border-slate-700 bg-slate-900/80 px-4 py-2 text-slate-200 transition hover:border-accent hover:text-white">← Mes tournois</a>
        </div>

        <?php if ($msgSucces): ?>
            <div class="mb-4 rounded-lg bg-emerald-900/40 border border-emerald-500/50 px-4 py-3 text-emerald-300 text-sm"><?= htmlspecialchars($msgSucces) ?></div>
        <?php endif; ?>
        <?php if ($msgErreur): ?>
            <div class="mb-4 rounded-lg bg-red-900/40 border border-red-500/50 px-4 py-3 text-red-300 text-sm"><?= htmlspecialchars($msgErreur) ?></div>
        <?php endif; ?>

        <?php if ($isAdmin && empty($phases)): ?>
            <?php if ($estPlein): ?>
                <form method="POST" class="space-y-4">
                    <input type="hidden" name="action" value="generer_bracket">
                    <p class="text-sm text-slate-300">Le tournoi est complet (<?= (int)$tournoi['nbInscrits'] ?> / <?= (int)$tournoi['nbTeam'] ?> équipes).</p>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-accent px-6 py-2 text-sm font-semibold text-white hover:bg-accent/80">Générer le bracket</button>
                </form>
            <?php else: ?>
                <p class="text-sm text-slate-300">Le tournoi n'est pas encore complet (<?= (int)$tournoi['nbInscrits'] ?> / <?= (int)$tournoi['nbTeam'] ?> équipes inscrites).</p>
            <?php endif; ?>
        <?php elseif (empty($phases)): ?>
            <p class="text-sm text-slate-300">Aucun match trouvé pour ce tournoi.</p>
        <?php else: ?>

        <div class="bracket">
    <?php foreach ($phases as $phase): ?>
        <?php
            $matchsDuRound = array_values($matchsByPhase[$phase['idPhase']] ?? []);
            $isLastPhase   = ($phase['idPhase'] == end($phases)['idPhase']);
        ?>
        <div class="round">
            <h3><?= htmlspecialchars($phase['nomPhase']) ?></h3>
            <div class="matches">

                <?php foreach ($matchsDuRound as $match): ?>
                    <?php
                        $hasTeams = count($match['teams']) >= 2;
                        $isDone   = $hasTeams && !empty(array_filter($match['teams'], fn($t) => $t['vainqueur']));
                        $css      = 'match-card';
                        if ($isDone)    $css .= ' done';
                        if (!$hasTeams) $css .= ' pending';
                    ?>
                    <div class="<?= $css ?>">

                        <?php if ($match['dateMatch']): ?>
                            <div class="match-date"><?= date('d/m/Y', strtotime($match['dateMatch'])) ?></div>
                        <?php endif; ?>

                        <?php if ($hasTeams): ?>
                            <?php foreach ($match['teams'] as $team): ?>
                                <div class="team-row <?= $team['vainqueur'] ? 'winner' : '' ?>">
                                    <span class="team-tag">[<?= htmlspecialchars($team['tag'] ?? '?') ?>]</span>
                                    <?= htmlspecialchars($team['nomTeam']) ?>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="team-row">À déterminer</div>
                            <div class="team-row">À déterminer</div>
                        <?php endif; ?>

                        <?php if ($isAdmin): ?>
                            <a class="match-link" href="gererMatch.php?idMatch=<?= $match['idMatch'] ?>&idTournoi=<?= $idTournoi ?>">
                                <?= $isDone ? '✎ Modifier' : '+ Saisir' ?>
                            </a>
                        <?php endif; ?>

                    </div>
                <?php endforeach; ?>

                <?php if ($isLastPhase): ?>
                    <?php
                        $vainqueur = null;
                        foreach ($matchsDuRound as $m) {
                            foreach ($m['teams'] as $t) {
                                if ($t['vainqueur']) { $vainqueur = $t; break 2; }
                            }
                        }
                    ?>
                    <?php if ($vainqueur): ?>
                        <div class="winner-tournoi">
                            🏆 Vainqueur : <strong><?= htmlspecialchars($vainqueur['nomTeam']) ?></strong>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php endif; ?>

    </section>
</main>

</body>
</html>
