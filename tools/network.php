<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../vendor/autoload.php';

use NeuralNetwork\Network;
use NeuralNetwork\ExampleSet;
use NeuralNetwork\Example;
use NeuralNetwork\Trainnig;

$network = Network::load('network.save');

if (!empty($_POST['input'])) {

	$letters = array_slice(scandir('./gameset'), 2);
	$bin = $network->answer(str_split($_POST['input']));
	$index = bindec($bin);

	echo isset($letters[$index]) ? $letters[$index] : '🤔';
} else {
	var_dump($network);
}

