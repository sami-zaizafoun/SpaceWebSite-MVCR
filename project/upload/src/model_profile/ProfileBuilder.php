<?php

require_once("model_profile/Profile.php");

/**
 * profile manipulation functions via forms
 */
class ProfileBuilder {
  protected $data;
  protected $avatarData;
  protected $error;
  protected $storage;

  /**
   * Creates a new instance, with the data passed as an argument if they exist,
   * and otherwise with the default values of the fields for creating a profile.
   * @param string $data  $_POST data
   * @param string $avatarData $_FILE data
   */
  public function __construct($data=null, $avatarData=null) {
    if ($data === null || $avatarData === null) {
			$data = array(
				"username" => "",
        "email" => "",
        "password" => "",
        "confirmPassword" => "",
        "status" => "user",
			);
      $avatarData = array(
        "avatar" => "",
      );
		}
		$this->data = $data;
    $this->avatarData = $avatarData;
		$this->errors = array();
    $this->imageFormat = array("image/jpg","image/jpeg","image/png",);
  }

  /**
   * checks if the uploaded image is an acceptable format
   * @param  array $format image extensions
   * @return boolean true if format is acceptable
   */
  public function checkImageFormat($format){
    if(in_array($format , $this->imageFormat)) {
      return true;
    }
    return false;
  }

  /**
   * Returns a new instance of ProfileBuilder with the editable data of the profile passed as an argument.
   * @param  Profile $profile
   * @return array instance of ProfileBuilder
   */
  public static function buildFromProfile(Profile $profile) {
    return new ProfileBuilder(array(
			"username" => $profile->getUserName(),
      "email" => $profile->getEmail(),
      "password" => "",
      "confirmPassword" => "",
      "status" => "user",
		),
    array(
      "avatar" => $profile->getAvatar(),
    ));
	}

  /**
   * Verifies the validity of the data sent by the client,
   * and returns an array of errors to correct.
   * @return boolean true is valid. False if not
   */
  public function isLogInValid(){
    $this->errors = array();
		if (!key_exists("username", $this->data) || $this->data["username"] === ""){
			$this->errors["username"] = "You must enter a username.";
    }else if (mb_strlen($this->data["username"], 'UTF-8') >= 25){
      $this->errors["username"] = "Username must be under 25 characters.";
    }else if ($this->data["username"] === NULL){
      $this->errors["username"] = "This account doesn't exist.";
    }

    if (!key_exists("password", $this->data) || $this->data["password"] === ""){
    	$this->errors["password"] = "You must enter a password.";
    }
    return count($this->errors) === 0;
  }

  /**
   * Verifies the validity of the data sent by the client,
   * and returns an array of errors to correct.
   * @return boolean true is valid. False if not
   */
  public function isValid() {
    $this->errors = array();
		if (!key_exists("username", $this->data) || $this->data["username"] === ""){
			$this->errors["username"] = "You must enter a username.";
    }else if (mb_strlen($this->data["username"], 'UTF-8') >= 25){
      $this->errors["username"] = "Username must be under 25 characters.";
    }else if ($this->data["username"] === NULL){
      $this->errors["username"] = "This username is already used.";
    }

    if (!key_exists("email", $this->data) || $this->data["email"] === ""){
  		$this->errors["email"] = "You must enter an email.";
    }else if ($this->data["email"] === NULL){
      $this->errors["email"] = "This email is already used.";
    }

    if (!key_exists("password", $this->data) || $this->data["password"] === ""){
    	$this->errors["password"] = "You must enter a password.";
    }

    if (!key_exists("confirmPassword", $this->data) || $this->data["confirmPassword"] === ""){
    	$this->errors["confirmPassword"] = "You must confirm your password.";
    }else if($this->data["password"] !== $this->data["confirmPassword"]){
      $this->errors["confirmPassword"] = "Passwords don't match.";
    }

    if (!key_exists("avatar", $this->avatarData) || $this->avatarData["avatar"]['name'] === ""){
  		$this->avatarData["avatar"]['name'] === "images/users/user.png";
    }else if($this->avatarData["avatar"]['size'] >= 350000){
      $this->errors["avatar"] = "Image size is too big.";
    }else if(!self::checkImageFormat($this->avatarData["avatar"]['type'])) {
      $this->errors["avatar"] = "File is not an image.";
    }
		return count($this->errors) === 0;
	}

  /**
   * Returns the reference of the field representing the name of a user.
   * @return string username
   */
  public function getUserNameRef() {
    return "username";
	}

  /**
   * Returns the reference of the field representing the email of a user.
   * @return string email
   */
  public function getEmailRef() {
    return "email";
	}

  /**
   * Returns the reference of the field representing the name of a user.
   * @return string password
   */
  public function getPasswordRef() {
    return "password";
	}

  /**
   * Returns the reference of the field representing the confirmPassword of a user.
   * @return string confirmPassword
   */
  public function getConfirmPasswordRef() {
		return "confirmPassword";
	}

  /**
   * Returns the reference of the field representing the avatar of a user.
   * @return string avatar
   */
  public function getAvatarRef() {
		return "avatar";
	}

  /**
   * Returns the value of a field based on the reference passed as an argument
   * @param  string $ref
   * @return array value of a field
   */
  public function getData($ref) {
    return key_exists($ref, $this->data)? $this->data[$ref]: '';
	}

  /**
   * Returns the value of a field based on the reference passed as an argument
   * @param  string $ref
   * @return array value of a field
   */
  public function getAvatarData($ref) {
    return key_exists($ref, $this->avatarData)? $this->avatarData[$ref]: '';
	}

  /**
   * returns the errors associated with the field of the reference passed as
   * an argument, or null if there is no error. Need to have called isValid () before.
   * @param  string $ref
   * @return array errors or null
   */
	public function getErrors($ref) {
		return key_exists($ref, $this->errors)? $this->errors[$ref]: null;
	}

  /**
   * Create a new instance of Profile with the data provided.
   * If all are not present, an exception is thrown.
   * @return Profile
   */
  public function createProfile() {
    if (!key_exists("username", $this->data)){
			throw new Exception("Missing fields for profile creation");
    }
    if (!key_exists("email", $this->data)){
  		throw new Exception("Missing fields for profile creation");
    }
    if (!key_exists("password", $this->data)){
    	throw new Exception("Missing fields for profile creation");
    }
    if (!key_exists("confirmPassword", $this->data)){
    	throw new Exception("Missing fields for profile creation");
    }
    if (!key_exists("avatar", $this->avatarData)){
    	throw new Exception("Missing fields for profile creation");
    }
    return new Profile($this->data["username"], $this->data["email"], $this->data["password"], $this->avatarData["avatar"], $this->data["status"]);
	}

  /**
   * Updates an instance of Profile with the data provided
   * @param  Profile $p
   */
  public function updateProfile(Profile $p) {
    if (key_exists("username", $this->data)){
			$p->setName($this->data["username"]);
    }
    if (key_exists("email", $this->data)){
  		$p->setEmail($this->data["email"]);
    }
    if (key_exists("password", $this->data)){
    	$p->setPassword($this->data["password"]);
    }
    if (key_exists("confirmPassword", $this->data)){
    	$p->setPassword($this->data["confirmPassword"]);
    }
	}
}
?>
