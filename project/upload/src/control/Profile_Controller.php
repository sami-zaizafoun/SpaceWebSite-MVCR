<?php

require_once("model_profile/Profile.php");
require_once("model_profile/ProfileStorage.php");
require_once("model_profile/ProfileBuilder.php");

require_once("view/MainView.php");

/**
 * Profile controller
 */
class Profile_Controller {

	protected $view;
	protected $db;
	protected $currentProfileBuilder;
	protected $modifiedProfileBuilder;

	/**
	 * constructor function
	 * @param MainView       $view MainView instance
	 * @param ProfileStorage $db   database instance
	 */
	public function __construct(MainView $view, ProfileStorage $db) {
		$this->view = $view;
		$this->db = $db;
		$this->currentProfileBuilder = key_exists('currentProfileBuilder', $_SESSION) ? $_SESSION['currentProfileBuilder'] : null;
		$this->modifiedProfileBuilder = key_exists('modifiedProfileBuilder', $_SESSION) ? $_SESSION['modifiedProfileBuilder'] : array();
	}

	/**
	 * destruct function
	 */
	public function __destruct() {
		$_SESSION['currentProfileBuilder'] = $this->currentProfileBuilder;
		$_SESSION['modifiedProfileBuilder'] = $this->modifiedProfileBuilder;
	}

	/**
	 * manageContent allows admins to manage users or planets
	 * @param string $profileId verify current user
	 */
	public function manageContent($profileId){
		$profile = $this->db->read($profileId);
		if($profile['status'] === 'admin'){
			$users = $this->db->readAll();
			$planets = $this->db->readAllPlanets();
			$this->view->adminPage($users, $planets);
		}else{
			$this->view->makeUnAuthorisedAccessPage();
		}
	}

	/**
	 * newSignIn creates a new profile builder and redirects to sign in form
	 */
	public function newSignIn(){
		if($this->currentProfileBuilder === null){
			$this->currentProfileBuilder = new ProfileBuilder();
		}
		$this->view->makeSignInPage($this->currentProfileBuilder);
	}

	/**
	 * verify login information, if valid, redirect to profile page, else redirect to form
	 * @param array $data       $_POST data
	 * @param array $avatarData $_FILE data
	 */
	public function verifyInfo($data, $avatarData){
		if(!$this->db->exists($data['username'])){
			$data['username'] = NULL;
		}
		$this->currentProfileBuilder = new ProfileBuilder($data, $avatarData);
		if($this->currentProfileBuilder->isLogInValid()){
			$verify = $this->db->verify($data);
			if($verify){
				$_SESSION['username'] = $data['username'];
				$this->view->makeSignInSuccessfullyPage($data['username']);
			}else{
				$this->view->makeUnsuccessfulSingInPage();
			}
		}else{
			$this->view->makeUnsuccessfulSingInPage();
		}
	}

	/**
	 * newAccount creates a new profile builder and redirects to sign up form
	 */
	public function newAccount(){
		if($this->currentProfileBuilder === null){
			$this->currentProfileBuilder = new ProfileBuilder();
		}
		$this->view->makeAccountCreationPage($this->currentProfileBuilder);
	}

	/**
	 * register new account if the information entered is valid, else redirect to form
	 * @param array $data       $_POST data
	 * @param array $avatarData $_FILE data
	 */
	public function saveNewAccount(array $data, array $avatarData){
		if($this->db->exists($data['username'])){
			$data['username'] = NULL;
		}
		if($this->db->emailExists($data['email'])){
			$data['email'] = NULL;
		}
		$this->currentProfileBuilder = new ProfileBuilder($data, $avatarData);

		if($this->currentProfileBuilder->isValid()){
			$profile = $this->currentProfileBuilder->createProfile();
			$profileId = $this->db->create($profile);
			$profileId = $profileId->getUserName();
			$this->currentProfileBuilder = null;
			$this->view->makeAccountCreatedPage($profileId);
		}else{
			$this->view->makeAccountNotCreatedPage();
		}
	}

	/**
	 * redirect to current user's profile page
	 * @param string $profileId
	 */
	public function profilePage($profileId){
		$profile = $this->db->read($profileId);
		$planets = $this->db->readPlanetOwner($profileId);
		if($profile === null){
			$this->view->makeUnknownProfilePage();
		}else{
			$_SESSION['avatar'] = $profile['avatar'];
			$_SESSION['status'] = $profile['status'];
			$result = new Profile($profile['username'], $profile['email'], $profile['password'], $profile['avatar'], $profile['status']);
			$this->view->makeProfilePage($profileId, $result, $planets);
		}
	}

	/**
	 * create an instance of modify profile builder and redirect to the user profile modification form
	 * @param string $profileId
	 */
	public function modifyAccount($profileId){
		if($_SESSION['username'] === $profileId || $_SESSION['status'] === 'admin'){
			if (key_exists($profileId, $this->modifiedProfileBuilder)){
				$this->view->makeModifyProfilePage($profileId, $this->modifiedProfileBuilder[$profileId]);
			}else{
				$profile = $this->db->read($profileId);
				$result = new Profile($profile['username'], $profile['email'], $profile['password'], $profile['avatar'], $profile['status']);
				if ($result === null) {
					$this->view->makeUnknownProfilePage();
				} else {
					$builder = ProfileBuilder::buildFromProfile($result);
					$this->view->makeModifyProfilePage($profileId, $builder);
				}
			}
		}else{
			$this->view->makeUnAuthorisedAccessPage();
		}
	}

	/**
	 * register modified account if the information entered is valid, else redirect to form
	 * @param string $profileId
	 * @param array $data       $_POST data
	 * @param array $avatarData $_FILE data
	 */
	public function saveProfileModifications($profileId, array $data, array $avatarData){
		$profile = $this->db->read($profileId);

		if($this->db->exists($data['username']) && $data['username']!== $_SESSION['username']){
			$data['username'] = NULL;
		}
		if($this->db->emailExists($data['email']) && $data['email']!==$profile['email'] ){
			$data['email'] = NULL;
		}

		$result = new Profile($profile['username'], $profile['email'], $profile['password'], $profile['avatar'], $profile['status']);

		if ($result === null) {
			$this->view->makeUnknownProfilePage();
		} else {
			$builder = new ProfileBuilder($data, $avatarData);
			if ($builder->isValid()) {
				$builder->updateProfile($result);
				$ok = $this->db->update($profileId, $result);

				if (!$ok){
					throw new Exception("Identifier has disappeared?!");
				}
				$username= $result->getUserName();
				unset($this->modifiedProfileBuilder[$username]);
				$this->view->makeProfileModifiedPage($username);
			} else {
				$this->modifiedProfileBuilder[$profileId] = $builder;
				$this->view->makeProfileNotModifiedPage($profileId);
			}
		}
	}

	/**
	 * disconnect current session
	 * @param string $profileId
	 */
	public function disconnectAccount($profileId){
		$profile = $this->db->read($profileId);
		$ok = $this->db->disconnect($profileId);
		if (!$ok) {
			$this->view->makeUnknownProfilePage();
		} else {
			$this->view->makeAccountDisconnectedPage();
		}
	}

	/**
	 * delete profile and content
	 * @param string $profileId
	 */
	public function deleteProfile($profileId){
		$profile = $this->db->read($profileId);
		if($_SESSION['username'] === $profileId || $_SESSION['status']==='admin'){
			$ok = $this->db->delete($profileId);
			if (!$ok) {
				$this->view->makeUnknownProfilePage();
			} else {
				$this->view->makeProfileDeletedPage();
			}
		}
		$this->view->makeUnAuthorisedAccessPage();
	}
}

?>
