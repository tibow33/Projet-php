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
    if (isset($_GET['idTournoi']) && isset($_GET['action'])) {
        try {
            $newStatus = ($_GET['action'] === 'ban') ? 'Banni' : 'Actif';
            $reqUpdate = $bdd->prepare("UPDATE tournoi SET statut = ? WHERE idTournoi = ?");
            $reqUpdate->execute([$newStatus, $_GET['idTournoi']]);
        } catch (PDOException $e) {
            die("Erreur : " . $e->getMessage());
        }
    }

    // Récupérer tous les tournois
    $reqTournois = $bdd->prepare("
        SELECT T.idTournoi, T.nomTournoi, T.dateDebut, T.dateFin, T.statut, J.nomJeu
        FROM tournoi T
        LEFT JOIN jeu J ON J.idJeu = T.idJeu
        ORDER BY T.nomTournoi ASC
    ");
    $reqTournois->execute();
?>

<a href="../deconnexion.php">Se déconnecter</a>
<a href="accueilAdmin.php">Accueil Admin</a>

<h2>Gestion des Tournois</h2>

<table>
    <tr>
        <th>Nom du Tournoi</th>
        <th>Jeu</th>
        <th>Date Début</th>
        <th>Date Fin</th>
        <th>Statut</th>
        <th>Actions</th>
    </tr>

    <?php while ($tournoi = $reqTournois->fetch()): ?>
    <tr>
        <td><?php echo htmlspecialchars($tournoi['nomTournoi']); ?></td>
        <td><?php echo htmlspecialchars($tournoi['nomJeu']); ?></td>
        <td><?php echo htmlspecialchars($tournoi['dateDebut']); ?></td>
        <td><?php echo htmlspecialchars($tournoi['dateFin']); ?></td>
        <td>
            <?php echo htmlspecialchars($tournoi['statut']); ?>
        </td>
        <td>
            <?php if ($tournoi['statut'] === 'Actif'): ?>
                <a href="?idTournoi=<?php echo $tournoi['idTournoi']; ?>&action=ban">Bannir</a>
            <?php else: ?>
                <a href="?idTournoi=<?php echo $tournoi['idTournoi']; ?>&action=unban">Débannir</a>
            <?php endif; ?>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
