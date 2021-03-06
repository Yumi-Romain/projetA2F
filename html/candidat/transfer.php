<?php

include_once $_SERVER["DOCUMENT_ROOT"] . "/../classes/Security.php";

session_start();

Security::check_login(array(1, 2));

if (!isset($_GET["ajaxtrue"])) {
    include_once $_SERVER["HTTP_HOST"] . "/erreurs/403.php";
    exit();
}

?>

<style>
    :root {
        --main-color: #06436f;
        --main-color-light: #06436f88;
        --auto-color: white;
    }
</style>
<style>
    .alertContainer {
        padding: 50px;
        width: 100%;
        text-align: center;
        font-family: 'Roboto', sans-serif;
    }
    .imgPole {
        display: inline-block;
        margin: 10px 20px;
        padding: 10px;
        cursor: pointer;
    }
    .imgPole>img {
        filter: grayscale(1);
        -webkit-filter: grayscale(1);
        -moz-filter: grayscale(1);
        -o-filter: grayscale(1);
        -ms-filter: grayscale(1);
        border-radius: 7px;
        transition: .2s filter ease, .2s box-shadow ease;
        padding: 10px;
        border: 2px solid rgba(0, 0, 0, .1);
    }
    .imgPole:hover>img,
    .active>img {
        filter: grayscale(0);
        -webkit-filter: grayscale(0);
        -moz-filter: grayscale(0);
        -o-filter: grayscale(0);
        -ms-filter: grayscale(0);
        box-shadow: 0px 0px 10px -1px rgba(0, 0, 0, .8);
    }
    .btnTransfert {
        display: inline-block;
        padding: 10px 15px;
        border: 2px solid rgba(0, 0, 0, .1);
        border-radius: 4px;
        transition: .2s background-color ease, .2s color ease;
        cursor: pointer;
        margin-right: 20px;
        margin-top: 20px;
    }
    .btnTransfert:hover {
        background-color: var(--main-color);
        color: var(--auto-color);
    }
</style>

<div class="alertContainer">
    <div class="mainText">
        <h3>Choix du pôle</h3>    
    </div>

    <div onclick="Transfer.Indus()" class="imgPole" id="Indus">
        <img src="/images/schema-industrie-15.svg" alt="logo a2f industrie" height="100" width="100"><br>
        <label for="Indus">Indus</label>
    </div>

    <div onclick="Transfer.SI()" class="imgPole" id="SI">
        <img src="/images/visuel-si.svg" alt="logo a2f SI" height="100" width="100"><br>
        <label for="SI">SI</label>
    </div>

    <div onclick="Transfer.Database()" class="imgPole" id="Database">
        <img src="/images/schema-database-17.svg" alt="logo a2f database" height="100" width="100"><br>
        <label for="Database">Database</label>
    </div>

    <br>

    <div onclick="Alert.close()" class="btnTransfert">Annuler</div>

    <div id="TransferBtn" onclick="Transfer.send(this)" class="btnTransfert">Transférer</div>

</div>