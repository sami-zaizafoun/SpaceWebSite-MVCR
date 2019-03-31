<?php

require_once("Router.php");

/**
 * Main view
 */
class MainView {

	protected $router;
	protected $style;
	protected $adminStyle;
	protected $title;
	protected $content;

	public function __construct(Router $router, $feedback) {
		$this->router = $router;
		$this->feedback = $feedback;
		$this->style = "";
		$this->adminStyle = "";
		$this->title = null;
		$this->content = null;
	}

	/******************************************************************************/
	/* Methods for generating pages					                                      */
	/******************************************************************************/

	public function makeHomePage() {
		$this->style = "home_page.css";
		$this->title = "Welcome God. The universe is in your hands. Do what you must!";
		$s = "";
		$s .="<p id='intro'> This is our solar system. Everything in here, you can not touch or you will erase our existence.
		But you can always have fun making your own planet in our <br> '<a id= 'creatRef' href='";
		$s .= $this->router->createPlanetPage() . "'>Create your own planet</a>' page.</p>\n";
		$s .= file_get_contents("content/home.txt");
		$this->content = $s;
	}

	public function adminPage($users, $planets){
		$this->title = "Manage content!";
		$this->style .= "adminPage.css";
		$s = "";
		$s .= $this->userContent($users, $planets);
		$this->content = $s;
	}

	public function makeSignInPage(ProfileBuilder $profile_builder){
		$this->title = "Go to your universe";
		$this->style = "form.css";
		$s = "";
		$s .= "<div class='signIn'>\n";
		$s .= "<form class='form' action='" . $this->router->signInToProfile() ."' method='POST' enctype= 'multipart/form-data'>\n";
		$s .= self::getSignInFormFields($profile_builder);
		$s .= "<input class='submitBtn' type='submit' value='Sign In' name='register'>\n";
		$s .= "</form></div>\n";
		$s .= "<div>\n";
		$s .= "<p><i>Don't have an account? <a id='signUp' href='";
		$s .= $this->router->signUpPage()."'>Sign up now</a>!</i></p>\n";
		$s .= "</div>\n";
		$this->content= $s;
	}

	public function makeSignInSuccessfullyPage($profileId) {
		$this->router->POSTredirect($this->router->ProfilePage($profileId), "You have successfully signed in!");
	}

	public function makeUnsuccessfulSingInPage() {
		$this->router->POSTredirect($this->router->signInPage(), "Invalid information!");
	}

	public function makeAccountDisconnectedPage() {
		$this->router->POSTredirect($this->router->homePage(), "You have successfully disconnected!");
	}

	public function makeAccountCreationPage(ProfileBuilder $profile_builder){
		$this->title = "Become a God";
		$this->style = "form.css";
		$s = "";
		$s .= "<div class='signUp'>\n";
		$s .= "<form class='form' action='" . $this->router->saveCreatedProfile() ."' method='POST' enctype= 'multipart/form-data'>\n";
		$s .= self::getSignUpFormFields($profile_builder);
		$s .= "<input class='submitBtn' type='submit' value='Register' name='register'>\n";
		$s .= "</form></div>\n";
		$s .= "<div>\n";
		$s .= "<p><i>Already have an account? <a id='signIn' href='";
		$s .= $this->router->signInPage()."'>Sign In</a>.</i></p>\n";
		$s .= "</div>\n";
		$this->content= $s;
	}

	public function makeAccountCreatedPage($profileId) {
		$this->router->POSTredirect($this->router->ProfilePage($profileId), "You have successfully registered!");
	}

	public function makeAccountNotCreatedPage() {
		$this->router->POSTredirect($this->router->signUpPage(), "Your profile was not created!");
	}

