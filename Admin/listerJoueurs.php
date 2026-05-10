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
    if (isset($_GET['idJoueur']) && isset($_GET['action'])) {
        try {
            $newStatus = ($_GET['action'] === 'ban') ? 'Banni' : 'Actif';
            $reqUpdate = $bdd->prepare("UPDATE compte SET statut = ? WHERE idCompte = ?");
            $reqUpdate->execute([$newStatus, $_GET['idJoueur']]);
        } catch (PDOException $e) {
            die("Erreur : " . $e->getMessage());
        }
    }

    // Récupérer tous les joueurs (sauf les admins)
    $reqJoueurs = $bdd->prepare("SELECT idCompte, pseudo, eMail, statut, dateCreation FROM compte WHERE typeCompte IS NULL OR typeCompte != 'Admin' ORDER BY pseudo ASC");
    $reqJoueurs->execute();
?>

<a href="../deconnexion.php">Se déconnecter</a>
<a href="accueilAdmin.php">Accueil Admin</a>

<h2>Gestion des Joueurs</h2>

<table>
    <tr>
        <th>Pseudo</th>
        <th>Email</th>
        <th>Date de création</th>
        <th>Statut</th>
        <th>Actions</th>
    </tr>

    <?php while ($joueur = $reqJoueurs->fetch()): ?>
    <tr>
        <td><?php echo htmlspecialchars($joueur['pseudo']); ?></td>
        <td><?php echo htmlspecialchars($joueur['eMail']); ?></td>
        <td><?php echo htmlspecialchars($joueur['dateCreation']); ?></td>
        <td>
            <?php echo htmlspecialchars($joueur['statut']); ?>
        </td>
        <td>
            <?php if ($joueur['statut'] === 'Actif'): ?>
                <a href="?idJoueur=<?php echo $joueur['idCompte']; ?>&action=ban">Bannir</a>
            <?php else: ?>
                <a href="?idJoueur=<?php echo $joueur['idCompte']; ?>&action=unban">Débannir</a>
            <?php endif; ?>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
