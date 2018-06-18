<?php

class BM {
    
    
    static public function register($nom, $prenom) {

        $login = substr($prenom, 0, 1) . $nom;

        $cpt = 0;
        while (!Security::login_validity($login)) {
            $cpt++;
            $login = substr($prenom, 0, 1) . $nom . $cpt;
        }

        $token = hash("sha256", $login . bin2hex(random_bytes(50)) . $pole);

        $pdo = Database::connect();
        
        $statement = $pdo->prepare("INSERT INTO BM (nom, prenom, login, token) VALUES (:nom, :prenom, :login, :token)");
        $statement->execute(array(
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':login' => $login,
            ':token' => $token
        ));

        $id = $pdo->lastInsertID();

        $pdo = null;

        $url = "http://" . $_SERVER["HTTP_HOST"] . "/register/?token=" . $token;

        return array(
            "url" => $url,
            "id" => $id);

    }

}