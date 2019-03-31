<?php

/**
 * Connet to MariaDB
 */
class DataBase {

	protected $pdo;
	protected $r;

	/**
	 * construct function. Connects to the database
	 */
	public function __construct(){
		define('MYSQL_USER', '21600538');
		define('MYSQL_PASSWORD', 'Iegh3xeePaeJeuQu');
		define('MYSQL_HOST', 'mysql.info.unicaen.fr');
		define('MYSQL_PORT', '3306');
		define('MYSQL_DB', '21600538_dev');

		$this->pdo = new PDO(
			'mysql:host='.MYSQL_HOST.';port='.MYSQL_PORT.';dbname='.MYSQL_DB.';charset=utf8mb4',
			MYSQL_USER, MYSQL_PASSWORD
		);
		$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	/**
	 * Certain characters have special significance in HTML, , and should be represented by HTML entities if they are to preserve their meanings. This function returns a string with these conversions made.
	 * @param string $str string to translate
	 * @return string      translated string
	 */
	function htmlesc($str) {
		return htmlspecialchars($str,
			ENT_QUOTES
			| ENT_SUBSTITUTE
			| ENT_HTML5,
			'UTF-8');
	}

	/**
	 * Get all users from db
	 * @return array users
	 */
	public function getAll(){
		$sql = $this->pdo->prepare("SELECT username, email, avatar FROM users");
		$sql->execute();
		$result = $sql->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}

	/**
	 * inserts a new profile to db
	 * @param  Profile $p instance of profile
	 * @return Profile    instance of new profile
	 */
	public function createProfile($p){
		if(isset($_POST['register'])){
			$username = $_POST['username'];
			$email = $_POST['email'];
			$password = password_hash($_POST['password'], PASSWORD_BCRYPT);
			$status = "user";

			if(empty($_FILES['avatar']['name'])){
				$avatarPath = "images/users/user.png";
			}else{
				$avatarPath = 'images/users/'.$_FILES['avatar']['name'];
				copy($_FILES['avatar']['tmp_name'], $avatarPath);
			}

			$_SESSION['username'] = $username;
			$_SESSION['avatar'] = $avatarPath;

			$sql = $this->pdo->prepare("INSERT INTO users (username, email, password, avatar, status)
			VALUES(:username, :email, :password, :avatarPath, :status)");

			$sql->bindParam(':username', $username);
			$sql->bindParam(':email', $email);
			$sql->bindParam(':password', $password);
			$sql->bindParam(':avatarPath', $avatarPath);
			$sql->bindParam(':status', $status);

			$sql->execute();
			$p = new Profile($username, $email, $password, $avatarPath, $status);
		}
		return $p;
	}

	/**
	 * select a specific profile from db
	 * @param  string $username
	 * @return array  user information
	 */
	public function getProfile($username){
		$sql = $this->pdo->prepare("SELECT * FROM users WHERE username='".$username."';");
		$sql->execute();
		$result = $sql->fetchAll(PDO::FETCH_ASSOC)[0];
		return $result;
	}

	/**
	 * verify if acount exists
	 * @param  string $username
	 * @return boolean true if account exists. False if not
	 */
	public function accountExists($username){
		$sql = $this->pdo->prepare("SELECT * from users WHERE username = '".$username."';");
    $sql->execute();
    $results = $sql->fetch();
		if(!empty($results)){
			return true;
		};
    return false;
	}

	/**
	 * verify if email exists
	 * @param  string $email
	 * @return boolean true if email exists. False if not
	 */
	public function emailExists($email){
		$sql = $this->pdo->prepare("SELECT * from users WHERE email = '".$email."';");
    $sql->execute();
    $results = $sql->fetch();
		if(!empty($results)){
			return true;
		};
    return false;
	}

	/**
	 * select user's password
	 * @param  string $username
	 * @return string  crypted password
	 */
	public function getPassword($username){
		$sql = $this->pdo->prepare("SELECT password FROM users WHERE username='".$username."';");
		$sql->execute();
		$result = $sql->fetch()[0];
		return $result;
	}

	/**
	 * insert updated data to db
	 * @param  string $username
	 * @param  Profile $p old instance of profile
	 * @return Profile updated instance of profile
	 */
	public function updateProfile($username, $p){
		$oldUserName = $_SESSION['username'];
		$oldPath = $_SESSION['avatar'];
		$status = $_SESSION['status'];

		//print_r($oldPath);die();

		if(isset($_POST['modify'])){
			$username = $_POST['username'];
			$email = $_POST['email'];
			$password = password_hash($_POST['password'], PASSWORD_BCRYPT);

			if(empty($_FILES['avatar']['name'])){
				$avatarPath = $oldPath;
			}else{
				$avatarPath = 'images/users/'.$_FILES['avatar']['name'];
				copy($_FILES['avatar']['tmp_name'], $avatarPath);
			}

			$_SESSION['username'] = $username;
			$_SESSION['avatar'] = $avatarPath;

			$sql = $this->pdo->prepare("UPDATE users SET username=:username, email=:email, password=:password, avatar=:avatarPath, status=:status
			WHERE username='".$oldUserName."';");

			$sql->bindParam(':username', $username);
			$sql->bindParam(':email', $email);
			$sql->bindParam(':password', $password);
			$sql->bindParam(':avatarPath', $avatarPath);
			$sql->bindParam(':status', $status);
			$sql->execute();
			$p = new Profile($username, $email, $password, $avatarPath, $status);
		}
		return $p;
	}

	/**
	 * delete user from db
	 * @param  string $username
	 */
	public function deleteProfile($username){
		$sql = $this->pdo->prepare("DELETE FROM users WHERE username='".$username."';");
		$sql->execute();
	}

	/**
	 * inserts a new planet to db
	 * @param  Planet $p instance of planet
	 * @return Planet    instance of new planet
	 */
	public function createPlanet($p){
		if(isset($_POST['register'])){
			$user = $this->getProfile($_SESSION['username']);
			$userID = (int)$user['id'];

			$name = $_POST['name'];
			$description = $_POST['description'];
			$imagePath = 'images/planets/'.$_FILES['image']['name'];
			copy($_FILES['image']['tmp_name'], $imagePath);

			$sql = $this->pdo->prepare("INSERT INTO planets (name, description, image, user_id)
			VALUES(:name, :description, :imagePath, :id)");


			$sql->bindParam(':name', $name);
			$sql->bindParam(':description', $description);
			$sql->bindParam(':imagePath', $imagePath);
			$sql->bindParam(':id', $userID);

			$sql->execute();
			$p = new Planet($name, $description, $imagePath);
		}
		return $p;
	}

	/**
	 * verify if planet exists
	 * @param  string $name
	 * @return boolean true if planet exists. False if not
	 */
	public function planetExists($name){
		$sql = $this->pdo->prepare("SELECT * from planets WHERE name = '".$name."';");
    $sql->execute();
    $results = $sql->fetch();
		if(!empty($results)){
			return true;
		};
    return false;
	}

	/**
	 * Get all planets from db
	 * @return array planets
	 */
	public function getAllPlanets(){
		$sql = $this->pdo->prepare("SELECT username, name, description, image FROM planets INNER JOIN users ON planets.user_id=users.id;");
		$sql->execute();
		$result = $sql->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}

	/**
	 * select all planets from a certain user
	 * @param  string $username
	 * @return array  a user's planets
	 */
	public function getAllUserPlanets($username){
		$sql = $this->pdo->prepare("SELECT name, description, image FROM planets INNER JOIN users ON planets.user_id=users.id WHERE username='".$username."';");
		$sql->execute();
		$result = $sql->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}

	/**
	 * select a specific planet from db
	 * @param  string $name
	 * @return array  planet information
	 */
	public function getPlanet($name){
		$sql = $this->pdo->prepare("SELECT * FROM planets INNER JOIN users ON planets.user_id=users.id WHERE name='".$name."';");
		$sql->execute();
		$result = $sql->fetchAll(PDO::FETCH_ASSOC)[0];
		return $result;
	}

	/**
	 * insert updated data to db
	 * @param  string $name
	 * @param  Planet $p old instance of planet
	 * @return Planet updated instance of planet
	 */
	public function updatePlanet($name, $p){
		$oldPlanetName = $name;

		if(isset($_POST['modify'])){
			$name = $_POST['name'];
			$description = $_POST['description'];
			$imagePath = 'images/planets/'.$_FILES['image']['name'];
			copy($_FILES['image']['tmp_name'], $imagePath);

			$sql = $this->pdo->prepare("UPDATE planets SET name=:name, description=:description, image=:imagePath
			WHERE name='".$oldPlanetName."';");

			$sql->bindParam(':name', $name);
			$sql->bindParam(':description', $description);
			$sql->bindParam(':imagePath', $imagePath);
			$sql->execute();
			$p = new Planet($name, $description, $imagePath);
		}
		return $p;
	}

	/**
	 * delete planet from db
	 * @param  string $name
	 */
	public function deletePlanet($name){
		$sql = $this->pdo->prepare("DELETE FROM planets WHERE name='".$name."';");
		$sql->execute();
	}

	/**
	 * disconnect user from server
	 * @param string $username
	 */
	public function disconnect($username){
		$sql = $this->pdo->prepare("SELECT username FROM users WHERE username='".$username."';");
		$sql->execute();
		if(session_destroy()){
			return true;
		}
		return false;
	}
}
?>
