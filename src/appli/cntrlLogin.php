<?php
require_once "utils.php";
require_once "src/dao/DaoUser.php";

class cntrlLogin {

    public function getLoginForm(){
        require_once PATH_VIEW . "vlogin.php";
    }

    public function getLoginResult(){
        $utils = new Utils();
        $mail       = $_POST['mail'];
        $password   = $_POST['password'];
        $daoUser    = new DaoUser(DBHOST, DBNAME, PORT, USER, PASS);

        $id = $daoUser->connectUser($mail, $password);

        if ($id == NULL) {
            $needle = "L'adresse email ou le mot de passe renseigné est incorrect";
            $utils->echoError($needle);
            require_once PATH_VIEW . "vlogin.php";

        }
        else {
            $utils->constructSession($id);
            $needle = "Vous êtes connecté";
            $utils->echoSuccess($needle);
            $cntrlApp = new CntrlApp();
            $cntrlApp->getEspacePerso();
        }
    }

};