<?php
use GigaZone\Svg\Svg;
require_once realpath('../../../../../../vendor/autoload.php');
if(!isset($_GET['debug']) || filter_var($_GET['debug'], FILTER_VALIDATE_BOOLEAN) === false) {
    Svg::header();
}
$content = file_get_contents(__DIR__ . '/diamond.svg');
Svg::fillColor(isset($_GET['bg']) ? $_GET['bg'] : '', '000000', $content);
die($content);
