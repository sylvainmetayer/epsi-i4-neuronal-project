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

	public function testClass(){

		$a =1;
		$b=2;
		$c=3;
		
		$exSet = new ExampleSet(1, 4);

		$exSet[0] = new Example("a", [0,0,0,$a]);
		$exSet[1] = new Example("a", [0,0,$a,$a]);
		$exSet[2] = new Example("a", [0,$a,$a,$a]);
		$exSet[3] = new Example("a", [$a,$a,$a,$a]);

		$exSet[4] = new Example("b", [0,0,0,$b]);
		$exSet[5] = new Example("b", [0,0,$b,$b]);
		$exSet[6] = new Example("b", [0,$b,$b,$b]);
		$exSet[7] = new Example("b", [$b,$b,$b,$b]);

		$exSet[8] = new Example("c", [0,0,0,$c]);
		$exSet[9] = new Example("c", [0,0,$c,$c]);
		$exSet[10] = new Example("c", [0,$c,$c,$c]);
		$exSet[11] = new Example("c", [$c,$c,$c,$c]);


		$this->assertContains("a", $exSet->getClasses());
		$this->assertContains("b", $exSet->getClasses());
		$this->assertContains("c", $exSet->getClasses());

		$this->assertCount(3, $exSet->getClasses());
	}
}