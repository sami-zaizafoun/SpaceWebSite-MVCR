<?php

/**
 * Profile entity
 */
class Profile{

  protected $username;
  protected $email;
  protected $password;
  protected $avatar;
  protected $status;

  /**
   * profile constructor
   * @param string $username
   * @param string $email
   * @param string $password
   * @param string $avatar
   * @param string $status
   */
  public function __construct($username, $email, $password, $avatar, $status){
    if(!self::isUserNameValid($username)){
      throw new Exception("Invalid username");
    }
    $this->username = $username;

    if(!self::isEmailValid($email)){
      throw new Exception("Invalid email");
    }
    $this->email = $email;

    if(!self::isPasswordValid($password)){
      throw new Exception("Invalid password");
    }
    $this->password = $password;
    $this->avatar = $avatar;
    $this->status = $status;
  }

  /**
   * profile username getter
   * @return string profile's username
   */
  public function getUserName(){
    return $this->username;
  }

  /**
   * profile email getter
   * @return string profile's email
   */
  public function getEmail(){
    return $this->email;
  }

  /**
   * profile password getter
   * @return string profile's password
   */
  public function getPassword(){
    return $this->password;
  }

  /**
   * profile avatar getter
   * @return string profile's avatar
   */
  public function getAvatar(){
    return $this->avatar;
  }

  /**
   * profile status getter
   * @return string profile's status
   */
  public function getStatus(){
    return $this->status;
  }

  /**
   * profile username setter
   * @return string profile's username
   */
  public function setName($username){
    if(!self::isUserNameValid($username)){
      throw new Exception("Invalid username");
    }
    $this->username = $username;
  }

  /**
   * profile email setter
   * @return string profile's email
   */
  public function setEmail($email){
    if(!self::isEmailValid($email)){
      throw new Exception("Invalid email");
    }
    $this->email = $email;
  }

  /**
   * profile password setter
   * @return string profile's password
   */
  public function setPassword($password){
    if(!self::isPasswordValid($password)){
      throw new Exception("Invalid password");
    }
    $this->password = $password;
  }

  /**
   * profile username verification
   * @return string profile's username
   */
  public static function isUserNameValid($username){
    return mb_strlen($username, 'UTF-8') < 25 && $username!== "" && $username!== NULL;
  }

  /**
   * profile email verification
   * @return string profile's email
   */
  public static function isEmailValid($email){
    return mb_strlen($email, 'UTF-8') && $email!== "" && $email!== NULL;
  }

  /**
   * profile password verification
   * @return string profile's password
   */
  public static function isPasswordValid($password){
    return mb_strlen($password, 'UTF-8') && $password!== "";
  }
}
?>
