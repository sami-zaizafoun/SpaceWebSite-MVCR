<?php

set_include_path("./src");

require_once("model_profile/ProfileStorageFile.php");
require_once("model_planet/PlanetStorageFile.php");
require_once("Router.php");

$db = new DataBase();

$router = new Router(new ProfileStorageFile($db), new PlanetStorageFile($db));
$router->main();

?>
