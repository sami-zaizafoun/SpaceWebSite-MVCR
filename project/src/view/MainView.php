<?php

require_once("Router.php");

class MainView {

	protected $router;
	protected $style;
	protected $title;
	protected $content;

	public function __construct(Router $router, $feedback) {
		$this->feedback = $feedback;
		$this->router = $router;
		$this->style = "";
		$this->title = null;
		$this->content = null;
	}

	/******************************************************************************/
	/* Méthodes de génération des pages                                           */
	/******************************************************************************/

	public function makeHomePage() {
		$this->title = "Welcome God. The universe is in your hands. Do what you must!";
		$s = "";
		$s .="<p id=\"intro\"> This is our solar system. Everything in here, you can not touch or you will erase our existence.
		But you can always have fun making your own planet in our <br> \"<a id= \"creatRef\" href=\"";
		$s .= $this->router->planetCreationPage();
		$s .="\">Create your own planet</a>\" page.</p>";
		$s .= file_get_contents("content/home.txt");
		$this->content = $s;
	}

	public function signUp(){
		$this->title = "Become a God";
		$s = "";
		$s .= "<div class=\"signUp\">\n";
		$s .= "<form class=\"form\" action=\"MainView.php\" method=\"post\" enctype=\"multipart/form-data\" autocomplete=\"off\">\n";
		$s .= "<input type=\"text\" placeholder=\"User Name\" name=\"username\" required />\n";
		$s .= "<input type=\"email\" placeholder=\"Email\" name=\"email\" required />\n";
		$s .= "<input type=\"password\" placeholder=\"Password\" name=\"password\" autocomplete=\"new-password\" required />\n";
		$s .= "<input type=\"password\" placeholder=\"Confirm Password\" name=\"confirmpassword\" autocomplete=\"new-password\" required />\n";
		$s .= "<div class=\"avatar\"><label>Select your avatar: </label><input type=\"file\" name=\"avatar\" accept=\"image/*\"></div>\n";
		$s .= "<input type=\"submit\" value=\"Register\" name=\"register\" class=\"btn btn-block btn-primary\" />\n";
		$s .= "</form>\n";
		$s .= " </div>\n";
		$this->content= $s;
	}

	public function makePlanetPage($id, Planet $p) {
		$pname = self::htmlesc($p->getName());
		$phistory = self::htmlesc($p->getHistory());
		$pclass = "planet$id";
		$pdatec = self::fmtDate($p->getCreationDate());
		$pdatem = self::fmtDate($p->getModifDate());

		$this->title = "The almighty $pname has been created";
		$s = "";
		$s .= "<p>The origins of $pname are the following: $phistory</p>\n";
		$s .= "<ul>\n";
		$s .= '<li><a href="'.$this->router->planetModifPage($id).'">Modify</a></li>'."\n";
		$s .= '<li><a href="'.$this->router->planetDeletionPage($id).'">Destroy</a></li>'."\n";
		$s .= "</ul>\n";
		$this->content = $s;
	}

	public function makePlanetCreationPage(PlanetBuilder $builder) {
		$this->title = "Add your planet";
		$s = '<form action="'.$this->router->saveCreatedPlanet().'" method="POST">'."\n";
		$s .= self::getFormFields($builder);
		$s .= "<button>Create</button>\n";
		$s .= "</form>\n";
		$this->content = $s;
	}

	public function makePlanetCreatedPage($id) {
		$this->router->POSTredirect($this->router->planetPage($id), "Your planet has been created !");
	}

	public function makePlanetNotCreatedPage() {
		$this->router->POSTredirect($this->router->planetCreationPage(), "Your planet is not worthy of creation yet!");
	}

	public function makePlanetDeletionPage($id, Planet $p) {
		$pname = self::htmlesc($p->getName());

		$this->title = "$pname's destruction page";
		$this->content = "<p>« {$pname} » is about to be destroyed.</p>\n";
		$this->content .= '<form action="'.$this->router->confirmPlanetDeletion($id).'" method="POST">'."\n";
		$this->content .= "<button>Annihilate</button>\n</form>\n";
	}

	public function makePlanetDeletedPage() {
		$this->router->POSTredirect($this->router->allPlanetsPage(), "The planet has been destroyed !");
	}

	public function makePlanetModifPage($id, PlanetBuilder $builder) {
		$this->title = "Planet recreation page";
		$this->content = "<p> Recreate your planet!</p>\n";
		$this->content .= '<form action="'.$this->router->updateModifiedPlanet($id).'" method="POST">'."\n";
		$this->content .= self::getFormFields($builder);
		$this->content .= '<button>Modify</button>'."\n";
		$this->content .= '</form>'."\n";
	}

