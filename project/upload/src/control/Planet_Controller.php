<?php

require_once("model_planet/Planet.php");
require_once("model_planet/PlanetStorage.php");
require_once("model_planet/PlanetBuilder.php");
require_once("view/MainView.php");

/**
 * Planets controller
 */
class Planet_Controller {

	protected $view;
	protected $db;
	protected $currentPlanetBuilder;
	protected $modifiedPlanetBuilder;

	/**
	 * constructor function
	 * @param MainView       $view MainView instance
	 * @param PlanetStorage $db   database instance
	 */
	public function __construct(MainView $view, PlanetStorage $db) {
		$this->view = $view;
		$this->db = $db;
		$this->currentPlanetBuilder = key_exists('currentPlanetBuilder', $_SESSION) ? $_SESSION['currentPlanetBuilder'] : null;
		$this->modifiedPlanetBuilder = key_exists('modifiedPlanetBuilder', $_SESSION) ? $_SESSION['modifiedPlanetBuilder'] : array();
	}

	/**
	 * destruct function
	 */
	public function __destruct() {
		$_SESSION['currentPlanetBuilder'] = $this->currentPlanetBuilder;
		$_SESSION['modifiedPlanetBuilder'] = $this->modifiedPlanetBuilder;
	}

	/**
	 * get all planets from database
	 */
	public function allPlanetsPage() {
	   $planets = $this->db->readAll();
		$this->view->makeGalleryPage($planets);
	}

	/**
	 * newPlanet creates a new planet builder and redirects to create a planet form
	 */
	public function newPlanet(){
		if($this->currentPlanetBuilder === null){
			$this->currentPlanetBuilder = new PlanetBuilder();
		}
		$this->view->makePlanetCreationPage($this->currentPlanetBuilder);
	}

	/**
	 * save new planet if the information entered is valid, else redirect to form
	 * @param array $data       $_POST data
	 * @param array $imageData $_FILE data
	 */
	public function saveNewPlanet(array $data, array $imageData){
		$this->currentPlanetBuilder = new PlanetBuilder($data, $imageData);

		if($this->currentPlanetBuilder->isValid()){
			$planet = $this->currentPlanetBuilder->createPlanet();
			$PlanetId = $this->db->create($planet);
			$PlanetId = $PlanetId->getName();
			$this->currentPlanetBuilder = null;
			$this->view->makePlanetCreatedPage($PlanetId);
		}else{
			$this->view->makePlanetNotCreatedPage();
		}
	}

	/**
	 * redirect to the chosen planet's page
	 * @param string $planetId
	 */
  public function planetPage($planetId) {
    $planet = $this->db->read($planetId);
		if($planet === null){
			$this->view->makeUnknownPlanetPage();
		}else{
			$result = new Planet($planet['name'],$planet['description'], $planet['image']);
      $user = $planet['username'];
			$this->view->makePlanetPage($planetId, $user, $result);
		}
	}

	/**
	 * create an instance of modify planet builder and redirect to the planet modification form
	 * @param string $planetId
	 */
	public function modifyPlanet($planetId){
		$planet = $this->db->read($planetId);
		if($_SESSION['username'] === $planet['username'] || $_SESSION['status'] === 'admin'){
			if (key_exists($planetId, $this->modifiedPlanetBuilder)){
				$this->view->makeModifyPlanetPage($planetId, $this->modifiedPlanetBuilder[$planetId]);
			}else{
				$result = new Planet($planet['name'], $planet['description'], $planet['image']);
				if ($result === null) {
					$this->view->makeUnknownPlanetPage();
				} else {
					$builder = PlanetBuilder::buildFromPlanet($result);
					$this->view->makeModifyPlanetPage($planetId, $builder);
				}
			}
		}else{
			$this->view->makeUnAuthorisedAccessPage();
		}
	}

	/**
	 * register modified planet if the information entered is valid, else redirect to form
	 * @param string $planetId
	 * @param array $data       $_POST data
	 * @param array $avatarData $_FILE data
	 */
	public function savePlanetModifications($planetId, array $data, array $imageData){
		$planet = $this->db->read($planetId);

		$result = new Planet($planet['name'], $planet['description'], $planet['image']);

		if ($result === null) {
			$this->view->makeUnknownPlanetPage();
		} else {
			$builder = new PlanetBuilder($data, $imageData);
			if ($builder->isValid()) {
				$builder->updatePlanet($result);
				$ok = $this->db->update($planetId, $result);

				if (!$ok){
					throw new Exception("Identifier has disappeared?!");
				}
				$name= $result->getName();
				unset($this->modifiedPlanetBuilder[$name]);
				$this->view->makePlanetModifiedPage($name);
			} else {
				$this->modifiedPlanetBuilder[$planetId] = $builder;
				$this->view->makePlanetNotModifiedPage($planetId);
			}
		}
	}

	/**
	 * delete planet
	 * @param string $planetId
	 */
	public function deletePlanet($planetId){
		$planet = $this->db->read($planetId);
		if($_SESSION['username'] === $planet['username'] || $_SESSION['status'] === 'admin'){
			$ok = $this->db->delete($planetId);
			if (!$ok) {
				$this->view->makeUnknownPlanetPage();
			} else {
				$this->view->makePlanetDeletedPage();
			}
		}
		$this->view->makeUnAuthorisedAccessPage();
	}
}

?>
