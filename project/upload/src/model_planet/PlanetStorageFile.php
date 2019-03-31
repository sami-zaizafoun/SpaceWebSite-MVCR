<?php

require_once("lib/DataBase.php");
require_once("model_planet/Planet.php");
require_once("model_planet/PlanetStorage.php");

/**
 * Manages the stocking of planets in the database
 */
class PlanetStorageFile implements PlanetStorage {

  private $db;

  public function __construct(DataBase $db){
    $this->db = $db;
  }

  /**
   * Insert a new planet in the database
   * @param  Planet $p
   * @return array new planet
   */
  public function create(Planet $p){
    return $this->db->createPlanet($p);
  }

  /**
   * verify if a planet exists in the databse
   * @param  string $planet
   * @return boolean true if the planet exists. False if nots
   */
  public function exists($planet){
    if($this->db->planetExists($planet)){
      return true;
    }
    return false;
  }

  /**
   * Returns an array of the planet's $id, or null if the identifier does not match any planet.
   * @param  string $id
   * @return array planet information
   */
  public function read($planetId) {
    if(self::exists($planetId)){
      return $this->db->getPlanet($planetId);
    }
    return;
  }

  /**
   * return an array of all planets
   * @return array all planets
   */
  public function readAll() {
    return $this->db->getAllPlanets();
  }

  /**
   * update the planet in the database
   * @param  string $id
   * @param  Planet $p
   * @return boolean true if the plant is modified. False if not
   */
  public function update($planetId, Planet $p) {
    if (self::exists($planetId)) {
      $this->db->updatePlanet($planetId, $p);
      return true;
    }
    return false;
  }

  /**
   * delete a planet
   * @param  string $id
   * @return boolean true if the planet is deleted. False if not
   */
  public function delete($planetId) {
    if (self::exists($planetId)) {
      $this->db->deletePlanet($planetId);
      return true;
    }
    return false;
  }
}

?>
