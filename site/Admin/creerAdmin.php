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

    $message = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $pseudo = $_POST['pseudo'] ?? '';
        $email = $_POST['email'] ?? '';
        $mdp = $_POST['mdp'] ?? '';
        $mdp2 = $_POST['mdp2'] ?? '';

        if (!$pseudo || !$email || !$mdp || !$mdp2) {
            $message = "Tous les champs sont obligatoires.";
        } elseif ($mdp !== $mdp2) {
            $message = "Les mots de passe ne correspondent pas.";
        } else {
            try {
                // Vérifier que le pseudo n'existe pas
                $reqCheck = $bdd->prepare("SELECT idCompte FROM compte WHERE pseudo = ?");
                $reqCheck->execute([$pseudo]);

                if ($reqCheck->rowCount() > 0) {
                    $message = "Ce pseudo est déjà utilisé.";
                } else {
                    $hash = password_hash($mdp, PASSWORD_DEFAULT);

                    $reqCreate = $bdd->prepare("INSERT INTO compte (pseudo, eMail, mdp, typeCompte, statut) VALUES (?, ?, ?, 'Admin', 'Actif')");
                    $reqCreate->execute([$pseudo, $email, $hash]);

                    $message = "Compte admin créé avec succès pour : " . htmlspecialchars($pseudo);
                }
            } catch (PDOException $e) {
                $message = "Erreur : " . $e->getMessage();
            }
        }
    }
?>

<h2>Créer un nouveau compte admin</h2>

<?php if (!empty($message)): ?>
    <div>
        <?php echo htmlspecialchars($message); ?>
    </div>
<?php endif; ?>

<form method="POST">
    <div>
        <label for="pseudo">Pseudo :</label>
        <input type="text" id="pseudo" name="pseudo" required>
    </div>
    <div>
        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required>
    </div>
    <div>
        <label for="mdp">Mot de passe :</label>
        <input type="password" id="mdp" name="mdp" required>
    </div>
    <div>
        <label for="mdp2">Confirmer le mot de passe :</label>
        <input type="password" id="mdp2" name="mdp2" required>
    </div>
    <button type="submit">Créer le compte admin</button>
</form>
