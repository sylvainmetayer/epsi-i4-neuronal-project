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

	public function testClassesList(){

		$exSet = new ExampleSet(1, 4);

		$exSet[] = new Example("a", [0,0,0,0]);
		$exSet[] = new Example("a", [0,0,0,0]);
		$exSet[] = new Example("a", [0,0,0,0]);
		$exSet[] = new Example("a", [0,0,0,0]);

		$exSet[] = new Example("b", [0,0,0,0]);
		$exSet[] = new Example("b", [0,0,0,0]);
		$exSet[] = new Example("b", [0,0,0,0]);
		$exSet[] = new Example("b", [0,0,0,0]);

		$exSet[] = new Example("c", [0,0,0,0]);
		$exSet[] = new Example("c", [0,0,0,0]);
		$exSet[] = new Example("c", [0,0,0,0]);
		$exSet[] = new Example("c", [0,0,0,0]);

		$this->assertContains("a", $exSet->getClasses());
		$this->assertContains("b", $exSet->getClasses());
		$this->assertContains("c", $exSet->getClasses());

		$this->assertCount(3, $exSet->getClasses());
	}
}