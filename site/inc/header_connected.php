<?php
// Header for connected users — expects $rootPath to be defined and session started
?>
<header class="border-b border-slate-800 bg-panel/90 backdrop-blur-md shadow-neon">
    <div class="mx-auto flex max-w-6xl flex-col gap-4 px-5 py-5 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm uppercase tracking-[0.35em] text-neon/80">Gaming Hub</p>
            <p class="mt-1 text-xs text-slate-400">Plateforme de tournois, équipes et défis</p>
        </div>
        <nav class="flex flex-wrap items-center gap-3 text-sm">
            <a href="<?= $rootPath ?>/Joueur/accueilConnecteJoueur.php" class="rounded-full border border-slate-700 bg-slate-900/80 px-4 py-2 text-slate-200 transition hover:border-accent hover:text-white">Accueil</a>
            <a href="<?= $rootPath ?>/listeJeux.php" class="rounded-full border border-slate-700 bg-slate-900/80 px-4 py-2 text-slate-200 transition hover:border-glow hover:text-white">Jeux</a>
            <a href="<?= $rootPath ?>/Team/listeTeam.php" class="rounded-full border border-slate-700 bg-slate-900/80 px-4 py-2 text-slate-200 transition hover:border-glow hover:text-white">Teams</a>
            <a href="<?= $rootPath ?>/Tournoi/listeTournoi.php" class="rounded-full border border-slate-700 bg-slate-900/80 px-4 py-2 text-slate-200 transition hover:border-glow hover:text-white">Tournois</a>
            <?php if (!empty($_SESSION['pseudo'])): ?>
                <a href="<?= $rootPath ?>/deconnexion.php" class="rounded-full border border-slate-700 bg-slate-900/80 px-4 py-2 text-slate-200 transition hover:border-glow hover:text-white">Déconnexion</a>
                <a href="<?= $rootPath ?>/Joueur/accueilConnecteJoueur.php" class="rounded-full border border-slate-700 bg-accent/10 px-4 py-2 text-accent transition hover:bg-accent/20">Mon Espace</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
