<?php

namespace NeuralNetwork;

use \MLException\InconsistentDataException;

class ExampleSet extends \Utils\ArrayObj {

	private $targetSize;

	private $inputSize;
	
	public function __construct(int $targetSize, int $inputSize){
		$this->targetSize=$targetSize;
		$this->inputSize=$inputSize;
	}

	public function offsetSet($offset, $example) {
		if( $example instanceof Example ){
			if (strlen($example->target) == $this->targetSize 
				&& count($example->input) == $this->inputSize){
				
				return parent::offsetSet($offset, $example);
			} else {
				throw new InconsistentDataException("This example does not correspond to the set", 1);
			}
		}
		else {
			throw new \InvalidArgumentException("This set only accepts integers", 1);
			
		}
	}
}