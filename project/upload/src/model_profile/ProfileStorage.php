<?php

require_once("Profile.php");

interface ProfileStorage{

  /**
   * Insert a new profile in the database
   * @param  Profile $p
   * @return array new profile
   */
  public function create(Profile $p);

  /**
   * verify if an account exists in the databse
   * @param  string $id
   * @return boolean true if the profile exists. False if nots
   */
  public function exists($id);

  /**
   * verify if an email exists in the databse
   * @param  string $id
   * @return boolean true if the email exists. False if nots
   */
  public function emailExists($id);

  /**
   * verify of login informations are valid
   * @param  array $array log in information
   * @return boolean true if information is correct. False if not
   */
  public function verify($array);

  /**
   * Returns an array of the profile's $id, or null if the identifier does not match any profile.
   * @param  string $id
   * @return array profile information
   */
  public function read($id);

  /**
   * return an array of all profiles
   * @return array all profiles
   */
  public function readAll();

  /**
	 * Get all planets from db
	 * @return array planets
	 */
  public function readAllPlanets();

  /**
	 * select all planets from a certain user
	 * @param  string $profileId
	 * @return array  a user's planets
	 */
  public function readPlanetOwner($profileId);

  /**
   * update the profile in the database
   * @param  string $id
   * @param  Profile $p
   * @return boolean true if the profile is modified. False if not
   */
  public function update($id, Profile $p);

  /**
   * disconnect user from session
   * @param  string $id
   */
  public function disconnect($id);

  /**
   * delete a profile
   * @param  string $id
   * @return boolean true if the profile is deleted. False if not
   */
  public function delete($id);
}

?>
