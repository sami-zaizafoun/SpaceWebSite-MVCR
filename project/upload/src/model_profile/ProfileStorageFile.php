<?php

require_once("lib/DataBase.php");
require_once("model_profile/Profile.php");
require_once("model_profile/ProfileStorage.php");

/**
 * Manages the stocking of profiles in the database
 */
class ProfileStorageFile implements ProfileStorage {

  private $db;

  public function __construct(DataBase $db){
    $this->db = $db;
  }

  /**
   * Insert a new profile in the database
   * @param  Profile $p
   * @return array new profile
   */
  public function create(Profile $p){
    return $this->db->createProfile($p);
  }

  /**
   * verify if an account exists in the databse
   * @param  string $id
   * @return boolean true if the profile exists. False if nots
   */
  public function exists($profile){
    if($this->db->accountExists($profile)){
      return true;
    }
    return false;
  }

  /**
   * verify if an email exists in the databse
   * @param  string $id
   * @return boolean true if the email exists. False if nots
   */
  public function emailExists($email){
    if($this->db->emailExists($email)){
      return true;
    }
    return false;
  }

  /**
   * verify of login informations are valid
   * @param  array $array log in information
   * @return boolean true if information is correct. False if not
   */
  public function verify($array){
    if (self::exists($array['username'])) {
      $passwordCheck = $this->db->getPassword($array['username']);
      if(!password_verify($array['password'], $passwordCheck)){
        return false;
      }
      return true;
    }
    return true;
  }

  /**
   * Returns an array of the profile's $id, or null if the identifier does not match any profile.
   * @param  string $id
   * @return array profile information
   */
  public function read($profileId) {
    if(self::exists($profileId)){
      return $this->db->getProfile($profileId);
    }
    return;
  }

  /**
   * return an array of all profiles
   * @return array all profiles
   */
  public function readAll() {
    return $this->db->getAll();
  }

  /**
	 * Get all planets from db
	 * @return array planets
	 */
  public function readAllPlanets() {
    return $this->db->getAllPlanets();
  }

  /**
	 * select all planets from a certain user
	 * @param  string $profileId
	 * @return array  a user's planets
	 */
  public function readPlanetOwner($profileId) {
    return $this->db->getAllUserPlanets($profileId);
  }

  /**
   * update the profile in the database
   * @param  string $id
   * @param  Profile $p
   * @return boolean true if the profile is modified. False if not
   */
  public function update($profileId, Profile $p) {
    if (self::exists($profileId)) {
      $this->db->updateProfile($profileId, $p);
      return true;
    }
    return false;
  }

  /**
   * disconnect user from session
   * @param  string $id
   */
  public function disconnect($profileId){
    return $this->db->disconnect($profileId);
  }

  /**
   * delete a profile
   * @param  string $id
   * @return boolean true if the profile is deleted. False if not
   */
  public function delete($profileId) {
    if (self::exists($profileId)) {
      $this->db->deleteProfile($profileId);
      return true;
    }
    return false;
  }
}

?>
