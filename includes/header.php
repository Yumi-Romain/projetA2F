<header class="mainColor">

    <div class="header-left">
        <img id="logo-a2f" src="/images/logo-a2f-blanc-02.svg" height="46">
    </div>

    <div class="header-left">
        <div class="autoColor">Bienvenue, <span><?php echo $_SESSION['user']['login']; ?></span></div>
    </div>

    <?php if ($_SESSION['user']["type"] == 0) {?>
    <div class="header-left mr-1">
        <a class="btn btn-header bold noMI autoColor" href="/consultant">Mon profil</a>
    </div>
    <?php } ?>

    <?php if ($_SESSION['user']["type"] >= 1) {?>
    <div class="header-left mr-1">
        <a class="btn btn-header bold noMI autoColor" href="/recherche/stats">Statistiques</a>
    </div>
    <?php } ?>

    <div class="header-left mr-1">
        <a class="btn btn-header bold noMI autoColor" href="/recherche">Recherche</a>
    </div>

    <?php if ($_SESSION['user']["type"] >= 1) {?>
    <div class="header-left mr-1">
        <a class="btn btn-header bold noMI autoColor" href="/admin">Page Admin</a>
    </div>
    <?php } ?>

    <div class="header-right noMI">
        <a class="btn btn-header bold noMI autoColor" href="/disconnect.php">Déconnexion</a>
    </div>

</header>