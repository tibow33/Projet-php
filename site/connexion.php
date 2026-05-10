<?php
session_start();

try {
    $bdd = new PDO('mysql:host=localhost;dbname=ProjetPHP', 'root', 'root');

} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

$message = '';

//Permet la connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
    $pseudo = $_POST['pseudo'];
    $mdp = $_POST['mdp'];

    if (!empty($pseudo) && !empty($mdp)) {
        $req = $bdd->prepare('SELECT * FROM COMPTE WHERE pseudo = :pseudo');
        $req->execute(['pseudo' => $pseudo]);
        $compte = $req->fetch();

        if ($compte && password_verify($mdp, $compte['mdp'])) {
            // Vérifier le statut du compte
            if ($compte['statut'] === 'Banni') {
                $message = "Ce compte a été banni.";
            } else {
                $_SESSION['pseudo'] = $compte['pseudo'];
                $_SESSION['idCompte'] = $compte['idCompte'];
                $_SESSION['typeCompte'] = $compte['typeCompte'];

                // Redirection selon le type de compte
                if ($compte['typeCompte'] === 'Admin') {
                    header('Location: ./Admin/accueilAdmin.php');
                } else {
                    header('Location: ./Joueur/accueilConnecteJoueur.php');
                }
                exit;
            }
        } else {
            $message = "Nom d'utilisateur ou mot de passe incorrect.";
        }
    } else {
        $message = "Veuillez remplir tous les champs.";
    }
}
?>

<?php if (!empty($message)): ?>
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
                    <label class="label">Mot de passe</label>
                    <div class="control">
                        <input class="input" type="password" name="mdp" required>
                    </div>
                </div>
                <button class="button is-primary">Se connecter</button>
            </form> 