	public function makeModifyProfilePage($profileId, ProfileBuilder $profile_builder){
		$this->title = "Modify your profile";
		$this->style = "form.css";
		$s = "";
		$s .= "<div class='modify'>\n";
		$s .= "<form class='form' action='" . $this->router->updateModifiedProfile($profileId) . "' method='POST' enctype= 'multipart/form-data'>\n";
		$s .= self::getSignUpFormFields($profile_builder);
		$s .= "<input class='submitBtn' type='submit' value='Modify' name='modify'>\n";
		$s .= "</form></div>\n";
		$this->content= $s;
	}

	public function makeProfileModifiedPage($profileId){
		$this->router->POSTredirect($this->router->profilePage($profileId), "Your profile has been successfully modified!");
	}

	public function makeProfileNotModifiedPage($profileId){
		$this->router->POSTredirect($this->router->profileModifyPage($profileId), "Failed to modify profile.");
	}

	public function makeProfilePage($profileId, Profile $p, array $planets){
		$username = self::htmlesc($p->getUserName());
		$email = self::htmlesc($p->getEmail());
		$avatar = self::htmlesc($p->getAvatar());

		$this->style = "profile_page.css";
		$this->title = "Welcome $username to your universe";
		$s = "";
		$s = "<div class = 'main'>\n";
		$s .= "<div class = 'info'>\n";
		$s .= "<img class='avatar' src='". $avatar ."' alt='avatar'>\n";
		$s .= "<h2 class = 'name'> <b>". $username ."</b></h2>\n";
		$s .= "<p class = 'email'> Email : ". $email ."</p>\n";
		if($_SESSION['username'] === $username){
			$s .= "<aside class = 'profile_options'>\n";
			$s .= "<ul class = 'profile_options_list'>\n";
			$s .= '<li><a href="'.$this->router->profileModifyPage($profileId).'">Edit profile</a></li>'."\n";
			$s .= '<li><a href="'.$this->router->profileDisconnectPage($profileId).'">Disconnect</a></li>'."\n";
			$s .= "</ul>\n";
			$s .= "</aside>\n";
		}
		$s .= "</div>\n";
		$s .= "<div class = 'creations'>\n";
		if($_SESSION['username'] === $username){
			$s .= "<h2>Your creations : </h2>\n";
		}else{
			$s .= "<h2>". $username ."'s' creations : </h2>\n";
		}
		$s .= "<ul class='gallery'>\n";
		foreach ($planets as $id=>$planet) {

			$s .= $this->galleryPlanet($planet);
		}
		$s .= "</ul>\n";
		$s .= "</div>\n";
		$s .= "</div>\n";
		$this->content .= $s;
	}

	public function makeProfileDeletedPage() {
		$this->router->POSTredirect($this->router->homePage(), "Profile has been deleted!");
	}

	public function makeGalleryPage(array $planets) {
		$this->title = "Know your universe!";
		$this->style .= "gallery.css";
		$s = "";

		$s .= "<ul class='gallery'>\n";
		foreach ($planets as $id=>$planet) {
			$s .= $this->galleryPlanet($planet);
		}
		$s .= "</ul>\n";
		$this->content = $s;
	}

	public function makePlanetCreationPage(PlanetBuilder $planet_builder){
		$this->title = "Create your planet";
		$this->style = "form.css";
		$s = "";
		$s .= "<div class='planet_form'>\n";
		$s .= "<form class='form' action='" . $this->router->saveCreatedPlanet() ."' method='POST' enctype= 'multipart/form-data'>\n";
		$s .= self::getPlanetCreationFormFields($planet_builder);
		$s .= "<input class='submitBtn' type='submit' value='Create' name='register'>\n";
		$s .= "</form></div>\n";
		$this->content= $s;
	}

	public function makePlanetCreatedPage($planetId) {
		$this->router->POSTredirect($this->router->planetPage($planetId), "You have successfully created your planet!");
	}

	public function makePlanetNotCreatedPage() {
		$this->router->POSTredirect($this->router->createPlanetPage(), "Your planet was not created!");
	}

