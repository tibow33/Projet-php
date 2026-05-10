<?php

    session_start();

    try {
        $bdd = new PDO('mysql:host=localhost;dbname=ProjetPHP', 'root', 'root');
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

    $pseudo = $_SESSION['pseudo'] ?? 'Admin';
?>

<a href="../connexion.php">Se déconnecter</a>
<a href="creerAdmin.php">Créer un nouveau compte admin</a>
<a href="listerJoueurs.php">Gérer les joueurs</a>
<a href="listerTournois.php">Gérer les tournois</a>
<a href="listerTeams.php">Gérer les teams</a>