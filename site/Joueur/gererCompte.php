<?php

    session_start();
    try {
        $bdd = new PDO('mysql:host=localhost;dbname=ProjetPHP', 'root', '');

    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }
    $message = '';

    $idCompte = $_SESSION['idCompte'];

    // Récupération des infos du compte
    $req = $bdd->prepare("SELECT pseudo, email, dateCreation, age FROM Compte WHERE idCompte = ?");
    $req->execute([$idCompte]);
?>

<a href="./modifierCompte.php">Modifier le compte</a>
<a href=".:supprimerCompte.php">Supprimer le Compte</a>


<div class="carte-compte">
    <p>>Pseudo : <?= htmlspecialchars($compte['pseudo']) ?></p>
    <p>>Email : <?= htmlspecialchars($compte['email']) ?></p>
    <p>>Compte créé le : <?= htmlspecialchars($compte['dateCreation']) ?></p>
    <p>Âge : <?= htmlspecialchars($compte['age']) ?></p>
</div>