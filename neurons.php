<?php

require 'classes.php';

$network = new Network(3,15);
$examples = new ExampleSet(3, 15);
$letters = json_decode(file_get_contents("inputs.json"), true);
foreach ($letters as $key => $value) {
	$examples[] = new Example($key, $value);
}

$trainnig = new Trainnig($examples);

$network->print();
$trainnig->run($network, 100);

if(isset($argv) && !empty($argv[1])){
	$network->save($argv[1]);
}

$trainnig->test($letters, $network);

//var_dump($letters);

