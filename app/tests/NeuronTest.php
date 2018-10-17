<?php

namespace NeuralNetwork\Tests;

use NeuralNetwork\Neuron;

use PHPUnit\Framework\TestCase;

class NeuronTest extends TestCase {
	
	public function testSize(){
		$n = new Neuron(10);
		
		$this->assertEquals(10, count($n->getWeights()));
	}
}