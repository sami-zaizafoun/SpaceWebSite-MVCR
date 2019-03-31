<?php

require_once("view/MainView.php");
require_once("lib/DataBase.php");
require_once("control/Profile_Controller.php");
require_once("control/Planet_Controller.php");

/**
 * Router of out site
 */
class Router {

	/**
	 * Constructor method
	 * @param ProfileStorage $profileDB accounts database
	 * @param PlanetStorage  $planetDB  planets database
	 */
	public function __construct(ProfileStorage $profileDB, PlanetStorage $planetDB) {
		$this->profileDB = $profileDB;
		$this->planetDB = $planetDB;
	}

	public function main() {
		session_start();

		$feedback = key_exists('feedback', $_SESSION) ? $_SESSION['feedback'] : '';
		$_SESSION['feedback'] = '';

		$view = new MainView($this, $feedback);
		$profileCtl = new Profile_Controller($view, $this->profileDB);
		$planetCtl = new Planet_Controller($view, $this->planetDB);

		/* URL analysis*/
		$profileId = key_exists('profile', $_GET) ? $_GET['profile'] : null;
		$planetId = key_exists('planet', $_GET) ? $_GET['planet'] : null;
		$action = key_exists('action', $_GET) ? $_GET['action'] : null;
		if ($action === null) {
			$action = ($profileId === null) ? "home" : "see";
		}
		/* URL direction*/
		try {
			/* Authorised access for connected users*/
			if(isset($_SESSION['username'])){
				switch ($action) {
					case "see":
						if ($profileId === null) {
							$view->makeUnknownActionPage();
						} else {
							$profileCtl->profilePage($profileId);
						}
						break;

					case "home":
						$view->makeHomePage();
						break;

					case "manageContent":
						$profileCtl->manageContent($_SESSION['username']);
						break;

					case "signIn":
						$profileCtl->newSignIn();
						break;

					case "verify":
							if(!empty($_POST['username'])){
								$profileCtl->verifyInfo($_POST, "");
							}else{
								$view->makeUnsuccessfulSingInPage();
							}
							break;

					case "createAccount":
						$profileCtl->newAccount();
						break;

					case "register":
						$profileId = $profileCtl->saveNewAccount($_POST,$_FILES);
						break;

					case "modify":
						if ($profileId === null) {
							$view->makeUnknownProfilePage();
						} else {
							$profileCtl->modifyAccount($profileId);
						}
						break;

					case "saveModifications":
						if ($profileId === null) {
							$view->makeUnknownActionPage();
						} else {
							$profileCtl->saveProfileModifications($profileId, $_POST, $_FILES);
						}
						break;

					case "delete_profile":
						if ($profileId === null) {
							$view->makeUnknownProfilePage();
						} else {
							$profileCtl->deleteProfile($profileId);
						}
						break;

					case "viewPlanet":
						$planetCtl->planetPage($planetId);
						break;

					case "createPlanet":
						$planetCtl->newPlanet();
						break;

					case "gallery":
						$planetCtl->allPlanetsPage();
						break;

					case "save_planet":
						$planetId = $planetCtl->saveNewPlanet($_POST,$_FILES);
						break;

					case "modify_planet":
						if ($planetId === null) {
							$view->makeUnknownPlanetPage();
						} else {
							$planetCtl->modifyPlanet($planetId);
						}
						break;

					case "save_planetModifications":
						if ($planetId === null) {
							$view->makeUnknownActionPage();
						} else {
							$planetCtl->savePlanetModifications($planetId, $_POST, $_FILES);
						}
						break;
						break;

					case "delete_planet":
						if ($planetId === null) {
							$view->makeUnknownPlanetPage();
						} else {
							$planetCtl->deletePlanet($planetId);
						}
						break;

					case "disconnect":
						if ($profileId === null) {
							$view->makeUnknownProfilePage();
						} else {
							$profileCtl->disconnectAccount($profileId);
						}
						break;

					case "about":
						$view->makeAboutPage();
						break;

					default:
						/* L'internaute a demandé une action non prévue. */
						$view->makeUnknownActionPage();
						break;
				}
			}
			/* Authorised access for anonymous users*/
			else{
				switch ($action) {
					case "home":
						$view->makeHomePage();
						break;

					case "signIn":
						$profileCtl->newSignIn();
						break;

					case "verify":
							if(!empty($_POST['username'])){
								$profileCtl->verifyInfo($_POST, "");
							}else{
								$view->makeUnsuccessfulSingInPage();
							}
							break;

					case "createAccount":
						$profileCtl->newAccount();
						break;

					case "register":
						$profileId = $profileCtl->saveNewAccount($_POST,$_FILES);
						break;

					case "gallery":
						$planetCtl->allPlanetsPage();
						break;

					case "about":
						$view->makeAboutPage();
						break;

					default:
						$view->makeUnAllowedActionPage();
						break;
				}
			}
		} catch (Exception $e) {
			$view->makeUnexpectedErrorPage($e);
		}
		/* Preview wanted page */
		$view->render();
	}

