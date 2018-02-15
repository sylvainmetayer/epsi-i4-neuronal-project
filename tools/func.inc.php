<?php
mb_internal_encoding('UTF-8');
function writeUTF8File($filename,$content) { 
	$f=fopen($filename,"w+"); 
	# Now UTF-8 - Add byte order mark 
	//fwrite($f, pack("CCC",0xef,0xbb,0xbf)); 
	fwrite($f,$content); 
	fclose($f); 
} 

function createPaths(array $paths){
	foreach ($paths as $path) {
		if(!file_exists($path)){
			@mkdir($path, 0777, true);
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

	imagejpeg($img,"img/$name.jpg");

	return $name;
}