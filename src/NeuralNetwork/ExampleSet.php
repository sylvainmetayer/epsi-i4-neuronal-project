<?php

namespace NeuralNetwork;

class ExampleSet extends \Utils\ArrayObj {

	private $targetSize;

	private $inputSize;
	
	public function __construct(int $targetSize, int $inputSize){
		$this->targetSize=$targetSize;
		$this->inputSize=$inputSize;
	}

	public function offsetSet($offset, $example) {
		if( $example instanceof Example 
			&& strlen($example->target) == $this->targetSize 
			&& count($example->input) == $this->inputSize){
			return parent::offsetSet($offset, $example);
		}
			
	}
}