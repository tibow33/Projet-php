<?php
session_start();

try {
    $bdd = new PDO('mysql:host=localhost;dbname=ProjetPHP', 'root', '');

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
        $pseudo = $req->fetch();

        if ($pseudo && password_verify($mdp, $pseudo['mdp'])) {

            $_SESSION['pseudo'] = $pseudo['pseudo'];
            $_SESSION['idCompte'] = $pseudo['idCompte'];
            header('Location: ./Joueur/accueilConnecteJoueur.php');
            exit;
        } else {
            $message = "Nom d'utilisateur ou mot de passe incorrect.";
        }
    } else {
        $message = "Veuillez remplir tous les champs.";
    }
}
?>

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