	public function makeModifyPlanetPage($planetId, PlanetBuilder $planet_builder){
		$this->title = "Modify " . $planetId;
		$this->style = "form.css";
		$s = "";
		$s .= "<div class='modify'>\n";
		$s .= "<form class='form' action='" . $this->router->updateModifiedPlanet($planetId) . "' method='POST' enctype= 'multipart/form-data'>\n";
		$s .= self::getPlanetCreationFormFields($planet_builder);
		$s .= "<input class='submitBtn' type='submit' value='Modify' name='modify'>\n";
		$s .= "</form></div>\n";
		$this->content= $s;
	}

	public function makePlanetModifiedPage($planetId){
		$this->router->POSTredirect($this->router->planetPage($planetId), "Your creation has been successfully modified!");
	}

	public function makePlanetNotModifiedPage($planetId){
		$this->router->POSTredirect($this->router->planetModifyPage($planetId), "Failed to modify your creation.");
	}

	public function makePlanetPage($planetId, $user, Planet $planet){
		$name = self::htmlesc($planet->getName());
		$description = self::htmlesc($planet->getDescription());
		$image = self::htmlesc($planet->getImage());

		$this->style = "planet_page.css";
		$this->title = "$name's page";
		$s = "";
		$s = "<div class = 'main'>\n";
		$s .= "<div class = 'info'>\n";
		$s .= "<img class='image' src='". $image ."' alt='avatar'>\n";
		$s .= "<h2 class = 'name'> <b>". $name ."</b></h2>\n";
		$s .= "<p class = 'description'>". $description ."</p>\n";
		$s .= "<p class = 'username'> <b>Created by:</b> <a class = 'username' href='" .$this->router->profilePage($user)."'> " . $user ."</a></p>\n";
		$s .= "</div>\n";

		if($_SESSION['username'] === $user){
			$s .= "<div class = 'planet_options'>\n";
			$s .= "<ul class = 'planet_options_list'>\n";
			$s .= '<li><a href="'.$this->router->planetModifyPage($planetId).'">Edit planet</a></li>'."\n";
			$s .= '<li><a href="'.$this->router->planetDeletionPage($planetId).'">Destroy planet</a></li>'."\n";
			$s .= "</ul>\n";
			$s .= "</div>\n";
		}

		$s .= "</div>\n";
		$this->content .= $s;
	}

	public function makePlanetDeletedPage() {
		$this->router->POSTredirect($this->router->allPlanetsPage(), "This planet was destroyed!");
	}

	public function makeUnAllowedActionPage(){
		$this->router->POSTredirect($this->router->signInPage(), "You must sign in to view this page");
	}

	public function makeAboutPage(){
		$this->title = "About this website";
		$this->style = "about.css";
		$s = "";
		$s .= "<div class='about'>";
		$s .= "<h2> Why?</h2>";
		$s .= "<p> This website was created by <u>21600538</u>. The reason why I chose this theme is my love for astronomy. Space is vast, and it's nearly impossible for us to
							dicover even a fraction of it during our time.. So with this web application, I've given anyone who decides to join us the possibilty to create their own universe
							or explore a universe created by another user.</p>";
		$s .= "<p><b>PS: Funny images are hiddes within wrong URLs.</b></p>";
		$s .= "</div>";

		$s .= "<div class='extra'>";
		$s .= "<h2> Extra features</h2>";
		$s .= "<p> I used my knowledge in JavaScript to create a tab like feature for admin monitoring. And to create a function to take us back to the top of the page after scrolling.
						Pagination was the next step but because of lack of time, this feature will have to wait...</p>";
		$s .= "</div>";
		$this->content= $s;
	}


	/******************************************************************************/
	/* Utility methods		                                                        */
	/******************************************************************************/

