<?php

set_include_path("./src");

require_once("model/PlanetStorageFile.php");
require_once("Router.php");

$router = new Router(new PlanetStorageFile($_SERVER['TMPDIR'].'/planet_db.txt'));
$router->main();

?>
