<?php
define("PROJECT_ROOT_PATH", __DIR__ . "/../");
define("DB_PATH", __DIR__ . "/../db/");

// include the base controller file
require_once PROJECT_ROOT_PATH . "/Controller/Api/BaseController.php";

// include the use model file
require_once PROJECT_ROOT_PATH . "/Model/UserModel.php";
?>