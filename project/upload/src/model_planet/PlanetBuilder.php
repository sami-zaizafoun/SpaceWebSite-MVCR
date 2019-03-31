<?php

require_once("model_planet/Planet.php");

/**
 * planet manipulation functions via forms
 */
class PlanetBuilder {
  protected $data;
  protected $imageData;
  protected $error;
  protected $storage;

  /**
   * Creates a new instance, with the data passed as an argument if they exist,
   * and otherwise with the default values of the fields for creating a planet.
   * @param string $data  $_POST data
   * @param string $avatarData $_FILE data
   */
  public function __construct($data=null, $imageData=null) {
    if ($data === null || $imageData === null) {
			$data = array(
				"name" => "",
        "description" => "",
			);
      $imageData = array(
        "image" => "",
      );
		}
		$this->data = $data;
    $this->imageData = $imageData;
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
   * Returns a new instance of PlanetBuilder with the editable data of the planet passed as an argument.
   * @param  Planet $planet
   * @return array instance of PlanetBuilder
   */
  public static function buildFromPlanet(Planet $planet) {
    return new PlanetBuilder(array(
			"name" => $planet->getName(),
      "description" => $planet->getDescription(),
		),
    array(
      "image" => $planet->getImage(),
    ));
	}

  /**
   * Verifies the validity of the data sent by the client,
   * and returns an array of errors to correct.
   * @return boolean true is valid. False if not
   */
  public function isValid() {
    $this->errors = array();
		if (!key_exists("name", $this->data) || $this->data["name"] === ""){
			$this->errors["name"] = "You must enter a planet's name.";
    }else if (mb_strlen($this->data["name"], 'UTF-8') >= 25){
      $this->errors["name"] = "Planet's name must be under 25 characters.";
    }

    if (!key_exists("description", $this->data) || $this->data["description"] === ""){
  		$this->errors["description"] = "You must enter a description.";
    }else if (mb_strlen($this->data["description"], 'UTF-8') >= 1000 ){
      $this->errors["description"] = "Planet's description must be under 1000 characters.";
    }else if (mb_strlen($this->data["description"], 'UTF-8') < 5 ){
      $this->errors["description"] = "Planet's description must be at least 5 characters long.";
    }

    if (!key_exists("image", $this->imageData) || $this->imageData["image"]['name'] === ""){
  		$this->errors["image"] = "You must choose an image for your planet.";
    }else if($this->imageData["image"]['size'] >= 750000){
      $this->errors["image"] = "Image size is too big.";
    }else if(!self::checkImageFormat($this->imageData["image"]['type'])) {
      $this->errors["image"] = "File is not an image.";
    }
		return count($this->errors) === 0;
	}

  /**
   * Returns the reference of the field representing the name of a planet.
   * @return string name
   */
  public function getNameRef() {
    return "name";
	}

  /**
   * Returns the reference of the field representing the description of a planet.
   * @return string description
   */
  public function getDescriptionRef() {
    return "description";
	}

  /**
   * Returns the reference of the field representing the name of a planet.
   * @return string image
   */
  public function getImageRef() {
		return "image";
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
  public function getImageData($ref) {
    return key_exists($ref, $this->imageData)? $this->imageData[$ref]: '';
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
   * Create a new instance of Planet with the data provided.
   * If all are not present, an exception is thrown.
   * @return Planet
   */
  public function createPlanet() {
    if (!key_exists("name", $this->data)){
			throw new Exception("Missing fields for planet creation");
    }
    if (!key_exists("description", $this->data)){
  		throw new Exception("Missing fields for planet creation");
    }
    if (!key_exists("image", $this->imageData)){
    	throw new Exception("Missing fields for planet creation");
    }
    return new Planet($this->data["name"], $this->data["description"], $this->imageData["image"]);
	}

  /**
   * Updates an instance of Planet with the data provided
   * @param  Planet $p
   */
  public function updatePlanet(Planet $p) {
    if (key_exists("name", $this->data)){
			$p->setName($this->data["name"]);
    }
    if (key_exists("description", $this->data)){
  		$p->setDescription($this->data["description"]);
    }
	}
}
?>
