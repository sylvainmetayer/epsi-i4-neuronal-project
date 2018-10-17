<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../../vendor/autoload.php';

use NeuralNetwork\Network;
use NeuralNetwork\ExampleSet;
use NeuralNetwork\Example;
use NeuralNetwork\Trainnig;

$network = Network::load('../.cache/network.save');

if(isset($_POST['input']) && preg_match("#^(0|1){256}$#", $_POST['input'])){

	$inputChain = $_POST['input'];

	$letters = array_slice(scandir('../.cache/gameset'), 2);
	$bin = $network->answer(str_split($inputChain));
	$index = bindec($bin);

	echo isset($letters[$index]) ? $letters[$index] : '🤔';
} else {
	var_dump($network);
}

