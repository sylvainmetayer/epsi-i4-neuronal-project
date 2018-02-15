<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'func.inc.php';

createPaths(["../.cache/img","../.cache/gameset"]);

if(isset($_POST['expected']) && !empty($_POST['input'])){
	$saveFile = "../.cache/gameset/".$_POST['expected'];

	$saveFile = htmlentities($saveFile, ENT_COMPAT,'UTF-8', true);

	$gameset = file_exists($saveFile) ? explode("\n", file_get_contents($saveFile)) : [];

	if(!in_array($_POST['input'],$gameset)){
		http_response_code(201);
		
		array_push($gameset, $_POST['input']);
		file_put_contents($saveFile, implode("\n", $gameset));
		echo './.cache/img/'.makeImage($_POST['input']).'.jpg';
		
	} else {
		http_response_code(200);
	}
} else {
	http_response_code(400);
	var_dump($_GET);
	var_dump($_POST);
}
