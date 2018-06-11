<?php

class Security {

    static public function gen_token($id) {

        $token = bin2hex(random_bytes(32));

        $_SESSION['user']['token'][$token] = $id;

        return true;

    }

    static public function check_token($token, $id) {

        if ($_SESSION['user']['token'][$token] == $id) {

            unset($_SESSION['user']['token'][$token]);

            return true;

        } else return false;

    }

    static public function check_login($lvl) {

        if (isset($_SESSION['user']['connected'])) {

            if (!$_SESSION['user']['connected']) header("locaton: http://" . $_SERVER['HTTP_HOST'] . "/identification/");

        } else header("locaton: http://" . $_SERVER['HTTP_HOST'] . "/identification/");

        if (!in_array($_SESSION['user']['type'], $lvl)) {

            include_once $_SERVER['DOCUMENT_ROOT'] . "/erreurs/403.php";
            exit();

        }

    }

}