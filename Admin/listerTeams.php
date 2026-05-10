<?php

    session_start();

    try {
        $bdd = new PDO('mysql:host=localhost;dbname=ProjetPHP', 'root', 'root');
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

<a href="../deconnexion.php">Se déconnecter</a>
<a href="accueilAdmin.php">Accueil Admin</a>

<h2>Gestion des Teams</h2>

<table>
    <tr>
        <th>Nom de la Team</th>
        <th>Tag</th>
        <th>Chef</th>
        <th>Statut</th>
        <th>Actions</th>
    </tr>

    <?php while ($team = $reqTeams->fetch()): ?>
    <tr>
        <td><?php echo htmlspecialchars($team['nomTeam']); ?></td>
        <td><?php echo htmlspecialchars($team['tag']); ?></td>
        <td><?php echo htmlspecialchars($team['pseudo']); ?></td>
        <td>
            <?php echo htmlspecialchars($team['statut']); ?>
        </td>
        <td>
            <?php if ($team['statut'] === 'Actif'): ?>
                <a href="?idTeam=<?php echo $team['idTeam']; ?>&action=ban">Bannir</a>
            <?php else: ?>
                <a href="?idTeam=<?php echo $team['idTeam']; ?>&action=unban">Débannir</a>
            <?php endif; ?>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
