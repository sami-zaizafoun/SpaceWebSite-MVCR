<?php

/**
 * Planet entity
 */
class Planet{

  protected $name;
  protected $description;
  protected $image;

  /**
   * planet constructor
   * @param string $name
   * @param string $description
   * @param string $image
   */
  public function __construct($name, $description, $image){
    if(!self::isNameValid($name)){
      throw new Exception("Invalid name");
    }
    $this->name = $name;

    if(!self::isDescriptionValid($description)){
      throw new Exception("Invalid description");
    }
    $this->description = $description;
    $this->image = $image;
  }

  /**
   * planet name getter
   * @return string planet's name
   */
  public function getName(){
    return $this->name;
  }

  /**
   * planet description getter
   * @return string planet's description
   */
  public function getDescription(){
    return $this->description;
  }

  /**
   * planet image getter
   * @return string planet's image
   */
  public function getImage(){
    return $this->image;
  }

  /**
   * planet name setter
   * @return string planet's name
   */
  public function setName($name){
    if(!self::isNameValid($name)){
      throw new Exception("Invalid name");
    }
    $this->name = $name;
  }

  /**
   * planet name setter
   * @return string planet's name
   */
  public function setDescription($description){
    if(!self::isDescriptionValid($description)){
      throw new Exception("Invalid description");
    }
    $this->description = $description;
  }

  /**
   * planet name verification
   * @return string planet's name
   */
  public static function isNameValid($name){
    return mb_strlen($name, 'UTF-8') < 25 && $name!== "" && $name!== NULL;
  }

  /**
   * planet description verification
   * @return string planet's description
   */
  public static function isDescriptionValid($description){
    return mb_strlen($description, 'UTF-8') && $description!== "" && $description!== NULL;
  }
}
?>
