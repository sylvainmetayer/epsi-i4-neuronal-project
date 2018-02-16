<?php

require 'vendor/autoload.php';

use NeuralNetwork\Network;
use NeuralNetwork\ExampleSet;
use NeuralNetwork\Example;
use NeuralNetwork\Trainnig;

$network = new Network(3,15);
$examples = new ExampleSet(3, 15);
$letters = json_decode(file_get_contents("inputs.json"), true);
foreach ($letters as $key => $value) {
	$examples[] = new Example($key, $value);
}

$trainnig = new Trainnig($examples);

$network->print();

for ($i=0,  $successRate=0; $i < 100 && $successRate < 100; $i++) {

	$successRate = $trainnig->runWorkout($network);
	
	echo "$successRate % \n";
}

$trainnig->test($letters, $network);

$network->print();
//var_dump($letters);