	public function makePlanetModifiedPage($id) {
		$this->router->POSTredirect($this->router->planetPage($id), "The planet has been modified !");
	}

	public function makePlanetNotModifiedPage($id) {
		$this->router->POSTredirect($this->router->planetModifPage($id), "You've made your planet worse. Try again!");
	}

	public function makeGalleryPage(array $planets) {
		$this->title = "Current universe";
		$this->content .= "<ul class=\"gallery\">\n";
		foreach ($planets as $id=>$p) {
			$this->content .= $this->galleryPlanet($id, $p);
		}
		$this->content .= "</ul>\n";
	}

	public function makeUnknownPlanetPage() {
		$this->title = "Error";
		$this->content = "This planet doesn't exist or it has been destroyed!";
	}

	public function makeUnknownActionPage() {
		$this->title = "Error";
		$this->content = "You're lost in space.";
	}

	/* Génère une page d'erreur inattendue. Peut optionnellement
	 * prendre l'exception qui a provoqué l'erreur
	 * en paramètre, mais n'en fait rien pour l'instant. */
	public function makeUnexpectedErrorPage(Exception $e=null) {
		$this->title = "Error";
		$this->content = "Une erreur inattendue s'est produite." . "<pre>" . var_export($e) . "</pre>";
	}

	/******************************************************************************/
	/* Méthodes utilitaires                                                       */
	/******************************************************************************/

	protected function getMenu() {
		return array(
			"Home" => $this->router->homePage(),
			"Planet library" => $this->router->allPlanetsPage(),
			"Create your own planet" => $this->router->planetCreationPage(),
			"About" => $this->router->homePage(),
			"Sign Up" => $this->router->signUpPage(),
		);
	}

	protected function galleryPlanet($id, $p) {
		$pclass = "planet".$id;
		$res = '<li><a href="'.$this->router->planetPage($id).'">';
		$res .= '<h3>'.self::htmlesc($p->getName()).'</h3>';
		$res .= '<div class="sample '.$pclass.'"></div>';
		$res .= '</a></li>'."\n";
		return $res;
	}

	protected function getFormFields(PlanetBuilder $builder) {
		$nameRef = $builder->getNameRef();
		$s = "";

		$s .= '<p><label>Planet\'s name : <input type="text" name="'.$nameRef.'" value="';
		$s .= self::htmlesc($builder->getData($nameRef));
		$s .= "\" />";
		$err = $builder->getErrors($nameRef);
		if ($err !== null)
			$s .= ' <span class="error">'.$err.'</span>';
		$s .="</label></p>\n";

		$historyRef = $builder->getHistoryRef();
		$s .= '<p><label>Planet\'s history : <input type="text" name="'.$historyRef.'" value="';
		$s .= self::htmlesc($builder->getData($historyRef));
		$s .= '" ';
		$s .= '	/>';
		$err = $builder->getErrors($historyRef);
		if ($err !== null)
			$s .= ' <span class="error">'.$err.'</span>';
		$s .= '</label></p>'."\n";

		$imgRef = $builder->getImgRef();
		$s .= "<div class=\"planetImg\"><label>Select your planet: </label><input type=\"file\" name=\"planetImg\" accept=\"image/*\"></div>";

		return $s;
	}

	protected static function fmtDate(DateTime $date) {
		return "le " . $date->format("Y-m-d") . " à " . $date->format("H:i:s");
	}

	/* Une fonction pour échapper les caractères spéciaux de HTML,
	* car celle de PHP nécessite trop d'options. */
	public static function htmlesc($str) {
		return htmlspecialchars($str,
			/* on échappe guillemets _et_ apostrophes : */
			ENT_QUOTES
			/* les séquences UTF-8 invalides sont
			* remplacées par le caractère �
			* au lieu de renvoyer la chaîne vide…) */
			| ENT_SUBSTITUTE
			/* on utilise les entités HTML5 (en particulier &apos;) */
			| ENT_HTML5,
			'UTF-8');
	}

	/******************************************************************************/
	/* Rendu de la page                                                           */
	/******************************************************************************/
	public function render() {
		if ($this->title === null || $this->content === null) {
			$this->makeUnexpectedErrorPage();
		}
		include("Squelette.php");
		/* On affiche la page.
		 * Ici on pourrait faire des echo, mais simplement fermer
		 * la balise PHP revient au même, et le HTML est plus lisible.
		 * En revanche le code PHP est moins lisible : une autre solution
		 * est de mettre ce squelette dans un fichier à part et
		 * de simplement faire un «include». */

	}
}


?>
