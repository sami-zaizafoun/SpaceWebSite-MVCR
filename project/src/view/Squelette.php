<!DOCTYPE html>
<html lang="en">
	<head>
		<title> Planet Controller </title>
		<meta charset="UTF-8" />
		<link rel="stylesheet" href="skins/screen.css" />
		<link rel="stylesheet" href="skins/planets.css" />
		<link rel="stylesheet" href="skins/media.css" />
		<link rel="stylesheet" href="skins/signUp.css" />
		<script src="js/script.js"></script>
		<style>
			<?php echo $this->style; ?>
		</style>
	</head>

	<body>
		<div class="pageHead">
			<ul>
				<li>
					<figure class = "rotatingPlanets">
						<a href="#sun2"><img id="sun" src="images/planets/sun.png" alt="sun"></a>
						<figcaption>The Sun</figcaption>
					</figure>
				</li>

				<li>
					<figure class = "rotatingPlanets">
						<a href="#mercury2"><img id="mercury" src="images/planets/mercury.gif" alt="mercury"></a>
						<figcaption>Mercury</figcaption>
					</figure>
				</li>

				<li>
					<figure class = "rotatingPlanets">
						<a href="#venus2"><img id="venus" src="images/planets/venus.gif" alt="Venus"></a>
						<figcaption>Venus</figcaption>
					</figure>
				</li>

				<li>
					<figure class = "rotatingPlanets">
						<a href="#venus2"><img  id="earth" src="images/planets/earth.gif" alt="Earth"></a>
						<figcaption>Earth</figcaption>
					</figure>
				</li>

				<li>
					<figure class = "rotatingPlanets">
						<a href="#mars2"><img id="mars" src="images/planets/mars.gif" alt="Mars"></a>
						<figcaption>Mars</figcaption>
					</figure>
				</li>

				<li>
					<figure class = "rotatingPlanets">
						<a href="#jupiter2"><img id="jupiter" src="images/planets/jupiter.gif" alt="Jupiter"></a>
						<figcaption>Jupiter</figcaption>
					</figure>
				</li>

				<li>
					<figure class = "rotatingPlanets">
						<a href="#saturn2"><img id="saturn" src="images/planets/saturn.gif" alt="Saturn"></a>
						<figcaption>Saturn</figcaption>
					</figure>
				</li>

				<li>
					<figure class = "rotatingPlanets">
						<a href="#uranus2"><img id="uranus" src="images/planets/uranus.gif" alt="Uranus"></a>
						<figcaption>Uranus</figcaption>
					</figure>
				</li>

				<li>
					<figure class = "rotatingPlanets">
						<a href="#neptune2"><img id="neptune" src="images/planets/neptune.gif" alt="Neptune"></a>
						<figcaption>Neptune</figcaption>
					</figure>
				</li>

				<li>
					<figure class = "rotatingPlanets">
						<a href="#pluto2"><img id="pluto" src="images/planets/pluto.gif" alt="Pluto"></a>
						<figcaption>Pluto</figcaption>
					</figure>
				</li>
			</ul>
		</div>

		<nav class="menu">
			<ul>
				<?php
					foreach ($this->getMenu() as $text => $link) {
						echo "<li><a href=\"$link\">$text</a></li>";
					}
				?>
			</ul>
		</nav>

		<main>
			<h1><?php echo $this->title; ?></h1>
			<?php echo $this->content; ?>
		</main>

		<button onclick="topFunction()" id="myBtn" title="Go to top">Top</button>

	</body>

</html>
