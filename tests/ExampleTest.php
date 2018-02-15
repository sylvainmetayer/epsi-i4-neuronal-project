<?php

namespace NeuralNetwork\Tests;

use NeuralNetwork\Example;

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase {
	
	public function testSave(){
		$ex = new Example("A", [0,0,0,0,0,0]);

		$this->assertEquals("A", $ex->getTarget());
		$this->assertEquals([0,0,0,0,0,0], $ex->getInput());
	}
}