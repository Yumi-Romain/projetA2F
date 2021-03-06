<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/Database.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/Competence.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/Client.php";

session_start();

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="/cdn/main.1.css">
    <link rel="stylesheet" href="/recherche/main.css">
    <script src="/cdn/Popup.js"></script>
    <script src="/cdn/Dropdown.js"></script>
    <script src="/cdn/Search.js"></script>
    <title>Recherche</title>
</head>

<body>

    <?php include $_SERVER["DOCUMENT_ROOT"] . "/../includes/header.php" ?>

    <div class="main-wrapper">

        <div class="search">
            <input id="inputCons" type="text" class="searchBar" placeholder="Entrez un nom (optionnel)">

            <?php

                if ($_SESSION['user']['type'] == 2) {

            ?>

            <label for="archive">
                <input type="checkbox" name="archive" id="archive">
                <div for="archive" class="checkbox">✔</div>
                archive
            </label>

            <?php

                }

            ?>

            <div class="filterGrid">

                <div class="filterGridLeft borderRight">

                    Pôles :
                    <br>
                    <label for="poleIndus" class="pole">
                        <input type="checkbox" name="poleIndus" id="poleIndus">
                        <div class="checkbox">✔</div>
                        Indus
                    </label>
                    <label for="poleDatabase" class="pole">
                        <input type="checkbox" name="poleDatabase" id="poleDatabase">
                        <div class="checkbox">✔</div>
                        Database
                    </label>
                    <label for="poleSi" class="pole">
                        <input type="checkbox" name="poleSi" id="poleSi">
                        <div class="checkbox">✔</div>
                        Si
                    </label>

                </div>

                <div class="filterGridRight">
                    Disponibilités :
                    <label for="dispMtn" class="disp">
                        <input type="checkbox" name="dispMtn" id="dispMtn">
                        <div class="checkbox">✔</div>
                        Maintenant
                    </label>
                    <label for="disp1M" class="disp">
                        <input type="checkbox" name="disp1M" id="disp1M">
                        <div class="checkbox">✔</div>
                        Dans 1 mois
                    </label>
                    <br>
                    <label for="disp2M" class="disp">
                        <input type="checkbox" name="disp2M" id="disp2M">
                        <div class="checkbox">✔</div>
                        Dans 2 mois
                    </label>
                    <label for="disp3M" class="disp">
                        <input type="checkbox" name="disp3M" id="disp3M">
                        <div class="checkbox">✔</div>
                        Dans 3 mois et plus
                    </label>
                </div>

                <div class="filterGridLarge borderTop divClientList">

                    <div class="btn fakeComp" onclick="Popup.open('popupClient')">Selection des clients</div>

                </div>

                <div class="filterGridLarge borderTop divCompList">

                    <div class="btn fakeComp" onclick="Popup.open('popupComp')">Selection des competences</div>

                </div>

            </div>

        </div>

        <div class="searchBtn btn" onclick="search.send()">Rechercher</div>

    </div>

    <div class="popup" id="popupComp">

        <div class="compList">

            <div class="compListContainer">

                <?php

                $comp = Competence::get_array();

                // parcour d'un tableau et ressort le nom de la competence et ses enfant ( recursion )
                function tab($tab, $cpt) {

                    ?>
                        <div id="ddc<?php echo $cpt?>" class="dropdownContainer">
                    <?php

                    $cpt += 1;
                
                    foreach ($tab as $name => $value) {
                    
                        if ($value["enfant"] != null) {
                            
                            ?>

                                <div id="ddt<?php echo $cpt?>" class="dropdownTrigger">
                                    <?php echo $name; ?>
                                </div>

                                <?php
                            
                            $returned = tab($value["enfant"], $cpt);

                            $cpt = $returned["cpt"];

                        } else {

                            ?>

                                <div data-name="<?php echo $name; ?>" data-id="<?php echo $value["id_competence"]; ?>" class="competence">
                                    <?php echo $name; ?>
                                </div>

                            <?php

                        }
                        
                    }

                    ?>
                        </div>
                    <?php

                    return array(
                        "cpt" => $cpt
                    );

                };

                $cpt = 0;

                foreach ($comp as $name => $value) {

                    if ($value["enfant"] != null) {

                        ?>
                            <div id="ddt<?php echo $cpt?>" class="dropdownTrigger">
                                <?php echo $name; ?>
                            </div>
                        <?php
                        
                        $returned = tab($value["enfant"], $cpt);

                        $cpt = $returned["cpt"];

                    }

                }

                ?>

                <script>
                    Dropdown.load();
                </script>

            </div>

        </div>

        <div class="compListSelected">

            <div class="compListContainer divCompListS">

            </div>

        </div>

        <div class="compSelectClose btn close" onclick="Popup.close('popupComp', search)">Valider</div>

    </div>

    <div class="popup" id="popupClient">

        <div class="compList">

            <div class="clientListContainer">

                <?php

                $c = Client::get_array();

                foreach ($c as $client) {

                    ?>

                    <div data-name="<?php echo $client["entreprise"] ?>" data-id="<?php echo $client["id_client"]; ?>" class="client">
                        <?php echo $client["entreprise"] ?>
                    </div>

                    <?php
                    
                }

                ?>

            </div>

        </div>

        <div class="clientListSelected">

            <div class="clientListContainer divClientListS">

            </div>

        </div>

        <div class="clientSelectClose btn close" onclick="Popup.close('popupClient')">Valider</div>

    </div>

    <script>
        var search = new Search();
    </script>

</body>

</html>