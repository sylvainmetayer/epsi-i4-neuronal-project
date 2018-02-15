<?php

namespace NeuralNetwork\Tests;

use NeuralNetwork\Example;
use NeuralNetwork\ExampleSet;

use MLException\InconsistentDataException;
use PHPUnit\Framework\TestCase;

class ExampleSetTest extends TestCase {

	public function testInvalidElementType(){		
		$this->expectException(\InvalidArgumentException::class);

		$exSet = new ExampleSet(3, 4);
		$exSet[] = ['input'=>[0,0,0,0], 'target'=> '00000'];
	}
	
	public function testTargetSizeCheck(){		
		$this->expectException(InconsistentDataException::class);

		$exSet = new ExampleSet(3, 4);
		$exSet[] = new Example("0000", [0,0,0,0]);
	}

	public function testInputSizeCheck(){		
		$this->expectException(InconsistentDataException::class);

		$exSet = new ExampleSet(4, 3);
		$exSet[] =  new Example("000", [0,0,0]);
	}
}