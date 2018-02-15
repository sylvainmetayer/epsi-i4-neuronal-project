<?php

require __DIR__ .'/../vendor/autoload.php';

use NeuralNetwork\Network;
use NeuralNetwork\ExampleSet;
use NeuralNetwork\Example;
use NeuralNetwork\Trainnig;

$letters = array_slice(scandir(__DIR__.'/.cache/gameset'), 2);

$numberClass = count($letters);
$nBits = ceil(log($numberClass,2));

$network = new Network($nBits,256);
$examples = new ExampleSet($nBits, 256);

$test = [];

echo "\n\033[32mTrainnig for dataset :  \033[0m \n\n";

foreach ($letters as $key => $letter) {

	$bin = sprintf( "%0".$nBits."d", decbin( $key ));
	$gamesets =  explode("\n", file_get_contents(__DIR__."/.cache/gameset/$letter"));

	echo "$bin => $letter \n";


	foreach ($gamesets as $gameset) {

		$input = str_split($gameset);

		$test[$bin] = $input;

		$examples[] = new Example($bin, $input);
	}

}


echo "\033[32mTrainning begin, please wait ... \033[0m \n";


$trainnig = new Trainnig($examples);
$trainnig->run($network, 1000);

echo "\n\033[32mTrainning is over : \033[0m \n\n";


$trainnig->test($test, $network);

$network->save(__DIR__."/.cache/network.save");

