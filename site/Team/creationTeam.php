<?php

    session_start();


    try {
        $bdd = new PDO('mysql:host=localhost;dbname=ProjetPHP', 'root', '');

    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }
    $message = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $chef = $_SESSION['idCompte'];
        $nomTeam = $_POST['nomTeam'];
        $tag = $_POST['tag'];

        try {
            $bdd = new PDO("mysql:host=localhost;dbname=ProjetPHP;charset=utf8", "root", "");

            $sql = $bdd->prepare("INSERT INTO Team (nomTeam, chef, tag) VALUES (?, ?, ?)");
            $sql->execute([$nomTeam, $chef, $tag]);

            header("Location: ./accueilConnecteJoueur.php");
            exit;
        } catch (PDOException $e) {
            $message = "Erreur : " . $e->getMessage();
        }
    };
?>

            <?php if (!empty($message)) : ?>
                <div class="notification is-danger">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <form method="POST">

                <div class="field">
                    <label class="label">Nom de la Team</label>
                    <div class="control">
                        <input class="input" type="text" name="nomTeam" required>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Tag (4 caractères max)</label>
                    <div class="control">
                        <input class="input" type="text" name="tag" required>
                    </div>
                </div>
                <div>
                    <span> Chef : <?php echo $chef = $_SESSION['pseudo'] ?> </span>
                </div>
                <button class="button is-primary">Créer la team</button>

            </form>