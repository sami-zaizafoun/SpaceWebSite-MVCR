<?php

require_once("model/Planet.php");

/* Fonctions de manipulation des planetes via des formulaires */
class PlanetBuilder {

	protected $data;
	protected $errors;

	/* Crée une nouvelle instance, avec les données passées en argument si
 	 * elles existent, et sinon avec
 	 * les valeurs par défaut des champs de création d'une planete. */
	public function __construct($data=null) {
		if ($data === null) {
			$data = array(
				"name" => "",
				"history"=> "",
			);
		}
		$this->data = $data;
		$this->errors = array();
	}

	/* Renvoie une nouvelle instance de PlanetBuilder avec les données
	 * modifiables de la planete passée en argument. */
	public static function buildFromPlanet(Planet $planet) {
		return new PlanetBuilder(array(
			"name" => $planet->getName(),
			"history" => $planet->getHistory(),
			"image" => $planet->getImage(),
		));
	}

	/* Vérifie la validité des données envoyées par le client,
	 * et renvoie un tableau des erreurs à corriger. */
	public function isValid() {
		$this->errors = array();
		if (!key_exists("name", $this->data) || $this->data["name"] === "")
			$this->errors["name"] = "Vous devez entrer un nom";
		if (!key_exists("history", $this->data) || $this->data["history"] === "")
			$this->errors["history"] = "Vous devez entrer une histoire";
		return count($this->errors) === 0;
	}

	/* Renvoie la «référence» du champ représentant le nom d'une planete. */
	public function getNameRef() {
		return "name";
	}

	public function getHistoryRef() {
		return "history";
	}

	public function getImgRef() {
		return "image";
	}

	/* Renvoie la valeur d'un champ en fonction de la référence passée en argument. */
	public function getData($ref) {
		return key_exists($ref, $this->data)? $this->data[$ref]: '';
	}

	/* Renvoie les erreurs associées au champ de la référence passée en argument,
 	 * ou null s'il n'y a pas d'erreur.
 	 * Nécessite d'avoir appelé isValid() auparavant. */
	public function getErrors($ref) {
		return key_exists($ref, $this->errors)? $this->errors[$ref]: null;
	}

	/* Crée une nouvelle instance de Planet avec les données
	 * fournies. Si toutes ne sont pas présentes, une exception
	 * est lancée. */
	public function createPlanet() {
		if (!key_exists("name", $this->data))
			throw new Exception("Missing fields for planet creation");
		if (!key_exists("history", $this->data))
			throw new Exception("Missing fields for planet creation");
		if (!key_exists("image", $this->data))
			throw new Exception("Missing fields for planet creation");
		return new Planet($this->data["name"], $this->data["history"], $this->data["image"]);
	}

	/* Met à jour une instance de Planet avec les données
	 * fournies. */
	public function updatePlanet(Planet $p) {
		if (key_exists("name", $this->data))
			$p->setName($this->data["name"]);
		if (key_exists("history", $this->data))
			$p->setHistory($this->data["history"]);
		if (key_exists("image", $this->data))
			$p->setImage($this->data["image"]);
	}
}

?>
