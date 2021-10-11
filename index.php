<?php

session_start();

include "Config/config.php";

$webshopController = new WebshopController();
//$webshopController->manageSession();
$webshopController->route();


