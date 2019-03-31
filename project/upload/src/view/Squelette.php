<!DOCTYPE html>
<html lang="en">
	<head>
		<title> Planet Controller </title>
		<meta charset="UTF-8" />
		<link rel="ICON" href="images/earthIco.png" type="image/ico" />
		<link rel="stylesheet" type="text/css" href="style/main.css" />
		<link rel="stylesheet" type="text/css" href="style/planets.css" />
		<link rel="stylesheet" type="text/css" href="style/<?php echo $this->style;?>" />
		<script src="js/script.js"></script>
	</head>

	<body>
		<div class="pageHead">
			<ul>
				<li>
					<figure class = "rotatingPlanets">
						<figcaption class="planet_captions">The Sun</figcaption>
						<?php echo "<a href='".$this->router->planetPage('sun')."'>"?><img id="sun" src="images/menu/sun.png" alt="sun"></a>
					</figure>
				</li>

				<li>
					<figure class = "rotatingPlanets">
						<figcaption class="planet_captions">Mercury</figcaption>
						<?php echo "<a href='".$this->router->planetPage('mercury')."'>"?><img id="mercury" src="images/menu/mercury.gif" alt="mercury"></a>
					</figure>
				</li>

				<li>
					<figure class = "rotatingPlanets">
						<figcaption class="planet_captions">Venus</figcaption>
						<?php echo "<a href='".$this->router->planetPage('venus')."'>"?><img id="venus" src="images/menu/venus.gif" alt="Venus"></a>
					</figure>
				</li>

				<li>
					<figure class = "rotatingPlanets">
						<figcaption class="planet_captions">Earth</figcaption>
						<?php echo "<a href='".$this->router->planetPage('earth')."'>"?><img  id="earth" src="images/menu/earth.gif" alt="Earth"></a>
					</figure>
				</li>

				<li>
					<figure class = "rotatingPlanets">
						<figcaption class="planet_captions">Mars</figcaption>
						<?php echo "<a href='".$this->router->planetPage('mars')."'>"?><img id="mars" src="images/menu/mars.gif" alt="Mars"></a>
					</figure>
				</li>

				<li>
					<figure class = "rotatingPlanets">
						<figcaption class="planet_captions">Jupiter</figcaption>
						<?php echo "<a href='".$this->router->planetPage('jupiter')."'>"?><img id="jupiter" src="images/menu/jupiter.gif" alt="Jupiter"></a>
					</figure>
				</li>

				<li>
					<figure class = "rotatingPlanets">
						<figcaption class="planet_captions">Saturn</figcaption>
						<?php echo "<a href='".$this->router->planetPage('saturn')."'>"?><img id="saturn" src="images/menu/saturn.gif" alt="Saturn"></a>
					</figure>
				</li>

				<li>
					<figure class = "rotatingPlanets">
						<figcaption class="planet_captions">Uranus</figcaption>
						<?php echo "<a href='".$this->router->planetPage('uranus')."'>"?><img id="uranus" src="images/menu/uranus.gif" alt="Uranus"></a>
					</figure>
				</li>

				<li>
					<figure class = "rotatingPlanets">
						<figcaption class="planet_captions">Neptune</figcaption>
						<?php echo "<a href='".$this->router->planetPage('neptune')."'>"?><img id="neptune" src="images/menu/neptune.gif" alt="Neptune"></a>
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

		<?php if ($this->feedback !== '') { ?>
			<div class="feedback"><?php echo $this->feedback; ?></div>
		<?php } ?>

		<main>
			<h1><?php echo $this->title; ?></h1>
			<div class= "content">
				<?php echo $this->content; ?>
			</div>
		</main>

		<button onclick="topFunction()" id="myBtn" title="Go to top">Top</button>

	</body>

</html>
