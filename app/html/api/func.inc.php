<?php
function createPaths(array $paths){
	foreach ($paths as $path) {
		if(!file_exists($path)){
			mkdir($path, 0777, true);
		}
	}
}

function makeImage($str){
	$size = sqrt(strlen($str));

	$img = imagecreatetruecolor($size,$size);
	$white = imagecolorallocate($img, 255, 255, 255);
	$black = imagecolorallocate($img, 0, 0, 0);

	for ($y=0; $y < $size; $y++) { 
		for ($x=0; $x < $size; $x++) {
			$i = intval($x + ($size)*$y);
			$color = intval($str[$i]) > 0 ? $black : $white;
			imagesetpixel($img, $x, $y, $color);
		}
	}

	$name = uniqid();

	imagepng($img,__DIR__ . "/../.cache/img/$name.png");

	return $name;
}