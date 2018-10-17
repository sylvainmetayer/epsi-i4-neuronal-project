<?php

namespace NeuralNetwork\Tests;

use NeuralNetwork\Network;

use PHPUnit\Framework\TestCase;

class NetworkTest extends TestCase {
	
	public function testSize(){
		
		$n = new Network(5,4);

		$this->assertEquals(5, strlen($n->answer([0,0,0,0])));
	}
}