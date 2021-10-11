<?php


class SessionManager
{
    public function __construct()
    {

    }

    public function setCookie()
    {
        setCookie("HygieneWebshopCookie", $_SESSION['hashedUsername'],  time() + 18000, "/","BAUMGARTNER_Webshop_FE", true);
    }

    public function logIn()
    {
        //$name = $_POST['name'];
        if (empty($name) || !isset($name)) {
            $name = "Guest";
        }
        $_SESSION['username'] = $name;
        $_SESSION['hashedUsername'] = md5($name);
    }

    public function logOut(){
        session_destroy();
    }
}