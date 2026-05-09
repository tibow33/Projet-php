<?php
    session_start();


        try {
            $bdd = new PDO('mysql:host=localhost;dbname=ProjetPHP', 'root', '');

        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }

    $idCompte = $_SESSION['idCompte'];

    // Récupération des infos actuelles
    $req = $bdd->prepare("SELECT pseudo, email FROM Compte WHERE idCompte = ?");
    $req->execute([$idCompte]);
    $compte = $req->fetch();

    if (!$compte) {
        die("Erreur : compte introuvable.");
    }

    $message = "";

    // Traitement du formulaire
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $nouveauPseudo = $_POST['pseudo'];
        $nouvelEmail = $_POST['email'];

        // Mise à jour
        $update = $bdd->prepare("UPDATE Compte SET pseudo = ?, email = ? WHERE idCompte = ?");
        $update->execute([$nouveauPseudo, $nouvelEmail, $idCompte]);

        // Mise à jour de la session
        $_SESSION['pseudo'] = $nouveauPseudo;

        $message = "Informations mises à jour avec succès !";
    }
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Modifier mon compte</title>
        <link rel="stylesheet" href="../style.css">
    </head>
    <body>

        <h1>Modifier mon compte</h1>
        
        <?php if (!empty($message)) : ?>
            <div class="notification is-success">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>
        
        <form method="POST">
        
            <div class="field">
                <label class="label">Pseudo</label>
                <input class="input" type="text" name="pseudo" value="<?= htmlspecialchars($compte['pseudo']) ?>" required>
            </div>
        
            <div class="field">
                <label class="label">Email</label>
                <input class="input" type="email" name="email" value="<?= htmlspecialchars($compte['email']) ?>" required>
            </div>
        
            <button class="button is-primary">Enregistrer les modifications</button>
        </form>
        
        <br>
        <a href="monCompte.php" class="button">Retour à mon compte</a>

    </body>
</html>