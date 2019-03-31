<?php

require_once("Planet.php");

interface PlanetStorage{

  /**
   * Insert a new planet in the database
   * @param  Planet $p
   * @return array new planet
   */
  public function create(Planet $p);

  /**
   * verify if a planet exists in the databse
   * @param  string $planet
   * @return boolean true if the planet exists. False if nots
   */
  public function exists($planet);

  /**
   * Returns an array of the planet's $id, or null if the identifier does not match any planet.
   * @param  string $id
   * @return array planet information
   */
  public function read($id);

  /**
   * return an array of all planets
   * @return array all planets
   */
  public function readAll();

  /**
   * update the planet in the database
   * @param  string $id
   * @param  Planet $p
   * @return boolean true if the plant is modified. False if not
   */
  public function update($id, Planet $p);

  /**
   * delete a planet
   * @param  string $id
   * @return boolean true if the planet is deleted. False if not
   */
  public function delete($id);
}

?>
