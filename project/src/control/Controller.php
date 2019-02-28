<?php

/*** Contrôleur du site des planetes. ***/

/* Inclusion des classes nécessaires */
require_once("model/Planet.php");
require_once("model/PlanetStorage.php");
require_once("model/PlanetBuilder.php");
require_once("view/MainView.php");

class Controller {

	protected $v;
	protected $planetdb;
	protected $currentPlanetBuilder;
	protected $modifiedPlanetBuilders;

	public function __construct(MainView $view, PlanetStorage $planetdb) {
		$this->v = $view;
		$this->planetdb = $planetdb;
		$this->currentPlanetBuilder = key_exists('currentPlanetBuilder', $_SESSION) ? $_SESSION['currentPlanetBuilder'] : null;
		$this->modifiedPlanetBuilders = key_exists('modifiedPlanetBuilders', $_SESSION) ? $_SESSION['modifiedPlanetBuilders'] : array();
	}

	public function __destruct() {
		$_SESSION['currentPlanetBuilder'] = $this->currentPlanetBuilder;
		$_SESSION['modifiedPlanetBuilders'] = $this->modifiedPlanetBuilders;
	}

	public function signUpPage(){
		$this->v->signUp();
	}

	public function planetPage($id) {
		/* Une planete est demandée, on la récupère en BD */
		$planet = $this->planetdb->read($id);
		if ($planet === null) {
			/* La planete n'existe pas en BD */
			$this->v->makeUnknownPlanetPage();
		} else {
			/* La planete existe, on prépare la page */
			$this->v->makePlanetPage($id, $planet);
		}
	}

	public function allPlanetsPage() {
		$planets = $this->planetdb->readAll();
		$this->v->makeGalleryPage($planets);
	}

	public function newPlanet() {
		/* Affichage du formulaire de création
		* avec les données par défaut. */
		if ($this->currentPlanetBuilder === null) {
			$this->currentPlanetBuilder = new PlanetBuilder();
		}
		$this->v->makePlanetCreationPage($this->currentPlanetBuilder);
	}

	public function saveNewPlanet(array $data) {
		$this->currentPlanetBuilder = new PlanetBuilder($data);
		if ($this->currentPlanetBuilder->isValid()) {

			/* On construit la nouvelle planete */
			$planet = $this->currentPlanetBuilder->createPlanet();
			/* On l'ajoute en BD */
			$planetId = $this->planetdb->create($planet);
			/* On détruit le builder courant */
			$this->currentPlanetBuilder = null;
			/* On redirige vers la page de la nouvelle planete */
			$this->v->makePlanetCreatedPage($planetId);
		} else {
			$this->v->makePlanetNotCreatedPage();
		}
	}

	public function deletePlanet($planetId) {
		/* On récupère la planete en BD */
		$planet = $this->planetdb->read($planetId);
		if ($planet === null) {
			/* La planete n'existe pas en BD */
			$this->v->makeUnknownPlanetPage();
		} else {
			/* La planete existe, on prépare la page */
			$this->v->makePlanetDeletionPage($planetId, $planet);
		}
	}

	public function confirmPlanetDeletion($planetId) {
		/* L'utilisateur confirme vouloir supprimer
		* la planete. On essaie. */
		$ok = $this->planetdb->delete($planetId);
		if (!$ok) {
			/* La planete n'existe pas en BD */
			$this->v->makeUnknownPlanetPage();
		} else {
			/* Tout s'est bien passé */
			$this->v->makePlanetDeletedPage();
		}
	}

	public function modifyPlanet($planetId) {
		if (key_exists($planetId, $this->modifiedPlanetBuilders)) {
			/* Préparation de la page de formulaire */
			$this->v->makePlanetModifPage($planetId, $this->modifiedPlanetBuilders[$planetId]);
		} else {
			/* On récupère en BD la planete à modifier */
			$p = $this->planetdb->read($planetId);
			if ($p === null) {
				$this->v->makeUnknownPlanetPage();
			} else {
				/* Extraction des données modifiables */
				$builder = PlanetBuilder::buildFromPlanet($p);
				/* Préparation de la page de formulaire */
				$this->v->makePlanetModifPage($planetId, $builder);
			}
		}
	}

	public function savePlanetModifications($planetId, array $data) {
		/* On récupère en BD la planete à modifier */
		$planet = $this->planetdb->read($planetId);
		if ($planet === null) {
			/* La planete n'existe pas en BD */
			$this->v->makeUnknownPlanetPage();
		} else {
			$builder = new PlanetBuilder($data);
			/* Validation des données */
			if ($builder->isValid()) {
				/* Modification de la planete */
				$builder->updatePlanet($planet);
				/* On essaie de mettre à jour en BD.
				* Normalement ça devrait marcher (on vient de
				* récupérer la planete). */
				$ok = $this->planetdb->update($planetId, $planet);
				if (!$ok)
					throw new Exception("Identifier has disappeared?!");
				/* Redirection vers la page de la planete */
				unset($this->modifiedPlanetBuilders[$planetId]);
				$this->v->makePlanetModifiedPage($planetId);
			} else {
				$this->modifiedPlanetBuilders[$planetId] = $builder;
				$this->v->makePlanetNotModifiedPage($planetId);
			}
		}
	}

}

?>
