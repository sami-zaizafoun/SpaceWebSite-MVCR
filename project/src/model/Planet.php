<?php

/* Représente une planete. */
class Planet {

	protected $name;
	protected $history;
	protected $image;
	protected $creationDate;
	protected $modifDate;

	/* Construit une planete. Si les paramètres de date ne sont pas passés,
	 * la planete est considérée comme étant toute nouvelle. */
	public function __construct($name, $history, $image, $creationDate=null, $modifDate=null) {
		if (!self::isNameValid($name))
			throw new Exception("Invalid planet name");
		$this->name = $name;
		if (!self::isHistoryValid($history))
			throw new Exception("Invalid history");
		$this->history = $history;
		if (!self::isImageValid($image))
			throw new Exception("Invalid Image");
		$this->image = $image;
		$this->creationDate = $creationDate !== null? $creationDate: new DateTime();
		$this->modifDate = $modifDate !== null? $modifDate: new DateTime();
	}

	public function getName() {
		return $this->name;
	}

	public function getHistory() {
		return $this->history;
	}

	public function getImage() {
		return $this->image;
	}

	/* Renvoie un objet DateTime correspondant à
	 * la création de la planete. */
	public function getCreationDate() {
		return $this->creationDate;
	}

	/* Renvoie un objet DateTime correspondant à
	 * la dernière modification de la planete. */
	public function getModifDate() {
		return $this->modifDate;
	}

	/* Modifie le nom de la planete. Le nouveau nom doit
	 * être valide au sens de isNameValid, sinon
	 * une exception est levée. */
	public function setName($name) {
		if (!self::isNameValid($name))
			throw new Exception("Invalid planet name");
		$this->name = $name;
		$this->modifDate = new DateTime();
	}

	public function setHistory($history) {
		if (!self::isNameValid($history))
			throw new Exception("Invalid planet name");
		$this->history = $history;
		$this->modifDate = new DateTime();
	}

	public function setImage($image) {
		if (!self::isNameValid($image))
			throw new Exception("Invalid planet name");
		$this->image = $image;
		$this->modifDate = new DateTime();
	}

	/* Indique si $name est un nom valide pour une planete.
	 * Il doit faire moins de 30 caractères,
	 * et ne pas être vide. */
	public static function isNameValid($name) {
		return mb_strlen($name, 'UTF-8') < 30 && $name !== "";
	}

	public static function isHistoryValid($history) {
		return mb_strlen($history, 'UTF-8') && $history !== "";
	}

	public static function isImageValid($image) {
		$image = getimagesize($image);
		if (($image > 2000000)) {
			$response = array(
					"type" => "error",
					"message" => "Image size exceeds 2MB"
			);

			return $response;
		}    // Validate image file dimension
		else if ($width > "300" || $height > "200") {
			$response = array(
					"type" => "error",
					"message" => "Image dimension should be within 300X200"
			);
			return $response;
		}else{
			return $image;
		}
		return $image;
	}
}

?>
