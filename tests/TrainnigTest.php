<?php

namespace NeuralNetwork\Tests;

use NeuralNetwork\Network;
use NeuralNetwork\ExampleSet;
use NeuralNetwork\Example;
use NeuralNetwork\Trainnig;


use PHPUnit\Framework\TestCase;

class TrainnigTest extends TestCase {
	
	public function testConformanceExercise(){
		
		$network = new Network(3,15);
		$examples = new ExampleSet(3, 15);
		
		$letters = [
			"000" => [ 1,1,1,1,0,1,1,0,1,1,0,1,1,1,1 ],
			"001" => [ 0,1,0,1,1,0,0,1,0,0,1,0,1,1,1 ],
			"010" => [ 1,1,1,0,0,1,1,1,1,1,0,0,1,1,1 ],
			"011" => [ 1,1,1,0,0,1,1,1,1,0,0,1,1,1,1 ],
			"100" => [ 1,0,1,1,0,1,1,1,1,0,0,1,0,0,1 ],
			"101" => [ 1,1,1,1,0,0,1,1,1,0,0,1,1,1,1 ],
			"110" => [ 1,1,1,1,0,0,1,1,1,1,0,1,1,1,1 ],
			"111" => [ 1,1,1,0,0,1,0,1,0,0,1,0,0,1,0 ]
		];

		foreach ($letters as $key => $value) {
			$examples[] = new Example($key, $value);
		}

		$trainnig = new Trainnig($examples);
		$trainnig->run($network, 50);

		$fail = false;
		foreach ($letters as $letter => $input) {
			if( strval($letter) !== $network->answer($input)){
				$fail=true;
			}
		}

		$this->assertEquals(false, $fail);
	}
}