	protected function getMenu() {
		if(key_exists("username", $_SESSION)){
			$profileId =	$_SESSION['username'];
			if($_SESSION['status'] === 'admin'){
				$this->style = "menuAdmin.css";
				return array(
					"Home" => $this->router->homePage(),
					"Planet library" => $this->router->allPlanetsPage(),
					"Create your own planet" => $this->router->createPlanetPage(),
					"Manage users" => $this->router->adminPage(),
					"About" => $this->router->aboutPage(),
					$profileId	=> $this->router->profilePage($profileId),
				);
			} else{
				return array(
					"Home" => $this->router->homePage(),
					"Planet library" => $this->router->allPlanetsPage(),
					"Create your own planet" => $this->router->createPlanetPage(),
					"About" => $this->router->aboutPage(),
					$profileId => $this->router->profilePage($profileId),
				);
			}
		}else{
			return array(
				"Home" => $this->router->homePage(),
				"Planet library" => $this->router->allPlanetsPage(),
				"Create your own planet" => $this->router->createPlanetPage(),
				"About" => $this->router->aboutPage(),
				"Sign In" => $this->router->signInPage(),
			);
		}
	}

	protected function userContent($users, $planets) {
		$s = "";
		$userKeys = array_keys(array_values($users)[0]);
		$planetKeys = array_keys(array_values($planets)[0]);

		$s .= "<div class='tab'>";
		$s .= "<button id=\"default\" class='tablinks' onclick='openTab(event, \"Users\")'> Users </button>";
		$s .= "<button class='tablinks' onclick='openTab(event, \"Planets\")'> Planets </button>";
		$s .= "</div>";

		$s .= "<div id='Users' class='tabcontent'>";
		$s .= "<table class='admin_table'>";
		$s .= "<thead>";
		$s .="<tr>";
		$s .= "<th>" . $userKeys[0] . "</th>";
		$s .= "<th>" . $userKeys[1] . "</th>";
		$s .= "<th>" . $userKeys[2] . "</th>";
		$s .= "<th> Options </th>";
    $s .= "</tr>";
		$s .= "</thead>";
		$s .= "<tbody>";
		foreach ($users as $key => $value) {
			$s .= "<tr>";
      $s .= "<td>". $value['username'] ."</td>";
      $s .= "<td>". $value['email'] ."</td>";
			$s .= "<td>". $value['avatar'] ."</td>";
			$s .= "<td>";
			$s .= "<ul class = 'profile_options_list'>\n";
			$s .= '<li><a href="">Edit profile</a></li>'."\n";
			$s .= '<li><a href="'.$this->router->profileDeletionPage($value['username']).'">Delete</a></li>'."\n";
			$s .= "</ul>\n";
			$s .= "</td>";
    	$s .= "</tr>";
		}
		$s .= "</tbody>";
		$s .= "</table>";
		$s .= "</div>";

		$s .= "<div id='Planets' class='tabcontent'>";
		$s .= "<table class='admin_table'>";
		$s .= "<thead>";
		$s .="<tr>";
		$s .= "<th>" . $planetKeys[0] . "</th>";
		$s .= "<th>" . $planetKeys[1] . "</th>";
		$s .= "<th>" . $planetKeys[2] . "</th>";
		$s .= "<th>" . $planetKeys[3] . "</th>";
		$s .= "<th> Options </th>";
    $s .= "</tr>";
		$s .= "</thead>";
		$s .= "<tbody>";
		foreach ($planets as $key => $value) {
			$s .= "<tr>";
			$s .= "<td>". $value['username'] ."</td>";
      $s .= "<td>". $value['name'] ."</td>";
      $s .= "<td>". $value['description'] ."</td>";
			$s .= "<td>". $value['image'] ."</td>";
			$s .= "<td>";
			$s .= "<ul class = 'profile_options_list'>\n";
			$s .= '<li><a href="'.$this->router->planetModifyPage($value['name']).'">Edit planet</a></li>'."\n";
			$s .= '<li><a href="'.$this->router->planetDeletionPage($value['name']).'">Delete</a></li>'."\n";
			$s .= "</ul>\n";
			$s .= "</td>";
    	$s .= "</tr>";
		}
		$s .= "</tbody>";
		$s .= "</table>";
		$s .= "</div>";
		return $s;
	}

