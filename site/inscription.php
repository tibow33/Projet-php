<?php
    $message = "";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $pseudo = $_POST["pseudo"];
        $eMail = $_POST["eMail"];
        $age = $_POST["age"];
        $password = $_POST["password"];
        $confirm  = $_POST["confirm"];
                
        // Vérif password
        if ($password !== $confirm) {
            $message = "Les mots de passe ne correspondent pas.";
        }
                
        //Crée le compte
        else {
            try {
                $bdd = new PDO("mysql:host=localhost;dbname=ProjetPHP;charset=utf8", "root", "");


                $hash = password_hash($password, PASSWORD_DEFAULT);

                $sql = $bdd->prepare("INSERT INTO Compte (pseudo, eMail, age, mdp) VALUES (?, ?, ?, ?)");
                $sql->execute([$pseudo, $eMail, $age, $hash]);

                header("Location: ./Joueur/accueilConnecteJoueur.php");
                exit;

                } catch (PDOException $e) {
                    $message = "Erreur : " . $e->getMessage();
                }
            }
    }
?>

            <?php if (!empty($message)) : ?>
                <div class="notification is-danger">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <form method="POST">

                <div class="field">
                    <label class="label">Pseudo</label>
                    <div class="control">
                        <input class="input" type="text" name="pseudo" required>
                    </div>
                </div>
                <div class="field">
                    <label class="label">e-Mail</label>
                    <div class="control">
                        <input class="input" type="text" name="eMail" required>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Age</label>
                    <div class="control">
                        <input class="input" type="text" name="age" required>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Mot de passe</label>
                    <div class="control">
                        <input class="input" type="password" name="password" required>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Confirmer le mot de passe</label>
                    <div class="control">
                        <input class="input" type="password" name="confirm" required>
                    </div>
                </div>
                <button class="button is-primary">S'inscrire</button>
            </form>
