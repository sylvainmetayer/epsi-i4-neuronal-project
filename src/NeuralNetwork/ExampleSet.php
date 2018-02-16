<?php

namespace NeuralNetwork;

use \MLException\InconsistentDataException;
use \Utils\ArrayObj;

class ExampleSet extends ArrayObj {

	private $targetSize;

	private $inputSize;
	
	private $classes=[];
	
	public function __construct(int $targetSize, int $inputSize){
		$this->targetSize=$targetSize;
		$this->inputSize=$inputSize;
	}

	public function offsetSet($offset, $example) {
		if( $example instanceof Example ){
			if (strlen($example->getTarget()) == $this->targetSize 
				&& count($example->getInput()) == $this->inputSize){

				if (!in_array($example->getTarget(), $this->classes)) {
					$this->classes[] = $example->getTarget();
				}
				
				return parent::offsetSet($offset, $example);
			} else {
				throw new InconsistentDataException("This example does not correspond to the set", 1);
			}
		}
		else {
			throw new \InvalidArgumentException("This set only accepts integers", 1);
			
		}
	}

	public function getClasses(){
		return $this->classes;
	}
}