<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'func.inc.php';

createPaths(["../.cache/img","../.cache/gameset"]);

if(isset($_POST['expected']) && preg_match("#^[a-z0-9]*$#i", $_POST['expected'])  ) {

	$expectedChain = $_POST['expected'];
	
	$saveFile = "../.cache/gameset/$expectedChain";

	if(isset($_POST['input']) && preg_match("#^(0|1){256}$#", $_POST['input'])){

		$inputChain = $_POST['input'];
		
		$gameset = file_exists($saveFile) ? explode("\n", file_get_contents($saveFile)) : [];

		if(!file_exists($saveFile) && count(scandir("../.cache/gameset"))-2 >= 4 ) {
			http_response_code(423);
		} else {
			if(!in_array($inputChain,$gameset) && count($gameset) < 5){
				http_response_code(201);
				
				array_push($gameset, $inputChain);
				file_put_contents($saveFile, implode("\n", $gameset));
				echo './.cache/img/'.makeImage($inputChain).'.png';
				
			} else {
				http_response_code(200);
			}
		}

	
	}
	
	if(!empty($_POST['delete']) && preg_match("#^[0-9]*$#i", $_POST['delete']) && file_exists($saveFile)){
		
		$deleteItem = $_POST['delete'];

		$gameset = explode("\n", file_get_contents($saveFile));
		unset( $gameset[$deleteItem]);
		file_put_contents($saveFile, implode("\n", $gameset));

		http_response_code(200);
	}

} else {
	http_response_code(400);
	var_dump($_GET);
	var_dump($_POST);
}
