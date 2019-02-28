<?php

require_once("model/PlanetStorage.php");
require_once("view/MainView.php");
require_once("control/Controller.php");


class Router {

	public function __construct(PlanetStorage $planetdb) {
		$this->planetdb = $planetdb;
	}

	public function main() {
		session_start();

		$feedback = key_exists('feedback', $_SESSION) ? $_SESSION['feedback'] : '';
		$_SESSION['feedback'] = '';

		$view = new MainView($this, $feedback);
		$ctl = new Controller($view, $this->planetdb);

		/* Analyse de l'URL */
		$planetId = key_exists('planet', $_GET) ? $_GET['planet'] : null;
		$action = key_exists('action', $_GET) ? $_GET['action'] : null;
		if ($action === null) {
			/* Pas d'action demandée : par défaut on affiche
	 	 	 * la page d'accueil, sauf si une planete est demandée,
	 	 	 * auquel cas on affiche sa page. */
			$action = ($planetId === null) ? "home" : "see";
		}

		try {
			switch ($action) {
			case "see":
				if ($planetId === null) {
					$view->makeUnknownActionPage();
				} else {
					$ctl->planetPage($planetId);
				}
				break;

			case "createAccount":
				$ctl->signUpPage();
				break;

			case "createPlanet":
				$ctl->newPlanet();
				break;

			case "saveNewPlanet":
				$planetId = $ctl->saveNewPlanet($_POST);
				break;

			case "delete":
				if ($planetId === null) {
					$view->makeUnknownActionPage();
				} else {
					$ctl->deletePlanet($planetId);
				}
				break;

			case "confirmDeletion":
				if ($planetId === null) {
					$view->makeUnknownActionPage();
				} else {
					$ctl->confirmPlanetDeletion($planetId);
				}
				break;

			case "modify":
				if ($planetId === null) {
					$view->makeUnknownActionPage();
				} else {
					$ctl->modifyPlanet($planetId);
				}
				break;

			case "saveModifications":
				if ($planetId === null) {
					$view->makeUnknownActionPage();
				} else {
					$ctl->savePlanetModifications($planetId, $_POST);
				}
				break;

			case "gallery":
				$ctl->allPlanetsPage();
				break;

			case "home":
				$view->makeHomePage();
				break;

			default:
				/* L'internaute a demandé une action non prévue. */
				$view->makeUnknownActionPage();
				break;
			}
		} catch (Exception $e) {
			/* Si on arrive ici, il s'est passé quelque chose d'imprévu
	 	 	 * (par exemple un problème de base de données) */
			$view->makeUnexpectedErrorPage($e);
		}

		/* Enfin, on affiche la page préparée */
		$view->render();
	}

	/* URL de la page d'accueil */
	public function homePage() {
		return ".";
	}

	public function signUpPage() {
		return ".?action=createAccount";
	}

	/* URL de la page de la planete d'identifiant $id */
	public function planetPage($id) {
		return ".?planet=$id";
	}

	/* URL de la page avec toutes les planetes */
	public function allPlanetsPage() {
		return ".?action=gallery";
	}

	/* URL de la page de création d'une planete */
	public function planetCreationPage() {
		return ".?action=createPlanet";
	}

	/* URL d'enregistrement d'une nouvelle planete
	 * (champ 'action' du formulaire) */
	public function saveCreatedPlanet() {
		return ".?action=saveNewPlanet";
	}

	/* URL de la page d'édition d'une planete existante */
	public function planetModifPage($id) {
		return ".?planet=$id&amp;action=modify";
	}

	/* URL d'enregistrement des modifications sur une
	 * planete (champ 'action' du formulaire) */
	public function updateModifiedPlanet($id) {
		return ".?planet=$id&amp;action=saveModifications";
	}

	/* URL de la page demandant la confirmation
	 * de la suppression d'une planete */
	public function planetDeletionPage($id) {
		return ".?planet=$id&amp;action=delete";
	}

	/* URL de suppression effective d'une planete
	 * (champ 'action' du formulaire) */
	public function confirmPlanetDeletion($id) {
		return ".?planet=$id&amp;action=confirmDeletion";
	}

	/* Fonction pour le POST-redirect-GET,
 	 * destinée à prendre des URL du routeur
 	 * (dont il faut décoder les entités HTML) */
	public function POSTredirect($url, $feedback) {
		$_SESSION['feedback'] = $feedback;
		header("Location: ".htmlspecialchars_decode($url), true, 303);
		die;
	}
}

?>
