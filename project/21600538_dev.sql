-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 31, 2019 at 03:05 PM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `21600538_dev`
--

-- --------------------------------------------------------

--
-- Table structure for table `planets`
--

CREATE TABLE `planets` (
  `id` int(11) NOT NULL,
  `name` varchar(256) CHARACTER SET utf8 NOT NULL,
  `description` varchar(1024) CHARACTER SET utf8 NOT NULL,
  `image` varchar(256) CHARACTER SET utf8 NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `planets`
--

INSERT INTO `planets` (`id`, `name`, `description`, `image`, `user_id`) VALUES
(11, 'Sun', 'The Sun is the star at the center of the Solar System. It is a nearly perfect sphere of hot plasma, with internal convective motion that generates a magnetic field via a dynamo process. It is by far the most important source of energy for life on Earth. Its diameter is about 1.39 million kilometers (864,000 miles), or 109 times that of Earth, and its mass is about 330,000 times that of Earth. It accounts for about 99.86% of the total mass of the Solar System. Roughly three quarters of the Sun\'s mass consists of hydrogen (~73%); the rest is mostly helium (~25%), with much smaller quantities of heavier elements, including oxygen, carbon, neon, and iron.', 'images/planets/sun.png', 19),
(12, 'Mercury', 'Mercury is the smallest and innermost planet in the Solar System. Its orbital period around the Sun of 87.97 days is the shortest of all the planets in the Solar System. It is named after the Roman deity Mercury, the messenger of the gods.\r\n\r\nLike Venus, Mercury orbits the Sun within Earth\'s orbit as an inferior planet, and never exceeds 28° away from the Sun when viewed from Earth. This proximity to the Sun means the planet can only be seen near the western or eastern horizon during the early evening or early morning. At this time it may appear as a bright star-like object, but is often far more difficult to observe than Venus. The planet telescopically displays the complete range of phases, similar to Venus and the Moon, as it moves in its inner orbit relative to Earth, which reoccurs over the so-called synodic period approximately every 116 days.', 'images/planets/mercury.png', 19),
(13, 'Venus', 'Venus is the second planet from the Sun, orbiting it every 224.7 Earth days. It has the longest rotation period (243 days) of any planet in the Solar System and rotates in the opposite direction to most other planets (meaning the Sun would rise in the west and set in the east). It does not have any natural satellites. It is named after the Roman goddess of love and beauty. It is the second-brightest natural object in the night sky after the Moon, reaching an apparent magnitude of −4.6 – bright enough to cast shadows at night and, rarely, visible to the naked eye in broad daylight. Orbiting within Earth\'s orbit, Venus is an inferior planet and never appears to venture far from the Sun; its maximum angular distance from the Sun (elongation) is 47.8°.', 'images/planets/venus.png', 19),
(14, 'Earth', 'Earth is the third planet from the Sun and the only astronomical object known to harbor life. According to radiometric dating and other sources of evidence, Earth formed over 4.5 billion years ago. Earth\'s gravity interacts with other objects in space, especially the Sun and the Moon, Earth\'s only natural satellite. Earth revolves around the Sun in 365.26 days, a period known as an Earth year. During this time, Earth rotates about its axis about 366.26 times.\r\n\r\nEarth\'s axis of rotation is tilted with respect to its orbital plane, producing seasons on Earth. The gravitational interaction between Earth and the Moon causes ocean tides, stabilizes Earth\'s orientation on its axis, and gradually slows its rotation. Earth is the densest planet in the Solar System and the largest of the four terrestrial planets.', 'images/planets/earth.png', 19),
(15, 'Mars', 'Mars is the fourth planet from the Sun and the second-smallest planet in the Solar System after Mercury. In English, Mars carries a name of the Roman god of war, and is often referred to as the \"Red Planet\" because the reddish iron oxide prevalent on its surface gives it a reddish appearance that is distinctive among the astronomical bodies visible to the naked eye. Mars is a terrestrial planet with a thin atmosphere, having surface features reminiscent both of the impact craters of the Moon and the valleys, deserts, and polar ice caps of Earth.\r\n\r\nThe rotational period and seasonal cycles of Mars are likewise similar to those of Earth, as is the tilt that produces the seasons. Mars is the site of Olympus Mons, the largest volcano and second-highest known mountain in the Solar System, and of Valles Marineris, one of the largest canyons in the Solar System. The smooth Borealis basin in the northern hemisphere covers 40% of the planet and may be a giant impact feature. Mars has two moons, Phobos and Deimos, whi', 'images/planets/mars.png', 19),
(16, 'Jupiter', 'Jupiter is the fifth planet from the Sun and the largest in the Solar System. It is a giant planet with a mass one-thousandth that of the Sun, but two-and-a-half times that of all the other planets in the Solar System combined. Jupiter and Saturn are gas giants; the other two giant planets, Uranus and Neptune, are ice giants. Jupiter has been known to astronomers since antiquity. It is named after the Roman god Jupiter. When viewed from Earth, Jupiter can reach an apparent magnitude of −2.94, bright enough for its reflected light to cast shadows, and making it on average the third-brightest natural object in the night sky after the Moon and Venus.', 'images/planets/jupiter.png', 19),
(17, 'Saturn', 'Saturn is the sixth planet from the Sun and the second-largest in the Solar System, after Jupiter. It is a gas giant with an average radius about nine times that of Earth. It has only one-eighth the average density of Earth, but with its larger volume Saturn is over 95 times more massive. Saturn is named after the Roman god of agriculture; its astronomical symbol (♄) represents the god\'s sickle.', 'images/planets/saturn.png', 19),
(18, 'Uranus', 'Uranus (from the Latin name \"Ūranus\" for the Greek god Οὐρανός) is the seventh planet from the Sun. It has the third-largest planetary radius and fourth-largest planetary mass in the Solar System. Uranus is similar in composition to Neptune, and both have bulk chemical compositions which differ from that of the larger gas giants Jupiter and Saturn. For this reason, scientists often classify Uranus and Neptune as \"ice giants\" to distinguish them from the gas giants. Uranus\' atmosphere is similar to Jupiter\'s and Saturn\'s in its primary composition of hydrogen and helium, but it contains more \"ices\" such as water, ammonia, and methane, along with traces of other hydrocarbons. It is the coldest planetary atmosphere in the Solar System, with a minimum temperature of 49 K (−224 °C; −371 °F), and has a complex, layered cloud structure with water thought to make up the lowest clouds and methane the uppermost layer of clouds. The interior of Uranus is mainly composed of ices and rock.', 'images/planets/uranus.png', 19),
(19, 'Neptune', 'Neptune is the eighth and farthest known planet from the Sun in the Solar System. In the Solar System, it is the fourth-largest planet by diameter, the third-most-massive planet, and the densest giant planet. Neptune is 17 times the mass of Earth and is slightly more massive than its near-twin Uranus, which is 15 times the mass of Earth and slightly larger than Neptune. Neptune orbits the Sun once every 164.8 years at an average distance of 30.1 AU (4.5 billion km). It is named after the Roman god of the sea and has the astronomical symbol ♆, a stylised version of the god Neptune\'s trident.', 'images/planets/neptune.png', 19),
(22, 'Titan', 'Titan is the largest moon of Saturn and the second-largest natural satellite in the Solar System. It is the only moon known to have a dense atmosphere, and the only object in space, other than Earth, where clear evidence of stable bodies of surface liquid has been found.\r\n\r\nTitan is the sixth gravitationally rounded moon from Saturn. Frequently described as a planet-like moon, Titan is 50% larger than Earth\'s moon and 80% more massive. It is the second-largest moon in the Solar System after Jupiter\'s moon Ganymede, and is larger than the planet Mercury, but only 40% as massive. Discovered in 1655 by the Dutch astronomer Christiaan Huygens, Titan was the first known moon of Saturn, and the sixth known planetary satellite (after Earth\'s moon and the four Galilean moons of Jupiter). Titan orbits Saturn at 20 Saturn radii. From Titan\'s surface, Saturn subtends an arc of 5.09 degrees and would appear 11.4 times larger in the sky than the Moon from Earth. ', 'images/planets/titan.png', 26),
(23, 'Proxima Centauri b', 'Proxima Centauri b (also called Proxima b or Alpha Centauri Cb) is an exoplanet orbiting in the habitable zone of the red dwarf star Proxima Centauri, which is the closest star to the Sun and part of a triple star system. It is located about 4.2 light-years (1.3 parsecs, 40 trillion km, or 25 trillion miles) from Earth in the constellation of Centaurus, making it the closest known exoplanet to the Solar System.\r\n\r\nProxima Centauri b orbits the star at a distance of roughly 0.05 AU (7,500,000 km; 4,600,000 mi) with an orbital period of approximately 11.2 Earth days, and has an estimated mass of at least 1.3 times that of the Earth. Its habitability has not been established, though it is unlikely to be habitable since the planet is subject to stellar wind pressures of more than 2,000 times those experienced by Earth from the solar wind.', 'images/planets/Proxima Centauri b.png', 27),
(24, 'Kepler-442b', 'Kepler-442b(also known by its Kepler object of interest designation KOI-4742.01) is a confirmed near-Earth-sized exoplanet, likely rocky, orbiting within the habitable zone of the K-type main-sequence star Kepler-442, about 1,206 light-years (370 parsecs), from Earth in the constellation Lyra. The planet was discovered by NASA\'s Kepler spacecraft using the transit method, in which the dimming effect that a planet causes as it crosses in front of its star is measured. NASA announced the confirmation of the exoplanet on 6 January 2015.', 'images/planets/Kepler-442b.png', 28);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(256) NOT NULL,
  `email` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  `avatar` varchar(256) NOT NULL,
  `status` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `avatar`, `status`) VALUES
(19, 'z9sami7z', 'sami.zaizafoun@gmail.com', '$2y$10$OAX1em2I.VDJ6YA8L.yJA.L.Rt4KceLvT7NgiHsf4PrSuW5WmCqG2', 'images/users/index.png', 'admin'),
(26, 'Jack', 'jack.sparrow@gmail.com', '$2y$10$JCFfQbkwML1rG/HDYNiJ1eF.x/9vmMC.BBr6fuHurSQdh4ix1OrwK', 'images/users/jack.png', 'user'),
(27, 'Barbra', 'barbra.baggins@gmail.com', '$2y$10$4UUPaq0BZGzBVmPCSQvPJ.zyrwTR2hqXzCgL4CQcgDkOkT74o86DW', 'images/users/barbra.png', 'user'),
(28, 'Bob', 'sponge.bob@gmail.com', '$2y$10$fQcTeWeatO3KFNRdabs2AeoEuOFqHG87mTv5p4fySSCkjV0P6MOmi', 'images/users/bob.png', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `planets`
--
ALTER TABLE `planets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `user_id_2` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `planets`
--
ALTER TABLE `planets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `planets`
--
ALTER TABLE `planets`
  ADD CONSTRAINT `planets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