	protected function galleryPlanet($planet) {
		$s = "";
		$s .= "<li class='planet_item'><a href='";
		$s .= $this->router->planetPage($planet['name']);
		$s .= "'>";
		$s .= "<figure class='planet_figure'>";
		$s .= "<img class='planet_img' src='" .$planet['image'] . "' alt=' ". $planet['name'] . "' >";
		$s .= "<figcaption class='planet_name'>" . $planet['name'] . "</figcaption>";
		$s .= '</figure></a>';
		$s .= '</li>'."\n";
		return $s;
	}

	protected function getSignUpFormFields(ProfileBuilder $profile_builder){
		$s =  "";

		$usernameRef = $profile_builder->getUserNameRef();
		$s .= "<p><label>Select your username: </label><input type='text' placeholder='Username' name='".$usernameRef."' value='";
		$s .= self::htmlesc($profile_builder->getData($usernameRef));
		$s .= "' />";
		$err = $profile_builder->getErrors($usernameRef);
		if ($err !== null){
			$s .= '<br><span class="error">'.$err.'</span>';
		}
		$s .="</p>\n";

		$emailRef = $profile_builder->getEmailRef();
		$s .= "<p><label>Enter your email: </label><input type='email' placeholder='Email' name='".$emailRef."' value='";
		$s .= self::htmlesc($profile_builder->getData($emailRef));
		$s .= "' />";
		$err = $profile_builder->getErrors($emailRef);
		if ($err !== null){
			$s .= '<br><span class="error">'.$err.'</span>';
		}
		$s .="</p>\n";

		$passwordRef = $profile_builder->getPasswordRef();
		$s .= "<p><label>Select your password: </label><input type='password' placeholder='Password' name='".$passwordRef."' value='";
		$s .= self::htmlesc($profile_builder->getData($passwordRef));
		$s .= "' />";
		$err = $profile_builder->getErrors($passwordRef);
		if ($err !== null){
			$s .= '<br><span class="error">'.$err.'</span>';
		}
		$s .="</p>\n";

		$passwordConfirmRef = $profile_builder->getConfirmPasswordRef();
		$s .= "<p><label>Confirm password: </label><input type='password' placeholder='Confirm password' name='".$passwordConfirmRef."' value='";
		$s .= self::htmlesc($profile_builder->getData($passwordConfirmRef));
		$s .= "' />";
		$err = $profile_builder->getErrors($passwordConfirmRef);
		if ($err !== null){
			$s .= '<br><span class="error">'.$err.'</span>';
		}
		$s .="</p>\n";

		$avatarRef = $profile_builder->getAvatarRef();
		$s .= "<p><label>Select your avatar: </label><input type='file' name='".$avatarRef."' />";
		$err = $profile_builder->getErrors($avatarRef);
		if ($err !== null){
			$s .= '<br><span class="error">'.$err.'</span>';
		}
		$s .="</p>\n";
		return $s;
	}

	protected function getSignInFormFields(ProfileBuilder $profile_builder){
		$s =  "";

		$usernameRef = $profile_builder->getUserNameRef();
		$s .= "<p><label>Enter your username: </label><input type='text' placeholder='Username' name='".$usernameRef."' value='";
		$s .= self::htmlesc($profile_builder->getData($usernameRef));
		$s .= "' />";
		$err = $profile_builder->getErrors($usernameRef);
		if ($err !== null){
			$s .= '<br><span class="error">'.$err.'</span>';
		}
		$s .="</p>\n";

		$passwordRef = $profile_builder->getPasswordRef();
		$s .= "<p><label>Enter your password: </label><input type='password' placeholder='Password' name='".$passwordRef."' value='";
		$s .= self::htmlesc($profile_builder->getData($passwordRef));
		$s .= "' />";
		$err = $profile_builder->getErrors($passwordRef);
		if ($err !== null){
			$s .= '<br><span class="error">'.$err.'</span>';
		}
		$s .="</p>\n";
		return $s;
	}

