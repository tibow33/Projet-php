<?php
session_start();
$rootPath = '..';

// Vérification : utilisateur connecté
if (empty($_SESSION['idCompte'])) {
    header('Location: ' . $rootPath . '/connexion.php');
    exit;
}

// Vérification : idTournoi passé en GET
if (empty($_GET['idTournoi']) || !is_numeric($_GET['idTournoi'])) {
    header('Location: listeTournoi.php');
    exit;
}

$idTournoi = (int) $_GET['idTournoi'];
$idCompte  = (int) $_SESSION['idCompte'];

try {
    $bdd = new PDO('mysql:host=localhost;dbname=ProjetPHP', 'root', '');
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

// Vérification : l'utilisateur est bien chef d'une team
$reqChef = $bdd->prepare("SELECT idTeam FROM team WHERE chef = ?");
$reqChef->execute([$idCompte]);
$team = $reqChef->fetch();

if (!$team) {
    // Pas chef → retour liste avec message d'erreur
    header('Location: listeTournoi.php?erreur=pas_chef');
    exit;
}

$idTeam = (int) $team['idTeam'];

// Vérification : le tournoi existe
$reqTournoi = $bdd->prepare("SELECT idTournoi, nbTeam FROM tournoi WHERE idTournoi = ?");
$reqTournoi->execute([$idTournoi]);
$tournoi = $reqTournoi->fetch();

if (!$tournoi) {
    header('Location: listeTournoi.php?erreur=tournoi_introuvable');
    exit;
}

// Vérification : la team n'est pas déjà inscrite
$reqDejaInscrit = $bdd->prepare("SELECT * FROM participe WHERE idTeam = ? AND idTournoi = ?");
$reqDejaInscrit->execute([$idTeam, $idTournoi]);

if ($reqDejaInscrit->rowCount() > 0) {
    header('Location: listeTournoi.php?erreur=deja_inscrit');
    exit;
}

// Vérification : le tournoi n'est pas complet (nbTeam = nombre max de places)
$reqNbInscrits = $bdd->prepare("SELECT COUNT(*) AS total FROM participe WHERE idTournoi = ?");
$reqNbInscrits->execute([$idTournoi]);
$nbInscrits = (int) $reqNbInscrits->fetch()['total'];

if ($nbInscrits >= (int) $tournoi['nbTeam']) {
    header('Location: listeTournoi.php?erreur=complet');
    exit;
}

// Tout est bon → inscription
$reqInscrire = $bdd->prepare("INSERT INTO participe (idTeam, idTournoi) VALUES (?, ?)");
$reqInscrire->execute([$idTeam, $idTournoi]);

header('Location: listeTournoi.php?succes=1');
exit;
