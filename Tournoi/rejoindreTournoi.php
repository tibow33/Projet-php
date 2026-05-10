<?php
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=ProjetPHP', 'root', 'root');

$idTournoi = $_GET['idTournoi'];
$idCompte = $_SESSION['idCompte'];

// Récupérer la team du chef
$reqTeam = $bdd->prepare("SELECT idTeam FROM team WHERE chef = ?");
$reqTeam->execute([$idCompte]);
$team = $reqTeam->fetch();

if ($team) {
    $idTeam = $team['idTeam'];

    // Inscrire la team
    $reqInsert = $bdd->prepare("INSERT INTO participe (idTeam, idTournoi) VALUES (?, ?)");
    $reqInsert->execute([$idTeam, $idTournoi]);
}

header("Location: listeTournoi.php");
exit;
?>