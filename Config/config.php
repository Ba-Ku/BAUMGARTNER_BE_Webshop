<?php

header('Access-Control-Allow-Origin: http://localhost:63342');
header('Access-Control-Allow-Methods: GET');

error_reporting(E_ALL);

include "Services/InputValidation.php";
include "Services/SessionManager.php";
include "Controller/WebshopController.php";
include "Services/Database.php";
include "Models/ShoppingCart.php";
include "Models/ProductList.php";
include "Models/ProductTypeList.php";
include "Views/JsonView.php";

define("DATABASE_TYPE", "mysql");
define("DATABASE_HOST", "localhost");
define("DATABASE_NAME", "fh_beb_ueb4");
define("DATABASE_CHARSET", "utf8");
define("DATABASE_USERNAME", "root");
define("DATABASE_PASSWORD", "");

define("URL_ENDPOINT_PRODUCTTYPES", "http://localhost/BAUMGARTNER_Webshop/index.php?action=listProductsByTypeId&typeId=");
define("URL_ENDPOINT_PRODUCTS", "http://localhost/BAUMGARTNER_Webshop/index.php?action=listTypes");

