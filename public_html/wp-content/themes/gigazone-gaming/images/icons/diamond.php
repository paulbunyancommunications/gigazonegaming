<?php
use GigaZone\Svg\Svg;
require_once '../../../../../vendor/autoload.php';
Svg::header();
$content = file_get_contents(__DIR__ . '/diamond.svg');
Svg::fillColor(isset($_GET['bg']) ? $_GET['bg'] : '', '000000', $content);
die($content);