	/**
	 * homePage url
	 * @return string home url
	 */
	public function homePage() {
		return ".";
	}

	/**
	 * adminPage url
	 * @return string admin control url
	 */
	public function adminPage(){
		return ".?action=manageContent";
	}

	/**
	 * signInPage url
	 * @return string sign in form url
	 */
	public function signInPage() {
		return ".?action=signIn";
	}

	/**
	 * signInToProfile url
	 * @return string sign in confirmed url
	 */
	public function signInToProfile(){
		return ".?action=verify";
	}

	/**
	 * signUpPage url
	 * @return string sign up form url
	 */
	public function signUpPage() {
		return ".?action=createAccount";
	}

	/**
	 * saveCreatedProfile url
	 * @return string sign up confirmed
	 */
	public function saveCreatedProfile() {
		return ".?action=register";
	}

	/**
	 * profilePage url
	 * @return string profile page url
	 */
	public function profilePage($profileId) {
		return ".?profile=$profileId";
	}

	/**
	 * profileModifyPage url
	 * @return string profile modification form url
	 */
	public function profileModifyPage($profileId) {
		return ".?profile=$profileId&amp;action=modify";
	}

	/**
	 * updateModifiedProfile url
	 * @return string profile modification confirmed url
	 */
	public function updateModifiedProfile($profileId){
		return ".?profile=$profileId&amp;action=saveModifications";
	}

	/**
	 * profileDisconnectPage url
	 * @return string disconnect profile url
	 */
	public function profileDisconnectPage($profileId) {
		return ".?profile=$profileId&amp;action=disconnect";
	}

	/**
	 * profileDeletionPage url
	 * @return string delete profile url
	 */
	public function profileDeletionPage($profileId){
		return ".?profile=$profileId&amp;action=delete_profile";
	}

	/**
	 * allPlanetsPage url
	 * @return string gallery url
	 */
	public function allPlanetsPage(){
		return ".?action=gallery";
	}

	/**
	 * createPlanetPage url
	 * @return string create planet form url
	 */
	public function createPlanetPage() {
		return ".?action=createPlanet";
	}

	/**
	 * saveCreatedPlanet url
	 * @return string create planet confirmed url
	 */
	public function saveCreatedPlanet() {
		return ".?action=save_planet";
	}

	/**
	 * planetPage url
	 * @return string planet page url
	 */
	public function planetPage($planetId) {
		return ".?action=viewPlanet&amp;planet=$planetId";
	}

	/**
	 * planetModifyPage url
	 * @return string modify planet form url
	 */
	public function planetModifyPage($planetId) {
		return ".?planet=$planetId&amp;action=modify_planet";
	}

	/**
	 * updateModifiedPlanet url
	 * @return string modify planet confirmed url
	 */
	public function updateModifiedPlanet($planetId){
		return ".?planet=$planetId&amp;action=save_planetModifications";
	}

	/**
	 * planetDeletionPage url
	 * @return string delete planet url
	 */
	public function planetDeletionPage($planetId) {
		return ".?planet=$planetId&amp;action=delete_planet";
	}

	/**
	 * about page url
	 * @return string about url
	 */
	public function aboutPage(){
		return ".?action=about";
	}

	/**
	 * POST-redirect-GET function
	 * @param string $url      redirection link
	 * @param string $feedback redirection message
	 */
	public function POSTredirect($url, $feedback) {
		$_SESSION['feedback'] = $feedback;
		header("Location: ".htmlspecialchars_decode($url), true, 303);
		die;
	}
}

?>
