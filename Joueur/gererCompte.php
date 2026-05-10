<?php

    session_start();
    try {
        $bdd = new PDO('mysql:host=localhost;dbname=ProjetPHP', 'root', 'root');

    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }
    $message = '';

    $idCompte = $_SESSION['idCompte'];

    // Récupération des infos du compte
    $req = $bdd->prepare("SELECT pseudo, email, dateCreation FROM Compte WHERE idCompte = ?");
    $req->execute([$idCompte]);
    $compte = $req->fetch();
?>

<a href="./modifierCompte.php">Modifier le compte</a>
<a href="./supprimerCompte.php">Supprimer le Compte</a>


<div class="carte-compte">
    <p>Pseudo : <?= htmlspecialchars($compte['pseudo']) ?></p>
    <p>Email : <?= htmlspecialchars($compte['email']) ?></p>
    <p>Date de création : <?= htmlspecialchars($compte['dateCreation']) ?></p>
</div>