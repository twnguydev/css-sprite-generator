<?php
/**
 * Sprite/CSS/HTML Generator in PHP/CLI
 * sprite.php
 * Tanguy Gibrat
 * Epitech 2023
*/

require("./generator/three.php");
require("./generator/search_images.php");
require("./generator/css_generator.php");
require("./generator/html_generator.php");
require("./generator/sprite_generator.php");
require("./generator/filters.php");

filterSprite($argv);
?>