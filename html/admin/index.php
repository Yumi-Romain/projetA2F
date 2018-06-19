<?php

include_once $_SERVER["DOCUMENT_ROOT"] . "/../classes/Database.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/../classes/Competence.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/../classes/Client.php";    
include_once $_SERVER["DOCUMENT_ROOT"] . "/../classes/Consultant.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/../classes/Candidat.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/../classes/BM.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/../classes/RH.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/../classes/Security.php";

session_start();

$token = Security::gen_token('4');

if ($_SESSION['user']['type'] == 0 && 0) {
    echo "vous n'avez pas accès à la page d'administration en tant que consultant";
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/cdn/main.css">
    <link rel="stylesheet" href="/admin/main.css">
    <script src="/cdn/Ajax.js"></script>
    <script src="/cdn/Alert.js"></script>
    <script src="/cdn/Post.js"></script>
    <script src="/cdn/Admin.js"></script>
    <script src="/cdn/Dropdown.js"></script>
    <script src="/cdn/Popup.js"></script>
    <script src="/cdn/Profile.js"></script>
    <script src="/cdn/Post.js"></script>
    <title>A2F advisor</title>
</head>
<body>
    <nav>
        <div onclick="Popup.open('Comp')" class="onglet">Compétences</div>
        <div onclick="Popup.open('rh')" class="onglet">Ressources humaines</div>
        <div onclick="Popup.open('bm')" class="onglet">Business manager</div>
        <div onclick="Popup.open('cons')" class="onglet">Consultants</div>
        <div onclick="Popup.open('candidats')" class="onglet">Candidats</div>
        <div onclick="Popup.open('client')" class="onglet">Clients</div>
    </nav>
    <div class="mainWrapper">

        <div  onclick="location.href='/'" class="close">Retour</div>


<!-- COMPÉTENCE -->

        <div class="popup" id="Comp"><div class="nav">Compétences</div>
            <div class="relative-wrapper-container">
                <?php

                    $cpt = 0;
                    
                    function tab($tab, $cpt) {

                        ?><div id="ddc<?php echo $cpt; ?>" class="dropdownContainer"><?php

                        $cpt += 1;
                        
                        foreach ($tab as $name => $value) {
                            
                            if ($value["enfant"] != null) {
                                
                                ?><div id="ddt<?php echo $cpt; ?>" class="dropdownTrigger"><?php echo $name; ?>
                                <div onclick="Admin.addDaughter(<?php echo $value["id_competence"]; ?>, <?php echo $cpt; ?>)" data-name="<?php echo $name; ?>" data-id="<?php echo $value["id_competence"]; ?>" class="competence borderComp">Ajouter une compétence fille</div>
                                <div onclick="Post.send('admin/traitement.php', { 'id' : '<?php echo $value["id_competence"]; ?>' , 'action' : 'delete'})" data-name="<?php echo $name; ?>" data-id="<?php echo $value["id_competence"]; ?>" class="competence borderComp">Supprimer</div>
                            
                                </div>
                                
                                <?php

                                $returned = tab($value["enfant"], $cpt);

                                $cpt = $returned["cpt"];

                            } else {

                                ?><div class="competence"><?php echo $name; ?>
                                    <div onclick="Post.send('/admin/traitement.php', { 'id' : '<?php echo $value["id_competence"]; ?>' , 'action' : 'delete'})" data-name="<?php echo $name; ?>" data-id="<?php echo $value["id_competence"]; ?>" class="competence borderComp">Supprimer</div>
                                </div><?php

                            }
                            
                        }

                        ?>

                        </div><?php

                        return array(
                            "cpt" => $cpt,
                        );
                    
                    };

                    $comp = Competence::get_array();
                    
                    foreach ($comp as $name => $value) {
                    
                        if (is_array($value)) {

                            ?><div id="ddt<?php echo $cpt; ?>" class="dropdownTrigger"><?php echo $name ?>

                            <div onclick="Admin.addDaughter(<?php echo $value["id_competence"]; ?>, <?php echo $cpt; ?>)" data-name="<?php echo $name; ?>" data-id="<?php echo $value["id_competence"]; ?>" class="competence borderComp">Ajouter une compétence fille</div>

                            </div><?php
                            
                            $returned = tab($value["enfant"], $cpt);

                            $cpt = $returned["cpt"];

                        } else {

                            ?><div><?php echo $name; ?> - <?php echo $value; ?></div><?php

                        }

                    }

                ?>
            </div>
        </div>


<!-- RH -->

        <div class="popup" id="rh"><div class="nav">Ressources humaines</div>
            <div class="relative-wrapper-container">
                <?php

                    $rh = RH::get_array();
                    foreach ($rh as $name => $value) {
                        
                        ?><div><?php  echo $value['nom']; ?> - 
                        <?php echo $value['prenom']; ?></div>
                    <?php 
                    }
                    ?>

                <!-- <div class="popup" id="rh"><div class="nav">Responsable</div> -->
                    
                    <form action="/admin/traitement.php/" method="post">
                        
                        <input type="hidden" name="token" value="<?php echo $token; ?>" >
                        <input type="hidden" name='action' value='add_rh'>

                        <input type="text" name="nom" placeholder="Nom" required>
                        <input type="text" name="prenom" placeholder="Prenom" required>

                        <input type="submit" value="Envoyer">

                    </form>
                
                <!-- </div> -->
            </div>
        </div>


<!-- BM -->

        <div class="popup" id="bm"><div class="nav">Business manager</div>
            <div class="relative-wrapper-container">
            <?php

                $bm = BM::get_array();

                foreach ($bm as $name => $value) {
                    
                    ?><div><?php  echo $value['nom']; ?> - 
                    <?php echo $value['prenom']; 
                    
                    echo "<form action='/admin/traitement.php/' method='post'>
                    <input type='hidden' name='token' value=" . $token ." >
                    <input type='hidden' name='action' value='delete_bm'>
                    <input type='hidden' name='id_bm' value='" .$value['id_bm'] . "'>
                    
                    <input type='submit' value='Supprimer'";?></div>
                <?php 
                   

                }
                ?>

                <!-- <div class="popup" id="rh"><div class="nav">Responsable</div> -->
    
                <form action="/admin/traitement.php/" method="post">
                    
                        <input type="hidden" name="action" value="add_bm">

                        <input type="text" name="nom" placeholder="Nom" required>
                        <input type="text" name="prenom" placeholder="Prenom" required>

                        <input type="submit" value="Envoyer">

                    </form>
            
                <!-- </div> -->
            </div>
        </div>


<!-- CONSULTANT -->

        <div class="popup" id="cons"><div class="nav">Consultants</div>
            <div class="relative-wrapper-container">
            <?php

                $cons = Consultant::get_array();

                foreach ($cons as $nsame => $value) {
                    
                    ?><div><?php  echo $value['nom']; ?> - 
                    <?php echo $value['prenom']; ?>
                    - Pole <?php echo $value['nom_pole']; ?></div>
                <?php 
                }
                ?>
                
                <!-- <div class="popup" id="rh"><div class="nav">Responsable</div> -->
    
                <form action="/admin/traitement.php/" method="post">
                        
                        <input type="hidden" name="action" value="add_consultant">
                        <input type="hidden" name="token" value="<?php echo $token; ?>" >

                        <input type="text" name="nom" placeholder="Nom" required>
                        <input type="text" name="prenom" placeholder="Prenom" required>
                        <select name="pole" required><option selected disabled>Pole</option><?php

                        $p = array(
                            "1" => "SI",
                            "2" => "Indus",
                            "3" => "Database"
                        );
                        for($i=1; $i <= 3; $i++){
                            ?>
                            <option value=" <?php echo $i; ?>"><?php echo $p[$i]; ?> </option><?php
                        }
                        ?>

                        <input type="submit" value="Envoyer">

                    </form>
        
                <!-- </div> -->
            </div>
        </div>


<!-- CANDIDAT -->

        <div class="popup" id="candidats"><div class="nav">Candidats</div>
             <div class="relative-wrapper-container">
             <?php

                $cand = Candidat::get_array();

                foreach ($cand as $nsame => $value) {
                    
                    ?><div><?php  echo $value['nom']; ?> - 
                    <?php echo $value['prenom']; ?></div>
                <?php 
                }
                ?> 
                <!-- <div class="popup" id="rh"><div class="nav">Responsable</div> -->
    
                <form action="/admin/traitement.php/" method="post">
                            
                        <input type="hidden" name="" value="add_candidat">
                        <input type="hidden" name="token" value="<?php echo $token; ?>" >

                        <input type="text" name="nom" placeholder="Nom" required>
                        <input type="text" name="prenom" placeholder="Prenom" required>


                        <input type="submit" value="Envoyer">

                    </form>
            
                <!-- </div> -->
            </div>
        </div>


<!-- CLIENT -->

        <div class="popup" id="client"><div class="nav">Clients</div>
             <div class="relative-wrapper-container">
             <?php

                $client = Client::get_array();

                foreach ($client as $nsame => $value) {
                    
                    ?><div><?php  echo $value['entreprise'];?></div>
                <?php 
                }
                ?>
                <!-- <div class="popup" id="rh"><div class="nav">Responsable</div> -->
    
                <form action="/admin/traitement.php/" method="post">
                        
                        <input type="hidden" name="" value="add_client">
                        <input type="hidden" name="token" value="<?php echo $token; ?>" >

                        <input type="text" name="nom" placeholder="Nom" required>
                        <input type="text" name="prenom" placeholder="Prenom" required>

                        <input type="submit" value="Envoyer">

                       </form>
    
                <!-- </div> -->
            </div>
        </div>
    </div> 
    <?php var_dump($_POST['url']); ?>
<?php if(isset($_POST['url'])){ ?>
    <script>    

    Alert.popup({
        title: "Compte créé",
        text: "Création du mot de passe : \n <?php echo $_POST['url']; ?>",
        confirmColor: "#bbbbbb",
        confirmText: "Retour",
        confirm: function() {
            Alert.close();
        }
    })
    </script> <?php
} ?>

    <script>
        Popup.open('Comp');
        Dropdown.load();
    </script>   

</body>
</html>