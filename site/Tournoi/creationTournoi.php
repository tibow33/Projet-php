<?php

    session_start();


    try {
        $bdd = new PDO('mysql:host=localhost;dbname=ProjetPHP', 'root', 'root');
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }
    $message = '';
    $createur = $_SESSION['pseudo'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $idCreateur = $_SESSION['idCompte'];
        $nomTournoi = $_POST['nomTournoi'];
        $dateDebut = $_POST['dateDebut'];
        $dateFin = $_POST['dateFin'];
        $idJeu = $_POST['idJeu'];
        $nbTeam = $_POST['nbTeam'];
        $descTournoi = $_POST['descTournoi'];

        try {
            $sql = $bdd->prepare("INSERT INTO Tournoi (nomTournoi, dateDebut, dateFin, idJeu, nbTeam, descTournoi, createur) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $sql->execute([$nomTournoi, $dateDebut, $dateFin, $idJeu, $nbTeam, $descTournoi, $idCreateur]);

            header("Location: ./../Joueur/accueilConnecteJoueur.php");
            exit;
        } catch (PDOException $e) {
            $message = "Erreur : " . $e->getMessage();
        }
    };
?>

<form method="POST">
    <div class="field">
        <label class="label">Nom du Tournoi</label>
        <div class="control">
            <input class="input" type="text" name="nomTournoi" required>
        </div>
    </div>
    <div class="field">
        <label class="label">Date Début</label>
        <div class="control">
            <input class="input" type="date" name="dateDebut" required>
        </div>
    </div>

    <div class="field">
        <label class="label">Date Fin</label>
        <div class="control">
            <input class="input" type="date" name="dateFin" required>
        </div>
    </div>

    <div class="field">
        <label class="label">Jeu</label>
        <select name="idJeu" required>
            <option value="">Selectionnez un jeu</option>

            <?php 
                $reqJeu = "SELECT idJeu, nomJeu FROM Jeu ORDER BY nomJeu ASC";
                $resultReqJeu = $bdd->query($reqJeu);

                if ($resultReqJeu->rowCount() > 0) {
                    while ($jeu = $resultReqJeu->fetch()) {
                        echo '<option value="' . $jeu['idJeu'] . '">' . $jeu['nomJeu'] . '</option>';
                    }
                }
            ?>
        </select>
    </div>

    <div class="field">
        <label class="label">Nombre de Team</label>
        <div class="control">
            <input class="input" type="number" name="nbTeam" required>
        </div>
    </div>
    <div class="field">
        <label class="label">Description</label>
        <div class="control">
            <input class="input" type="text" name="descTournoi">
        </div>
    </div>
    <div>
        <span> Créateur : <?php echo $createur = $_SESSION['pseudo'];?> </span>
    </div>

    <button class="button is-primary">Créer le Tournoi</button>


</form>