	protected function getPlanetCreationFormFields(PlanetBuilder $planet_builder){
		$s =  "";

		$nameRef = $planet_builder->getNameRef();
		$s .= "<p><label>Select your planet's name: </label><input type='text' placeholder=\"Planet's name\" name='".$nameRef."' value='";
		$s .= self::htmlesc($planet_builder->getData($nameRef));
		$s .= "' />";
		$err = $planet_builder->getErrors($nameRef);
		if ($err !== null){
			$s .= '<br><span class="error">'.$err.'</span>';
		}
		$s .="</p>\n";

		$descriptionRef = $planet_builder->getDescriptionRef();
		$s .= "<p><label id='planet_description'>Describe your planet: </label><textarea id='description' placeholder=\"Planet's description\" name='".$descriptionRef."'>";
		$s .= self::htmlesc($planet_builder->getData($descriptionRef));
		$s .= "</textarea>";
		$err = $planet_builder->getErrors($descriptionRef);
		if ($err !== null){
			$s .= '<br><span class="error">'.$err.'</span>';
		}
		$s .="</p>\n";


		$imagerRef = $planet_builder->getImageRef();
		$s .= "<p><label>Select your planet's looks: </label><input type='file' name='".$imagerRef."'>";
		$err = $planet_builder->getErrors($imagerRef);
		if ($err !== null){
			$s .= '<br><span class="error">'.$err.'</span>';
		}
		$s .="</p>\n";
		return $s;
	}

	/**
	 * Certain characters have special significance in HTML, , and should be represented by HTML entities if they are to preserve their meanings. This function returns a string with these conversions made.
	 * @param string $str string to translate
	 * @return string      translated string
	 */
	public static function htmlesc($str) {
		return htmlspecialchars($str,
			ENT_QUOTES
			| ENT_SUBSTITUTE
			| ENT_HTML5,
			'UTF-8');
	}

	public function makeUnknownActionPage() {
		$this->style = "error.css";
		$this->title = "Unknown destination!";
		$this->content = "<p>Our scientists are doing their best to locate you!</p>
		<img class='unknown_page' src='images/unknown_page.png' alt='unknown_page'>
		";

	}

	public function makeUnAuthorisedAccessPage() {
		$this->style = "error.css";
		$this->title = "That's not yours!";
		$this->content = "<p>GET OFF MY LAWN!</p>
		<img class='notAllowed' src='images/notAllowed.png' alt='notAllowed'>
		";

	}

	/**
	 * Generates an unexpected error page. Optionally can take the exception that
	 *  caused the parameter error, but does not do it at the moment.
	 * @param Exception $e
	 */
	public function makeUnexpectedErrorPage(Exception $e=null) {
		$this->style = "error.css";
		$this->title = "Error! something went wrong!";
		$this->content = "<p>We have no idea what happened!</p>
		<img class='unexpected' src='images/unexpected.png' alt='unexpected'>
		<pre>" . var_export($e) . "</pre>";
	}

	public function makeUnknownProfilePage() {
		$this->style = "error.css";
		$this->title = "This person escaped our custody";
		$this->content = "<img class='wrong_profile' src='images/wrong_profile.png' alt='wrong_profile'>";
	}

	public function makeUnknownPlanetPage() {
		$this->style = "error.css";
		$this->title = "This planet doesn't exist";
		$this->content = "<img class='explosion' src='images/planet_explosion.png' alt='planet_explosion'>";
	}

	/******************************************************************************/
	/* Rendering of the page                                                      */
	/******************************************************************************/
	public function render() {
		if ($this->title === null || $this->content === null) {
			$this->makeUnexpectedErrorPage();
		}
		include("Squelette.php");

	}
}
?>
